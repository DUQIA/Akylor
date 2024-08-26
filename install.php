<?php
$file = __DIR__ . '/language.php'; // 使用绝对路径
$log = __DIR__ . '/log.php';
if (file_exists($file) && is_readable($log)) {
    require($file);
    require($log);
} else {
    die('File does not exist');
}
$language = LANGUAGE;
$translations = TRANSLATIONS;

session_start();
// 获取当前协议
$protocol = (!empty($_SESSION['HTTPS']) && $_SESSION['HTTPS'] !== 'off') ? 'https' : 'http';

// 根据文件确定步骤
$step = file_exists('config.php') ? 4 : 1;

// 连接数据库
function  sql_conn() {
    $conn = new mysqli($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_pass'], $_SESSION['db_name']);
    if ($conn->connect_errno) {
      throw new Exception('conn:' . $conn->connect_error);
    }
    return $conn;
}

// 创建数据库
function sql_create($conn) {
    $sql_users = 'users';
    // 如果表创建，就跳过
    $sql = "CREATE TABLE IF NOT EXISTS {$sql_users} (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user VARCHAR(20) NOT NULL,
        pass VARCHAR(100) NOT NULL,
        is_active BOOLEAN DEFAULT FALSE,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
    $conn->query($sql);

    // 插入管理账户
    $ad_name = htmlspecialchars(filter_input(INPUT_POST, 'ad_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $ad_pass = htmlspecialchars(filter_input(INPUT_POST, 'ad_pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $stmt = $conn->prepare("INSERT INTO {$sql_users} (user, pass) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param('ss', $ad_name, password_hash($ad_pass, PASSWORD_ARGON2I));
        $stmt->execute();
        $stmt->close();
    }
}

// 写入config文件
function write_config() {
    $config_content = "<?php\n" .
        "define('DB_HOST', '{$_SESSION['db_host']}');\n" .
        "define('DB_USER', '{$_SESSION['db_user']}');\n" .
        "define('DB_PASS', '{$_SESSION['db_pass']}');\n" .
        "define('DB_NAME', '{$_SESSION['db_name']}');\n" ;
    file_put_contents('config.php', $config_content, LOCK_EX);
}

// 获取当前步骤
$step = isset($_POST['step']) ? $_POST['step'] : $step;

// 安装步骤 引用 外部步骤和内部步骤保持一致
function install_step(&$step) {
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($step) {
                case 1:
                    $_SESSION['db_host'] = filter_input(INPUT_POST, 'db_host', FILTER_SANITIZE_URL);
                    $_SESSION['db_user'] = htmlspecialchars(filter_input(INPUT_POST, 'db_user', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $_SESSION['db_pass'] = htmlspecialchars(filter_input(INPUT_POST, 'db_pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $_SESSION['db_name'] = htmlspecialchars(filter_input(INPUT_POST, 'db_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $conn = sql_conn();
                    $conn->close();
                    $step = 2;
                    break;
                case 2:
                    $conn = sql_conn();
                    sql_create($conn);
                    $conn->close();
                    write_config();
                    // 创建账户完成后，删除敏感信息
                    unset($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_pass'], $_SESSION['db_name']);
                    $step = 3;
                    break;
                case 3:
                    $step = 4;
                    break;
                default:
                    break;
            }
            $_SESSION['step'] = $step; // 更新会话中的步骤信息
        }
    } catch (Exception $e) {
      log_error($e->getMessage(), 'install');
    }
    return $step;
} 

// 将内部步骤传递给外部
$step = install_step($step);

// 确定步骤
if ($step === 1) {
  $step = 1;
} elseif ($step > 1 && $step < 4) {
  $step = isset($step) ? $step : 1;
} elseif ($step === 4) {
  $step = 4;
} else {
  header("Location: " . $_SERVER['PHP_SELF']);
}
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $translations['title']; ?></title>
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
          <input type="text" name="db_host" id="db_host" placeholder="<?php echo $translations['db_host']; ?>" required autofocus><br>
          <input type="text" name="db_user" id="db_user" placeholder="<?php echo $translations['db_user']; ?>" required><br>
          <div class="pass-show-hide">
            <input type="password" name="db_pass" id="pass" placeholder="<?php echo $translations['db_pass']; ?>" autocomplete="off" required>
            <button type="button" onclick="showHide()"><img src="./svg/preview-open.svg" alt="preview" id="toggle_password"></button>
          </div>
          <input type="text" name="db_name" id="db_name" placeholder="<?php echo $translations['db_name']; ?>" required><br>
          <input type="submit" id="next" value="<?php echo $translations['next']; ?>">
        </div>
      </body>
    <?php elseif ($step === 2): ?>
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
    <?php elseif ($step === 3): ?>
      <body>
        <div class="container">
          <h1><img src="./Akylor.ico" alt="icon" style="width: 100px;"></h1>
          <h1><?php echo $translations['complete']; ?></h1>
          <span>
            <a href="<?php echo $protocol . $_SERVER['HTTP_HOST'] ?>"><?php echo $translations['home']; ?></a>
            <a href="./login.php"><?php echo $translations['login']; ?></a>
          </span>
        </div>
    </body>
    <?php else: ?>
      <body>
        <div class="container">
          <h1><img src="./Akylor.ico" alt="icon" style="width: 100px;"></h1>
          <h1><?php echo $translations['Successfully']; ?></h1>
          <span>
            <a href="./login.php"><?php echo $translations['login']; ?></a>
          </span>
        </div>
      </body>
    <?php endif; ?>
</form>
</html>