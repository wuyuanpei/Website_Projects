<?php
//insert an event
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
$title = $json_obj['title'];
$tag = $json_obj['tag'];
$token = $json_obj['token'];
$date = $json_obj['date'];

if(!hash_equals($_SESSION['token'],$token)){
    echo json_encode(array(
		"success" => false,
		"message" => "Request forgery detected!"
	));
	exit;
}
//Identify that the timestamp is legal and the title is not null
if(!preg_match('/^\d{4}-\d{1,2}-\d{1,2}\s\d{2}\:\d{2}\:00$/', $date)||$title==""||((int)substr($date,-8,2))>=24||((int)substr($date,-5,2))>=60){
    echo json_encode(array(
		"success" => false,
		"message" => "Inputs are illegal!"
	));
	exit;
}
$username = $_SESSION['username'];

//insert the event into the table "events"
$stmt = $mysqli->prepare("insert into events (title,tag,username,date) values (?,?,?,?)");
if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "Inputs are illegal!"
	));
	exit;
}

$stmt->bind_param('ssss', $title,$tag,$username,$date);

$stmt->execute();

if($mysqli->error!=null){
	echo json_encode(array(
		"success" => false,
		"message" => "Inputs are illegal!"
	));
	exit;
}
$stmt->close();

echo json_encode(array(
    "success" => true,
    "message" => "Event Added!"
));

exit;
?>