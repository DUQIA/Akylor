<?php

class StatusCode {
    private string $update_url = 'https://api.github.com/repos/DUQIA/Akylor/releases';
    private string $current_version = 'v0.0.02';

    // 获取状态码，并判断
    private function code(string $command): array|false
    {
        // 使用 escapeshellcmd 和 escapeshellarg 进行命令安全处理
        $command = escapeshellcmd($command);
        // 使用 exec 获取输出和返回码
        exec($command, $output, $return_var);

        return ($return_var === 0) ? $output : false;
    }

    // 获取更新信息
    public function getUpdate($translations): string
    {
        $command = 'curl -s -H "User-Agent: Mozilla/5.0 (compatible; PHP script)" ' . escapeshellarg($this->update_url);
        $output = $this->code($command);

        $versions_data = ($output === false || !isset($output[0])) ? array($this->current_version, $this->current_version) : array($output, $this->current_version);
        
        $version_data = json_decode($versions_data[0][0], true);

        // 获取更新
        if (empty($version_data[0]['name'])) {
            return $versions_data[1] .'<a href="https://github.com/DUQIA/Akylor/releases" alt="update" target="_blank" style="cursor: pointer; color: red;"><strong>' . htmlspecialchars($translations['get_failed'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</strong></a>';
        } elseif ($version_data[0]['name'] !== $versions_data[1]) {
            $htmlUrl = htmlspecialchars($version_data[0]['html_url'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            return $versions_data[1] .'<a href="' . $htmlUrl . '" alt="update" target="_blank" style="cursor: pointer; color: blue;"><strong>' . htmlspecialchars($translations['get_update'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</strong></a>';
        } else {
            return $versions_data[1];
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

        if ($output !== false && isset($output[0])) {
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
                return (!isset($matches) && $key === $matches[1]) ? $value : 'Unknown';
            }
        }
        return 'Unknown';
    }
}
