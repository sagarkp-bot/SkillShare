<?php
require_once "helpers.php";
need_login(true); // login check

$user_id=$_SESSION["user_id"]; // user id
$stmt=$conn->prepare("SELECT tutorials.*, COUNT(DISTINCT likes.id) AS like_count, COUNT(DISTINCT comments.id) AS comment_count FROM tutorials LEFT JOIN likes ON tutorials.id = likes.tutorial_id LEFT JOIN comments ON tutorials.id = comments.tutorial_id WHERE tutorials.user_id = ? GROUP BY tutorials.id ORDER BY tutorials.created_at DESC");
$stmt->bind_param("i",$user_id);
$stmt->execute();

json_out(["name"=>$_SESSION["name"],"tutorials"=>rows($stmt->get_result())]); // send
