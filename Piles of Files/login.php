<?php
require 'database.php';
include "verify.php";

$user_guess = $_POST['username'];
$pwd_guess = $_POST['password'];

//if the user enter nothing in the login, directly return
if(!verify($user_guess,$pwd_guess)){
	header("Location: NotFound.html");
	exit;
}

$stmt = $mysqli->prepare("select COUNT(*),username,password from users where username=?");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $user_guess);

$stmt->execute();

$stmt->bind_result($cnt, $user_id, $pwd_hash);

$stmt->fetch();

$stmt->close();

// Compare the submitted password to the actual password hash
if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
	// Login succeeded!
	session_start();
	$_SESSION['username'] = $user_id;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	header("Location: homepage.php");
	exit;
} else{
	// Login failed
	header("Location: NotFound.html");
	exit;
}
?>
