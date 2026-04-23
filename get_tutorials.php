<?php
require_once "helpers.php";

$category=getv("category","All"); // filters
$search=getv("search");
$sort=getv("sort","newest");
$type=getv("content_type");
$words=preg_split('/\s+/',strtolower($search));

if(in_array("popular",$words,true)){
    $sort="popular";
    $search=trim(str_ireplace("popular","",$search));
}

foreach(["video","image","text"] as $word){
    if(in_array($word,$words,true)){
        $type=$word;
        $search=trim(str_ireplace($word,"",$search));
    }
}

$sql="SELECT tutorials.*, users.name AS author, COUNT(DISTINCT likes.id) AS like_count, COUNT(DISTINCT comments.id) AS comment_count FROM tutorials JOIN users ON tutorials.user_id = users.id LEFT JOIN likes ON tutorials.id = likes.tutorial_id LEFT JOIN comments ON tutorials.id = comments.tutorial_id WHERE 1";
$types=""; // bind types
$values=[];

if($category!=="All"){
    $sql.=" AND tutorials.category = ?";
    $types.="s";
    $values[]=$category;
}

if(in_array($type,["text","image","video"],true)){
    $sql.=" AND tutorials.content_type = ?";
    $types.="s";
    $values[]=$type;
}

if($search!==""){
    $like="%".$search."%";
    $sql.=" AND (tutorials.title LIKE ? OR tutorials.description LIKE ? OR users.name LIKE ?)";
    $types.="sss";
    $values[]=$like;
    $values[]=$like;
    $values[]=$like;
}

$sql.=" GROUP BY tutorials.id ORDER BY ".($sort==="popular" ? "like_count DESC, tutorials.views DESC, tutorials.created_at DESC" : "tutorials.created_at DESC");
$stmt=$conn->prepare($sql);
if($values) $stmt->bind_param($types,...$values);
$stmt->execute();

json_out(rows($stmt->get_result())); // send
