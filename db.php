<?php
$conn=new mysqli("localhost","root","","skillshare"); // db link
if($conn->connect_error) die("Connection failed: ".$conn->connect_error); // stop
$conn->set_charset("utf8mb4"); // utf8
