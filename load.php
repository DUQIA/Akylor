<?php
// 使用绝对路径来避免路径遍历攻击
$configFile = __DIR__ . '/config.php';
$indexFile = __DIR__ . '/www/index.php';

if (file_exists($configFile)) {
    require $indexFile;
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $url = $protocol . $host . '/install.php'; // 构建完整 URL
    header('Location: ' . $url);
    exit(); // 确保在重定向后停止脚本执行
}
?>