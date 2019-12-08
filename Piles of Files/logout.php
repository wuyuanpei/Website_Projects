<?php
//destroy session and then logout
session_start();
session_destroy();
header("Location: homepage.php");
exit;
?>