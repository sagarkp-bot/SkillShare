<?php
require_once "helpers.php";
need_login(); // login check

$user_id=$_SESSION["user_id"]; // user id
$skill=post("skill");
$details=post("details");

if(!$skill) back_alert("Please enter a skill name."); // check

$stmt=$conn->prepare("INSERT INTO skill_requests (user_id, skill, details) VALUES (?, ?, ?)");
$stmt->bind_param("iss",$user_id,$skill,$details);

if(!$stmt->execute()) back_alert("Error submitting request. Try again."); // save
log_activity($conn,$user_id,"submit_skill_request",$skill); // log
go_alert("explore.html","Request submitted! We will notify you when it is available.");
