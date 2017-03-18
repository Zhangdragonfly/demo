<?php

namespace weixin\api;

use weixin\api\behaviors\QrLoginBehavior;
use weixin\api\behaviors\UserInfoBehavior;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Object;
use yii\caching\Cache;
use yii\di\Instance;
use yii\web\BadRequestHttpException;

/**
 * Class Weixin
 * @package weixin\api
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class Weixin extends Component
{
    /**
     * 公众号应用ID
     * @var String
     */
    public $appID;

    /**
     * 公众号应用密钥
     * @var String
     */
    public $appSecret;

    /**
     * 公众号服务器配置令牌
     * @var String
     */
    public $token;

    /**
     * 公众号服务器配置加解密密钥
     * @var String
     */
    public $encodingAESKey;

    /**
     * 微信支付商户号
     * @var String
     */
    public $merchantID;

    /**
     * 微信支付商户支付密钥（需都微信支付商户平台-API设置，自行进行设置）
     * @var String
     */
    public $paySecret;

    /**
     * 缓存组件名称，默认调用系统cache组件
     * @var Cache
     */
    public $cache = 'cache';

    public static $weixin;

    /**
     * attach behavior to component statically
     * @return array
     */
    public function behaviors()
    {
        return [
            AccessToken::className(),
            ThirdPartyLogin::className(),
            UserInfoBehavior::className()
        ];
    }

    /**
     * 初始化组件
     */
    public function init()
    {
        parent::init();
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        } else {
            throw new InvalidConfigException("Cache must be turned on");
        }
        self::$weixin = $this;
    }

    /**
     * 微信服务器端验证
     * 微信公众平台 > 开发者中心 > 服务器配置
     * @param bool $return
     * @return bool
     */
    public function verify($return = false)
    {
        // Yii::trace('weixin verify token: ' . $this->token, 'dev\*' . __METHOD__);

        $encryptStr = "";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $postStr = file_get_contents("php://input");
            $array = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->encrypt_type = isset($_GET["encrypt_type"]) ? $_GET["encrypt_type"] : '';
            if ($this->encrypt_type == 'aes') { //aes加密
                $this->log($postStr);
                $encryptStr = $array['Encrypt'];
                $pc = new Prpcrypt($this->encodingAesKey);
                $array = $pc->decrypt($encryptStr, $this->appid);
                if (!isset($array[0]) || ($array[0] != 0)) {
                    if (!$return) {
                        die('decrypt error!');
                    } else {
                        return false;
                    }
                }
                $this->postxml = $array[1];
                if (!$this->appid)
                    $this->appid = $array[2];
                //为了没有appid的订阅号。
            } else {
                $this->postxml = $postStr;
            }
        } elseif (isset($_GET["echostr"])) {
            $echoStr = $_GET["echostr"];
            if ($return) {
                if ($this->checkSignature())
                    return $echoStr;
                else
                    return false;
            } else {
                if ($this->checkSignature())
                    die($echoStr);
                else
                    die('no access');
            }
        }
        if (!$this->checkSignature($encryptStr)) {
            if ($return)
                return false;
            else
                die('no access');
        }
        return true;
    }

    /**
     * 微信服务器端验证
     * @param string $str
     * @return bool
     */
    private function checkSignature($str = '')
    {
        $signature = isset($_GET["signature"]) ? $_GET["signature"] : '';
        $signature = isset($_GET["msg_signature"]) ? $_GET["msg_signature"] : $signature; //如果存在加密验证则用加密验证段
        $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
        $nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : '';
        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce, $str);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function getJsSdkSignature()
    {
        $jsapi_ticket = self::get_jsapi_ticket();
        if (isset($_GET['nonceStr'])) {
            $noncestr = $_GET['nonceStr'];
        } else if (isset($_POST['nonceStr'])) {
            $noncestr = $_POST['nonceStr'];
        } else {
            $noncestr = '';
        }
        if (isset($_GET['timestamp'])) {
            $timestamp = $_GET['timestamp'];
        } else if (isset($_POST['timestamp'])) {
            $timestamp = $_POST['timestamp'];
        } else {
            $timestamp = time();
        }
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
        } else if (isset($_POST['url'])) {
            $url = $_POST['url'];
        } else {
            $url = '';
        }
        $string1 = 'jsapi_ticket=' . $jsapi_ticket['jsapi_ticket'] . '&noncestr=' . $noncestr . '&timestamp=' . $timestamp . '&url=' . $url;
        $signature = sha1($string1);

        $this->response(array('errcode' => 100, 'errmsg' => 'Get signature successfully', 'signature' => $signature, 'timestamp' => $timestamp, 'jsapi_ticket' => $jsapi_ticket['jsapi_ticket'], 'noncestr' => $noncestr, 'url' => $url));
    }

    /**
     * 返回微信支付签名
     * @param $array
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function paySign($array)
    {
        if (!is_array($array)) {
            throw new InvalidParamException("pay sign data must be type array");
        }
        if ($this->paySecret === null) {
            throw new InvalidParamException("pay sign must set \$paySecret param");
        }
        ksort($array);
        $signStr = static::arrayToPaySignStr(array_filter($array));
        $signStr .= '&key=' . $this->paySecret;
        return strtoupper(md5($signStr));
    }

    /**
     * 检查微信支付签名
     * @param $xml
     * @return bool
     * @throws BadRequestHttpException
     */

    public function checkPaySign($xml)
    {
        $arr = static::xmlToArray($xml);
        if (!isset($arr['sign'])) {
            throw new BadRequestHttpException("sign can't empty");
        }
        $sign = $arr['sign'];
        unset($arr['sign']);
        return $sign === $this->paySign($arr) ? true : false;
    }

    /**
     * @param Object $object
     * @return array
     */

    public function objectToArray($object)
    {
        if (!$object instanceof Object) {
            throw new InvalidParamException("\$object must be extend \\yii\\base\\Object");
        }
        $array = [];
        foreach ($object as $key => $val) {
            $array[$key] = $val;
        }
        return $array;
    }

    /**
     * @param array|Object $array
     * @param bool|true $header
     * @return string
     */

    public function arrayToXml($array, $header = true)
    {
        if (!is_array($array) && !$array instanceof Object) {
            throw new InvalidParamException("\$array must be type array or Object");
        }
        $xml = $header === true ? '<xml>' : '';
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $xml .= '<' . $k . '>' . self::arrayToXml($v, $header) . '</' . $k . '>';
            } else {
                $xml .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
            }
        }
        $xml .= $header === true ? '</xml>' : '';
        return $xml;
    }

    /**
     *
     * @param string $xml
     * @return array|mixed
     */

    public function xmlToArray($xml)
    {
        if (!function_exists("simplexml_load_string")) {
            throw new \BadFunctionCallException("need  function simplexml_load_string");
        }
        libxml_disable_entity_loader(true);
        $obj = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return empty($obj) ? [] : json_decode(json_encode($obj), true);
    }


    /**
     * 使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串
     * @param $array
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function arrayToPaySignStr($array)
    {
        if (!is_array($array)) {
            throw new InvalidParamException("\$array must be type array ");
        }
        $str = '';
        foreach ($array as $k => $v) {
            $str .= ($str == '' ? $k . '=' . $v : '&' . $k . '=' . $v);
        }
        return $str;
    }

    /**
     * Http请求
     * @param $url
     * @param null $params
     * @param string $type
     * @return bool|mixed
     * @throws \yii\base\InvalidParamException
     */
    public function http($url, $params = null, $type = 'get')
    {
        $curl = curl_init();
        switch ($type) {
            case 'get':
                is_array($params) && $params = http_build_query($params);
                !empty($params) && $url .= (stripos($url, '?') === false ? '?' : '&') . $params;
                break;
            case 'post':
                curl_setopt($curl, CURLOPT_POST, true);
                if (!is_array($params)) {
                    throw new InvalidParamException("Post data must be an array.");
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'raw':
                curl_setopt($curl, CURLOPT_POST, true);
                if (is_array($params)) {
                    throw new InvalidParamException("Post raw data must not be an array.");
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            default:
                throw new InvalidParamException("Invalid http type '{$type}' called.");
        }
        if (stripos($url, "https://") !== false) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($curl);
        $status = curl_getinfo($curl);
        curl_close($curl);
        if (isset($status['http_code']) && intval($status['http_code']) == 200) {
            return $content;
        }
        return false;
    }

}