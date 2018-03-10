<?php
$action = "/api";
$businessProcessDebugLoanId=217;
$bindingVisaCallbackInfo=array(
    "orderno"=>"S00032320116102844F",
    "validationcode"=>"716931",
);
$rechargeCallbackInfo=array(
    "orderno"=>"CA5319260915031090",
    "validationcode"=>"081943",
);

$下笔需要还款信息=[
    "欠费ID"=>3,
    "还款金额"=>1,
    "还款人ID"=>13,
];

?>
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#公共类API" data-toggle="tab">公共类API</a></li>
            <li><a href="#用户类API" data-toggle="tab">用户API</a></li>
            <li><a href="#借款类API" data-toggle="tab">借款API</a></li>
            <li><a href="#欠款类API" data-toggle="tab">欠款API</a></li>
            <li><a href="#投资类API" data-toggle="tab">投资API</a></li>
            <li><a href="#还款类API" data-toggle="tab">还款API</a></li>
            <li><a href="#担保类API" data-toggle="tab">担保API</a></li>
            <li><a href="#转账类API" data-toggle="tab">转账API</a></li>
            <li><a href="#支付网关类API" data-toggle="tab">支付网关API</a></li>
            <li><a href="#通知类API" data-toggle="tab">通知API</a></li>
            <li><a href="#功能测试API" data-toggle="tab">测试API</a></li>
            <li><a href="#存证测试API" data-toggle="tab">安存测试API</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="公共类API">

            </div>
            <div class="tab-pane" id="用户类API">
            </div>

            <div class="tab-pane" id="欠款类API">
            </div>
            <div class="tab-pane" id="投资类API">
            </div>
            <div class="tab-pane" id="还款类API">

            </div>
            <div class="tab-pane" id="担保类API">

            </div>
            <div class="tab-pane" id="转账类API">

            </div>

            <div class="tab-pane" id="支付网关类API">
            </div>
            <div class="tab-pane" id="通知类API">

            </div>

            <div class="tab-pane" id="功能测试API">
            </div>

            <div class="tab-pane" id="存证测试API">

                <span>
                    <form action="<?php echo $action; ?>" method="POST">
                        <input type="hidden" name="c" value='
                            <?php
                        $s=array(
                            "count"=>1,
                            "cliname"=>getCliName(),
                            "cliver"=>"1.0",
                            "sessionkey"=>getSessionKey("jim"),
                            "source"=>$source,
                            "uid"=>getUserId("jim"),
                            "reqs"=>array(
                                array(
                                    "version"=>"2.0",
                                    "name"=>"test.test.testmoney.post",
                                    "params"=>array(
                                        'money' => "1266600456.66",
                                    ),
                                ),
                            ),
                        );
                        echo json_encode($s);
                        ?>
                            '/>
                        <input type="submit" value="金额大写测试" />
                    </form>
                </span>

                <span>
                    <form action="<?php echo $action; ?>" method="POST">
                        <input type="hidden" name="c" value='
                            <?php
                        $s=array(
                            "count"=>1,
                            "cliname"=>getCliName(),
                            "cliver"=>"1.0",
                            "sessionkey"=>getSessionKey("jim"),
                            "source"=>$source,
                            "uid"=>getUserId("jim"),
                            "reqs"=>array(
                                array(
                                    "version"=>"2.0",
                                    "name"=>"push.ancun.certificate.post",
                                    "params"=>array(
                                        "userid" => '34',
                                        'pdfType' => 'consign',
                                        'data' => array(
                                            "jiafang"                   => "龙泉",
                                            "jiafangIdCard"             => "362402666666666666",
                                            "jiafangAddress"            => "浙江省杭州市西湖区宁波大厦",
                                            "yifang"                    => "杭州爱鑫品金融信息服务有限公司",
                                            "yifangIdCard"              => "B-123456",
                                            "borrower"                  => "张伟",
                                            "borrowerMoney"             => "5200000",
                                            "borrowerAddress"           => "北京市朝阳区望京SOHO",
                                            "borrowerEmail"             => "wlq314@qq.com",
                                            "borrowerPhoneNumber"       => "13340018888",
                                            "borrowerLandlinePhone"     => "18670886666",
                                            "borrowerUnitPhone"         => "0796-7762360",
                                            'sign_time'                 => date("Y年 m月 d日"),
                                        ),
                                    ),
                                ),
                            ),
                        );
                        echo json_encode($s);
                        ?>
                            '/>
                        <input type="submit" value="用户委托追偿协议推送测试" />
                    </form>
                </span>

                <span>
                <form action="<?php echo $action; ?>" method="POST">
                    <input type="hidden" name="c" value='
                            <?php
                    $s=array(
                        "count"=>1,
                        "cliname"=>getCliName(),
                        "cliver"=>"1.0",
                        "sessionkey"=>getSessionKey("jim"),
                        "source"=>$source,
                        "uid"=>getUserId("jim"),
                        "reqs"=>array(
                            array(
                                "version"=>"2.0",
                                "name"=>"push.ancun.certificate.post",
                                "params"=>array(
                                    "userid" => '34',
                                    'pdfType' => 'loan',
                                    'data' => array(
                                        "jiafang"                   => "龙泉",
                                        "jiafangIdCard"             => "362402666666666666",
                                        "jiafangAddress"            => "浙江杭州市西湖区",
                                        "jiafangEmail"              => "wlq314@qq.com",
                                        "jiafangPhoneNumber"        => "13340018888",
                                        "jiafangLandlinePhone"      => "18670668866",
                                        "jiafangUnitPhone"          => "0796-7777668",
                                        "yifang"                    => "哥哥",
                                        "yifangIdCard"              => "888866668888666688",
                                        "yifangAddress"             => "江西南昌昌平区江西农业大学",
                                        "yifangEmail"               => "longquangege@foxmail.com",
                                        "yifangPhoneNumber"         => "18270826101",
                                        "yifangLandlinePhone"       => "17212345678",
                                        "yifangUnitPhone"           => "0100-866116",
                                        "binfang"                   => "杭州爱鑫品金融信息服务有限公司",
                                        "binfangBusinessLincense"   => "D-QWER668",
                                        "LoanMoney"                 => "11111100.00",
                                        "LoanInterest"              => "3.5%",
                                        "LoanTime"                  => "60",
                                        'sign_time'                 => date("Y年 m月 d日"),
                                    ),
                                ),
                            ),
                        ),
                    );
                    echo json_encode($s);
                    ?>
                            '/>
                    <input type="submit" value="借款协议推送测试" />
                </form>
                </span>

                <span>
                    <form action="<?php echo $action; ?>" method="POST">
                        <input type="hidden" name="c" value='
                            <?php
                        $s=array(
                            "count"=>1,
                            "cliname"=>getCliName(),
                            "cliver"=>"1.0",
                            "sessionkey"=>getSessionKey("jim"),
                            "source"=>$source,
                            "uid"=>getUserId("jim"),
                            "reqs"=>array(
                                array(
                                    "version"=>"2.0",
                                    "name"=>"push.ancun.certificate.post",
                                    "params"=>array(
                                        "userid" => '34',
                                        'pdfType' => 'guarantee',
                                        'data' => array(
                                            "jiafang"                   => "龙泉",
                                            "jiafangIdCard"             => "362402666666666666",
                                            "jiafangAddress"            => "浙江杭州市西湖区",
                                            "jiafangEmail"              => "wlq314@qq.com",
                                            "jiafangPhoneNumber"        => "13340018888",
                                            "jiafangLandlinePhone"      => "18876609154",
                                            "jiafangUnitPhone"          => "02117758610",
                                            "yifang"                    => "哥哥",
                                            "yifangIdCard"              => "888866668888666688",
                                            "yifangAddress"             => "江西南昌昌平区江西农业大学",
                                            "yifangEmail"               => "longquangege@foxmail.com",
                                            "yifangPhoneNumber"         => "18270826101",
                                            "yifangLandlinePhone"       => "1667725860",
                                            "yifangUnitPhone"           => "0760-5532000",
                                            "binfang"                   => "杭州爱鑫品金融信息服务有限公司",
                                            "bingfangBusinessLicense"   => "bingfang666888",
                                            "LoanMoney"                 => "456000.00",
                                            "LoanInterest"              => "24%",
                                            "LoanTime"                  => "90",
                                            'sign_time'                 => date("Y年 m月 d日"),
                                        ),
                                    ),
                                ),
                            ),
                        );
                        echo json_encode($s);
                        ?>
                            '/>
                        <input type="submit" value="担保协议推送测试" />
                    </form>
                </span>

                 <span>
                    <form action="<?php echo $action; ?>" method="POST">
                        <input type="hidden" name="c" value='
                            <?php
                        $s=array(
                            "count"=>1,
                            "cliname"=>getCliName(),
                            "cliver"=>"1.0",
                            "sessionkey"=>getSessionKey("jim"),
                            "source"=>$source,
                            "uid"=>getUserId("jim"),
                            "reqs"=>array(
                                array(
                                    "version"=>"2.0",
                                    "name"=>"push.ancun.certificate.post",
                                    "params"=>array(
                                        "userid" => '34',
                                        'pdfType' => 'assign',
                                        'data' => array(
                                            "jiafang"                   => "龙泉",
                                            "jiafangIdCard"             => "362402666666666666",
                                            "jiafangAddress"            => "浙江杭州市西湖区",
                                            "jiafangEmail"              => "wlq314@qq.com",
                                            "jiafangPhoneNumber"        => "13340018888",
                                            "jiafangLandlinePhone"      => "17796011069",
                                            "jiafangUnitPhone"          => "0691-8864720",
                                            "yifang"                    => "哥哥",
                                            "yifangIdCard"              => "888866668888666688",
                                            "yifangAddress"             => "江西南昌昌平区江西农业大学",
                                            "yifangEmail"               => "longquangege@foxmail.com",
                                            "yifangPhoneNumber"         => "18270826101",
                                            "yifangLandlinePhone"       => "18270826111",
                                            "yifangUnitPhone"           => "0796-7735809",
                                            "binfang"                   => "杭州爱鑫品金融信息服务有限公司",
                                            "binfangBusinessLincense"   => "A-7893245122",
                                            "LoanMoney"                 => "888000.00",
                                            "LoanInterest"              => "20%",
                                            "LoanTime"                  => "90",
                                            'sign_time'                 => date("Y年 m月 d日"),
                                        ),
                                    ),
                                ),
                            ),
                        );
                        echo json_encode($s);
                        ?>
                            '/>
                        <input type="submit" value="债权转让推送测试" />
                    </form>
                </span>
            </div>

        </div>
    </div>
</div>
