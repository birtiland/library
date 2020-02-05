<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    $book_id = $_POST["book_id"];
    $tag_id = $_POST["tag_id"];
    require_once "connect.php";
    $connect = connect()->prepare("DELETE FROM relations WHERE bookid = ? AND tagid = ?");
    $connect->bindParam(1, $book_id);
    $connect->bindParam(2, $tag_id);
    $connect->execute();
    header("location:edit_book.php?id=$book_id");
}