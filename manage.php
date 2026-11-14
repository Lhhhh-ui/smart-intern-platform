<?php
session_start();
header('Content-Type: application/json');

// 登录校验
if(!isset($_SESSION['username']) && !isset($_COOKIE['username'])){
    echo json_encode(['code' => 0, 'msg' => '请先登录']);
    exit;
}

require_once 'config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // 查询列表
    case 'list':
        $sql = "SELECT * FROM intern ORDER BY intid DESC";
        $result = $conn->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    'intid' => $row['intid'],
                    'iname' => $row['iname'],
                    'category' => $row['category'],
                    'salary' => $row['salary'],
                    'edu' => $row['edu'],
                    'waddr' => $row['waddr'],
                    'request' => $row['request']
                ];
            }
        }
        echo json_encode(['code' => 1, 'msg' => 'success', 'data' => $data]);
        break;

    // 查询单条（编辑回填）
    case 'get':
        $id = intval($_POST['intid'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['code' => 0, 'msg' => '参数错误']);
            break;
        }
        $sql = "SELECT * FROM intern WHERE intid = $id";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode([
                'code' => 1,
                'msg' => 'success',
                'data' => [
                    'intid' => $row['intid'],
                    'iname' => $row['iname'],
                    'category' => $row['category'],
                    'salary' => $row['salary'],
                    'edu' => $row['edu'],
                    'waddr' => $row['waddr'],
                    'request' => $row['request']
                ]
            ]);
        } else {
            echo json_encode(['code' => 0, 'msg' => '数据不存在']);
        }
        break;

    // 添加
    case 'add':
        $name = trim($_POST['postname'] ?? '');
        $category = trim($_POST['category'] ?? 'it');
        $salary = trim($_POST['salary'] ?? '');
        $edu = trim($_POST['education'] ?? '');
        $place = trim($_POST['workplace'] ?? '');
        $req = trim($_POST['request'] ?? '');

        if ($name == '' || $salary == '' || $edu == '' || $place == '' || $req == '') {
            echo json_encode(['code' => 0, 'msg' => '请填写完整信息']);
            break;
        }

        $sql = "INSERT INTO intern (iname, category, salary, edu, waddr, request) 
                VALUES ('$name','$category','$salary','$edu','$place','$req')";

        if ($conn->query($sql)) {
            echo json_encode(['code' => 1, 'msg' => '添加成功']);
        } else {
            echo json_encode(['code' => 0, 'msg' => '添加失败：' . $conn->error]);
        }
        break;

    // 修改
    case 'update':
        $id = intval($_POST['intid'] ?? 0);
        $name = trim($_POST['postname'] ?? '');
        $category = trim($_POST['category'] ?? 'it');
        $salary = trim($_POST['salary'] ?? '');
        $edu = trim($_POST['education'] ?? '');
        $place = trim($_POST['workplace'] ?? '');
        $req = trim($_POST['request'] ?? '');

        if ($id <= 0) {
            echo json_encode(['code' => 0, 'msg' => '参数错误']);
            break;
        }
        if ($name == '' || $salary == '' || $edu == '' || $place == '' || $req == '') {
            echo json_encode(['code' => 0, 'msg' => '请填写完整信息']);
            break;
        }

        $sql = "UPDATE intern SET 
                iname='$name', category='$category', salary='$salary', 
                edu='$edu', waddr='$place', request='$req' 
                WHERE intid=$id";

        if ($conn->query($sql)) {
            echo json_encode(['code' => 1, 'msg' => '修改成功']);
        } else {
            echo json_encode(['code' => 0, 'msg' => '修改失败：' . $conn->error]);
        }
        break;

    // 删除
    case 'delete':
        $id = intval($_POST['intid'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['code' => 0, 'msg' => '参数错误']);
            break;
        }
        $sql = "DELETE FROM intern WHERE intid=$id";
        if ($conn->query($sql)) {
            echo json_encode(['code' => 1, 'msg' => '删除成功']);
        } else {
            echo json_encode(['code' => 0, 'msg' => '删除失败：' . $conn->error]);
        }
        break;

    default:
        echo json_encode(['code' => 0, 'msg' => '未知操作']);
}

$conn->close();
?>
