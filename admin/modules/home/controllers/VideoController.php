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
use common\models\VideoMediaBaseInfo;
use common\models\WomHomePageMedia;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Response;

/**
 * 首页网红管理
 * Class VideoController
 * @package admin\modules\home\controllers
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class VideoController extends BaseAppController{

    /**
     * 列表
     */
    public function actionList(){
        $query = (new Query())
            ->select('uuid, info, media_uuid, media_name as public_name, media_cate, cust_order, status')
            ->from(['home' => WomHomePageMedia::tableName()])
            ->where(['media_type' => WomHomePageMedia::VIDEO_MEDIA_TYPE]);
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
            $status = $request->post("status");

            $homeMedia = WomHomePageMedia::findOne(['uuid' => $uuid]);

            // 获取最新资源信息
            $mediaInfo = VideoMediaBaseInfo::findOne(['uuid' => $homeMedia['media_uuid']]);

            $info['media_type'] = 'video';
            $info['name'] = $mediaInfo['stage_name'];
            $info['avatar_img'] = $mediaInfo['avatar_img']; // 头像
            $info['avg_view'] = $mediaInfo['avg_watch_num'];
            $info['follower_num'] = $mediaInfo['main_platform_follower_num']; // 粉丝数
            //$info['desc'] = $mediaInfo['case_desc']; // 简介
            $info['retail_price_min'] = $mediaInfo['retail_price_min'];
            $info['retail_price_max'] = $mediaInfo['retail_price_max'];

            $homeMedia->desc = $mediaInfo['case_desc'];
            $homeMedia->info = json_encode($info);

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
        $page = intval($request->post('page'));
        $query = (new Query())
            ->select('base.name_cn, base.name_en, base.mobile_phone, base.qq, base.weixin, base.sex, base.area, base.uuid as media_uuid, base.tag, base.uuid, base.birth_date,base.platform_conf,base.main_platform,base.stage_name,base.current_address,base.pref_vendor_uuid,base.status, base.put')
            ->from(['base' => VideoMediaBaseInfo::tableName()]);

        $name = $request->post("name");
        if(!empty($name)){
            $query->andWhere(['like', 'stage_name', $name]);
        }

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
            $mediaInfo = VideoMediaBaseInfo::findOne(['uuid' => $mediaUuid]);

            $info['media_type'] = 'video';
            $info['name'] = $mediaInfo['stage_name'];
            $info['avatar_img'] = $mediaInfo['avatar_img']; // 头像
            $info['avg_view'] = $mediaInfo['avg_watch_num'];
            $info['follower_num'] = $mediaInfo['main_platform_follower_num']; // 粉丝数
            //$info['desc'] = $mediaInfo['case_desc']; // 简介
            $info['retail_price_min'] = $mediaInfo['retail_price_min'];
            $info['retail_price_max'] = $mediaInfo['retail_price_max'];

            $homeMedia = new WomHomePageMedia();
            $homeMedia->uuid = PlatformHelper::getUUID();
            $homeMedia->media_type = $mediaType;
            $homeMedia->media_uuid = $mediaUuid;
            $homeMedia->media_name = $mediaInfo['stage_name'];
            $homeMedia->desc = $mediaInfo['case_desc'];
            $homeMedia->info = json_encode($info);
            $homeMedia->media_cate = '#'. $cate. '#';
            $homeMedia->cust_order = 8;
            $homeMedia->status = WomHomePageMedia::STATUS_HIDDEN;
            $homeMedia->save();

            return ['err_code' => 0, 'err_msg' => '添加成功!'];
        }
    }

}