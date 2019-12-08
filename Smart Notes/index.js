// Required Information
let notes = [];
let username = null;
let token = null;
let seq = "course";
let type = "Complete Notes";

let courses = [];
let tags = [];
let dates = [];

let definitions = [];
let theorems = [];
let problems = [];
let texts = [];

//Distribute the current order to newly added module
let currentS = 0;

// Refresh elements in "root" div
// "state" will label the current state of the user
function refresh(state) {

    function Display(props) {
        // Draw the portal
        if (state == "portal" || state == "login_error" || state == "signup_error") {
            return (
                <div id="portal">
                    <br />
                    <input type="text" id="username" className="form-control" placeholder="username" />&nbsp;&nbsp;
                <input type="password" id="password" className="form-control" placeholder="password" />&nbsp;&nbsp;
                    <select className="form-control" id="identity">
                        <option value="student">student</option>
                        <option value="professor">professor</option>
                    </select>
                    &nbsp;&nbsp;
                    <button id="login" className="btn btn-info">
                        Login
            </button>
                    &nbsp;&nbsp;
                    <button id="signup" className="btn btn-info">
                        Sign Up
            </button>
                    <br />
                    <label id='errorInfo'>
                        {state == "login_error" ? "Sorry! Login failed: Incorrect Username/Password/Identity!" : ""}
                        {state == "signup_error" ? "Sorry! Signup failed: Illgal Username/Password/Identity or Username has already been used by others!" : ""}
                    </label>
                    <br />
                    <hr />
                    <img src="bg.png" alt="background image" />
                </div>);
        }
        // Draw the Generate Materials Page
        else if (state == "generate") {
            var courses_ui = [];
            let k = 0;
            for (let course of courses) {
                courses_ui.push(<label key={k}><input type="checkbox" id={"c_" + k++} />{course}&nbsp;</label>);
            }

            var dates_ui = [];
            for (let date of dates) {
                dates_ui.push(<label key={k}><input type="checkbox" id={"c_" + k++} />{date + " "}&nbsp;</label>);
            }

            var tags_ui = [];
            for (let tag of tags) {
                tags_ui.push(<label key={k}><input type="checkbox" id={"c_" + k++} />{tag + " "}&nbsp;</label>);
            }
            return (
                <div id="generate_material_page">
                    <h2>Organize Your Notes and Quickly Generate Materials!</h2><hr />
                    <label className="rem">Course:&nbsp;</label>
                    {courses_ui.length > 0 ? courses_ui : "No course available."}
                    <br />
                    <label className="rem">Date:&nbsp;</label>
                    {dates_ui.length > 0 ? dates_ui : "No date available."}
                    <br />
                    <label className="rem">Tag:&nbsp;</label>
                    {tags_ui.length > 0 ? tags_ui : "No tag available."}
                    <br />
                    <label className="rem">Definition:&nbsp;</label>
                    <label><input type="checkbox" id="d_t" />Term</label>&nbsp;
                    <label><input type="checkbox" id="d_d" />Definition</label>&nbsp;
                    <label><input type="checkbox" id="d_e" />Example</label>&nbsp;
                    <label><input type="checkbox" id="d_r" />Remark</label>&nbsp;&nbsp;&nbsp;
                    <label className="rem">Theorem:&nbsp;</label>
                    <label><input type="checkbox" id="t_s" />Statement</label>&nbsp;
                    <label><input type="checkbox" id="t_p" />Proof</label>&nbsp;
                    <label><input type="checkbox" id="t_a" />Application</label>&nbsp;
                    <label><input type="checkbox" id="t_r" />Remark</label>&nbsp;&nbsp;&nbsp;
                    <label className="rem">Problem:&nbsp;</label>
                    <label><input type="checkbox" id="p_q" />Question</label>&nbsp;
                    <label><input type="checkbox" id="p_a" />Answer</label>&nbsp;
                    <label><input type="checkbox" id="p_r" />Remark</label>&nbsp;&nbsp;&nbsp;
                    <label className="rem">Text:&nbsp;</label>
                    <label><input type="checkbox" id="e_t" />Text</label>&nbsp;
                    <hr />
                    <button id="short1" className="btn btn-success">"Cheat Sheet"</button>&nbsp;&nbsp;
                    <button id="short2" className="btn btn-success">"Practice Problems"</button>&nbsp;&nbsp;
                    <button id="short3" className="btn btn-success">"Definition Table"</button>&nbsp;&nbsp;
                    <button id="short4" className="btn btn-success">"Theorem Table"</button>&nbsp;&nbsp;
                    <button id="dothat" className="btn btn-info">!!! Generate the above selected contents !!!</button>
                    <hr />
                    <input type="text" id="file_name" className="form-control" placeholder="file_name.pdf" />&nbsp;&nbsp;
                    <button id="generate_pdf" className="btn btn-warning">Download as PDF</button>&nbsp;&nbsp;
                    <button id="quit_g" className="btn btn-danger">Go back to the dashboard</button><hr />
                    <textarea id="materials" rows="30" cols="85">
                    </textarea>
                </div>);
        }

        // Draw the dashboard 
        else if (state == "dashboard") {

            //Construct the main table
            const noteElements = [];
            if (notes.length > 0) {
                //The current "folder" name
                //Since the data are all sorted, each time a new folder name appears, create a new folder
                let current;
                switch (seq) {
                    case "tag": current = notes[0].tag; break;
                    case "date": current = notes[0].date; break;
                    case "course": current = notes[0].course; break;
                }
                let k = 0;
                let d = 0;
                let idCon = 0;
                if (type != "Complete Notes")
                    noteElements.push(<div key={k++}><label className="folder"><b>1. Folder:&nbsp;&nbsp;{seq}&nbsp;-&nbsp;</b>{current}</label>&nbsp;&nbsp;<button id={idCon}>fold/display</button>
                        <table className={idCon}><tbody><tr className="head"><td className="course"><b>Course</b></td><td className="date"><b>Date</b></td><td className="tag"><b>Tag</b></td><td className="title"><b>Details</b></td></tr></tbody></table></div>);
                else
                    noteElements.push(<div key={k++}><label className="folder"><b>1. Folder:&nbsp;&nbsp;{seq}&nbsp;-&nbsp;</b>{current}</label>&nbsp;&nbsp;<button id={idCon}>fold/display</button>
                        <table className={idCon}><tbody><tr className="head"><td className="ops"><b>Operation</b></td><td className="course"><b>Course</b></td><td className="date"><b>Date</b></td><td className="tag"><b>Tag</b></td><td className="title"><b>Details</b></td></tr></tbody></table></div>);

                var count = 2;
                for (let note of notes) {
                    let now;
                    switch (seq) {
                        case "tag": now = note.tag; break;
                        case "date": now = note.date; break;
                        case "course": now = note.course; break;
                    }
                    if (now != current) {
                        current = now;
                        idCon++;
                        if (type != "Complete Notes")
                            noteElements.push(<div key={k++}><label className="folder"><b>{count++}. Folder:&nbsp;&nbsp;{seq}&nbsp;-&nbsp;</b>{current}</label>&nbsp;&nbsp;<button id={idCon}>fold/display</button>
                                <table className={idCon}><tbody><tr className="head"><td className="course"><b>Course</b></td><td className="date"><b>Date</b></td><td className="tag"><b>Tag</b></td><td className="title"><b>Details</b></td></tr></tbody></table></div>);
                        else
                            noteElements.push(<div key={k++}><label className="folder"><b>{count++}. Folder:&nbsp;&nbsp;{seq}&nbsp;-&nbsp;</b>{current}</label>&nbsp;&nbsp;<button id={idCon}>fold/display</button>
                                <table className={idCon}><tbody><tr className="head"><td className="ops"><b>Operation</b></td><td className="course"><b>Course</b></td><td className="date"><b>Date</b></td><td className="tag"><b>Tag</b></td><td className="title"><b>Details</b></td></tr></tbody></table></div>);

                    }
                    if (type == "Complete Notes") {
                        noteElements.push(
                            <table key={k++} className={idCon}>
                                <tbody>
                                    <tr className="content" >
                                        <td className="ops"><button className="btn btn-xs" id={"s_" + note.id}>Share</button></td>
                                        <td className="course">{note.course}</td>
                                        <td className="date">{note.date}</td>
                                        <td className="tag">{note.tag}</td>
                                        <td className="title"><a id={"x" + note.id}>Title: {note.title}</a></td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        );
                    } else if (type == "Definitions") {
                        noteElements.push(
                            <table key={k++} className={idCon} id={"y" + d++}>
                                <tbody>
                                    <tr className="content" >
                                        <td className="course">{note.course}</td>
                                        <td className="date">{note.date}</td>
                                        <td className="tag">{note.tag}</td>
                                        <td className="title"><a id={"x" + note.id}>Term: {note.term}; Definition: {note.definition}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        );
                    } else if (type == "Theorems") {
                        noteElements.push(
                            <table key={k++} className={idCon} id={"y" + d++}>
                                <tbody>
                                    <tr className="content" >
                                        <td className="course">{note.course}</td>
                                        <td className="date">{note.date}</td>
                                        <td className="tag">{note.tag}</td>
                                        <td className="title"><a id={"x" + note.id}>Statement: {note.statement}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        );
                    } else if (type == "Problems") {
                        noteElements.push(
                            <table key={k++} className={idCon} id={"y" + d++}>
                                <tbody>
                                    <tr className="content" >
                                        <td className="course">{note.course}</td>
                                        <td className="date">{note.date}</td>
                                        <td className="tag">{note.tag}</td>
                                        <td className="title"><a id={"x" + note.id}>Question: {note.question}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        );
                    } else if (type == "Texts") {
                        noteElements.push(
                            <table key={k++} className={idCon} id={"y" + d++}>
                                <tbody>
                                    <tr className="content" >
                                        <td className="course">{note.course}</td>
                                        <td className="date">{note.date}</td>
                                        <td className="tag">{note.tag}</td>
                                        <td className="title"><a id={"x" + note.id}>Text: {note.text}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        );
                    }
                }
            }
            return (<div id="dashboard">
                <h1>Hello {username}! Welcome to SmartNote!</h1><hr />
                <select id="type">
                    <option>{type}(current)</option>
                    <option value="Complete Notes">Complete Notes</option>
                    <option value="Definitions">Definitions</option>
                    <option value="Theorems">Theorems</option>
                    <option value="Problems">Problems</option>
                    <option value="Texts">Texts</option>
                </select>

                &nbsp;Organized by&nbsp;
                <select id="organize">
                    <option>{seq}(current)</option>
                    <option value="course">course</option>
                    <option value="date">date</option>
                    <option value="tag">tag</option>
                </select>
                &nbsp;&nbsp;
                <button id="create_new" className="btn btn-warning">
                    Create New Note
                </button>&nbsp;&nbsp;
                <button id="generate" className="btn btn-info">
                    Generate Materials
                </button>&nbsp;&nbsp;
                <button id="logout" className="btn btn-danger">
                    Log Out
                </button>
                <br /><br />
                <div>
                    {notes.length > 0 ? noteElements : "No data available. You can click 'Create New Note' to create notes!"}
                </div>
            </div>);
        }
        //edit/post page
        else if (state == "post" || state == "postPrepare") {
            return (
                <div>
                    <div className="jumbotron">
                        <label id="titleCaption">Note Title:</label> <input type="text" id="titleInput" /><br /><br />
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <label>Course:&nbsp;</label>
                                    </td><td>
                                        <input type="text" id="courseInput" placeholder="Type your course code" /><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Date:&nbsp;</label>
                                    </td><td>
                                        <input type="text" id="dateInput" placeholder="yyyy-MM-dd" /><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Tag:&nbsp;</label>
                                    </td><td>
                                        <input type="text" id="tagInput" placeholder="Add a tag to your note" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="notePaper">
                        <p id="noteReminder">Here!</p>
                        <hr />
                        <button id="quit" className="btn btn-danger">Quit and delete this draft</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <button id="save" className="btn btn-warning">Quit and save this draft</button>
                        <hr />
                    </div>
                    <div className="move" id="move1">
                        <div className="banner" id="banner1"><i>Drag me to...</i></div>
                        <div className="contentBox" id="content1"><b>Definition</b><br />term, definition, example, remark</div>
                    </div>
                    <div className="move" id="move2">
                        <div className="banner" id="banner2"><i>Drag me to...</i></div>
                        <div className="contentBox" id="content2"><b>Theorem</b><br />statement, proof, application, remark</div>
                    </div>
                    <div className="move" id="move3">
                        <div className="banner" id="banner3"><i>Drag me to...</i></div>
                        <div className="contentBox" id="content3"><b>Problem</b><br />question, answer, remark</div>
                    </div>
                    <div className="move" id="move4">
                        <div className="banner" id="banner4"><i>Drag me to...</i></div>
                        <div className="contentBox" id="content4"><b>Text</b><br />plain text</div>
                    </div>
                </div>

            );
        } else {//Draw the error page
            return (<p>Nothing on this page! 404<br /> Please contact developer: yuanpei.wu@wustl.edu</p>);
        }
    }
    ReactDOM.render(
        <Display />,
        document.getElementById('root')
    );
    //Create New Note
    $("#create_new").click(function () {
        //Set currentS 0
        currentS = 0;
        refresh("post");
    });

    //Go into Generate Material Page
    $("#generate").click(function () {
        $.ajax({
            type: "POST",
            url: "getCourses.php",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data) {
                courses = data;
                $.ajax({
                    type: "POST",
                    url: "getTags.php",
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data2) {
                        tags = data2;
                        $.ajax({
                            type: "POST",
                            url: "getDates.php",
                            contentType: "application/json; charset=utf-8",
                            dataType: "json",
                            success: function (data3) {
                                dates = data3;
                                $.ajax({
                                    type: "POST",
                                    url: "getDefinitions.php",
                                    contentType: "application/json; charset=utf-8",
                                    data: JSON.stringify({ "seq": "date" }),
                                    dataType: "json",
                                    success: function (data4) {
                                        definitions = data4;
                                        $.ajax({
                                            type: "POST",
                                            url: "getTheorems.php",
                                            contentType: "application/json; charset=utf-8",
                                            data: JSON.stringify({ "seq": "date" }),
                                            dataType: "json",
                                            success: function (data5) {
                                                theorems = data5;
                                                $.ajax({
                                                    type: "POST",
                                                    url: "getProblems.php",
                                                    contentType: "application/json; charset=utf-8",
                                                    data: JSON.stringify({ "seq": "date" }),
                                                    dataType: "json",
                                                    success: function (data6) {
                                                        problems = data6;
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "getTexts.php",
                                                            contentType: "application/json; charset=utf-8",
                                                            data: JSON.stringify({ "seq": "date" }),
                                                            dataType: "json",
                                                            success: function (data7) {
                                                                texts = data7;
                                                                refresh("generate");
                                                            },
                                                            error: function (data) {
                                                            }
                                                        });
                                                    },
                                                    error: function (data) {
                                                    }
                                                });
                                            },
                                            error: function (data) {
                                            }
                                        });
                                    },
                                    error: function (data) {
                                    }
                                });
                            },
                            error: function (data) {
                            }
                        });
                    },
                    error: function (data) {
                    }
                });
            },
            error: function (data) {
            }
        });
    });
    //By default, check all the boxes
    let checkBoxInd = 0;
    while ($("#c_" + checkBoxInd).length > 0) {
        $("#c_" + checkBoxInd).attr("checked", 'true');
        checkBoxInd++;
    }
    //Display the information to the display board
    $("#dothat").click(function () {
        let temp_courses = [];
        for (var i = 0; i < courses.length; i++) {
            if ($("#c_" + i).attr("checked")) {
                temp_courses.push(courses[i]);
            }
        }
        let temp_dates = [];
        for (var i = 0; i < dates.length; i++) {
            if ($("#c_" + (i + courses.length)).attr("checked")) {
                temp_dates.push(dates[i]);
            }
        }
        let temp_tags = [];
        for (var i = 0; i < tags.length; i++) {
            if ($("#c_" + (courses.length + dates.length + i)).attr("checked")) {
                temp_tags.push(tags[i]);
            }
        }
        for (var i = 0; i < definitions.length; i++) {
            const temp = definitions[i];
            if (temp_courses.indexOf(temp['course']) > -1 && temp_dates.indexOf(temp['date']) > -1 && temp_tags.indexOf(temp['tag']) > -1) {
                if ($("#d_t").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Term: " + temp['term'] + "\n");
                }
                if ($("#d_d").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Definition: " + temp['definition'] + "\n");
                }
                if ($("#d_e").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Example: " + temp['example'] + "\n");
                }
                if ($("#d_r").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Remark: " + temp['remark'] + "\n");
                }
            }
        }

        for (var i = 0; i < theorems.length; i++) {
            const temp = theorems[i];
            if (temp_courses.indexOf(temp['course']) > -1 && temp_dates.indexOf(temp['date']) > -1 && temp_tags.indexOf(temp['tag']) > -1) {
                if ($("#t_s").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Statement: " + temp['statement'] + "\n");
                }
                if ($("#t_p").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Proof: " + temp['proof'] + "\n");
                }
                if ($("#t_a").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Application: " + temp['application'] + "\n");
                }
                if ($("#t_r").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Remark: " + temp['remark'] + "\n");
                }
            }
        }
        for (var i = 0; i < problems.length; i++) {
            const temp = problems[i];
            if (temp_courses.indexOf(temp['course']) > -1 && temp_dates.indexOf(temp['date']) > -1 && temp_tags.indexOf(temp['tag']) > -1) {
                if ($("#p_q").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Question: " + temp['question'] + "\n");
                }
                if ($("#p_a").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Answer: " + temp['answer'] + "\n");
                }
                if ($("#p_r").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Remark: " + temp['remark'] + "\n");
                }
            }
        }
        for (var i = 0; i < texts.length; i++) {
            const temp = texts[i];
            if (temp_courses.indexOf(temp['course']) > -1 && temp_dates.indexOf(temp['date']) > -1 && temp_tags.indexOf(temp['tag']) > -1) {
                if ($("#e_t").attr("checked")) {
                    $("#materials").val($("#materials").val() + "Text: " + temp['text'] + "\n");
                }
            }
        }
    });

    //Quit to the dashboard button
    $("#quit_g").click(function () {
        getInform();
    });

    //Generate PDF button
    $("#generate_pdf").click(function () {
        //Download as pdf
        const pdf = new jsPDF();
        let m = $("#materials").val() + "\n";
        //Control the number of lines and the number of characters per line
        let count = 0;
        let countL = 0;
        let lineEnd = 0;
        for (var i = 0; i < m.length; i++) {
            const tmp = m.charAt(i);
            if (tmp != '\n') count++;
            else {
                count = 0;
                pdf.text(m.substring(lineEnd, i), 15, countL * 6 + 15);
                countL++;
                lineEnd = i + 1;
            }
            if (count > 68 && tmp == " " && m.charAt(i + 1) != "\n") {
                pdf.text(m.substring(lineEnd, i), 15, countL * 6 + 15);
                count = 0;
                countL++;
                lineEnd = i + 1;
            }
            if (count == 71 && m.charAt(i + 1) != "\n") {
                pdf.text(m.substring(lineEnd, i + 1), 15, countL * 6 + 15);
                count = 0;
                countL++;
                lineEnd = i + 1;
            }
            if (countL >= 45) {
                pdf.addPage();
                countL = 0;
            }
        }
        if ($("#file_name").val() == "")
            pdf.save("smart_note.pdf");
        else
            pdf.save($("#file_name").val());

    });
    //Shortcuts on generating pdf
    $("#short1").click(function () {
        //Add checked for severl box
        $("#d_t").attr("checked", 'true');
        $("#d_d").attr("checked", 'true');
        $("#t_s").attr("checked", 'true');
        $("#t_p").attr("checked", 'true');
        $("#p_q").attr("checked", 'true');
        $("#p_a").attr("checked", 'true');
    });
    $("#short2").click(function () {
        //Add checked for severl box
        $("#p_q").attr("checked", 'true');
        $("#p_a").attr("checked", 'true');
        $("#p_r").attr("checked", 'true');
    });
    $("#short3").click(function () {
        //Add checked for severl box
        $("#d_t").attr("checked", 'true');
        $("#d_d").attr("checked", 'true');
        $("#d_e").attr("checked", 'true');
        $("#d_r").attr("checked", 'true');
    });
    $("#short4").click(function () {
        //Add checked for severl box
        $("#t_s").attr("checked", 'true');
        $("#t_p").attr("checked", 'true');
        $("#t_a").attr("checked", 'true');
        $("#t_r").attr("checked", 'true');
    });
    //Log out
    $("#logout").click(function () {
        refresh("portal")
        $.ajax({
            type: "POST",
            url: "logout.php",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data) {
            },
            error: function (data) {
            }
        });
    });

    //Organize selection box
    $("#organize").change(function () {
        seq = $("#organize").val();
        getInform();
    });

    //Type selection box
    $("#type").change(function () {
        type = $("#type").val();
        getInform();
    });

    //Login
    $("#login").click(function () {
        $.ajax({
            type: "POST",
            url: "login.php",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(getLoginData()),
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    token = data['token'];
                    username = data['username'];
                    getInform();

                } else {
                    refresh("login_error");
                }
            },
            error: function (data) {
                refresh("login_error");
            }
        });
    });

    function getLoginData() {
        var json = {
            "username": $("#username").val(),
            "password": $("#password").val(),
            "identity": $("#identity").val()
        };
        return json;
    }
    //Signup
    $("#signup").click(function () {
        $.ajax({
            type: "POST",
            url: "signup.php",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(getLoginData()),
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    token = data['token'];
                    username = data['username'];
                    getInform();

                } else {
                    refresh("signup_error");
                }
            },
            error: function (data) {
                refresh("signup_error");
            }
        });
    });
    // Return the date that is used to fill date as default value
    function getFormatDate() {
        var now = new Date();
        var y = now.getFullYear();
        var m = now.getMonth() + 1;
        var d = now.getDate();
        m = m < 10 ? "0" + m : m;
        d = d < 10 ? "0" + d : d;
        return y + "-" + m + "-" + d;
    }
    $("#dateInput").val(getFormatDate());
    //Quit button
    $("#quit").click(function () {
        getInform();
    });
    //Save button
    $("#save").click(function () {
        const all = [];
        const alli = [];
        alli.push($("#titleInput").val());
        if ($("#courseInput").val() == "")
            alli.push("No course");
        else
            alli.push($("#courseInput").val());
        alli.push($("#dateInput").val());
        if ($("#tagInput").val() == "")
            alli.push("No tag");
        else
            alli.push($("#tagInput").val());
        all.push(alli);

        for (var i = 0; i < currentS; i++) {
            //It is a definition module
            if ($("#d" + i + "-" + "0").length > 0) {
                const temp = [];
                temp.push("d");
                temp.push($("#d" + i + "-" + "0").val());
                temp.push($("#d" + i + "-" + "1").val());
                temp.push($("#d" + i + "-" + "2").val());
                temp.push($("#d" + i + "-" + "3").val());
                all.push(temp);
            } else if ($("#t" + i + "-" + "0").length > 0) {
                const temp = [];
                temp.push("t");
                temp.push($("#t" + i + "-" + "0").val());
                temp.push($("#t" + i + "-" + "1").val());
                temp.push($("#t" + i + "-" + "2").val());
                temp.push($("#t" + i + "-" + "3").val());
                all.push(temp);
            }
            else if ($("#p" + i + "-" + "0").length > 0) {
                const temp = [];
                temp.push("p");
                temp.push($("#p" + i + "-" + "0").val());
                temp.push($("#p" + i + "-" + "1").val());
                temp.push($("#p" + i + "-" + "2").val());
                all.push(temp);
            }
            else if ($("#e" + i + "-" + "0").length > 0) {
                const temp = [];
                temp.push("e");
                temp.push($("#e" + i + "-" + "0").val());
                all.push(temp);
            }
        }
        $.ajax({
            type: "POST",
            url: "saveModules.php",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(all),
            dataType: "json",
            success: function (data) {
                if (!data['success'])
                    alert("Sorry! Save failed! There are some illegal and malicious inputs detected. (Date should be yyyy-MM-dd)");
                getInform();
            },
            error: function (data) {
                alert("Sorry! Save failed! There are some illegal and malicious inputs detected. (Date should be yyyy-MM-dd)");
                getInform();
            }
        });
    });
    
    //When share button is clicked
    $('button[id^=s_]').click(function(){
        const id = ($(this).attr('id')).substring(2);
        $("#dialog").empty();
        $("#dialog").append("<p><b>Share this note to...</b></p><input type='text' id='s_username' style='width:200px' placeholder='username' /><p><b>if the username exists</b></p><br/>");
        $("#dialog").dialog({
                open: function (event, ui) {
                    $(".ui-dialog-titlebar-close").hide();
                },
                buttons: {
                    "Share": function () {
                        $(this).dialog('close');
                        $.ajax({
                            type: "POST",
                            url: "ShareNotes.php",
                            contentType: "application/json; charset=utf-8",
                            data: JSON.stringify({ "note_id": id, "username": $("#s_username").val()}),
                            dataType: "json",
                            success: function (data) {  
                            },
                            error: function (data) {
                            }
                        });
                    },
                    "Close": function(){
                        $(this).dialog('close');
                    }
                },
                title: "Share Note...",
                show: "slide",
                hide: "slide",
                height: 230,
                width: 300,
        });
    });
    //When a link is clicked
    $("a").click(function (event) {
        event.preventDefault();
        const id = ($(this).attr('id')).substring(1);
        if (type == "Complete Notes") {
            $.ajax({
                type: "POST",
                url: "getModules.php",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({ "note_id": id }),
                dataType: "json",
                success: function (data) {
                    refresh("postPrepare");

                    $("#titleInput").val(data[data.length - 1]["title"]);
                    $("#dateInput").val(data[data.length - 1]["date"]);
                    $("#tagInput").val(data[data.length - 1]["tag"]);
                    $("#courseInput").val(data[data.length - 1]["course"]);

                    for (var i = 0; i < data.length - 1; i++) {
                        if (data[i]["type"] == "d") {
                            addModule(1, data[i]["term"], data[i]["definition"], data[i]["example"], data[i]["remark"]);
                        }
                        if (data[i]["type"] == "t") {
                            addModule(2, data[i]["statement"], data[i]["proof"], data[i]["application"], data[i]["remark"]);
                        }
                        if (data[i]["type"] == "q") {
                            addModule(3, data[i]["question"], data[i]["answer"], data[i]["remark"], "");
                        }
                        if (data[i]["type"] == "e") {
                            addModule(4, data[i]["text"], "", "", "");
                        }
                    }
                },
                error: function (data) {
                    refresh("error");
                }
            });
        } else {
            const locIdx = Number($(this).parent().parent().parent().parent().attr("id").substring(1));
            const obj = notes[locIdx];
            $("#dialog").empty();
            if (type == "Definitions")
                $("#dialog").append("<p><b>Definition</b></p><input type='text' id='e_term' style='width:650px' placeholder='term' value='" + obj["term"] + "' /><br/><input type='text' id='e_definition' style='width:650px' placeholder='definition' value='" + obj["definition"] + "' /><br/><input type='text' id='e_example' style='width:650px' placeholder='example' value='" + obj["example"] + "' /><br/><input type='text' id='e_remark' style='width:650px' placeholder='remark' value='" + obj["remark"] + "' /><br/>");
            if (type == "Theorems")
                $("#dialog").append("<p><b>Theorem</b></p><input type='text' id='e_statement' style='width:650px' placeholder='statement' value='" + obj["statement"] + "' /><br/><input type='text' id='e_proof' style='width:650px' placeholder='proof' value='" + obj["proof"] + "' /><br/><input type='text' id='e_application' style='width:650px' placeholder='application' value='" + obj["application"] + "' /><br/><input type='text' id='e_remark' style='width:650px' placeholder='remark' value='" + obj["remark"] + "' /><br/>");
            if (type == "Problems")
                $("#dialog").append("<p><b>Problem</b></p><input type='text' id='e_question' style='width:650px' placeholder='question' value='" + obj["question"] + "' /><br/><input type='text' id='e_answer' style='width:650px' placeholder='answer' value='" + obj["answer"] + "' /><br/><input type='text' id='e_remark' style='width:650px' placeholder='remark' value='" + obj["remark"] + "' /><br/>");
            if (type == "Texts")
                $("#dialog").append("<p><b>Text</b></p><input type='text' id='e_text' style='width:650px' placeholder='text' value='" + obj["text"] + "' /><br/>");

            $("#dialog").dialog({
                open: function (event, ui) {
                    $(".ui-dialog-titlebar-close").hide();
                },
                buttons: {
                    "Save and Quit": function () {
                        if (type == "Definitions") {
                            $.ajax({
                                type: "POST",
                                url: "editDefinition.php",
                                contentType: "application/json; charset=utf-8",
                                data: JSON.stringify({ "id": id, "term": $("#e_term").val(), "definition": $("#e_definition").val(), "example": $("#e_example").val(), "remark": $("#e_remark").val() }),
                                dataType: "json",
                                success: function (data) {
                                    getInform();
                                },
                                error: function (data) {
                                    refresh("error");
                                }
                            });
                        }

                        if (type == "Theorems") {
                            $.ajax({
                                type: "POST",
                                url: "editTheorem.php",
                                contentType: "application/json; charset=utf-8",
                                data: JSON.stringify({ "id": id, "statement": $("#e_statement").val(), "proof": $("#e_proof").val(), "application": $("#e_application").val(), "remark": $("#e_remark").val() }),
                                dataType: "json",
                                success: function (data) {
                                    getInform();
                                },
                                error: function (data) {
                                    refresh("error");
                                }
                            });
                        }

                        if (type == "Problems") {
                            $.ajax({
                                type: "POST",
                                url: "editProblem.php",
                                contentType: "application/json; charset=utf-8",
                                data: JSON.stringify({ "id": id, "question": $("#e_question").val(), "answer": $("#e_answer").val(), "remark": $("#e_remark").val() }),
                                dataType: "json",
                                success: function (data) {
                                    getInform();
                                },
                                error: function (data) {
                                    refresh("error");
                                }
                            });
                        }

                        if (type == "Texts") {
                            $.ajax({
                                type: "POST",
                                url: "editText.php",
                                contentType: "application/json; charset=utf-8",
                                data: JSON.stringify({ "id": id, "text": $("#e_text").val() }),
                                dataType: "json",
                                success: function (data) {
                                    getInform();
                                },
                                error: function (data) {
                                    refresh("error");
                                }
                            });
                        }

                        $(this).dialog('close');
                    },
                    "Delete and Quit": function () {

                        if (type == "Definitions") {
                            $.ajax({
                                type: "POST",
                                url: "deleteDefinition.php",
                                contentType: "application/json; charset=utf-8",
                                data: JSON.stringify({ "id": id }),
                                dataType: "json",
                                success: function (data) {
                                    getInform();
                                },
                                error: function (data) {
                                    refresh("error");
                                }
                            });
                        }

                        if (type == "Theorems") {
                            $.ajax({
                                type: "POST",
                                url: "deleteTheorem.php",
                                contentType: "application/json; charset=utf-8",
                                data: JSON.stringify({ "id": id }),
                                dataType: "json",
                                success: function (data) {
                                    getInform();
                                },
                                error: function (data) {
                                    refresh("error");
                                }
                            });
                        }

                        if (type == "Problems") {
                            $.ajax({
                                type: "POST",
                                url: "deleteProblem.php",
                                contentType: "application/json; charset=utf-8",
                                data: JSON.stringify({ "id": id }),
                                dataType: "json",
                                success: function (data) {
                                    getInform();
                                },
                                error: function (data) {
                                    refresh("error");
                                }
                            });
                        }

                        if (type == "Texts") {
                            $.ajax({
                                type: "POST",
                                url: "deleteText.php",
                                contentType: "application/json; charset=utf-8",
                                data: JSON.stringify({ "id": id }),
                                dataType: "json",
                                success: function (data) {
                                    getInform();
                                },
                                error: function (data) {
                                    refresh("error");
                                }
                            });
                        }

                        $(this).dialog('close');
                    }
                },
                title: "Module Editing...",
                show: "slide",
                hide: "slide",
                height: 300,
                width: 700,
            });
        }
    });

    // Get information from database to display in dashboard
    function getInform() {
        if (type == "Complete Notes") {
            $.ajax({
                type: "POST",
                url: "dashboardNote.php",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({ "seq": seq }),
                dataType: "json",
                success: function (data) {
                    notes = data;
                    refresh("dashboard");
                },
                error: function (data) {
                    refresh("error");
                }
            });
        } else if (type == "Theorems") {
            $.ajax({
                type: "POST",
                url: "getTheorems.php",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({ "seq": seq }),
                dataType: "json",
                success: function (data) {
                    notes = data;
                    refresh("dashboard");
                },
                error: function (data) {
                    refresh("error");
                }
            });
        } else if (type == "Definitions") {
            $.ajax({
                type: "POST",
                url: "getDefinitions.php",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({ "seq": seq }),
                dataType: "json",
                success: function (data) {
                    notes = data;
                    refresh("dashboard");
                },
                error: function (data) {
                    refresh("error");
                }
            });
        } else if (type == "Problems") {
            $.ajax({
                type: "POST",
                url: "getProblems.php",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({ "seq": seq }),
                dataType: "json",
                success: function (data) {
                    notes = data;
                    refresh("dashboard");
                },
                error: function (data) {
                    refresh("error");
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: "getTexts.php",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({ "seq": seq }),
                dataType: "json",
                success: function (data) {
                    notes = data;
                    refresh("dashboard");
                },
                error: function (data) {
                    refresh("error");
                }
            });
        }
    }


    //Button click functions
    //When "fold/display all notes" are clicked
    var curr = "";
    var idCon2 = 0;
    for (let note of notes) {
        var n;
        switch (seq) {
            case "tag": n = note.tag; break;
            case "date": n = note.date; break;
            case "course": n = note.course; break;
        }
        const now = n;
        if (now != curr) {
            const idCon3 = idCon2++;
            $("#" + idCon3).click(function () {
                const css = $("." + idCon3).css("display");
                if (css == "none")
                    $("." + idCon3).css("display", "block");
                else
                    $("." + idCon3).css("display", "none");
            });
            curr = now;
        }
    }

    //Drag Box
    for (var i = 1; i <= 4; i++)
        dragMove("#banner" + i, "#move" + i, i);

    function dragMove(dragBox, contentBox, i) {
        let Moving = false;
        $(dragBox).mousedown(function (e) {
            Moving = true;
            let div_x = e.pageX - $(contentBox).offset().left;
            let div_y = e.pageY - $(contentBox).offset().top;
            $(document).mousemove(function (e) {
                if (Moving) {
                    $(contentBox).css({ "left": e.pageX - div_x, "top": e.pageY - div_y });
                }
            });
        });
        $(dragBox).mouseup(
            function (e) {
                Moving = false;
                $(contentBox).css({ "left": 20, "top": 260 + (i - 1) * 125 });
                if (e.pageX >= 500) {
                    addModule(i, "", "", "", "");
                }
            });
    }

    //Add a module to the note
    function addModule(i, v1, v2, v3, v4) {
        $("#noteReminder").css("display", "none");

        if (i == 1) {
            $("#notePaper").append("<div class='noteLines'><label>Term:&nbsp;</label><input class='moduleInput' type=‘text’ style='width:193px' value='" + v1 + "' placeholder='Terminology' id='d" + currentS + "-" + 0 + "'/><label>&nbsp;Definition:&nbsp;</label><input class='moduleInput' value='" + v2 + "' type=‘text’ style='width:550px' placeholder='Definition of the Terminology' id='d" + currentS + "-" + 1 + "'/><br/><label>Example:&nbsp;</label><input class='moduleInput' type=‘text’ value='" + v3 + "' style='width:795px' placeholder='Example related to the Terminology' id='d" + currentS + "-" + 2 + "'/><br/><label>Remark:&nbsp;</label><input class='moduleInput' value='" + v4 + "' type=‘text’ style='width:800px' placeholder='Any Remark' id='d" + currentS + "-" + 3 + "'/><br/></div><hr/>");
        } else if (i == 2) {
            $("#notePaper").append("<div class='noteLines'><label>Statement:&nbsp;</label><input class='moduleInput' type=‘text’ value='" + v1 + "' style='width:782px' placeholder='Statement of the Theorem' id='t" + currentS + "-" + 0 + "'/><br/><label>&nbsp;Proof:&nbsp;</label><input class='moduleInput' value='" + v2 + "' type=‘text’ style='width:820px' placeholder='Proof of the Theorem' id='t" + currentS + "-" + 1 + "'/><br/><label>Application:&nbsp;</label><input class='moduleInput' value='" + v3 + "' type=‘text’ style='width:777px' placeholder='One of Applications of the Theorem' id='t" + currentS + "-" + 2 + "'/><br/><label>Remark:&nbsp;</label><input class='moduleInput' value='" + v4 + "' type=‘text’ style='width:800px' placeholder='Any Remark' id='t" + currentS + "-" + 3 + "'/><br/></div><hr/>");
        } else if (i == 3) {
            $("#notePaper").append("<div class='noteLines'><label>Question:&nbsp;</label><input class='moduleInput' type=‘text’ value='" + v1 + "' style='width:793px' placeholder='Problem Question' id='p" + currentS + "-" + 0 + "'/><br/><label>&nbsp;Answer:&nbsp;</label><input class='moduleInput' value='" + v2 + "' type=‘text’ style='width:805px' placeholder='Correct Answer of the Question' id='p" + currentS + "-" + 1 + "'/><br/><label>Remark:&nbsp;</label><input class='moduleInput' value='" + v3 + "' type=‘text’ style='width:800px' placeholder='Any Remark' id='p" + currentS + "-" + 2 + "'/><br/></div><hr/>");
        } else if (i == 4) {
            $("#notePaper").append("<div class='noteLines'><label>Text:&nbsp;</label><input class='moduleInput' type=‘text’ value='" + v1 + "' style='width:823px' placeholder='text' id='e" + currentS + "-" + 0 + "'/><br/></div><hr/>");

        }
        currentS++;
    }

}
//When the document is first loaded
$(refresh("portal"));