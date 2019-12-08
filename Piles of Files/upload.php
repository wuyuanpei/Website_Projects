<?php
require 'database.php';
session_start();
$title = $_POST['title'];
$link = $_POST['link'];
$content = $_POST['content'];
$username = $_SESSION['username'];
date_default_timezone_set('America/Chicago');
$time = date("Y-m-d H:i:s");
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//insert the story into the table "stories"
$stmt = $mysqli->prepare("insert into stories (title,content,username,likes,dislikes,date,link) values (?,?,?,0,0,?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('sssss', $title,$content,$username,$time,$link);

$stmt->execute();

if($mysqli->error!=null){
	echo $mysqli->error;
	exit;
}
$stmt->close();

header("Location: homepage.php");

exit;
?>