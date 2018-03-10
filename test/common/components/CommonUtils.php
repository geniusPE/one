<?php
/**
 * Author: ConnorCho(caokang@foxmail.com)
 * User: connor
 * Date: 14/10/11 11:47
 * Source: CommonUtils.php
 */

class CommonUtils {

    public static function WrapperVar($var)
    {
        return isset($var)?$var:'';
    }

    /**
     * @param $var
     * @return array|string
     * trim数组所有值
     */
    public static function WrapperTrim($var)
    {
        function walk($var)
        {
            if (!is_array($var))
                return trim($var);

            return array_map(__FUNCTION__, $var);
        };
         return walk($var);

    }

    /**
     * 统一trim输入内容
     */
    public static function trimPostGet()
    {
        if(!empty($_POST))
        array_walk_recursive($_POST, function (&$val) { $val = trim($val); });
        if(!empty($_GET))
        array_walk_recursive($_GET, function (&$val) { $val = trim($val); });
    }

    /**
     * @param $img_url
     * @return string
     * 返回用户头像的完整链接地址
     */
    public static function avatar($img_url)
    {
        return $img_url?CommonUtils::geRealUrl($img_url):'/images/app_logo.png';
    }

    /**
     * @param $user 可以是AR也可以是user_id
     * @param bool $is_text
     * @param array $htmlOptions
     * @param string $username
     * @param string $img_url
     * * @param string $flag
     * @return string
     * 返回用户空间链接
     */
    public static function spaceLink($user,$is_text=true,$htmlOptions=[],$username='',$img_url='',$flag=null)
    {
        $tmp = $user;

        $htmlOptions=!empty($htmlOptions)?$htmlOptions:['width'=>64,'height'=>64];

        $trigger = is_object($user);

        if($is_text==true)
        {
            if($trigger===false && $username=='')
            {
                $user = MUser::model()->findByPk($user);
                $trigger = true;
            }

            $title = $text = $trigger?CommonUtils::encodeVar($user->nick_name):$username;
            $htmlOptions['encode'] = true;

        }

        else
        {
            if($trigger===false && $img_url=='')
            {
                $user = MUser::model()->findByPk($user);
                $trigger = true;
            }

            $title = $username?:($trigger?$user->nick_name:'');
            $head_img = $img_url?:$user->head_img;
            $text = CHtml::image(self::avatar($head_img),'头像',$htmlOptions);
        }
        $user_id = ($trigger&&$user)?$user->user_id:$tmp;
        $htmlOptions['title']=$title;
        $url = array('account/detail','id'=>$user_id);
        if($flag==1 || $user_id=='0')
            $url='javascript:;';

        return self::absoluteLink($text,$url,$htmlOptions);
    }

    /**
     * @param $var 要输出的原始内容(除了换行)
     * 安全输出内容
     */
    public static function WrapperEcho($var,$isreturn = false)
    {
        $str = str_replace(array("\r\n", "\r", "\n"), "<br />", htmlentities($var));
        if($isreturn) {
            return $str;
        }
        echo $str;

    }

    /**
     * @param $var
     * @param bool $strip_tags
     * @return string
     * encode内容并返回
     */
    public static function encodeVar($var,$strip_tags=false)
    {
        $var = $strip_tags?strip_tags($var):$var;
        return str_replace(array("\r\n", "\r", "\n"), "<br />",CHtml::encode(trim($var)));
    }


    /**
     * @param $e
     * @return string
     * Debug模式下返回Exception或Ar错误的具体信息
     */
    public static function errorMsg($e)
    {
        $msg = '';
//        if(YII_DEBUG) {
            if ($e instanceof Exception)
                $msg = $e->getMessage();
            elseif
            ($e instanceof CActiveRecord && !empty($e->errors))
            {
                $errors = current($e->errors);
                $msg = $errors[0];
            }

//        }
        return $msg;

    }

    /**
     * 格式化输出的文字
     * @param $str
     * @param int $length
     * @param string $encoding
     */
    public static function formatMbSubstr($str,$length=5,$encoding='utf-8') {
        if(($substr = mb_substr($str,0,$length,$encoding)) == $str)
            echo  $str;
        else
            echo $substr . '...';
    }


    /**
     * @param $url
     * @return string
     * 获取dl文件的可用url
     */
    public static function geRealUrl($url)
    {

        if(substr($url,0,7)!='http://')
        {
            $url = Yii::app()->params['dl.url'].$url;
        }
        $pos = strrpos($url,'/');
        return substr($url,0,$pos+1).urlencode(substr($url,$pos+1));
    }

    /**
     * @param string $unixTime
     * @return string
     * 获取星期几信息
     */
    public static function getWeek($unixTime=''){
        $unixTime=is_numeric($unixTime)?$unixTime:time();
        $weekarray=array('日','一','二','三','四','五','六');
        return '星期'.$weekarray[date('w',$unixTime)];
    }

    public static function loginDialog()
    {
        if(Yii::app()->user->isGuest) echo ' data-toggle="modal" data-target="#login_modal" ';
    }

    public static function formatBytes($byte)
    {
        return  Yii::app()->format->size($byte);
    }

