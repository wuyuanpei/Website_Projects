<?php
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response
//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$note_id= $json_obj['note_id'];

// $stmt = $mysqli->prepare("select id, text, course, tag, date, title from notes where username=? and note_id=?");

// if(!$stmt){
// 	echo json_encode(array(
// 		"success" => false,
// 		));
// 	exit;
// }else{
//     //For HTTP-only Cookies
//     ini_set("session.cookie_httponly", 1);
//     session_start();
//     $stmt->bind_param('ss',$_SESSION["username"]);

//     $stmt->execute();

//   	$stmt->bind_result($id, $example ,$term, $definition, $remark, $course, $tag, $date, $seq, $title);
// }
// //Send back to the client by a json array(dictionary)
// $courses_array = array();
// while($stmt->fetch()){
//   //Avoid Persistent XSS
//   $course_array = array(
//        "id" => htmlentities($id),
//        "example" => htmlentities($example),
//        "term" => htmlentities($term),
//        "definition" => htmlentities($definition),
//        "remark" => htmlentities($remark),
//        "course" => htmlentities($course),
//        "tag" => htmlentities($tag),
//        "date" => htmlentities($date),
//        "seq" => htmlentities($seq),
//        "title" => htmlentities($title)
//      );
//     array_push($courses_array, $course_array);
//   }

// $stmt->close();


$stmt = $mysqli->prepare("select question ,answer, remark, course, tag, seq, date, title from problems where username=? and note_id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		));
	exit;
}else{
    //For HTTP-only Cookies
    ini_set("session.cookie_httponly", 1);
    session_start();
    $stmt->bind_param('ss',$_SESSION["username"],$note_id);

    $stmt->execute();

  	$stmt->bind_result($question ,$answer, $remark, $course, $tag, $seq, $date, $title);
}
//Send back to the client by a json array(dictionary)
$courses_array = array();
while($stmt->fetch()){
  //Avoid Persistent XSS
  $course_array = array(
    "type" => htmlentities("q"),
    "question" => htmlentities($question),
    "answer" => htmlentities($answer),
    "remark" => htmlentities($remark),
    "course" => htmlentities($course),
    "tag" => htmlentities($tag),
    "date" => htmlentities($date),
    "title" => htmlentities($title),
    "seq" => htmlentities($seq)
     );
    array_push($courses_array, $course_array);
  }

$stmt->close();

//Delete it after get the data
$stmt = $mysqli->prepare("delete from problems where username=? and note_id=?");

$stmt->bind_param('ss',$_SESSION["username"],$note_id);

$stmt->execute();

$stmt->close();



$stmt = $mysqli->prepare("select statement, proof, application, remark, course, tag, date, seq, title from theorems where username=? and note_id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		));
	exit;
}else{
    //For HTTP-only Cookies
    ini_set("session.cookie_httponly", 1);
    $stmt->bind_param('ss',$_SESSION["username"],$note_id);

    $stmt->execute();

  	$stmt->bind_result($statement ,$proof, $application, $remark, $course, $tag, $date,$seq, $title);
}
//Send back to the client by a json array(dictionary)

while($stmt->fetch()){
  //Avoid Persistent XSS
  $course_array = array(
    "type" => htmlentities("t"),
    "statement" => htmlentities($statement),
    "proof" => htmlentities($proof),
    "application" => htmlentities($application),
    "remark" => htmlentities($remark),
    "course" => htmlentities($course),
    "tag" => htmlentities($tag),
    "date" => htmlentities($date),
    "title" => htmlentities($title),
    "seq" => htmlentities($seq)
     );
    array_push($courses_array, $course_array);
  }

$stmt->close();


//Delete it after get the data
$stmt = $mysqli->prepare("delete from theorems where username=? and note_id=?");

$stmt->bind_param('ss',$_SESSION["username"],$note_id);

$stmt->execute();

$stmt->close();


$stmt = $mysqli->prepare("select example,term, definition, remark, course, tag, date, seq, title from definitions where username=? and note_id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		));
	exit;
}else{
    //For HTTP-only Cookies
    ini_set("session.cookie_httponly", 1);
    $stmt->bind_param('ss',$_SESSION["username"],$note_id);

    $stmt->execute();

  	$stmt->bind_result($example ,$term, $definition, $remark, $course, $tag, $date, $seq, $title);
}
//Send back to the client by a json array(dictionary)

while($stmt->fetch()){
  //Avoid Persistent XSS
  $course_array = array(
      "type" => htmlentities("d"),
       "example" => htmlentities($example),
       "term" => htmlentities($term),
       "definition" => htmlentities($definition),
       "remark" => htmlentities($remark),
       "course" => htmlentities($course),
       "tag" => htmlentities($tag),
       "date" => htmlentities($date),
       "seq" => htmlentities($seq),
       "title" => htmlentities($title)
     );
    array_push($courses_array, $course_array);
  }

$stmt->close();


//Delete it after get the data
$stmt = $mysqli->prepare("delete from definitions where username=? and note_id=?");

$stmt->bind_param('ss',$_SESSION["username"],$note_id);

$stmt->execute();

$stmt->close();


$stmt = $mysqli->prepare("select text, course, tag, date, seq, title from texts where username=? and note_id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		));
	exit;
}else{
    //For HTTP-only Cookies
    ini_set("session.cookie_httponly", 1);
    $stmt->bind_param('ss',$_SESSION["username"],$note_id);

    $stmt->execute();

  	$stmt->bind_result($text, $course, $tag, $date, $seq, $title);
}
//Send back to the client by a json array(dictionary)

while($stmt->fetch()){
  //Avoid Persistent XSS
  $course_array = array(
      "type" => htmlentities("e"),
       "text" => htmlentities($text),
       "course" => htmlentities($course),
       "tag" => htmlentities($tag),
       "date" => htmlentities($date),
       "seq" => htmlentities($seq),
       "title" => htmlentities($title)
     );
    array_push($courses_array, $course_array);
  }

$stmt->close();

//Delete it after get the data
$stmt = $mysqli->prepare("delete from texts where username=? and note_id=?");

$stmt->bind_param('ss',$_SESSION["username"],$note_id);

$stmt->execute();

$stmt->close();

//sort the array in ascending order, according to the key
array_multisort(array_column($courses_array,'seq'),SORT_ASC,$courses_array);

//Get the note info
$stmt = $mysqli->prepare("select course, tag, date, title from notes where username=? and id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		));
	exit;
}else{
    //For HTTP-only Cookies
    ini_set("session.cookie_httponly", 1);
    $stmt->bind_param('ss',$_SESSION["username"],$note_id);

    $stmt->execute();

  	$stmt->bind_result($course, $tag, $date, $title);
}
//Send back to the client by a json array(dictionary)

while($stmt->fetch()){
  //Avoid Persistent XSS
  $course_array = array(
       "course" => htmlentities($course),
       "tag" => htmlentities($tag),
       "date" => htmlentities($date),
       "title" => htmlentities($title)
     );
    array_push($courses_array, $course_array);
  }

$stmt->close();

//Delete it after get the data
$stmt = $mysqli->prepare("delete from texts where username=? and note_id=?");

$stmt->bind_param('ss',$_SESSION["username"],$note_id);

$stmt->execute();

$stmt->close();



//Delete it after get the data
$stmt = $mysqli->prepare("delete from notes where username=? and id=?");

$stmt->bind_param('ss',$_SESSION["username"],$note_id);

$stmt->execute();

$stmt->close();


echo json_encode($courses_array);


?>
