<?php
require_once 'config.php';
header('Content-Type: application/json');

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

$conn->close();

echo json_encode(['code' => 1, 'msg' => 'success', 'data' => $data]);
?>
