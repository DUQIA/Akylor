<?php

$file = __DIR__ . '/load.php'; // 使用绝对路径

(file_exists($file)) ? require $file : die('File does not exist');
