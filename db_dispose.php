<?php

$file = __DIR__ . '/config.php';
$id_file = __DIR__ . '/session_id.php';
$url_file = __DIR__ . '/url_status_code.php';

if (file_exists($file) && file_exists($id_file) && file_exists($url_file)) {
    require $file; // 仅在文件存在的情况下包含
    require_once $id_file;
    require_once $url_file;
} else {
    die('File does not exist');
}

class Db {
    private mysqli $conn;
    private string $home_config = 'home_config';
    private string $home_labels = 'home_labels';

    // 初始化连接
    public function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->connStatus();
    }

    // 事务开始
    private function beginTransaction(): void
    {
        if ($this->conn->autocommit(false)) {
            // 开始事务
            $this->conn->begin_transaction();
        } else {
            throw new Exception('Transaction begin failed');
        }
    }

    // 事务提交
    private function commit(): void
    {
        if ($this->conn->commit()) {
            // 提交事务
            $this->conn->autocommit(true);
        } else {
            throw new Exception('Transaction commit failed');
        }
    }

    // 事务回滚
    private function rollBack(): void
    {
        if ($this->conn->rollback()) {
            // 回滚事务
            $this->conn->autocommit(true);
        } else {
            throw new Exception('Transaction rollback failed');
        }
    }

    // 检查连接
    private function connStatus(): void
    {
        if ($this->conn->connect_errno) {
            throw new Exception('Connection error: ' . $this->conn->connect_error);
        }
    }

    // 清除
    private function dbClear(string $command, string $failed): void
    {
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            $stmt->execute();
        } else {
            throw new Exception($failed);
        }
    }

    // 所有行查询
    private function dbQueryAllLine(string $command, string $failed): array
    {
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result(); // 获取结果
            if ($result->num_rows > 0) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $result->free_result(); // 释放内存
                return $data;
            } else {
                return [];
            }
        } else {
            throw new Exception($failed);
        }
    }

    // 统计查询
    private function dbQueryCount(string $key, string $value, string $command, string $failed): array
    {
        $data = array();
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result(); // 获取结果
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[$row[$key]] = $row[$value]; // 合并新数组
                  }
            }
            $result->free_result(); // 释放内存
            return $data;
        } else {
            throw new Exception($failed);
        }
    }

    // 单行查询
    private function dbQuerySingleLine(string $command, string $failed): array
    {
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result(); // 获取结果
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $result->free_result(); // 释放内存
                return $data;
            } else {
                return [];
            }
        } else {
            throw new Exception($failed);
        }
    }

    // 更新
    private function dbUpdate(string $command, string $bindType, array $bindParam, string $failed): void
    {
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            $stmt->bind_param($bindType, ...$bindParam);
            $stmt->execute();
        } else {
            throw new Exception($failed);
        }
    }

    // 遍历行
    private function dbQueryTraverse(string $command, string $bindType, array $bindParam, string $failed): void
    {
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            foreach ($bindParam as $value) {
                if (!empty($value)) {
                    $stmt->bind_param($bindType, $value);
                    $stmt->execute();
                }
            }
        } else {
            throw new Exception($failed);
        }
    }

    // 遍历更值
    private function dbQueryTraverseUpdate(string $command, string $bindType, array $bindParam, array $bindValue, string $failed): void
    {
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            foreach ($bindParam as $key => $value) {
                if (!empty($value)) {
                    $stmt->bind_param($bindType, $value, $bindValue[$key]);
                    $stmt->execute();
                }
            }
        } else {
            throw new Exception($failed);
        }
    }


    // 遍历插值
    private function dbQueryTraverseInsert(string $command, string $bindType, array $bindParam, string $bindValue, string $failed): void
    {
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            foreach ($bindParam as $value) {
                if (!empty($value)) {
                    $stmt->bind_param($bindType, $value, $bindValue);
                    $stmt->execute();
                }
            }
        } else {
            throw new Exception($failed);
        }
    }

    // 查询结果
    private function dbQueryResult(string $command, string $bindType, array $bindParam, string $failed): array
    {
        $stmt = $this->conn->prepare("{$command}");
        if ($stmt) {
            $stmt->bind_param($bindType, ...$bindParam);
            $stmt->execute();
            $result = $stmt->get_result(); // 获取结果

            if ($result->num_rows > 0) {
                // 返回内容
                $row = $result->fetch_assoc();
                $result->free_result(); // 释放内存
                return $row;
            } else {
                return [];
            }
        } else {
            throw new Exception($failed);
        }
    }

    // 登录
    public function dbLogin(string $name, string $pass): int
    {
        // 查询 用户
        $command = 'SELECT user, pass, session_id, is_active FROM users WHERE user = ?';
        $row = $this->dbQueryResult($command, 's', [$name], 'Login failed');
        if ($row) {
            if (password_verify($pass, $row['pass'])) { // 验证密码
                // 登录成功
                
                // 唯一登录
                $new_sid = session_id();
                $sid = $row['session_id'];
                if ($sid !== $new_sid) {
                    $cookie = htmlspecialchars($new_sid, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $update_command = 'UPDATE users SET session_id = ? WHERE user = ?';
                    $this->dbUpdate($update_command, 'ss', [$cookie, $name], 'Update session failed');
                }
                return $row['is_active'] === 1 ? 2 : 3; // 用户激活或未激活
            } else {
                echo "<script>alert('Logon failed');</script>";
            // 密码错误
            }
        } else {
            echo "<script>alert('Logon failed');</script>";
            // 用户不存在
        }
        $ip = (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) ?: 'invalid Ip'; // 获取当前ip地址
        throw new Exception('Login failed: IP: '. $ip . ' user: ' . $name);
        return 1; // 默认返回
    }

    // cookie 验证
    public function dbCookie(): string|false
    {
        if (!isset($_COOKIE['UID'])) {
            check();
        }
        $cookie = htmlspecialchars($_COOKIE['UID'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        // 查询 cookie.
        $command = 'SELECT user, pass, session_id, is_active FROM users WHERE session_id = ?';
        $row = $this->dbQueryResult($command, 's', [$cookie], 'Cookie verification failed');
        if ($row) {
            return $row['session_id'];
        } // else { // 唯一 id 检测
        //   throw new Exception('Cookie verification failed');
        // }
        // 如果查询结果为空，返回 false
        return false;
    }

    // home_config 配置更新
    public function dbUpdateHomeConfig(string $site_name, string $site_icon, string $home_theme, string $home_icon, array $label_id, array $home_label, bool $home_search, bool $home_login, string $home_content): void
    {
        try {
            // 验证 ID 有效性
            $data = $this->dbQueryHomeLabelAll();
            $existing_ids = array_column($data, 'id');
            if (!is_array($label_id) || array_diff(array_filter($label_id), $existing_ids)) {
                echo "<script>alert('Label ID is invalid');</script>";
                throw new Exception('ID does not exist');
            }

            $label_id_string = implode(", ",$label_id);
            $home_label_string = implode(", ",$home_label);

            $command = "UPDATE {$this->home_config} SET site_name = ? , site_icon = ? , home_theme = ? , home_icon = ? , label_id = ? , home_label = ? , home_search = ? , home_login = ? , home_content = ? ";
            $this->dbUpdate($command, 'ssssssiis', [$site_name, $site_icon, $home_theme, $home_icon, $label_id_string, $home_label_string, $home_search, $home_login, $home_content], 'Update home_config failed');
        } catch (Exception $e) {
            throw new Exception('home_config update failed: ' . $e->getMessage());
        }
    }

    // home_config 配置查询
    public function dbQueryHomeConfig(): array
    {
        try {
            // 开始事务
            $this->beginTransaction();
            $command = "SELECT * FROM {$this->home_config}";
            $data = $this->dbQuerySingleLine($command, 'Query home_config failed');
            if (empty($data)) {
                // home_config 创建默认配置
                $default_command = "INSERT INTO {$this->home_config} (site_name, site_icon, home_theme, home_icon, label_id, home_label, home_search, home_login, home_content) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $default_config = [
                    'Akylor',
                    '../Akylor.ico',
                    'default',
                    '../Akylor.ico',
                    '1, 2, 3',
                    'About, Dropdown, Link',
                    false,
                    false,
                    '&amp;lt;h1&amp;gt;Akylor&amp;lt;/h1&amp;gt;'
                ];
                $this->dbUpdate($default_command, 'ssssssiis', $default_config, 'Insert home_config default config failed');
                // home_labels 创建默认配置
                $default_labels_command = "INSERT INTO {$this->home_labels} (label_name, label_type, label_content) VALUES (?, ?, ?)";
                $default_labels_config = [
                    ['About', 'button', '&amp;lt;img src=&amp;#039;https://avatars.githubusercontent.com/u/30207375?v=4&amp;amp;size=64&amp;#039; alt=&amp;#039;icon&amp;#039; width=&amp;#039;80px&amp;#039; height=&amp;#039;80px&amp;#039;&amp;gt;
&amp;lt;h1&amp;gt;DUQIA&amp;lt;/h1&amp;gt;
&amp;lt;p&amp;gt;Akylor developer&amp;lt;/p&amp;gt;
&amp;lt;span&amp;gt;
&amp;lt;a class=&amp;#039;tooltip&amp;#039; href=&amp;#039;https://github.com/DUQIA&amp;#039; target=&amp;#039;_blank&amp;#039; rel=&amp;#039;nofollow noopener noreferrer&amp;#039;&amp;gt;&amp;lt;img src=&amp;#039;/content/themes/default/svg/github.svg&amp;#039; alt=&amp;#039;github&amp;#039; width=&amp;#039;25px&amp;#039; height=&amp;#039;25px&amp;#039;&amp;gt;
&amp;lt;span class=&amp;#039;tooltip-text&amp;#039;&amp;gt;GitHub&amp;lt;/span&amp;gt;
&amp;lt;/a&amp;gt;
&amp;lt;/span&amp;gt;'],
                    ['Dropdown', 'dropdown', '&amp;lt;a href=&amp;#039;https://github.com/DUQIA/Akylor&amp;#039; target=&amp;#039;_blank&amp;#039; rel=&amp;#039;nofollow noopener noreferrer&amp;#039;&amp;gt;Akylor&amp;lt;/a&amp;gt;'],
                    ['Link', 'link', '&amp;lt;a href=&amp;#039;https://blog.akylor.us.kg&amp;#039; target=&amp;#039;_blank&amp;#039; rel=&amp;#039;nofollow noopener noreferrer&amp;#039;&amp;gt;Blog&amp;lt;/a&amp;gt;']
                ];
                $this->dbDeleteHomeLabelAll();
                foreach ($default_labels_config as $label) {
                    $this->dbUpdate($default_labels_command, 'sss', $label, 'Insert home_labels default config failed');
                }
                return $this->dbQueryHomeConfig();
            }
        // 提交事务
        $this->commit();
        } catch (Exception $home_config_query) {
            // 回滚事务
            $this->rollBack();
            throw new Exception('home_config query failure: ' . $home_config_query->getMessage());
        }
        return $data;
    }

    // home_config 查询所有
    public function dbQueryHomeConfigAll(): array
    {
        $command = "SELECT * FROM {$this->home_config}";
        $data = $this->dbQuerySingleLine($command, 'Query home_config failed');
        return isset($data) ? $data : [];
    }

    // home_labels 查询所有
    public function dbQueryHomeLabelAll(): array
    {
        $command = "SELECT * FROM {$this->home_labels}";
        $data = $this->dbQueryAllLine($command, 'Query home_labels failed');
        return $data;
    }

    // home_labels 清空
    public function dbDeleteHomeLabelAll(): array
    {
        $command = "DELETE FROM {$this->home_labels}";
        $this->dbClear($command, 'Clear home_labels failed');
        return [];
    }

    // home_labels 删除
    protected function dbDeleteHomeLabel(array $home_label): void
    {
        $command = "DELETE FROM {$this->home_labels} WHERE id = ?";
        $this->dbQueryTraverse($command, 's', $home_label, 'Delete home_labels failed');
    }

    // home_labels 创建
    protected function dbCreateHomeLabel(array $home_label): void
    {
        $command = "INSERT INTO {$this->home_labels} (label_name, label_type) VALUES (?, ?)";
        $this->dbQueryTraverseInsert($command, 'ss', $home_label, 'dropdown', 'Create home_labels failed');
    }

    // home_labels 更新id
    protected function dbUpdateHomeLabelId(array $home_label, array $label_id): void
    {
        $command = "UPDATE {$this->home_labels} SET label_name = ? WHERE id = ?";
        $this->dbQueryTraverseUpdate($command, 'ss', $home_label, $label_id, 'Update home_labels failed');
    }

    // home_labels 更新数据
    public function dbUpdateHomeLabelData(string $label_name, string $selected_option, string $label_content): void
    {
        $command = "UPDATE {$this->home_labels} SET label_type = ? , label_content = ? WHERE label_name = ?";
        $this->dbUpdate($command, 'sss', [$selected_option, $label_content, $label_name], 'Update home_labels failed');
    }

    // home_labels 查询
    public function dbQueryHomeLabel(array $label_id, array $home_label): array
    {
        try {
            // 开始事务
            $this->beginTransaction();

            $data = $this->dbQueryHomeLabelAll();

            // 首次创建数据
            if (empty($data)) {
                $this->dbCreateHomeLabel($home_label);
                return $this->dbQueryHomeLabel($label_id, $home_label);
            } else {
                $existing_ids = array_column($data, 'id');
                $existing_labels = array_column($data, 'label_name');

                // 验证 ID 有效性
                if (!is_array($label_id) || array_diff($label_id, $existing_ids)) {
                    // 回滚事务
                    $this->rollBack();
                    echo "<script>alert('ID does not exist');</script>";
                    return $data;
                }
                
                $delete_ids = array_diff($existing_ids, $label_id); // 删除id
                $Remaining_labels = array_diff_assoc($home_label, $existing_labels); // 剩余标签
                $update_ids = array_intersect_key($label_id, $Remaining_labels); // 通过 id 更新标签
                $update_laebels = array_intersect_key($Remaining_labels, $label_id);
                $create_labels = array_diff_key($Remaining_labels, $label_id); // 创建标签

                if (!empty($delete_ids)) { // 删除
                    $this->dbDeleteHomeLabel($delete_ids);
                }
                if (!empty($update_ids) && !empty($update_laebels)) { // 更新
                    $this->dbUpdateHomeLabelId($update_laebels, $update_ids);
                }
                if (!empty($create_labels)) { // 创建
                    $this->dbCreateHomeLabel($create_labels);
                }

                if (!empty($delete_ids) || !empty($update_ids) && !empty($update_laebels) || $create_labels) {
                    // 重新查询最新的 home_labels 数据
                    $update_data = $this->dbQueryHomeLabelAll();
                    $update_ids = array_column($update_data, 'id');
                    // 更新 home_config 的id
                    $update_label_id = "UPDATE {$this->home_config} SET label_id = ?";
                    $string = implode(', ', array_values($update_ids));
                    $this->dbupdate($update_label_id, 's', [$string], 'Update home_config failed');
                    return $this->dbQueryHomeLabel($update_ids, $home_label);
                }
            }
            // 提交事务
            $this->commit();
        } catch (Exception $home_labels_query) {
            // 回滚事务
            $this->rollBack();
            throw new Exception('home_labels query failure: ' . $home_labels_query->getMessage());
        }
        return $data;
    }

    // IP 统计
    public function dbIpCount(): array
    {
        $command = "SELECT country, COUNT(*) as count FROM ip_log GROUP BY country";
        return $this->dbQueryCount('country', 'count', $command, 'IP count failed');
    }

    // IP 访问记录
    public function dbIp(): void
    {
        $ip = (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) ?: 'invalid Ip'; // 获取当前ip地址
        $response = new StatusCode();
        $country = $response->getIp($ip); // 获取当前ip地址的详细信息

        $timestamp = filter_var($_SERVER['REQUEST_TIME'], FILTER_VALIDATE_INT); // 时间戳
        $method = htmlspecialchars($_SERVER['REQUEST_METHOD'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 请求方法
        $protocol = htmlspecialchars($_SERVER['SERVER_PROTOCOL'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 协议
        $response_code = http_response_code(); // 响应码
        $file = htmlspecialchars($_SERVER['SCRIPT_NAME'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 访问文件
        $proxy = htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // 用户代理

        $command = "INSERT INTO ip_log (ip, country, timestamp, request_method, protocol, request_code, accessed_file, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $this->dbUpdate($command, 'sssssiss', [$ip, $country, $timestamp, $method, $protocol, $response_code, $file, $proxy], 'Ip Insertion failure');
    }

    // 对象销毁时关闭连接
    public function __destruct()
    {
        // 关闭连接
        if ($this->conn) {
            $this->conn->close();
        }
    }
}