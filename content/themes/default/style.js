document.addEventListener('DOMContentLoaded', function() {
    // 生成随机数
    function random(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    // 创建 star 元素
    const starOffset = document.querySelector('.star');
    const starNumber = random(20, 30);
    createStar(starNumber);

    // 生成 star 元素
    function createStar(number) {
        for (let i = 0; i < number; i++) {
            // 延迟显示 star 元素
            let time = random(0, 3000);
            setTimeout(() => {
                const star = document.createElement('img');
                star.src = '/content/themes/default/svg/star.svg';
                star.alt = 'star';
                star.className = 'star';
                star.loading = 'lazy';

                const starWidth = random(8, 15);
                const starHeight = random(0, 90);
                const starLeft = random(0, 97);

                // 初始化 star 元素位置
                star.style.width = starWidth + 'px';
                star.style.height = 'auto';
                star.style.margin = starWidth + 'px';
                star.style.bottom = starHeight + '%';
                star.style.left = starLeft + '%';
                star.style.filter = 'invert(1) drop-shadow(0 0 10px var(--color-font-white))';
                star.style.animationName = 'star-move, star-glint';
                star.style.animationIterationCount = 'infinite, infinite';

                // 初始化 star 元素动画
                star.style.animationDuration = random(70, 400) + 's, ' + random(6, 20) + 's';

                // 将 star 元素添加到 starOffset 元素中
                starOffset.appendChild(star);
            }, i * time); // 调整延迟时间以适应需求
        }
    }

    // 点击 button 触发
    const buttons = document.querySelectorAll('.navigation span .button');
    buttons.forEach(button => {
        button.addEventListener('click', function(buttonEvent) {
            const buttonContent = button.querySelector('.button-content');
            const buttonContentBorder = buttonContent.querySelector('.content-border');

            // 点击内容不隐藏
            if (button.contains(buttonEvent.target) && buttonContent.style.visibility !== 'visible') {
                displayButton(buttonContent, buttonContentBorder);
            } else if (!buttonContentBorder.contains(buttonEvent.target)) {
                // 点击以外隐藏
                buttonContent.removeAttribute('style');
                buttonContentBorder.removeAttribute('style');
            } else {
                // 点击显示内容
                displayButton(buttonContent, buttonContentBorder);
            }
        });
    });

    // 显示 button 内容
    function displayButton(buttonContent, buttonContentBorder) {
        buttonContent.style.visibility = 'visible';
        buttonContentBorder.style.transform = 'scale(1)';
    }

    // 点击 dropdown 触发
    const dropdowns = document.querySelectorAll('.dropdown');

    // 隐藏 dropdown 内容
    function hiddenDropdown(dropdownContent, arrow, dropdown) {
        dropdownContent.removeAttribute('style');
        arrow.removeAttribute('style');
        dropdown.dataset.open = 'false';
    }

    // dropdown 事件监听
    function handleDropdownClick(dropdownEvent) {
        const dropdown = dropdownEvent.currentTarget;
        const dropdownContent = dropdown.querySelector('.dropdown-content');
        const arrow = dropdown.querySelector('.arrow');

        // 检查当前下拉框是否已经展开
        if (dropdown.dataset.open === 'true') {
            hiddenDropdown(dropdownContent, arrow, dropdown);
        } else {
            // 关闭其它已展开的下拉框
            document.querySelectorAll('.dropdown').forEach(otherItem => {
                if (otherItem !== dropdown && otherItem.dataset.open === 'true') {
                    const itemDropdownContent = otherItem.querySelector('.dropdown-content');
                    const itemArrow = otherItem.querySelector('.arrow');
                    hiddenDropdown(itemDropdownContent, itemArrow, otherItem);
                }
            });
            // 默认展开当前下拉框
            dropdownContent.style.display = 'block';
            arrow.style.transform = 'scaleY(-1)';
            dropdown.dataset.open = 'true';
        }
    }

    function dropdown() {
        if (window.innerWidth < 1000) {
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', handleDropdownClick);
            });
        } else {
            dropdowns.forEach(dropdown => {
                dropdown.removeEventListener('click', handleDropdownClick);
                const closeDropdownContent = dropdown.querySelector('.dropdown-content');
                const closeArrow = dropdown.querySelector('.arrow');
                hiddenDropdown(closeDropdownContent, closeArrow, dropdown);
            });
        }
    }

    // 点击 checkbox 触发
    const checkboxs = document.querySelector('#checkbox');
    const masking = document.querySelector('.masking');
    const labels = document.querySelector('.labels');

    // 隐藏 checkbox 内容
    function hiddenCheckbox(masking, labels, checkbox) {
        masking.removeAttribute('style');
        labels.removeAttribute('style');
        checkbox.checked = false;
    }

    // checkbox 事件监听
    function handleCheckboxClick(checkboxEvent) {
        const checkbox = checkboxEvent.currentTarget;

        if (checkbox.checked || labels.contains(checkboxEvent.target)) {
            masking.style.visibility = 'visible';
            labels.style.visibility = 'visible';
            labels.style.top = '10.3%';
        } else {
            hiddenCheckbox(masking, labels, checkbox);
        }
    }

    function checkbox() {
        if (window.innerWidth < 1000) {
            checkboxs.addEventListener('click', handleCheckboxClick);
        } else {
            checkboxs.removeEventListener('click', handleCheckboxClick);
            hiddenCheckbox(masking, labels, checkboxs);
        }
    }

    // 监听窗口大小变化
    window.addEventListener('resize', dropdown);
    window.addEventListener('resize', checkbox);

    // 初始检查
    dropdown();
    checkbox();

    // 全局监听事件
    window.addEventListener('click', function(event) {
        const globalDropdowns = document.querySelectorAll('.dropdown');

        // checkbox 点击其它关闭
        if (masking.contains(event.target)) {
            hiddenCheckbox(masking, labels, checkboxs);
        }

        // dropdown 点击其它关闭
        globalDropdowns.forEach(globalDropdown => {
            const globalDropdownContent = globalDropdown.querySelector('.dropdown-content');
            const globalArrow = globalDropdown.querySelector('.arrow');
            if (!globalDropdown.contains(event.target)) {
                hiddenDropdown(globalDropdownContent, globalArrow, globalDropdown);
            }
        });
    });
});