<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

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

$msg = $_POST['msg'] ?? '';
if (!$msg) {
    echo json_encode(['code' => 0, 'msg' => '请输入问题']);
    exit;
}

$url = "https://open.bigmodel.cn/api/paas/v4/chat/completions";
$apiKey = $_ENV['ZHIPU_API_KEY'] ?? '';

if (empty($apiKey)) {
    echo json_encode(['code' => 0, 'msg' => 'API密钥未配置']);
    exit;
}

$postData = [
    "model" => "glm-4-flash",
    "messages" => [
        ["role" => "system", "content" => "你是一个实习助手，专门解答实习相关的问题。"],
        ["role" => "user", "content" => $msg]
    ]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer " . $apiKey
    ]
]);

$res = curl_exec($ch);

if ($res === false) {
    echo json_encode(['code' => 0, 'msg' => '请求失败：' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$json = json_decode($res, true);

if (isset($json['choices'][0]['message']['content'])) {
    echo json_encode(['code' => 1, 'msg' => 'success', 'data' => $json['choices'][0]['message']['content']]);
} elseif (isset($json['error']['message'])) {
    echo json_encode(['code' => 0, 'msg' => 'API错误：' . $json['error']['message']]);
} else {
    echo json_encode(['code' => 0, 'msg' => 'AI暂时无法回答']);
}
?>
