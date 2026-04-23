<?php
require_once "helpers.php";
need_admin(); // admin check

$totals=$conn->query("SELECT (SELECT COUNT(*) FROM users) AS users, (SELECT COUNT(*) FROM tutorials) AS tutorials, (SELECT COUNT(*) FROM comments) AS comments, (SELECT COUNT(*) FROM likes) AS likes, (SELECT COUNT(*) FROM skill_requests) AS requests, (SELECT COUNT(*) FROM reports) AS reports, (SELECT COALESCE(SUM(views), 0) FROM tutorials) AS views")->fetch_assoc(); // totals
$types=rows($conn->query("SELECT content_type, COUNT(*) AS total FROM tutorials GROUP BY content_type")); // types
$popular=rows($conn->query("SELECT tutorials.id, tutorials.title, tutorials.category, tutorials.content_type, tutorials.views, users.name AS author, COUNT(DISTINCT likes.id) AS like_count, COUNT(DISTINCT comments.id) AS comment_count FROM tutorials JOIN users ON tutorials.user_id = users.id LEFT JOIN likes ON tutorials.id = likes.tutorial_id LEFT JOIN comments ON tutorials.id = comments.tutorial_id GROUP BY tutorials.id ORDER BY like_count DESC, tutorials.views DESC, comment_count DESC LIMIT 10")); // popular

json_out(["totals"=>$totals,"content_types"=>$types,"popular"=>$popular]);
