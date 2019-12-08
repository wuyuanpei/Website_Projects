<?php
// dashboard.php Get Notes from database
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response
//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$seq = $json_obj['seq'];

//Get events from the database
if($seq=="date")
  $stmt = $mysqli->prepare("select id, date, tag, course, title from notes where username=? order by date");
else if($seq=="tag")
  $stmt = $mysqli->prepare("select id, date, tag, course, title from notes where username=? order by tag");
else
  $stmt = $mysqli->prepare("select id, date, tag, course, title from notes where username=? order by course");

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
    
  	$stmt->bind_result($id, $date, $tag,$course,$title);
}
//Send back to the client by a json array(dictionary)
$courses_array = array();
while($stmt->fetch()){
  //Avoid Persistent XSS
  $course_array = array(
       "id" => htmlentities($id),
       "date" => htmlentities($date),
       "tag" => htmlentities($tag),
       "course" => htmlentities($course),
       "title" => htmlentities($title)
     );
    array_push($courses_array, $course_array);
  }

echo json_encode($courses_array);
$stmt->close();

?>
