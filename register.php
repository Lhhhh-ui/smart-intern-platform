<?php
header('Content-Type: application/json');

require_once 'config.php';

$uname = $_POST["username"] ?? '';
$eml = $_POST["email"] ?? '';
$pwd = $_POST["password"] ?? '';
$pwd1 = $_POST["password1"] ?? '';

// 用户名校验
if(($uname[0]>='a' && $uname[0]<='z')||($uname[0]>='A' && $uname[0]<='Z')){
    // 用户名合法
}else{
    echo json_encode(['code' => 0, 'msg' => '用户名不合法，首字符必须为字母']);
    exit;
}

// 邮箱校验
if(strpos($eml,"@qq.com")!==false){
    // 邮箱合法
}else{
    echo json_encode(['code' => 0, 'msg' => '邮箱不合法，仅支持QQ邮箱']);
    exit;
}

// 密码校验
$plength = strlen($pwd);
if($plength>=6 && $plength<=8){
    // 密码合法
}else{
    echo json_encode(['code' => 0, 'msg' => '密码长度必须为6-8位']);
    exit;
}

// 确认密码校验
$plength1 = strlen($pwd1);
if($plength1>=6 && $plength1<=8){
    // 确认密码合法
}else{
    echo json_encode(['code' => 0, 'msg' => '确认密码长度必须为6-8位']);
    exit;
}

if($pwd!==$pwd1){
    echo json_encode(['code' => 0, 'msg' => '两次密码不一致']);
    exit;
}

// 数据库插入
$sql = "INSERT INTO user(usrname, pwd, eml) VALUES('$uname', '$pwd', '$eml')";

if($conn->query($sql) === TRUE){
    echo json_encode(['code' => 1, 'msg' => '注册成功']);
}else{
    echo json_encode(['code' => 0, 'msg' => '注册失败：' . $conn->error]);
}

$conn->close();
?>
