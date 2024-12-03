<?php

$db_file = dirname(__DIR__) . '/db_dispose.php';
$log_file = dirname(__DIR__) . '/log.php';
$id_file = dirname(__DIR__) . '/session_id.php';

if (file_exists($db_file) && file_exists($log_file) && file_exists($id_file)) {
    require $db_file;
    require $log_file;
    require_once $id_file;
} else {
    die('File does not exist');
}

// 启动会话
start_session();

// 获取主题
try {
    $db_class = new Db();
    $db_class->dbIp(); // IP记录
    $home_configs = $db_class->dbQueryHomeConfigAll(); // 获取站点配置
    !empty($home_configs) ?: die('Go to the background to create the configuration! <a href=' . wait('/login') . '>ClickHere</a>');

    // 导入主题
    $theme = __DIR__ . '/themes/' . $home_configs['home_theme'] . '/' . 'index.php';
    (file_exists($theme)) ? require $theme : die('File does not exist');
} catch (Exception $e) {
    log_error($e->getMessage(), 'content');
}
