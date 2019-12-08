<?php
//delete an event
require 'database.php';

header("Content-Type: application/json"); // We are sending a JSON response
//For HTTP-only Cookies
ini_set("session.cookie_httponly", 1);
session_start();

//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$id = $json_obj['id'];
$token = $json_obj['token'];

if(!hash_equals($_SESSION['token'],$token)){
    echo json_encode(array(
		"success" => false,
		"message" => "Request forgery detected!"
	));
	exit;
}

$username = $_SESSION['username'];

//delete the event from the table "events"

$stmt = $mysqli->prepare("delete from events where id=? and username=?");
if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "illegal!"
	));
	exit;
}
$stmt->bind_param('ss', $id, $username);

$stmt->execute();

if($mysqli->error!=null){
	echo json_encode(array(
		"success" => false,
		"message" => "illegal!"
	));
	exit;
}
$stmt->close();

echo json_encode(array(
    "success" => true,
    "message" => "Event Deleted!"
));

exit;
?>