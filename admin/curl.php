<?php
// 需要安装 cURL扩展
    // try {
    //     // 初始化cURL
    //     $ch = curl_init();

    //     // 设置 cURL 选项
    //     curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/DUQIA/Phone-Sync/releases');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 转换为字符串
    //     curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP script)'); // 设置User-Agent头

    //     // 执行 cURL 请求
    //     $response = curl_exec($ch);
    // } catch (Exception $e) {
    //     echo 'error: ' . $e->getMessage();
    // }
    // // 检查是否出错
    // if (curl_errno($ch)) {
    //     echo 'cURL错误: ' . curl_error($ch);
    // } else {
    //     // 将JSON字符串解码为PHP数组
    //     $data = json_decode($response, true);
    //     return $versions;
    //     // 获取更新
    //     if ($data[0]['name'] !== $versions) {
    //         // 更新
    //         $content = file_get_contents($data[0]['assets'][0]['browser_download_url']);
    //         // 下载
    //         file_put_contents('Akylor.zip', $content, LOCK_EX);
    //         // 移动
    //         rename('Akylor.zip', '../Akylor.zip');
    //     }
    // }
    // // 关闭 cURL 连接
    // curl_close($ch);
    ?>