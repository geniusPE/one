<?php
/**
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

error_reporting(E_ALL & ~E_NOTICE);
require_once "env.php";

bcscale(2);//全局设置小数位数

return array(
    'homeUrl'=>$__SYSTEM_GLOBAL_VARIABLES['homeurl'],
    'name' => $__SYSTEM_GLOBAL_VARIABLES['app.name'],
    'preload' => array('log','bizlogger'),
    'aliases' => array(
        'frontend' => dirname(__FILE__) . '/../..' . '/frontend',
        'common' => dirname(__FILE__) . '/../..' . '/common',
        'backend' => dirname(__FILE__) . '/../..' . '/backend',
        'vendor' => dirname(__FILE__) . '/../..' . '/common/lib/vendor',
        'ebanklib' => dirname(__FILE__) . '/../../..' . '/ebanklib',
    ),
    'import' => array(
        'common.extensions.components.*',
        'common.exceptions.*',
        'common.components.*',
        'common.helpers.*',
        'common.models.*',
        'common.utils.*',
        'common.domains.*',
        'common.hooks.*',
        'common.utils.*',
        'common.cacheloader.*',
        'common.asyncimpl.*',
        'common.sms.*',
        'application.controllers.*',
        'application.extensions.*',
        'application.helpers.*',
        'application.models.*',
        'ebanklib.widgets.*',
        'ebanklib.api.*',
        'ebanklib.api.impl.*',
        'ebanklib.common.threads.*',
        'ebanklib.common.console.*',
        'ebanklib.common.domain.*',
        'ebanklib.common.exceptions.*',
        'ebanklib.common.transaction.*',
        'ebanklib.common.utils.*',
        'ebanklib.components.*',
        'ebanklib.common.support.*',
        'ebanklib.common.ticketholder.*',
        'ebanklib.common.models.*',
        'ebanklib.common.resourcemgr.*',
        'ebanklib.common.asyncrunner.*',
        'ebanklib.common.asyncrunner.models.*',
        'ebanklib.common.http.*',
        'ebanklib.common.rpc.*',
        'ebanklib.common.swooleapp.*',
        'ebanklib.common.messagebus.*',
        'ebanklib.common.cacheloader.*',
        'ebanklib.components.logger.*',
        'ebanklib.components.hooks.*',
        'ebanklib.hookimpl.*',
        'ebanklib.common.messagebus.msgreceiverimpl.*',
        //异步服务
        'ebanklib.common.task.*',
        'ebanklib.common.task.taskreceiverimpl.*',
        'common.taskreceiverimpl.*',
        //消息总线
        'ebanklib.common.messagebus.*',
        //trpc
        'ebanklib.components.trpc.*',
        'ebanklib.api2.*',
    ),
    'components' => array(
        'verifycode' =>array(
            'class' => 'common.components.imgverifycode.CVerifyCodeComponent',
            'env'   => 'dev',
            'options' => [
                'dev'=>[
                    'request.serverurl' => $__SYSTEM_GLOBAL_VARIABLES['request.serverurl'],
                ],
                'product'=>[
                    'request.serverurl' => $__SYSTEM_GLOBAL_VARIABLES['request.serverurl'],
                ],
                'width'    => '260',
                'height'   => '100',
                'fontsize' => '40',
                'codelen'  => '4',
                'charset'  => 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789',
            ],
        ),
        'db'=>array(
            'connectionString' => "mysql:host={$__SYSTEM_GLOBAL_VARIABLES['db.host']};dbname={$__SYSTEM_GLOBAL_VARIABLES['db.db_name']}",
            'emulatePrepare' => true,
            'username' => $__SYSTEM_GLOBAL_VARIABLES['db.user_name'],
            'password' => $__SYSTEM_GLOBAL_VARIABLES['db.password'],
            'charset' => 'utf8mb4',
            'schemaCachingDuration'=>$__SYSTEM_GLOBAL_VARIABLES['risk_db.schemacachingduration'],
        ),
        'cache'=>array(
            'class'=>'ebanklib.components.rediscache.CRedisCacheComponent',
            "options"=>[
                "host"=>$__SYSTEM_GLOBAL_VARIABLES["redis"]["host"],
                "port"=>$__SYSTEM_GLOBAL_VARIABLES["redis"]["port"],
                "timeout"=>$__SYSTEM_GLOBAL_VARIABLES["redis"]["timeout"],
                "prefix"=>$__SYSTEM_GLOBAL_VARIABLES["redis"]["workspace"],//前缀，每个项目必须不同
            ],
        ),
        'pubsub'=>array(
            'class'=>'ebanklib.components.rediscache.CRedisPubSubComponent',
            "options"=>[
                "host"=>$__SYSTEM_GLOBAL_VARIABLES["redis"]["host"],
                "port"=>$__SYSTEM_GLOBAL_VARIABLES["redis"]["port"],
                "timeout"=>$__SYSTEM_GLOBAL_VARIABLES["redis"]["timeout"],
            ],
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        "session"=>[
            'class' => 'system.web.CCacheHttpSession',
        ],
        'log' => array(
            'class'  => 'CLogRouter',
            'routes' => array(
                array(
                    'class'        => 'CDbLogRoute',
                    'connectionID' => 'db',
                    'levels'       => 'error',
                ),
            ),
        ),
        'mailer' => [
            'class' => 'ebanklib.components.PHPMailer_5_2_4.components.PHPMailer',
            'CharSet' => 'UTF-8',
            'SMTPAuth' => true,
            'Port' => 25,
            'expiration' => 86400, //默认有效期1天
            'Host' => 'smtp.exmail.qq.com',
            'From' => "webmaster@chnloan.com",
            'Username' => "webmaster@chnloan.com",
            'Password' => 'a65319541bf411e6B',
        ],
        'domainFactory'=>array(
            'class'=>'ebanklib.components.domainfactory.CDomainFactoryComponent',
        ),
        'orderno'=>array(
            'class'=>'ebanklib.components.orderno.COrderNOComponent',
            "options"=>array(
                    "cache.component.name"=>"cache",
                    "orderno.expire"=>86400,
                    "orderno.length"=>25,//单号长度，一天最多可以产生9位数的订单号
                ),
        ),
        'sms'=>array(
            'class'=>'ebanklib.components.sms.CSMSComponent',
            'type' => $__SYSTEM_GLOBAL_VARIABLES['sms']['type'], //CL创蓝 YP云片
            'use_proxy'=>$__SYSTEM_GLOBAL_VARIABLES['p2p.proxy'],//本地调试正式账号时开启代理避免提示ip非法
            'proxy_url'=>"{homeurl}/host/a814102ee3b211e5b294fb19de61cea2",//代理的URL模板
            "options"=>array(
                'limitNumber' => $__SYSTEM_GLOBAL_VARIABLES['sms']['limitNumber'],
                'limitTime'   => $__SYSTEM_GLOBAL_VARIABLES['sms']['limitTime'],
                'limitDay'    => $__SYSTEM_GLOBAL_VARIABLES['sms']['limitDay'],
                'isLimit'     => $__SYSTEM_GLOBAL_VARIABLES['sms']['isLimit'],
                "cl"=>[
                    //'service'=>'http://222.73.117.158/msg/HttpBatchSendSM',
                    'service'=>'http://222.73.117.156/msg/HttpBatchSendSM', //20160420创蓝受到攻击后临时修改
                    'account'=>'zj_khtz',
                    'password'=>'byxZJX151012',
                    'voiceaccount'=>'yy12590-clcs-05',
                    'voicepassword'=>'ghuhGGH8532',
                    'product'=>'349312826',
                    'sign'=>"【快乐钱包】",
                ],
            ),
        ),
        'booster' => array(
            'class' => 'vendor.clevertech.yiibooster.src.components.Booster',
        ),
        'curl' => array(
            'class' => 'vendor.hackerone.curl.Curl',
            'options' => array(/* additional curl options */),
        ),
        'resourceManager' => [
            'class' => 'ebanklib.components.resourcemgr.CResourceManagerComponent',
            'options' => ["dl.url"=>$__SYSTEM_GLOBAL_VARIABLES['dl.url'],
                          "dl.path"=>$__SYSTEM_GLOBAL_VARIABLES["dl.path"],
                          "bizlogger.component.id"=>"bizlogger",
                         ],
        ],
        'qiniuresourceManager' => [
            'class' => 'ebanklib.components.resourcemgr.QiniuResourceManagerComponent',
            'options' => ["qiniu.accesskey"=>$__SYSTEM_GLOBAL_VARIABLES['qiniu']['qiniu.accesskey'],
                "qiniu.secretkey"=>$__SYSTEM_GLOBAL_VARIABLES['qiniu']['qiniu.secretkey'],
                "qiniu.bucket.mapper"=>$__SYSTEM_GLOBAL_VARIABLES['qiniu']['qiniu.bucket.mapper'],
                "ffmpeg.key"=>"ffmpeg",
                "thumb.key"=>"thumb",
                "bizlogger.component.id"=>"bizlogger",
            ],
        ],
        'bizlogger' => array(
            'class' => 'ebanklib.components.logger.CDiaryRollLogger',
            'options' => [
                "logger.properties"=>$__SYSTEM_GLOBAL_VARIABLES["logger.properties"],
                "logger.file"=>$__SYSTEM_GLOBAL_VARIABLES["logger.file"],
                "app.params.keyname"=>"source",
                "app.params.user.keyname"=>"uid",
            ]
        ),
        "messagePusher"=>[
            "class"=>"ebanklib.components.msgpusher.CMessagerPusherComponent",
            "options"=>[
                "push.redirect.url"=>"{homeurl}/host/a814102ee3b211e5b294fb19de61cea2",
                "bizlogger.component.id"=>"bizlogger",//日志组件的名字
                "hooker.component.id"=>"hook",//钩子组件的名字
                "aliastype"=>"PHONE",//别名类型
                "unicast.expire.daycount"=>1,//单播时消息的超时天数
                "mode"=>$__SYSTEM_GLOBAL_VARIABLES["push.config"]['mode'],//开发模式/生产模式（dev/product）
                "ios"=>[
                    "appkey"=>"5720bf2e67e58e4da10007b6",
                    "master.secret"=>"dswgpht8fphak9pkbaiwzw0utoy0hqvu",
                ],
                "android"=>[
                    "appkey"=>"5720bfa667e58ef3cd001f7b",
                    "master.secret"=>"sxxld4chfx9yy9cc2q6y5pui4myixa66",
                ],
            ],
        ],
        "hook"=>[
            'class' => 'ebanklib.components.hooks.CHookComponent',
            "options"=>[
                "hooks"=>[
                    "onLookupOrganizationId"=>"COnLookupOrganizationIdHookHandle",
                    "onLookupOrganizationName"=>"COnLookupOrganizationNameHookHandle",
                    "onLookupOrganizationSign"=>"COnLookupOrganizationSignHookHandle",
                    "onLookupMessagePusherKeys"=>"COnLookupMessagePusherKeysHookHandle",
                ],
            ]
        ],
        'influxdb'=>array(
            'class'=>'ebanklib.components.monitor.CInfluxdbComponent',
            'options'=>array(
                'project'=>$__SYSTEM_GLOBAL_VARIABLES["project.prefix"],
                'host'=>$__SYSTEM_GLOBAL_VARIABLES['influxdb']['host'],
                'port'=>$__SYSTEM_GLOBAL_VARIABLES['influxdb']['port'],
                'user'=>$__SYSTEM_GLOBAL_VARIABLES['influxdb']['user'],
                'pwd'=>$__SYSTEM_GLOBAL_VARIABLES['influxdb']['pwd'],
            ),
        ),
        'rabbitmq'=>array(
            'class'=>'ebanklib.components.rabbitmq.CRabbitMQComponent',
            "options"=>[
                'prefix'=>$__SYSTEM_GLOBAL_VARIABLES["project.prefix"],
                "host"=>$__SYSTEM_GLOBAL_VARIABLES["rabbitmq"]["host"],
                "port"=>$__SYSTEM_GLOBAL_VARIABLES["rabbitmq"]["port"],
                "user"=>$__SYSTEM_GLOBAL_VARIABLES["rabbitmq"]["user"],
                "pwd"=>$__SYSTEM_GLOBAL_VARIABLES["rabbitmq"]["pwd"],
            ],
        ),
        'trpc' => array(
            'class' => 'ebanklib.components.trpc.CThriftComponent',
            'options' => [
                'prefix' => $__SYSTEM_GLOBAL_VARIABLES["project.prefix"],
                'endpoints' => $__SYSTEM_GLOBAL_VARIABLES['trpc']['endpoints'],
                'rBufSize' => $__SYSTEM_GLOBAL_VARIABLES['trpc']['rBufSize'],
                'wBufSize' => $__SYSTEM_GLOBAL_VARIABLES['trpc']['wBufSize'],
                'socket_timeout' => $__SYSTEM_GLOBAL_VARIABLES['trpc']['socket_timeout'],
                'rpc_timeout' => $__SYSTEM_GLOBAL_VARIABLES['trpc']['rpc_timeout'],
                'whitelist' => $__SYSTEM_GLOBAL_VARIABLES['trpc']['whitelist'],
                'register' => $__SYSTEM_GLOBAL_VARIABLES['trpc']['register'],
            ],
        ),
    ),
    'behaviors' => array(
        'app' => 'common.behaviors.ApplicationBehavior',
    ),
    // application parameters
    'params' => array(
        'prefix' => $__SYSTEM_GLOBAL_VARIABLES['project.prefix'],
        'yii.handleErrors'   => true,
        'yii.debug' => true,
        'yii.traceLevel' => 3,
        // php configuration
        'php.defaultCharset' => 'utf-8',
        'php.timezone'       => 'PRC',
        'frontend.url'=>$__SYSTEM_GLOBAL_VARIABLES['frontend.url'],
        'dl.url'=>$__SYSTEM_GLOBAL_VARIABLES['dl.url'],
        'dl.path'=>$__SYSTEM_GLOBAL_VARIABLES['dl.path'],
        "company.name"=>$__SYSTEM_GLOBAL_VARIABLES["company.name"],
        'sessionkey.expire'=>2592000,//保存30天自动过期
        "project.prefix"=>$__SYSTEM_GLOBAL_VARIABLES["project.prefix"],
        "async.config"=>[
            "channel.prefix"=>$__SYSTEM_GLOBAL_VARIABLES["project.prefix"],  //主要用来区分不同的项目
            "classmap"=>[
                "async.test.test.1"=>"ASyncTestImpl",
                "async.notice.postsms"=>"ASyncNoticePostSmsImpl",
            ],
        ],
        "api.forward.config"=>[
            "FORWARD"=>$__SYSTEM_GLOBAL_VARIABLES["api.forward.config"]["FORWARD"],
            "RECV"=>$__SYSTEM_GLOBAL_VARIABLES["api.forward.config"]["RECV"],
        ],//跳转映射配置
        "push.config"=>$__SYSTEM_GLOBAL_VARIABLES["push.config"],
        "swoole.config"=>[
            "FORWARD"=>$__SYSTEM_GLOBAL_VARIABLES["swoole.config"]["FORWARD"],
            "RECV"=>$__SYSTEM_GLOBAL_VARIABLES["swoole.config"]["RECV"],
            "API"=>$__SYSTEM_GLOBAL_VARIABLES["swoole.config"]["API"],
        ],//新的基于tcp的RPC设置
        //异步任务
        "task" => [
            'prefix' => $__SYSTEM_GLOBAL_VARIABLES["project.prefix"],
            //客户端
            "client" => [
                "url" => $__SYSTEM_GLOBAL_VARIABLES['task']['client']['url'],
                'timeout' => $__SYSTEM_GLOBAL_VARIABLES['task']['client']['timeout'],
                'settings' => $__SYSTEM_GLOBAL_VARIABLES['task']['client']['settings'],
            ],
            //服务器
            "server" => [
                "url" => $__SYSTEM_GLOBAL_VARIABLES['task']['server']['url'],
                'settings' => $__SYSTEM_GLOBAL_VARIABLES['task']['server']['settings'],
                //映射列表：主题->实现类
                'classmap' => [
                    'selfcheck' => "SelfCheckTaskReceiverImpl",
                    'sms' => 'SmsTaskReceiverImpl',
                ],
            ],
        ],
        //新版消息总线
        "messagebus" => [
            'prefix' => $__SYSTEM_GLOBAL_VARIABLES["project.prefix"],
            //监听的交换机
            'exchange' => $__SYSTEM_GLOBAL_VARIABLES["messagebus"]["exchange"],
            /**
             * 消息总线会将rabbitmq中如下subject的消息转发到消息队列
             */
            'allow' => [
                'selfcheck',
            ],
        ]
    ),
);
