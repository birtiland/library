<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    require_once "connect.php";
    $book_id = $_POST["book_id"];
    $major = $_POST["major"];

    //检查是否可删除
    $connect = connect()->prepare("SELECT * FROM books_information WHERE major = ?");
    $connect->bindParam(1,$major);
    $connect->execute();
    //检查完成

    if ($connect->fetch(PDO::FETCH_ASSOC)) {
        echo "有书籍用此专业信息，删除失败";
    } else {
        $connect = connect()->prepare("DELETE FROM major WHERE major = ?");
        $connect->bindParam(1,$major);
        $connect->execute();
        echo "删除成功";
    }
}