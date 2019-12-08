<?php
include 'filepath.php';

session_start();

$filename = $_GET['file'];

$username = $_SESSION['username'];

$full_path = findpath($filename,$username);

// from class wiki
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($full_path);

// from class wiki
header("Content-Type: ".$mime);
readfile($full_path);

?>