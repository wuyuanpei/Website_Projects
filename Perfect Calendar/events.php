<?php
// events.php
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response

//Get events from the database
$stmt = $mysqli->prepare("select title, tag, date, id from events where username=?");

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
    
  	$stmt->bind_result($events_title, $events_tag, $events_date,$id);
}
//Send back to the client by a json array(dictionary)
$events_array = array();
while($stmt->fetch()){
  //Avoid Persistent XSS
  $event_array = array(
       "title" => htmlentities($events_title),
       "tag" => htmlentities($events_tag),
       "date" => htmlentities($events_date),
       "id" => htmlentities($id)
     );
    array_push($events_array, $event_array);
  }

echo json_encode($events_array);
$stmt->close();

?>
