<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 25/10/16 2:24 PM By Manson
 */
namespace admin\modules\weibo\controllers;

use common\models\AdWeiboOrder;
use admin\controllers\BaseAppController;
use yii\data\ActiveDataProvider;
use admin\helpers\AdminHelper;
use common\models\MediaWeibo;
use common\models\AdWeiboPlan;
use yii\db\Query;
use Yii;

/**
 * 微博媒体资源控制类
 * Class MediaController
 */
class OrderController extends BaseAppController{

    /**
     * 微博订单列表页
     */
    public function actionList(){
        $request = Yii::$app->request;
        $query = (new Query())
        ->select([
            'ad_weibo_order.uuid as order_uuid',
            'ad_weibo_order.weibo_name',
            'ad_weibo_order.status',
            'ad_weibo_order.sub_type',
            'ad_weibo_order.price',
            'ad_weibo_plan.uuid',
            'ad_weibo_plan.plan_name',
            'ad_weibo_plan.execute_start_time',
            'ad_weibo_plan.execute_end_time',
            'media_vendor.name'
        ])
        ->from(['ad_weibo_order' => AdWeiboOrder::tableName()])
        ->leftJoin(['ad_weibo_plan'], 'ad_weibo_order.plan_uuid  =  ad_weibo_plan.uuid')
        ->leftJoin(['media_vendor'], 'ad_weibo_order.vendor_uuid  =  media_vendor.uuid')
        ->orderBy(['ad_weibo_order.update_time' => SORT_DESC]);
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $search_name = $request->post('search_name');
            $plan_name = $request->post('plan_name');
            $order_uuid = $request->post('order_uuid');
            $status = $request->post('status');
            $execute_start_time = strtotime($request->post('execute_start_time'));
            $execute_end_time = strtotime($request->post('execute_end_time'));
            if (!empty($search_name)){
                $query->andWhere(['or', ['like', 'ad_weibo_order.weibo_name', $search_name]]);
            }
            if (!empty($plan_name)){
                $query->andWhere(['or',['like','ad_weibo_plan.plan_name',$plan_name]]);
            }
            if (!empty($order_uuid)){
                $query->andWhere(['or',['like','ad_weibo_order.uuid',$order_uuid]]);
            }
            if ($status != -1){
                $query->andWhere(['ad_weibo_order.status'=>$status]);
            }
            if (!empty($execute_start_time) && !empty($execute_end_time)){
                $query->andWhere(['between','ad_weibo_plan.execute_start_time',$execute_start_time,$execute_end_time]);
            }elseif(!empty($execute_start_time)){
                $query->andWhere(['>=','ad_weibo_plan.execute_start_time',$execute_start_time]);
            }elseif(!empty($execute_end_time)){
                $query->andWhere(['<=','ad_weibo_plan.execute_start_time',$execute_end_time]);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => AdminHelper::getPageSize(),
                'page' => $page
            ]
        ]);
        return $this->render('list', ['dataProvider' => $dataProvider]);
    }

    /**
     *  微博订单详情页
     */
    public function actionDetail(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            $order_uuid = $request->get('uuid');
            $query = (new Query())
            ->select([
                'ad_weibo_plan.plan_name',
                'ad_weibo_plan.plan_desc',
                'ad_weibo_plan.contacts',
                'ad_weibo_plan.phone',
                'ad_weibo_plan.execute_start_time',
                'ad_weibo_plan.execute_end_time',
                'ad_weibo_plan.feedback_time',
                'ad_weibo_order.uuid',
                'ad_weibo_order.status',
                'ad_weibo_order.weibo_name',
                'ad_weibo_order.accept_remark',
                'ad_weibo_order.follower_num',
                'ad_weibo_order.sub_type',
                'ad_weibo_order.price',
                'ad_weibo_order.create_time',
                'ad_weibo_order.execute_person_uuid',
                'ad_weibo_order.execute_remark',
                'ad_weibo_order.execute_price',
                'media_vendor.name'
            ])
            ->from(['ad_weibo_plan' => AdWeiboPlan::tableName()])
            ->leftJoin(['ad_weibo_order'], 'ad_weibo_order.plan_uuid  =  ad_weibo_plan.uuid')
            ->leftJoin(['media_vendor'], 'ad_weibo_order.vendor_uuid  =  media_vendor.uuid')
            ->andWhere(['ad_weibo_order.uuid'=>$order_uuid]);
            $command = $query->createCommand();
            $rows = $command->queryOne();
            if(!empty($rows)){
                return $this->render('detail',['data'=>$rows]);
            }else{
                return $this->redirect('?r=weibo/order/list');
            }
        }else{
            return $this->redirect('?r=weibo/order/list');
        }
        //return $this->render('detail');
    }


    /**
     *  修改微博需求
     */
    public function actionUpdateOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $order_uuid = $request->post("order_uuid");
            $status = $request->post("status");
            $execute_uuid = $request->post("execute_uuid");
            $execute_remark = $request->post("execute_remark");
            $execute_price = $request->post("execute_price");
            $order = AdWeiboOrder::findOne(['uuid' =>$order_uuid ]);
            $order->status = $status;
            $order->execute_person_uuid = trim($execute_uuid);
            $order->execute_remark = trim($execute_remark);
            $order->execute_price = $execute_price;
            $order->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }


}
