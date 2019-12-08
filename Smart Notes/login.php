<?php
// login.php
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response

//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];
$password = $json_obj['password'];
$identity = $json_obj['identity'];
if($identity=="student")
	$identity = "s";
if($identity=="professor")
	$identity = "p";

$stmt = $mysqli->prepare("select COUNT(*),username,password,identity from users where username=?");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! Incorrect Username/Password/Identity"
	));
	exit;
}else{
	$stmt->bind_param('s', $username);

	$stmt->execute();

	$stmt->bind_result($cnt, $user_id, $pwd_hash, $iden);

	$stmt->fetch();

	$stmt->close();
}
// Check to see if the username and password are valid.
if($cnt == 1 && password_verify($password, $pwd_hash) && $identity==$iden){
	session_start();
	//For HTTP-only Cookies
	ini_set("session.cookie_httponly", 1);
	$_SESSION['username'] = $username;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 
	echo json_encode(array(
		"success" => true,
		"token" => htmlentities($_SESSION['token']),
		"username"=>$username
	));
	exit;
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! Incorrect Username/Password/Identity"
	));
	exit;
}
?>