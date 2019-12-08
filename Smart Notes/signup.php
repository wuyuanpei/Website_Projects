<?php
// signup.php
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

//verify input
function verify($username,$password,$identity){
    if (strlen($username)>20){
        return 0;
    }
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        return 0;
	}
	if (strlen($password)<1){
        return 0;
	}
	if(($identity!="student")&&($identity!="professor")){
		return 0;
	}
    return 1;
}
if(!verify($username,$password,$identity)){
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! Your username/password is illegal!"
	));
	exit;
}
$stmt = $mysqli->prepare("insert into users (username,password,identity) values (?, ?, ?)");

if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! Your username is illegal or has already been used by others. Please change a username."
	));
	exit;
}else{
	$iden = "";
	if($identity=="student")
		$iden = "s";
	if($identity=="professor")
		$iden = "p";
	$stmt->bind_param('sss', $username, password_hash($password,PASSWORD_BCRYPT),$iden);

	$stmt->execute();

if($mysqli->error!=null){
	echo json_encode(array(
		"success" => false,
		"message" => "Sorry! Your username has already been used by others. Please change a username."
	));
	exit;
}
}
$stmt->close();

session_start();
//For HTTP-only Cookies
ini_set("session.cookie_httponly", 1);

$_SESSION['username'] = $username;
$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

echo json_encode(array(
	"success" => true,
	"token" => htmlentities($_SESSION['token']),
	"username" => $username
));

?>