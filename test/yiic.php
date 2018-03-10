<?php
/**
 *
 * Yiic.php bootstrap file
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

require('./common/lib/vendor/autoload.php');
require('./composer/vendor/autoload.php');

$app=Yiinitializr\Helpers\Initializer::create('./console', 'console', array(
	'./common/config/env.php',
	'./common/config/main.php',
	'./common/config/local.php',
));
Yii::app()->params["source"]="console";
$app->run();

