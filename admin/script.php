<?php

$id_file = dirname(__DIR__) . '/session_id.php';
$log_file = dirname(__DIR__) . '/log.php';
if (file_exists($id_file) && file_exists($log_file)) {
    require_once $id_file; // 使用 require_once 防止重复包含
    require_once $log_file;
} else {
    die('File does not exist');
}

// 启动会话
start_session();

// 检查会话
check();

try {
    // 设置超时时间（例如3秒）
    $timeout = 3;
    $command = escapeshellcmd('timeout ' . $timeout . ' vnstat -l --json');  // 过滤命令
    $output = shell_exec($command);

    // 匹配 rx 中的 ratestring
    $pattern_rx = '/"rx":\{"ratestring":"([0-9.]+) kbit\/s"/';
    preg_match_all($pattern_rx, $output, $matches_rx);

    // 匹配 tx 中的 ratestring
    $pattern_tx = '/"tx":\{"ratestring":"([0-9.]+) kbit\/s"/';
    preg_match_all($pattern_tx, $output, $matches_tx);

    // ram
    $memory_info = shell_exec('free -m');
    $pattern_ram = '/Mem:\s+(\d+)\s+(\d+)/';
    preg_match($pattern_ram, $memory_info, $matches_ram);
    $ram_total = intval($matches_ram[1]);
    $ram_used = intval($matches_ram[2]);
    $ram_usage_percentage = ($ram_used / $ram_total) * 100;
    $ram = round($ram_usage_percentage, 2);

    // cpu
    $cpu_info = shell_exec('top -bn1 | grep "Cpu(s)"');
    $pattern_cpu = '/(\d+.\d+)\s+id/';
    preg_match($pattern_cpu, $cpu_info, $matches_cpu);
    $cpu = round(100 - $matches_cpu[1], 2);

    // 运行时间
    $system_run_time = shell_exec('uptime -p');

    // 进程
    $processes_info = shell_exec('ps -eo pid,cmd,%cpu,%mem --sort=-%cpu | head -n 10');

    // ip统计
    $file = __DIR__ . '/language.php';
    $db = dirname(__DIR__) . '/db_dispose.php';
    if (file_exists($db)) {
    include $file;
    include_once $db;
    } else {
    die('File does not exist');
    }
    $language = LANGUAGE;
    // IP记录
    $new_counts = array();
    $db_class = new Db();
    $counts = $db_class->dbIpCount();

    // 是否为中文
    if ($language === 'zh') {
        $lang_zh = file_get_contents(dirname(__DIR__) . '/js/names-zh.json');
        $zh = json_decode($lang_zh, true);

        // 将匹配的中文替换英文，映射到新表中
        foreach ($counts as $key => $value) {
            if (isset($zh[$key])) {
                $new_key = $zh[$key]; // 获取键
                $new_counts[$new_key] = $value;
            } else {
                $new_counts[$key] = $value;
            }
        }
    } else {
        $new_counts = $counts;
    }

    // 合并结果
    $results = array_merge(array(floatval(current($matches_rx[1])), floatval(current($matches_tx[1])), $ram, $cpu, $system_run_time, $processes_info), $new_counts);

    header('Content-Type: application/json');
    echo json_encode($results, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    log_error($e->getMessage(), 'script');
}