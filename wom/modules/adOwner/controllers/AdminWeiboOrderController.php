<?php
/**
 * 广告主个人中心/微博订单管理
 * Class AdminWeiboOrderController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */

namespace wom\modules\adOwner\controllers;

use common\models\AdWeiboOrder;
use common\models\AdWeiboPlan;
use common\models\MediaVendor;
use common\models\MediaWeibo;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use Yii;

class AdminWeiboOrderController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 微博订单列表
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        $query = (new Query())
            ->select([
                'order.uuid as order_uuid',
                'order.weibo_name',
                'order.status',
                'order.sub_type',
                'order.price',
                'plan.uuid as plan_uuid',
                'plan.plan_name',
                'plan.plan_desc',
                'plan.execute_start_time',
                'plan.execute_end_time',
                'vendor.name'
            ])
            ->from(['order' => AdWeiboOrder::tableName()])
            ->leftJoin(['plan' => AdWeiboPlan::tableName()], 'order.plan_uuid  =  plan.uuid')
            ->leftJoin(['vendor' => MediaVendor::tableName()], 'order.vendor_uuid  = vendor.uuid')
            ->andWhere(['plan.ad_owner_uuid' => $ad_owner_uuid])
            ->orderBy(['order.update_time' => SORT_DESC]);
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $weibo_name = $request->post('weibo_name');
            $order_status = $request->post('order_status');
            $execute_start_time = strtotime($request->post('execute_start_time'));
            $execute_end_time = strtotime($request->post('execute_end_time'));
            if (!empty($weibo_name)) {
                $query->andWhere(['or', ['like', 'order.weibo_name', $weibo_name]]);
            }
            if ($order_status != -1) {
                $query->andWhere(['order.status' => $order_status]);
            }
            if (!empty($execute_start_time) && !empty($execute_end_time)) {
                $query->andWhere(['between', 'plan.execute_start_time', $execute_start_time, $execute_end_time]);
            } elseif (!empty($execute_start_time)) {
                $query->andWhere(['>=', 'plan.execute_start_time', $execute_start_time]);
            } elseif (!empty($execute_end_time)) {
                $query->andWhere(['<=', 'plan.execute_start_time', $execute_end_time]);
            }
        }
        $pager = new Pagination(['totalCount' => $query->count()]);
        $pager->pageSize = 10;
        $pager->page = $page;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pager->pageSize,
                'page' => $pager->page
            ]
        ]);

        Yii::trace($query->count(), 'dev\#' . __METHOD__);

        return $this->render('list', [
            'dataProvider' => $dataProvider->getModels(),
            'pager' => $pager
        ]);
    }


    //微博订单详情
    public function actionDetail()
    {
        $this->layout = '//site-stage';
        $request = Yii::$app->request;
        if ($request->isGet) {
            $order_uuid = $request->get('order_uuid');
            $weiboOrder = AdWeiboOrder::findOne(['uuid' => $order_uuid]);
            $weiboPlan = AdWeiboPlan::findOne(['uuid' => $weiboOrder->plan_uuid]);
            return $this->render('detail', [
                'weiboOrder' => $weiboOrder,
                'weiboPlan' => $weiboPlan,
            ]);
        } else {
            return $this->render('detail');
        }
    }

}