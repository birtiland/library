<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    require_once "connect.php";
    $number = $_COOKIE["number"];

    //查找卡号
    $connect = connect()->prepare("SELECT card_id FROM Cards WHERE `number` = ?");
    $connect->bindParam(1,$number);
    $connect->execute();
    $result = $connect->fetch(PDO::FETCH_ASSOC);
    //查找完成
    $card_id = $result["card_id"];

    ?>
    <h2>我的借阅</h2>
    <div><span>借阅卡号：<?php echo $card_id ?></span><span style="float: right"><a href="library.php">返回</a> </span> </div>
    <div><hr/></div>
    <?php

    //查找借阅信息
    $connect = connect()->prepare("SELECT book_id,book_name,borrow_time,return_time FROM Borrows WHERE borrowerID = ? AND is_returned = 0 ORDER BY borrow_time");
    $connect->bindParam(1,$number);
    $connect->execute();
    //查找完成

    while ($result = $connect->fetch(PDO::FETCH_ASSOC)) {
        echo "书籍编号：" . $result["book_id"];echo "    ";
        echo "书籍名称：" . $result["book_name"] . "    ";
        date_default_timezone_set("Asia/Shanghai");
        echo "借阅时间：" . date("Y-m-d H:i:s",$result["borrow_time"]) . "    ";
        echo "最晚归还时间：" . date("Y-m-d H:i:s",$result["return_time"]) . "<hr/>";
    }
}