<?php

/**
 * 获取通知的更新提醒（notice.update.remind.get）
 */
class NoticeUpdateRemind_1_0 extends AbstractAPIImpl implements API {
    /* @var NoticeDomain $noticeDomain*/
    private $noticeDomain;

    public function init(){
        $this->noticeDomain=Yii::app()->domainFactory->getNoticeDomain();
    }

    public function run( $params ) {
        $userId=$params["__uid"];
//        $timestamp=null;
//        if (APIUtils::isParamExists($params["params"], "timestamp")){
//            $t=$params["params"]["timestamp"];
//            if (!DateUtils::isDatetime($t))
//                throw new APIException("传入的时间戳不是日期时间类型", APIException::$ERR_API_INTERNALERROR);
//            $timestamp=strtotime($t);
//        }
        $ret=$this->noticeDomain->getNoticeUpdateRemindCount($userId);
        $result=array(
            "userid"=>(string)$userId,//用户ID
            "newnoticecount"=>(string)$ret['result'],//新通知数
        );
        return $result;
    }

    public function getName() {
        return "notice.update.remind.get";
    }

    public function getVersion() {
        return "1.0";
    }
}
