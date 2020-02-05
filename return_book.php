<?php
if ( (!isset($_COOKIE["number"])) || $_COOKIE["isAdministrator"]!=1 ) {
    header("location:in.php");
} else {
    $book_id = $_POST["book_id"];
    $card_id = $_POST["card_id"];
    require_once "connect.php";

    //判断借阅信息是否正确
    $connect = connect()->prepare("SELECT * FROM Borrows WHERE book_id = ? AND card_id = ? AND is_returned = 0");
    $connect->bindParam(1,$book_id);
    $connect->bindParam(2,$card_id);
    $connect->execute();
    //判断完成

    if ($connect->fetch(PDO::FETCH_ASSOC)) {

        //修改借阅信息
        date_default_timezone_set("Asia/Shanghai");
        $time = time();
        $connect = connect()->prepare("UPDATE Borrows SET is_returned = 1, returned = ? WHERE book_id = ? AND card_id = ?");
        $connect->bindParam(1,$time);
        $connect->bindParam(2,$book_id);
        $connect->bindParam(3,$card_id);
        $a = $connect->execute();
        //修改完成

        //修改图书数量
        $connect = connect()->prepare("UPDATE books_information SET quantity = quantity + 1 where bookid = ?");
        $connect->bindParam(1,$book_id);
        $b = $connect->execute();
        //修改完成

        if ($a && $b) {
            echo "归还成功，即将返回";
            header("refresh:3;url=users.php");
        } else {
            echo "出错啦！归还失败！即将返回";
            header("refresh:3;url=users.php");
        }
    } else {
        echo "借阅信息错误，请核对后再试，即将返回";
        header("refresh:5;url=users.php");
    }
}