<?php
require "database.php";
session_start();
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
$stmt = $mysqli->prepare("delete from comments where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $_POST['id']);

$stmt->execute();

if($mysqli->error!=null){
	echo $mysqli->error;
	exit;
}
$stmt->close();

$stmt = $mysqli->prepare("delete from stories where id=? and username=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ss', $_POST['id'], $_SESSION['username']);

$stmt->execute();

if($mysqli->error!=null){
	echo $mysqli->error;
	exit;
}
$stmt->close();

header("Location: edit.php");

exit;
?>