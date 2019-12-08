<?php
//make sure whether the username and password are legal or not
function verify($username,$password){
    if (strlen($username)>20){
        return 0;
    }
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        return 0;
    }
    if( !preg_match('/^[\w_\-]+$/', $password) ){
        return 0;
    }
    return 1;
}
?>