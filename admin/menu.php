<?php

$id_file = dirname(__DIR__) . '/session_id.php';

(file_exists($id_file)) ? require_once $id_file : die('File does not exist');

// 检查会话
if (!isset($_COOKIE['UID']) || !is_string($_COOKIE['UID']) || $_COOKIE['UID'] !== session_id()) {
    check();
}

// 访问文件
$file = htmlspecialchars($_SERVER['SCRIPT_NAME'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

switch ($file) {
    case '/admin/home/index.php':
        $home = 'selected';
        break;
    default:
        $admin = 'selected';
        break;
}
?>
<div class="menu">
    <table>
        <thead>
            <tr>
                <th>
                    <img src="/Akylor.ico" alt="icon">
                    <span>Akylor</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <a href="<?php echo wait('/admin'); ?>" class="menu-item <?php echo htmlspecialchars((isset($admin)) ? $admin : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
                        <img src="/admin/svg/dashboard-one.svg" alt="dashboard">
                        <span><?php echo htmlspecialchars($translations['dashboard'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></span>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="/admin/home" class="menu-item <?php echo htmlspecialchars((isset($home)) ? $home : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
                        <img src="/admin/svg/home-two.svg" alt="home">
                        <span><?php echo htmlspecialchars($translations['home'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></span>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="/admin/logout.php" class="menu-item" id="logout">
                        <img src="/admin/svg/power.svg" alt="exit">
                        <span><?php echo htmlspecialchars($translations['logout'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></span>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
