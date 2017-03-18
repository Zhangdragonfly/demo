<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/29/16 11:29 AM
 */

namespace wom\modules\adOwner\controllers;

//use wom\controllers\BaseAppController;
use common\helpers\PlatformHelper;
use common\models\AdVideoPlan;
use common\models\AdVideoOrder;
use common\models\MediaVideo;
use common\models\VideoPlatformCommonInfo;
use common\models\VendorVideoBind;
use common\models\VideoVendorPrice;
use yii\web\Response;
use yii\db\Query;
use yii;

/**
 * 视频投放
 * Class VideoOrderController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class VideoPlanController extends AdOwnerBaseAppController
{
    public $layout = '//site-stage';

    /**
     * 视频立即预约生成plan和order
     */
    public function actionAddMediaPlanOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $media_uuid_array = $request->post('media_uuid_list');
            $plan_uuid = $request->post('plan_uuid');
            if(empty($plan_uuid)){//plan_uuid不存在
                //新增视频预约计划
                $videoPlan = new AdVideoPlan();
                $videoPlan->uuid = PlatformHelper::getUUID();
                $videoPlan->ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
                $videoPlan->create_time = time();
                $videoPlan->last_update_time = time();
                $videoPlan->save();
                $plan_uuid = $videoPlan->uuid;
                foreach($media_uuid_array as $key=>$platform_uuid){
                    //平台基本信息
                    $platformInfo = VideoPlatformCommonInfo::findOne(['uuid'=>$platform_uuid]);
                    //视频资源信息
                    $mediaVideo = MediaVideo::findOne(['uuid'=>$platformInfo->video_uuid]);
                    //供应商信息
                    $vendorInfo = VendorVideoBind::findOne(['video_uuid'=>$platformInfo->video_uuid,'is_pref_vendor'=>1]);
                    $active_end_time = $vendorInfo->active_end_time;
                    $vendor_uuid = $vendorInfo->vendor_uuid;
                    //新建订单
                    $videoOrder = new AdVideoOrder();
                    $videoOrder->uuid = PlatformHelper::getUUID();
                    $videoOrder->plan_uuid = $plan_uuid;
                    $videoOrder->platform_uuid = $platform_uuid;
                    $videoOrder->vendor_uuid = $vendor_uuid;
                    $videoOrder->sex = $mediaVideo->sex;
                    $videoOrder->account_name = $platformInfo->account_name;
                    $videoOrder->account_id = $platformInfo->account_id;
                    $videoOrder->platform_type = $platformInfo->platform_type;
                    $videoOrder->avatar = $platformInfo->avatar;
                    $videoOrder->follower_num = $platformInfo->follower_num;
                    $videoOrder->accept_remark = $platformInfo->remark;
                    $videoOrder->active_end_time = $active_end_time;
                    $videoOrder->status = 0;
                    $videoOrder->create_time = time();
                    $videoOrder->last_update_time = time();
                    $videoOrder->save();
                }
                return json_encode(['err_code' => 0, 'err_msg' => '保存成功', 'plan_uuid' => $plan_uuid]);
            }else{//plan_uuid存在时
                foreach($media_uuid_array as $key=>$platform_uuid){
                    //平台基本信息
                    $platformInfo = VideoPlatformCommonInfo::findOne(['uuid'=>$platform_uuid]);
                    //视频资源信息
                    $mediaVideo = MediaVideo::findOne(['uuid'=>$platformInfo->video_uuid]);
                    //供应商信息
                    $vendorInfo = VendorVideoBind::findOne(['video_uuid'=>$platformInfo->video_uuid,'is_pref_vendor'=>1]);
                    $active_end_time = $vendorInfo->active_end_time;
                    //判断订单是否存在
                    $order = AdVideoOrder::findOne(['plan_uuid'=>$plan_uuid,'platform_uuid'=>$platform_uuid]);
                    if($order ==null){
                        $videoOrder = new AdVideoOrder();
                        $videoOrder->uuid = PlatformHelper::getUUID();
                        $videoOrder->plan_uuid = $plan_uuid;
                        $videoOrder->platform_uuid = $platform_uuid;
                        $videoOrder->sex = $mediaVideo->sex;
                        $videoOrder->account_name = $platformInfo->account_name;
                        $videoOrder->platform_type = $platformInfo->platform_type;
                        $videoOrder->avatar = $platformInfo->avatar;
                        $videoOrder->follower_num = $platformInfo->follower_num;
                        $videoOrder->accept_remark = $platformInfo->remark;
                        $videoOrder->active_end_time = $active_end_time;
                        $videoOrder->status = 0;
                        $videoOrder->create_time = time();
                        $videoOrder->last_update_time = time();
                        $videoOrder->save();
                    }
                }
                return json_encode(['err_code' => 0, 'err_msg' => '保存成功', 'plan_uuid' => $plan_uuid]);
            }
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '保存失败']);
        }
    }


    /*
     * 视频预约需求订单列表页
     */
    public function actionCreatePlanOrder(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            $plan_uuid = $request->get('plan_uuid');
            $videoOrder = AdVideoOrder::find()->asArray()->where(['plan_uuid'=>$plan_uuid])->all();
            foreach($videoOrder as $key=>$val){

                $platform = VideoPlatformCommonInfo::findOne(['uuid'=>$val['platform_uuid']]);
                $videoBind = VendorVideoBind::findOne(['vendor_uuid'=>$val['vendor_uuid'],'is_pref_vendor'=>1,'video_uuid'=>$platform->video_uuid]);
                $platformPrice = VideoVendorPrice::findOne(['platform_uuid'=>$val['platform_uuid'],'vendor_bind_uuid'=>$videoBind->uuid]);
                if(empty($platformPrice)){
                    $videoOrder[$key]['price_one'] = "0.00";
                    $videoOrder[$key]['price_two'] = "0.00";
                }else{
                    $videoOrder[$key]['price_one'] = $platformPrice->price_orig_one;
                    $videoOrder[$key]['price_two'] = $platformPrice->price_orig_two;
                }

            }
            return $this->render('plan-order-create',[
                'videoOrderList'=>$videoOrder,
                'plan_uuid'=>$plan_uuid
            ]);
        }else{
            return $this->render('plan-order-create');
        }
    }

    /*
    * 删除预约需求的视频订单
    */
    public function actionDeleteVideoOrder(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $order_uuid = $request->post('order_uuid');
            AdVideoOrder::deleteAll(['uuid'=>$order_uuid]);
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败']);
        }
    }

    /*
   * 提交预约需求
   */
    public function actionSubmitPlan(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $plan_uuid = $request->post('plan_uuid');
            $order_price_array = $request->post('price_json_info');
            //更新视频plan表
            $videoPlan = AdVideoPlan::findOne(['uuid'=>$plan_uuid]);
            $videoPlan->plan_name = trim($request->post('plan_name'));
            $videoPlan->contacts = trim($request->post('contacts'));
            $videoPlan->phone = trim($request->post('phone'));
            $videoPlan->plan_desc = trim($request->post('plan_desc'));
            $videoPlan->execute_start_time = strtotime($request->post('execute_start_time'));
            $videoPlan->execute_end_time = strtotime($request->post('execute_end_time'));
            $videoPlan->feedback_time = strtotime($request->post('feedback_time'));
            $videoPlan->last_update_time = time();
            $videoPlan->status = 1;
            $videoPlan->save();
            //更新视频order表
            foreach ($order_price_array as $key => $val) {
                $videoOrder = AdVideoOrder::findOne(['uuid'=>$key]);
                $videoOrder->sub_type = $val['sub_type'];
                $videoOrder->price = $val['price'];
                $videoOrder->last_update_time = time();
                $videoOrder->status = 1;
                $videoOrder->save();
            }
            return json_encode(['err_code' => 0, 'err_msg' => '提交成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '提交失败']);
        }
    }

}