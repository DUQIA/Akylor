<?php

$id_file = dirname(__DIR__) . '/session_id.php';

// 使用绝对路径来防止文件包含漏洞
if (file_exists($id_file)) {
    require_once $id_file; // 通过使用 require_once 来防止重复包含
} else {
    die('File does not exist');
}

// 检查会话
if (!isset($_COOKIE['UID']) || $_COOKIE['UID'] !== session_id() || $cookie !== session_id()) {
    check();
}

// 销毁会话
// 需要在所有 echo print_r header 之前摧毁 cookie 信息
destroy();