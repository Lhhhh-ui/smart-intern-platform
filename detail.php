<?php
session_start();
header('Content-Type: application/json');

// 游客拦截
if(!isset($_SESSION['username']) && !isset($_COOKIE['username'])){
    echo json_encode(['code' => 0, 'msg' => '游客无法查看岗位详情，请先登录']);
    exit;
}

require_once 'config.php';
//接受前端请求
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['code' => 0, 'msg' => '参数错误']);
    exit;
}

$sql = "SELECT * FROM intern WHERE intid = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo json_encode(['code' => 0, 'msg' => '岗位不存在']);
    exit;
}

$job = $result->fetch_assoc();
$conn->close();

echo json_encode([
    'code' => 1,
    'msg' => 'success',
    'data' => [
        'iname' => $job['iname'],
        'salary' => $job['salary'],
        'edu' => $job['edu'],
        'waddr' => $job['waddr'],
        'request' => $job['request']
    ]
]);
?>
