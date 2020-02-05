<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    $book_id = $_POST["book_id"];
    require_once "connect.php";
    $connect = connect()->prepare("UPDATE books_information SET quantity=quantity+1 WHERE bookid=?");
    $connect->bindParam(1, $book_id);
    $connect->execute();
    header("location:edit_book.php?id=$book_id");
}