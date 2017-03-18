<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:31 PM
 */

namespace admin\modules\weixin\controllers;

use admin\helpers\AdminHelper;
use common\models\AdWeixinPlan;
use common\models\MediaWeixin;
use common\models\WomAccount;
use common\helpers\DateTimeHelper;
use Yii;
use admin\controllers\BaseAppController;
use common\models\AdWeixinOrder;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use common\models\MediaVendor;

/**
 * Class OrderController
 * @package admin\modules\weixin\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class OrderController extends BaseAppController
{
    /**
     * 预约订单列表
     */
    public function actionOrderList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $planName = $request->post('plan-name', ''); // 名称
            $orderId = $request->post('order-id', ''); // 订单id
            $publicName = $request->post('public-name', ''); // public name or public id
            $vendor_name = $request->post('vendor-name', ''); // weixin
            $orderCreateTimeRange = $request->post('order-create-time-range', ''); // 订单新建时间
            $orderAcceptTimeRange = $request->post('order-accept-time-range', ''); // 接单时间
            $vendor_uuid = $request->get('vender_uuid', '');
            $page = $request->post('page', 0);

            $query = (new Query())
                ->select('order.uuid AS order_uuid, plan.name AS plan_name, weixin.public_name AS weixin_public_name, weixin.public_id AS weixin_public_id, order.position_code, order.is_fixed_price, order.price_min, order.price_max, order.execute_price, order.create_time AS order_create_time, order.execute_time, order.status AS order_status')
                ->from(['order' => AdWeixinOrder::tableName()])
                ->leftJoin(['plan' => AdWeixinPlan::tableName()], 'plan.uuid = order.plan_uuid')
                ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = order.weixin_media_uuid')
                ->leftJoin(['media_vendor' => MediaVendor::tableName()], 'media_vendor.uuid = order.vendor_uuid');

            if (!empty($planName)) {
                $query->andWhere(['like', 'plan.name', $planName]);
            }
            if (!empty($vendor_uuid)) {
                $name = MediaVendor::findOne(['uuid' => $vendor_uuid]);
                $query->andWhere(['media_vendor.uuid' => $vendor_uuid]);
            }
            if (!empty($vendor_name)) {
                $query->andWhere(['like', 'media_vendor.contact_person', $vendor_name]);
            }
            if (!empty($orderId)) {
                $query->andWhere(['order.uuid' => $orderId]);
            }
            if (!empty($publicName)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $publicName], ['like', 'weixin.public_id', $publicName]]);
            }
            if (!empty($orderCreateTimeRange)) {
                $createTimeRange = DateTimeHelper::getStartEndDateFromRange($orderCreateTimeRange);
                $startDate = $createTimeRange['startDate'];
                $endDate = $createTimeRange['endDate'];
                $query->andWhere(['between', 'order.execute_time', $startDate, $endDate]);

            }
            if (!empty($orderAcceptTimeRange)) {
                //TODO 订单接单时间
                $acceptTimeRange = DateTimeHelper::getStartEndDateFromRange($orderCreateTimeRange);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);

            return $this->render('list', [
                'dataProvider' => $dataProvider
            ]);
        } else {
            $request = Yii::$app->request;
            $mediaUUID = $request->get('media-uuid', ''); // weixin media uuid
            $mediaPublicId = $request->get('media-public-id', ''); // 媒体public id
            $vendorUUID = $request->get('vender-uuid', ''); // media vendor uuid

            $query = (new Query())
                ->select('order.uuid AS order_uuid, plan.name AS plan_name, weixin.public_name AS weixin_public_name, weixin.public_id AS weixin_public_id, order.position_code, order.is_fixed_price, order.price_min, order.price_max, order.execute_price, order.create_time AS order_create_time, order.execute_time, order.status AS order_status')
                ->from(['order' => AdWeixinOrder::tableName()])
                ->leftJoin(['plan' => AdWeixinPlan::tableName()], 'plan.uuid = order.plan_uuid')
                ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = order.weixin_media_uuid')
                ->leftJoin(['media_vendor' => MediaVendor::tableName()], 'media_vendor.uuid = order.vendor_uuid');

            if (!empty($mediaUUID)) {
                $query->andWhere(['order.weixin_media_uuid' => $mediaUUID]);
            }

            if (!empty($vendorUUID)) {
                $query->andWhere(['media_vendor.uuid' => $vendorUUID]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);

            return $this->render('order-list', [
                'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * 预约订单详情
     */
    public function actionOrderDetail(){
        $request = Yii::$app->request;
        return $this->render('order-detail');
    }



    /**
     * 直投订单列表
     */
    public function actionPutList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $planName = $request->post('plan-name', ''); // 名称
            $orderId = $request->post('order-id', ''); // 订单id
            $publicName = $request->post('public-name', ''); // public name or public id
            $vendor_name = $request->post('vendor-name', ''); // weixin
            $orderCreateTimeRange = $request->post('order-create-time-range', ''); // 订单新建时间
            $orderAcceptTimeRange = $request->post('order-accept-time-range', ''); // 接单时间
            $vendor_uuid = $request->get('vender_uuid', '');
            $page = $request->post('page', 0);

            $query = (new Query())
                ->select('order.uuid AS order_uuid, plan.name AS plan_name, weixin.public_name AS weixin_public_name, weixin.public_id AS weixin_public_id, order.position_code, order.is_fixed_price, order.price_min, order.price_max, order.execute_price, order.create_time AS order_create_time, order.execute_time, order.status AS order_status')
                ->from(['order' => AdWeixinOrder::tableName()])
                ->leftJoin(['plan' => AdWeixinPlan::tableName()], 'plan.uuid = order.plan_uuid')
                ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = order.weixin_media_uuid')
                ->leftJoin(['media_vendor' => MediaVendor::tableName()], 'media_vendor.uuid = order.vendor_uuid');

            if (!empty($planName)) {
                $query->andWhere(['like', 'plan.name', $planName]);
            }
            if (!empty($vendor_uuid)) {
                $name = MediaVendor::findOne(['uuid' => $vendor_uuid]);
                $query->andWhere(['media_vendor.uuid' => $vendor_uuid]);
            }
            if (!empty($vendor_name)) {
                $query->andWhere(['like', 'media_vendor.contact_person', $vendor_name]);
            }
            if (!empty($orderId)) {
                $query->andWhere(['order.uuid' => $orderId]);
            }
            if (!empty($publicName)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $publicName], ['like', 'weixin.public_id', $publicName]]);
            }
            if (!empty($orderCreateTimeRange)) {
                $createTimeRange = DateTimeHelper::getStartEndDateFromRange($orderCreateTimeRange);
                $startDate = $createTimeRange['startDate'];
                $endDate = $createTimeRange['endDate'];
                $query->andWhere(['between', 'order.execute_time', $startDate, $endDate]);

            }
            if (!empty($orderAcceptTimeRange)) {
                //TODO 订单接单时间
                $acceptTimeRange = DateTimeHelper::getStartEndDateFromRange($orderCreateTimeRange);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);

            return $this->render('list', [
                'dataProvider' => $dataProvider
            ]);
        } else {
            $request = Yii::$app->request;
            $mediaUUID = $request->get('media-uuid', ''); // weixin media uuid
            $mediaPublicId = $request->get('media-public-id', ''); // 媒体public id
            $vendorUUID = $request->get('vender-uuid', ''); // media vendor uuid

            $query = (new Query())
                ->select('order.uuid AS order_uuid, plan.name AS plan_name, weixin.public_name AS weixin_public_name, weixin.public_id AS weixin_public_id, order.position_code, order.is_fixed_price, order.price_min, order.price_max, order.execute_price, order.create_time AS order_create_time, order.execute_time, order.status AS order_status')
                ->from(['order' => AdWeixinOrder::tableName()])
                ->leftJoin(['plan' => AdWeixinPlan::tableName()], 'plan.uuid = order.plan_uuid')
                ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = order.weixin_media_uuid')
                ->leftJoin(['media_vendor' => MediaVendor::tableName()], 'media_vendor.uuid = order.vendor_uuid');

            if (!empty($mediaUUID)) {
                $query->andWhere(['order.weixin_media_uuid' => $mediaUUID]);
            }

            if (!empty($vendorUUID)) {
                $query->andWhere(['media_vendor.uuid' => $vendorUUID]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);

            return $this->render('put-list', [
                'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * 直投订单详情
     */
    public function actionPutDetail(){
        $request = Yii::$app->request;
        return $this->render('put-detail');
    }


}