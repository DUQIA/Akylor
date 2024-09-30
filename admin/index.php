<?php

$file = __DIR__ . '/language.php'; // 使用绝对路径
$log_file = dirname(__DIR__) . '/log.php';
$id_file = dirname(__DIR__) . '/session_id.php';
$url_flie = dirname(__DIR__) . '/url_status_code.php';

if (file_exists($file) && file_exists($log_file) && file_exists($id_file) && file_exists($url_flie)) {
    require $file; // 仅在文件存在的情况下包含
    require $log_file;
    require_once $id_file;
    require $url_flie;
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

    // 获取更新
    function get_update(): string
    {
        $url = new StatusCode();
        $versions_data = $url->getUpdate();
        $version_data = json_decode($versions_data[0][0], true);

        // 获取更新
        if (empty($version_data)) {
            return $versions_data[1];
        } elseif ($version_data[0]['name'] !== $versions_data[1]) {
            $htmlUrl = htmlspecialchars($version_data[0]['html_url'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            return $versions_data[1] .'<a href="' . $htmlUrl . '" alt="update" target="_blank" style="cursor: pointer; margin: 0 10px; color: blue;"><strong>获取更新</strong></a>';
        } else {
            return $versions_data[1];
        }
    }
    $versions = get_update();
} catch (Exception $e) {
    log_error($e->getMessage(), 'admin');
}
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['title']; ?></title>
    <link rel="icon" type="image/ico" href="../Akylor.ico">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <script src="https://registry.npmmirror.com/jquery/3.7.1/files/dist/jquery.min.js"></script>
    <script src="https://registry.npmmirror.com/echarts/5.5.1/files/dist/echarts.min.js"></script>
    <script src="./js/style.js"></script>
    <script type="text/javascript" src="./js/panel.js"></script>
</head>
    <body>
        <div class="loading" id="loading"></div>
        <div class="container">
            <div class="menu">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <img src="../Akylor.ico" alt="icon">
                                <span>Akylor</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a class="menu-item selected">
                                    <img src="./svg/dashboard-one.svg" alt="dashboard">
                                    <span><?php echo $translations['dashboard']; ?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="./logout.php" class="menu-item" id="logout">
                                    <img src="./svg/power.svg" alt="exit">
                                    <span><?php echo $translations['logout']; ?></span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="main">
                <div class="header">
                    <span>
                        <button class="content-item select" id="dashboard"><?php echo $translations['dashboard']; ?></a>
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
                                <h3><?php echo $translations['system_info']; ?></h3>
                                <div class="arrange">
                                    <div class="appose">
                                        <b><?php echo $translations['system_name']; ?></b>
                                        <p><?php echo $matches_system[1]; ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo $translations['host_name']; ?></b>
                                        <p><?php echo $matches_system[2]; ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo $translations['version_name']; ?></b>
                                        <p><?php echo $matches_system[3]; ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo $translations['version_info']; ?></b>
                                        <p><?php echo $matches_system[4]; ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo $translations['machine_type']; ?></b>
                                        <p><?php echo $matches_system[5]; ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo $translations['boot_time']; ?></b>
                                        <p><?php echo $system_start_time; ?></p>
                                    </div>
                                    <div class="appose">
                                        <b><?php echo $translations['run_time']; ?></b>
                                        <p id="time"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="processes">
                                <div id="info"></div>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        Akylor © <?php echo $versions; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>