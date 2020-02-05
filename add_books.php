<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    require_once 'connect.php';
    $major = $_POST['major'];
    $book_name = $_POST['book_name'];
    $book_quantity = $_POST['book_quantity'];
    $book_quantity = ($book_quantity) ? $book_quantity : 0;
    if (!$book_name) {
        echo "书籍名称不能为空";
    } elseif (!$major) {
        echo "专业不能为空";
    } else {
        $connect = connect();
        if (!$connect) {
            echo "哪出毛病了";
        } else {
            $check = $connect->prepare("INSERT INTO books_information(`name`, quantity, major) VALUE (?,?,?)");
            $check->bindParam(1, $book_name);
            $check->bindParam(2, $book_quantity);
            $check->bindParam(3, $major);
            $a = $check->execute();
            if ($a) {
                echo "添加成功,即将返回";
                header("refresh:3;url=library.php");
            } else {
                echo "哪出毛病了";
            }
        }
    }
}