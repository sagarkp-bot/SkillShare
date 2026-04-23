<?php
session_start(); // session
session_destroy(); // clear
header("Location: index.html"); // home
exit;
