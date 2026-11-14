<?php
session_start();

// 清除所有 session 数据
$_SESSION = array();

// 清除 session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// 清除用户名 cookie
setcookie("username", '', time() - 3600, '/');

// 销毁 session
session_destroy();

// 返回 JSON 响应
header('Content-Type: application/json');
echo json_encode(['code' => 1, 'msg' => '退出成功']);
?>
