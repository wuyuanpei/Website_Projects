<?php
include 'filepath.php';

session_start();

$filename = $_GET['file'];

$username = $_SESSION['username'];

$full_path = findpath($filename,$username);

//delete the file in the system
unlink($full_path);

header("Location: homepage.php");
exit;

?>