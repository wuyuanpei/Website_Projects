<?php
// Get Courses from database
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response
//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

$stmt = $mysqli->prepare("select distinct course from notes where username=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		));
	exit;
}else{
    //For HTTP-only Cookies
    ini_set("session.cookie_httponly", 1);
    session_start();
    $stmt->bind_param('s',$_SESSION["username"]);

    $stmt->execute();

  	$stmt->bind_result($course);
}
//Send back to the client by a json array(dictionary)
$courses_array = array();
while($stmt->fetch()){
    array_push($courses_array, $course);
}

echo json_encode($courses_array);
$stmt->close();

?>
