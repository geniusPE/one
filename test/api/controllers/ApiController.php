<?php

class ApiController extends CController implements APIDispatcherCallback {
    const C_DUP_REQUEST_FREEZE_MSEC=1;
    /** @var  APIDispatcher $$dispatcher */
    private $dispatcher;

    /** @var  CDiaryRollLogger $bizLogger */
    private $bizLogger;


    /**
     * @return CDiaryRollLogger
     */
    public function getBizLogger(){
        if (!$this->bizLogger){
            $this->bizLogger=Yii::app()->bizlogger;
        }
        return $this->bizLogger;
    }


    public function init() {
        $this->dispatcher = new APIDispatcher($this);
        $this->dispatcher->init();//初始化dispatcher
        $apiRegisterList=Yii::app()->params["swoole.config"]["API"]["REGISTER"];
        foreach((array)$apiRegisterList as $apiRegister){
            /** @var APIRegister $apiRegister */
            $apiRegister=new $apiRegister();
            $apiRegister->registerAPIs($this->dispatcher);
        }
        $this->dispatcher->init();//初始化dispatcher
     }

    /**
     * 处理API请求
     */
    public function actionIndex(){

        try{
            if (Yii::app()->hasComponent("apiprofiler") && Yii::app()->apiprofiler->isEnabled())
                Yii::app()->apiprofiler->beginProfile();
            $this->dispatcher->setJsonResponseHeader();

            $c=Yii::app()->request->getParam("c");
            $resp=$this->dispatcher->dispatcherAction($c);

            if (Yii::app()->hasComponent("apiprofiler") && Yii::app()->apiprofiler->isEnabled()){
                Yii::app()->apiprofiler->endProfile();
                $resp["profiler"]=Yii::app()->apiprofiler->getProfilerReportUrl();
            }
            $result =$resp;
            echo $result;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * 请求网关
     *
     * 参数
     * |名称|类型|说明|
     * |:-|:-|:-|
     * |t|String|模式（1：格式化的参数，2：无格式化参数）|
     * |url|String|转向地址|
     * |fdata|mixed|格式化参数，只有t=1时有效|
     * |m|String|方法（get/post），只有t=2时有效|
     * |data|mixed|无格式化参数，只有t=2时有效|
     * |header|String|请求头，只有t=2时有效|
     * @throws APIException
     */
    public function actionGateway(){
        Yii::app()->params["source"]="gateway";
        $httpClient=new HttpClient();
        $t=Yii::app()->request->getParam(HttpGatewayClient::URL_KEY_NAME);
        $url=unserialize(urldecode($t));
        if (!$url)
            throw new APIException("未传入有效的代理转向url", true);
        $m=Yii::app()->request->getParam(HttpGatewayClient::METHOD_KEY_NAME);
        $type=Yii::app()->request->getParam(HttpGatewayClient::TYPE_KEY_NAME);
        if ($type==1){
            $tmp=Yii::app()->request->getParam(HttpGatewayClient::FDATA_KEY_NAME);
            $fdata=unserialize(urldecode($tmp));
            $this->dispatcher->getBizLogger()->debug("[API Gateway]->type={$type},method={$m},参数：url={$url},args=".APIUtils::jsonEncode($fdata));
            $response=$httpClient->post($url, $fdata);
        }else if ($type==2){
            $tmp=Yii::app()->request->getParam(HttpGatewayClient::DATA_KEY_NAME,"");
            $data=$tmp?unserialize(urldecode($tmp)):"";
            $headers=unserialize(urldecode(Yii::app()->request->getParam(HttpGatewayClient::HEAD_KEY_NAME, urlencode(serialize([])))));
            $this->dispatcher->getBizLogger()->debug("[API Gateway]->type={$type},method={$m},参数：url={$url},args=".$data);
            $response=$httpClient->http($url, $m, $data, $headers);
        }else
            throw new APIException("未传入有效的类型参数", APIException::$ERR_BUSINESS_ILLEGAL);
        $this->dispatcher->getBizLogger()->debug("[API Gateway]<-{$response}");
        echo $response;
    }

    /**
     * @inheritDoc
     */
    public function beforeDispatcherAction($actionParams)
    {
        // TODO: Implement beforeDispatcherAction() method.
    }

    /**
     * @inheritDoc
     */
    public function afterDispatcherAction(&$resp)
    {
        // TODO: Implement afterDispatcherAction() method.
    }

}
