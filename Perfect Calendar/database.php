<?php
$mysqli = new mysqli('localhost', 'phpscript', '123456', 'calendar');

if($mysqli->connect_errno) {
	exit;
}
?>