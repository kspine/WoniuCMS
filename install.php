<?php

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

$_GET['m'] = 'Install'; // 绑定Home模块到当前入口文件
define('APP_PATH','./Application/');
require './ThinkPHP/ThinkPHP.php';