<?php

/**
 * 演示用
 */
class TCommand extends AbstractConsoleCommand {
    /** @var CCache  $cache */
    private $cache;
    /** @var OptionDomain $optionDomain */
    private $optionDomain;
    /** @var NoticeDomain $noticeDomain */
    private $noticeDomain;
    public function init(){
        APIUtils::setCliName("CSL");
        APIUtils::setUid('256');
        APIUtils::setOrgId("100");

        $this->cache=Yii::app()->cache;
        $this->optionDomain=Yii::app()->domainFactory->getOptionDomain();
        $this->noticeDomain=Yii::app()->domainFactory->getNoticeDomain();
    }

    /**
     * @param array $args
     */
    public function run($args) {
//        echo (new Sms('KXUN'))->setStatusReport([]);
//        exit();
        APIUtils::getCliName();
        APIUtils::getOrgId();
        $userId = APIUtils::getUid();

        $sid = 20453627696;
        $phone='18297914203';
        $sql = "SELECT * FROM user_sms_record WHERE usmsr_unique_id='{$sid}' and  usmsr_status=1 and usmsr_phone=:phone";
        $UserSmsRecord = UserSmsRecord::model()->findBySql($sql,[':phone' => $phone]);
        print_r($UserSmsRecord);die;


        $this->cache->set('ab','123');
        $mobile = '18557532039';
        $sid = '16861550746';
        $deviceSource=9;
        $ret=$this->noticeDomain->pushMessage("NOT", "18621107160", "TT", "这是一个消息", [
            "loanid"=>"100",
        ], $deviceSource, "YOU",0);
        DIE;


//        $acIdArray=[46323,46325,46327,46329,46331,46333,46335,46337,46339,46341,46343,46345,46347,46349,46351,46353,46355,
//46357,46359,46361,46365,46367,46369,46371,46373,46377,46379,46381,46383,46385,46387,46389,46391,46393,
//46395,46397,46399,46401,46403,46405,46407,46409,46411,46413,46415,46417,46419,46421,46423,46425,46427,
//46429,46431,46433,46435,46437,46439,46441,46443,46445,46447,46449,46451,46453,46455,46457,46459,46461,
//46463,46465,46467,46469,46471,46473,46475,46477,46479,46481,46483,46485,46487,46489,46491,59303,59305,
//59307,59309,59311,59313,59315,59317,59319,59321,59327,59329,59331,59333,59335,59337,59339,59341,59343,
//59345,59347,59349,59351,59353,59355,59357,59359,59361,59363,59365,59367,59369,59371,59373,59375,59377,
//59379,59381,59383,59385,59387,59389,59391,59393,59395,59397,59399,59401,59403,59405,59407,59409,59411,
//59413,59415,59417,59419,59421,59423,59425,59427,59429,59433,59435,59437,59439,59441,59443,59445,59447,
//59449,59451,59455,59457,59459,59461,59463,59465,59467,59469,59471,59473,59475,59477,59479,59481,59483,
//59487,59489,59491,59493,59495,59497,59499,59501,59503,59505,59507,59509,59513,59515,59517,59519,59521,
//59523,59525,59527,59529,59531,59533,59535,59537,59539,59541,59543,59545,59547,59549,59551,59553,59555,
//59557,59559,59561,59563,59565,59569,59571,59573,59575,59577,59579,59581,59583,59585,59587,59589,59591,
//59593,59595,59597,59599,59601,59603,59605,59607,59609,59611,59613,59615,59617,59623,59625,59627,59629,
//59631,59633,59635,59637,59639,59641,59643,59645,59647,59649,59651,59653,59655,59657,59659,59661,59667,
//59669,59673,59675,59677,59681,59683,59685,59687,59689,59691,59693,59695,59697,59699,59701,59703,59705,
//59707,59709,59711,59713,59715,59719,59721,59723,59725,59727,59729,59731,59733,59735,59737,59739,59741,
//59743,59745,59747,59749,59751,59753,59755,59757,59759,59761,59763,59765,59767,59769,59773,59775,59777,
//59779,59781,59783,59785,59789,59791,59793,59795,59797,59799,59801,59803,59805,59807,59809,59811,59813,
//59815,59817,59819,59821,59823,59825,59827,59829,59831,59833,59835,59837,59839,59841,59843,59845,59847,
//59849,59851,59853,59855,59859,59861,60261,60317,60499,61595,61609,61615,61631,61641,61665,61765,61865,
//61867,61875];
//        ASyncRunner::batchRunAsyncCommand($acIdArray);
//        die;
//        $sign = '快乐钱包';
//        $code = rand(1000,9999);
//        $type = 'normal';
//        $phone = '18557532039';
//        $msg="尊敬的{$sign}用户，您本次的验证码为{$code}，120秒内有效，#b#的工作人员绝不会向您索取此验证码，切勿告知他人。";
////        $ret=$this->noticeDomain->postSMS($phone, $msg, $sign, '');
////        print_r($ret);
////        $ret = $this->noticeDomain->refreshVerifyCodeAndTicket($userId);
////        print_r($ret);
//
//        $userId = APIUtils::getUid();
//        $verifyCode = 'eyyf';
//        $ticket = '832770F29CE3FEC2A7B2A39730C0763123';

        $arr = array();
        $arr['userId'] = '23';
        $data['sign'] = '快乐钱包';
        $data['message'] = rand(1000,9999);
        $data['type'] = 'normal';
        $data['phone']= '18557532039';
        $data['message'] = "尊敬的{$data['sign']}用户，您本次的验证码为{$data['message']}，120秒内有效，{$data['sign']}的工作人员绝不会向您索取此验证码，切勿告知他人。";
        APIUtils::setOrgId(0);
        APIUtils::setCliName('CSL');

        $arr['ticket'] = '39BC9A395FEF679B3A49E2C4FCFE870523';
        $arr['verifyCode'] = 'hpc7';
        $arr['data'] = $data;
        $userinfo = new stdClass();
        $ret = $this->noticeDomain->test($userinfo,$data['phone'],$data['message']);
        print_r($ret);

        die;
        $ret = $this->noticeDomain->refreshVerifyCodeAndTicket();
        print_r($ret);

        $ret=$this->noticeDomain->postSMS("18557532039", $data['message'], $data['sign'],'normal');
        print_r($ret);
        die;
//        $ret = $this->noticeDomain->checkTicketAndVerifyCodeByUserId($arr['userId'],$arr['ticket'],$arr['verifyCode'],$data);

        $ret = $this->noticeDomain->checkTicketAndVerifyCodeByUserIdWithRpc($arr);
//        print_r($ret);

        $ret = $this->noticeDomain->refreshVerifyCode($userId);
        print_r($ret);




//        $sign=APIUtils::getOrgSign();
//        $code="123";
//        $msg="尊敬的{快乐钱包}用户，您本次的验证码为{$code}，120秒内有效，#b#的工作人员绝不会向您索取此验证码，切勿告知他人。";
//        $ret=$this->noticeDomain->postSMS("13573752816", $msg, $sign);
//        print_r($ret);
        $deviceSource=9;
        $ret=$this->noticeDomain->pushMessage("NOT", "18621107160", "TT", "这是一个消息", [
            "loanid"=>"100",
        ], $deviceSource, "YOU",0);
    }
}
