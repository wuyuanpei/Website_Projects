<?php
//share an event: get the event information then insert another entry with the receiver's username
require 'database.php';

header("Content-Type: application/json");// We are sending a JSON response
//For HTTP-only Cookies
ini_set("session.cookie_httponly", 1);
session_start();

//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$id = $json_obj['id'];
$s_username = $json_obj['s_username'];
$token = $json_obj['token'];

if(!hash_equals($_SESSION['token'],$token)){
    echo json_encode(array(
		"success" => false,
		"message" => "Request forgery detected!"
	));
	exit;
}

$username = $_SESSION['username'];

//Get the event from the database
$stmt = $mysqli->prepare("select title, tag, date from events where username=? and id=?");

if(!$stmt){
	echo json_encode(array(
        "success" => false,
        "message" => "Cannot get this event from database!"
		));
	exit;
}

$stmt->bind_param('ss',$username,$id);

$stmt->execute();
    
$stmt->bind_result($event_title, $event_tag, $event_date);

$stmt->fetch();

if($mysqli->error!=null){
	echo json_encode(array(
		"success" => false,
		"message" => "Cannot get this event from database!"
	));
	exit;
}
$stmt->close();
$stmt = $mysqli->prepare("insert into events (title,tag,username,date) values (?,?,?,?)");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "illegal!"
	));
	exit;
}

$stmt->bind_param('ssss', $event_title,$event_tag,$s_username,$event_date);

$stmt->execute();

if($mysqli->error!=null){
	echo json_encode(array(
        "success" => false,
        //Avoid XSS
        "message" => "The username '".htmlentities($s_username)."' is not found!"
	));
	exit;
}
$stmt->close();

echo json_encode(array(
    "success" => true,
    "message" => "Event Shared!"
));

exit;
?>