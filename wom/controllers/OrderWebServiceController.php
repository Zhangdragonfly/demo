<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 1/12/17 11:27
 */
namespace wom\controllers;
use common\helpers\SendNoticeHelper;
use yii\rest\ActiveController;

/**
 * Class OrderWebServiceController
 * @package wom\controllers
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class OrderWebServiceController extends ActiveController
{

    public $modelClass = 'common\models\AdWeixinOrder';
    /**
     * 发送手机短信
     * @param $template 模板id (如：发送验证码的模板，44998)
     * @param $mobile 手机号
     * @param $params 传递给模板的参数
     * 参数以占位符方式显示
     * 例如 $params = ['2300'],
     * 对应模板: 【51wom平台】(沃米优选)您已成功完成下单，本次已支付金额：{1}元。等待媒体主接单执行。
     * 发送的短信内容：【51wom平台】(沃米优选)您已成功完成下单，本次已支付金额：2300元。等待媒体主接单执行。
     * @return array
     */
    public function actionSendSms($template, $mobile, $params)
    {
        $params = explode('_',$params);
        SendNoticeHelper::send(SendNoticeHelper::TYPE_SMS, $mobile, $template, $params);
        return ['err_code' => 0, 'err_msg' => '发送成功'];
    }
}