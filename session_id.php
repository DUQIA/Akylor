<?php

// 不要将敏感信息暴露给用户
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 暴露更多的错误消息
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

try {
    function start_session(): void
    {
        session_name('UID');
        ini_set('session.use_strict_mode', 1); // 开启严格模式

        // 设置会话参数
        session_set_cookie_params([
            'lifetime' => 0, // 会话在浏览器关闭时失效
            'path' => '/', // 会话 cookie 的路径
            'domain' => htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), // 会话 cookie 的域名
            'secure' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), // 仅通过 HTTPS 传输
            'httponly' => true, // 禁止 JavaScript 访问
            'samesite' => 'Strict', // 防止 CSRF 攻击，使用更严格的策略 Strict Lax
            // 'Partitioned' => true, // 指定分区
            ]);
            
        // 检查会话是否已经启动
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // 启动会话
        }
        
        // 初始化会话
        if (!isset($_SESSION['initialized'])) {
            session_regenerate_id(true); // 重新生成会话 ID，防止会话固定攻击
            $_SESSION['initialized'] = true; // 设置初始化标志
        }
    }

    // 检查是否已设置 SID cookie 并与当前会话 ID 匹配
    function check(): void
    {
        // 受保护页面不缓存
        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0"); // Proxies.

        // 获取服务器的域名
        $host = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        // 验证 Origin 或 Referer 头是否来自合法域名
        $valid_origin = false;
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $origin = htmlspecialchars($_SERVER['HTTP_ORIGIN'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            if (stripos($origin, $host) !== false) {
                $valid_origin = true;
            }
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            if (stripos($referer, $host) !== false) {
                $valid_origin = true;
            }
        }

        if (!$valid_origin) {
            redirect();
        }
        
        // 验证 cookie 有效性
        if (isset($_COOKIE['UID'])) {
            $db = __DIR__ . '/db_dispose.php';
            if (file_exists($db)) {
                include_once $db;
            } else {
                die('File does not exist');
            }
            // Cookie查询
            $db_class = new Db();
            $cookie = $db_class->dbCookie();
        }
        if (!isset($_COOKIE['UID']) || $_COOKIE['UID'] !== session_id() || $cookie !== session_id()) {
            redirect();
        }
    }

    // 页面跳转
    function redirect(): void
    {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $url = $protocol . $host . '/login.php'; // 构建完整 URL
        header('Location: ' . $url);
        exit(); // 确保在重定向后停止脚本执行
    }
    
    // 销毁 cookie
    function destroy(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 清除会话cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        // 清除 SID Cookie
        setcookie('UID', '', time() - 42000, '/');
        
        // 销毁会话
        session_unset();
        session_destroy();
    }
} catch (Exception $i) {
    log_error($i->getMessage(), 'session_id');
}