    /**
     * 根据某个尺寸名字获取其他尺寸图片名
     * echo CommonUtils::getImgName('/123/33q_50x73.jpg',10,15);
     * @param $sourceName
     * @param $width
     * @param $height
     * @return mixed
     */
    public static function getImgName($sourceName,$width=64,$height=64)
    {
        $tmp = parse_url($sourceName);
        $sourceName = $tmp['path'];
        $ral =Yii::app()->params['dl.path'].$sourceName;
        $new_img = preg_replace('/_\d+x\d+./i','_'.$width.'x'.$height.'.',$sourceName);
        if(!file_exists(Yii::app()->params['dl.path'].$new_img))
        {
            if(file_exists($ral))
            {
                $resourse = Yii::app()->resourceManager->getInstance();
                $d=$du='';
                $resourse->resizeImage($ral,$width,$height,$d,$du,null);
            }

        }
        return $tmp['host']?$tmp['scheme'].'://'.$tmp['host'].$new_img:$new_img;
    }

    /**
     * 获取地址路径
     * @param $imgPath
     *
     * @return string
     */
    static public function getImageAbsolutePath($imgPath=''){
        if($imgPath == '') return '';
        /* @var ResourceManager $rs*/
        $rs=Yii::app()->resourceManager->getInstance();
        return $rs->getResourceAbsoluteUrl($imgPath);
    }

    /**
     * 获取头像的地址路径
     * @param string $imgPath
     * @return string
     */
    static public function getHeadImgAbsolutePath($imgPath='') {
        if($imgPath == '') return '/images/default.svg';
        /* @var ResourceManager $rs*/
        $rs=Yii::app()->resourceManager->getInstance();
        return $rs->getResourceAbsoluteUrl($imgPath);
    }

    /**
     * 解析ar参数
     * @param $model
     * @return array
     */
    public static function extractModelAttributes($model)
    {
        $params = [];
        if($model instanceof CActiveRecord)
            $params = $model->attributes;

        return $params;
    }

    public static function absoluteLink($text, $url='#', $htmlOptions=array())
    {
        if($url!=='')
            $htmlOptions['href']=self::absoluteUrl($url);
        return CHtml::tag('a',$htmlOptions,$text);
    }

    public static function absoluteUrl($url)
    {
        $turl = Yii::app()->request->getHostInfo('');
        if(is_string($url)&&stripos($url,'javascript:')==0)
            $turl = '';
        return $turl.CHtml::normalizeUrl($url);
    }

    public static function getDocComment($str, $tag = '')
    {
        if (empty($tag))
        {
            return $str;
        }

        $matches = array();
        preg_match("/".$tag."(.*)(\\r\\n|\\r|\\n)/U", $str, $matches);

        if (isset($matches[1]))
        {
            return trim($matches[1]);
        }

        return '';
    }

    /**
     * 格式化输出昵称
     * 尚未处理
     * @param $nickname
     */
    public static function echoNickname($nickname){
        echo $nickname;
    }


    public static function http_request($url, $args = '', $method = 'post', $cookiejar = false, $cookiefrash = false)
    {
        if ($method == 'get') {
            if (is_array($args)) {
                foreach ($args as $k => $v)
                    $url .= '&' . $k . '=' . $v;
            } else
                $url .= '&' . $args;
        }

        $ch = curl_init($url);
        if ($cookiejar) {
            if ($cookiefrash)
                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiejar);
            else
                curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiejar);
        }


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);

        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 格式化货币  当前需求是小数点后2位    所以模板中调用的时候直接使用  CommonUtils::formatMoney($money)  如果后期修改成小数点3位   不需要修改调用   只需要$decimalpoint = 3
     *
     * 注意事项：这个方法是用于模板中输出钱币时用    其余地方使用的时候建议不要缺省参数$decimalpoint
     *
     * @param $money
     * @param int $decimalpoint
     *
     * @return string
     */
    public static function formatMoney($money,$decimalpoint=2){
        return number_format(MathUtils::bankerRound($money,$decimalpoint),$decimalpoint);
    }

    /**
     * 格式化利率
     * @param $rate
     * @return mixed
     */
    public static function formatRate($rate){
        if((int)$rate == $rate)
            return (int)$rate;
        else
            return self::formatMoney($rate);
    }

    /**
     * 格式化借款剩余天数
     * @param $time
     * @return string
     */
    public static function formatEndDay($time) {
        $days = (int)(($time-time())/86400);
        if($days < 1)
            $res = '不足<strong>1</strong>天';
        else
            $res = "<strong>{$days}</strong>天";
        return $res;
    }

    /**
     * 格式化借款进度
     * @param $progress
     * @return int|string
     */
    public static function formatProgress($progress) {
        return doubleval($progress);
//        if((int)$progress == $progress)
//            return (int)$progress;
//        else
//            return self::formatMoney($progress);
    }

    /**
     * 格式化银行卡额度
     * @param $val
     * @return int
     */
    public static function formatBankVal($val){
        $num = intval($val/10000);

        if($num < 1)
            $num = intval($val/1000)/10;

        if($num < 0.1)
            $num = intval($val/100)/100;

        return $num.'万';

    }

    /**
     * 格式化银行卡号，每个四个增加一个空格
     * @param $code
     * @return string
     */
    public static function formatBankCode($code){

        $codes = array();

        for($i=0;$i< ceil(strlen($code)/4);$i++){
            $codes[] = substr($code,$i*4,4);
        }

        return implode($codes,' ');
    }
} 