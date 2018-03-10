<?php

require('./common/lib/vendor/autoload.php');
require('./composer/vendor/autoload.php');
$app=Yiinitializr\Helpers\Initializer::create('./api', 'api', array(
	'./common/config/env.php',
	'./common/config/main.php',
	'./common/config/local.php',
));
Yii::app()->params["source"]="api";
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", defined('DISPLAY_ERRORS'));
ini_set("log_errors", defined('DISPLAY_ERRORS'));
$server=new SwooleAPIHttpServer();
$server->start();
