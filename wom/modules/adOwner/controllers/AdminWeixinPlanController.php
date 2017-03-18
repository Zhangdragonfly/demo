<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/29/16 11:29 AM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\MediaHelper;
use common\models\AdWeixinOrder;
use common\models\AdWeixinPlan;
use common\models\MediaWeixin;
use wom\controllers\BaseAppController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Response;

/**
 * 广告主个人中心/微信活动管理
 * Class AdminWeixinPlanController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminWeixinPlanController extends BaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 活动列表
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        $pageNum = 0;
        if ($request->isPost) {
            // 搜索
            $planStatus = $request->post('plan-status');
            $planName = trim($request->post('plan-name'));
            $mediaName = trim($request->post('media-name'));
            $pageNum = $request->post('page', 0);

            $loginAccountInfo = $this->getLoginAccountInfo();

            $query = (new Query())
                ->select([
                        'plan.uuid AS plan_uuid',
                        'plan.name AS plan_name',
                        'plan.total_follower_num',
                        'plan.total_price_amount_max',
                        'plan.create_time',
                        'plan.status'])
                ->distinct()
                ->from(['plan' => AdWeixinPlan::tableName()])
                ->orderBy(['plan.last_update_time' => SORT_DESC])
                ->where(['plan.ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid']]);

            if ($planStatus != -1) {
                $query->andWhere(['plan.status' => $planStatus]);
            }
            if (!empty($planName)) {
                $query->andWhere(['like', 'plan.name', $planName]);
            }
            if (!empty($mediaName)) {
                $query->innerJoin(['order' => AdWeixinOrder::tableName()], 'plan.uuid = order.plan_uuid');
                $query->innerJoin(['weixin' => MediaWeixin::tableName()], 'order.weixin_media_uuid = weixin.uuid');
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
            $planUUID = $request->get('plan_uuid');
            $loginAccountInfo = $this->getLoginAccountInfo();
            $query = (new Query())
                ->select([
                    'plan.uuid AS plan_uuid',
                    'plan.name as plan_name',
                    'plan.total_follower_num',
                    'plan.total_price_amount_max',
                    'plan.create_time',
                    'plan.status'])
                ->from(['plan' => AdWeixinPlan::tableName()])
                ->orderBy(['plan.last_update_time' => SORT_DESC])
                ->where(['plan.ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid']]);
            if (isset($planUUID)) {
                $query->andWhere(['plan.uuid' => $planUUID]);
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
    }

    /**
     * 更新活动
     */
    public function actionUpdate()
    {
        $this->layout = '//site-stage';
        $request = Yii::$app->request;
        if ($request->isGet) {
            $planUUID = $request->get('plan_uuid');
            $weixinPlan = AdWeixinPlan::find()
                ->where(['uuid' => $planUUID])
                ->one();

            $weixinOrderList = (new Query())
                ->select([
                    'weixin_order.uuid AS order_uuid',
                    'weixin.public_name',
                    'weixin.public_id',
                    'weixin.pub_config',
                    'weixin.follower_num AS total_follower_num',
                    'weixin.y_head_avg_view_cnt AS head_avg_read_num',
                    'weixin_order.position_content_conf',
                    'weixin_order.status AS order_status',
                    'weixin_order.pub_type AS order_pub_type'])
                ->from(['weixin_order' => AdWeixinOrder::tableName()])
                ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid  =  weixin_order.weixin_media_uuid')
                ->orderBy(['weixin_order.create_time' => SORT_DESC])
                ->where(['weixin_order.plan_uuid' => $planUUID])
                ->andWhere(['<>', 'weixin_order.status', AdWeixinOrder::ORDER_STATUS_DELETED])
                ->all();

            foreach ($weixinOrderList as $i => $weixinOrder) {

                $orderStatus = $weixinOrder['order_status'];
                $pubConfigArray = json_decode($weixinOrder['pub_config'], true);

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

                $orderConfig = [];
                $orderConfig['order_uuid'] = $weixinOrder['order_uuid'];
                $orderConfig['head_avg_read_num'] = $weixinOrder['head_avg_read_num'];
                $orderConfig['total_follower_num'] = $weixinOrder['total_follower_num'];

                if ($orderStatus == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT || $orderStatus == AdWeixinOrder::ORDER_STATUS_TO_PAY) {
                    $orderConfig['is_paid'] = 0;

                    $orderConfig['pos_s']['retail_price'] = $pubConfigArray['pos_s']['retail_price_min'];
                    $orderConfig['pos_s']['has_add_content'] = $posContentAddedConfig['pos_s'];;
                    $orderConfig['pos_s']['pub_type'] = $pubConfigArray['pos_s']['pub_type'];
                    $orderConfig['pos_s']['is_selected'] = $posContentAddedConfig['pos_s_selected'];
                    if ($posContentAddedConfig['pos_s_selected'] == 1) {
                        $weixinOrderList[$i]['pos_selected'] = '单图文';
                    }

                    $orderConfig['pos_m_1']['retail_price'] = $pubConfigArray['pos_m_1']['retail_price_min'];
                    $orderConfig['pos_m_1']['has_add_content'] = $posContentAddedConfig['pos_m_1'];;
                    $orderConfig['pos_m_1']['pub_type'] = $pubConfigArray['pos_m_1']['pub_type'];
                    $orderConfig['pos_m_1']['is_selected'] = $posContentAddedConfig['pos_m_1_selected'];
                    if ($posContentAddedConfig['pos_m_1_selected'] == 1) {
                        $weixinOrderList[$i]['pos_selected'] = '多图文头条';
                    }

                    $orderConfig['pos_m_2']['retail_price'] = $pubConfigArray['pos_m_2']['retail_price_min'];
                    $orderConfig['pos_m_2']['has_add_content'] = $posContentAddedConfig['pos_m_2'];;
                    $orderConfig['pos_m_2']['pub_type'] = $pubConfigArray['pos_m_2']['pub_type'];
                    $orderConfig['pos_m_2']['is_selected'] = $posContentAddedConfig['pos_m_2_selected'];
                    if ($posContentAddedConfig['pos_m_2_selected'] == 1) {
                        $weixinOrderList[$i]['pos_selected'] = '多图文2条';
                    }

                    $orderConfig['pos_m_3']['retail_price'] = $pubConfigArray['pos_m_3']['retail_price_min'];
                    $orderConfig['pos_m_3']['has_add_content'] = $posContentAddedConfig['pos_m_3'];;
                    $orderConfig['pos_m_3']['pub_type'] = $pubConfigArray['pos_m_3']['pub_type'];
                    $orderConfig['pos_m_3']['is_selected'] = $posContentAddedConfig['pos_m_3_selected'];
                    if ($posContentAddedConfig['pos_m_3_selected'] == 1) {
                        $weixinOrderList[$i]['pos_selected'] = '多图文3-N条';
                    }

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
                }
                if ($orderStatus == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT ||
                    $orderStatus == AdWeixinOrder::ORDER_STATUS_REFUSE ||
                    $orderStatus == AdWeixinOrder::ORDER_STATUS_FlOW ||
                    $orderStatus == AdWeixinOrder::ORDER_STATUS_CANCElED ||
                    $orderStatus == AdWeixinOrder::ORDER_STATUS_FINISHED
                ) {

                    $orderConfig['is_paid'] = 1;
                    $orderConfig['pos_selected'] = $weixinOrder['position_code'];
                    $orderConfig['order_status'] = $weixinOrder['status'];
                    if ($weixinOrder['order_pub_type'] == 1) {
                        // 直接投放
                        $orderConfig['order_status_label'] = MediaHelper::getWeixinDirectOrderStatusLabel($weixinOrder['pub_type']);
                    } else {
                        // 原创约稿
                        $orderConfig['order_status_label'] = MediaHelper::getWeixinArrangeOrderStatusLabel($weixinOrder['pub_type']);
                    }

                    $orderConfig['retail_price'] = $weixinOrder['price_max'];
                    $orderConfig['operate_action'] = [];
                    $orderConfig['pos_s']['pub_type'] = $pubConfigArray['pos_s']['pub_type'];
                    $orderConfig['pos_m_1']['pub_type'] = $pubConfigArray['pos_m_1']['pub_type'];
                    $orderConfig['pos_m_2']['pub_type'] = $pubConfigArray['pos_m_2']['pub_type'];
                    $orderConfig['pos_m_3']['pub_type'] = $pubConfigArray['pos_m_3']['pub_type'];
                }

                $weixinOrderList[$i]['order_config'] = json_encode($orderConfig);

            }

            return $this->render('update', [
                'weixinPlan' => $weixinPlan,
                'weixinOrderList' => $weixinOrderList
            ]);
        }
    }
}