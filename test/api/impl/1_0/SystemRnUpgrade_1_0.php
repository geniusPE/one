<?php
/**
 * Created by PhpStorm.
 * User: phpkiller
 * Date: 2018/1/5
 * Time: 上午11:48
 */

/**
 * 检测RN更新包
 * Class SystemRnUpgrade_1_0
 */
class SystemRnUpgrade_1_0 extends AbstractAPIImpl implements API
{
    /** @var SystemDomain $systemDomain */
    private $systemDomain;
    /** @var QiniuResourceManager $resourceManager */
    private $resourceManager;

    public function init()
    {
        $this->systemDomain=Yii::app()->domainFactory->getSystemDomain();
        $this->resourceManager=Yii::app()->qiniuresourceManager->getInstance();
    }

    public function run($params)
    {
        if (!APIUtils::isParamExists($params["params"], "version", true))
            throw new APIException("未传入有效的版本号", APIException::$ERR_FORMAT_API);
        $version = $params["params"]["version"];
        $source=$params["__source"];
        $market=$params["__market"];
        $orgId=$params["__orgid"];
        $cliName=$params["__cliname"];
        $result[]= [
            "newversion"=>"0",
            "version"=>(string)$version,
            "type"=>"",
            "message"=>"",
            "url"=>"",
            "market"=>"00",
            "ver_tpe"=>"",
        ];
        if ($source==APIDispatcher::SOURCE_TYPE_IOS && $market=="00"){
            $market="AP";
        }
        $data = $this->systemDomain->getRnUpgradeByVersionAndSourceAndMarket($source, $version, $market, $orgId,
            $cliName);
        if ($data) {
            $result = [];
            foreach ($data as $cu){
                $result[] = [
                    'newversion' =>  '1',
                    'version'   => (string)$cu["cu_ver"],
                    'type'      => (string)$cu["cu_type"],
                    'message'   => $cu["cu_message"],
                    'url'       => $cu["cu_url"],
                    'market'    => $cu["cu_market"],
                    "ver_tpe"   => $cu["cu_ver_type"],
                ];
            }
        }
        return $result;
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getName()
    {
        return 'system.rn.upgrade.get';
    }
}