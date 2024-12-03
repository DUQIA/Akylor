<?php

// 模板
$id_file = dirname(dirname(__DIR__)) . '/session_id.php';

if (file_exists($id_file)) {
    require_once $id_file;
} else {
    die('File does not exist');
}

// 检查会话
if (!isset($_COOKIE['UID']) || !is_string($_COOKIE['UID']) || $_COOKIE['UID'] !== session_id()) {
    check();
}

// 通用函数
function render_labels(array $labels, callable $callback): void
{
    // 确保返回有效的 HTML 内容
    if (is_array($labels) && !empty($labels)) {
        foreach ($labels as $key => $label) {
            echo empty($label) ? '' : $callback($key, htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        }
    } else {
        echo '';
    }
}

// 标签按钮
function home_label(array $labels): void
{
    render_labels($labels, function ($key, $label) {
        return "<button type='button' class='content-item' id='label-$key'>$label</button>";
    });
}

// 标签面板
function home_label_panel(string $csrf_token, array $labels, array $home_label, string $translationType, string $translationDropdwon, string $translationLink, string $translationButton, string $translationContent, string $translationCode, string $translationSubmit): void
{
    render_labels($labels, function ($key, $label) use ($csrf_token, $home_label, $translationType, $translationDropdwon, $translationLink, $translationButton, $translationContent, $translationCode, $translationSubmit) {
        $csrf_token = htmlspecialchars($csrf_token, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $self = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $option = $key + 1;
        // 获取当前选中的标签
        $type = htmlspecialchars($home_label[$key]['label_type'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $contentCode = isset($home_label[$key]['label_content']) ? html_entity_decode($home_label[$key]['label_content'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : '';
        $content = html_entity_decode($contentCode, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        // 翻译
        $translatedType = htmlspecialchars($translationType, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $translatedDropdwon = htmlspecialchars($translationDropdwon, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $translatedLink = htmlspecialchars($translationLink, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $translatedButton = htmlspecialchars($translationButton, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $translatedContent = htmlspecialchars($translationContent, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $translatedCode = htmlspecialchars($translationCode, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $translatedSubmit = htmlspecialchars($translationSubmit, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        // 获取 type 对应的翻译
        switch ($type) {
            case 'dropdown':
                $translatedTypeValue = $translatedDropdwon;
                break;
            case 'link':
                $translatedTypeValue = $translatedLink;
                break;
            case 'button':
                $translatedTypeValue = $translatedButton;
                break;
            default:
                $translatedTypeValue = $translatedDropdwon;
                break;
        }

        return "
            <div id='label-$key' class='item'>
                <div class='page'>
                    <div class='content'>
                        <form method='post' action='$self'>
                            <input type='hidden' name='csrf_token' value='$csrf_token'> <!-- CSRF token -->
                            <div class='appose'>
                                <input type='hidden' name='label-name' id='label-name-$option' placeholder='$label' value='$label'>
                            </div>
                            <div class='arrange'>
                                <div class='appose'>
                                    <div class='home-label'>$translatedType</div>
                                    <div class='custom-select' data-option-id='selected-option-$option'>
                                        <div class='select-selected' id='select-selected-$option'>
                                            <span class='select-content'>$translatedTypeValue</span>
                                            <img class='arrow' src='../../svg/down.svg' alt='Drop-down'>
                                        </div>
                                        <div class='select-items'>
                                            <div class='items-list'>
                                                <div class='data' data-value='dropdown'>$translatedDropdwon</div>
                                                <div class='data' data-value='link'>$translatedLink</div>
                                                <div class='data' data-value='button'>$translatedButton</div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type='hidden' name='selected-option' id='selected-option-$option'>
                                </div>
                            </div>
                            <div class='arrange'>
                                <div class='appose'>
                                    <label for='label-content-$option'>$translatedContent</label>
                                    <textarea name='label-content' id='label-content-$option' placeholder='$translatedCode'>$content</textarea>
                                </div>
                            </div>
                            <!-- 提交按钮 -->
                            <input type='submit' value='$translatedSubmit'>
                        </form>
                    </div>
                </div>
            </div>";
    });
}
