<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    require_once 'connect.php';
    $connect = connect();
    if (!$connect) {
        echo "无法连接到服务器"; ?>
        <a href="library.php">点击返回</a><?php
    } else {
        $number = $_COOKIE['number'];
        $update = $connect->prepare("UPDATE users set HaveBorrowCard = 1 where `number` = ?");
        $update->bindParam(1, $number);
        $a = $update->execute();
        $update = $connect->prepare("INSERT INTO Cards (`number`) VALUE (?)");
        $update->bindParam(1, $number);
        $b = $update->execute();
        if ($a && $b) {
            setcookie("HaveBorrowCard", 1, time() + 3600);
            echo "申请成功，即将返回";
            header("refresh:3;url=library.php");
        } else {
            echo "失败了？？！原因我也不知道...";
        }
    }
}