<?php
/**
 * Author: ConnorCho(caokang@foxmail.com)
 * User: connor
 * Date: 15/2/10 11:33
 * Source: ApplicationBehavior.php
 */

class ApplicationBehavior extends CBehavior{
    /** @var CDiaryRollLogger $__bizLogger */
    private $__bizLogger;

    /**
     * @return CDiaryRollLogger
     */
    private function getBizLogger(){
        if (!$this->__bizLogger){
            $this->__bizLogger=Yii::app()->bizlogger;
        }
        return $this->__bizLogger;
    }

    public function events() {
        return array_merge(parent::events(),array(
            'onBeginRequest'=>'beginRequest',
            'onEndRequest'=>'endRequest',
        ));
    }

    /**
     * 统一入口行为
     * @param CEvent $event
     */
    public function beginRequest($event) {
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set("display_errors", defined('DISPLAY_ERRORS'));
        ini_set("log_errors", defined('DISPLAY_ERRORS'));
        CommonUtils::trimPostGet();
    }

    /**
     * @param CEvent $envent
     */
    public function endRequest($envent){
        //在请求结束时处理异步机制的清理动作
        AsyncEventHandlerItem::onEndRequest($envent);
    }
}
