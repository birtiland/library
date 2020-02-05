<?php
if(isset($_COOKIE["number"])){
    header("location:library.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<meta charset="UTF-8">
<h2>欢迎使用图书管理系统</h2>
<h3>请先登录</h3>
<form method="post" action="login.php">
    <div class="form-group">
        <label for="number">学工号</label>
        <input type="text" class="form-control" id="number" placeholder="学工号" name="number">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">密码</label>
        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="密码" name="password">
    </div>
    <button type="submit" class="btn btn-default">登录</button>
    <a href="register.html">注册</a>
</form>