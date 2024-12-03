<?php

$file = __DIR__ . '/language.php'; // 使用绝对路径
$log_file = dirname(__DIR__) . '/log.php';
$id_file = dirname(__DIR__) . '/session_id.php';

if (file_exists($file) && file_exists($log_file) && file_exists($id_file)) {
    require $file; // 仅在文件存在的情况下包含
    require $log_file;
    require_once $id_file;
} else {
    die('File does not exist');
}
$language = LANGUAGE;
$translations = TRANSLATIONS;

// 启动会话
start_session();

// 检查会话
check();

try {
    // 系统信息
    $system_information = php_uname();
    $pattern_system = "/^([^\s]+)\s([^\s]+)\s([^\s]+)\s[^-]+\D([^\s]+)\s.*\s(.*)$/";
    preg_match($pattern_system, $system_information, $matches_system);

    // 开机时间
    $system_start_time = date("Y-m-d H:i:s", filemtime("/proc/uptime"));
} catch (Exception $e) {
    log_error($e->getMessage(), 'admin');
}
?>
<!DOCTYPE html>
<html lang='<?php echo htmlspecialchars($language, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');?>'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?php echo htmlspecialchars($translations['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></title>
    <link rel='icon' type='image/ico' href='../Akylor.ico'>
    <link rel='stylesheet' type='text/css' href='./css/style.css'>
    <link rel='stylesheet' type='text/css' href='../css/loading.css'>
    <script src='./js/style.js'></script>
    <script src="../js/loading.js"></script>
    <script defer src='https://registry.npmmirror.com/jquery/3.7.1/files/dist/jquery.min.js'></script>
    <script defer src='https://registry.npmmirror.com/echarts/5.5.1/files/dist/echarts.min.js'></script>
    <script async type='text/javascript' src='./js/panel.js'></script>
</head>
    <body>
        <div class="loading"></div>
        <div class="container">
            <?php
                $menu = __DIR__ . '/menu.php';
                (file_exists($menu)) ? include $menu : die('File does not exist');
            ?>
            <div class="main">
                <div class="header">
                    <span>
                        <button type='button' class="content-item select" id="dashboard"><?php echo htmlspecialchars($translations['dashboard'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
                    </span>
                </div>
                <div class="layout">
                    <div id="dashboard" class="item show">
                        <div class="panel">
                            <div class="merge">
                                <div class="content">
                                    <div id="flow"></div>
                                </div>
                                <div class="content">
                                    <div id="resource"></div>
                                </div>
                            </div>
                            <div class="map">
                                <div class="content">
                                    <div id="world"></div>
                                </div>
                            </div>
                        </div>
                        <div class="message">
                            <div class="content">
                                <h3><?php echo htmlspecialchars($translations['system_info'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h3>
                                <div class="arrange">
                                    <div class="appose">
                                        <b><?php echo htmlspecialchars($translations['system_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></b>
                                        <p><?php echo htmlspecialchars($matches_system[1], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo htmlspecialchars($translations['host_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></b>
                                        <p><?php echo htmlspecialchars($matches_system[2], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo htmlspecialchars($translations['version_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></b>
                                        <p><?php echo htmlspecialchars($matches_system[3], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo htmlspecialchars($translations['version_info'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></b>
                                        <p><?php echo htmlspecialchars($matches_system[4], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo htmlspecialchars($translations['machine_type'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></b>
                                        <p><?php echo htmlspecialchars($matches_system[5], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo htmlspecialchars($translations['boot_time'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></b>
                                        <p><?php echo htmlspecialchars($system_start_time, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo htmlspecialchars($translations['run_time'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></b>
                                        <p id="time"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="processes">
                                <div id="info"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <?php
                        $footer = __DIR__ . '/footer.php';
                        (file_exists($footer)) ? include $footer : die('File does not exist');
                    ?>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                updateAnimation();
            })
        </script>
    </body>
</html>