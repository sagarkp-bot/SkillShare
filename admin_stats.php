<?php
require_once "helpers.php";
need_admin(); // admin check

$users=$conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()["count"]; // total
$tutorials=$conn->query("SELECT COUNT(*) AS count FROM tutorials")->fetch_assoc()["count"];
$comments=$conn->query("SELECT COUNT(*) AS count FROM comments")->fetch_assoc()["count"];

$user_rows=rows($conn->query("SELECT id, name, email, role FROM users ORDER BY id")); // rows
$tutorial_rows=rows($conn->query("SELECT tutorials.id, tutorials.title, tutorials.category, users.name AS author FROM tutorials JOIN users ON tutorials.user_id = users.id ORDER BY tutorials.created_at DESC"));

json_out([
    "user_count"=>$users,
    "tut_count"=>$tutorials,
    "comment_count"=>$comments,
    "users"=>$user_rows,
    "tutorials"=>$tutorial_rows
]);
