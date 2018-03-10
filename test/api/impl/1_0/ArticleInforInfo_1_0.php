<?php

/**
 * 获取资讯详情（article.infor.info.get）
 */
class ArticleInforInfo_1_0 extends AbstractAPIImpl implements API {
    public function run($params){
        $userId=$params["__uid"];
        $orgId=$params["__orgid"];
        $cliName=$params["__cliname"];
        /** @var PlatformArticle $article */
        $article=null;
        if (APIUtils::isParamExists($params["params"], "__article", true)){
            $article=$params["params"]["__article"];
        }else{
            if (!APIUtils::isParamExists($params["params"], "paid", true))
                throw new APIException("未传入有效的文档ID", APIException::$ERR_BUSINESS_ILLEGAL);
            $paid=$params["params"]["paid"];
            /** @var ArticleInforDomain $articleInforDomain */
            $articleInforDomain=Yii::app()->domainFactory->getArticleInforDomain();
            $article=$articleInforDomain->getArticleFastByArticleIdAndUserId($paid, $userId, $orgId, $cliName);
        }

        /* @var QiniuResourceManager $rs*/
        $rs=Yii::app()->qiniuresourceManager->getInstance();
        $url=$rs->getResourceAbsoluteUrl($article["pa_title_img"]);
        if ($article instanceof CActiveRecord){
            $agreeCount="0";
            $openedCount="0";
            $myAgreed="0";
            $myOpened="0";
        }else{
            $agreeCount=$article["agree_count"];
            $openedCount=$article["opened_count"];
            $myAgreed=$article["my_agreed"];
            $myOpened=$article["my_opened"];
        }
        $result=[
            "paid"=>$article["pa_id"],//文章ID
            "type"=>$article["pa_type"],//类型（OTL：外链，INL：站内链接，CHT：自产HTML内容）
            "title"=>$article["pa_title"],//标题
            "titleimg"=>$url,//标题图
            "shortdesc"=>$article["pa_short_desc"],//短描述
            "content"=>(string)$article["pa_content"],//与type所表示的类型一致
            "agreecount"=>(string)$agreeCount,//被顶数量
            "openedcount"=>(string)$openedCount,//被打开次数/被阅读次数
            "publishtime"=>$article["pa_pub_time"]?date(DateUtils::$DATETIME_FORMAT, $article["pa_pub_time"]):"",//发布时间
            "istop"=>(string)$article["pa_top"],//是否置顶
            "myagreed"=>$myAgreed,
            "myopened"=>$myOpened,
        ];
        return $result;
    }

    /**
     * 从字符串中统计和计算字符出现的次数
     * @param string $str 待检查字符串
     * @param string $char 要查的字符
     * @return int
     */
    private function countFromString($str, $char){
        $result=0;
        $cnt=StringUtils::getStrLen($str);
        for($i=0; $i<=$cnt-1; $i++){
            $ch=$str[$i];
            if ($ch==$char){
                $result++;
            }
        }
        return $result;
    }

    public function getName(){
        return "article.infor.info.get";
    }

    public function getVersion(){
        return "1.0";
    }
}