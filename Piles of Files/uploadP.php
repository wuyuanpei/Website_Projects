<!DOCTYPE html>
<html lang='en'>    
    <head>
        <title>
            Upload Story
        </title>
        <meta charset="utf-8"/>
        <style>
			body{text-align: center;font-size:200%;background-color:#000000;color:#FFFFFF}
            textarea{text-align: center;font-size:75%;background-color:#000000;color:#FFFFFF;font-family: inherit}
            input{text-align: center;font-size:75%;background-color:#000000;color:#FFFFFF;font-family: inherit}
            .caption{color:red;}
        </style>
    </head>
    <body>
        <p>
            <b>Welcome to PoF News</b><br/>
            <div class='caption'>Post your story!</div><br/>
            <textarea rows="3" cols="50" name="title" form="story_submit">TITLE...</textarea><br/>
            <textarea rows="1" cols="50" name="link" form="story_submit">LINK...</textarea><br/>
            <textarea rows="13" cols="50" name="content" form="story_submit">CONTENT...</textarea>
            <form action="upload.php" id="story_submit" method="POST">
                <input type="hidden" name="token" value="<?php
                session_start();
                echo $_SESSION['token'];
                ?>"/>
                <input type="submit" value="Post!"/>
            </form>
        <p>
    </body>
</html>