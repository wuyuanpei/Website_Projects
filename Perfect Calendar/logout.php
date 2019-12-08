<?php
//Check Whether this user has already logged in. If so, return the information
    header("Content-Type: application/json"); // We are sending a JSON response
    //For HTTP-only Cookies
    ini_set("session.cookie_httponly", 1);
    session_start();
    session_destroy();
    echo json_encode(array(
        "message" => "logged out"
    ));
?>