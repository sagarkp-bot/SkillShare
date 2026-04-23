<?php
require_once "helpers.php";

$email=post("email"); // form data
$password=$_POST["password"] ?? "";

if(!$email || !$password) back_alert("Please enter email and password."); // check

$stmt=$conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s",$email);
$stmt->execute();
$user=one_row($stmt); // user row

if(!$user) back_alert("No account found with that email.");
if(!password_verify($password,$user["password"])) back_alert("Wrong password. Try again.");

session_regenerate_id(true); // new id
$_SESSION["user_id"]=$user["id"];
$_SESSION["name"]=$user["name"];
$_SESSION["email"]=$user["email"];
$_SESSION["role"]=$user["role"];

log_activity($conn,$user["id"],"login",$user["email"]); // log
header("Location: ".($user["role"]==="admin" ? "admin.html" : "explore.html")); // redirect
exit;
