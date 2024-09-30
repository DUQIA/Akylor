document.addEventListener('DOMContentLoaded', function() {
    // 加载进度条
    const loading = document.getElementById('loading');

    // 菜单、内容切换
    const items = document.querySelectorAll('.item');
    const menuItems = document.querySelectorAll('.menu-item');
    const contentItems = document.querySelectorAll('.content-item');
    
    // 更新进度条
    function updateProgress() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'index.php', true);

        xhr.onprogress = function(event) {
            if (event.lengthComputable) {
                const percent = (event.loaded / event.total) * 100;
                loading.style.width = percent + '%';
            }
        };

        xhr.onload = function() {
            if (this.status >= 200 && this.status < 300) {
                loading.style.width = '100%';
                setTimeout(function() {
                    loading.style.width = '0%';
                    loading.style.display = 'none';
                }, 1000); // 延迟 1 秒隐藏进度条
            } else {
                loading.style.width = '0%';
                loading.style.display = 'none';
            }
        };

        xhr.onerror = function() {
            console.error('请求发生错误');
            // 可以在这里添加用户反馈
        };

        xhr.send();
    }
    updateProgress();
    
    // 初始化进度条，每 3 秒更新一次
    let width = 0;
    const interval = setInterval(() => {
        if (width >= 100) {
            clearInterval(interval);
            // 数据加载完成后隐藏进度条
            loading.style.width = '100%';
            setTimeout(() => {
                loading.style.opacity = '0';
            }, 500);
        } else {
            width += 10;
            loading.style.width = width + '%';
        }
    }, 3000); // 每3秒更新一次进度
    
    // 菜单点击事件
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            // 移除所有项的选中样式
            menuItems.forEach(i => i.classList.remove('selected'));

            // 添加选中样式到当前项
            item.classList.add('selected');
        });
    });

    // 内容点击事件
    contentItems.forEach(contentItem => {
        contentItem.addEventListener('click', () => {
            // 移除所有项的选中样式
            contentItems.forEach(i => i.classList.remove('select'));

            // 添加选中样式到当前项
            contentItem.classList.add('select');
            
            // 切换内容
            items.forEach(item => {
                item.classList.remove('show');

                // 将对应的 id 传递给对应的 class 显示当前内容
                const matchingItem = Array.from(items).find(item => item.id === contentItem.id);
                if (matchingItem) {
                    matchingItem.classList.add('show');
                }
            });
        });
    });
});