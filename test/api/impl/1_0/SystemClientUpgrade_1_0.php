<?php

/**
 * 检查客户端是否有更新版本（system.client.upgrade.get）
 * http://img.dianshangwen.com/Uploads/app/f/dianshangwen.apk
 * @author 韩晓峰
 *
 */
class SystemClientUpgrade_1_0 extends \AbstractAPIImpl implements \API {
    /** @var SystemDomain $systemDomain */
    private $systemDomain;
    /** @var QiniuResourceManager $resourceManager */
    private $resourceManager;

    public function init(){
	    $this->systemDomain=Yii::app()->domainFactory->getSystemDomain();
        $this->resourceManager=Yii::app()->qiniuresourceManager->getInstance();
    }

	public function getVersion() {
		return "1.0";
	}

	public function getName() {
		return "system.client.upgrade.get";
	}

    public function run($params) {
        if (!APIUtils::isParamExists($params["params"], "version", true))
            throw new APIException("未传入有效的版本号", APIException::$ERR_FORMAT_API);
        $version = $params["params"]["version"];
        $source=$params["__source"];
        $market=$params["__market"];
        $orgId=$params["__orgid"];
        $cliName=$params["__cliname"];
        $result = [
            "newversion"=>"0",
            "version"=>(string)$version,
            "type"=>"",
            "message"=>"",
            "url"=>"",
            "market"=>"00",
        ];
        if ($source==APIDispatcher::SOURCE_TYPE_IOS && $market=="00"){
            $market="AP";
        }
        $cu=$this->systemDomain->getClientUpgradeByVersionAndSourceAndMarket($source, $version, $market, $orgId, $cliName);
        if ($cu){
            $t = trim($cu["cu_url"]);
            if ($source==APIDispatcher::SOURCE_TYPE_ANDROID && !$t)
                return $result;
            if ($this->resourceManager->isRelactiveResource($t)){
                $url = $this->resourceManager->getResourceAbsoluteUrl($t);
            }else{
                $url=$t;
            }
            $result["newversion"] = "1";
            $result["version"] = (string)$cu["cu_ver"];
            $result["type"] = (string)$cu["cu_type"];
            $result["message"] = $cu["cu_message"];
            $result["url"] = $url;
            $result["market"]=$cu["cu_market"];
        }
        return $result;
    }
}
