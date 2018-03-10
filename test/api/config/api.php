<?php
/**
 * api.php configuration file
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
return array(
	'basePath' => realPath(__DIR__ . '/..'),
	'modules' => array(
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => 'yii',
			'ipFilters' => array('127.0.0.1','::1'),
		),
	),
    'aliases'    => array(
        'frontend'     => dirname(__FILE__) . '/../../frontend',
        'common'       => dirname(__FILE__) . '/../../common',
        'backend'      => dirname(__FILE__) . '/../../backend',
        'vendor'       => dirname(__FILE__) . '/../../common/lib/vendor',
        'YiiRestTools' => dirname(__FILE__) . '/../../common/lib/YiiRestTools',
        'Yiinitializr' => dirname(__FILE__) . '/../../common/lib/Yiinitializr',
        'api'          => dirname(__FILE__) . '/..',
    ),
    'import'     => array(
        'application.extensions.components.*',
        'application.extensions.behaviors.*',
        'application.extensions.filters.*',
        'application.extensions.filters.*',
        'api.impl.1_0.*',
    ),
	'components' => array(
		'errorHandler' => array(
			'errorAction' => 'site/error',
			'class' => 'EApiErrorHandler'
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				// REST patterns
				array('<controller>/index', 	'pattern' => 'api/<controller:\w+>', 		'verb' => 'GET'),
				array('<controller>/view', 		'pattern' => 'api/<controller:\w+>/view', 	'verb' => 'GET'),
				array('<controller>/update', 	'pattern' => 'api/<controller:\w+>/update', 'verb' => 'POST'),
				array('<controller>/delete', 	'pattern' => 'api/<controller:\w+>/delete', 'verb' => 'DELETE'),
				array('<controller>/create', 	'pattern' => 'api/<controller:\w+>/create', 'verb' => 'PUT'),
			),
		)
	),
	'params' => array(
		"source"=>"api",//声明来源：api,记录日志用
	),
);
