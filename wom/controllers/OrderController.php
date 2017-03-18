<?php
namespace wom\controllers;
use common\helpers\SendNoticeHelper;
use yii\web\Controller;

/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/26/16 10:13
 */
class OrderController extends Controller
{
    /**
     * 本地运行需要参考以下链接执行composer命令下载第三方组件
     * https://github.com/borodulin/yii2-services
     * @return array
     */
    public function actions()
    {
        return array(
            'sendSms'=>array(
                'class'=>'conquer\services\WebServiceAction',
            ),
        );
    }

    /**
     * 发送手机短信
     * @param $template 模板id
     * @param $mobile 手机号
     * @param $params 传递给模板的参数
     * 参数以占位符方式显示
     * 例如 $params = ['2300'],
     * 对应模板: 【51wom平台】(沃米优选)您已成功完成下单，本次已支付金额：{1}元。等待媒体主接单执行。
     * 发送的短信内容：【51wom平台】(沃米优选)您已成功完成下单，本次已支付金额：2300元。等待媒体主接单执行。
     * @return array
     * @soap
     */
    public function sendSms($template, $mobile, $params)
    {
        // TODO: 模板id根据code对应
        SendNoticeHelper::send(SendNoticeHelper::TYPE_SMS, $mobile, $template, $params);
        return ['err_code' => 0, 'err_msg' => '发送成功'];
    }

}