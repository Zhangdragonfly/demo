<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:29 PM
 */

namespace admin\modules\weixin\controllers;

use admin\controllers\BaseAppController;
use admin\helpers\AdminHelper;
use common\helpers\DateTimeHelper;
use common\helpers\MediaHelper;
use common\models\AdOwner;
use common\models\AdWeixinOrder;
use common\models\AdWeixinPlan;
use common\models\MediaExecutorAssign;
use common\models\MediaExecutor;
use common\models\MediaVendor;
use common\models\MediaWeixin;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use Yii;

/**
 * 投放计划管理
 * Class PlanController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class PlanController extends BaseAppController
{
    /**
     * 计划列表
     */
    public function actionList(){
        $request = Yii::$app->request;
        $query = new Query();
        $query->select([
            'ad_weixin_plan.uuid AS plan_uuid',
            'ad_weixin_plan.name as plan_name',
            'ad_weixin_plan.create_time',
            'ad_owner.uuid AS ad_owner_uuid',
            'ad_owner.comp_name',
            'ad_owner.contact_name',
            'ad_weixin_plan.total_follower_num',
            'ad_weixin_plan.budget_min',
            'ad_weixin_plan.budget_max',
            'ad_weixin_plan.media_amount',
            'ad_weixin_plan.status AS plan_status',
            'ad_weixin_plan.total_price_amount_min',
            'ad_weixin_plan.total_price_amount_max'
        ])
            ->from(['ad_weixin_plan' => AdWeixinPlan::tableName()])
            ->leftJoin(['ad_owner' => AdOwner::tableName()], 'ad_owner.uuid = ad_weixin_plan.ad_owner_uuid')
            ->orderBy(['ad_weixin_plan.create_time' => SORT_DESC]);
        if ($request->isPost) {
            $adOwnerName = $request->post('ad-owner-name', '');
            $searchName = $request->post('search-name', '');
            $planStatus = $request->post('plan-status', -1);
            $page = $request->post('page', 0);

            if (!empty($adOwnerName)) {
                $query->andWhere(['or', ['like', 'ad_owner.comp_name', $adOwnerName], ['like', 'ad_owner.contact_name', $adOwnerName]]);
            }
            if (!empty($searchName)) {
                $query->andWhere(['or',['like','ad_weixin_plan.uuid',$searchName],['like', 'ad_weixin_plan.name', $searchName]]);
            }
            if ($planStatus != -1) {
                $query->andWhere(['ad_weixin_plan.status' => $planStatus]);
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);

            return $this->render('list', [
                'dataProvider' => $dataProvider,
            ]);
        }
        if ($request->isGet) {
            $adOwnerUUID = $request->get('ad_owner_uuid', '');
            if (!empty($adOwnerUUID)) {
                $query->andWhere(['ad_owner.uuid' => $adOwnerUUID]);
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);
            return $this->render('list', [
                'dataProvider' => $dataProvider,
                'adOwnerUUID' => $adOwnerUUID
            ]);
        }
    }

    /**
     * 计划详情
     */
    public function actionDetail()
    {
        $request = Yii::$app->request;
        $planUUID = $request->get('plan-uuid');

        // 活动
        $plan = (new Query())
            ->select([
                'ad_owner.comp_name',
                'ad_owner.contact_name',
                'ad_owner.contact_1',
                'ad_owner.contact_2',
                'plan.name AS plan_name',
                'plan.budget_min',
                'plan.budget_max',
                'plan.publish_start_time',
                'plan.publish_end_time',
                'plan.plan_desc',
                'plan.comment AS plan_comment',
                'plan.total_execute_price_amount',
                'plan.require_deposit_amount',
                'plan.paid_deposit_amount',
                'plan.paid_amount',
                'plan.media_amount',
                'plan.total_follower_num',
                'plan.status',
                'plan.create_time AS plan_create_time',
                'plan.total_price_amount_min',
                'plan.total_price_amount_max'
            ])
            ->from(['plan' => AdWeixinPlan::tableName()])
            ->leftJoin(['ad_owner' => AdOwner::tableName()], 'ad_owner.uuid = plan.ad_owner_uuid')
            ->where(['plan.uuid' => $planUUID])
            ->one();

        // 订单
        $orderQuery = (new Query())
            ->select([
                'weixin.public_name',
                'weixin.public_id',
                'weixin.media_cate',
                'weixin.follower_area',
                'weixin.pub_config',
                'order.uuid AS order_uuid',
                'order.position_code',
                'order.status AS order_status',
                'order.price_min',
                'order.price_max',
                'order.execute_time',
                'vendor.name AS vendor_name',
                'vendor.contact_person AS vendor_contact_person'
            ])
            ->from(['order' => AdWeixinOrder::tableName()])
            ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = order.weixin_media_uuid')
            ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid = weixin.pref_vendor_uuid')
            //->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid = weixin.uuid')
            //->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid = media_executor_assign.executor_uuid')
            ->where(['order.plan_uuid' => $planUUID])
            ->orderBy(['order.create_time' => SORT_DESC]);

        $orderProvider = new ActiveDataProvider([
            'query' => $orderQuery,
            'pagination' => [
                'pageSize' => 50,
            ]
        ]);

        return $this->render('detail', [
            'plan' => $plan,
            'orderProvider' => $orderProvider
        ]);
    }

}