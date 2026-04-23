<?php
require_once "helpers.php";

$name=post("name"); // form data
$email=post("email");
$password=$_POST["password"] ?? "";

if(!$name || !$email || !$password) back_alert("Please fill all fields."); // check
if(!filter_var($email,FILTER_VALIDATE_EMAIL)) back_alert("Please enter a valid email address.");

$check=$conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s",$email);
$check->execute();
if(one_row($check)) back_alert("This email is already registered. Please login.");

$hash=password_hash($password,PASSWORD_DEFAULT); // hash
$stmt=$conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss",$name,$email,$hash);

if(!$stmt->execute()) back_alert("Error: Could not create account."); // save
log_activity($conn,$stmt->insert_id,"register",$email); // log
go_alert("auth.html","Account created! Please login.");
