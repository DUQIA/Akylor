<?php
$file = __DIR__ . '/config.php';

if (file_exists($file)) {
  require $file; // 仅在文件存在的情况下包含
} else {
  die('File does not exist');
}

// 登录
function query($name, $pass) {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); 
  if ($conn->connect_errno) {
    throw new Exception('conn:' . $conn->connect_error);
  }
  $stmt = $conn->prepare("SELECT user, pass, is_active FROM users WHERE user = ?");
  if ($stmt) {
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result(); // 获取结果

    if ($result->num_rows > 0) {
      // 检查密码
      $row = $result->fetch_assoc();
      if (password_verify($pass, $row['pass'])) { // 验证密码
        // 登录成功
        if ($row['is_active'] === 1) {
          return 2;
        }
        return 3;
      } else {
        // 密码错误
      }
    } else {
      // 用户不存在
    }
    
    $stmt->close();
  }
  $conn->close(); // 确保连接在所有情况下都被关闭
  echo "<script>alert('Logon failed');</script>";
  return 1; // 默认返回
}
?>