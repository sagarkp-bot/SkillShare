<?php
require_once "helpers.php";

$id=(int)($_GET["id"] ?? 0); // page id
if(!$id) json_out(["error"=>"No tutorial ID given"]);

if(getv("track","1")!=="0"){ // view count
    $view=$conn->prepare("UPDATE tutorials SET views = views + 1 WHERE id = ?");
    $view->bind_param("i",$id);
    $view->execute();
}

$stmt=$conn->prepare("SELECT tutorials.*, users.name AS author, COUNT(DISTINCT likes.id) AS like_count, COUNT(DISTINCT comments.id) AS comment_count FROM tutorials JOIN users ON tutorials.user_id = users.id LEFT JOIN likes ON tutorials.id = likes.tutorial_id LEFT JOIN comments ON tutorials.id = comments.tutorial_id WHERE tutorials.id = ? GROUP BY tutorials.id");
$stmt->bind_param("i",$id);
$stmt->execute();
$tutorial=one_row($stmt); // row

json_out($tutorial ?: ["error"=>"Tutorial not found"]);
