<?php
// delete module
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response

//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$id = $json_obj['id'];

$stmt = $mysqli->prepare("delete from problems where id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! prepare failed"
	));
	exit;
}else{
	$stmt->bind_param('s',$id);

	$stmt->execute();

	$stmt->close();
}
echo json_encode(array(
    "success" => true
));
exit;
?>