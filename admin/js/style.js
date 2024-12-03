document.addEventListener('DOMContentLoaded', function() {
    // 菜单、内容切换
    const items = document.querySelectorAll('.item');
    const menuItems = document.querySelectorAll('.menu-item');
    const contentItems = document.querySelectorAll('.content-item');
    
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