<?php
if(!isset($_COOKIE["number"])){
    header("location:in.php");
} else {
    require_once 'connect.php';
    $connect = connect();
    if (isset($_POST["search_name"])) {
        $a = '%' . $_POST['search_name'] . '%';
        setcookie("search_name",$a,time()+3600);
    } else {
        if (isset($_COOKIE["search_name"])) {
            $a = $_COOKIE["search_name"];
        } else {
            $a = "%%";
        }
    }
    $result = $connect->prepare("SELECT * FROM books_information WHERE `name` LIKE ?");
    $result->bindParam(1, $a);
    $result->execute();
    $array = [];
    while ($a = $result->fetch(PDO::FETCH_ASSOC)) {
        $array[] = $a;
    }
    session_start();
    $_SESSION["array"] = $array;
    header("location:library.php");
}