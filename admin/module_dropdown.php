<?php

// 组件 下拉框
$id_file = dirname(__DIR__) . '/session_id.php';

if (file_exists($id_file)) {
    require_once $id_file;
} else {
    die('File does not exist');
}

// 检查会话
if (!isset($_COOKIE['UID']) || !is_string($_COOKIE['UID']) || $_COOKIE['UID'] !== session_id()) {
    check();
}

// 获取主题
function get_theme(): void
{
    $folder_dir = dirname(__DIR__) . '/content/themes/';
    (file_exists($folder_dir)) ?: die('File does not exist');
    $directories = array_filter(glob($folder_dir . '*'), 'is_dir');
    
    foreach ($directories as $directory) {
        $folder = htmlspecialchars(basename($directory), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        echo "<div class='data' data-value='$folder'>$folder</div>";
    }
}

// html 代码
function dropdown_html(string $translations, string $home_configs): void
{
    $translation = htmlspecialchars($translations, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $home_config = htmlspecialchars($home_configs, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    echo "
        <div class='home-label'>$translation</div>
        <div class='custom-select' data-option-id='selected-option-0'>
            <div class='select-selected' id='select-selected'>
                <span class='select-content'>$home_config</span>
                <img class='arrow' src='../../svg/down.svg' alt='Drop-down'>
            </div>
            <div class='select-items'>
                <div class='items-list'>";
                    get_theme();
    echo "      </div>
            </div>
        </div>
        <input type='hidden' name='selected-option-0' id='selected-option-0'>
    ";
}

// javascript 代码
function dropdown_js(): void
{
    echo "
    const select = document.querySelectorAll('.custom-select');
    // 隐藏下拉框
    function hiddenClose(arrow, selectItems) {
        arrow.removeAttribute('style');
        selectItems.removeAttribute('style');
    }

    // 鼠标移动到元素上
    function hoverStyle(selectItems, selectItemsData) {
        selectItems.addEventListener('mouseover', function(event) {
            if (event.target.classList.contains('data')) {
                selectItemsData.forEach(data => data.classList.remove('select-color'));
                event.target.classList.add('select-color');
            }
        });
    }

    // 选择项
    function sel(item, optionId, selectItemsSpan, selectItemsData) {
        // 更新显示文本
        selectItemsSpan.textContent = item.textContent; 
        // 设置隐藏字段的值
        document.getElementById(optionId).value = item.getAttribute('data-value');
        // 选择项的背景颜色
        selectItemsData.forEach(function(data) {
            data.classList.remove('value', 'select-color');
        });
        item.classList.add('value', 'select-color');
    }

    // 下拉框
    function dropdownNav(item, arrow, selectItems, selectItemsSpan, selectItemsData) {
        selectItemsData.forEach(function(itemsData) {
            // 点击选项
            itemsData.addEventListener('click', function() {
                sel(itemsData, item.dataset.optionId, selectItemsSpan, selectItemsData);
                hiddenClose(arrow, selectItems);
            });
        });
    }

    // 默认值
    function defaultValue(item, selectItemsSpan, selectItemsData) {
        selectItemsData.forEach(function(itemsData) {
            let value = '';
            // 根据 Option 值来设置默认选项
            if (itemsData.textContent === selectItemsSpan.textContent) {
                value = itemsData.getAttribute('data-value');
                sel(itemsData, item.dataset.optionId, selectItemsSpan, selectItemsData);
            }
        });
    }

    // 点击窗口外
    function windowEvent(item, arrow, selectItems) {
        window.addEventListener('click', function(event) {
            if (!item.contains(event.target)) {
                hiddenClose(arrow, selectItems);
                item.dataset.open = 'false';
            }
        });
    }

    // 点击下拉框
    select.forEach(function(item) {
        const arrow = item.querySelector('.arrow');
        const selectItems = item.querySelector('.select-items');
        const selectItemsSpan = item.querySelector('.select-selected span');
        const selectItemsData = item.querySelectorAll('.select-items .data');
        defaultValue(item, selectItemsSpan, selectItemsData);
        item.addEventListener('click', function() {
            windowEvent(item, arrow, selectItems);
            dropdownNav(item, arrow, selectItems, selectItemsSpan, selectItemsData);
            hoverStyle(selectItems, selectItemsData);

            // 检查当前下拉框是否已经展开
            if (item.dataset.open === 'true') {
                hiddenClose(arrow, selectItems);
                item.dataset.open = 'false';
            } else {
                // 关闭其他已展开的下拉框
                select.forEach(otherItem => {
                    if (otherItem !== item && otherItem.dataset.open === 'true') {
                        hiddenClose(otherItem.querySelector('.arrow'), otherItem.querySelector('.select-items'));
                        otherItem.dataset.open = 'false';
                    }
                });
                // 默认展开当前下拉框
                arrow.style.transform = 'scaleY(-1)';
                selectItems.style.display = 'block';
                item.dataset.open = 'true';
            }
        });
    });

    // 获取父级元素
    function getAppose(selectContentElement) {
        // 从 select-content 元素开始向上查找，直到找到 class 为 appose 的元素
        let currentElement = selectContentElement;
        let elements = [];
        // 查找 .appose 元素
        while (currentElement && !currentElement.classList.contains('appose')) {
            currentElement = currentElement.parentElement;
        }

        if (currentElement) {
            elements.push(currentElement);
            // 查找 .home-label 元素
            currentElement = currentElement.querySelector('.home-label');
            if (currentElement) {
                elements.push(currentElement);
            }
            // 查找 .arrow 元素
            currentElement = currentElement.parentElement.querySelector('.arrow');
            if (currentElement) {
                elements.push(currentElement);
            }
        }
        return elements;
    }

    // 边框宽度
    function borderWidth() {
        const selectContentElements = document.querySelectorAll('.select-content');
        
        // 获取设备宽度
        const windowsWidth = window.innerWidth; // 窗口宽度
        const devicewidth = window.outerWidth; // 设备宽度
        const widthRatio = devicewidth / windowsWidth;

        // 计算一个动态的比例阈值
        const dynamicThreshold = 6.5 * (devicewidth / 1920);

        // 遍历每个 .select-content 元素
        selectContentElements.forEach((selectContent, index) => {
            const elements = getAppose(selectContent);
            if (elements) {
                // 获取文本宽度  元素宽度 - 标签宽度 - 箭头宽度 - 剩余间距 - 内边距 homeLabel.clientWidth - arrow.clientWidth
                const valueWidth = elements[0].clientWidth - elements[1].clientWidth - elements[2].clientWidth - 22 - 20;
                selectContent.style.maxWidth = (widthRatio > dynamicThreshold) ? 0 : valueWidth.toFixed(1) + 'px';
            }
        });
    }
    borderWidth(); // 初始执行
    
    // 面板切换
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('content-item')) {
            borderWidth();
        }
    });

    // 调整窗口大小
    window.addEventListener('resize', function() {
        borderWidth();
    });";
}
