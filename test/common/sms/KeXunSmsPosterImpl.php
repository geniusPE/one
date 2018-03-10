<?php

class KeXunSmsPosterImpl implements SmsPoster
{

    private $errMsgDict = [
        '1' => '消息包格式错误',
        '2' => 'IP鉴权错误',
        '3' => '账号密码不正确',
        '4' => '版本号错误',
        '5' => '其它错误',
        '6' => '接入点错误（如账户本身开的是CMPP接入）',
        '7' => '账号状态异常（账号已停用）',
        '21' => '连接过多',
        '100' => '系统内部错误，一般情况下例如：提交手机号码为 电信，但是该账号没用可用的电信接出点',
        '102' => '单次提交的号码数过多（建议200以内）',
        '10' => '原发号码错误，即extno错误',
        '15' => '余额不足',
        '17' => '账号签名无效',
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
        return $this->sendRet($this->post('send', $AuthSms, [
            'mobile' => $phone,
            'content' => $message
        ]));
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
        // 无语音短信
        $RetSendSms = new RetSendSms();
        $RetSendSms->status = Sms::SMS_STATUS_SEND_FAIL;
        $RetSendSms->exception = '此通道没有语音短信';
        return $RetSendSms;
    }

    private function sendRet($data)
    {
        $RetSendSms = new RetSendSms();
        $RetSendSms->unique_id = $data['taskID'];
        if ($data['returnstatus'] != 'Success') {
            $RetSendSms->status = Sms::SMS_STATUS_SEND_FAIL;
            $RetSendSms->code = $data['message'];
            $RetSendSms->exception = $this->errMsgDict[$data['message']];
        } else {
            $RetSendSms->status = Sms::SMS_STATUS_SEND_SUCCESS;
            $RetSendSms->code = 0;
        }
        return $RetSendSms;
    }

    private function post($action, AuthSms $AuthSms, $data = [])
    {
        $url = $AuthSms->url;
        list($account, $password) = explode(',', $AuthSms->account);
        $extno = $AuthSms->password;

        $data += [
            'action' => $action,
            'account' => $account,
            'password' => $password,
        ];
        if (in_array($action, ['send'])) {
            $data += [
                'extno' => $extno
            ];
        }
        Yii::app()->bizlogger->debug(json_encode($data));
        $HttpGatewayClient = new HttpGatewayClient(str_replace("{homeurl}", Yii::app()->getHomeUrl(), '{homeurl}/host/a814102ee3b211e5b294fb19de61cea2'));
        $ret = $HttpGatewayClient->request($url, "post", $data, 'HttpProxyFileGetContentImpl');
        $data = json_decode(json_encode(simplexml_load_string($ret)), true);
        return $data;
    }

    /**
     * 取短信状态回调内容
     * @param AuthSms $AuthSms
     * @return array
     */
    public function getStatusReport(AuthSms $AuthSms = null)
    {
        if (!isset($AuthSms)) return [];
        $data = $this->post('report', $AuthSms)['statusbox'] ?? [];
        Yii::app()->bizlogger->debug("请求科迅(状态报告)接口：" . APIUtils::jsonEncode($data));
        if (isset($data['status'])) {
            $data = [$data];
        }
        $list = [];
        foreach ($data as $info) {
            $list[] = [
                'sid' => $info['taskid'],
                'phone' => $info['mobile'],
                'recv_response_time' => strtotime($info['receivetime']),
                'code' => $info['errorcode'],
                'status' => $info['status'] == 10 ? SMS::SMS_STATUS_RES_SUCCESS : SMS::SMS_STATUS_RES_FAIL
            ];
        }
        return $list;
    }
}