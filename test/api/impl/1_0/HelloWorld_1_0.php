<?php
/**
 * HelloWorld_1_0.php
 *
 * @author      shengyayun<719048774@qq.com>
 * @since       2018/1/9 14:19
 */
class HelloWorld_1_0 extends AbstractAPIImpl implements API {

    /**
     * @var HelloDomain
     */
    private $helloDomain;

    public function init(){
        $this->helloDomain=Yii::app()->domainFactory->getHelloDomain();
    }


    public function run( $params ) {
        return $this->helloDomain->ping();
    }

    public function getName() {
        return "system.hello.world.get";
    }

    public function getVersion() {
        return "1.0";
    }
}