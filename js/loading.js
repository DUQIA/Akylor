// 进度条动画
function updateProgress(next) {
    // 加载进度条
    const loading = document.querySelector('.loading');

    // 更新进度条
    const xhr = new XMLHttpRequest();
    xhr.open('GET', next, true);

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
        console.error('Request error occurred');
        // 可以在这里添加用户反馈
    };

    xhr.send();
}

// admin 内使用
function updateAnimation() {
    const next = window.location.href;

    // 窗口刷新时触发
    window.addEventListener('beforeunload', function() {
        updateProgress(next);
    });
}