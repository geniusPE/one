<?php
/**
 * Created by PhpStorm.
 * User: phpkiller
 * Date: 2018/3/8
 * Time: 上午10:44
 */

class SiteController extends EController
{
    private $ips = [
        '127.0.0.1',
    ];

    public function beforeAction($action)
    {
        if (!in_array($this->ip(), $this->ips)) {
            throw new Exception("非法请求");
        }
        return parent::beforeAction($action);
    }

    public function actionSms(){
        $phone = Yii::app()->request->getParam('phone');
        print_r($phone);
        print_r($_POST);
        return '';
    }


    /** 认证 */
    public function actionInentify()
    {
        $res = '{"message": "查询成功", "code": "R0000", "data": {"category": 2, "score": 0, "descriptions": []}}';
        print_r($res);
        return $res;
    }

    /** 借款 */
    public function actionPayToCard()
    {
        $ret = '2eafd1fda5fe1019eb05e26bedac1e65ed62ffaadca91f8532d57700f89bfc9b0e6f7e8b03af491fa4960beb1b352b29c39d4d15
        be452a06c53bef0c8545ffa2df585a487d4e8842db698639b49993fd8e97929f2dbfe55f4fc50b8cdebdcedce4c7628d1433684c61f381ff
        a7799ef949a6838395bc84928a02c69dc8fad4b13417d235fe6f830d152db9c66e8dc1b4af2bc222b0e039342cb3956297e1755ea3d8d2ac
        1a138a419371e4d3a2a453da002d4a306ba899eecc4052793c77200b227dee68b0962dbad738819102e9d3d5f9a4466fa6da17cb570075b4
        92ae80ac376c9584c3eda6d7ca5cec5b668b01c27c26e04457cb1d16e1fa5a94fdf3f1d14b0df756c6fed3091a60f23a0745c1078342657
        0e4149f71cd4c6b8100f57a1df4cd636f2276f77baa24b6203b66d9c270ad5c4ea0a5041b708f1ecfb3802fb3e9b3712dde0f9dd5f3354e
        c5bb6a347621f9d3a0d9123e8fa3066c3bcc3d061f2857f2f080194c033e7d5a0a8941f9cf083af1f9719e4b70893de72e4defd4278f58f
        47804bb39dee774ca996959a70011f733a6ca1dcdd5d7cb401bc097c22b9ff485a65a0dd2bbfc13132c9b9bdf0a80f0388f3957bb56e16c
        1fc778de56a9d6e60a7954bb5a2a2a63571c5ec63deeb44faa0f4138337f6da4e04e0c60974270847fce88ff7c734d0f68b82c9f1cb5bc4
        fa8826f59cb7bb71d80f54a0a270324500d587502750ae97535dab4bc2fc0954c173fe605f4740e43ec42711f494d260d4cf61f3bbabc41
        18bee3288d7f5743a207c7d7797bbb9ea8347eed540aa6a97da632f502d1cb99a8146dfaa2e646e5187722128a62003bb893b702996d04f
        9dd75d9c687d1b76127825080165e9c17d7c1f250043642bb06bb3d2afd52aa';
//        {"trans_content":{"trans_reqDatas":[{"trans_reqData":{"to_acc_dept":"||工商银行","to_acc_name":"陈媛","to_acc_no":"6222023202047600290","trans_batchid":112958562,"trans_money":"8.50","trans_no":"PI43080949430318010871450","trans_orderid":129986535,"trans_summary":"借款[771][极速贷771号]借满，将8.50代付给借款人"}}],"trans_head":{"return_code":"0000","return_msg":"代付请求交易成功"}}}
        return $ret;
    }

