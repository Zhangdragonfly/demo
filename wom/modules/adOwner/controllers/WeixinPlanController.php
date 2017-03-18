<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:34 PM
 */
namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\models\AdOwner;
use common\models\AdWeixinPlan;
use common\models\AdWeixinOrder;
use common\models\MediaCollectLibraryGroup;
use common\models\WomDirectOrderTimeLineCtl;
use common\models\MediaWeixin;
use yii\web\Response;
use yii;
use yii\db\Query;
use yii\helpers\Url;

/**
 * 微信投放
 * 新建投放活动/选择媒体资源/
 * Class WeixinPlanController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class WeixinPlanController extends AdOwnerBaseAppController
{
    public $layout = '//site-stage';

    /**
     * 新建投放活动
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $planName = $request->post('plan_name');
            $planDesc = $request->post('plan_desc');
            $weixinMediaSelectedToPutIn = $request->post('weixin_media_selected_to_put_in');

            $planUUID = PlatformHelper::getUUID();

            // 新建计划
            $adWeixinPlan = new AdWeixinPlan();
            $adWeixinPlan->uuid = $planUUID;
            $adWeixinPlan->ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
            $adWeixinPlan->name = $planName;
            $adWeixinPlan->plan_desc = $planDesc;

            $totalFollowerNum = 0; // 预计粉丝数
            $totalPriceAmount = 0; // 预计投放总金额
            $totalPriceAmountPayOnline = 0; // 在线需要支付的金额
            // 新建订单
            if (isset($weixinMediaSelectedToPutIn)) {
                // 格式: media_1_uuid,media_2_uuid,
                // 将末尾的','去掉
                $weixinMediaSelectedToPutIn = substr($weixinMediaSelectedToPutIn, 0, strlen($weixinMediaSelectedToPutIn) - 1);
                $weixinMediaUUIDList = explode(',', $weixinMediaSelectedToPutIn);
                $mediaWeixinList = MediaWeixin::find()
                    ->indexBy('uuid')
                    ->where(['uuid' => $weixinMediaUUIDList])
                    ->all();

                foreach ($weixinMediaUUIDList as $mediaUUID) {
                    $order = new AdWeixinOrder();
                    $order->plan_uuid = $planUUID;
                    $order->weixin_media_uuid = $mediaUUID;
                    $order->vendor_uuid = $mediaWeixinList[$mediaUUID]->pref_vendor_uuid;
                    $order->position_code = 'pos_m_1'; // 默认发布文章位置为 多图文头条
                    $order->pub_type = $mediaWeixinList[$mediaUUID]->m_1_pub_type; // 默认的发布类型为 该账号设置的多图文头条 的发布类型
                    $order->position_content_conf = json_encode(['pos_s' => 0, 'pos_s_selected' => 0, 'pos_m_1' => 0, 'pos_m_1_selected' => 1, 'pos_m_2' => 0, 'pos_m_2_selected' => 0, 'pos_m_3' => 0, 'pos_m_3_selected' => 0]);
                    $order->is_fixed_price = 1;
                    $order->price_min = $mediaWeixinList[$mediaUUID]->retail_price_m_1_min;
                    $order->price_max = $mediaWeixinList[$mediaUUID]->retail_price_m_1_max;
                    $order->execute_price = $mediaWeixinList[$mediaUUID]->retail_price_m_1_min;
                    $order->save();

                    $totalFollowerNum += $mediaWeixinList[$mediaUUID]->follower_num;
                    $totalPriceAmount += $mediaWeixinList[$mediaUUID]->retail_price_m_1_min;
                    if ($order->pub_type == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
                        $totalPriceAmountPayOnline += $mediaWeixinList[$mediaUUID]->retail_price_m_1_min;
                    }
                }

                $adWeixinPlan->media_amount = count($weixinMediaUUIDList);
                $adWeixinPlan->total_follower_num = $totalFollowerNum;
                $adWeixinPlan->total_price_amount_min = $totalPriceAmount;
                $adWeixinPlan->total_price_amount_max = $totalPriceAmount;
                $adWeixinPlan->total_price_pay_online = $totalPriceAmountPayOnline;

            } else {
                $adWeixinPlan->media_amount = 0;
            }

            $adWeixinPlan->save();

            $adOwner = AdOwner::find()
                ->where(['uuid' => $this->getLoginAccountInfo()['ad-owner-uuid']])
                ->one();
            $adOwner->total_plan_cnt = $adOwner->total_plan_cnt + 1;
            $adOwner->save();

            return ['err_code' => 0, 'err_msg' => '保存成功', 'plan_uuid' => $planUUID];
        }

        if ($request->isGet) {
            $route = $request->get('route', 1);
            if ($route == 1) {
                // 来源: 直接点击"新建活动"
                return $this->render('step-1-create-plan', [
                    'route' => $route
                ]);
            } else if ($route == 2) {
                // 来源: 选择资源,在购物车里点击"立即投放"
                return $this->render('step-1-create-plan', [
                    'route' => $route
                ]);
            }
        }
    }

    /**
     * 把微信账号添加到一个已经存在的plan里
     */
    public function actionAddMediaIntoPlan()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $planUUID = $request->post('plan_uuid');
            $weixinMediaSelectedToPutIn = $request->post('weixin_media_selected_to_put_in');

            $adWeixinPlan = AdWeixinPlan::find()
                ->where(['uuid' => $planUUID])
                ->one();

            // 格式: media_1_uuid,media_2_uuid,
            // 将末尾的','去掉
            $weixinMediaSelectedToPutIn = substr($weixinMediaSelectedToPutIn, 0, strlen($weixinMediaSelectedToPutIn) - 1);
            $newWeixinMediaUUIDList = explode(',', $weixinMediaSelectedToPutIn);
            $mediaWeixinList = MediaWeixin::find()
                ->indexBy('uuid')
                ->where(['uuid' => $newWeixinMediaUUIDList])
                ->all();

            // 已经选的账号
            $existWeixinOrderList = AdWeixinOrder::find()
                ->where(['plan_uuid' => $planUUID])
                ->all();
            $existWeixinMediaUUIDList = [];
            foreach ($existWeixinOrderList as $_order) {
                $existWeixinMediaUUIDList[] = $_order->weixin_media_uuid;
            }

            $orderCnt = count($existWeixinMediaUUIDList);

            $totalFollowerNum = $adWeixinPlan->total_follower_num; // 预计粉丝数
            $totalPriceAmount = $adWeixinPlan->total_price_amount_min; // 预计投放总金额
            $totalPriceAmountPayOnline = $adWeixinPlan->total_price_pay_online; // 在线需要支付的金额

            foreach ($newWeixinMediaUUIDList as $mediaUUID) {
                if (in_array($mediaUUID, $existWeixinMediaUUIDList)) {
                    // 如果该账号已经选择,则不重新创建order
                    continue;
                }
                $order = new AdWeixinOrder();
                $order->plan_uuid = $planUUID;
                $order->weixin_media_uuid = $mediaUUID;
                $order->vendor_uuid = $mediaWeixinList[$mediaUUID]->pref_vendor_uuid;
                $order->position_code = 'pos_m_1'; // 默认发布文章位置为 多图文头条
                $order->pub_type = $mediaWeixinList[$mediaUUID]->m_1_pub_type; // 默认的发布类型为 该账号设置的多图文头条 的发布类型
                $order->is_fixed_price = 1;
                $order->price_min = $mediaWeixinList[$mediaUUID]->retail_price_m_1_min;
                $order->price_max = $mediaWeixinList[$mediaUUID]->retail_price_m_1_max;
                $order->execute_price = $mediaWeixinList[$mediaUUID]->retail_price_m_1_min;
                $order->position_content_conf = json_encode(['pos_s' => 0, 'pos_s_selected' => 0, 'pos_m_1' => 0, 'pos_m_1_selected' => 1, 'pos_m_2' => 0, 'pos_m_2_selected' => 0, 'pos_m_3' => 0, 'pos_m_3_selected' => 0]);
                $order->save();
                $orderCnt++;

                $totalFollowerNum += $mediaWeixinList[$mediaUUID]->follower_num;
                $totalPriceAmount += $mediaWeixinList[$mediaUUID]->retail_price_m_1_min;
                if ($order->pub_type == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
                    $totalPriceAmountPayOnline += $mediaWeixinList[$mediaUUID]->retail_price_m_1_min;
                }
            }
            $adWeixinPlan->media_amount = $orderCnt;
            $adWeixinPlan->total_follower_num = $totalFollowerNum;
            $adWeixinPlan->total_price_amount_min = $totalPriceAmount;
            $adWeixinPlan->total_price_amount_max = $totalPriceAmount;
            $adWeixinPlan->total_price_pay_online = $totalPriceAmountPayOnline;
            $adWeixinPlan->save();

            return ['err_code' => 0, 'err_msg' => '保存成功'];
        }
    }

    /**
     * 第3步 填写投放内容
     */
    public function actionConfirm()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $planUUID = $request->get('plan_uuid');

            $weixinOrderList = (new Query())
                ->select(['weixin.public_name', 'weixin.real_public_id', 'weixin.pub_config', 'weixin.y_head_avg_view_cnt', 'weixin.follower_num', 'weixin_order.uuid AS order_uuid', 'weixin_order.position_content_conf'])
                ->from(['weixin_order' => AdWeixinOrder::tableName()])
                ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid  =  weixin_order.weixin_media_uuid')
                ->where(['weixin_order.plan_uuid' => $planUUID])
                ->andWhere(['<>', 'weixin_order.status', AdWeixinOrder::ORDER_STATUS_DELETED])
                ->all();

            foreach ($weixinOrderList as $i => $weixinOrder) {
                $pubConfigArray = json_decode($weixinOrder['pub_config'], true);

                // 该订单可选用的位置
                $orderAvailablePosArray = [];
                if ($pubConfigArray['pos_s']['pub_type'] != 0) {
                    $orderAvailablePosArray[] = '单图文';
                }
                if ($pubConfigArray['pos_m_1']['pub_type'] != 0) {
                    $orderAvailablePosArray[] = '多图文头条';
                }
                if ($pubConfigArray['pos_m_2']['pub_type'] != 0) {
                    $orderAvailablePosArray[] = '多图文2条';
                }
                if ($pubConfigArray['pos_m_3']['pub_type'] != 0) {
                    $orderAvailablePosArray[] = '多图文3-N条';
                }
                $weixinOrderList[$i]['order_available_pos_list'] = $orderAvailablePosArray;

                /**
                 * position_content_conf 某个订单的所有位置 "是否已经推广内容","是否选择该位置"
                 * {
                 * “pos_s”:0,
                 * “pos_s_selected”: 0,
                 * “pos_m_1”:1,
                 * “pos_m_1_selected”: 1
                 * “pos_m_2”:0,
                 * “pos_m_2_selected”: 0,
                 * “pos_m_3”:0,
                 * “pos_m_3_selected”: 0
                 * }
                 */
                $posContentAddedConfig = json_decode($weixinOrder['position_content_conf'], true);

                $orderAvailablePosConfig = [];
                $orderAvailablePosConfig['order_uuid'] = $weixinOrder['order_uuid'];
                $orderAvailablePosConfig['head_avg_read_num'] = $weixinOrder['y_head_avg_view_cnt'];
                $orderAvailablePosConfig['total_follower_num'] = $weixinOrder['follower_num'];

                $orderAvailablePosConfig['pos_s']['pos_label'] = '单图文';
                $orderAvailablePosConfig['pos_s']['retail_price'] = $pubConfigArray['pos_s']['retail_price_min'];
                $orderAvailablePosConfig['pos_s']['has_add_content'] = $posContentAddedConfig['pos_s'];
                $orderAvailablePosConfig['pos_s']['pub_type'] = $pubConfigArray['pos_s']['pub_type'];
                $orderAvailablePosConfig['pos_s']['is_selected'] = $posContentAddedConfig['pos_s_selected'];
                if ($posContentAddedConfig['pos_s_selected'] == 1) {
                    $weixinOrderList[$i]['pos_selected'] = '单图文';
                }

                $orderAvailablePosConfig['pos_m_1']['retail_price'] = $pubConfigArray['pos_m_1']['retail_price_min'];
                $orderAvailablePosConfig['pos_m_1']['has_add_content'] = $posContentAddedConfig['pos_m_1'];
                $orderAvailablePosConfig['pos_m_1']['pub_type'] = $pubConfigArray['pos_m_1']['pub_type'];
                $orderAvailablePosConfig['pos_m_1']['is_selected'] = $posContentAddedConfig['pos_m_1_selected'];
                if ($posContentAddedConfig['pos_m_1_selected'] == 1) {
                    $weixinOrderList[$i]['pos_selected'] = '多图文头条';
                }

                $orderAvailablePosConfig['pos_m_2']['retail_price'] = $pubConfigArray['pos_m_2']['retail_price_min'];
                $orderAvailablePosConfig['pos_m_2']['has_add_content'] = $posContentAddedConfig['pos_m_2'];
                $orderAvailablePosConfig['pos_m_2']['pub_type'] = $pubConfigArray['pos_m_2']['pub_type'];
                $orderAvailablePosConfig['pos_m_2']['is_selected'] = $posContentAddedConfig['pos_m_2_selected'];
                if ($posContentAddedConfig['pos_m_2_selected'] == 1) {
                    $weixinOrderList[$i]['pos_selected'] = '多图文2条';
                }

                $orderAvailablePosConfig['pos_m_3']['retail_price'] = $pubConfigArray['pos_m_3']['retail_price_min'];
                $orderAvailablePosConfig['pos_m_3']['has_add_content'] = $posContentAddedConfig['pos_m_3'];
                $orderAvailablePosConfig['pos_m_3']['pub_type'] = $pubConfigArray['pos_m_3']['pub_type'];
                $orderAvailablePosConfig['pos_m_3']['is_selected'] = $posContentAddedConfig['pos_m_3_selected'];
                if ($posContentAddedConfig['pos_m_3_selected'] == 1) {
                    $weixinOrderList[$i]['pos_selected'] = '多图文3-N条';
                }

                $weixinOrderList[$i]['order_available_pos_config'] = json_encode($orderAvailablePosConfig);
                // Yii::trace(json_encode($orderAvailablePosConfig), 'dev\#' . __METHOD__);
            }

            return $this->render('step-3-confirm-plan', [
                'weixinOrderList' => $weixinOrderList,
                'planUUID' => $planUUID
            ]);
        }
    }

    /**
     * 删除购物车里的微信资源
     */
    public function actionDeleteMedia()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');

            AdWeixinOrder::updateAll([
                'status' => AdWeixinOrder::ORDER_STATUS_DELETED
            ], [
                'uuid' => $orderUUID
            ]);

            $order = (new Query())
                ->select(['weixin_order.plan_uuid', 'weixin_order.pub_type', 'weixin_order.price_min', 'weixin.follower_num'])
                ->from(['weixin_order' => AdWeixinOrder::tableName()])
                ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid  =  weixin_order.weixin_media_uuid')
                ->where(['weixin_order.uuid' => $orderUUID])
                ->one();
            $weixinPlan = AdWeixinPlan::find()
                ->where(['uuid' => $order['plan_uuid']])
                ->one();
            $weixinPlan->total_price_amount_min = $weixinPlan->total_price_amount_min - $order['price_min'];
            $weixinPlan->total_price_amount_max = $weixinPlan->total_price_amount_max - $order['price_min'];
            $weixinPlan->total_follower_num = $weixinPlan->total_follower_num - $order['follower_num'];
            if ($order['pub_type'] == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
                $weixinPlan->total_price_pay_online = $weixinPlan->total_price_pay_online - $order['price_min'];
            }
            $weixinPlan->save();

            return ['err_code' => 0, 'err_msg' => '删除成功'];
        }
    }

    /**
     * 改变订单账号的投放位置
     */
    public function actionChangeOrderPos()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $posCode = $request->post('pos_code');
            $pubType = $request->post('pub_type');
            $retailPrice = $request->post('retail_price');

            $weixinOrder = AdWeixinOrder::find()
                ->where(['uuid' => $orderUUID])
                ->one();

            $weixinPlan = AdWeixinPlan::find()
                ->where(['uuid' => $weixinOrder->plan_uuid])
                ->one();
            $weixinPlan->total_price_amount_min = $weixinPlan->total_price_amount_min - $weixinOrder->price_min + $retailPrice;
            $weixinPlan->total_price_amount_max = $weixinPlan->total_price_amount_max - $weixinOrder->price_min + $retailPrice;
            $weixinPlan->total_execute_price_amount = $weixinPlan->total_execute_price_amount - $weixinOrder->execute_price + $retailPrice;
            if ($weixinOrder->pub_type == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
                $weixinPlan->total_price_pay_online = $weixinPlan->total_price_pay_online - $weixinOrder->price_min + $retailPrice;
            }
            $weixinPlan->save();

            $posContentConfigArray = json_decode($weixinOrder->position_content_conf, true);
            $posContentConfigArray['pos_s_selected'] = 0;
            $posContentConfigArray['pos_m_1_selected'] = 0;
            $posContentConfigArray['pos_m_2_selected'] = 0;
            $posContentConfigArray['pos_m_3_selected'] = 0;
            $posContentConfigArray[$posCode . '_selected'] = 1;

            $weixinOrder->position_code = $posCode;
            $weixinOrder->pub_type = $pubType;
            $weixinOrder->is_fixed_price = 1;
            $weixinOrder->price_min = $retailPrice;
            $weixinOrder->price_max = $retailPrice;
            $weixinOrder->execute_price = $retailPrice;
            $weixinOrder->position_content_conf = json_encode($posContentConfigArray);
            $weixinOrder->save();

            return ['err_code' => 0, 'err_msg' => '更改成功'];
        }
    }

    /**
     * 支付确认页(选择支付账户)
     */
    public function actionPayConfirm(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $planUUID = $request->post('plan_uuid');
            $totalPriceToPay = $request->post('total_price_to_pay'); // 需要在线支付的金额
            $totalRetailPrice = $request->post('total_retail_price');
            AdWeixinPlan::updateAll([
                'total_price_pay_online' => $totalPriceToPay,
                'total_price_amount_min' => $totalRetailPrice,
                'total_price_amount_max' => $totalRetailPrice,
                'status' => AdWeixinPlan::STATUS_TO_PAY], ['uuid' => $planUUID]);
            AdWeixinOrder::updateAll(
                ['status' => AdWeixinOrder::ORDER_STATUS_TO_PAY],
                ['plan_uuid' => $planUUID, 'status' => AdWeixinOrder::ORDER_STATUS_TO_SUBMIT]
            );
            //任务时间控制
            $weixinOrder = AdWeixinOrder::findAll(['plan_uuid'=>$planUUID]);
            foreach ($weixinOrder as $item){
                $orderUUID = $item->uuid;
                $executeTime = $item->execute_time;
                $orderTime = time();
                $time_diff_hours = ($executeTime - $orderTime)/3600;
                //新建任务时间控制
                $orderTimeLine = new WomDirectOrderTimeLineCtl();
                $orderTimeLine->uuid = PlatformHelper::getUUID();
                $orderTimeLine->order_uuid = $orderUUID;
                $orderTimeLine->order_time = $orderTime;
                $orderTimeLine->execute_time = $executeTime;
                if($time_diff_hours>4){
                    $orderTimeLine->not_pay_flow_time = $orderTime+(2*3600);
                }else{
                    $orderTimeLine->not_pay_flow_time = $orderTime+(1*3600);
                }
                $orderTimeLine->save();
            }
            return ['err_code' => 0, 'err_msg' => '保存成功'];
        }
        if ($request->isGet) {
            $planUUID = $request->get('plan_uuid');
            $orderUUID = $request->get('order_uuid');
            $loginAccountInfo = $this->getLoginAccountInfo();//账户信息
            $adOwner = AdOwner::findOne(['uuid' => $loginAccountInfo['ad-owner-uuid']]);
            $plan = AdWeixinPlan::findOne(['uuid' => $planUUID]);
            if (!empty($planUUID)) {//活动支付
                $order = AdWeixinOrder::findAll(['plan_uuid' => $planUUID]);
                $total_price_pay_online = 0;
                foreach ($order as $item){//获取未支付订单的金额
                    if($item->status == 0){
                        $total_price_pay_online+=$item->price_min;
                    }
                }
                if ($adOwner->total_available_balance < $total_price_pay_online) {//判断账户余额是否足够支付
                    $balanceIsAvailable = 0;
                } else {
                    $balanceIsAvailable = 1;
                }
            }
            if (!empty($orderUUID)){//订单单独支付
                $order = AdWeixinOrder::findOne(['uuid' => $orderUUID]);
                $total_price_pay_online = $order->price_min;
                if ($adOwner->total_available_balance < $order->price_min) {//判断账户余额是否足够支付
                    $balanceIsAvailable = 0;
                } else {
                    $balanceIsAvailable = 1;
                }
            }
            return $this->render('step-pay-confirm', [
                'plan' => $plan,
                'order' => $order,
                'total_price_pay_online' => $total_price_pay_online,
                'balanceIsAvailable' => $balanceIsAvailable
            ]);
        }
    }

    /**
     * 提交原创类
     * @return array
     */
    public function actionSubmitArrangeOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $planUUID = $request->post('plan_uuid');

            AdWeixinPlan::updateAll([
                'total_price_pay_online' => 0,
                'total_price_amount_min' => 0,
                'total_price_amount_max' => 0,
                'status' => AdWeixinPlan::STATUS_TO_DEAL
            ], ['uuid' => $planUUID]);

            AdWeixinOrder::updateAll([
                'status' => AdWeixinOrder::ORDER_STATUS_TO_ACCEPT
            ], ['plan_uuid' => $planUUID, 'status' => AdWeixinOrder::ORDER_STATUS_TO_SUBMIT]);

            return ['err_code' => 0, 'err_msg' => '保存成功'];
        }
    }

}