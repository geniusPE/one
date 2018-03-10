<?php
/**
 * Initializer class file.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace Yiinitializr\Helpers;

use Yiinitializr\Helpers\Config;
use Yiinitializr\Helpers\ArrayX;
use Yiinitializr\Cli\Console;

/**
 * Initializer provides a set of useful functions to initialize a Yii Application development.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package Yiinitializr.helpers
 * @since 1.0
 */
class SwooleWebYiiInitializer extends Initializer{
	/**
	 * json编码
	 * @param array $array 数组
	 * @return string
	 */
	public static function jsonEncode($array){
		return json_encode($array, JSON_UNESCAPED_UNICODE);
	}
	/**
	 * json解码
	 * @param string $jsonString json字符串
	 * @return array
	 */
	public static function jsonDecode($jsonString){
		return json_decode($jsonString, true, 512, JSON_UNESCAPED_UNICODE);
	}

}
