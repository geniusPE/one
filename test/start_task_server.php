<?php
/**
 * 启动异步服务
 *
 * @author      shengyayun<719048774@qq.com>
 * @since       2017/11/30 11:15
 */
require('./common/lib/vendor/autoload.php');
require('./composer/vendor/autoload.php');
Yiinitializr\Helpers\Initializer::create('./../', 'console', array(
    './common/config/env.php',
    './common/config/main.php',
    './common/config/local.php',
), "cli");
Yii::app()->params["source"] = "console";
//错误级别
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", defined('DISPLAY_ERRORS'));
ini_set("log_errors", defined('DISPLAY_ERRORS'));
//配置
$server = new SwooleTaskServer();
$server->start();