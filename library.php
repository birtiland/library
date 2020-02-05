<?php
require_once 'connect.php';
    if(!isset($_COOKIE["number"])){
        header("location:in.php");
    }
    session_start();
    if (isset($_SESSION["array"])) {
        $a = $_SESSION['array'];
        unset($_SESSION["array"]);
    } else {
        setcookie("search_name","",time()-3600);
        $connect = connect();
        $result = $connect->prepare("SELECT * FROM books_information");
        $result->execute();
        $a = [];
        while($b = $result->fetch(PDO::FETCH_ASSOC)){
            $a[] = $b;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<meta charset="UTF-8">
<h3 align="center">某大学图书管理系统</h3>
<body>
<div>
    <div style="left">
        <span>尊敬的<?php echo $_COOKIE["username"] ."（";
                    echo ($_COOKIE["isAdministrator"]==1)?"管理员）":"普通用户）";
                   ?>
            您好
        </span>
        <span><?php
                if(!$_COOKIE["HaveBorrowCard"]){
                    ?><a href="BorrowCard.php">点击申请借阅卡</a><?php
                }
              ?>
        </span>
    </div>
    <div style="float: right">
        <span><a href="mine.php">我的账户</a> </span>
        <?php
        if ($_COOKIE["isAdministrator"]==1) echo "<span><a href='users.php'>管理</a> </span>";
        ?>
        <span><a href="logout.php">退出登录</a> </span>
    </div>
</div>
    <div>
        <form method="post" action="search_book.php">
            <span>输入书名以查询图书
            <input type="text" id="search_name" name="search_name">
            </span>
            <span><button type="submit">查询</button></span>
            <?php
            if($_COOKIE["isAdministrator"]==1){?>
                <a href="books.php">点击添加新图书</a><?php
            }
            ?>
        </form>
    </div>
    <hr/>
    <div>
        <?php
        if (isset($_COOKIE["page"])) {
            $page = $_COOKIE["page"];
            setcookie("page","",time()-3600);
        } else {
            $page = 1;
        }
        foreach ($a as $key => $b) {
            if ( ($key >= (($page-1) * 8)) && ($key < $page * 8) ) {
                echo "书籍编号：";echo $b["bookid"];echo "    ";echo "书籍名称：";echo $b["name"];echo "    ";echo "专业：";echo $b["major"];echo "    ";echo "剩余数量：";echo $b["quantity"];echo "    ";echo "标签：";
                $connect = connect()->prepare("select t.tag from relations as r join tags as t on r.tagid=t.tagid and r.bookid=?");
                $connect->bindParam(1,$b["bookid"]);
                $connect->execute();
                while ($d = $connect->fetch(PDO::FETCH_ASSOC)) {
                    echo $d["tag"];echo "    ";
                } ?>
                <form style="display: inline" method="post" action="borrow.php" name="<?php echo $b["bookid"] ?>">
                    <input type="hidden" name="book_id" value="<?php echo $b["bookid"] ?>">
                    <input type="hidden" name="book_name" value="<?php echo $b["name"] ?>">
                    <input type="hidden" name="book_major" value="<?php echo $b["major"] ?>">
                    <input type="submit" value="借阅图书">
                </form>
                <?php
                if ($_COOKIE["isAdministrator"]==1) {?>
                <a href="edit_book.php?id=<?php echo $b["bookid"] ?>">编辑图书信息</a><?php
                }
                echo "<hr/>";
                if ( (($key + 1) % 8) == 0 ) break;
            }
        }?>
        <div style="text-align: center">
            <a href="change_page.php?page=1">首页</a> <?php
            for ($i = 1;$i <= (ceil(count($a)/8)); $i++) {
                if ($i == $page) {echo $i;continue;}
                if ( (abs($page-$i)) == 1 ) { ?>
                    <a href='change_page.php?page=<?php echo $i ?>'><?php echo $i ?></a><?php
                } elseif (($page-$i) == 2) {
                    echo "...";
                } elseif (($i-$page) == 2) {
                    echo "...";break;
                }
            } ?>
            <a href="change_page.php?page=<?php echo (ceil(count($a)/8)) ?>">尾页</a>
        </div>
    </div>
</body>