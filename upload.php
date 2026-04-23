<?php
require_once "helpers.php";
need_login(); // login check

$user_id=$_SESSION["user_id"]; // user id
$title=post("title");
$category=post("category");
$description=post("description");
$video=post("video");
$type=get_content_type($video);

if(!$title || !$category || !$description) back_alert("Please fill all fields."); // check

$stmt=$conn->prepare("INSERT INTO tutorials (user_id, title, category, description, content_type, video_url) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss",$user_id,$title,$category,$description,$type,$video);

if(!$stmt->execute()) back_alert("Error uploading. Try again."); // save
log_activity($conn,$user_id,"upload_tutorial",$title); // log
go_alert("explore.html","Tutorial uploaded successfully!");
