<?php
/**
 * Created by PhpStorm.
 * User: anpengpeng
 * Date: 2017/8/3
 * Time: 下午2:42
 */
require 'ImgVerifyCode.php';
class CVerifyCodeComponent extends \CComponent
{
    /** @var  ImgVerifyCode $imgCode */
    private $imgCode;
    private $options;
    private $env;

    public $requestUrl;

    public function setOptions(array $options){
        $this->options = $options;
    }

    //设置env的值
    public function setEnv($value){
        $this->env = $value;
    }

    public function init(){
        $this->imgCode = new ImgVerifyCode($this->options['height'],$this->options['width'],$this->options['fontsize'],$this->options['codelen'],$this->options['charset']);
        $this->requestUrl  = $this->options[$this->env]['request.serverurl'];
    }

    /**
     * 第一次生成图片验证码
     */
    public function createImg(){
        $this->imgCode->doimg();
    }

    public function getVerifyCode(){
        return $this->imgCode->getCode();
    }

}
