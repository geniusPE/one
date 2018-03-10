<?php

/**
 * 获取资讯列表（article.infor.list.get）
 */
class ArticleInforList_1_0 extends AbstractAPIImpl implements API{
    public function run($params){
        if (!APIUtils::isParamExists($params["params"], "pagenum", true))
            throw new APIException("未传入有效的页号", APIException::$ERR_FORMAT_API);

        $userId=$params["__uid"];
        $orgId=$params["__orgid"];
        $cliName=$params["__cliname"];

        $pageNum=$params["params"]["pagenum"];
        if (!APIUtils::isParamExists($params["params"], "pagesize", true))
            throw new APIException("未传入有效的每页记录数", APIException::$ERR_FORMAT_API);
        $pageSize=$params["params"]["pagesize"];
        /** @var ArticleInforDomain $articleInforDomain */
        $articleInforDomain=Yii::app()->domainFactory->getArticleInforDomain();
        $pageCount=0;
        $totalItemCount=0;
        /** @var array $list */
        $list=$articleInforDomain->getArticleList($userId, $pageNum, $pageSize, $pageCount, $totalItemCount, $orgId, $cliName);
        $result=[
            "curpage"=>(string)$pageNum,//当前页号
            "pagecount"=>(string)$pageCount,//页数
            "totalitemcount"=>(string)$totalItemCount,//总记录数
            "articles"=>[],//文章数组，每个元素都是一个文章
        ];

        /** @var PlatformArticle $pa */
        foreach ((array)$list as $pa){
            $pms=APIUtils::cloneObject($params);
            $pms["params"]=[
                "__article"=>$pa,
            ];
            $t=$this->apicall("article.infor.info.get", $this->getVersion(), $pms);
            $result["articles"][]=$t;
        }
        return $result;
    }

    public function getName(){
        return "article.infor.list.get";
    }

    public function getVersion(){
        return "1.0";
    }
}