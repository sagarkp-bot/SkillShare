<?php
session_start(); // session
header("Content-Type: application/json");

if(!isset($_SESSION["user_id"])){ // no user
    echo json_encode(["logged_in"=>false]);
    exit;
}

echo json_encode([ // user data
    "logged_in"=>true,
    "user_id"=>$_SESSION["user_id"],
    "name"=>$_SESSION["name"],
    "email"=>$_SESSION["email"],
    "role"=>$_SESSION["role"]
]);
