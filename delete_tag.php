<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    $tag_id = $_POST["tag_id"];
    $book_id = $_POST["book_id"];
    require_once "connect.php";

    //查看是否允许删除
    $connect = connect()->prepare("SELECT * FROM relations WHERE tagid = ?");
    $connect->bindParam(1,$tag_id);
    $connect->execute();

    if ($connect->fetch(PDO::FETCH_ASSOC)) {
        echo "有书籍正在使用标签信息，删除失败";
    } else {
        $connect = connect()->prepare("DELETE FROM tags WHERE tagid = ?");
        $connect->bindParam(1,$tag_id);
        $connect->execute();
        echo "删除成功";
    }
}