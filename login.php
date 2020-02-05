<?php
foreach ($_COOKIE as $key=>$value) {
    setcookie($key,"",time()-3600);
}
    require_once 'connect.php';

    $connect = connect();
    $number = $_POST['number'];
    $password = md5($_POST['password']);
    $statement = $connect->prepare("SELECT * from users where `number` = ?");
    $statement->bindParam(1,$number);
    $statement->execute();
    $msg = $statement->fetch(PDO::FETCH_ASSOC);

    if(!$msg){   //检查是否有注册
        echo "用户不存在";?>
        <a href="in.php">点击返回</a><?php
    }else{   //检查密码
        if($msg["password"]!=$password){
            echo "密码不正确";?>
            <a href="in.php">点击返回</a><?php
        }else{  //执行登录
            setcookie("number",$number,time()+3600);
            setcookie("username",$msg["username"],time()+3600);
            setcookie("email",$msg["email"],time()+3600);
            setcookie("phone",$msg["phone"],time()+3600);
            setcookie("ID_number",$msg["ID_number"],time()+3600);
            setcookie("isAdministrator",$msg["isAdministrator"],time()+3600);
            setcookie("HaveBorrowCard",$msg["HaveBorrowCard"],time()+3600);
            setcookie("major",$msg["major"],time()+3600);
            header("location:library.php");
        }
    }