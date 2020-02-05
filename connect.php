<?php
function connect(){
    $dsn = 'mysql:dbname=labrary;host=101.200.122.177;port=3306';

    $username = 'root';

    $password = '19980822abc';

    try {

        $db = new PDO($dsn, $username, $password); // also allows an extra parameter of configuration
        $db->query("set character set 'utf8'");
        $db->query("set names 'utf8'");
    } catch(PDOException $e) {

        die('Could not connect to the database:<br/>' . $e);

    }
    return $db;
}