<?php

// 获取用户的首选语言并进行验证
$language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : 'en';
$language = preg_replace('/[^a-zA-Z]/', '', $language); // 只允许字母

// 定义常量
define('LANGUAGE', $language);
define('TRANSLATIONS', (LANGUAGE === 'zh') ? array(
  'title' => 'Akylor-面板',
  'dashboard' => '仪表盘',
  'logout' => '登出',
  'system_info' => '系统信息',
  'system_name' => '系统名称',
  'host_name' => '主机名称',
  'version_name' => '版本名称',
  'version_info' => '版本信息',
  'machine_type' => '机器类型',
  'boot_time' => '开机时间',
  'run_time' => '运行时间',
) : array(
  'title' => 'Akylor-panel',
  'dashboard' => 'dashboard',
  'logout' => 'logout',
  'system_info' => 'system info',
  'system_name' => 'system name',
  'host_name' => 'host name',
  'version_name' => 'version name',
  'version_info' => 'version info',
  'machine_type' => 'machine type',
  'boot_time' => 'boot time',
  'run_time' => 'run time',
));
