<?php
//if the user enter nothing in the login, directly return
if($_GET['username']==''){
	header("Location: NotFound.html");
exit;
}
//match the username with the entries in users.txt
$h = fopen("/srv/Module2-Group/users.txt", "r");
$linenum = 1;
while( !feof($h) ){
	$user = trim(fgets($h));
	if( $user == $_GET['username']){
		session_start();
		$_SESSION['username'] = $user;
		header("Location: homepage.php");
		fclose($h);
		exit;
	}
	$linenum++;
}
//return if no such username is found in users.txt
header("Location: NotFound.html");
fclose($h);
exit;
?>
