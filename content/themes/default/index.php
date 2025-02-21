<?php

$db_file = dirname(dirname(dirname(__DIR__))) . '/db_dispose.php';
$log_file = dirname(dirname(dirname(__DIR__))) . '/log.php';
$id_file = dirname(dirname(dirname(__DIR__))) . '/session_id.php';

if (file_exists($db_file) && file_exists($log_file) && file_exists($id_file)) {
    require_once $db_file;
    require_once $log_file;
    require_once $id_file;
} else {
    die('File does not exist');
}

// 访问包含主题名，重定向到首页
if (strpos($_SERVER['REQUEST_URI'], '/content/themes/default')) {
    redirect('');
}

// 获取主题文件
function get_theme_file(string $folder): void
{
    $folder_dir = dirname(dirname(dirname(__DIR__))) . "/content/themes/$folder/";
    (file_exists($folder_dir)) ?: die('File does not exist');
    $directories = glob($folder_dir . '/*.{css,js}', GLOB_BRACE); // 只获取 css js 文件

    // 判断后缀名
    foreach ($directories as $directory) {
        $file = basename($directory); // 获取文件名
        $ext = pathinfo($directory, PATHINFO_EXTENSION); // 获取文件后缀名
        if ($ext === 'css') {
            echo "<link rel='stylesheet' type='text/css' href='./content/themes/$folder/$file'>";
        } elseif ($ext === 'js') {
            echo "<script src='./content/themes/$folder/$file'></script>";
        }
    }
}

// 判断语言
function get_language(): string
{
    $language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : 'en';
    $language = preg_replace('/[^a-zA-Z]/', '', $language); // 只允许字母
    return ($language === 'zh') ? 'zh' : 'en'; // 只允许字母
}

// 根据语言翻译
function translate(string $content): string
{
    // 必须匹配 [|] 三个符号
    if (preg_match('/\[([^\]]*\|[^\]]*)\]/', $content)) {
        $result = preg_replace_callback('/\[([^\]]*)\]/', function($matches) {
            $translates = explode("|", $matches[1]);
        
            // 获取用户的首选语言并进行验证
            return (get_language() === 'zh') ? $translates[1] : $translates[0];
        }, $content);
        return $result;
    } else {
        return $content;
    }
    
}

// 标签样式
function label_style(array $labels): void
{
    foreach ($labels as $label) {
        $name = translate(htmlspecialchars($label['label_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        $code = isset($label['label_content']) ? html_entity_decode($label['label_content'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : '';
        $html = translate(html_entity_decode($code, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

        switch ($label['label_type']) {
            case 'dropdown':
                echo "<div class='dropdown'>
                    <button type='button' class='dropbtn'>
                        <d>$name</d>
                        <img class='arrow' src='../svg/down.svg' alt='Drop-down'>
                    </button>
                    <div class='dropdown-content'>
                        <div class='content-list'>
                            $html
                        </div>
                    </div>
                </div>";
                break;
            case 'button':
                echo "<div class='button'>
                    <button type='button'>$name</button>
                    <div class='button-content'>
                        <div class='content-border'>
                            $html
                        </div>
                    </div>
                </div>";
                break;
            case 'link':
                echo $html;
                break;
            default:
                echo $html;
                break;
        }
    }
}

try {
    $db_class = new Db();
    $home_configs = $db_class->dbQueryHomeConfigAll(); // 获取站点配置
    $home_lable = $db_class->dbQueryHomeLabelAll(); // 获取标签
} catch (Exception $e) {
    log_error($e->getMessage(), 'default'); // 记录更安全的错误信息
}
?>
<!DOCTYPE html>
<html lang='<?php echo htmlspecialchars(get_language(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?php echo htmlspecialchars($home_configs['site_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></title>
    <link rel='icon' type='image/ico' href='<?php echo htmlspecialchars($home_configs['site_icon'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>'>
    <?php get_theme_file($home_configs['home_theme']); ?>
</head>
    <body>
        <div class='star'></div>
        <div class='background-top'></div>
        <!-- 导航栏 -->
        <div class='navigation'>
            <div class='masking'></div>
            <!-- 导航栏开关 -->
            <input id='checkbox' type='checkbox'>
            <label class='toggle' for='checkbox'>
                <div class='bar1 bars'></div>
                <div class='bar2 bars'></div>
                <div class='bar3 bars'></div>
            </label>
            <!-- 导航栏图标 -->
            <a class='logo' href='<?php echo wait(''); ?>'><img src='<?php echo htmlspecialchars($home_configs['home_icon'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>' alt='logo'></a>
            <!-- 标签 -->
            <span class='labels'><?php label_style($home_lable); ?></span>
            <!-- 搜索 登录 -->
            <span class='start'></span>
        </div>
        <div class='main'>
            <?php
                $content_code = html_entity_decode($home_configs['home_content'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                echo html_entity_decode($content_code, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            ?>
        </div>
    </body>
</html>