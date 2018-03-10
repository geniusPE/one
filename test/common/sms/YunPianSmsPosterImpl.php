<?php

use Yunpian\Sdk\YunpianClient;
use Yunpian\Sdk\Constant\YunpianConstant;

class YunPianSmsPosterImpl implements SmsPoster
{

    /**
     * 发送短信
     * @param AuthSms $AuthSms
     * @param string $phone 手机号
     * @param string $message 短信内容
     * @return RetSendSms
     */
    public function sendSms(AuthSms $AuthSms, $phone, $message)
    {
        return $this->sendRet('sms', $AuthSms, [
            YunpianConstant::MOBILE => $phone,
            YunpianConstant::TEXT => $message
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
        return $this->sendRet('voice', $AuthSms, [
            YunpianConstant::MOBILE => $phone,
            YunpianConstant::CODE => $message
        ]);
    }

    private function sendRet($action, AuthSms $AuthSms, $message)
    {
        $client = YunpianClient::create($AuthSms->password);
        switch ($action) {
            case 'sms':
                $responseData = $client->sms()->single_send($message);
                break;
            case 'voice':
                $responseData = $client->voice()->send($message);
                break;
        }
        $RetSendSms = new RetSendSms();
        $RetSendSms->code = $responseData->code();
        $RetSendSms->unique_id = $responseData->data()['sid'] ?? 0;
        if ($RetSendSms->code != 0) {
            $RetSendSms->status = Sms::SMS_STATUS_SEND_FAIL;
            $RetSendSms->exception = StringUtils::contains($responseData->detail(), "mobile手机号格式不正确") ? '手机号格式不正确' : $responseData->detail();
        } else {
            $RetSendSms->status = Sms::SMS_STATUS_SEND_SUCCESS;
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
        Yii::app()->bizlogger->debug("收到云片回调：" . APIUtils::jsonEncode($_REQUEST));
        $sms_status = json_decode(urldecode($_REQUEST['sms_status']),true) ?? [];
        $list = [];
        foreach ($sms_status as $sms) {
            $report_status = $sms['report_status'];
            $list[] = [
                'sid' => $sms['sid'],
                'phone' => $sms['mobile'],
                'recv_response_time' => strtotime($sms['user_receive_time']),
                'code' => $sms['error_msg'],
                'status' => ($report_status == 'SUCCESS' || $report_status == '0') ? SMS::SMS_STATUS_RES_SUCCESS : SMS::SMS_STATUS_RES_FAIL
            ];
        }
        return $list;
    }
}