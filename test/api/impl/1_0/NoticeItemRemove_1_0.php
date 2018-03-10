<?php

/**
 * 删除通知（notice.item.remove.post）
 */
class NoticeItemRemove_1_0 extends AbstractAPIImpl implements API {
    /** @var NoticeDomain $noticeDomain */
    private $noticeDomain;

    public function init(){
        $this->noticeDomain=Yii::app()->domainFactory->getNoticeDomain();
    }

    public function run($params){
        $userId=$params["__uid"];

        if (!APIUtils::isParamExists($params["params"], "unidarray", true))
            throw new APIException("未传入有效的通知ID数组", APIException::$ERR_BUSINESS_ILLEGAL);
        $unidArray=$params["params"]["unidarray"];

        if (!is_array($unidArray))
            throw new APIException("未传入有效的通知ID数组", APIException::$ERR_BUSINESS_ILLEGAL);
        $result=[
            "userid"=>(string)$userId,
            "removecount"=>"0",
        ];
        if (empty($unidArray)){
            return $result;
        }
        $ret=$this->noticeDomain->updateNoticeStatusByIdArray($userId, $unidArray, 0);
        $result["removecount"]=(string)$ret["result"];
        return $result;
    }

    public function getName(){
        return "notice.item.remove.post";
    }

    public function getVersion(){
        return "1.0";
    }
}
