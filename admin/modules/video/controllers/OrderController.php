<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:31 PM
 */

namespace admin\modules\video\controllers;

use admin\helpers\AdminHelper;
use common\models\AdOwner;
use common\models\AdVideoPlan;
use common\models\AdVideoOrder;
use common\models\WomAccount;
use common\models\MediaVendor;
use common\helpers\DateTimeHelper;
use Yii;
use admin\controllers\BaseAppController;
use yii\data\ActiveDataProvider;
use yii\db\Query;


/**
 * Class OrderController
 * @package admin\modules\weixin\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class OrderController extends BaseAppController
{
    /**
     * 订单列表
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        $query = (new Query())
        ->select([
            'order.uuid as order_uuid',
            'order.account_name',
            'order.account_id',
            'order.platform_type',
            'order.status',
            'order.sub_type',
            'order.price',
            'plan.uuid',
            'plan.plan_name',
            'plan.execute_start_time',
            'plan.execute_end_time',
            'vendor.name'
        ])
        ->from(['order' => AdVideoOrder::tableName()])
        ->leftJoin(['plan'=>AdVideoPlan::tableName()], 'order.plan_uuid  =  plan.uuid')
        ->leftJoin(['vendor'=>MediaVendor::tableName()], 'vendor.uuid  =  order.vendor_uuid')
        ->orderBy(['order.last_update_time' => SORT_DESC]);
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $plan_name = $request->post('plan_name');
            $account_name = $request->post('account_name');
            $account_id = $request->post('account_id');
            $status = $request->post('order_status');
            $execute_start_time = strtotime($request->post('execute_start_time'));
            $execute_end_time = strtotime($request->post('execute_end_time'));
            if (!empty($plan_name)){
                $query->andWhere(['or',['like','plan.plan_name',$plan_name]]);
            }
            if (!empty($account_name)){
                $query->andWhere(['or',['like','order.account_name',$account_name]]);
            }
            if (!empty($account_id)){
                $query->andWhere(['or',['like','order.account_id',$account_id]]);
            }
            if ($status != -1){
                $query->andWhere(['order.status'=>$status]);
            }
            if (!empty($execute_start_time) && !empty($execute_end_time)){
                $query->andWhere(['between','plan.execute_start_time',$execute_start_time,$execute_end_time]);
            }elseif(!empty($execute_start_time)){
                $query->andWhere(['>=','plan.execute_start_time',$execute_start_time]);
            }elseif(!empty($execute_end_time)){
                $query->andWhere(['<=','plan.execute_start_time',$execute_end_time]);
            }
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
    }

    /**
     *  视频订单详情页
     */
    public function actionDetail(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            $order_uuid= $request->get('uuid');
            $query = (new Query())
            ->select([
                'plan.plan_name',
                'plan.plan_desc',
                'plan.contacts',
                'plan.phone',
                'plan.execute_start_time',
                'plan.execute_end_time',
                'plan.feedback_time',
                'order.uuid',
                'order.status',
                'order.account_name',
                'order.accept_remark',
                'order.follower_num',
                'order.sub_type',
                'order.price',
                'order.create_time',
                'order.execute_person_uuid',
                'order.execute_remark',
                'order.execute_price',
                'vendor.name'
            ])
            ->from(['plan' => AdVideoPlan::tableName()])
            ->leftJoin(['order'=> AdVideoOrder::tableName()], 'order.plan_uuid  =  plan.uuid')
            ->leftJoin(['vendor'=> MediaVendor::tableName()], 'order.vendor_uuid  = vendor.uuid')
            ->andWhere(['order.uuid'=>$order_uuid]);
            $command = $query->createCommand();
            $rows = $command->queryOne();
            if(!empty($rows)){
                return $this->render('detail',['data'=>$rows]);
            }else{
                return $this->redirect('?r=video/order/list');
            }
        }else{
            return $this->redirect('?r=video/order/list');
        }
        return $this->render('detail');
    }

    /**
     *  修改视频需求
     */
    public function actionUpdateOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $order_uuid = $request->post("order_uuid");
            $status = $request->post("status");
            $execute_uuid = $request->post("execute_uuid");
            $execute_remark = $request->post("execute_remark");
            $execute_price = $request->post("execute_price");
            $order = AdVideoOrder::findOne(['uuid' =>$order_uuid ]);
            $order->status = $status;
            $order->execute_person_uuid = trim($execute_uuid);
            $order->execute_remark = trim($execute_remark);
            $order->execute_price = trim($execute_price);
            $order->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

}