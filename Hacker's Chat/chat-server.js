// Require the packages we will use:
var http = require("http"),
    socketio = require("socket.io"),
    fs = require("fs");

//An array that stores all the users who logged in
let users = [];

//An array that stores all the rooms
let rooms = [];

//The class that stores all the information of a room
var room_number_current = 10000001;
function Room(_creator_socket, _creator, _type, _name, _password = null) {
    //creator_socket and _creator are corresponding
    this.creator_socket = _creator_socket;
    this.creator = _creator;

    this.type = _type;
    this.name = _name;
    this.password = _password;
    this.room_number = room_number_current++;

    //current_users and current_sockets are corresponding
    this.current_users = [];
    this.current_sockets = [];

    //banned__sockets
    this.banned_sockets = [];
}
// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html:
var app = http.createServer(function (req, resp) {
    // This callback runs when a new connection is made to our HTTP server.

    fs.readFile("client.html", function (err, data) {
        // This callback runs when the client.html file has been read from the filesystem.	
        if (err) return resp.writeHead(500);
        resp.writeHead(200);
        resp.end(data);
    });
});
app.listen(3456);

// Do the Socket.IO magic:
var io = socketio.listen(app);
io.sockets.on("connection", function (socket) {
    // This callback runs when a new Socket.IO connection is established.

    //Monitor who disconnects from the server
    socket.on('disconnect', function () {
        //Remove it from "users"
        users.splice(users.indexOf(socket), 1);
        //Remove it from room.current_sockets and room.current_users
        for (var i = 0; i < rooms.length; i++) {
            if (rooms[i] == null) continue;
            const ind = rooms[i].current_sockets.indexOf(socket);
            if (ind > -1) {
                rooms[i].current_sockets.splice(ind, 1);
                rooms[i].current_users.splice(ind, 1);

                //Call all the users in the room to update display info
                for (var j = 0; j < rooms[i].current_sockets.length; j++)
                    rooms[i].current_sockets[j].emit("the_room", { "the_room": emitTheRoom(i) });

                //Call all the users (to update lobby)
                io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });
            }
            //If the creator disconnects from the server, change the admin or delete the room
            if(socket==rooms[i].creator_socket){
                //Pick the user who stays longest in this room as the new Admin
                if(rooms[i].current_users.length>0){
                    rooms[i].creator = rooms[i].current_users[0];
                    rooms[i].creator_socket = rooms[i].current_sockets[0];

                    for (var m = 0; m < rooms[i].current_sockets.length; m++)
                        rooms[i].current_sockets[m].emit("the_room", { "the_room": emitTheRoom(i) });

                    //Call all the users (to update lobby)
                    io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });

                    //Send new admin info
                    rooms[i].creator_socket.emit("you_are_admin", { "admin": [true, rooms[i].current_users] });
                    rooms[i].creator_socket.emit("message_to_client", { message: ["System", "You become the new Administrator of this chat room!"] });
            
                }else{
                    //Delete the room
                    const room_ind = i;
                    const the_room = rooms[room_ind];
            
                    //Substitude the room in room list as null
                    rooms[room_ind] = null;
            
                    //Call all the users (to update lobby)
                    io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });
                }
            }
        }

    });

    socket.on('message_to_server', function (data) {
        // This callback runs when the server receives a new message from the client.
        console.log("message: " + data["message"]); // log it to the Node.JS output
        const the_room = rooms[data['message'][0] - 10000001];
        //Only Send the information to the users in this room
        if (data["message"][3] == "all") {
            for (var i = 0; i < the_room.current_sockets.length; i++) {
                the_room.current_sockets[i].emit("message_to_client", { message: [data["message"][2], data["message"][1]] }) // broadcast the message to other users
            }
        }
        else if (data["message"][3] == "anony") {
            for (var i = 0; i < the_room.current_sockets.length; i++) {
                the_room.current_sockets[i].emit("message_to_client", { message: ["Anonymous", data["message"][1]] }) // broadcast the message to other users
            }
        }else{
            //The user is sending private message to himself
            if(the_room.current_sockets[parseInt(data["message"][3])]==socket){
                socket.emit("message_to_client", { message: ["Yourself", data["message"][1]]});
                return;
            }
            the_room.current_sockets[parseInt(data["message"][3])].emit("message_to_client", { message: [data["message"][2]+"(private)", data["message"][1]] }) // broadcast the message to only one user
            socket.emit("message_to_client", { message: [data["message"][2]+"(private)", data["message"][1]] }) // broadcast the message to only one user
        }
    });

    socket.on('user_login', function (data) {
        // This callback runs when the server receives a new user logged in.
        console.log("user_login: " + data["nickname"]); // log it to the Node.JS output
        // Push the user into the users dictionary
        users.push(socket);
        // Give all the room information to the user
        socket.emit("info_rooms", { "info_rooms": emitRoomInfo() });
    });

    socket.on('room_created', function (data) {
        // This callback runs when a new char room created
        console.log("room_created: " + data["room_created"][1]); // log it to the Node.JS output
        //Create Room object and add it to the array
        if (data["room_created"][0] == "public")
            var new_room = new Room(socket, data["room_created"][3], data["room_created"][0], data["room_created"][1]);
        else
            var new_room = new Room(socket, data["room_created"][3], data["room_created"][0], data["room_created"][1], data["room_created"][2]);
        rooms.push(new_room);
        // Give all the room information to all the user
        io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });
    });

    socket.on('enter_room', function (data) {
        // This callback runs when a user wants to enter a chat room
        console.log("enter_attempt: " + data["enter_room"]); // log it to the Node.JS output
        const room_num = parseInt(data["enter_room"][0].substring(1)) - 10000001;
        const the_room = rooms[room_num];

        //When the user is banned from this room
        if (the_room.banned_sockets.indexOf(socket) != -1) {
            socket.emit("the_room", { "the_room": [false, "Sorry! You are banned from this chat room by Admin."] });
            return;
        }
        if (the_room.type == "public") {
            the_room.current_users.push(data["enter_room"][1]);
            the_room.current_sockets.push(socket);
            //Call all the users in the room to update display info
            for (var i = 0; i < the_room.current_sockets.length; i++)
                the_room.current_sockets[i].emit("the_room", { "the_room": emitTheRoom(room_num) });

            //Call all the users (to update lobby)
            io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });

            the_room.creator_socket.emit("you_are_admin", { "admin": [true, the_room.current_users] });

        }
        //Then the room must be private
        else if (the_room.password == data["enter_room"][2]) {
            the_room.current_users.push(data["enter_room"][1]);
            the_room.current_sockets.push(socket);
            //Call all the users in the room to update display info
            for (var i = 0; i < the_room.current_sockets.length; i++)
                the_room.current_sockets[i].emit("the_room", { "the_room": emitTheRoom(room_num) });

            //Call all the users (to update lobby)
            io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });

            //When a user enters a chat room, send info to admin
            the_room.creator_socket.emit("you_are_admin", { "admin": [true, the_room.current_users] });
        } else {
            socket.emit("the_room", { "the_room": [false, "Sorry! Password incorrect."] });
        }
    });

    socket.on('quit_room', function (data) {
        // This callback runs when a user wants to quit a chat room
        console.log("quit_room: " + data["quit_room"]); // log it to the Node.JS output
        const room_num = data["quit_room"][0];
        const the_room = rooms[room_num];
        //Remove it from room.current_sockets and room.current_users
        const ind = the_room.current_sockets.indexOf(socket);
        the_room.current_sockets.splice(ind, 1);
        the_room.current_users.splice(ind, 1);
        io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });
        //Call all the users in the room to update display info
        for (var i = 0; i < the_room.current_sockets.length; i++)
            the_room.current_sockets[i].emit("the_room", { "the_room": emitTheRoom(room_num) });
        socket.emit("you_are_admin", { "admin": [false] });
        //notify the admin that a user logged out
        if (socket != the_room.creator_socket)
            the_room.creator_socket.emit("you_are_admin", { "admin": [true, the_room.current_users] });
    });

    socket.on('kick_user', function (data) {
        // This callback runs when an admin wants to kick a user
        const room_ind = data["kick"][0];
        const the_room = rooms[room_ind];
        const user_ind = data["kick"][1];

        //Verify
        if (the_room.creator_socket != socket) {
            socket.emit("system_info", { "info": "Failed! You are not the Admin." });
            return;
        }

        // An admin cannot kick out himself
        if (the_room.current_sockets[user_ind] == socket) {
            socket.emit("system_info", { "info": "Failed! You(Admin) cannot kick yourself." });
            return;
        }
        //Put the user out of current_users and current_sockets
        const kicked_user = the_room.current_sockets.splice(user_ind, 1)[0];
        the_room.current_users.splice(user_ind, 1);
        //Notify admin
        socket.emit("system_info", { "info": "Succeeded! You have kicked out this user." });

        //Call all the users in the room to update display info
        for (var i = 0; i < the_room.current_sockets.length; i++)
            the_room.current_sockets[i].emit("the_room", { "the_room": emitTheRoom(room_ind) });

        //Call all the users (to update lobby)
        io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });

        //Let the admin to update admin panel
        socket.emit("you_are_admin", { "admin": [true, the_room.current_users] });

        //Kick the user
        kicked_user.emit("you_are_kicked", { "kicked": true });
    });

    socket.on('ban_user', function (data) {
        // This callback runs when an admin wants to ban a user
        const room_ind = data["ban"][0];
        const the_room = rooms[room_ind];
        const user_ind = data["ban"][1];

        //Verify
        if (the_room.creator_socket != socket) {
            socket.emit("system_info", { "info": "Failed! You are not the Admin." });
            return;
        }

        // An admin cannot ban himself
        if (the_room.current_sockets[user_ind] == socket) {
            socket.emit("system_info", { "info": "Failed! You(Admin) cannot ban yourself." });
            return;
        }
        //Put the user out of current_users and current_sockets
        const banned_socket = the_room.current_sockets.splice(user_ind, 1)[0];
        the_room.current_users.splice(user_ind, 1);

        //Put the user in the banned list
        the_room.banned_sockets.push(banned_socket);

        //Notify admin
        socket.emit("system_info", { "info": "Succeeded! You have banned this user." });

        //Call all the users in the room to update display info
        for (var i = 0; i < the_room.current_sockets.length; i++)
            the_room.current_sockets[i].emit("the_room", { "the_room": emitTheRoom(room_ind) });

        //Call all the users (to update lobby)
        io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });

        //Let the admin to update admin panel
        socket.emit("you_are_admin", { "admin": [true, the_room.current_users] });

        //Ban the user
        banned_socket.emit("you_are_banned", { "banned": true });
    });

    socket.on('delete_room', function (data) {
        // This callback runs when an admin wants to delete the room

        const room_ind = data["delete"][0];
        const the_room = rooms[room_ind];

        //Verify
        if (the_room.creator_socket != socket) {
            socket.emit("system_info", { "info": "Failed! You are not the Admin." });
            return;
        }

        //Substitude the room in room list as null
        rooms[room_ind] = null;

        //Call all the users in the room to get out of here
        for (var i = 0; i < the_room.current_sockets.length; i++)
            the_room.current_sockets[i].emit("the_room_deleted", { "deleted": true });

        //Call all the users (to update lobby)
        io.sockets.emit("info_rooms", { "info_rooms": emitRoomInfo() });

        //Let the admin to update admin panel
        socket.emit("you_are_admin", { "admin": [false] });
    });

    //Return the room that the user wants to log into
    function emitTheRoom(i) {
        //The first entry is true if the login succeed
        let the_room_info = [true];
        the_room_info.push(rooms[i].room_number);
        the_room_info.push(rooms[i].name);
        the_room_info.push(rooms[i].type);
        the_room_info.push(rooms[i].current_users);
        the_room_info.push(rooms[i].creator);
        console.log(the_room_info);
        return the_room_info;
    }
    //Return room info that will be sent to users
    function emitRoomInfo() {
        let rooms_info = [];
        for (var i = 0; i < rooms.length; i++) {
            if (rooms[i] == null)
                continue;
            const room_info = [];
            room_info.push("#" + rooms[i].room_number);
            room_info.push(rooms[i].name);
            room_info.push(rooms[i].type);
            room_info.push(rooms[i].current_users);
            room_info.push(rooms[i].creator);
            rooms_info.push(room_info);
        }
        return rooms_info;
    }
});