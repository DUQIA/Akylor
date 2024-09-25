<?php

$file = __DIR__ . '/config.php';
$id_file = __DIR__ . '/session_id.php';
$url_file = __DIR__ . '/url_status_code.php';

if (file_exists($file) && file_exists($id_file) && file_exists($url_file)) {
  require $file; // 仅在文件存在的情况下包含
  require_once $id_file;
  require_once $url_file;
} else {
  die('File does not exist');
}

class Db {
  private $conn;

  // 初始化连接
  public function __construct() {
    $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $this->connStatus();
  }

  // 检查连接
  public function connStatus() {
    if ($this->conn->connect_errno) {
      throw new Exception('Connection error: ' . $this->conn->connect_error);
    }
  }

  // 登录
  public function dbLogin($name, $pass) {
    $stmt = $this->conn->prepare("SELECT user, pass, session_id, is_active FROM users WHERE user = ?");
    if ($stmt) {
      $stmt->bind_param('s', $name);
      $stmt->execute();
      $result = $stmt->get_result(); // 获取结果

      if ($result->num_rows > 0) {
        // 检查密码
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['pass'])) { // 验证密码
          // 登录成功
          
          // 唯一登录
          $new_sid = session_id();
          $sid = $row['session_id'];
          if ($sid !== $new_sid) {
            $cookie = htmlspecialchars($new_sid, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $update_stmt = $this->conn->prepare("UPDATE users SET session_id = ? WHERE user = ?");
            if ($update_stmt) {
              $update_stmt->bind_param('ss', $cookie, $name);
              $update_stmt->execute();
            }
            $update_stmt->close();
          }
          if ($row['is_active'] === 1) {
            return 2; // 用户激活
          }
          return 3; // 用户未激活
          } else {
            // 密码错误
          }
        } else {
          // 用户不存在
        }
        echo "<script>alert('Logon failed');</script>";
      } else {
        throw new Exception('Login failed');
      }
    $stmt->close();
    return 1; // 默认返回
  }

  // cookie 验证
  public function dbCookie() {
    if (!isset($_COOKIE['UID'])) {
        check();
    }
    $cookie = htmlspecialchars($_COOKIE['UID'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $stmt = $this->conn->prepare("SELECT user, pass, session_id, is_active FROM users WHERE session_id = ?");
    if ($stmt) {
      $stmt->bind_param('s', $cookie);
      $stmt->execute();
      $result = $stmt->get_result(); // 获取结果
      if ($result->num_rows > 0) {
        // 获取cookie
        $row = $result->fetch_assoc();
        return $row['session_id'];
      }
    } // else { // 唯一 id 检测
    //   throw new Exception('Cookie verification failed');
    // }
    $stmt->close();
  }

  // IP 统计
  public function dbIpCount() {
    $stmt = $this->conn->prepare("SELECT country, COUNT(*) as count FROM ip_log GROUP BY country");
    if ($stmt) {
      $data = array();
      $stmt->execute();
      $result = $stmt->get_result(); // 获取结果
      while ($row = $result->fetch_assoc()) {
        $data[$row['country']] = $row['count']; // 合并新数组
      }
      $result->free_result();
      return $data;
    } else {
      throw new Exception('Query failure');
    }
    $stmt->close();
  }

  // IP 访问记录
  public function dbIp() {
    $ip = (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) ?: 'invalid Ip'; // 获取当前ip地址
    $response = new StatusCode();
    $country = $response->getIp($ip);

    $stmt = $this->conn->prepare("INSERT INTO ip_log (ip, country, timestamp, request_method, protocol, request_code, accessed_file, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $timestamp = filter_var($_SERVER['REQUEST_TIME'], FILTER_VALIDATE_INT); // 时间戳
    $method = htmlspecialchars($_SERVER['REQUEST_METHOD'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 请求方法
    $protocol = htmlspecialchars($_SERVER['SERVER_PROTOCOL'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 协议
    $response_code = http_response_code(); // 响应码
    $file = htmlspecialchars($_SERVER['SCRIPT_NAME'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 访问文件
    $proxy = htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 用户代理
    if ($stmt) {
      $stmt->bind_param(
        'sssssiss',
        $ip,
        $country,
        $timestamp,
        $method,
        $protocol,
        $response_code,
        $file,
        $proxy
      );
      $stmt->execute();
    } else {
      throw new Exception('Insertion failure');
    }
    $stmt->close();
  }

  // 对象销毁时关闭连接
  public function __destruct() {
    // 关闭连接
    if ($this->conn) {
      $this->conn->close();
    }
  }
}