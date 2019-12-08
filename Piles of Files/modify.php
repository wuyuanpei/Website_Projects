<!DOCTYPE html>
<html lang='en'>    
    <head>
        <title>
            Edit Story
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
        <?php
            require "database.php";
            session_start();
            if(!hash_equals($_SESSION['token'], $_POST['token'])){
                die("Request forgery detected");
            }
            $stmt = $mysqli->prepare("select title,content,link from stories where id=?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            
            $stmt->bind_param('s', $_POST["id"]);
            
            $stmt->execute();
            
            $stmt->bind_result($title, $content,$link);
            
            $stmt->fetch();
            
            $stmt->close();
        ?>
            <b>Welcome to PoF News</b><br/>
            <div class='caption'>Edit your story!</div><br/>
            <textarea rows="3" cols="50" name="title" form="story_submit"><?php printf("%s",htmlspecialchars($title));?></textarea><br/>
            <textarea rows="1" cols="50" name="link" form="story_submit"><?php printf("%s",htmlspecialchars($link));?></textarea><br/>
            <textarea rows="13" cols="50" name="content" form="story_submit"><?php printf("%s",htmlspecialchars($content));?></textarea>
            <form action="resubmit.php" id="story_submit" method="POST">
                <input type="hidden" name="token" value="<?php
                echo $_SESSION['token'];
                ?>"/>
                <input type="hidden" name="id" value="<?php
                echo $_POST['id'];
                ?>">
                <input type="submit" value="Edit!"/>
            </form>
        <p>
    </body>
</html>