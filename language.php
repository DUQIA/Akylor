<?php
// 获取用户的首选语言并进行验证
$language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : 'en';
$language = preg_replace('/[^a-zA-Z]/', '', $language); // 只允许字母

// 定义常量
define('LANGUAGE', $language);
define('TRANSLATIONS', (LANGUAGE === 'zh') ? array(
  'title' => 'Akylor-安装',
  'title_login' => 'Akylor-登录',
  'db_host' => '数据库主机',
  'db_user' => '数据库用户名',
  'db_pass' => '数据库密码',
  'db_name' => '数据库名称',
  'next' => '下一步',
  'ad_name' => '用户名',
  'ad_pass' => '密码',
  'submit' => '提交',
  'complete' => '安装完成!',
  'home' => '主页',
  'login' => '登录',
  'Successfully' => '已成功安装!',
) : array(
  'title' => 'Akylor-install',
  'title_login' => 'Akylor-login',
  'db_host' => 'Database host',
  'db_user' => 'Database user name',
  'db_pass' => 'Database password',
  'db_name' => 'Database name',
  'next' => 'next',
  'ad_name' => 'user name',
  'ad_pass' => 'password',
  'submit' => 'submit',
  'complete' => 'installation complete!',
  'home' => 'home',
  'login' => 'login',
  'Successfully' => 'Successfully installed!',
));
?>