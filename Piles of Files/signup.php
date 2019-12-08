<?php
require 'database.php';
include "verify.php";
$user = $_POST['username'];
$pwd = $_POST['password'];
//from class wiki: verify the input username
if(!verify($user,$pwd)) {
    echo "Sorry! Invalid username or password: can only contain A-Za-z0-9_-. Your username should not be more than 20 characters.";
    exit;
}

$stmt = $mysqli->prepare("insert into users (username,password) values (?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ss', $user, password_hash($pwd,PASSWORD_BCRYPT));

$stmt->execute();

if($mysqli->error!=null){
	echo "Sorry! Your username has already been used by others.";
	exit;
}
$stmt->close();

session_start();

$_SESSION['username'] = $user;
$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

header("Location: homepage.php");

exit;
?>