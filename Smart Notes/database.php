<?php
// Content of database.php
// username:phpscript
// password:123456
// database name:SmartNote
// link to our instance phpmyadmin:
// http://ec2-54-242-152-54.compute-1.amazonaws.com/phpmyadmin/
$mysqli = new mysqli('localhost', 'phpscript', '123456', 'SmartNote');
$mysqli2 = new mysqli('localhost', 'phpscript', '123456', 'SmartNote');

if($mysqli->connect_errno) {
	exit;
}
?>