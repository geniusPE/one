<?php

/**
 * 资讯动作（article.infor.action.post）
 */
class ArticleInforAction_1_0 extends AbstractAPIImpl implements API{
    public function run($params){
        if (!APIUtils::isParamExists($params["params"], "paid", true))
            throw new APIException("未传入有效的文章ID", APIException::$ERR_BUSINESS_ILLEGAL);
        $paId=$params["params"]["paid"];
        if (!APIUtils::isParamExists($params["params"], "action", true))
            throw new APIException("未传入有效的动作类型", APIException::$ERR_BUSINESS_ILLEGAL);
        $action=$params["params"]["action"];
        $userId=$params["__uid"];
        $orgId=$params["__orgid"];
        $cliName=$params["__cliname"];
        /** @var ArticleInforDomain $articleInforDomain */
        $articleInforDomain=Yii::app()->domainFactory->getArticleInforDomain();
        if ($action=="AGR"){//顶
            $ret=$articleInforDomain->agreeArticle($paId, $userId, $orgId, $cliName);
        }else if ($action=="RDR"){//已读
            $ret=$articleInforDomain->openArticle($paId, $userId, $orgId, $cliName);
        }else
            throw new APIException("未传入有效的动作类型", APIException::$ERR_BUSINESS_ILLEGAL);
        $result=[
            "userid"=>$userId,//用户ID
        ];
        return $result;
    }

    public function getName(){
        return "article.infor.action.post";
    }

    public function getVersion(){
        return "1.0";
    }
}