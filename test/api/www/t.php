<?php

include "inc/f.php";
require('./../../common/lib/vendor/autoload.php');
//require('./../../composer/vendor/autoload.php');

Yiinitializr\Helpers\Initializer::create('./../', 'api', array(
    __DIR__ .'/../../common/config/env.php',
    __DIR__ .'/../../common/config/main.php',
    __DIR__ .'/../../common/config/local.php'
));

$source = "2"; //0：browser，1：Android，2：IOS
$optionVistor = false; //设置是否是访客

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <script src="/js/jquery-1.8.3.js"></script>
    <script src="/js/api.js"></script>
    <title>测试页</title>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#v1" data-toggle="tab">1.0测试页</a></li>
            <li><a href="#v2" data-toggle="tab">2.0测试页</a></li>
            <li><a href="#test" data-toggle="tab">API测试页</a></li>
            <li><a href="#cashdesk" data-toggle="tab">新浪支付收银台</a></li>
            <li><a href="#testLogin" data-toggle="tab">测试登陆验证功能</a></li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="v1">
                <?php include "inc/v1.php"; ?>
            </div>
            <div class="tab-pane" id="v2">
                <?php include "inc/v2.php"; ?>
            </div>
            <div class="tab-pane" id="cashdesk">
                <div id="cashdesk_out1">
                    新浪支付收银台
                </div>

            </div>
            <div class="tab-pane" id="testLogin">
                <div id="testLogin_out1">
                    <form action="">
                        手机号：<input type="text" name="phone"><br>
                        <span class="message">验证码：<input type="text"><a href="" id="getVerify">获取短信验证码</a><br></span>
                        <span class="verifyCode" style="display: none">
                            <input type="text" name="verifycode" placeholder="请输入验证码">
                            <img src="" alt="" class="imgVerify" >
                            <input type="hidden" name="ticket" value="" class="ticket">
                            <input type="button" value="提交">
                        </span>
                        <br>
                    </form>
                </div>
            </div>
            <div class="tab-pane" id="test">
                    <span>
                        <form action="/api" method="GET">
                            <textarea name="c" value='' cols="130" rows="5"></textarea>
                            <br/>
                            <input type="submit" value="执行GET请求API" />
                        </form>
                        <br/>
                    </span>
                    <span>
                        <form action="/api" method="POST">
                            <textarea name="c" value='' cols="130" rows="5"></textarea>
                            <br/>
                            <input type="submit" value="执行POST请求API" />
                        </form>
                        <br/>
                    </span>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-1.8.3.js"></script>
<script src="js/bootstrap.js"></script>
<script>
   /* $('#getVerify').click(function(){
        var phone = $('input[name=phone]').val();
        if (phone == ''){
            alert("手机号不能为空");
            return false;
        }
        $.ajax({
            'url':'http://api.notice.dev:8080/host/getVerify',
            'type':'post',
            'data':{'phone':phone},
            'dataType':'json',
            success:function(msg){
                if (msg['url']){
                    $('.message').hide();
                    $('.verifyCode').show();
                    $('.imgVerify').attr('src',msg['url']);
                    $('.imgVerify').click(function(){
                        this.src = msg['url']+"?"+new Date().getTime();
                    })
                    $('.ticket').val(msg['ticket']);
                }
            }

        })
        return false;
    })
    $('input[type=button]').click(function(){
        var verifycode = $('input[name=verifycode]').val();
        var ticket     = $('input[name=ticket]').val();
        if (verifycode == '') {
            alert('请先输入验证码！');
        }
        $.ajax({
            'url':'http://api.notice.dev:8080/host/checkVerify',
            'type':'post',
            'data':{'verifycode':verifycode,'ticket':ticket},
            'dataType':'json',
            success:function(msg){
                if (msg){
                    alert(msg);
                }
            }

        })
    })*/
</script>

</body>
</html>
