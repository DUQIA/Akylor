<?php

// 使用绝对路径来避免路径遍历攻击
$file = __DIR__ . '/config.php';
$index_file = __DIR__ . '/www/index.php';

if (file_exists($file)) {
    require $index_file;
} else {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $url = $protocol . $host . '/install.php'; // 构建完整 URL
    header('Location: ' . $url);
    exit(); // 确保在重定向后停止脚本执行
}
?>