<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<meta charset="UTF-8">
<div>
    <h4>添加新的种类的书籍</h4>请确保您添加的书籍为新种类，修改已有书籍数量见下方
    <div>
        <form method="post" action="add_books.php">
        <span>书籍名称
        <input type="text" id="book_name" name="book_name">
        </span>
            <span>专业
        <select name="major"><?php
            require_once "connect.php";
            $connect = connect()->prepare("SELECT major FROM `major` order by case when major = \"其他\" then 1 else 0 end asc");
            $connect->execute();
            while ($result = $connect->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?php echo $result["major"] ?>"><?php echo $result["major"] ?></option> <?php
            } ?>
        </select>
        </span>
            <span>数量
        <input type="text" id="book_quantity" name="book_quantity">
        </span>
            <span><button type="submit">添加书籍</button></span>
        </form>
    </div>
    <div><br></div>
    <div>
        <h4>添加新种类专业</h4>
        <form action="add_major.php" method="post">
        <span>
            <input type="hidden" value="<?php echo "books.php" ?>" name="url">
            <input type="text" name="major">
            <input type="submit" value="确定">
        </span>
        </form>
    </div>
</div>
</html>