<?php
session_start();
header('Content-Type: application/json');

require_once 'config.php';

$uname = trim($_POST['username'] ?? '');
$pwd = trim($_POST['pwd'] ?? '');

if (empty($uname) || empty($pwd)) {
    echo json_encode(['code' => 0, 'msg' => '用户名或密码不能为空']);
    exit;
}

// 用户名首字符校验
if(!(($uname[0] >= 'a' && $uname[0] <= 'z') || ($uname[0] >= 'A' && $uname[0] <= 'Z'))){
    echo json_encode(['code' => 0, 'msg' => '用户名不合法，首字符必须为字母']);
    exit;
}

// 密码长度校验
$plength = strlen($pwd);
if($plength < 6 || $plength > 8){
    echo json_encode(['code' => 0, 'msg' => '密码长度必须为6-8位']);
    exit;
}

// 数据库校验
$sql = "SELECT * FROM user WHERE usrname='$uname' AND pwd='$pwd'";
$result = $conn->query($sql);

if($result->num_rows == 0){
    echo json_encode(['code' => 0, 'msg' => '用户名或密码错误']);
    exit;
}

$_SESSION['username'] = $uname;
// 设置 cookie
setcookie("username", $uname, time()+3600*24, '/');

$conn->close();

echo json_encode(['code' => 1, 'msg' => '登录成功']);
?>
