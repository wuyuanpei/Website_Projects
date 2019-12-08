<?php
// connect to the database
require 'database.php';
header("Content-Type: application/json"); // We are sending a JSON response
//Posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

$title = $json_obj[0][0];
$course = $json_obj[0][1];
$date = $json_obj[0][2];
$tag = $json_obj[0][3];

$note_id = (int)((microtime(true)*10)%1000000000);

//create a new note
$stmt = $mysqli->prepare("insert into notes (id,username,date,tag,course,title) values (?,?,?,?,?,?)");

if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => "Sorry! Insert into notes prepare statement failed!"
    ));
    exit;
}else{

    session_start();
    $stmt->bind_param('ssssss', $note_id,$_SESSION["username"],$date,$tag,$course,$title);

    $stmt->execute();

    if($mysqli->error!=null){
        echo json_encode(array(
        "success" => false,
        "message" => $mysqli->error
        ));
        exit;
    }
}
$stmt->close();

//From 1
$i = 1;
$seq = 0;
for(;$i<sizeof($json_obj);$i++){
    if($json_obj[$i][0]=="d"){
    //Insert Definition

    $stmt = $mysqli->prepare("insert into definitions (username,note_id,date,title,tag,course,term,definition,example,remark,seq) values (?,?,?,?,?,?,?,?,?,?,?)");

    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Sorry!"
        ));
        exit;
    }else{
        $seq = $i-1;
        $stmt->bind_param('sssssssssss',$_SESSION["username"], $note_id, $date,$title,$tag,$course,$json_obj[$i][1],$json_obj[$i][2],$json_obj[$i][3],$json_obj[$i][4],$seq);
    
        $stmt->execute();
    
        if($mysqli->error!=null){
            echo json_encode(array(
            "success" => false,
            "message" => "Sorry! Insert into definitions failed!"
            ));
            exit;
        }
    }
    $stmt->close();
    }
    else if($json_obj[$i][0]=="t"){
    //Insert Theorem

    $stmt = $mysqli->prepare("insert into theorems (username,note_id,date,title,tag,course,statement,proof,application,remark,seq) values (?,?,?,?,?,?,?,?,?,?,?)");

    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Sorry!"
        ));
        exit;
    }else{
        $seq = $i-1;
        $stmt->bind_param('sssssssssss',$_SESSION["username"], $note_id, $date,$title,$tag,$course,$json_obj[$i][1],$json_obj[$i][2],$json_obj[$i][3],$json_obj[$i][4],$seq);
    
        $stmt->execute();
    
        if($mysqli->error!=null){
            echo json_encode(array(
            "success" => false,
            "message" => "Sorry! Insert into theorems failed!"
            ));
            exit;
        }
    }
    $stmt->close();
    }
    else if($json_obj[$i][0]=="p"){
        //problems

    $stmt = $mysqli->prepare("insert into problems (username,note_id,date,title,tag,course,question,answer,remark,seq) values (?,?,?,?,?,?,?,?,?,?)");

    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Sorry!"
        ));
        exit;
    }else{
        $seq = $i-1;
        $stmt->bind_param('ssssssssss',$_SESSION["username"], $note_id, $date,$title,$tag,$course,$json_obj[$i][1],$json_obj[$i][2],$json_obj[$i][3],$seq);
    
        $stmt->execute();
    
        if($mysqli->error!=null){
            echo json_encode(array(
            "success" => false,
            "message" => "Sorry! Insert into problems failed!"
            ));
            exit;
        }
    }
    $stmt->close();
    }
    else if($json_obj[$i][0]=="e"){
        //Insert Text

    $stmt = $mysqli->prepare("insert into texts (username,note_id,date,title,tag,course,text,seq) values (?,?,?,?,?,?,?,?)");

    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Sorry!"
        ));
        exit;
    }else{
        $seq = $i-1;
        $stmt->bind_param('ssssssss',$_SESSION["username"], $note_id, $date,$title,$tag,$course,$json_obj[$i][1],$seq);
    
        $stmt->execute();
    
        if($mysqli->error!=null){
            echo json_encode(array(
            "success" => false,
            "message" => "Sorry! Insert into texts failed!"
            ));
            exit;
        }
    }
    $stmt->close();
    }
}
echo json_encode(array(
    "success" => true,
    "message" => "Succeed!"
    ));
    exit;
?>