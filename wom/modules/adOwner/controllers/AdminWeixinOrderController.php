<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/29/16 11:29 AM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\helpers\SendNoticeHelper;
use common\models\AdWeixinOrder;
use common\models\AdWeixinOrderDirectContent;
use common\models\AdWeixinOrderPublishResult;
use common\models\AdWeixinOrderTrack;
use common\models\AdWeixinPlan;
use common\models\MediaWeixin;
use yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\data\Pagination;

/**
 * 广告主个人中心/微信订单管理
 * Class AdminWeixinOrderController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminWeixinOrderController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 订单列表
     */
    public function actionList(){
        $request = Yii::$app->request;
        $pageNum = 0;
        $query = (new Query())
        ->select([
            'order.uuid AS order_uuid',
            'order.plan_uuid',
            'order.position_code as pos_code',
            'order.price_min',
            'order.paid_amount',
            'order.pub_type',
            'order.publish_start_time',
            'order.publish_end_time',
            'order.status AS order_status',
            'plan.uuid AS plan_uuid',
            'plan.name as plan_name',
            'weixin.public_name as weixin_name',
            'weixin.public_id as weixin_id'
        ])
        ->from(['order' => AdWeixinOrder::tableName()]);
        $query->leftJoin(['plan' => AdWeixinPlan::tableName()], 'order.plan_uuid = plan.uuid');
        $query->leftJoin(['weixin' => MediaWeixin::tableName()], 'order.weixin_media_uuid = weixin.uuid');
        $query->orderBy(['order.create_time' => SORT_DESC]);
        $query->andWhere(['plan.ad_owner_uuid' => self::getLoginAccountInfo()['ad-owner-uuid']]);
        $query->andWhere(['<>', 'order.status', AdWeixinOrder::ORDER_STATUS_DELETED]);
        //GET参数
        if ($request->isGet) {
            $planUUID = $request->get('plan_uuid');
            if (isset($planUUID)) {
                $query->andWhere(['order.plan_uuid' => $planUUID]);
            }
        }
        //POST参数
        if ($request->isPost) {
            $orderStatus = $request->post('order-status');
            $secondarySelectOption = $request->post('secondary-select-option');
            $secondarySelectValue = trim($request->post('secondary-select-value', ''));
            $pageNum = $request->post('page', 0);
            if($orderStatus != -11){
                $query->andWhere(['order.status' => $orderStatus]);
            }
            if($secondarySelectOption == 'account' && !empty($secondarySelectValue)){
                $query->andWhere(['or', ['like', 'weixin.public_name', $secondarySelectValue], ['like', 'weixin.public_id', $secondarySelectValue]]);
            }
            if($secondarySelectOption == 'plan-name' && !empty($secondarySelectValue)){
                $query->andWhere(['like', 'plan.name', $secondarySelectValue]);
            }
        }
        $pager = new Pagination(['totalCount' => $query->count()]);
        $pager->pageSize = 10;
        $pager->page = $pageNum;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pager->pageSize,
                'page' => $pager->page
            ]
        ]);
        return $this->render('list', [
            'dataResult' => $dataProvider->getModels(),
            'pager' => $pager
        ]);

    }

    /**
     *  获取执行链接
     */
    public function actionGetExecuteLink(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->get('order_uuid');
            $publishResult = AdWeixinOrderPublishResult::findOne(['order_uuid' => $orderUUID]);
            $orderTrack = AdWeixinOrderTrack::find()
                ->where(['order_uuid' => $orderUUID, 'track_type' => AdWeixinOrderTrack::TRACK_TYPE_PUBLISH_ORDER])
                ->one();
            return ['err_code' => 0, 'publish_result' => $publishResult, 'order_track' => $orderTrack];
        }
    }

    /**
     * 确认执行链接
     */
    public function actionToVerifyExecuteLink(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $result = $this->updateOrderStatus($orderUUID, AdWeixinOrder::ORDER_STATUS_CONFIRM_LINK);
            if ($result) {// TODO URL文章监控
                return ['err_code' => 0, 'err_msg' => '执行链接确认成功'];
            } else {
                return ['err_code' => 1, 'err_msg' => '执行链接确认失败'];
            }
        }
    }

    /**
     * 反馈执行链接
     */
    public function actionFeedbackExecuteLink(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $orderFeedback = $request->post('order_feedback');
            $result = $this->updateOrderStatus($orderUUID, AdWeixinOrder::ORDER_STATUS_TO_CONFTRM_FEEDBACK);
            if ($result) {
                $orderTrack = new AdWeixinOrderTrack();
                $orderTrack->uuid = PlatformHelper::getUUID();
                $orderTrack->order_uuid = $orderUUID;
                $orderTrack->owner_type = AdWeixinOrderTrack::AD_OWNER;
                $orderTrack->content = $orderFeedback;
                $orderTrack->comment = '广告主反馈执行链接';
                $orderTrack->create_time = time();
                $orderTrack->track_type = AdWeixinOrderTrack::TRACK_TYPE_FEEDBACK_EXECUTE_LINK;
                if ($orderTrack->save()) {
                    return ['err_code' => 0, 'err_msg' => '执行链接反馈成功'];
                } else {
                    return ['err_code' => 2, 'err_msg' => '执行链接反馈内容添加失败'];
                }
            } else {
                return ['err_code' => 1, 'err_msg' => '执行链接反馈失败'];
            }
        }
    }

    /**
     * 获取(直接投放)订单详情
     */
    public function actionGetDirectOrderDetail(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->get('order_uuid');
            $orderDetail = (new Query())
                ->select(['plan.name AS plan_name',
                    'order.execute_time',
                    'content.original_mp_url',
                    'content.title',
                    'content.author',
                    'content.cover_img',
                    'content.article_content',
                    'content.link_url',
                    'content.article_short_desc',
                    'content.cert_img_urls',
                    'content.comment'])
                ->from(['order' => AdWeixinOrder::tableName()])
                ->where(['order.uuid' => $orderUUID])
                ->leftJoin(['plan' => AdWeixinPlan::tableName()], 'plan.uuid = order.plan_uuid')
                ->leftJoin(['content' => AdWeixinOrderDirectContent::tableName()], 'content.order_uuid = order.uuid')
                ->one();
            $orderDetail['execute_time'] = date('Y-m-d H:i', $orderDetail['execute_time']);
            return ['err_code' => 0, 'order_detail' => $orderDetail];
        }
    }

    /**
     * 获取流单原因
     */
    public function actionGetOrderReason(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->get('order_uuid');
            $reasonType = $request->get('type');
            if($reasonType == "flow"){
                $orderTrack = AdWeixinOrderTrack::findOne(['order_uuid' => $orderUUID, 'track_type' => AdWeixinOrderTrack::TRACK_TYPE_FLOW_ORDER]);
            }
            if($reasonType == "refuse"){
                $orderTrack = AdWeixinOrderTrack::findOne(['order_uuid' => $orderUUID, 'track_type' => AdWeixinOrderTrack::TRACK_TYPE_REFUSED_ORDER]);
            }
            return ['err_code' => 0, 'refuse_content' => $orderTrack->content];
        }
    }

    /**
     * 取消订单
     */
    public function actionCancelOrder(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->get('order_uuid');
            $order = AdWeixinOrder::findOne(['uuid' => $orderUUID]);
            $order->status = AdWeixinOrder::ORDER_STATUS_CANCElED;
            if($order->save()){
                $plan = AdWeixinPlan::findOne(['uuid'=>$order->plan_uuid]);
                $plan->status = AdWeixinPlan::STATUS_CANCEL;
                $plan->save();
                return ['err_code' => 0, 'err_msg' => '订单取消成功'];
            }else{
                return ['err_code' => 1, 'err_msg' => '订单取消失败'];
            }

        }
    }


    /**
     * 更新订单状态
     * @param $orderUUID 订单id
     * @param $status    订单状态
     * @return bool
     */
    public function updateOrderStatus($orderUUID, $status){
        $order = AdWeixinOrder::findOne(['uuid' => $orderUUID]);
        $order->status = $status;
        $order->last_update_time = time();
        return $order->save();
    }




}