<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 8/24/16 10:19 AM
 */
namespace admin\modules\home\controllers;

use admin\controllers\BaseAppController;
use admin\helpers\AdminHelper;
use common\helpers\MediaHelper;
use common\helpers\PlatformHelper;
use common\models\MediaExecutor;
use common\models\MediaExecutorAssign;
use common\models\MediaVendor;
use common\models\MediaWeixin;
use common\models\WomHomePageMedia;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Response;

/**
 * 首页微信管理
 * Class WeixinController
 * @package admin\modules\home\controllers
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class WeixinController extends BaseAppController{

    /**
     * 列表
     */
    public function actionList(){
        $query = (new Query())
            ->select('uuid,info, media_uuid, media_name as public_name, media_cate, cust_order, status')
            ->from(['home' => WomHomePageMedia::tableName()])
            ->where(['media_type' => WomHomePageMedia::WEIXIN_MEDIA_TYPE]);
        $request = Yii::$app->request;
        if($request->isPost){
            $mediaCate = $request->post("media_cate", -1);
            if(!empty($mediaCate) && $mediaCate != -1){
                $query->andWhere(['media_cate' => '#'.$mediaCate.'#']);
            }
        }
        $page = $request->post("page", 0);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => AdminHelper::getPageSize(),
                'page' => $page
            ]
        ]);

        return $this->render('list',[
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 显示[隐藏]
     */
    public function actionChangeStatus(){

        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $uuid = $request->post("uuid");
            $status = $request->post("status");

            $homeMedia = WomHomePageMedia::findOne(['uuid' => $uuid]);

            $homeMedia->status = $status;

            if($homeMedia->save()){
                return ['err_code' => 0, 'err_msg' => '状态更新成功'];
            }else{
                return ['err_code' => 1, 'err_msg' => '状态更新失败'];
            }
        }
    }

    /**
     * 更新
     */
    public function actionUpdate(){
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $uuid = $request->post("uuid");

            $homeMedia = WomHomePageMedia::findOne(['uuid' => $uuid]);

            // 获取最新资源信息
            $mediaInfo = MediaWeixin::findOne(['uuid' => $homeMedia['media_uuid']]);

            $info['avatar_img'] = $mediaInfo['avatar_big_img']; // 头像
            $info['m_head_avg_view'] = $mediaInfo['m_head_avg_view_cnt']; // 平均阅读数
            $info['follower_num'] = $mediaInfo['follower_num']; // 粉丝数
            //$info['desc'] = $mediaInfo['desc']; // 简介

            $info['media_type'] = 'weixin';
            $info['name'] = $mediaInfo['public_name'];
            $info['weixin_id'] = $mediaInfo['public_id'];
            $info['retail_price_min'] = $mediaInfo['retail_price_m_1_min'];
            $info['retail_price_max'] = $mediaInfo['retail_price_m_1_max'];

            $homeMedia->desc = $mediaInfo['account_short_desc'];
            $homeMedia->info = json_encode($info);

            $homeMedia->save();

            if($homeMedia->save()){
                return ['err_code' => 0, 'err_msg' => '更新成功'];
            }else{
                return ['err_code' => 1, 'err_msg' => '更新失败'];
            }
        }
    }

    /**
     * 显示简介
     */
    public function actionShowDesc(){
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $uuid = $request->post("uuid");
            $homeMedia = WomHomePageMedia::findOne(['uuid' => $uuid]);

            return ['err_code' => 0, 'err_msg' => $homeMedia['desc']];
        }
    }
    /**
     * 提交简介
     */
    public function actionSubmitDesc(){
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $uuid = $request->post("uuid");
            $desc = $request->post("desc");
            $homeMedia = WomHomePageMedia::findOne(['uuid' => $uuid]);
            $homeMedia->desc = $desc;

            if($homeMedia->save()){
                return ['err_code' => 0, 'err_msg' => '保存成功'];
            }else{
                return ['err_code' => 1, 'err_msg' => '保存失败'];
            }
        }
    }

    /**
     * 删除
     */
    public function actionDelete(){
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $uuid = $request->post("uuid");

            $homeMedia = WomHomePageMedia::findOne(['uuid' => $uuid]);

            if($homeMedia->delete()){
                return ['err_code' => 0, 'err_msg' => '删除成功'];
            }else{
                return ['err_code' => 1, 'err_msg' => '删除失败'];
            }
        }
    }

    /**
     * 添加的页面
     */
    public function actionAdd(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $name = $request->post('account');
            $followerCntMin = intval($request->post('follower-cnt-min'));
            $followerCntMax = intval($request->post('follower-cnt-max'));
            $mediaCate = $request->post('media-cate', -1);
            $isActivated = $request->post('is-activated', -1);
            $putUp = $request->post('put-up', -1);

            $query = (new Query())
                ->select('weixin.uuid as media_uuid, weixin.public_id, weixin.public_name, weixin.media_cate, weixin.follower_num, weixin.put_up, weixin.status as weixin_status, weixin.account_cert, weixin.is_activated, weixin.create_time, weixin.vendor_cnt, weixin.to_verify_vendor_cnt, weixin.has_pref_vendor, weixin.last_verify_time, weixin.order_finished_cnt, weixin.order_refuse_cnt, weixin.order_abort_cnt, vendor.name AS vendor_name, vendor.contact_person AS vendor_contact_person, media_executor.name AS executor_name')
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->where(['=', 'weixin.to_verify_vendor_cnt', 0])
                ->andWhere(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK])
                ->andWhere(['weixin.has_pref_vendor' => 1]);

            if (isset($name)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $name], ['like', 'weixin.public_id', $name]]);
            }

            if (($followerCntMin == 0 && $followerCntMax > 0) || ($followerCntMin > 0 && $followerCntMax > 0)) {
                $query->andWhere(['between', 'weixin.follower_num', $followerCntMin, $followerCntMax]);
            }

            if ($mediaCate != -1) {
                $query->andWhere(['like', 'weixin.media_cate', '#' . $mediaCate . '#']);
            }

            if ($isActivated != -1) {
                $query->andWhere(['weixin.is_activated' => $isActivated]);
            }

            if ($putUp != -1) {
                $query->andWhere(['weixin.put_up' => $putUp]);
            }
            $page = $request->post("page");
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);

            return $this->render('add', [
                'dataProvider' => $dataProvider
            ]);
        }else {
            $query = (new Query())
                ->select('weixin.uuid as media_uuid, weixin.public_id, weixin.public_name, weixin.media_cate, weixin.follower_num, weixin.put_up, weixin.status as weixin_status, weixin.account_cert, weixin.is_activated, weixin.create_time, weixin.vendor_cnt, weixin.to_verify_vendor_cnt, weixin.has_pref_vendor, weixin.last_verify_time, weixin.order_finished_cnt, weixin.order_refuse_cnt, weixin.order_abort_cnt, vendor.name AS vendor_name, vendor.contact_person AS vendor_contact_person, media_executor.name AS executor_name')
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->where(['=', 'weixin.to_verify_vendor_cnt', 0])
                ->andWhere(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK])
                ->andWhere(['weixin.has_pref_vendor' => 1]);
            $query->andWhere(['weixin.is_activated' => 1]);
            $query->andWhere(['weixin.put_up' => 1]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                ]
            ]);


            return $this->render('add', [
                'dataProvider' => $dataProvider
            ]);
        }



    }

    /**
     * 加入首页
     */
    public function actionAddHome(){
        $request = Yii::$app->request;
        if ($request->isPost) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $cate = $request->post('cate'); // 分类
            $mediaType = $request->post('mediaType'); // 媒体类型
            $mediaUuid = $request->post('mediaUuid'); // 媒体uuid

            // 获取资源详情
            $mediaInfo = MediaWeixin::findOne(['uuid' => $mediaUuid]);

            $info['media_type'] = 'weixin';
            $info['name'] = $mediaInfo['public_name'];
            $info['avatar_img'] = $mediaInfo['avatar_big_img']; // 头像
            $info['weixin_id'] = $mediaInfo['public_id'];

            $info['retail_price_min'] = $mediaInfo['retail_price_m_1_min'];
            $info['retail_price_max'] = $mediaInfo['retail_price_m_1_max'];


            $info['m_head_avg_view'] = $mediaInfo['m_head_avg_view_cnt']; // 平均阅读数
            $info['follower_num'] = $mediaInfo['follower_num']; // 粉丝数
            //$info['desc'] = $mediaInfo['desc']; // 简介

            $homeMedia = new WomHomePageMedia();
            $homeMedia->uuid = PlatformHelper::getUUID();
            $homeMedia->media_type = $mediaType;
            $homeMedia->media_uuid = $mediaUuid;
            $homeMedia->media_name = $mediaInfo['public_name'];
            $homeMedia->desc = $mediaInfo['account_short_desc'];
            $homeMedia->info = json_encode($info);
            $homeMedia->media_cate = '#'. $cate. '#';
            $homeMedia->cust_order = 8;
            $homeMedia->status = WomHomePageMedia::STATUS_HIDDEN;
            $homeMedia->save();

            return ['err_code' => 0, 'err_msg' => '添加成功!'];
        }
    }

}