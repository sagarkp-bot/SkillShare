<?php
require_once "helpers.php";
need_admin(); // admin check

$id=(int)($_POST["id"] ?? 0); // report id
$stmt=$conn->prepare("UPDATE reports SET status = 'resolved' WHERE id = ?");
$stmt->bind_param("i",$id);

if(!$stmt->execute()) json_out(["success"=>false]); // save
log_activity($conn,$_SESSION["user_id"] ?? null,"resolve_report","Report ID: ".$id); // log
json_out(["success"=>true]);
