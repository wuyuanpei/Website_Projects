<!DOCTYPE html>
<html>
<head>
	<title>
        PoF News
    </title>
    <style>
        body {
            background-color: Black;
            color : White;  
        }
        .title{
            text-align: center;
            font-size: 400%;
        }
        .caption {
            color:red;
            text-align: center;
            font-size: 275%; 
        }
        .content {
            text-align: center;
            font-size: 175%; 
        }
        .link {
            color:red;
            font-size: 150%; 
        }
        .inf{
            font-size: 150%; 
        }
        .comment_field{
            font-size: 120%; 
        }
        .reminder{
            font-size: 150%; 
        }
        .comment{
            font-size: 125%; 
        }
        form {
            display:inline;
        }
    </style>
</head>
<body>
        <form action = "homepage.php" method="POST">
            <input type = "submit" value ="homepage">
        </form>
        <p class='title'><b> Piles of Files </b></p>
           
        <?php
            require "database.php";
            $stmt = $mysqli->prepare("select id,title,content,likes,dislikes,username,date,link from stories where id=?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            
            $stmt->bind_param('s', $_GET["id"]);
            
            $stmt->execute();
            
            $stmt->bind_result($id,$title, $content,$likes,$dislikes,$username,$date,$link);
            
            $stmt->fetch();
            
            $stmt->close();
            printf("<p class='caption'>%s</p>",htmlspecialchars( $title));
            printf("<p class='content'>%s</p>",htmlspecialchars($content));
            printf("<a class='link' href=%s>%s</a>",htmlspecialchars($link),htmlspecialchars($link));
            printf("<p class='inf'>By %s<br/>%s</p>",htmlspecialchars($username),htmlspecialchars($date));
            session_start();
            printf("
                <form action='like.php' method='POST'>
                        <input type='hidden' name='story_id' value=%s />
                        <input type='submit' value='%s-LIKE'/>
                </form>
                <form action='dislike.php' method='POST'>
                        <input type='hidden' name='story_id' value=%s />
                        <input type='submit' value='%s-DISLIKE'/>
                </form></br></br>",
                htmlspecialchars($id),
                htmlspecialchars($likes),
                htmlspecialchars($id),
                htmlspecialchars($dislikes));
            if(isset($_SESSION['username'])){
                if(isset($_SESSION['comment_c'])){
                printf("<textarea class='comment_field' rows='5' cols='50' name='comment' form='comment_submit'>%s</textarea><br/>
                <form action='comment.php' id='comment_submit' method='POST'>
                        <input type='hidden' name='token' value=%s />
                        <input type='hidden' name='story_id' value=%s />
                        <input type='hidden' name='username' value=%s />
                        <input type='submit' value='add comment'/>
                </form>",htmlspecialchars( $_SESSION['comment_c']),htmlspecialchars( $_SESSION['token']),htmlspecialchars($id),htmlspecialchars( $_SESSION['username']));
                unset($_SESSION['comment_c']);
            }else{
                    printf("<textarea class='comment_field' rows='5' cols='50' name='comment' form='comment_submit'>COMMENT...</textarea><br/>
                <form action='comment.php' id='comment_submit' method='POST'>
                        <input type='hidden' name='token' value=%s />
                        <input type='hidden' name='story_id' value=%s />
                        <input type='hidden' name='username' value=%s />
                        <input type='submit' value='add comment'/>
                </form>",
                htmlspecialchars( $_SESSION['token']),
                htmlspecialchars($id),
                htmlspecialchars( $_SESSION['username'])
            );
                
                }
            }else{
                printf("<p class='reminder'>Please Log in to add comment!
                <form action='login.html'>
	            <input type='submit'  value='login'/> 
	            </form></p>");
            }
            $stmt = $mysqli->prepare("select comment,username from comments where story_id=?");
            if(!$stmt){
	            printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('s', $id);

            $stmt->execute();

            $result = $stmt->get_result();

            echo "<ul>\n";
            $count = 0;
            while($row = $result->fetch_assoc()){
		        printf ("<li class='comment'><p>%s</br>By %s</p></li>\n",htmlspecialchars( $row["comment"] ),htmlspecialchars( $row["username"] ));
                $count+=1;
            }
            echo "</ul>\n";
            printf("%d comment(s)",$count);
            $stmt->close();
        ?>
        
</body>
</html>


