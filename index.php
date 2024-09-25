<?php

$file = __DIR__ . '/load.php'; // 使用绝对路径

if (file_exists($file)) {
    require $file; // 仅在文件存在的情况下包含
} else {
    die('File does not exist');
}