    /** 还款 */
    public function actionRepay()
    {
        $ret = '93e8965f56674e5212e7bd169c1252a6ac33524b573d9276b584b49370fd340116434b88ae4b5322ba5500405b0bce849a177f5
        4070a7ea03541300ca7a84d5cc58f5affcaab073b3dddb3c39ec47e007e7ce34951ae75ec1643cd7d27a5fed6b6361ed03f666074c3f1f9
        1870bbce983edb90eb5f3949d76743cc4f428363b76b50d56c88c97763a919ed6c920b086ded7453564cf073e7047d7a775a11e642d1793
        ef608414234deb1734c58da7f2babf2e3922441c8df093895bc50bba34fcac02d3d25614b0679b1c250ba4dc1a888e532b10148ba85a3a5
        50df89dd6ac1cfdc34b736334f1fece5e7740934d10f2da77f8c7dd0f3cb486b3fe1ac19c4e935996f491276a1a16e510e37d0bb82c0b55
        3155dbf5a82cdc504d3aa8d38cd25a078de36bf92640b8c53076c0d29b68cc6a8766092c658be2896be3656584f9c182f1aa3e9f7459145
        aa4c5da5e9c7b734d4fc3ae9013c3087bc2d353bbe21ca2d0b13d507229f3c47fdf0003ddb15dfe4fdb2c8fbaab20cf0fede6db84b65498
        5fb4f0e386a753701951f775c59f8bc17bf5a171edf5f22d76c4836d76620d2e77591a04334637af8e3a5225cebb5b8aa409c47d3647b34
        ea7113647b049acde48c1e627b596bede8024f437f8624195f3b52aa5c87551150931c11a71ac9beccd697bbb7566f94a4748f63fb4e3c4
        549fb349b48dfdcf1f5d4fe8a8c5fe3e93f0402b8dbc773a79cfd06426f09dcc311d4134634db4851d2fb40aebcb1be9c88cfc19947909e
        2969a63221c45e5211142dfcb0201ca9687ea0e5260e7914bb3b82744af09020d85cfe2e525bacfa6e453429cd825d89ffe4435cd514fe0
        2106bdc7930e849bb1571f82aea60e9eb406149cb92b32a6f461d57f961b8dbd509';
//        {"additional_info":"手工还10.00元","biz_type":"0000","data_type":"json","member_id":"1160405","req_reserved":"","resp_code":"0000","resp_msg":"交易成功","succ_amt":"1000","terminal_id":"34388","trade_date":"20180308133522","trans_id":"RD35802318220318010860823","trans_no":"201803080110001751079847","trans_serial_no":"Z864223RD35802318220318010860823","txn_sub_type":"13","txn_type":"0431","version":"4.0.0.0"}
        return $ret;
    }

    /** 查询 */
    public function actionQuery()
    {
        $ret = '93e8965f56674e5212e7bd169c1252a6ac33524b573d9276b584b49370fd340116434b88ae4b5322ba5500405b0bce849a177f5
        4070a7ea03541300ca7a84d5cc58f5affcaab073b3dddb3c39ec47e007e7ce34951ae75ec1643cd7d27a5fed6b6361ed03f666074c3f1f9
        1870bbce983edb90eb5f3949d76743cc4f428363b733e6d534502de22e16e7721ed6bf8c4d61c5f03d25f1cd2438101d63bd4526ffaf637
        b70e10550e4518b7623555d67daaa4048eccf226ce55100c4d9a79a93fb05b77b1e99243ec2633e912e4ad10ad1b3665b09bb24fc96ad26
        06f738a252fea6ea4599dd5eae057076d4a7bc509a4286e4a75e9deb244d21baf4c17381629b50c0f86c0d76df4b1da67c807b85b5d28ed
        cf9b26c525fd3f26e41a9770176a63ac727764be78af47121e55d0ea82ee59a1c0ec76164057d655e1dd35ad5e76fdb09ffbfe5f2574bf0
        b781430ecd0f2695dd3320df4ff6f474bf0bbbefb881e1086bb079ab4db4abb4534028f63d13b658e10b8bde59e09da2818b7bba52d50b5
        a73691e270043ee9896e0b46e11722d9d0235be901184733bd68ff2cce2837782d5ca1a315dc4f83c4981e2511a11f9cfd828321fce1b30
        3e588891deb6f56724e9d7f35e9edae59b79ec548a7d2bd2fbbb2cc77c62c8f72282b3c4ba3c4ff03bb9f291a3b6b2fd36e8066dc14fd66
        19a3cecf02b2d58be901be63dd29d34f56e5e3a950a50400f3e4feed192009f934782052dabb89ff24c0ace027fbeece83ce0fd1341458b
        6630a4f3142b91a0ad3e009a26413c86d388c7a00eeebb53401ca3ded86191b06f12d06bdf533ab4c2e76c9b8c96e3f106416821a4d1b7a
        373c39290207f2cdd856eedf8f41b6477c5ef3764ea13c3b212579631cd1c3539166dbd296366d68e02b2ce53aac8190e3a6ad23ae98c47
        c831ec67549cc0680a0a33ba1adafc578115c38c74f1efd70f91336bdd3c1f61f11aa633618a63e50f4cdb72f05534527f1a900a07fe9aa
        9e18668fd7a063bf536fe7f6387328bfbb43522bcbe7889f176ab64f7ef19fcf4902b03aee0ccdc7cf1e4fd8e14ca61214ccc';
//        {"additional_info":"手工还10.00元","biz_type":"0000","data_type":"json","member_id":"1160405","order_stat":"S","orig_trade_date":"20180308133522","orig_trans_id":"RD35802318220318010860823","req_reserved":"","resp_code":"0000","resp_msg":"交易成功","succ_amt":"1000","terminal_id":"34388","trans_no":"201803080110001751079847","trans_serial_no":"Z654243RD35802318220318010860823","txn_sub_type":"31","txn_type":"0431","version":"4.0.0.0"}
        return $ret;
    }

    public function ip() {
        //strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $res =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
        return $res;
        //dump(phpinfo());//所有PHP配置信息
    }



}