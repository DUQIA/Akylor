document.addEventListener('DOMContentLoaded', function() {
    // 菜单、内容切换
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
    contentItems.forEach(item => {
        item.addEventListener('click', () => {
            // 移除所有项的选中样式
            contentItems.forEach(i => i.classList.remove('select'));

            // 添加选中样式到当前项
            item.classList.add('select');
        });
    });
});