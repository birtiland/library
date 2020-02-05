<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    require_once "connect.php";
    $book_id = $_GET["id"];
    $tag = $_POST["tag"];
    $connect = connect()->prepare("INSERT INTO tags (tag) VALUE (?)");
    $connect->bindParam(1, $tag);
    $connect->execute();
    header("location:edit_book.php?id=$book_id");
}