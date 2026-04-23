<?php
require_once "helpers.php";
need_login(true); // login check

$tutorial_id=(int)($_POST["tutorial_id"] ?? 0); // form data
$comment=post("comment");
$user_id=$_SESSION["user_id"];

if(!$tutorial_id || !$comment) json_out(["success"=>false,"message"=>"Empty comment"]); // check

$stmt=$conn->prepare("INSERT INTO comments (tutorial_id, user_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis",$tutorial_id,$user_id,$comment);

if(!$stmt->execute()) json_out(["success"=>false,"message"=>"Could not save comment"]); // save
log_activity($conn,$user_id,"add_comment","Tutorial ID: ".$tutorial_id); // log
json_out(["success"=>true,"message"=>"Comment added"]);
