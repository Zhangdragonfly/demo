<?php

namespace wom\yii2_alipay;

use wom\yii2_alipay\AlipaySubmit;
use yii\helpers\Url;

class AlipayPay {

    /* *
 * 配置文件
 * 版本：3.4
 * 修改日期：2016-03-08
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法：
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
 * 2、更换浏览器或电脑，重新登录查询。
 */

//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
public $partner		= '2088711465369097';

//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
public $seller_id	= '2088711465369097';

// MD5密钥，安全检验码，由数字和字母组成的32位字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
public $key			= 'ftliblbtle6za5m9m01jlitcy2xuelb5';

// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
public $notify_url = "http://商户网址/create_direct_pay_by_user-PHP-UTF-8/notify_url.php";

// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
public $return_url;

//签名方式
public $sign_type;

//字符编码格式 目前支持 gbk 或 utf-8
public $input_charset;

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
public $cacert;

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
public $transport = "http";

// 支付类型 ，无需修改
public $payment_type = "1";

// 产品类型，无需修改
public $service = "create_direct_pay_by_user";

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


//↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

// 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
public $anti_phishing_key = "";

// 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
public $exter_invoke_ip = "";

//↑↑↑↑↑↑↑↑↑↑请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


function init(){
    $this->sign_type = strtoupper('MD5');
    $this->input_charset = strtolower('utf-8');
    $this->cacert= getcwd().'\\cacert.pem';
    $this->return_url = \Yii::$app->params['domain']['home'] . Url::to(['/ad-owner/alipay/return-call']);
}
    /**
     * @name requestPay
     * @desc
     * @param $out_trade_no String 商户订单号，商户网站订单系统中唯一订单号，必填
     * @param $subject String 订单名称
     * @param $total_fee String 付款金额
     * @param $body String 订单描述
     * @param $show_url String 商品展示地址
     * @param $extra_common_param String 公共参数
     * @return String 跳转HTML
     */
    public function requestPay($out_trade_no, $subject, $total_fee, $body, $show_url, $extra_common_param) {
        $this->init();
        /*         * ************************请求参数************************* */
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数
        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1

        /*         * ********************************************************* */

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($this->partner),
            "seller_id" => trim($this->seller_id),
            "payment_type" => $payment_type,
            "notify_url" => $this->notify_url,
            "return_url" => $this->return_url,
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            "show_url" => $show_url,
            "extra_common_param" => $extra_common_param,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim(strtolower($this->input_charset))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($this->bulidConfig());
        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
        return $html_text;
    }

    public function verifyNotify() {
        $alipayNotify = new AlipayNotify($this->bulidConfig());
        $verify_result = $alipayNotify->verifyNotify();

        return $verify_result;
    }

    public function verifyReturn() {
        $alipayNotify = new AlipayNotify($this->bulidConfig());
        $verify_result = $alipayNotify->verifyReturn();

        return $verify_result;
    }

    public function bulidConfig() {
        $this->init();
        //构造要请求的配置数组
        $alipay_config = array(
            'partner' => $this->partner,
            'seller_id' => $this->seller_id,
            'key' => $this->key,
            'sign_type' => $this->sign_type,
            'input_charset' => $this->input_charset,
            'cacert' => $this->cacert,
            'transport' => $this->transport,
        );
        return $alipay_config;
    }

}
