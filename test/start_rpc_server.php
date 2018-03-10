<?php

define('RPC_RUN_DEBUG_MODE',"1");//阻塞模式运行
require('./common/lib/vendor/autoload.php');
require('./composer/vendor/autoload.php');
require('../ebanklib/common/rpc/SwooleRpcBootstapConfig.php');
$bootstrapConfig=new SwooleRpcBootstapConfig();
$bootstrapConfig->root="./api";
$bootstrapConfig->configName="api";
$bootstrapConfig->configArray=[
	'../common/config/env.php',
	'../common/config/main.php',
	'../common/config/local.php',
];
$bootstrapConfig->spiName="cli";
$app=Yiinitializr\Helpers\SwooleWebYiiInitializer::create('./api', $bootstrapConfig->configName, array(
	'./common/config/env.php',
	'./common/config/main.php',
	'./common/config/local.php',
), $bootstrapConfig->spiName);
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", defined('DISPLAY_ERRORS'));
ini_set("log_errors", defined('DISPLAY_ERRORS'));
$listenUrl=Yii::app()->params["swoole.config"]["RECV"]["LISTEN"];
$urlArray=parse_url($listenUrl);
$server=new SwooleRPCServer($urlArray["host"], $urlArray["port"], $bootstrapConfig);
$server->start();
