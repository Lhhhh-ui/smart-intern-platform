<?php
// 加载环境变量
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// 数据库连接配置（从环境变量读取）
$host = 'localhost';
$dbuser = $_ENV['DB_USER'] ?? 'root';
$dbpass = $_ENV['DB_PASS'] ?? 'root';
$dbname = $_ENV['DB_NAME'] ?? 'database';
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
