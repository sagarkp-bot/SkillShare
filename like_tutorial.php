<?php
require_once "helpers.php";

function like_count($conn,$tutorial_id){ // like count
    $stmt=$conn->prepare("SELECT COUNT(*) AS total FROM likes WHERE tutorial_id = ?");
    $stmt->bind_param("i",$tutorial_id);
    $stmt->execute();
    $row=one_row($stmt);
    return (int)($row["total"] ?? 0);
}

$tutorial_id=(int)($_GET["tutorial_id"] ?? $_POST["tutorial_id"] ?? 0); // form data
$user_id=$_SESSION["user_id"] ?? 0;

if($_SERVER["REQUEST_METHOD"]==="GET"){ // get state
    $liked=false;
    if($user_id){
        $check=$conn->prepare("SELECT id FROM likes WHERE tutorial_id = ? AND user_id = ?");
        $check->bind_param("ii",$tutorial_id,$user_id);
        $check->execute();
        $liked=(bool)one_row($check);
    }
    json_out(["count"=>like_count($conn,$tutorial_id),"liked"=>$liked]);
}

if(!$user_id) json_out(["error"=>"not_logged_in"]); // login check

$check=$conn->prepare("SELECT id FROM likes WHERE tutorial_id = ? AND user_id = ?");
$check->bind_param("ii",$tutorial_id,$user_id);
$check->execute();
$liked=(bool)one_row($check); // current state

if($liked){
    $stmt=$conn->prepare("DELETE FROM likes WHERE tutorial_id = ? AND user_id = ?");
    $stmt->bind_param("ii",$tutorial_id,$user_id);
    $stmt->execute();
    $liked=false;
    log_activity($conn,$user_id,"unlike_tutorial","Tutorial ID: ".$tutorial_id);
}else{
    $stmt=$conn->prepare("INSERT INTO likes (tutorial_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii",$tutorial_id,$user_id);
    $stmt->execute();
    $liked=true;
    log_activity($conn,$user_id,"like_tutorial","Tutorial ID: ".$tutorial_id);
}

json_out(["liked"=>$liked,"count"=>like_count($conn,$tutorial_id)]); // send
