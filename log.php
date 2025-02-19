<?php

function log_error(string $message, string $filename): void
{
    // 验证文件名，确保没有目录遍历攻击
    if (preg_match('/^[a-zA-Z0-9_\-]+$/', $filename) !== 1) {
        throw new InvalidArgumentException('无效的文件名');
    }

    $logDir = __DIR__ . '/log';
    $logFile = $logDir . '/' . $filename . '.log';

    // 检查目录是否存在，如果不存在则创建
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true); // 设置目录权限为 0755
    }

    // 检查文件是否存在，如果不存在则创建
    if (!file_exists($logFile)) {
        file_put_contents($logFile, ''); // 创建空文件
        chmod($logFile, 0644); // 设置文件权限为 0644
    }

    // 过滤和转义日志消息
    $message = strip_tags($message); // 移除 HTML 和 PHP 标签
    $message = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 转义特殊字符

    // 记录错误日志
    error_log(date("Y/m/d/H-i-s") . ':' . $message . PHP_EOL, 3, $logFile);
}
