<?php

/**
 * 批量获取选项列表（system.multioptions.list.get）
 */
class SystemMultiOptionsList_1_0 extends AbstractAPIImpl implements API{
    /** @var OptionDomain $optionDomain */
    private $optionDomain;
    /* @var QiniuResourceManager $qiniuresourceManager */
    private $qiniuResourceManager;
    private $variables=[];

    public function init() {
        $this->optionDomain=Yii::app()->domainFactory->getOptionDomain();
        $this->qiniuResourceManager=Yii::app()->qiniuresourceManager->getInstance();
    }

    /**
     * @param QiniuResourceManager $rs
     * @param String $url
     * @return bool
     */
    public function isUrl($rs, $url){
        $result=!$rs->isRelactiveResource(strtolower($url));
        return $result;
    }

    /**
     * 解析普通文本选项
     * @param $value
     * @return mixed
     */
    protected function processTXTOption($value){
        return $value;
    }

    /**
     * 解析静态选项
     * @param string $value
     * @return string
     */
    protected function parserSTCOption($value){
        $result=$this->qiniuResourceManager->getResourceAbsoluteUrl($value);
        return $result;
    }

    /**
     * 解析动态选项
     * @param string $value
     * @return string
     */
    protected function parserDYNOption($value){
        $result=$value;
        if ($this->qiniuResourceManager->isRelactiveResource($value)){
            $result=$this->qiniuResourceManager->getResourceAbsoluteUrlEx(Yii::app()->homeUrl, $value);
        }

        $varList=$this->getVariableNames();
        foreach((array)$varList as $var){
            $varName=StringUtils::substr($var, 2, StringUtils::getStrLen($var)-2);
            $replaceItem="{".$varName."}";
            $replaceVal=$this->variables["__{$varName}"];
            if (!is_string($replaceVal)){
                continue;
            }
            $result=str_replace($replaceItem, $replaceVal, $result);
        }
        return $result;
    }

    /**
     * @return string[]
     */
    protected function getVariableNames(){
        $result=[];
        foreach((array)$this->variables as $varName=>$varValue){
            if (StringUtils::startWith($varName, "__")){
                $result[]=$varName;
            }
        }
        return $result;
    }

    /**
     * @param string $optionValueType
     * @param string $optionValue
     */
    protected function dispatchOptions($optionValueType, $optionValue){
        $result="";
        if ($optionValueType==Options::C_VALUE_TYPE_TXT){
            return $optionValue;
        }else if ($optionValueType==Options::C_VALUE_TYPE_STATIC){
            $result=$this->parserSTCOption($optionValue);
        }else if ($optionValueType==Options::C_VALUE_TYPE_DYNAMIC){
            $result=$this->parserDYNOption($optionValue);
        }
        return $result;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasVariable($name){
        $key="__".$name;
        $result=APIUtils::isParamExists($this->variables, $key, true);
        return $result;
    }

    public function run( $params ) {
        $this->variables=$params;
        if (!APIUtils::isParamExists($params["params"], "items", true))
            throw new APIException("未传入有效的项目数组", APIException::$ERR_BUSINESS_ILLEGAL);
        $items=$params["params"]["items"];

        if (!is_array($items)){
            throw new APIException("未传入有效的项目数组", APIException::$ERR_BUSINESS_ILLEGAL);
        }
        $list=[];
        foreach((array)$items as $item){
            $category=$item["category"];
            $optionArray=$item["optionarray"];
            if (!empty($optionNameArray) && !is_array($optionArray)){
                throw new APIException("未传入有效的选项数组", APIException::$ERR_BUSINESS_ILLEGAL);
            }
            $optionNameArray=empty($optionNameArray)?$optionNameArray:[];
            /** @var Options[] $tempList */
            $tempList=$this->optionDomain->getOptionsArrayByCategoryArrayAndOptionNameArray([$category], $optionNameArray,Options::C_SCOPE_FRONT_ONLY);
            $list=array_merge($list, $tempList);
        }

        $result=[
            "itemcount"=>(string)count($list),
            "items"=>[],
        ];

        /** @var Options $op */
        foreach((array)$list as $op){
            $opValue="";
            $opAddonValue="";
            if ($op->sops_value){
                $opValue=$this->dispatchOptions($op->sops_value_type, $op->sops_value);
            }

            if ($op->sops_addon_value){
                $opAddonValue = $this->dispatchOptions($op->sops_addon_value_type, $op->sops_addon_value);
            }
            $source=APIUtils::getDevSource();
            if ($op->so_category="banner" && $op->sops_name=="快乐钱包" && $source!=APIDispatcher::SOURCE_TYPE_IOS){
                $opValue="http://img.chnloan.com/Uploads/static/g/20160427/banner@3x.png";
                $opAddonValue="";
            }

            $result["items"][]=[
                "itemname"=>$op->sops_name,//选项名
                "itemvalue"=>$opValue,//选项值
                "itemaddon"=>$opAddonValue,//选项附加值
            ];
        }
        return $result;
    }

    public function getName() {
        return "system.multioption.list.get";
    }

    public function getVersion() {
        return "1.0";
    }
}
