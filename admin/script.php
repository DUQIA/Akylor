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
    class script {

        private string $command_bandwidth = 'timeout 3 vnstat -l --json'; // 设置超时时间（例如3秒）带宽
        private string $command_ram = 'free -m'; // 内存
        private string $command_cpu = 'top -bn1 | grep "Cpu(s)"'; // cpu
        private string $command_run_time = 'uptime -p'; // 运行时间
        private string $command_processes = 'ps -eo pid,cmd,%cpu,%mem --sort=-%cpu | head -n 10'; // 进程前10，cpu排序

        // 子进程
        private function proc(string $command): string
        {
            $process = popen($command, "r");
            if ($process) {
                $output = stream_get_contents($process);
                pclose($process);
                return $output;
            }
            return '';
        }

        // 带宽
        private function bandWidth(): array
        {
            $stdout = $this->proc($this->command_bandwidth);
            // 处理输出
            if (isset($stdout)) {
                // 匹配 rx 中的 ratestring
                $pattern_rx = '/"rx":\{"ratestring":"([0-9.]+) kbit\/s"/';
                preg_match_all($pattern_rx, $stdout, $matches_rx);
                // 匹配 tx 中的 ratestring
                $pattern_tx = '/"tx":\{"ratestring":"([0-9.]+) kbit\/s"/';
                preg_match_all($pattern_tx, $stdout, $matches_tx);
                return array(floatval(current($matches_rx[1])), floatval(current($matches_tx[1])));
            }
            return array(0, 0);
        }

        // 内存
        private function ram(): array
        {
            $memory_info = $this->proc($this->command_ram);
            if (isset($memory_info)) {
                $pattern_ram = '/Mem:\s+(\d+)\s+(\d+)/';
                preg_match($pattern_ram, $memory_info, $matches_ram);
                $ram_total = intval($matches_ram[1]);
                $ram_used = intval($matches_ram[2]);
                $ram_usage_percentage = ($ram_used / $ram_total) * 100;
                $ram = round($ram_usage_percentage, 2);
                return array($ram);
            }
            return array(0);
        }

        // cpu
        private function cpu(): array
        {
            $cpu_info = $this->proc($this->command_cpu);
            if (isset($cpu_info)) {
                $pattern_cpu = '/(\d+.\d+)\s+id/';
                preg_match($pattern_cpu, $cpu_info, $matches_cpu);
                $cpu = round(100 - floatval($matches_cpu[1]), 2);
                return array($cpu);
            }
            return array(0);
        }

        // 运行时间
        private function runTime(): array
        {
            $run_time_info = $this->proc($this->command_run_time);
            if (isset($run_time_info)) {
                return array($run_time_info);
            }
            return array(0);
        }

        // 进程
        private function processes(): array
        {
            $processes_info = $this->proc($this->command_processes);
            if (isset($processes_info)) {
                return array($processes_info);
            }
            return array(0);
        }

        // ip统计
        private function ipStatistics(): array
        {
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
            return $new_counts;
        }

        public function info(): array
        {
            // 获取服务器信息
            $results = array_merge($this->bandWidth(), $this->ram(), $this->cpu(), $this->runTime(), $this->processes(), $this->ipStatistics());
            return $results;
        }
    }

    $script = new script();
    $results = $script->info();
    
    // 合并结果

    header('Content-Type: application/json');
    echo json_encode($results, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    log_error($e->getMessage(), 'script');
}