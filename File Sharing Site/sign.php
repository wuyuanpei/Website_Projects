<?php
$user = $_GET['username'];
//from class wiki: verify the input username
if( !preg_match('/^[\w_\-]+$/', $user) ){
    echo "Sorry! Invalid username: username can only contain A-Za-z0-9_-";
    exit;
}
//verify that this is a new user
$h = fopen("/srv/Module2-Group/users.txt", "r+");
$linenum = 1;
while( !feof($h) ){
	$xuser = trim(fgets($h));
	if( $user == $xuser){
		echo "User Existed! Please directly login!";
		fclose($h);
		exit;
	}
	$linenum++;
}
//add this user to users.txt
fwrite($h,$user."\n");
fclose($h);
mkdir("/srv/Module2-Group/".$user);
session_start();
$_SESSION['username'] = $user;
header("Location: homepage.php");
exit;
?>