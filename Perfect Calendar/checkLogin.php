<?php
//Check Whether this user has already logged in. If so, return the information
    header("Content-Type: application/json"); // We are sending a JSON response
    //For HTTP-only Cookies
    ini_set("session.cookie_httponly", 1);
    session_start();
    if(isset($_SESSION['username'])){
        echo json_encode(array(
            "success" => true,
            //Avoid Reflected XSS
            "username" => htmlentities($_SESSION['username']),
            "token" => htmlentities($_SESSION['token'])
        ));
    }else{
        echo json_encode(array(
            "success" => false
        ));
    }
?>