<?php

// 组件 标签框
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

// javascript 代码
function label_js(array $home_label, string $translations): void
{
    // 遍历数组并转义每个元素
    $home_label = array_map(function($id) {
        return array_map(function($label) {
            return !empty($label) ? htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : '';
        }, $id);
    }, $home_label);

    $translation = htmlspecialchars($translations, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    // 转换为 JSON 处理特殊字符
    $home_labels_json = json_encode($home_label, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    
    echo "const homeContainer = document.querySelector('.home-container');
    let PHPLabels = $home_labels_json;
    let activeCard = null; // 保存当前激活的卡片
    let nextId = 0; // 用于生成唯一的ID

    // 过滤掉空字符串
    // PHPLabels = PHPLabels.filter(label => label.trim() !== '');

    // 初始化卡片
    labels = JSON.parse(JSON.stringify(PHPLabels));
    if (labels.length > 0) {
        labels.forEach(label => {
            createCard(label['id'], label['label_name']);
        });
        createCard('');
    } else {
        createCard('');
    }

    // 创建卡片
    function createCard(ids, label) {
        let cards = Array.from(document.querySelectorAll('.card')); // 获取所有卡片

        // 移除所有待输入的卡片
        cards.filter(card => {
            const imgElement = card.querySelector('.card-text span img');
            return imgElement && imgElement.alt === '+';
        }).forEach(card => card.remove());

        // 在0-9个卡片内
        if (cards.length >= 0 && cards.length <= 9) {
            // 创建卡片
            const card = document.createElement('div');
            card.className = 'card';
            // 创建显示文本
            const cardShowText = document.createElement('div');
            cardShowText.className = 'card-text reveal';
            // 创建span
            const cardTextSpan = document.createElement('span');
            cardTextSpan.textContent = label;
            let cardImgAdd;
            if (!label) {
                cardImgAdd = document.createElement('img');
                cardImgAdd.src = '/admin/svg/plus.svg';
                cardImgAdd.alt = '+';
                cardTextSpan.appendChild(cardImgAdd);
            }
            // 创建隐藏
            const cardHideText = document.createElement('div');
            cardHideText.className = 'card-text';
            // 创建id
            const cardId = document.createElement('input');
            cardId.type = 'hidden';
            cardId.name = 'label-id[]';
            cardId.className = 'label-id';
            cardId.id = 'label-id-' + nextId;
            if (ids) { // 将值传递给输入框
                cardId.value = ids;
            }
            // 创建输入框
            const cardInput = document.createElement('input');
            cardInput.type = 'text';
            cardInput.name = 'home-list[]';
            cardInput.className = 'home-list';
            cardInput.id = 'home-list-' + nextId;
            cardInput.maxLength = 20;
            cardInput.size = 20;
            cardInput.placeholder = '$translation';
            if (label) { // 将值传递给输入框
                cardInput.value = label;
            }
            // 创建删除按钮
            const cardRemove = document.createElement('div');
            cardRemove.className = 'card-remove';
            const cardImgRemove = document.createElement('img');
            cardImgRemove.className = 'remove';
            cardImgRemove.src = '/admin/svg/close-small.svg';
            cardImgRemove.alt = 'x';

            cardShowText.appendChild(cardTextSpan);
            cardHideText.appendChild(cardId);
            cardHideText.appendChild(cardInput);
            cardRemove.appendChild(cardImgRemove);
            card.appendChild(cardShowText);
            card.appendChild(cardHideText);
            card.appendChild(cardRemove);
            homeContainer.appendChild(card);

            // 绑定事件
            bindCardEvents(card);
            
            // 更新下一个ID
            nextId++;
        }
    }

    // 点击卡片
    function bindCardEvents(card) {
        displayRemove(card);
        card.addEventListener('click', function(event) {
            // 如果激活的卡片不是当前点击的卡片，则关闭当前激活的卡片
            if (activeCard && activeCard !== card) {
                const activeTexts = activeCard.querySelectorAll('.card-text');
                toggleDisplay(activeTexts);
            }

            // 激活的卡片是当前点击的卡片，切换显示
            activeCard = card;
            const inputField = card.querySelector('.home-list');
            // 切换当前卡片的文本
            const currentCardText = card.querySelectorAll('.card-text');
            // 在输入框内
            if (event.target.classList.contains('home-list')) {
                if (!inputField.dataset.listenerAdded) {
                    inputField.addEventListener('keydown', function(clickEvent) {
                        handleEnterKey(clickEvent, currentCardText, card);
                    });
                    inputField.dataset.listenerAdded = true; // 防止重复添加监听器
                }
                return;
            }
            toggleDisplay(currentCardText);
        });
        
        // 绑定删除按钮事件
        const cardRemove = card.querySelector('.remove');
        cardRemove.addEventListener('click', function(clickEvent) {
            clickEvent.stopPropagation(); // 阻止默认行为
            card.remove();
            createCard();
        });
    }

    // 回车
    function handleEnterKey(event, currentCardText, item) {
        // 按下回车
        if (event.key === 'Enter') {
            event.preventDefault(); // 阻止默认行为
            // 不为空的时候才显示
            if (event.target.value !== '') {
                item.querySelector('.card-text span').textContent = event.target.value;
                displayRemove(item);
                createCard();
            }
            toggleDisplay(currentCardText);
        }
        activeCard = null;
    }

    // 切换显示
    function toggleDisplay(currentCardText) {
        currentCardText.forEach(text => {
            text.classList.toggle('reveal'); // 切换显示
        });
    }

    // 显示删除按钮
    function displayRemove(item) {
        const cardTextSpan = item.querySelector('.card-text span').textContent;
        const cardRemove = item.querySelector('.card-remove');
        // 确保不为空 且不是 +
        if (cardTextSpan !== '' && cardTextSpan !== ' ' && cardTextSpan !== '+') {
            // 文本存在，才显示删除按钮
            if (cardRemove) {
                cardRemove.style.display = 'flex';
            }
        }
        activeCard = null;
    }

    // 点击其它关闭卡片
    window.addEventListener('click', function(windowEvent) {
        // 检查点击是否发生在.card或其子元素上
        if (activeCard && !activeCard.contains(windowEvent.target)) {
            const activeTexts = activeCard.querySelectorAll('.card-text');
            // 确保只在输入框显示才触发
            if (activeTexts[1].className === 'card-text reveal') {
                toggleDisplay(activeTexts);
            }
            activeCard = null;
        }
    });";
}
