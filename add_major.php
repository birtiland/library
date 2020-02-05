<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    $major = $_POST["major"];
    $URL = $_POST["url"];
    require_once "connect.php";
    $connect = connect()->prepare("INSERT INTO major (major) VALUE (?)");
    $connect->bindParam(1,$major);
    $connect->execute();
    echo "添加成功，即将返回";
    header("refresh:3;url=$URL");
}