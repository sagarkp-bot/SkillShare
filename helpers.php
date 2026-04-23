<?php
if(session_status()===PHP_SESSION_NONE) session_start(); // session
require_once "db.php";

function post($key,$default=""){return trim($_POST[$key] ?? $default);} // post text
function getv($key,$default=""){return trim($_GET[$key] ?? $default);} // get text

function json_out($data){ // send json
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
}

function back_alert($text){ // go back
    echo "<script>alert(".json_encode($text).");history.back();</script>";
    exit;
}

function go_alert($url,$text=""){ // go page
    $msg=$text ? "alert(".json_encode($text).");" : "";
    echo "<script>$msg window.location=".json_encode($url).";</script>";
    exit;
}

function rows($result){return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];} // all rows

function one_row($stmt){ // one row
    $result=$stmt->get_result();
    return $result ? $result->fetch_assoc() : null;
}

function need_login($json=false){ // login check
    if(isset($_SESSION["user_id"])) return;
    $json ? json_out(["error"=>"not_logged_in"]) : go_alert("auth.html","Please login first.");
}

function need_admin(){ // admin check
    if(($_SESSION["role"] ?? "")==="admin") return;
    json_out(["error"=>"Access denied"]);
}

function get_content_type($url){ // content type
    $url=trim($url);
    if($url==="") return "text";
    return preg_match("/\.(jpg|jpeg|png|gif|webp)(\?.*)?$/i",$url) ? "image" : "video";
}

function log_activity($conn,$user_id,$action,$details=""){ // save log
    $stmt=$conn->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
    if(!$stmt) return;
    $stmt->bind_param("iss",$user_id,$action,$details);
    $stmt->execute();
}
