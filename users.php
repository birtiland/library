<?php
if ( (!isset($_COOKIE["number"])) || $_COOKIE["isAdministrator"]!=1 ) {
    header("location:in.php");
} else {

    //管理员管理归还书籍
    ?>
    <div><h2>管理归还图书</h2><span style="float: right"><a href="library.php">返回</a> </span></div>
    <div>
        <span>精准查找以归还图书</span><br>
        <form action="return_book.php" method="post">
            书籍编号：
            <input type="text" name="book_id">
            借阅卡号：
            <input type="text" name="card_id">
            <input type="submit" value="确定归还">
        </form>
    </div>

    <div><br></div>

    <!-- 管理员任命新管理员 -->
    <div><h2>任命新管理员</h2></div>
    <div>
        <span>精准输入学号以任命新管理员</span>
        <form method="post" action="appoint.php">
            <input type="text" name="number">
            <input type="submit" value="任命">
        </form>
    </div>
    <?php
}