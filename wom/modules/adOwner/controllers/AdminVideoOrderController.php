<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/29/16 11:29 AM
 */

namespace wom\modules\adOwner\controllers;

use common\models\AdVideoOrder;
use common\models\AdVideoPlan;
use common\models\MediaVendor;
use common\models\MediaVideo;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii;
/**
 * 广告主个人中心/视频订单管理
 * Class AdminVideoOrderController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminVideoOrderController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 视频订单列表
     */
    public function actionList(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        $query = (new Query())
        ->select([
            'order.uuid as order_uuid',
            'order.account_name',
            'order.account_id',
            'order.status',
            'order.sub_type',
            'order.price',
            'order.sex',
            'order.platform_type',
            'order.platform_uuid',
            'plan.uuid as plan_uuid',
            'plan.plan_name',
            'plan.plan_desc',
            'plan.execute_start_time',
            'plan.execute_end_time',
            'vendor.name'
        ])
        ->from(['order' => AdVideoOrder::tableName()])
        ->leftJoin(['plan' => AdVideoPlan::tableName()], 'order.plan_uuid  =  plan.uuid')
        ->leftJoin(['vendor' => MediaVendor::tableName()], 'order.vendor_uuid  = vendor.uuid')
        ->andWhere(['plan.ad_owner_uuid'=>$ad_owner_uuid])
        ->orderBy(['order.last_update_time' => SORT_DESC]);
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $account_name = $request->post('account_name');
            $order_status = $request->post('order_status');
            $execute_start_time = strtotime($request->post('execute_start_time'));
            $execute_end_time = strtotime($request->post('execute_end_time'));
            if (!empty($account_name)){
                $query->andWhere(['or', ['like', 'order.account_name', $account_name]]);
            }
            if ($order_status != -1){
                $query->andWhere(['order.status'=>$order_status]);
            }
            if (!empty($execute_start_time) && !empty($execute_end_time)){
                $query->andWhere(['between','plan.execute_start_time',$execute_start_time,$execute_end_time]);
            }elseif(!empty($execute_start_time)){
                $query->andWhere(['>=','plan.execute_start_time',$execute_start_time]);
            }elseif(!empty($execute_end_time)){
                $query->andWhere(['<=','plan.execute_start_time',$execute_end_time]);
            }
        }
        $pager = new Pagination(['totalCount' => $query->count()]);
        $pager->pageSize = 10;
        $pager->page = $page;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' =>  $pager->pageSize,
                'page' =>  $pager->page
            ]
        ]);
        return $this->render('list', [
            'dataProvider' =>  $dataProvider->getModels(),
            'pager' => $pager
        ]);
    }


    //视频订单详情
    public function actionDetail(){
        $this->layout = '//site-stage';
        $request = Yii::$app->request;
        if ($request->isGet) {
            $order_uuid = $request->get('order_uuid');
            $videoOrder = AdVideoOrder::findOne(['uuid'=>$order_uuid]);
            $videoPlan = AdVideoPlan::findOne(['uuid'=>$videoOrder->plan_uuid]);
            return $this->render('detail',[
                'videoOrder'=>$videoOrder,
                'videoPlan'=>$videoPlan,
            ]);
        }else{
            return $this->render('detail');
        }
    }




}