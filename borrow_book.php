<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
}
$HaveBorrowCard = $_COOKIE["HaveBorrowCard"];
//检查是否有借阅卡
if (!$HaveBorrowCard) {
    echo "请先申请借阅卡，即将返回主页";
    header("refresh:3;url=library.php");
} else {
    require_once "connect.php";
    $book_id =  $_POST["book_id"];
    $day =  $_POST["day"];
    $number = $_COOKIE["number"];
    $book_name = $_POST["book_name"];
    $book_major = $_POST["book_major"];

    //获取卡号
    $connect = connect()->prepare("SELECT card_id FROM Cards WHERE `number` = ?");
    $connect->bindParam(1,$number);
    $connect->execute();
    $result = $connect->fetch(PDO::FETCH_ASSOC);
    $card_id = $result["card_id"];
    setcookie("card_id",$card_id,time()+3600);
    //卡号获取完成

    //检查是否有逾期未还书籍
    $connect = connect()->prepare("SELECT return_time FROM Borrows WHERE is_returned = 0");
    $connect->execute();
    $check = 0;
    while ($result = $connect->fetch(PDO::FETCH_ASSOC)) {
        if ($result["return_time"]<time()) {
            $check = 1;break;
        }
    }
    //检查完成

    if ($check) {
        echo "您有逾期未还书籍，此次借阅取消，请先归还该书籍，页面即将跳转";
        header("refresh:3;url=library.php");
    } else {

        //检查是否重复借阅
        $connect = connect()->prepare("SELECT * FROM Borrows WHERE is_returned = 0 AND book_id = ? AND book_name = ? AND card_id = ? AND borrowerID = ?");
        $connect->bindParam(1,$book_id);
        $connect->bindParam(2,$book_name);
        $connect->bindParam(3,$card_id);
        $connect->bindParam(4,$number);
        $connect->execute();
        //检查完成

        if ($connect->fetch(PDO::FETCH_ASSOC)) {
            echo "您已借阅过该图书未还，即将返回";
            header("refresh:3;url=library.php");
        } else {

            //检查书籍是否有剩余
            $connect = connect()->prepare("SELECT quantity FROM books_information WHERE bookid = ?");
            $connect->bindParam(1,$book_id);
            $connect->execute();
            $result = $connect->fetch(PDO::FETCH_ASSOC);
            //检查完成

            if (!$result["quantity"]) {
                echo "该图书已无剩余，即将返回";
                header("refresh:3;url=library.php");
            } else {

                //判断是否禁止借阅
                $connect = connect()->prepare("SELECT return_time, returned FROM Borrows WHERE is_returned = 1 AND borrowerID = ?");
                $connect->bindParam(1,$number);
                $connect->execute();
                date_default_timezone_set("Asia/Shanghai");
                $time = time();
                while ($result = $connect->fetch(PDO::FETCH_ASSOC)) {
                    if ( ($result["returned"] > $result["return_time"]) && (($time-$result["returned"]) <= 604800) ) {
                        $check = 1;break;
                    }
                }
                //判断完成

                if ($check) {
                    echo "您正处于禁止借阅状态，下次请不要逾期还书了哦，马上跳转页面";
                    header("refresh:3;url=library.php");
                } else {

                    //判断是否超数借书
                    $connect = connect()->prepare("select count(id) from Borrows where book_major = ? or book_major = \"学科基础\" union all select count(id) from Borrows where book_major <> ? and book_major <> \"学科基础\"");
                    $connect->bindParam(1,$_COOKIE["major"]);
                    $connect->bindParam(2,$_COOKIE["major"]);
                    $connect->execute();
                    $result = $connect->fetchALL(PDO::FETCH_NUM);
                    //判断完成

                    if ( ($book_major == "学科基础" || $book_major == $_COOKIE["major"]) && (!($result[0][0] < 20)) ) {
                        echo "本专业书籍最多借阅20本！即将返回";
                        header("refresh:3;url=library");
                    } elseif ( !($book_major == "学科基础" || $book_major == $_COOKIE["major"]) && (!($result[0][0] < 10)) ) {
                        echo "非本专业书籍最多借阅10本！即将返回";
                        header("refresh:3;url=library");
                    } else {

                        //写入借阅信息
                        $borrow_time = time();
                        $return_time = time()+($day*24*60*60);
                        $connect = connect()->prepare("INSERT INTO Borrows (book_id, book_name, card_id, borrowerID, borrow_time, return_time, book_major) VALUE (?,?,?,?,?,?,?)");
                        $connect->bindParam(1,$book_id);
                        $connect->bindParam(2,$book_name);
                        $connect->bindParam(3,$card_id);
                        $connect->bindParam(4,$number);
                        $connect->bindParam(5,$borrow_time);
                        $connect->bindParam(6,$return_time);
                        $connect->bindParam(7,$book_major);
                        $a = $connect->execute();
                        //信息写入完成

                        //图书数量减一
                        $connect = connect()->prepare("UPDATE books_information SET quantity=quantity-1 WHERE bookid=?");
                        $connect->bindParam(1,$book_id);
                        $b = $connect->execute();
                        //数量更新完成

                        if ($a && $b) {
                            echo "借阅成功，即将返回";
                            header("refresh:3;url=library.php");
                        } else {
                            echo "莫名原因失败，即将返回";
                        }
                    }
                }
            }
        }
    }
}