<?php
// edit module
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response

//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$id = $json_obj['id'];

$term = $json_obj['term'];
$definition = $json_obj['definition'];
$example = $json_obj['example'];
$remark = $json_obj['remark'];

$stmt = $mysqli->prepare("update definitions set term=?,definition=?,example=?,remark=? where id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! prepare failed"
	));
	exit;
}else{
	$stmt->bind_param('sssss', $term, $definition, $example, $remark, $id);

	$stmt->execute();

	$stmt->close();
}
echo json_encode(array(
    "success" => true
));
exit;
?>