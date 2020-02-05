<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
}
$book_id = $_POST["book_id"];
$book_name = $_POST["book_name"];
$book_major = $_POST["book_major"];
?>
<h2>借阅图书：<?php echo $book_name ?></h2>
<form action="borrow_book.php" method="post">
    <span>借阅时间：</span>
    <select name="day"> <?php
        for ($i = 1;$i<=31;$i++) { ?>
        <option value="<?php echo $i ?>"><?php echo $i ?></option> <?php
        } ?>
    </select>
    <span>天</span>
    <input type="hidden" name="book_id" value="<?php echo $book_id ?>">
    <input type="hidden" name="book_name" value="<?php echo $book_name ?>">
    <input type="hidden" name="book_major" value="<?php echo $book_major ?>">
    <input type="submit"  value="确定借阅">
    <a href="library.php">取消</a>
</form>
