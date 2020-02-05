<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    require_once "connect.php";
    $book_id = $_POST["book_id"];
    $new_name = $_POST["new_name"];
    $connect = connect()->prepare("UPDATE books_information SET `name` = ? WHERE bookid = ?");
    $connect->bindParam(1,$new_name);
    $connect->bindParam(2,$book_id);
    $connect->execute();
    echo "修改成功，即将返回";
    header("refresh:3;url=edit_book.php?id=$book_id");
}