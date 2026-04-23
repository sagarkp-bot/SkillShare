<?php
require_once "helpers.php";
need_admin(); // admin check

$result=$conn->query("SELECT skill_requests.id, skill_requests.skill, skill_requests.details, skill_requests.status, users.name AS requested_by FROM skill_requests JOIN users ON skill_requests.user_id = users.id ORDER BY skill_requests.created_at DESC");
json_out(rows($result)); // send
