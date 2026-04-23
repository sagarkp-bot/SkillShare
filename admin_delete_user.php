<?php
require_once "helpers.php";
need_admin(); // admin check

$id=(int)($_POST["id"] ?? 0); // user id
if(!$id) json_out(["success"=>false,"message"=>"No user ID"]);

$check=$conn->prepare("SELECT role FROM users WHERE id = ?");
$check->bind_param("i",$id);
$check->execute();
$user=one_row($check); // user row

if(!$user) json_out(["success"=>false,"message"=>"User not found"]);
if($user["role"]==="admin") json_out(["success"=>false,"message"=>"Admin accounts cannot be deleted"]);

$stmt=$conn->prepare("DELETE FROM users WHERE id = ? AND role <> 'admin'");
$stmt->bind_param("i",$id);

if(!$stmt->execute() || !$stmt->affected_rows) json_out(["success"=>false,"message"=>"Could not delete"]); // delete
log_activity($conn,$_SESSION["user_id"] ?? null,"admin_delete_user","User ID: ".$id); // log
json_out(["success"=>true]);
