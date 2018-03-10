<?php

/**
 * 异步更新腾讯征信分
 */
class ASyncNoticePostSmsImpl extends AbstractASyncRunnerImpl implements ASyncRunnable{

    public function getName(){
        return "async.notice.postsms";
    }

    public function init(){

    }

    /**
     * @param ASyncEntity $entity
     */
    public function run($entity){
        $sign= $entity->parameters["sign"];
        $message = $entity->parameters["message"];
        $phone = $entity->parameters["phone"];
        $type = $entity->parameters["type"];
        $userInfo=new CUserInfoObject(APIUtils::getUid(), APIUtils::getOrgId(), APIUtils::getCliName());
        /** @var CSMSComponent $sms */
        $sms=Yii::app()->sms;
        if ($type == 'voice') {
            $this->getBizLogger()->debug('发送云片语音短信');
            $result=$sms->postVoiceSMS($userInfo, $phone, $message, $sign);
        }else{
            if ($sms->type == 'YP'){
                if($type == "sellsms"){
                    $result=$sms->postSellSMS($userInfo, $phone, $message, $sign);
                }elseif($type == "normal"){
                    $result=$sms->postSMS($userInfo, $phone, $message, $sign);
                    $this->getBizLogger()->debug("短信返回值=".APIUtils::jsonEncode($result));
                }
            }else{
                $result=$sms->clSmsPost($userInfo, $phone, $message);
            }
        }

        return $result;
    }
}
