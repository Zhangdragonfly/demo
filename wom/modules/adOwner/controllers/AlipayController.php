<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create:  2016/8/9 15:41
 */
namespace wom\modules\adOwner\controllers;
use common\models\AdOwner;
use common\models\AdOwnerAllTradeRecord;
use common\models\AdOwnerFundChangeRecord;
use common\models\WomDirectOrderTimeLineCtl;
use common\models\AdWeixinOrder;
use common\models\AdWeixinPlan;
use wom\components\OrderEvent;
use wom\yii2_alipay\AlipayNotify;
use Yii;
use wom\yii2_alipay\AlipayPay;
use yii\db\Exception;
use yii\helpers\Url;
use common\helpers\PlatformHelper;
use yii\web\Controller;

/**
 * 支付宝支付
 * Class AlipayController
 * @package wom\modules\adOwner\controllers
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class AlipayController extends AdOwnerBaseAppController{
    public $layout = '//admin-ad-owner';
    /**
     * 生成充值跳转链接
     * @return string
     */
    public function actionPay()
    {
        $request = Yii::$app->request;

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = PlatformHelper::getUUID();
        //$request->post('WIDout_trade_no');
        //订单名称，必填
        $subject = $request->post('WIDsubject');

        //付款金额，必填
        $total_fee = $request->post('WIDtotal_fee');
        //商品描述，可空
        $body = $request->post('WIDbody');
        //公共参数
        $extra_common_param = $request->post('plan_uuid').'-'.$request->post('order_uuid');
        // 生成充值记录
        $record = new AdOwnerFundChangeRecord();

        $record->uuid = $out_trade_no;
        $record->owner_uuid = self::getLoginAccountInfo()['ad-owner-uuid'];
        $record->type = AdOwnerFundChangeRecord::TYPE_TOP_UP_ONLINE;
        $record->amount = $total_fee;
        $record->comment = '支付宝充值即时到账';

        $record->save();

        $show_url = 'http://www.51wom.com';
        $alipay = new AlipayPay();
        $html = $alipay->requestPay($out_trade_no, $subject, $total_fee, $body, $show_url, $extra_common_param);
        echo $html;
    }

    /**
     * @var String 页面跳转同步通知页面路径
     * 需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
     * @return string
     * @throws Exception
     */
    public function actionReturnCall()
    {
        $alipay = new AlipayPay();
        $alipayNotify = new AlipayNotify($alipay->bulidConfig());
        $verify_result = $alipayNotify->verifyReturn();
        if ($verify_result) {//验证成功
            //判断结果，跳转到不同页面
            $success = $_GET['trade_status'];
            $out_trade_no = $_GET['out_trade_no'];
            $extra_common_param = explode('-',$_GET['extra_common_param']);
            $planUUID = $extra_common_param[0];
            $orderUUID = $extra_common_param[1];
            $amount = $_GET['total_fee'];
            if ($success == 'TRADE_SUCCESS') {

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    if($planUUID == 0){
                        // 充值

                        // 更新充值记录
                        $record = AdOwnerFundChangeRecord::findOne(['uuid' => $out_trade_no]);
                        $record->complete_time = time();
                        $record->status = AdOwnerFundChangeRecord::STATUS_SUCCESS;
                        $record->save();
                        // 增加用户账号余额 total_balance 和 当前账户可用金额 total_available_balance
                        $adOwner = AdOwner::findOne(['uuid' => self::getLoginAccountInfo()['ad-owner-uuid']]);
                        $adOwner->total_balance = $adOwner['total_balance'] + $amount;
                        $adOwner->total_available_balance = $adOwner['total_available_balance'] + $amount;

                        $adOwner->save();
                    }else{
                        // 支付

                        // 更新充值记录
                        $record = AdOwnerFundChangeRecord::findOne(['uuid' => $out_trade_no]);
                        $record->complete_time = time();
                        $record->status = AdOwnerFundChangeRecord::STATUS_SUCCESS;
                        $record->save();
                        // 增加订单冻结记录
                        $tradeRecord = new AdOwnerAllTradeRecord();
                        $tradeRecord->uuid = PlatformHelper::getUUID();
                        $tradeRecord->type = AdOwnerAllTradeRecord::TYPE_FREEZE;
                        $tradeRecord->owner_uuid = self::getLoginAccountInfo()['ad-owner-uuid'];
                        $tradeRecord->plan_uuid = $planUUID;
                        $plan = AdWeixinPlan::findOne(['uuid' => $planUUID]);
                        $tradeRecord->plan_name = $plan['name'];
                        $tradeRecord->media_type = AdOwnerAllTradeRecord::MEDIA_TYPE_WEIXIN;
                        $tradeRecord->create_time = time();
                        $tradeRecord->amount = $amount;
                        $tradeRecord->comment = '提交订单冻结金额';
                        $tradeRecord->save();
                        // 增加用户账号余额 total_balance 和 当前账户冻结金额 total_frozen_amount
                        $adOwner = AdOwner::findOne(['uuid' => self::getLoginAccountInfo()['ad-owner-uuid']]);
                        $adOwner->total_balance = $adOwner['total_balance'] + $amount;
                        $adOwner->total_frozen_amount = $adOwner['total_frozen_amount'] + $amount;
                        $adOwner->save();
                        // 更新订单状态为已支付
                        if($orderUUID == 0){
                            $orderList = AdWeixinOrder::findAll(['plan_uuid' => $planUUID]);
                        }else{
                            $orderList = AdWeixinOrder::findAll(['uuid' => $orderUUID]);
                        }
                        foreach($orderList as $key => $order){
                            $order->status = AdWeixinOrder::ORDER_STATUS_TO_ACCEPT;
                            $order->save();
                            //新建任务时间控制
                            $orderTimeLine = WomDirectOrderTimeLineCtl::findOne(['order_uuid'=>$order->uuid]);
                            $orderTime = $orderTimeLine->order_time;
                            $executeTime = $orderTimeLine->execute_time;
                            $time_diff_hours = ($executeTime - $orderTime)/3600;
                            $orderTimeLine->pay_time = time();
                            if($time_diff_hours>4){
                                $orderTimeLine->not_accept_flow_time = $orderTime+(2*3600);
                            }else{
                                $orderTimeLine->not_accept_flow_time = $orderTime+(1*3600);
                            }
                            $orderTimeLine->save();
                        }
                        // 更新活动状态为已支付
                        $plan = AdWeixinPlan::findOne(['uuid' => $planUUID]);
                        $plan->status = AdWeixinPlan::STATUS_IN_PROGRESS;
                        $plan->save();

                        //TODO 给广告主发送已支付短信通知

                    }
                    $transaction->commit();

                } catch (Exception $e) {
                    $transaction->rollBack();
                }
                $this->redirect(['/ad-owner/admin-fin-manage/pay-success']);
            } else {
                // 更新充值记录
                $record = AdOwnerFundChangeRecord::findOne(['uuid' => $out_trade_no]);
                $record->complete_time = time();
                $record->status = AdOwnerFundChangeRecord::STATUS_CANCEL;
                $record->save();
                $this->redirect(['/financial-center/ad-owner-list']);
            }
        }else{
            echo "验证失败";
        }
    }

    /**
     * 支付表单
     */
    public function actionPayForm(){
        $request = Yii::$app->request;
        $orderUUID = $request->get('order',0);
        $planUUID = $request->get('plan',0);
        $amount = $request->get('amount',0);
        if($orderUUID == 0){
            if($planUUID != 0){
                $plan = AdWeixinPlan::findOne(['uuid' => $planUUID]);
                $amount = $plan->total_price_pay_online;
            }
        }else{
            $order = AdWeixinOrder::findOne(['uuid' => $orderUUID]);
            $amount = $order->price_min;
        }
        return $this->render('pay-form', [
            'order_uuid' => $orderUUID,
            'plan_uuid' => $planUUID,
            'amount' => $amount
        ]);
    }
}