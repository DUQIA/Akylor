<?php
$file = __DIR__ . '/language.php'; // 使用绝对路径
$db = __DIR__ . '/db_dispose.php';
$log = __DIR__ . '/log.php';

if (file_exists($file) && file_exists($db) && file_exists($log)) {
    require $file; // 仅在文件存在的情况下包含
    require $db;
    require $log;
} else {
    die('File does not exist');
}
$language = LANGUAGE;
$translations = TRANSLATIONS;

ini_set('session.use_strict_mode', 1); // 开启严格模式
session_start();

// 设置cookie
function set_cookie() {
  $sessionId = session_id();
  setcookie('SID', $sessionId, [
      'expires' => 0, // 关掉浏览器时
      'path' => '/',
      'domain' => $_SERVER['HTTP_HOST'], // 动态设置域名
      'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? true : false, // 仅通过HTTPS传输
      'httponly' => true, // 禁止JavaScript访问
      'samesite' => 'Lax' // 防止CSRF攻击
  ]);
}

// 获取当前步骤
$step = isset($_POST['step']) ? $_POST['step'] : 1;

// 登录步骤 引用 外部步骤和内部保持一致
function login_step(&$step) {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        switch ($step) {
          case 1:
            // 获取用户输入的用户名和密码
            $ad_name = htmlspecialchars(filter_input(INPUT_POST, 'ad_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $ad_pass = htmlspecialchars(filter_input(INPUT_POST, 'ad_pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');    
            $login_value = query($ad_name, $ad_pass);
            // 登录成功设置 cookie
            if ($login_value > 1) {
              set_cookie();
            }
            // 调用 query 函数并传递实际的值
            $step = $login_value;
            break;
          case 2:
            $step = 3;
            break;
          default:
            break;
        }
        $_SESSION['step'] = $step; // 更新会话中的步骤信息
      }
    } catch (Exception $e) {
      log_error($e->getMessage(), 'login');
    }
    return $step;
}

// 将内部步骤传递给外部
$step = login_step($step);
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $translations['title_login']; ?></title>
    <link rel="icon" type="image/ico" href="./Akylor.ico">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <script src="./js/style.js"></script>
</head>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
  <!-- 外部步骤 -->
  <input type="hidden" name="step" value="<?php echo $step; ?>">
    <?php if ($step === 1): ?>
        <body>
            <div class="container">
            <h1><img src="./Akylor.ico" alt="icon" style="width: 100px;"></h1>
            <input type="text" name="ad_name" id="ad_name" maxlength="20" size="20" placeholder="<?php echo $translations['ad_name']; ?>" required autofocus><br>
            <div class="pass-show-hide">
                <input type="password" name="ad_pass" id="pass" maxlength="20" size="20" placeholder="<?php echo $translations['ad_pass']; ?>" autocomplete="off" required><br>
                <button type="button" onclick="showHide()"><img src="./svg/preview-open.svg" alt="preview" id="toggle_password"></button>
            </div>
            <input type="submit" id="submit" value="<?php echo $translations['submit']; ?>">
            </div>
        </body>
    <?php elseif ($step === 2): ?>
        <body>
            <div class="container">
            <h1><img src="./Akylor.ico" alt="icon" style="width: 100px;"></h1>
            </div>
        </body>
    <?php else: ?>
      <?php
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $url = $protocol . $host . '/admin'; // 构建完整 URL
        header('Location: ' . $url);
        exit(); // 确保在重定向后停止脚本执行
      ?>
    <?php endif; ?>