<?php
require_once "helpers.php";
need_login(true); // login check

$user_id=$_SESSION["user_id"]; // user id
$tutorial_id=(int)($_POST["id"] ?? 0);

$check=$conn->prepare("SELECT id FROM tutorials WHERE id = ? AND user_id = ?");
$check->bind_param("ii",$tutorial_id,$user_id);
$check->execute();
if(!one_row($check)) json_out(["success"=>false,"message"=>"Not your tutorial."]); // check

$stmt=$conn->prepare("DELETE FROM tutorials WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii",$tutorial_id,$user_id);

if(!$stmt->execute() || !$stmt->affected_rows) json_out(["success"=>false,"message"=>"Could not delete."]); // delete
log_activity($conn,$user_id,"delete_own_tutorial","Tutorial ID: ".$tutorial_id); // log
json_out(["success"=>true]);
