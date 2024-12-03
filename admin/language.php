<?php

// 获取用户的首选语言并进行验证
$language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : 'en';
$language = preg_replace('/[^a-zA-Z]/', '', $language); // 只允许字母

// 定义常量
define('LANGUAGE', $language);
define('TRANSLATIONS', (LANGUAGE === 'zh') ? array(
  'title' => 'Akylor-面板',
  'dashboard' => '仪表盘',
  'home' => '主页',
  'logout' => '登出',
  'system_info' => '系统信息',
  'system_name' => '系统名称',
  'host_name' => '主机名称',
  'version_name' => '版本名称',
  'version_info' => '版本信息',
  'machine_type' => '机器类型',
  'boot_time' => '开机时间',
  'run_time' => '运行时间',
  // home
  'home_site' => '网站',
  'home_site_name' => '名称',
  'home_site_icon' => '图标',
  'home_site_path' => '路径',
  'home_theme' => '主题',
  'home_theme_folder' => '文件夹',
  'home_navigation' => '导航栏',
  'home_navigation_label' => '标签',
  'home_navigation_label_description' => '回车结束',
  'home_navigation_search' => '搜索',
  'home_navigation_login' => '登录',
  'home_main_body' => '正文',
  'home_main_body_content' => '内容',
  'home_main_body_code' => 'html 编码',
  'home_label_type' => '类型',
  'home_label_dropdown' => '下拉',
  'home_label_link' => '链接',
  'home_label_button' => '按钮',
  'home_submit' => '提交',
) : array(
  'title' => 'Akylor-panel',
  'dashboard' => 'Dashboard',
  'home' => 'Home',
  'logout' => 'Logout',
  'system_info' => 'System info',
  'system_name' => 'System name',
  'host_name' => 'Host name',
  'version_name' => 'Version name',
  'version_info' => 'Version info',
  'machine_type' => 'Machine type',
  'boot_time' => 'Boot time',
  'run_time' => 'Run time',
  // home
  'home_site' => 'Website',
  'home_site_name' => 'Name',
  'home_site_icon' => 'Icon',
  'home_site_path' => 'Path',
  'home_theme' => 'Theme',
  'home_theme_folder' => 'Folder',
  'home_navigation' => 'Navigation',
  'home_navigation_label' => 'Label',
  'home_navigation_label_description' => 'Carriage return end',
  'home_navigation_search' => 'Search',
  'home_navigation_login' => 'Login',
  'home_main_body' => 'Main body',
  'home_main_body_content' => 'Content',
  'home_main_body_code' => 'Html code',
  'home_label_type' => 'Type',
  'home_label_dropdown' => 'Dropdown',
  'home_label_link' => 'Link',
  'home_label_button' => 'Button',
  'home_submit' => 'Submit',
));
