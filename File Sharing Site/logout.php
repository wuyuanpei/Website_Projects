<?php
//destroy session and then logout to index.html
session_start();
session_destroy();
header("Location: index.html");
exit;
?>