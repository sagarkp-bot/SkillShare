<?php
require_once "helpers.php";

$tutorial_id=(int)($_GET["tutorial_id"] ?? 0); // page id
if(!$tutorial_id) json_out([]);

$stmt=$conn->prepare("SELECT comments.comment, comments.created_at, users.name FROM comments JOIN users ON comments.user_id = users.id WHERE comments.tutorial_id = ? ORDER BY comments.created_at ASC");
$stmt->bind_param("i",$tutorial_id);
$stmt->execute();

json_out(rows($stmt->get_result())); // send
