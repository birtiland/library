<?php
if ( (!isset($_COOKIE["number"])) || $_COOKIE["isAdministrator"]!=1 ) {
    header("location:in.php");
} else {
    require_once "connect.php";
    $number = $_POST["number"];
    $connect = connect()->prepare("UPDATE users SET isAdministrator = 1 WHERE `number` = ?");
    $connect->bindParam(1,$number);
    $connect->execute();
    echo "任命成功，即将返回";
    header("refresh:3;url=users.php");
}