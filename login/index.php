<?php

$file = dirname(__DIR__) . '/language.php'; // 使用绝对路径
$db_file = dirname(__DIR__) . '/db_dispose.php';
$id_file = dirname(__DIR__) . '/session_id.php';
$log_file = dirname(__DIR__) . '/log.php';

if (file_exists($file) && file_exists($db_file) && file_exists($id_file) && file_exists($log_file)) {
    require $file; // 仅在文件存在的情况下包含
    require $db_file;
    require_once $id_file;
    require $log_file;
} else {
    die('File does not exist');
}
$language = LANGUAGE;
$translations = TRANSLATIONS;

// 启动会话
start_session();

// 获取当前步骤
$step = isset($_POST['step']) ? $_POST['step'] : 1;

// CSRF 保护
$csrf_token = !isset($_SESSION['csrf_token']) ? bin2hex(random_bytes(32)) : $_SESSION['csrf_token']; // 生成 CSRF token
$_SESSION['csrf_token'] = $csrf_token; // 存储 token

// 登录步骤 引用 外部步骤和内部保持一致
function login_step(int &$step): int
{
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            !isset($_POST['csrf_token']) || hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']) ?: die('CSRF token validation failed');
            switch ($step) {
                case 1:
                    // IP记录
                    $db_class = new Db();
                    $db_class->dbIp();
                    // 获取用户输入的用户名和密码
                    $ad_name = htmlspecialchars(filter_input(INPUT_POST, 'ad_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $ad_pass = htmlspecialchars(filter_input(INPUT_POST, 'ad_pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $login_value = $db_class->dbLogin($ad_name, $ad_pass);
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
<html lang="<?php echo htmlspecialchars($language, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($translations['title_login'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></title>
    <link rel="icon" type="image/ico" href="../Akylor.ico">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/loading.css">
    <script src="../js/style.js"></script>
    <script src="../js/loading.js"></script>
</head>
    <body>
        <div class="loading"></div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
        <input type='hidden' name='csrf_token' value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"> <!-- CSRF token -->
        <!-- 外部步骤 -->
        <input type="hidden" name="step" value="<?php echo htmlspecialchars($step, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
            <?php if ($step === 1): ?>
                <body>
                    <div class="loading"></div>
                    <div class="container">
                    <h1><img src="../Akylor.ico" alt="icon" style="width: 100px;"></h1>
                    <input type="text" name="ad_name" id="ad_name" maxlength="20" size="20" placeholder="<?php echo htmlspecialchars($translations['ad_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" required autofocus><br>
                    <div class="pass-show-hide">
                        <input type="password" name="ad_pass" id="pass" maxlength="20" size="20" placeholder="<?php echo htmlspecialchars($translations['ad_pass'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" autocomplete="off" required><br>
                        <button type="button" id="toggle"><img src="../svg/preview-open.svg" alt="preview" id="toggle_password"></button>
                    </div>
                    <input type="submit" id="submit" value="<?php echo htmlspecialchars($translations['submit'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
                    </div>
                </body>
            <?php elseif ($step === 2): ?>
                <body>
                    <div class="container">
                    <h1><img src="../Akylor.ico" alt="icon" style="width: 100px;"></h1>
                    </div>
                </body>
            <?php else: ?>
                <?php
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $host = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $url = $protocol . $host . '/admin'; // 构建完整 URL
                header('Location: ' . $url);
                exit(); // 确保在重定向后停止脚本执行
                ?>
            <?php endif; ?>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                updateAnimation();
            })
        </script>
    </body>
</html>
