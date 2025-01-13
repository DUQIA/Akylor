<?php

$file = dirname(__DIR__) . '/language.php'; // 使用绝对路径
$db_file = dirname(dirname(__DIR__)) . '/db_dispose.php';
$log_file = dirname(dirname(__DIR__)) . '/log.php';
$id_file = dirname(dirname(__DIR__)) . '/session_id.php';

if (file_exists($file) && file_exists($db_file) && file_exists($log_file) && file_exists($id_file)) {
    require $file; // 仅在文件存在的情况下包含
    require $db_file;
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
    // 配置查询
    $db_class = new Db();
    $home_configs = $db_class->dbQueryHomeConfig();

    // 设置主页标签
    $label_ids = isset($home_configs['label_id']) ? explode(", ", $home_configs['label_id']) : [];
    $home_labels = isset($home_configs['home_label']) ? explode(", ", $home_configs['home_label']) : [];
    $filter_ids = array_filter($label_ids);
    $filter_labels = array_filter($home_labels);
    $home_label = (is_array($home_labels) && !empty($filter_labels)) ? $db_class->dbQueryHomeLabel($filter_ids, $filter_labels) : $db_class->dbDeleteHomeLabelAll();

    // CSRF 保护
    $csrf_token = !isset($_SESSION['csrf_token']) ? bin2hex(random_bytes(32)) : $_SESSION['csrf_token']; // 生成 CSRF token
    $_SESSION['csrf_token'] = $csrf_token; // 存储 token

    // 设置主页选项
    function home_option(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            !isset($_POST['csrf_token']) || hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']) ?: die('CSRF token validation failed');
            $post = $_POST ?? null;
            if ($post !== null) {
                $post_count = count($post); // 获取数组长度

                if ($post_count > 4) { // 主页表单
                    $site_name = htmlspecialchars(filter_input(INPUT_POST, 'site-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $site_icon = htmlspecialchars(filter_input(INPUT_POST, 'site-icon', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $home_theme = htmlspecialchars(filter_input(INPUT_POST, 'selected-option-0', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $home_title = htmlspecialchars(filter_input(INPUT_POST, 'home-title', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $home_content = htmlspecialchars(filter_input(INPUT_POST, 'home-content', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    // 单选框
                    $home_search = isset($_POST['home-search']) ? htmlspecialchars(filter_input(INPUT_POST, 'home-search', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : false;
                    $home_login = isset($_POST['home-login']) ? htmlspecialchars(filter_input(INPUT_POST, 'home-login', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : false;
                    // 标签id
                    $label_id = filter_input(INPUT_POST, 'label-id', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    if ($label_id) {
                        $label_id = array_map('htmlspecialchars', $label_id);
                    }
                    // 标签数据转数组
                    $home_label_lists = filter_input(INPUT_POST, 'home-list', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    if ($home_label_lists) {
                        $home_label_lists = array_map('htmlspecialchars', $home_label_lists);
                    }

                    // 更新数据库
                    global $db_class;
                    $db_class->dbUpdateHomeConfig($site_name, $site_icon, $home_theme, $home_title, $label_id, $home_label_lists, $home_search, $home_login, $home_content);
                } else { // 标签表单
                    $label_name = htmlspecialchars(filter_input(INPUT_POST, 'label-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $selected_option = htmlspecialchars(filter_input(INPUT_POST, 'selected-option', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $label_content = htmlspecialchars(filter_input(INPUT_POST, 'label-content', FILTER_SANITIZE_FULL_SPECIAL_CHARS), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    // 更新数据库
                    global $db_class;
                    $db_class->dbUpdateHomeLabelData($label_name, $selected_option, $label_content);
                }
            }

            // 更新会话
            // redirect('/admin/home');
        }
    }
    home_option();
} catch (Exception $e) {
    log_error($e->getMessage(), 'admin-home');
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($translations['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></title>
    <link rel="icon" type="image/ico" href="../../Akylor.ico">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/home-style.css">
    <link rel='stylesheet' type='text/css' href='../../css/loading.css'>
    <script src="../js/style.js"></script>
    <script src="../../js/loading.js"></script>
</head>
    <body>
        <div class="loading"></div>
        <div class="container">
            <?php
                $menu = dirname(__DIR__). '/menu.php';
                (file_exists($menu)) ? include $menu : die('File does not exist');
            ?>
            <div class="main">
                <div class="header">
                    <span class="header-title">
                        <button type="button" class="content-item select" id="home"><?php echo htmlspecialchars($translations['home'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></button>
                        <?php
                            $home_template = __DIR__ . '/home_template.php';
                            (file_exists($home_template)) ? include $home_template : die('File does not exist');
                            home_label($home_labels);
                        ?>
                    </span>
                </div>
                <div class="layout">
                    <div id="home" class="item show">
                        <div class="page">
                            <div class="content">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
                                <input type='hidden' name='csrf_token' value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"> <!-- CSRF token -->
                                <h3><?php echo htmlspecialchars($translations['home_site'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h3>
                                <!-- 文本输入框 -->
                                <div class="arrange">
                                    <div class="appose">
                                        <label for="site-name"><?php echo htmlspecialchars($translations['home_site_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></label>
                                        <input type="text" name="site-name" id="site-name" size="20" placeholder='<?php echo htmlspecialchars($translations['home_site_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>' value='<?php echo htmlspecialchars($home_configs['site_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>' required>
                                    </div>
                                </div>
                                <div class="arrange">
                                    <div class="appose">
                                        <label for="site-icon"><?php echo htmlspecialchars($translations['home_site_icon'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></label>
                                        <input type="text" name="site-icon" id="site-icon" size="20" placeholder='<?php echo htmlspecialchars($translations['home_site_path'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>' value='<?php echo htmlspecialchars($home_configs['site_icon'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>' required>
                                    </div>
                                </div>
                                <h3><?php echo htmlspecialchars($translations['home_theme'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h3>
                                <!-- 下拉框 -->
                                <div class="arrange">
                                    <div class="appose">
                                        <?php
                                            $module_dropdown = dirname(__DIR__) . '/module_dropdown.php';
                                            (file_exists($module_dropdown)) ? include $module_dropdown : die('File does not exist');
                                            dropdown_html($translations['home_theme_folder'], $home_configs['home_theme']);
                                        ?>
                                    </div>
                                </div>
                                <h3><?php echo htmlspecialchars($translations['home_navigation'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h3>
                                <!-- 文本输入框 -->
                                <div class="arrange">
                                    <div class="appose">
                                        <label for="home-title"><?php echo htmlspecialchars($translations['home_site_icon'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></label>
                                        <input type="text" name="home-title" id="home-title" size="20" placeholder='<?php echo htmlspecialchars($translations['home_site_path'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>' value='<?php echo htmlspecialchars($home_configs['home_icon'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>' required>
                                    </div>
                                </div>
                                <div class="arrange">
                                    <div class="appose">
                                        <div class="home-label"><?php echo htmlspecialchars($translations['home_navigation_label'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></div>
                                        <div class="home-container"></div>
                                    </div>
                                </div>
                                <!-- 复选框 -->
                                <div class="arrange">
                                    <div class="appose">
                                        <label for="home-search"><?php echo htmlspecialchars($translations['home_navigation_search'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></label>
                                        <input type="checkbox" name="home-search" id="home-search" <?php echo htmlspecialchars(($home_configs['home_search']) ? 'checked' : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>>
                                    </div>
                                </div>
                                <div class="arrange">
                                    <div class="appose">
                                        <label for="home-login"><?php echo htmlspecialchars($translations['home_navigation_login'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></label>
                                        <input type="checkbox" name="home-login" id="home-login" <?php echo htmlspecialchars(($home_configs['home_login']) ? 'checked' : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>>
                                    </div>
                                </div>
                                <h3><?php echo htmlspecialchars($translations['home_navigation_login'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h3>
                                <div class="arrange">
                                    <div class="appose">
                                        <label for="home-content"><?php echo htmlspecialchars($translations['home_main_body_content'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></label>
                                        <textarea name="home-content" id="home-content" placeholder='<?php echo htmlspecialchars($translations['home_main_body_code'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>'><?php echo html_entity_decode($home_configs['home_content'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                                <!-- 提交按钮 -->
                                <input type="submit" value='<?php echo htmlspecialchars($translations['home_submit'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>'>
                            </form>
                            </div>
                        </div>
                    </div>
                    <?php empty($home_label) ?: home_label_panel($csrf_token, $home_labels, $home_label, $translations['home_label_type'], $translations['home_label_dropdown'], $translations['home_label_link'], $translations['home_label_button'], $translations['home_main_body_content'], $translations['home_main_body_code'], $translations['home_submit']); ?>
                </div>
                <div class="footer">
                    <?php
                        $footer = dirname(__DIR__) . '/footer.php';
                        (file_exists($footer)) ? include $footer : die('File does not exist');
                    ?>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                updateAnimation();
                <?php dropdown_js(); ?>;
                <?php
                    $module_label = dirname(__DIR__) . '/module_label.php';
                    (file_exists($module_label)) ? include $module_label : die('File does not exist');
                    label_js($home_label, $translations['home_navigation_label_description']);
                ?>
            });
        </script>
    </body>
</html>
