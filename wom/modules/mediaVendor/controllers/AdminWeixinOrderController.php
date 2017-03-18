<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/9/16 10:21
 */
namespace wom\modules\mediaVendor\controllers;

use common\helpers\PlatformHelper;
use common\helpers\SendNoticeHelper;
use common\models\AdWeixinOrder;
use common\models\AdWeixinOrderDirectContent;
use common\models\AdWeixinOrderPublishResult;
use common\models\WomDirectOrderTimeLineCtl;
use common\models\AdWeixinOrderTrack;
use common\models\AdWeixinPlan;
use common\models\MediaWeixin;
use yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\data\Pagination;

/**
 * 媒体主个人中心/微信订单管理
 * Class AdminWeixinOrderController
 * @package wom\modules\mediaVendor\controllers
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class AdminWeixinOrderController extends MediaVendorBaseAppController
{
    public $layout = '//admin-media-vendor';

    /**
     * 订单列表
     */
    public function actionList(){
        $request = Yii::$app->request;
        $pageNum = 0;
        if ($request->isPost) {
            // 搜索
            $planName = trim($request->post('plan_name'));
            $mediaName = trim($request->post('media_name'));
            $pageNum = $request->post('page', 0);

            $query = (new Query())
            ->select('
                order.uuid AS order_uuid,
                order.position_code,
                order.paid_amount,
                order.pub_type,
                order.publish_start_time,
                order.publish_end_time,
                order.status,
                plan.name as plan_name,
                weixin.public_name as weixin_name,
                weixin.public_id as weixin_id
             ')
            ->from(['order' => AdWeixinOrder::tableName()]);
            $query->leftJoin(['plan' => AdWeixinPlan::tableName()], 'order.plan_uuid = plan.uuid');
            $query->leftJoin(['weixin' => MediaWeixin::tableName()], 'order.weixin_media_uuid = weixin.uuid');
            if (!empty($planName)) {
                $query->andWhere(['like', 'plan.name', $planName]);
            }
            if (!empty($mediaName)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $mediaName], ['like', 'weixin.public_id', $mediaName]]);
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

        if ($request->isGet) {
            $query = (new Query())
            ->select([
                'order.uuid AS order_uuid',
                'order.position_code AS pos_code',
                'order.paid_amount',
                'order.pub_type',
                'order.price_min',
                'order.price_max',
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
            $query->andWhere(['order.vendor_uuid' => self::getLoginAccountInfo()['media-vendor-uuid']]);
            $query->andWhere(['in', 'order.status', [
                AdWeixinOrder::ORDER_STATUS_TO_ACCEPT,
//                AdWeixinOrder::ORDER_STATUS_CANCElED,
                AdWeixinOrder::ORDER_STATUS_REFUSE,
                AdWeixinOrder::ORDER_STATUS_FlOW,
                AdWeixinOrder::ORDER_STATUS_FINISHED,
                AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_LINK,
                AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_RESULT,
                AdWeixinOrder::ORDER_STATUS_CONFIRM_LINK,
                AdWeixinOrder::ORDER_STATUS_TO_CONFTRM_FEEDBACK
            ]]);

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
    }

//    /**
//     * 获取执行结果
//     * @param $pub_type
//     * @param $status
//     * @return string
//     */
//    public function getExecuteResult($pub_type, $status)
//    {
//        $executeResult = 'show-empty';
//        switch ($status) {
//            case AdWeixinOrder::ORDER_STATUS_FINISHED:
//                // 已完成
//                $executeResult = 'show_execute_link,show_effect_shots';
//                break;
//            case AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_RESULT:
//                if ($pub_type == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
//                    // 待效果截图
//                    $executeResult = 'show_execute_link';
//                }
//                break;
//            case AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_PREVIEW:
//                if ($pub_type == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
//                    // 已反馈
//                    $executeResult = 'show_execute_link';
//                }
//                break;
//            case AdWeixinOrder::ORDER_STATUS_TO_CONFIRM_PREVIEW:
//                if ($pub_type == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
//                    // 待提交执行链接
//                    $executeResult = 'show_execute_link';
//                }
//                break;
//            default:
//                $executeResult = '默认';
//                break;
//        }
//        return '[' . $executeResult . ']';
//    }

    /**
     * 拒单
     */
    public function actionRefuseOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $content = $request->post('content');
            $result = $this->updateOrderStatus($orderUUID, AdWeixinOrder::ORDER_STATUS_REFUSE);
            if ($result == 1) {
                $orderTrack = new AdWeixinOrderTrack();
                $orderTrack->uuid = PlatformHelper::getUUID();
                $orderTrack->order_uuid = $orderUUID;
                $orderTrack->owner_type = AdWeixinOrderTrack::MEDIA_VENDOR;
                $orderTrack->content = $content;
                $orderTrack->comment = '';
                $orderTrack->create_time = time();
                $orderTrack->track_type = AdWeixinOrderTrack::TRACK_TYPE_REFUSED_ORDER;
                if ($orderTrack->save()) {
                    return ['err_code' => 0, 'err_msg' => '拒单成功'];
                } else {
                    return ['err_code' => 1, 'err_msg' => '拒单理由提交失败'];
                }
            } else {
                return ['err_code' => 2, 'err_msg' => '拒单提交失败'];
            }
        }
    }

    /**
     * 媒体主接单
     */
    public function actionAcceptOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $result = AdWeixinOrder::updateAll(['status' => AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_LINK], ['uuid' => $orderUUID]);
            if ($result) {
                //任务时间控制
                $orderTimeLine = WomDirectOrderTimeLineCtl::findOne(['order_uuid'=>$orderUUID]);
                $executeTime =  $orderTimeLine->execute_time;
                $orderTimeLine->execute_validity_time = $executeTime+(2*3600);//执行有效时间
                $orderTimeLine->accept_time = time();
                $orderTimeLine->save();
                //给广告主发送短信
//                SendNoticeHelper::send(SendNoticeHelper::TYPE_SMS, "13381872702", 154326);
                return ['err_code' => 0, 'err_msg' => '接单成功'];
            } else {
                return ['err_code' => 1, 'err_msg' => '接单失败'];
            }
        }
    }

    /**
     * 媒体主提交执行链接
     */
    public function actionSubmitExecuteLink(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $executeLink = $request->post('execute_link');
            $screenshotName = $request->post('screenshot_name');
            $screenshotName = substr($screenshotName,0,-1);

            // TODO 判断链接中文章作者是否是订单中的微信公众号
            AdWeixinOrder::updateAll(['status' => AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_RESULT], ['uuid' => $orderUUID]);
            $publishResult = AdWeixinOrderPublishResult::findOne(['order_uuid' => $orderUUID]);
            if ($publishResult === null) {
                $publishResult = new AdWeixinOrderPublishResult();
                $publishResult->order_uuid = $orderUUID;
                $publishResult->publish_url = $executeLink;
                $publishResult->publish_screenshot = $screenshotName;
                $publishResult->create_time = time();
                $publishResult->update_time = time();
                $publishResult->save();
                //任务时间控制
                $orderTimeLine = WomDirectOrderTimeLineCtl::findOne(['order_uuid'=>$orderUUID]);
                $executeTime =  $orderTimeLine->execute_time;
                $submitBtnOpenTime = strtotime(date('Ymd',$executeTime+(3*24*3600)));//效果截图"提交"按钮打开时间（执行时间3天后的零点）
                $orderTimeLine->execute_real_time = time();//提交执行链接时间
                $orderTimeLine->not_confirm_execute_time = strtotime(date('Ymd')) + 86400;//系统默认执行通过时间今天24点
                $orderTimeLine->screenshot_commit_btn_open_time = $submitBtnOpenTime;
                $orderTimeLine->screenshot_submit_validity_time = $submitBtnOpenTime+(48*3600);//效果截图提交有效时间（提交按钮打开的48小时后）
                $orderTimeLine->save();
            } else {
                $publishResult->publish_url = $executeLink;
                $publishResult->publish_screenshot = $screenshotName;
                $publishResult->update_time = time();
                $publishResult->save();
            }
            return ['err_code' => 0, 'err_msg' => '执行链接提交成功'];
        }
    }

    /**
     * 媒体主提交效果截图
     */
    public function actionSubmitEffectShots()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $screenshotName = $request->post('screenshot_name');
            $screenshotName = substr($screenshotName,0,-1);
            //订单已完成
            $order = AdWeixinOrder::findOne(['uuid' => $orderUUID]);
            $order->status = AdWeixinOrder::ORDER_STATUS_FINISHED;
            $order->save();
            //保存截图
            $publishResult = AdWeixinOrderPublishResult::findOne(['order_uuid' => $orderUUID]);
            $publishResult->publish_effect_screenshot = $screenshotName;
            $publishResult->save();
            //任务时间控制
            $orderTimeLine = WomDirectOrderTimeLineCtl::findOne(['order_uuid'=>$orderUUID]);
            $orderTimeLine->screenshot_submit_time = time();//提交效果截图时间
            $orderTimeLine->save();
            return ['err_code' => 0, 'err_msg' => '效果截图提交成功'];
        }
    }

    /**
     * 获取广告主执行链接的反馈
     */
    public function actionGetOrderFeedback()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $orderTrack = AdWeixinOrderTrack::findOne(['order_uuid' => $orderUUID, 'track_type' => AdWeixinOrderTrack::TRACK_TYPE_FEEDBACK_EXECUTE_LINK]);
            return ['err_code' => 0, 'order_track' => $orderTrack];
        }
    }

    /**
     * 通过微信文章URL获取发布者微信id
     * @param $url 微信文章链接
     * @return mixed
     */
    public function getWeixinIdByUrl($url)
    {
        $contents = @file_get_contents($url);
        $contents = explode('profile_meta_value">', $contents);
        if (empty($contents[1])) {
            return null;
        }
        $contents = $contents[1];
        $contents = explode('</span>', $contents);
        return $contents[0];
    }

    /**
     * 获取(直接投放)订单详情
     */
    public function actionGetDirectOrderDetail()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $row = (new Query())
            ->select('plan.name as plan_name,
                      order.execute_time,
                      content.original_mp_url,
                      content.title,
                      content.author,
                      content.cover_img,
                      content.article_content,
                      content.link_url,
                      content.article_short_desc,
                      content.cert_img_urls,
                      content.comment
                    ')
            ->from(['order' => AdWeixinOrder::tableName()])
            ->where(['order.uuid' => $orderUUID])
            ->leftJoin(['plan' => AdWeixinPlan::tableName()], 'plan.uuid = order.plan_uuid')
            ->leftJoin(['content' => AdWeixinOrderDirectContent::tableName()], 'content.order_uuid = order.uuid')
            ->one();
            $row['execute_time'] = date('Y.m.d H:i:s', $row['execute_time']);
            return ['err_code' => 0, 'detail' => $row];
        }
    }

    /**
     * 获取原因
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
     * 更新订单状态
     * @param $orderUUID
     * @param $status
     * @return int
     */
    public function updateOrderStatus($orderUUID, $status){
        $rnt = AdWeixinOrder::updateAll(['status' => $status], ['uuid' => $orderUUID]);
        return $rnt;
    }

}