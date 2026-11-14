<?php
// 数据库连接配置
$host = 'localhost';
$dbuser = 'root';
$dbpass = 'root';
$dbname = 'database';
$port = '3306';

// 建立连接
$conn = new mysqli($host, $dbuser, $dbpass, $dbname, $port);

// 检测连接
if ($conn->connect_error) {
    die("数据库连接失败：" . $conn->connect_error);
}

// 设置字符集
$conn->set_charset("utf8");
?>
