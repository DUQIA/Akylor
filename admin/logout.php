<?php

$id_file = dirname(__DIR__) . '/session_id.php';

// 使用绝对路径来防止文件包含漏洞
(file_exists($id_file)) ? require_once $id_file : die('File does not exist');


// 检查会话
if (!isset($_COOKIE['UID']) || !is_string($_COOKIE['UID']) || $_COOKIE['UID'] !== session_id()) {
    check();
}

// 销毁会话
// 需要在所有 echo print_r header 之前摧毁 cookie 信息
destroy();
