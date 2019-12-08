<?php
// Content of database.php
// username:phpscript
// password:123456
// database name:news
// link to our instance phpmyadmin:
// http://ec2-54-242-152-54.compute-1.amazonaws.com/phpmyadmin/
$mysqli = new mysqli('localhost', 'phpscript', '123456', 'news');

if($mysqli->connect_errno) {
	exit;
}
?>