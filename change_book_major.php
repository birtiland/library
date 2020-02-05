<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    $book_id = $_POST["book_id"];
    $major = $_POST["major"];
    $url = $_POST["url"];
    require_once "connect.php";
    $connect = connect()->prepare("UPDATE books_information SET major = ? WHERE bookid = ?");
    $connect->bindParam(1,$major);
    $connect->bindParam(2,$book_id);
    $a = $connect->execute();
    echo "修改成功，即将返回";
    header("refresh:3;url=$url");
}