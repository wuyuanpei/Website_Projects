<?php
//verify the filename, username, and return the path of the file
//two verifying functions from class wiki
function findpath($filename, $username){
    if( !preg_match('/^[\w_\.\-\s]+$/', $filename) ){
        echo 'Sorry! Invalid filename: filename can only contain A-Za-z0-9_.-';
        exit;
    }

    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo "Sorry! Invalid username: username can only contain A-Za-z0-9_-";
        exit;
    }
    return sprintf("/srv/Module2-Group/%s/%s", $username, $filename);
}
?>