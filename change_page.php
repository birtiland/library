<?php
$aim_page = $_GET["page"];
setcookie("page",$aim_page,time()+3600);
echo $aim_page;
header("location:search_book.php");