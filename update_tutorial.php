<?php
require_once "helpers.php";
need_login(); // login check

$user_id=$_SESSION["user_id"]; // user id
$id=(int)($_POST["id"] ?? 0);
$title=post("title");
$category=post("category");
$description=post("description");
$video=post("video");
$type=get_content_type($video);

if(!$title || !$category || !$description) back_alert("Please fill all fields."); // check

$check=$conn->prepare("SELECT id FROM tutorials WHERE id = ? AND user_id = ?");
$check->bind_param("ii",$id,$user_id);
$check->execute();
if(!one_row($check)) go_alert("profile.html","Access denied.");

$stmt=$conn->prepare("UPDATE tutorials SET title = ?, category = ?, description = ?, content_type = ?, video_url = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("sssssii",$title,$category,$description,$type,$video,$id,$user_id);

if(!$stmt->execute()) back_alert("Error updating. Try again."); // save
log_activity($conn,$user_id,"update_tutorial",$title); // log
go_alert("profile.html","Tutorial updated!");
