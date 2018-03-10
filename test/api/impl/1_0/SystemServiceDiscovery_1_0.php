<?php

/**
 * 服务发现（system.service.discovery.get）
 */
class SystemServiceDiscovery_1_0 extends AbstractAPIImpl implements API{
    /** @var SystemDomain $systemDomain */
    private $systemDomain;

    public function init(){
        $this->systemDomain=Yii::app()->domainFactory->getSystemDomain();
    }

    public function run($params) {
        header("cache-control:no-cache,must-revalidate");
        if (!APIUtils::isParamExists($params["params"], "mode", true))
            throw new APIException("未传入有效的运行模式，必须是D/P之一", APIException::$ERR_BUSINESS_ILLEGAL);
        $mode=$params["params"]["mode"];
        $list=$this->systemDomain->getSystemServiceConfList($mode);
        $result=["count"=>"0",//服务数量
            "services"=>[],
            ];
        $ret=ModelUtils::groupEntityListFromListByEntityId($list, "src_type");
        /**
         * @var String $type
         * @var SysServiceConf[] $itemList
         */
        foreach((array)$ret as $type=>$itemList){
            $t=[
                "servicetype"=>$type,//服务类型（API：API服务地址）|
                "serviceitems"=>[],//服务项目数组，每个元素都是一个Item，返回的列表以权重倒排，权重大的在前|
            ];
            /** @var SysServiceConf $item */
            foreach((array)$itemList as $item){
                $url=trim($item->src_url);
                if ($params["__source"]==APIDispatcher::SOURCE_TYPE_IOS && $params["__cliver"]<="1.1.0" && StringUtils::endsWith($url,"/api")){
                    $url=substr($url, 0, StringUtils::getStrLen($url)-4);
                }
                $temp=[
                    "url"=>$url,
                    "weight"=>(string)$item->src_weight,
                    ];
                $t["serviceitems"][]=$temp;
            }
            $result["services"][]=$t;
            $cnt=$result["count"]+1;
            $result["count"]=(string)$cnt;
        }
        return $result;
    }

    public function getName() {
        return "system.service.discovery.get";
    }

    public function getVersion() {
        return "1.0";
    }
}
