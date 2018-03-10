<?php

interface SmsPoster
{

    /**
     * 发送短信
     * @param AuthSms $AuthSms
     * @param string $phone 手机号
     * @param string $message 短信内容
     * @return RetSendSms
     */
    public function sendSms(AuthSms $AuthSms, $phone, $message);

    /**
     * 发送语音
     * @param AuthSms $AuthSms
     * @param string $phone 手机号
     * @param string $message 短信内容
     * @return RetSendSms
     */
    public function sendVoice(AuthSms $AuthSms, $phone, $message);

    /**
     * 取短信状态回调内容
     * @param AuthSms $AuthSms
     * @return array
     */
    public function getStatusReport(AuthSms $AuthSms = null);
}