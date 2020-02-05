<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
}
session_start();
$book_id = $_GET["id"];
require_once "connect.php";
$connect = connect();
$result = $connect->prepare("SELECT * FROM books_information WHERE bookid = ?");
$result->bindParam(1,$book_id);
$result->execute();
$book = $result->fetch(PDO::FETCH_ASSOC);
?>
<h1>编辑图书信息：<?php echo $book["name"] ?></h1>
<h4><a href="library.php">返回</a> </h4>
<div>
    <div>
        <span>修改图书名称：</span>
        <form action="change_book_name.php" method="post" style="display: inline">
            <input type="text" name="new_name">
            <input type="hidden" name="book_id" value="<?php echo $book_id ?>">
            <input type="submit" value="确认修改">
        </form>
        <br>
        <span>图书数量：</span>
        <form action="quantity_cut.php" method="post" style="display: inline">
            <input type="hidden" name="book_id" value="<?php echo $book_id ?>">
            <input type="submit" value="－">
        </form>
        <?php echo $book["quantity"] ?>
        <form action="quantity_add.php" method="post" style="display: inline">
            <input type="hidden" name="book_id" value="<?php echo $book_id ?>">
            <input type="submit" value="＋">
        </form>
    </div>
</div>
<div>
    <div>
        <span>已加图书标签(点击删除标签)：</span>
        <span><?php
            $connect = connect()->prepare("select t.tagid,t.tag from relations as r join tags as t on r.tagid=t.tagid and r.bookid=?");
            $connect->bindParam(1,$book_id);
            $connect->execute();
            while($d = $connect->fetch(PDO::FETCH_ASSOC)){?>
                <form action="tag_cut.php" method="post" style="display: inline">
                    <input type="hidden" name="book_id" id="book_id" value="<?php echo $book_id ?>" />
                    <input type="hidden" name="tag_id" id="tag_id" value="<?php echo $d["tagid"] ?>" />
                    <input type="submit" value="<?php echo $d["tag"] ?>">
                </form><?php
            } ?>
        </span>
        <br>
        <span>可选图书标签(点击添加标签)：</span>
        <span><?php
            $connect = connect()->prepare("SELECT r.bookid,t.* FROM tags t LEFT JOIN relations r ON (t.tagid=r.tagid AND r.bookid=?) ORDER BY bookid");
            $connect->bindParam(1,$book_id);
            $connect->execute();
            $result = $connect->fetchALL(PDO::FETCH_ASSOC);
            foreach ($result as $d) {
                if ($d["bookid"]) {break;}?>
                <form action="tag_add.php" method="post" style="display: inline">
                    <input type="hidden" name="book_id" id="book_id" value="<?php echo $book_id ?>" />
                    <input type="hidden" name="tag_id" id="tag_id" value="<?php echo $d["tagid"] ?>" />
                    <input type="submit" value="<?php echo $d["tag"] ?>">
                </form><?php
            } ?>
        </span>
    </div>
    <form method="post" action="add_tag.php?id=<?php echo $book_id ?>" style="display: inline">
        <span>添加新标签类型：</span>
        <span>
            <input type="text" id="tag" name="tag">
            <button type="submit">添加</button>
        </span>
    </form>
    <form method="post" action="change_book_major.php" style="display: list-item">
        <span>修改专业：
            <select name="major">
                <?php
                $connect = connect()->prepare("SELECT major FROM `major` order by case when major = \"其他\" then 1 else 0 end asc");
                $connect->execute();
                $a = $connect->fetchALL(PDO::FETCH_ASSOC);
                foreach ($a as $result) {
                    if ($result["major"] == $book["major"]) { ?>
                        <option value="<?php echo $result["major"] ?>" selected><?php echo $result["major"] ?></option> <?php
                    } else { ?>
                        <option value="<?php echo $result["major"] ?>"><?php echo $result["major"] ?></option> <?php
                    }
                } ?>
            </select>
        </span>
        <input type="hidden" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>" name="url">
        <input type="hidden" value="<?php echo $book_id ?>" name="book_id">
        <input type="submit" value="确定">
    </form>
</div>
<div><br></div>
<div>
    <h3>添加新种类专业</h3>
    <form action="add_major.php" method="post" style="display: list-item">
        <span>
            <input type="hidden" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>" name="url">
            <input type="text" name="major">
            <input type="submit" value="确定">
        </span>
    </form>
</div>
<div><br></div>
<div>
    <h3>删除无用专业/标签信息</h3>
    <form method="post" action="delete_major.php" style="display: list-item">
        <select name="major"> <?php
            foreach ($a as $item) { ?>
                <option value="<?php echo $item["major"] ?>"><?php echo $item["major"] ?></option> <?php
            } ?>
        </select>
        <input type="hidden" name="book_id" value="<?php echo $book_id ?>">
        <input type="submit" value="删除">
    </form>
    <form method="post" action="delete_tag.php" style="display: list-item">
        <select name="tag_id"> <?php
            $connect = connect()->prepare("SELECT * FROM tags");
            $connect->execute();
            $a = $connect->fetchALL(PDO::FETCH_ASSOC);
            foreach ($a as $item) { ?>
                <option value="<?php echo $item["tagid"] ?>"><?php echo $item["tag"] ?></option> <?php
            } ?>
        </select>
        <input type="hidden" name="book_id" value="<?php echo $book_id ?>">
        <input type="submit" value="删除">
    </form>
</div>