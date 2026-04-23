<?php
require_once "helpers.php";
need_admin(); // admin check

$result=$conn->query("SELECT reports.id, reports.reason, reports.status, tutorials.title AS tutorial_title, tutorials.id AS tutorial_id, users.name AS reported_by FROM reports JOIN tutorials ON reports.tutorial_id = tutorials.id JOIN users ON reports.user_id = users.id ORDER BY reports.created_at DESC");
json_out(rows($result)); // send
