<?php

$file = dirname(__DIR__) . '/language.php';
$id_file = dirname(__DIR__) . '/session_id.php';
$url_flie = dirname(__DIR__) . '/url_status_code.php';

if (file_exists($file) && file_exists($id_file) && file_exists($url_flie)) {
    require_once $id_file;
    require_once $url_flie;
} else {
    die('File does not exist');
}
$translations = TRANSLATIONS;

// 检查会话
if (!isset($_COOKIE['UID']) || !is_string($_COOKIE['UID']) || $_COOKIE['UID'] !== session_id()) {
    check();
}

// 获取更新
$url = new StatusCode();
$versions = $url->getUpdate($translations);
?>
Akylor © <?php echo $versions; ?>