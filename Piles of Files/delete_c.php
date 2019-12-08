<?php
require "database.php";
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

header("Location: edit.php");

exit;
?>