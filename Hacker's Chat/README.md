# Hackers' Chat
The link to our chat website:
http://ec2-54-242-152-54.compute-1.amazonaws.com:3456
>Of course you can download the repo and type "npm install" and "node chat-server.js" to test it in localhost. However, we also provide another option that we download "forever" and run the website on the instance. We believe it will be more convenient for you to test all the functions through this link.

Richard Wu

# Creative Portion!
- Anonymous!
  > Users can send anonymous message to other people in the chat room. The name tag will display "anonymous" instead of the user's personal nickname.
- Dynamic Administration!
  > The creator of a chat room holds the Admin of the room. However, when the Admin loses the connection to the server, the room's Admin will move to another user who stays the longest in this chat room. If there is no other people in this chat room and the chat room's Admin loses connection, our system will automatically delete this chat room.
- Human Centered Design!
  > In our chat website, human centered design is put into account. When the portal is loaded, the login_input textfield will automatically grab the focus. Similarly, the specified textfields on dashboard and chat room pages will grab focus intentionally. In addition, users can press Enter(on Windows)/Return(on Mac) to move to the next textfield and submit information. For example, pressing Enter after finishing a message will let you automatically send this message and empty the textfield for the next input.
- Connection Check!
  > Our chat website will track every user's connection. When a user disconnect from the server (reload the page or close the browser), our system will clear the user from the current chat room and remind other users the latest status of the dashboard and the chat room. So nothing will mess up and Dynamic Administration is implemented through this way.
- Security Check!
  > We use Room Number to label every chat room as the primary key and store the sockets of the users and creators with their usernames. Therefore, the same username and the same chat room name will never mess up. Also, when the Admin attempt to kick out or ban a user or delete the room, we will first check his socket with the admin_socket stored to verify the request. We also considered some extreme cases like the chat room or username is empty.
- System Reminder!
  > Any legal or illegal operation will have a system reminder to help users and the Admin track what they are doing. For example, when a user enter a chat room, there will be a system reminder saying "Welcome to Room######". It's the same when an Admin kicked out or banned a user from a chat room.
- Hackers' Chat!
  > We named our chat website "Hackers' Chat" because we intentionally did not do Filter Input and Escape Output (another reason is that it is not on the grading rubric). Originally we planed to use StringEscapeUtils.escapeHtml to detect input, but we think that let users type <img> or <a> in the message would have much more fun (although some tags like <script> would be dangerous), so that's why it is called "Hackers' Chat".
  
}
# Login Details! Welcome to Hackers' Chat!
- Basically all the operations and functions are intuitive.
- Again, downloading repo, using "npm install" and "node chat-server.js" is the same as visiting the link above.
- In the dashboard, changing the options in the selection list will provide two types of rooms (private/public). Clicking "Create Chat Room" could let you create a chat room, and pressing "Enter" in the table below could let you enter a room.
- In the chat room, typing message in the textfield, choosing "To ALL", "Anonymous", or specify a username would let you send public/private messages in the chat room. If you are an Admin, select a username and then you can "kick" or "ban" his or her, or you can delete the room if you want. 

# About
 - Thank you so much for visiting my site.
 - Email: yuanpei.wu@wustl.edu
