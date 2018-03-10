<?php

class YunRongSmsPosterImpl implements SmsPoster
{

    private $errMsgDict = [
        '-9' => '用户名为空（可能提交的请求可是有误，系统这次GET和POST两种请求）',
        '-1' => '用户名或口令错误',
        '-2' => 'IP验证错误',
        '-3' => '定时日期错误',
        '-10' => '余额不足',
        '-101' => 'userId为空',
        '-102' => '目标号码为空',
        '-103' => '内容为空',
        '-104' => '群发手机号码大于200个或短信群发号码个数不能大于100条',
        '200' => '目标号码错误',
        '201' => '目标号码在黑名单中',
        '202' => '内容包含敏感单词',
        '203' => '特服号未分配',
        '204' => '优先级错误(可以不传只进行发送)或分配通道错误',
        '999' => '其他异常',
        '-999' => '其它异常，短信内容可能为空'
    ];

    /**
     * 发送短信
     * @param AuthSms $AuthSms
     * @param string $phone 手机号
     * @param string $message 短信内容
     * @return RetSendSms
     */
    public function sendSms(AuthSms $AuthSms, $phone, $message)
    {
        return $this->sendRet('sendMessage', $AuthSms, UUIDUtils::generateKey(), [
            'mobilePhone' => $phone,
            'body' => $message
        ]);
    }

    /**
     * 发送语音
     * @param AuthSms $AuthSms
     * @param string $phone 手机号
     * @param string $message 短信内容
     * @return RetSendSms
     */
    public function sendVoice(AuthSms $AuthSms, $phone, $message)
    {
        return $this->sendRet('sendAudioMessage', $AuthSms, UUIDUtils::generateKey(), [
            'mobilePhone' => $phone,
            'body' => $message
        ]);
    }

    private function sendRet($cmd, AuthSms $AuthSms, $uniqueId, $data)
    {
        $url = $AuthSms->url;
        $account = $AuthSms->account;
        $password = $AuthSms->password;
        
        $data += [
            'cmd' => $cmd,
            'userName' => $account,
            'passWord' => $password,
            'messageId' => $uniqueId,
            'serviceCode' => "0341"
        ];
        Yii::app()->bizlogger->debug(json_encode($data));
        $HttpGatewayClient = new HttpGatewayClient(str_replace("{homeurl}", Yii::app()->getHomeUrl(), '{homeurl}/host/a814102ee3b211e5b294fb19de61cea2'));
        $ret = $HttpGatewayClient->request($url, "post", $data, 'HttpProxyFileGetContentImpl');
        $data = json_decode($ret);
        $RetSendSms = new RetSendSms();
        $RetSendSms->unique_id = $uniqueId;
        if ($data->resultCode != 0) {
            $RetSendSms->status = Sms::SMS_STATUS_SEND_FAIL;
            $RetSendSms->code = $data->errorCode;
            $RetSendSms->exception = $this->errMsgDict[$data->errorCode];
        } else {
            $RetSendSms->status = Sms::SMS_STATUS_SEND_SUCCESS;
            $RetSendSms->code = 0;
        }
        return $RetSendSms;
    }

    /**
     * 取短信状态回调内容
     * @param AuthSms $AuthSms
     * @return array
     */
    public function getStatusReport(AuthSms $AuthSms = null)
    {
        Yii::app()->bizlogger->debug("收到云融(状态报告)回调：" . APIUtils::jsonEncode($_REQUEST));
        $list = [];
        $i = 0;
        while ($i++ < ($_REQUEST['messageQty'] ?: 1)) {
            $list[] = [
                'sid' => $_REQUEST["submitMessageId$i"],
                'phone' => $_REQUEST["mobilePhone$i"],
                'recv_response_time' => strtotime($_REQUEST["dateTimeStr$i"]),
                'code' => $_REQUEST["deliveryStatusCode$i"],
                'status' => $_REQUEST["deliveryStatus$i"] == "true" ? SMS::SMS_STATUS_RES_SUCCESS : SMS::SMS_STATUS_RES_FAIL
            ];
        }
        return $list;
    }
}