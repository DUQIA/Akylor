<?php

class StatusCode {
    private string $update_url = 'https://api.github.com/repos/DUQIA/Akylor/releases';
    private string $current_version = 'v0.0.01';

    // 获取状态码，并判断
    public function code(string $command): array|false
    {
        // 使用 escapeshellcmd 和 escapeshellarg 进行命令安全处理
        $command = escapeshellcmd($command);
        // 使用 exec 获取输出和返回码
        exec($command, $output, $return_var);

        if ($return_var === 0) {
            return $output;
        } else {
            return false;
        }
    }

    // 获取更新信息
    public function getUpdate(): array
    {
        $command = 'curl -s -H "User-Agent: Mozilla/5.0 (compatible; PHP script)" ' . escapeshellarg($this->update_url);
        $output = $this->code($command);
        if (empty($output) || !isset($output[0])) {
            return array($this->current_version, $this->current_version);
        } else {
            return array($output, $this->current_version);
        }
    }

    // whois 获取IP信息
    public function getIp(string $ip): string
    {
        if ($ip === 'invalid Ip') {
            return 'Unknown';
        }
        $command = 'timeout 1 whois ' . escapeshellarg($ip) . ' | grep -i country';
        $output = $this->code($command);

        if (!empty($output) && isset($output[0])) {
            $pattern = '/country:\s+(\w+)/';
            preg_match($pattern, $output[0], $matches);

            // 国家代码转换
            $filePath = __DIR__ . '/js/names.json';
            if (!file_exists($filePath)) {
                die('File does not exist');
            }
            $compatable = file_get_contents($filePath);
            $country = json_decode($compatable, true);
            foreach ($country as $key => $value) {
                if (!isset($matches) && $key === $matches[1]) {
                    return $value;
                } else {
                    return 'Unknown';
                }
            }
        }
        return 'Unknown';
    }
}
