<?php
require "database.php";
session_start();
$stmt = $mysqli->prepare("select comment from comments where id=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('s', $_POST['id']);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$comment_c = htmlspecialchars( $row["comment"] );

$_SESSION['comment_c'] = $comment_c;

$stmt->close();

$stmt = $mysqli->prepare("delete from comments where id=? and username=?;");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
session_start();
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
$stmt->bind_param('ss', $_POST['id'], $_SESSION['username']);

$stmt->execute();

if($mysqli->error!=null){
	echo $mysqli->error;
	exit;
}
$stmt->close();
$s = "Location: story.php?id=" . $_POST['story_id'];
header($s);

exit;
?>