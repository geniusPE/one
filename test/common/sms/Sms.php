<?php

/**
 * 多通道短信入口
 * 1.根据ind自动选择短信平台
 * 2.每个短信平台实现相同功能时方法和参数都一样
 */
class Sms
{
    const SMS_STATUS_SEND_WAIT = '0'; // 等待发送
    const SMS_STATUS_SEND_SUCCESS = '1'; // 发送成功
    const SMS_STATUS_SEND_FAIL = '2'; // 发送失败
    const SMS_STATUS_RES_SUCCESS = '3'; // 接收成功
    const SMS_STATUS_RES_FAIL = '4'; // 接收失败

    const SMS_TYPE_TEXT = 'T'; // 短信
    const SMS_TYPE_VOICE = 'V'; // 语音

    const MAX_ROW_FAIL_TIMES = 3;

    /**
     * 短信通道工厂
     * @param $ind 短信类型 - 类似 验证码 催收 系统通知
     * @return SmsPosterImpl
     */
    public static function impl($ind)
    {
        return new SmsPosterImpl($ind);
    }

    /**
     * 判断用户是否需要换通道
     * @param string $sccId
     * @param string $phone 手机号
     * @return null | ScInfo
     */
    public function isChangeSmsConf($sccId, $phone)
    {
        $debugKey = "用户手机号:$phone, scc_id:$sccId,";
        $UserSmsConf = UserSmsConf::model()->find('scc_id = :scc_id and user_phone = :user_phone', [
            'scc_id' => $sccId,
            'user_phone' => $phone
        ]);
        if (!$UserSmsConf || $UserSmsConf->switch_times == 0) {
            Yii::app()->bizlogger->debug("$debugKey 不需要切换通道");
            return null;
        }
        $newSmsConf = $this->newSmsConf($sccId, $UserSmsConf->switch_times + 1);
        if (!$newSmsConf) {
            Yii::app()->bizlogger->debug("$debugKey 找不到新通道, 改用原通道 (按理说不会出现此情况, 设置switch_times的时候就已判断过)");
            return null;
        }
        Yii::app()->bizlogger->debug("$debugKey 需要切换到新通道");
        return $newSmsConf;
    }

    /**
     * 处理短信状态报告
     * @param $list 短信状态内容
     */
    public function setStatusReport($list)
    {
        Yii::app()->bizlogger->debug("处理后的短信通道回调内容：" . APIUtils::jsonEncode($list));
        foreach ($list as $info) {
            $sid = $info['sid'];
            $phone = $info['phone'];
            $sql = "SELECT * FROM user_sms_record WHERE usmsr_unique_id='{$sid}' and  usmsr_status=1 and usmsr_phone=:phone";
            $UserSmsRecord = UserSmsRecord::model()->findBySql($sql,[':phone' => $phone]);
//            $UserSmsRecord = UserSmsRecord::model()->find("usmsr_unique_id=:sid and usmsr_status=1 and usmsr_phone=:phone", [':sid' => $sid, ':phone' => $phone]);
            //如该短信的状态已经返回，则不修改
            if (!$UserSmsRecord) {
                continue;
            }
            $UserSmsRecord->usmsr_recv_response_time = $info['recv_response_time'];
            $UserSmsRecord->usmsr_code = $info['code'];
            $UserSmsRecord->usmsr_status = $info['status'];
            $UserSmsRecord->update();
            Yii::app()->bizlogger->debug("更新用户短信记录，数据内容：" . APIUtils::jsonEncode($UserSmsRecord->attributes));
            $this->recordReceiveFailTimes($info['status'], $UserSmsRecord->scc_id, $phone);
        }
    }

    /**
     * 记录接收短信失败次数 用于达到一定次数给用户换通道
     * @param $status
     * @param $sccId
     * @param $phone
     */
    private function recordReceiveFailTimes($status, $sccId, $phone)
    {
        $debugKey = "用户手机号:$phone, scc_id:$sccId,";
        $scInfo = SmsDomain::confCCacheLoader()->findEntityListByKeyValues([
            'scc_id' => $sccId,
        ])[0];
        $currentSccName = $scInfo['scc_name'];
        $sccNameEl = explode('-', $currentSccName);
        if (is_numeric(end($sccNameEl))) {
            array_pop($sccNameEl);
            $scInfo = SmsDomain::confCCacheLoader()->findEntityListByKeyValues([
                'cliname' => $scInfo['cliname'],
                'org_id' => $scInfo['org_id'],
                'scc_type' => $scInfo['scc_type'],
                'scc_name' => implode('', $sccNameEl)
            ])[0];
            $sccId = $scInfo['scc_id'];
            $debugKey .= " 默认scc_id:$sccId,";
        }
        $defaultSccName = $scInfo['scc_name'];
        $UserSmsConf = UserSmsConf::model()->find("scc_id = :scc_id and user_phone = :user_phone", [
            'scc_id' => $sccId,
            'user_phone' => $phone
        ]);
        if (!$UserSmsConf) {
            $UserSmsConf = new UserSmsConf();
            $UserSmsConf->scc_id = $sccId;
            $UserSmsConf->user_phone = $phone;
            $UserSmsConf->switch_times = 0;
            $UserSmsConf->row_fail_times = 0;
        }
        $trueSccName = $defaultSccName . ($UserSmsConf->switch_times > 0 ? '-' . ($UserSmsConf->switch_times + 1) : '');
        if ($trueSccName != $currentSccName) {
            Yii::app()->bizlogger->debug("$debugKey 当前需要记录的通道和日志的通道不一样, 不对记录进行修改");
            return;
        }
        if($status == Sms::SMS_STATUS_RES_SUCCESS) {
            $UserSmsConf->row_fail_times = 0;
            $UserSmsConf->save();
            return;
        }
        $UserSmsConf->row_fail_times += 1;
        Yii::app()->bizlogger->debug("$debugKey 连续{$UserSmsConf->row_fail_times}次没收到短信, 最多连续" . self::MAX_ROW_FAIL_TIMES . "次没收到短信会切换通道, 切换次数(循环):$UserSmsConf->switch_times, 切换次数(累计):$UserSmsConf->switch_times_count");
        if ($UserSmsConf->row_fail_times < self::MAX_ROW_FAIL_TIMES) {
            // 连续失败次数没到设置的点
            $UserSmsConf->save();
            return;
        }
        // 连续失败次数到了设置的点
        $UserSmsConf->switch_times += 1;
        $UserSmsConf->switch_times_count += 1;
        $UserSmsConf->row_fail_times = 0;
        $newSmsConf = $this->newSmsConf($sccId, $UserSmsConf->switch_times + 1);
        if (!$newSmsConf) {
            Yii::app()->bizlogger->debug("$debugKey 没有备选通道了, 切换到默认通道");
            $UserSmsConf->switch_times = 0;
        } else {
            Yii::app()->bizlogger->debug("$debugKey 成功切换到{$newSmsConf['scc_name']}");
        }
        $UserSmsConf->save();
    }

