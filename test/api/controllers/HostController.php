<?php

/**
 * 第三方托管平台控制器
 * 1.通知回调
 * 2.代理请求，将开发机器的请求通过在托管平台白名单里的服务器进行中转
 */
class HostController extends EController{
    private $logger=null;
    const EMPTY_VAL="565aed7a-b76f-11e6-bc7c-ab3e8e53cc27";
    public function init(){

    }

    /**
     * @return CDiaryRollLogger
     */
    public function getBizLogger(){
        if ($this->logger==null)
            $this->logger=Yii::app()->bizlogger;
        return $this->logger;
    }

    /**
     * 测试页，确保服务可用
     * https://api.ebank99.com.cn/host?test
     */
    public function actionIndex(){
        //测试页
        if (array_key_exists("test", $_REQUEST)){
            $t=$_GET["test"];
            echo $t?$t:"test ok";
            return;
        }
    }

    /**
     * 代理地址
     * https://api.ebank99.com.cn/host/a814102ee3b211e5b294fb19de61cea2?test
     */
    public function actionA814102ee3b211e5b294fb19de61cea2(){
        if (array_key_exists("test", $_REQUEST)){
            $t=$_GET["test"];
            echo $t?$t:"test ok";
            return;
        }
        $this->getBizLogger()->debugEx("收到来自{$_SERVER["REMOTE_ADDR"]}的代理请求<- _REQUEST=".APIUtils::jsonEncode($_REQUEST), "proxy", null);
        $urlTemp=Yii::app()->request->getParam("url","");
        //$result[self::URL_KEY_NAME]=urlencode(serialize($url));
        if (!$urlTemp)
            throw new APIException("代理时没有传入有效的转向URL", APIException::$ERR_API_INTERNALERROR);
        $url=unserialize(urldecode($urlTemp));
        $this->getBizLogger()->debugEx("url=".$url, "proxy", null);
        $dataTemp=Yii::app()->request->getParam("data","");
        if (!$dataTemp)
            throw new APIException("代理时没有传入有效的data", APIException::$ERR_API_INTERNALERROR);
        $data=unserialize(urldecode($dataTemp));
        $this->getBizLogger()->debugEx("data=".APIUtils::jsonEncode($data), "proxy", null);

        $actionClass=Yii::app()->request->getParam("actionclass", "HttpProxySimpleActionImpl");
        $this->getBizLogger()->debugEx("actionClass=".$actionClass, "proxy", null);
        /** @var AbstractHttpProxyActionImpl $action */
        $action=new $actionClass();
        $ret=$action->request($url, $data);
        $this->getBizLogger()->debugEx("代理返回->".$ret, "proxy", null);
        echo $ret;
    }

////    /**
////     * 创蓝短信异步回调
////     */
//    public function actionB93d743e680b11e6b406bba793fe5db5(){
//        Yii::app()->params["source"]="SMSNOTIFY";
//        $this->getBizLogger()->debug("收到创蓝的发送状态异步通知回调".APIUtils::jsonEncode($_REQUEST));
//        //提交短信时平台返回的msgid
//        $msgId=Yii::app()->request->getParam("msgid");
//        //格式YYMMDDhhmm，其中YY=年份的最后两位（00-99），MM=月份（01-12），DD=日（01-31），hh=小时（00-23），mm=分钟（00-59）
//        $t=Yii::app()->request->getParam("reportTime");
//        $reportTime=$t;
//        //单一的手机号码
//        $mobile=Yii::app()->request->getParam("mobile");
//        ////状态报告数值
//        $code=Yii::app()->request->getParam("status");
//
//        $usr = UserSmsRecord::model()->find("usmsr_unique_id='".trim($msgId)."' and usmsr_status=0");
//        if (!$usr){
//            echo 1;
//            return;
//        }
//
//        $usr->usmsr_code=$code;
//        $usr->usmsr_recv_response_time=$reportTime;
//        if ($usr->usmsr_code=="DELIVRD"){
//            $usr->usmsr_async_exception="";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_COMPELETE;
//        }else if ($usr->usmsr_code="EXPIRED"){
//            $usr->usmsr_async_exception="短消息超过有效期";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }else if ($usr->usmsr_code="UNDELIV"){
//            $usr->usmsr_async_exception="短消息是不可达的";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }else if ($usr->usmsr_code="UNKNOWN"){
//            $usr->usmsr_async_exception="未知短消息状态";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }else if ($usr->usmsr_code="REJECTD"){
//            $usr->usmsr_async_exception="短消息超过有效期";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }else if ($usr->usmsr_code="DTBLACK"){
//            $usr->usmsr_async_exception="目的号码是黑名单号码";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }else if ($usr->usmsr_code="ERR:104"){
//            $usr->usmsr_async_exception="系统忙";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }else if ($usr->usmsr_code="REJECT"){
//            $usr->usmsr_async_exception="审核驳回";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }else if ($usr->usmsr_code="其他"){
//            $usr->usmsr_async_exception="网关内部状态";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }else{
//            $usr->usmsr_async_exception="其它原因";
//            $usr->usmsr_status=UserSmsRecord::SMS_STATUS_FAIL;
//        }
//
//        $ret=$usr->update();
//        if (!$ret){
//            throw new APIException(ModelUtils::getErrorMessage($usr), APIException::$ERR_BUSINESS_ILLEGAL);
//        }
//        $this->getBizLogger()->debug("更新用户短信记录，数据内容：".APIUtils::jsonEncode($usr->attributes));
//    }

//    /**
//     * 云融回调函数 - 上行短信
//     */
//    public function actionCea19970d01711e7be30139d58a95d21()
//    {
//        $this->getBizLogger()->debug("收到云融(上行短信)回调：" . APIUtils::jsonEncode($_REQUEST));
//        return '0'; // 不知道为什么要返回0，改之前就这样，如果不需要就删除吧
//    }

    /**
     * 生成图片验证码
     */
    public function actionimgVerifyCode()
    {
        $ticket = Yii::app()->request->getParam('ticket');
        /** @var NoticeDomain $noticeDomain */
        $noticeDomain = Yii::app()->domainFactory->getNoticeDomain();
        $noticeDomain->createImgVerifyCode($ticket);
    }

    /**
     * 云片回调函数
     */
    public function action80aa4d5f7559cb6dd3455a1e1c901735()
    {
        try {
            Sms::impl('YUNP')->setStatusReport();
            echo 'SUCCESS';
        } catch (Exception $e) {
            $message = ModelUtils::getErrorMessage(['code' => $e->getCode(), 'message' => $e->getMessage()]);
            $this->getBizLogger()->debug($message);
            throw new APIException($message, APIException::$ERR_BUSINESS_ILLEGAL);
        }
    }

    /**
     * 云融回调函数 - 状态报告
     */
    public function actionEbb48c7ad01711e78c79e7417172e264()
    {
        try {
            Sms::impl('YUNR')->setStatusReport();
            echo '0#成功';
        } catch (Exception $e) {
            $message = ModelUtils::getErrorMessage(['code' => $e->getCode(), 'message' => $e->getMessage()]);
            $this->getBizLogger()->debug($message);
            echo "-1#$message";
            throw new APIException($message, APIException::$ERR_BUSINESS_ILLEGAL);
        }
    }

    /**
     * thrift rpc
     */
    public function actionTrpc()
    {
        Yii::app()->trpc->handle();
    }}
