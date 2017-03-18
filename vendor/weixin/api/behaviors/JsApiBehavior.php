<?php

namespace weixin\api\behaviors;

use Yii;
use yii\helpers\Url;

/**
 * Class JsApiBehavior
 * @package weixin\api\behaviors
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class JsApiBehavior extends Behavior
{
    /**
     * 页面中wx.config()
     */
    public function registerJsApi()
    {
        // 如果url包含#，则去除
        $currentUrl = Url::current([], true);
        $pos = strpos($currentUrl, '#');// 如果'#'存在的话，则返回位置（索引从0开始），否则返回false
        if($pos !== false){
            //#存在
            $currentUrl = substr($currentUrl, 0, $pos);
        }
        $signData = [
            'jsapi_ticket' => $this->owner->getJsApiTicket(),
            'timestamp' => time(),
            'noncestr' => Yii::$app->security->generateRandomString(10),
            'url' => $currentUrl // 当前网页的URL，不包含#及其后面部分
        ];
        $apiList = [
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone',
            'startRecord',
            'stopRecord',
            'onVoiceRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'onVoicePlayEnd',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'translateVoice',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard',
        ];
        ksort($signData, SORT_STRING); // 对所有待签名参数按照字段名的ASCII 码从小到大排序（字典序）
        $signStr = $this->owner->arrayToPaySignStr($signData); // 使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串string1。这里需要注意的是所有参数名均为小写字符
        $sign = sha1($signStr); // 对string1作sha1加密，字段名和字段值都采用原始值，不进行URL 转义。
        $jsApiList = implode("','", $apiList);
        $jsApiList = "'" . $jsApiList . "'";
        Yii::$app->controller->view->registerJsFile('http://res.wx.qq.com/open/js/jweixin-1.1.0.js');
        Yii::$app->controller->view->registerJs("wx.config({debug: true, appId: '" . $this->owner->appID
            . "',timestamp: '" . $signData['timestamp']
            . "',nonceStr: '" . $signData['noncestr']
            . "',signature: '" . $sign
            . "',jsApiList: [" . $jsApiList . "]});");
    }
}