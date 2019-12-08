<?php
include 'filepath.php';
session_start();

$filename = basename($_FILES['uploadedfile']['name']);

$username = $_SESSION['username'];

$full_path = findpath($filename,$username);

//from class wiki: upload file and then direct to homepage
if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
	header("Location: homepage.php");
	exit;
}else{
	echo "Sorry! Upload Error: your file should be no more than 2MB";
}

?>