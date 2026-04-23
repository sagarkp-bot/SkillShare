<?php
require_once "helpers.php";
need_admin(); // admin check

$result=$conn->query("SELECT activity_logs.id, activity_logs.action, activity_logs.details, activity_logs.created_at, users.name, users.email FROM activity_logs LEFT JOIN users ON activity_logs.user_id = users.id ORDER BY activity_logs.id DESC LIMIT 100");
json_out(rows($result)); // send