    /**
     * 获取新的短信配置
     * @param $sccId
     * @param $switchTimes 切换次数
     * @return mixed
     */
    private function newSmsConf($sccId, $switchTimes)
    {
        $currentScInfo = SmsDomain::confCCacheLoader()->findEntityListByKeyValues([
            'scc_id' => $sccId,
        ])[0];
        return SmsDomain::confCCacheLoader()->findEntityListByKeyValues([
            'cliname' => $currentScInfo['cliname'],
            'org_id' => $currentScInfo['org_id'],
            'scc_type' => $currentScInfo['scc_type'],
            'scc_name' => $currentScInfo['scc_name'] . '-' . $switchTimes
        ])[0];
    }
}

class SmsPosterImpl
{

    /** @var SmsPoster $channel */
    private $channel;

    // 短信通道标识 根据这个值选择短信平台
    private $ind;

    // 短信通道标识的字典 用于选择对应的类
    private $indDict = [
        'YUNP' => 'YunPianSmsPosterImpl', // 云片
        'YUNR' => 'YunRongSmsPosterImpl', // 云融
        'KXUN' => 'KeXunSmsPosterImpl', // 科迅
        'MENG' => 'MengWangSmsPosterImpl' // 梦网
    ];

    public function __construct($ind)
    {
        $this->ind = $ind;
        $className = $this->indDict[$ind];
        $this->channel = new $className();
    }

    /**
     * 发送短信
     * @param AuthSms $AuthSms
     * @param string $sccId
     * @param string $type 短信类型语音或文本
     * @param string $phone 手机号
     * @param string $message 短信内容
     * @throws APIException
     * @return RetSendSms
     */
    public function sendSms(AuthSms $AuthSms, $sccId, $type, $phone, $message)
    {
        Yii::app()->bizlogger->debug("发送短信,渠道=$this->ind,类型=$type");
        $usmsr = new UserSmsRecord();
        $usmsr->scc_id = $sccId;
        $usmsr->usmsr_phone = $phone;
        $usmsr->usmsr_message = $message;
        $usmsr->usmsr_status = Sms::SMS_STATUS_SEND_WAIT;
        $usmsr->usmsr_vendor = $this->ind;
        $usmsr->usmsr_sendttime = time();
        $usmsr->cliname = APIUtils::getCliName();
        $usmsr->org_id = APIUtils::getOrgId();
        $usmsr->create_user = APIUtils::getUid() ?: 0;
        $usmsr->create_time = time();
        $ret = $usmsr->save();
        if (!$ret) {
            throw new APIException(ModelUtils::getErrorMessage($usmsr), APIException::$ERR_API_INTERNALERROR);
        }
        switch ($type) {
            case Sms::SMS_TYPE_TEXT:
                $RetSendSms = $this->channel->sendSms($AuthSms, $phone, $message);
                break;
            case Sms::SMS_TYPE_VOICE:
                $RetSendSms = $this->channel->sendVoice($AuthSms, $phone, $message);
                break;
            default:
                throw new APIException('短信通道类型错误', APIException::$ERR_API_INTERNALERROR);
        }
        $usmsr->usmsr_exception = $RetSendSms->exception ?? '提交成功';
        $usmsr->usmsr_status = $RetSendSms->status;
        $usmsr->usmsr_code = $RetSendSms->code;
        $usmsr->usmsr_unique_id = $RetSendSms->unique_id ?: 0;
        $usmsr->usmsr_send_response_time = time();
        $usmsr->usmsr_recv_response_time = 0;
        $ret = $usmsr->save();
        if (!$ret) {
            throw new APIException(ModelUtils::getErrorMessage($usmsr), APIException::$ERR_BUSINESS_ILLEGAL);
        }
        Yii::app()->bizlogger->debug("短信返回值=" . APIUtils::jsonEncode($RetSendSms));
        return $RetSendSms;
    }

    /**
     * 处理短信状态报告
     * @param AuthSms $AuthSms
     */
    public function setStatusReport(AuthSms $AuthSms = null)
    {
        (new Sms())->setStatusReport($this->channel->getStatusReport($AuthSms));
    }
}


/**
 * 发送消息后用来存结果的对象
 */
class RetSendSms
{
    public $code = 0;
    public $unique_id;
    public $exception;
    public $status;
}

/**
 * 短信通道的身份认证信息
 */
class AuthSms
{
    public $url;
    public $account;
    public $password;

    public function __construct($url, $account, $password)
    {
        $this->url = $url;
        $this->account = $account;
        $this->password = $password;
    }
}