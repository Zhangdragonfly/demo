<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/29/16 11:29 AM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\models\AdWeiboPlan;
use common\models\AdWeiboOrder;
use common\models\MediaWeibo;
use common\models\WeiboVendorBind;
use common\models\MediaVendor;
use yii\web\Response;
use yii\db\Query;
use yii;

/**
 * 微博投放
 * Class WeiboOrderController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class WeiboPlanController extends AdOwnerBaseAppController
{
    public $layout = '//site-stage';


    /*
    * 微博预约需求订单列表页
    */
    public function actionCreatePlanOrder(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            $plan_uuid = $request->get('plan_uuid');
            $weiboOrder = AdWeiboOrder::find()->asArray()->where(['plan_uuid'=>$plan_uuid])->all();
            foreach($weiboOrder as $key=>$val){
                $weiboBindInfo = WeiboVendorBind::findOne(['weibo_uuid'=>$val['weibo_uuid'],'is_pref_vendor'=>1]);
                $weiboOrder[$key]['sd_price'] = $weiboBindInfo->soft_direct_price_retail;
                $weiboOrder[$key]['st_price'] = $weiboBindInfo->soft_transfer_price_retail;
                $weiboOrder[$key]['md_price'] = $weiboBindInfo->micro_direct_price_retail;
                $weiboOrder[$key]['mt_price'] = $weiboBindInfo->micro_transfer_price_retail;
            }
            return $this->render('plan-order-create',[
                'weiboOrderList'=>$weiboOrder,
                'plan_uuid'=>$plan_uuid
            ]);
        }else{
            return $this->render('plan-order-create');
        }
    }


    /**
     * 微博立即预约生成plan和order
     */
    public function actionAddMediaPlanOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $media_uuid_array = $request->post('media_uuid_list');
            $plan_uuid = $request->post('plan_uuid');
            if(empty($plan_uuid)){//plan_uuid不存在
                //新增微博plan
                $weiboPlan = new AdWeiboPlan();
                $weiboPlan->uuid = PlatformHelper::getUUID();
                $weiboPlan->ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
                $weiboPlan->create_time = time();
                $weiboPlan->update_time = time();
                $weiboPlan->save();
                $plan_uuid = $weiboPlan->uuid;
                foreach($media_uuid_array as $key=>$weibo_uuid){
                    $mediaWeibo = MediaWeibo::findOne(['uuid'=>$weibo_uuid]); //微博资源信息
                    $vendorBindInfo = WeiboVendorBind::findOne(['weibo_uuid'=>$weibo_uuid,'is_pref_vendor'=>1]);//供应商信息
                    //新建订单
                    $weiboOrder = new AdWeiboOrder();
                    $weiboOrder->uuid = PlatformHelper::getUUID();
                    $weiboOrder->plan_uuid = $plan_uuid;
                    $weiboOrder->weibo_uuid = $weibo_uuid;
                    $weiboOrder->vendor_uuid = $vendorBindInfo->vendor_uuid;
                    $weiboOrder->weibo_name = $mediaWeibo->weibo_name;
                    $weiboOrder->follower_num = $mediaWeibo->follower_num;
                    $weiboOrder->accept_remark = $mediaWeibo->accept_remark;
                    $weiboOrder->status = 0;
                    $weiboOrder->create_time = time();
                    $weiboOrder->update_time = time();
                    $weiboOrder->save();
                }
                return json_encode(['err_code' => 0, 'err_msg' => '保存成功', 'plan_uuid' => $plan_uuid]);
            }else{//plan_uuid存在时
                foreach($media_uuid_array as $key=>$weibo_uuid){
                    $mediaWeibo = MediaWeibo::findOne(['uuid'=>$weibo_uuid]); //微博资源信息
                    $vendorInfo = WeiboVendorBind::findOne(['weibo_uuid'=>$weibo_uuid,'is_pref_vendor'=>1]);//供应商信息
                    //判断订单是否存在
                    $order = AdWeiboOrder::findOne(['plan_uuid'=>$plan_uuid,'weibo_uuid'=>$weibo_uuid]);
                    if($order ==null){
                        $weiboOrder = new AdWeiboOrder();
                        $weiboOrder->uuid = PlatformHelper::getUUID();
                        $weiboOrder->plan_uuid = $plan_uuid;
                        $weiboOrder->weibo_uuid = $weibo_uuid;
                        $weiboOrder->vendor_uuid = $vendorInfo->vendor_uuid;
                        $weiboOrder->weibo_name = $mediaWeibo->weibo_name;
                        $weiboOrder->follower_num = $mediaWeibo->follower_num;
                        $weiboOrder->accept_remark = $mediaWeibo->accept_remark;
                        $weiboOrder->status = 0;
                        $weiboOrder->create_time = time();
                        $weiboOrder->update_time = time();
                        $weiboOrder->save();
                    }
                }
                return json_encode(['err_code' => 0, 'err_msg' => '保存成功', 'plan_uuid' => $plan_uuid]);
            }
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '保存失败']);
        }
    }

    /*
  * 删除预约需求的微博订单
  */
    public function actionDeleteWeiboOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $order_uuid = $request->post('order_uuid');
            AdWeiboOrder::deleteAll(['uuid'=>$order_uuid]);
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败']);
        }
    }

    /*
 * 提交预约需求
 */
    public function actionSubmitWeiboPlan(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $plan_uuid = $request->post('plan_uuid');
            $order_price_array = $request->post('price_json_info');
            //更新微博plan表
            $weiboPlan = AdWeiboPlan::findOne(['uuid'=>$plan_uuid]);
            $weiboPlan->plan_name = trim($request->post('plan_name'));
            $weiboPlan->contacts = trim($request->post('contacts'));
            $weiboPlan->phone = trim($request->post('phone'));
            $weiboPlan->plan_desc = trim($request->post('plan_desc'));
            $weiboPlan->execute_start_time = strtotime($request->post('execute_start_time'));
            $weiboPlan->execute_end_time = strtotime($request->post('execute_end_time'));
            $weiboPlan->feedback_time = strtotime($request->post('feedback_time'));
            $weiboPlan->update_time = time();
            $weiboPlan->status = 1;
            $weiboPlan->save();
            //更新微博order表
            foreach ($order_price_array as $key => $val) {
                $weiboOrder = AdWeiboOrder::findOne(['uuid'=>$key]);
                $weiboOrder->sub_type = $val['sub_type'];
                $weiboOrder->price = $val['price'];
                $weiboOrder->update_time = time();
                $weiboOrder->status = 1;
                $weiboOrder->save();
            }
            return json_encode(['err_code' => 0, 'err_msg' => '提交成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '提交失败']);
        }
    }


}