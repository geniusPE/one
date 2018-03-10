<?php

/**
 * 系统设置领域对象
 */
class SystemDomain extends AbstractDomain {
    /**
     * @param string $mode 运行模式
     * @return SysServiceConf[]
     * @throws APIException
     */
    public function getSystemServiceConfList($mode){
        if (!in_array($mode, [SysServiceConf::C_MODE_DEV, SysServiceConf::C_MODE_PROD]))
            throw new APIException("未传入有效的运行模式，必须是P/D之一", APIException::$ERR_BUSINESS_ILLEGAL);
        $cliName=APIUtils::getCliName();
        $orgId=APIUtils::getOrgId();
        $cdb=new CDbCriteria();
        $cdb->condition="cliname=:cliname
                     and org_id=:orgid
                     and src_mode=:mode";
        $cdb->params=["cliname"=>$cliName,
            "orgid"=>$orgId,
            "mode"=>$mode,];
        $cdb->order="src_type, src_weight desc";
        $result=SysServiceConf::model()->findAll($cdb);
        if (empty($result)){
            throw new APIException("没有发现可用的服务", APIException::$ERR_BUSINESS_ILLEGAL);
        }
        return $result;
    }

    /**
     * 根据来源/版本号/市场来获取客户端鞥新对象
     * @param string $source
     * @param string $currentVersion
     * @param string $market
     * @param string $orgId
     * @param string $cliName
     * @return array
     * @throws APIException
     */
    public function getClientUpgradeByVersionAndSourceAndMarket($source, $currentVersion, $market, $orgId, $cliName){
        $marketArray=[];
        if ($source==APIDispatcher::SOURCE_TYPE_ANDROID){
            if (!in_array($market, ClientUpgrade::ANDROID_MARKETS)){
                throw new APIException("[{$market}]是未知的Android市场",APIException::$ERR_BUSINESS_ILLEGAL);
            }
            $marketArray[]=$market;
        }else if ($source==APIDispatcher::SOURCE_TYPE_IOS){
            if (!in_array($market, ClientUpgrade::IOS_MARKETS)){
                throw new APIException("[{$market}]是未知的IOS市场",APIException::$ERR_BUSINESS_ILLEGAL);
            }
            $marketArray=ClientUpgrade::IOS_MARKETS;
        }else{
            throw new APIException("只有android/ios可以获取更新", APIException::$ERR_BUSINESS_ILLEGAL);
        }
        $markets=SQLUtils::getSqlInString($marketArray);
        $cu=null;
        if (!empty($marketArray)){
            $sql="select * from m_client_upgrade
             where cu_ver > :version
               and cu_source=:source
               and cu_market in ({$markets})
               and org_id=:orgid
               and cliname=:cliname
             order by cu_ver desc";
            /** @var ClientUpgrade $cu */
            $cu=ClientUpgrade::model()->findBySql($sql, [
                "version"=>$currentVersion,
                "source"=>$source,
                "orgid"=>$orgId,
                "cliname"=>$cliName,
            ]);
        }
        return $cu?$cu->attributes:[];
    }

    /**
     * 根据来源/版本号/市场来获取客户端鞥新对象
     * @param string $source
     * @param string $currentVersion
     * @param string $market
     * @param string $orgId
     * @param string $cliName
     * @return array
     * @throws APIException
     */
    public function getRnUpgradeByVersionAndSourceAndMarket($source, $currentVersion, $market, $orgId, $cliName){
        $marketArray=[];
        if ($source==APIDispatcher::SOURCE_TYPE_ANDROID){
            if (!in_array($market, ClientUpgrade::ANDROID_MARKETS)){
                throw new APIException("[{$market}]是未知的Android市场",APIException::$ERR_BUSINESS_ILLEGAL);
            }
            $marketArray[]=$market;
        }else if ($source==APIDispatcher::SOURCE_TYPE_IOS){
            if (!in_array($market, ClientUpgrade::IOS_MARKETS)){
                throw new APIException("[{$market}]是未知的IOS市场",APIException::$ERR_BUSINESS_ILLEGAL);
            }
            $marketArray=ClientUpgrade::IOS_MARKETS;
        }else{
            throw new APIException("只有android/ios可以获取更新", APIException::$ERR_BUSINESS_ILLEGAL);
        }
        $markets=SQLUtils::getSqlInString($marketArray);
        $cu=null;
        if (!empty($marketArray)){
            $sql="select * from m_rn_upgrade
             where cu_ver > :version
               and cu_source=:source
               and cu_market in ({$markets})
               and org_id=:orgid
               and cliname=:cliname
             order by cu_ver desc limit 1";
            /** @var ClientUpgrade $cu */
            $cu=MRnUpgrade::model()->findAllBySql($sql, [
                "version"=>$currentVersion,
                "source"=>$source,
                "orgid"=>$orgId,
                "cliname"=>$cliName,
            ]);
        }
        return $cu?$cu:[];
    }

    /**
     * 根据cliname获取更新包列表
     * @param $cliname
     * @param $cu_source
     * @return array
     */
    public function getClientUpgradeList($cliname,$cu_source){
        $result = ClientUpgrade::model()->findAll("cliname in $cliname and cu_source=$cu_source");
        foreach($result as $value){
            $data[] = $value->attributes;
        }
        return $data;
    }

    /**
     * 添加更新包
     * @param $data
     * @return bool
     */
    public function addClientUpgrade($data){
        $clientUpgrade = new ClientUpgrade;
        $clientUpgrade->cliname = $data['cliname'];
        $clientUpgrade->org_id = $data['org_id'];
        $clientUpgrade->create_time = $data['create_time'];
        $clientUpgrade->create_user = $data['create_user'];
        $clientUpgrade->cu_source = $data['cu_source'];
        $clientUpgrade->cu_market = $data['cu_market'];
        $clientUpgrade->cu_ver = $data['cu_ver'];
        $clientUpgrade->cu_url = $data['cu_url'];
        $clientUpgrade->cu_message = $data['cu_message'];
        $clientUpgrade->cu_type = $data['cu_type'];
        $ret = $clientUpgrade->save();
        return $ret;
    }

    public function updateClientUpgrade($data){
        $clientUpgrade = ClientUpgrade::model()->findByPk($data['cu_id']);
        $clientUpgrade->update_time = $data['update_time'];
        $clientUpgrade->update_user = $data['update_user'];
        $clientUpgrade->cu_ver = $data['cu_ver'];
        $clientUpgrade->cu_type = $data['cu_type'];
        $clientUpgrade->cu_url = $data['cu_url'];
        $clientUpgrade->cu_message = $data['cu_message'];
        $ret = $clientUpgrade->save();
        return $ret;
    }

    /**
     * 根据主键id查询更新包
     * @param $cu_id
     * @return mixed
     */
    public function getClientUpgradeById($cu_id){
        $sql = "SELECT * from m_client_upgrade where cu_id=$cu_id";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data;
    }

    public function deleteClientUpgrade($id){
        $model = ClientUpgrade::model()->findByPk($id);
        $ret = $model->delete();
        return $ret;
    }
}
