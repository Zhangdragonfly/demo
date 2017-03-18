<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM
 */

namespace wom\modules\site\controllers;

use common\models\AdOwner;
use common\models\AdWeixinOrder;
use common\models\AdWeixinPlan;
use common\models\WomDirectOrderTimeLineCtl;
use wom\components\OrderEvent;
use wom\controllers\BaseAppController;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

/**
 * 支付
 * Class PaymentController
 * @package wom\modules\site\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class PaymentController extends BaseAppController
{
    public function actionAliPay(){
        $request = Yii::$app->request;
        if ($request->isPost && $request->post('pay-type') == 'wom') {
            // 沃米账号支付

        }
    }

    public function actionAdOwnerWeixinPlan(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $planUUID = $request->post('plan_uuid');
            $orderUUID = $request->post('order_uuid',0);
            //账户信息
            $loginAccountInfo = $this->getLoginAccountInfo();
            $adOwner = AdOwner::findOne(['uuid' => $loginAccountInfo['ad-owner-uuid']]);
            //活动支付
            if (!empty($planUUID)) {
                $order = AdWeixinOrder::findAll(['plan_uuid' => $planUUID]);
                $total_price_pay_online = 0;
                foreach ($order as $item){//获取未支付订单的金额
                    if($item->status == 0){
                        $total_price_pay_online+=$item->price_min;
                    }
                }
            }
            //订单单独支付
            if (!empty($orderUUID)){
                $order = AdWeixinOrder::findOne(['uuid' => $orderUUID]);
                $total_price_pay_online = $order->price_min;
            }
            //账户余额付款
            if ($adOwner->total_available_balance > $total_price_pay_online) {
                $adOwner->total_available_balance = $adOwner->total_available_balance - $total_price_pay_online;
                $adOwner->total_frozen_amount = $adOwner->total_frozen_amount + $total_price_pay_online;
                $adOwner->not_yet_order = 1;
                $adOwner->save();
                //修改订单到已支付状态
                if(!empty($planUUID)){
                    $orderList = AdWeixinOrder::findAll(['plan_uuid' => $planUUID]);
                }
                if(!empty($orderUUID)){
                    $orderList = AdWeixinOrder::findAll(['uuid' => $orderUUID]);
                }
                foreach($orderList as $key => $order){
                    $order->status = AdWeixinOrder::ORDER_STATUS_TO_ACCEPT;
                    $order->save();
                    //新建系统任务时间控制
                    $orderTimeLine = WomDirectOrderTimeLineCtl::findOne(['order_uuid'=>$order->uuid]);
                    $orderTime = $orderTimeLine->order_time;
                    $executeTime = $orderTimeLine->execute_time;
                    $time_diff_hours = ($executeTime - $orderTime)/3600;
                    $orderTimeLine->pay_time = time();
                    if($time_diff_hours>4){//执行时间大于下单时间超过4小时
                        $orderTimeLine->not_accept_flow_time = $orderTime+(2*3600);
                    }else{
                        $orderTimeLine->not_accept_flow_time = $orderTime+(1*3600);
                    }
                    $orderTimeLine->save();
                }
                //修改活动订单支付状态
                $weixinPlan = AdWeixinPlan::findOne(['uuid' => $planUUID]);
                $order = AdWeixinOrder::findAll(['plan_uuid' => $planUUID]);
                //判断活动中订单是否全都支付
                $planPayAll = 1;
                foreach ($order as $item){
                    if($item->status == 0){
                        $planPayAll = 0;
                    }
                }
                if($planPayAll == 1){
                    $weixinPlan->status = AdWeixinPlan::STATUS_IN_PROGRESS;
                    $weixinPlan->save();
                }else{
                    $weixinPlan->status = AdWeixinPlan::STATUS_TO_PAY;
                    $weixinPlan->save();
                }
                //TODO 给广告主发送已支付短信通知

                return ['err_code' => 0, 'err_msg' => '提交成功', 'redirect_url' => Url::to(['/ad-owner/admin-weixin-plan/list'])];
            }else{
                // TODO 可用余额不足,跳转到余额不足错误页
            }

        }
    }
}