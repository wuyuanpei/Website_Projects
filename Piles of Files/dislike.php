<?php
require 'database.php';

$story_id = $_POST['story_id'];

$stmt = $mysqli->prepare("update stories set dislikes=dislikes+1 where id=?");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $story_id);

$stmt->execute();

header("Location: story.php?id=".$story_id);
exit;

?>
