<?php
require_once "helpers.php";
need_login(true); // login check

$user_id=$_SESSION["user_id"]; // user id
$tutorial_id=(int)($_POST["tutorial_id"] ?? 0);
$reason=post("reason");

if(!$tutorial_id || !$reason) json_out(["success"=>false,"message"=>"Please provide a reason."]); // check

$stmt=$conn->prepare("INSERT INTO reports (tutorial_id, user_id, reason) VALUES (?, ?, ?)");
$stmt->bind_param("iis",$tutorial_id,$user_id,$reason);

if(!$stmt->execute()) json_out(["success"=>false,"message"=>"Could not submit report."]); // save
log_activity($conn,$user_id,"report_tutorial","Tutorial ID: ".$tutorial_id); // log
json_out(["success"=>true]);
