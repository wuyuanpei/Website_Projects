<!DOCTYPE html>
<html>
<head>
	<title>My Posts</title>
    <style>
        body {
        background-color: Black;
        color : White;  
    }
    li{
        list-style-type:none;
        text-align: center;
        font-size: 150%;
        line-height:  200%;
    }
    .title {
        text-align: center;
        font-size: 350%;
    }
    .caption {
        color:red;
        text-align: center;
        font-size: 200%;      
    }
    .username {
    position: absolute;
    top: 10px;
    right: 350px;
    }
    form {
    display:inline;
    }
    </style>
</head>
<body>
    <form action = "homepage.php" mwthod="POST">
        <input type = "submit" value ="homepage">
    </form>
    <div class='username'>
        Hello, 
        <?php
        session_start();
        printf("%s!",htmlspecialchars($_SESSION['username']));?>
    </div>
        <p class='title'><b>My posts on PoF</b></p>
        <p class='caption'>!!! STORIES !!!</p>
<?php
require 'database.php';
$stmt = $mysqli->prepare("select id,title,link,content from stories where username=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
exit;
}
$stmt->bind_param('s', $_SESSION['username']);

$stmt->execute();

$result = $stmt->get_result();

echo "<ul>\n";
while($row = $result->fetch_assoc()){
    printf ("<li>%s&nbsp;&nbsp;&nbsp;&nbsp;
    <form action='delete.php' method='POST'>
    <input type='submit' value='delete'/>
    <input type='hidden' name='id' value=%s />
    <input type='hidden' name='token' value=%s /> 
    </form>
    <form action='modify.php' method='POST'>
    <input type='submit' value='edit'/> 
    <input type='hidden' name='id' value=%s />
    <input type='hidden' name='token' value=%s />
    </form></li>",htmlspecialchars( $row["title"] ),
    htmlspecialchars( $row["id"] ),
    $_SESSION['token'],
    htmlspecialchars( $row["id"] ),
    $_SESSION['token']);
}
echo "</ul>\n";
$stmt->close();
?>
	<p class='caption'>!!! COMMENTS !!!</p>
<?php
$stmt = $mysqli->prepare("select story_id,id,comment from comments where username=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
exit;
}
$stmt->bind_param('s', $_SESSION['username']);

$stmt->execute();

$result = $stmt->get_result();

echo "<ul>\n";
while($row = $result->fetch_assoc()){
    printf ("<li>\"%s\"&nbsp;&nbsp;&nbsp;&nbsp;
    <form action='delete_c.php' method='POST'>
    <input type='submit' value='delete' /> 
    <input type='hidden' name='id' value=%s /> 
    <input type='hidden' name='token' value=%s />
    </form>
    <form action='modify_c.php' method='POST'>
    <input type='submit'  value='edit' /> 
    <input type='hidden' name='id' value=%s /> 
    <input type='hidden' name='story_id' value=%s /> 
    <input type='hidden' name='token' value=%s />
    </form></li>",htmlspecialchars( $row["comment"] ),
    htmlspecialchars( $row["id"] ),
    $_SESSION['token'],
    htmlspecialchars( $row["id"] ),
    htmlspecialchars( $row["story_id"] ),
    $_SESSION['token']);
}
echo "</ul>\n";
$stmt->close();
?>
</body>
</html>


