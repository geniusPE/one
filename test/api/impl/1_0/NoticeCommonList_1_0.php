<?php
/**
 * NoticeCommonList_1_0.php  获取通知列表（notice.common.list.get）
 * User: wlq
 * CreateTime: 16-1-21 上午12:22
 */

class NoticeCommonList_1_0 extends AbstractAPIImpl implements API {
    public function run( $params ) {
        $userId=$params["__uid"];

        $typeArray=[];
        if (APIUtils::isParamExists($params["params"], "type", true))
            $typeArray=$params["params"]["type"];

        if (!APIUtils::isParamExists($params["params"], "pagenum", true))
            throw new APIException("未传入有效的页号", APIException::$ERR_FORMAT_API);
        $pageNum=$params["params"]["pagenum"];

        if (!APIUtils::isParamExists($params["params"], "pagesize", true))
            throw new APIException("未传入有效的每页记录数", APIException::$ERR_FORMAT_API);
        $pageSize=$params["params"]["pagesize"];

        /* @var NoticeDomain $noticeDomain */
        $noticeDomain = Yii::app()->domainFactory->getNoticeDomain();
        $ret=$noticeDomain->getNoticeListByType($userId, $typeArray, $pageNum, $pageSize);
        $list=$ret["list"];
        $pageCount=$ret["pagecount"];
        $totalItemCount=$ret["totalitemcount"];
        $result=array(
            "userid"=>(string)$userId,//用户ID
            "curpage"=>(string)$pageNum,//当前页号
            "pagecount"=>(string)$pageCount,//页数
            "totalcount"=>(string)$totalItemCount,//通知总数量
            "noticecount"=>(string)count($list),//通知数组数量
            "notices"=> [],//通知数组，每个元素都是一个通知对象
        );
        /** @var UserNotice $un */
        foreach((array)$list as $un){
            $t = [
                "unid"=>$un["un_id"],
                'createtime' => date("Y-m-d H:i:s", $un["create_time"]),
                'untype' => $un["un_type"],
                'untitle' => $un["un_title"],
                'unremind' => $un["un_remind"],
                'untext' => $un["un_text"],
                'unsrcuserid' => $un["un_src_user_id"],
                'unjumptype' => $un["un_jump_type"],
                'unjumpid' => $un["un_jump_id"],
                'uniserr' => $un["un_is_err"],
            ];
            if(($un["un_starttime"] == 0 || $un["un_starttime"] <= time()) && ($un["un_endtime"] == 0 || $un["un_endtime"] >= time())) {
                $result["notices"][]=$t;
            }
        }
        return $result;
    }

    public function getName() {
        return "notice.common.list.get";
    }

    public function getVersion() {
        return "1.0";
    }
}
