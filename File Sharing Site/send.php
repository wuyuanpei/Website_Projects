<?php
include "filepath.php";
session_start();
$paths = findpath($_GET["filename"],(String)@$_SESSION['username']);
$pathr = findpath($_GET["filename"],$_GET["receivername"]);
//To verify that the dir exists, or there is no such user
if(!is_dir(dirname($pathr))){
    echo "File Sharing Failed! The user you entered does not exist! 
    <form action='homepage.php' method='GET'>
    <input type='submit' value='return'/>
    </form>";
    exit;
}
//Copy the file to the dir of the receiver
if(copy($paths,$pathr)){
echo "File Sharing Succeed!
    <form action='homepage.php' method='GET'>
    <input type='submit' value='return'/>
    </form>";
}else{
    echo "File Sharing Failed! 
    <form action='homepage.php' method='GET'>
    <input type='submit' value='return'/>
    </form>";
}
?>