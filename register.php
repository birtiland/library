<?php

    require_once 'connect.php';
    $connect = connect();
    if($connect){
        $username = $_POST['username'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $number = $_POST['number'];
        $ID_number = $_POST['ID_number'];

        //检查学号和身份证号对不对的上
        $check = $connect->prepare("select `number`,ID_number, major from student_information WHERE `number` = ?");
        $check->bindParam(1,$number);
        $check->execute();
        $a = $check->fetch(PDO::FETCH_ASSOC);
        if(!$a){
            echo "学号错误或不存在";
        }else{
            if($a[1]!=$ID_number){
                echo "身份证号不正确";
            }else{
                //检查是不是注册过了
                $b = $connect->prepare("select `number` from users WHERE `number` = ?");
                $b->bindParam(1,$number);
                $b->execute();
                $c = $b->fetch(PDO::FETCH_NUM);
                if($c){
                    echo "您已注册，请直接登录，即将为您自动跳转";
                    header("refresh:3;url=in.php");
                }else{
                    //执行注册
                    $major = $a["major"];
                    $statement = $connect->prepare("insert into users(username,password,email,phone,`number`,ID_number,major) value(?,?,?,?,?,?,?)");
                    $statement->bindParam(1,$username);
                    $statement->bindParam(2,$password);
                    $statement->bindParam(3,$email);
                    $statement->bindParam(4,$phone);
                    $statement->bindParam(5,$number);
                    $statement->bindParam(6,$ID_number);
                    $statement->bindParam(7,$major);
                    $return = $statement->execute();
                    if($return){
                        echo "注册成功，即将将跳转至登录页面";
                        header("refresh:3;url=in.php");
                    }else echo "失败了，为毛我也不知道，你再试试";
                }
            }
        }
    }else{
        echo "服务器连接失败了，向本人反馈吧";
    }
