<?php
function log_error($message, $filename) {
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

    // 记录错误日志
    error_log($message, 3, $logFile);
}
?>
