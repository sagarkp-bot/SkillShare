<?php
require_once "helpers.php";
need_admin(); // admin check

$id=(int)($_POST["id"] ?? 0); // tutorial id
if(!$id) json_out(["success"=>false,"message"=>"No tutorial ID"]);

$stmt=$conn->prepare("DELETE FROM tutorials WHERE id = ?");
$stmt->bind_param("i",$id);

if(!$stmt->execute() || !$stmt->affected_rows) json_out(["success"=>false,"message"=>"Could not delete"]); // delete
log_activity($conn,$_SESSION["user_id"] ?? null,"admin_delete_tutorial","Tutorial ID: ".$id); // log
json_out(["success"=>true]);
