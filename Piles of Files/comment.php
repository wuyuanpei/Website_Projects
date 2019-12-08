<!DOCTYPE html>
<html>
<head>
	<title>Comment Submit!</title>
	<style>
		body {
    	background-color: Black;
    	color : White; 
		font-size: 400%; 
	}
	</style>
</head>

<body>
<?php
require 'database.php';
$stmt = $mysqli->prepare("insert into comments (story_id,comment,username) values (?,?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
session_start();
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
$stmt->bind_param('sss',$_POST['story_id'],$_POST['comment'],$_POST['username']);

$stmt->execute();
if($mysqli->error!=null){
	echo $mysqli->error;
	exit;
}
$stmt->close();

header("Location: story.php?id=".$_POST['story_id']);
exit;
?>
</body>
</html>