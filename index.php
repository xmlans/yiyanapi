<?php
// api.xmc.tw/index.php
// By Star Dream Studio
// 设置响应类型和跨域头
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");  // 如有需要，可将 * 改为指定域名

// 数据库配置
$host = 'localhost';   // 数据库连接地址
$db   = '';   // 数据库名
$user = '';   // 数据库用户名
$pass = '';   // 数据库密码
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => '数据库连接失败: ' . $e->getMessage()]);
  exit;
}

try {
  $pdo->exec("UPDATE api_counter SET request_count = request_count + 1 WHERE id = 1");
} catch (PDOException $e) {
}

if (isset($_GET['action']) && $_GET['action'] === 'total') {
  $startTime = microtime(true);
  
  $sql = "SELECT SUM(CHAR_LENGTH(sentence)) AS total_chars FROM sentence";
  $stmt = $pdo->query($sql);
  $row = $stmt->fetch();
  
  if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => '没有找到数据']);
    exit;
  }
  
  $total_chars = $row['total_chars'];
  $query_time = microtime(true) - $startTime;
  
  $stmt2 = $pdo->query("SELECT request_count FROM api_counter WHERE id = 1");
  $row2 = $stmt2->fetch();
  $api_request_count = $row2 ? $row2['request_count'] : 0;
  
  echo json_encode([
    'status' => 200,
    'total_char_count' => (int)$total_chars,
    'query_time' => round($query_time, 4),
    'api_request_count' => (int)$api_request_count
  ]);
  exit;
} else {
  $startTime = microtime(true);
  
  $stmt = $pdo->query("SELECT * FROM sentence ORDER BY RAND() LIMIT 1");
  $result = $stmt->fetch();
  
  if (!$result) {
    http_response_code(404);
    echo json_encode(['error' => '没有找到任何数据']);
    exit;
  }
  
  $query_time = microtime(true) - $startTime;
  $charCount = mb_strlen($result['sentence'], 'UTF-8');
  
  echo json_encode([
    'status' => 200,
    'data' => $result,
    'query_time' => round($query_time, 4),
    'char_count' => $charCount
  ]);
  exit;
}
?>
