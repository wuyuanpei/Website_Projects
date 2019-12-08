<?php
// Get Modules from database
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response
//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];
$note_id = $json_obj['note_id'];
$new_id = (int)((microtime(true)*10)%1000000000);
//Get events from the database

  $stmt = $mysqli->prepare("select  date, course, tag, title from notes where id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
    "message" => "message 1"
		));
	exit;
}else{
    $stmt->bind_param('s',$note_id);

    $stmt->execute();

  	$stmt->bind_result($date, $course, $tag, $title);

}

while($stmt->fetch()){
  //Avoid Persistent XSS
   $stmt_1 = $mysqli2->prepare("insert into notes (id,username, date, course, tag, title) values (?,?, ?, ?, ?, ?)");

if(!$stmt_1){
	echo json_encode(array(
		"success" => false,
    "message" => "message 2"
		));
	exit;
}else{
    $stmt_1->bind_param('ssssss',$new_id,$username, $date, $course, $tag, $title);

    $stmt_1->execute();
if($mysqli2->error!=null){
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! The username does not exist. Please use a valid username."
	));
	exit;
}
$stmt_1->close();
}
}
    $stmt->close();

/////////////////////////////////////////////////////// 2nd table
   $stmt = $mysqli->prepare("select  term, definition, example, remark, seq from definitions where note_id=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
    "message" => "message 3"
		));
	exit;
}else{
    $stmt->bind_param('s',$note_id);

    $stmt->execute();

  	$stmt->bind_result($term, $definition, $example, $remark, $seq);
}

while($stmt->fetch()){
  //Avoid Persistent XSS
   $stmt_1 = $mysqli2->prepare("insert into definitions (username, note_id, date, course, tag, title, term, definition, example, remark, seq ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if(!$stmt_1){
	echo json_encode(array(
		"success" => false,
    "message" => "message 4"
		));
	exit;
}else{
    $stmt_1->bind_param('sssssssssss',$username, $new_id, $date, $course, $tag, $title, $term, $definition, $example, $remark, $seq );

    $stmt_1->execute();
if($mysqli->error!=null){
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! The username does not exist. Please use a valid username."
	));
	exit;
}
}
    $stmt_1->close();
  }
  $stmt->close();
/////////////////////////////////////////////////////////////////////////////////////////////// 3rd table
$stmt = $mysqli->prepare("select question, answer, remark, seq from problems where note_id=?");

if(!$stmt){
echo json_encode(array(
 "success" => false,
 "message" => "message 5"
 ));
exit;
}else{
 $stmt->bind_param('s',$note_id);

 $stmt->execute();

 $stmt->bind_result($question, $answer, $remark, $seq);
}

while($stmt->fetch()){
//Avoid Persistent XSS
$stmt_1 = $mysqli2->prepare("insert into problems (username, note_id, date, course, tag, title, question, answer, remark, seq ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if(!$stmt_1){
echo json_encode(array(
 "success" => false,
 "message" => "message 6"
 ));
exit;
}else{
 $stmt_1->bind_param('ssssssssss',$username, $new_id, $date, $course, $tag, $title, $question, $answer, $remark, $seq );

 $stmt_1->execute();
if($mysqli->error!=null){
echo json_encode(array(
 "success" => false,
 "message" => "Sorry! The username does not exist. Please use a valid username."
));
exit;
}
}
 $stmt_1->close();
}
$stmt->close();
////////////////////////////////////////////////////////////////////////////////// 4th table
$stmt = $mysqli->prepare("select statement, proof, application, remark, seq from theorems where note_id=?");

if(!$stmt){
echo json_encode(array(
 "success" => false,
 "message" => "message 7"
 ));
exit;
}else{
 $stmt->bind_param('s',$note_id);

 $stmt->execute();

 $stmt->bind_result($statement, $proof, $application, $remark, $seq);
}

while($stmt->fetch()){
//Avoid Persistent XSS
$stmt_1 = $mysqli2->prepare("insert into theorems (username, note_id, date, course, tag, title, statement, proof, application, remark, seq ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if(!$stmt_1){
echo json_encode(array(
 "success" => false,
 "message" => "message 8"
 ));
exit;
}else{
 $stmt_1->bind_param('sssssssssss',$username, $new_id, $date, $course, $tag, $title, $statement, $proof, $application, $remark, $seq );

 $stmt_1->execute();
if($mysqli->error!=null){
echo json_encode(array(
 "success" => false,
 "message" => "Sorry! The username does not exist. Please use a valid username."
));
exit;
}
}
 $stmt_1->close();
}
$stmt->close();
/////////////////////////////////////////////////////////////////////////////////// 5th table
$stmt = $mysqli->prepare("select text, seq from texts where note_id=?");

if(!$stmt){
echo json_encode(array(
 "success" => false,
 "message" => "message 9"
 ));
exit;
}else{
 $stmt->bind_param('s',$note_id);

 $stmt->execute();

 $stmt->bind_result($text, $seq);
}

while($stmt->fetch()){
//Avoid Persistent XSS
$stmt_1 = $mysqli2->prepare("insert into texts (username, note_id, date, course, tag, title, text, seq ) values (?, ?, ?, ?, ?, ?, ?, ?)");

if(!$stmt_1){
echo json_encode(array(
 "success" => false,
 "message" => "message 10"
 ));
exit;
}else{
 $stmt_1->bind_param('ssssssss',$username, $new_id, $date, $course, $tag, $title, $text, $seq );

 $stmt_1->execute();
if($mysqli->error!=null){
echo json_encode(array(
 "success" => false,
 "message" => "Sorry! The username does not exist. Please use a valid username."
));
exit;
}
}
 $stmt_1->close();
}
$stmt->close();
echo json_encode(array(
 "success" => true,
 "message" => "Share procedures have succeeded."
));

?>
