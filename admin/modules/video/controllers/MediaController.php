<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 7/7/16 2:03 PM
 */
namespace admin\modules\video\controllers;

use admin\controllers\BaseAppController;
use common\helpers\DateTimeHelper;
use common\helpers\MediaHelper;
use common\models\MediaVendor;
use common\models\MediaVideo;
use common\models\VendorVideoBind;
use common\models\VideoPlatformCommonInfo;
use common\models\VideoGrabTask;
use common\helpers\PlatformHelper;
use admin\helpers\AdminHelper;
use common\models\VideoVendorPrice;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\db\Query;
use Yii;

/**
 * 基础信息
 * Class HuajiaoController
 * @package admin\modules\video\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaController extends BaseAppController
{

    /**
     * 视频资源的入驻账号
     */
    public function actionCreate(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $vendor_array = $request->post('vendor_info_json');
            $platform_array = $request->post('platform_info_json');
            $pref_vendor = $request->post('pref_vendor');
            $main_platform = $request->post('main_platform');
            //保存视频资源信息
            $mediaVideo = new MediaVideo();
            $mediaVideo->uuid = PlatformHelper::getUUID();
            $mediaVideo->nickname = trim($request->post('nickname'));
            $mediaVideo->name = trim($request->post('realname'));
            $mediaVideo->sex = $request->post('sex');
            $mediaVideo->address = trim($request->post('area'));
            $mediaVideo->coop_remark = trim($request->post('coop_remark'));
            $mediaVideo->media_cate = trim($request->post('cate'));
            $mediaVideo->main_platform = trim($request->post('main_platform'));
            $mediaVideo->create_time = time();
            $mediaVideo->update_time = time();
            $mediaVideo->save();
            //保存平台信息
            $videoUuid = $mediaVideo->uuid;
            foreach($platform_array as $key=>$val){
                $videoPlatform = new VideoPlatformCommonInfo();
                $videoPlatform->uuid = PlatformHelper::getUUID();
                $videoPlatform->video_uuid = $videoUuid;
                $videoPlatform->platform_type = $val['type'];
                $videoPlatform->account_name = trim($val['account_name']);
                $videoPlatform->account_id = trim($val['account_id']);
                $videoPlatform->follower_num = intval($val['follower_num']);
                $videoPlatform->url = trim($val['url']);
                $videoPlatform->avg_watch_num = trim($val['avg_watch_sum']);
                $videoPlatform->person_sign = trim($val['person_sign']);
                $videoPlatform->auth_type = $val['auth_status'];
                $videoPlatform->remark = trim($val['remark']);
                $videoPlatform->is_put = 1;
                $videoPlatform->status = 1;
                $videoPlatform->create_time = time();
                $videoPlatform->update_time = time();
                $videoPlatform->audit_time = time();
                $videoPlatform->save();
            }
            //保存供应商信息以及价格信息
            foreach($vendor_array as $key=>$val){
                $venderVideoBind = new VendorVideoBind();
                $venderVideoBind->uuid = PlatformHelper::getUUID();
                $venderVideoBind->video_uuid = $videoUuid;
                $venderVideoBind->vendor_uuid = $val['vendor_uuid'];
                $venderVideoBind->cooperate_level = $val['cooperate_level'];
                $venderVideoBind->account_period = $val['account_period'];
                $venderVideoBind->belong_type = $val['belong_type'];
                $venderVideoBind->active_end_time = strtotime($val['active_end_time']);
                $venderVideoBind->status = 1;
                $venderVideoBind->create_time = time();
                $venderVideoBind->update_time = time();
                if($val['vendor_uuid'] == $pref_vendor){//首选供应商
                    $venderVideoBind->is_pref_vendor = 1;
                }else{
                    $venderVideoBind->is_pref_vendor = 0;
                }
                $venderVideoBind->save();
                //vendor表微博资源的改动
                $vendor = MediaVendor::findOne(['uuid'=>$val['vendor_uuid']]);
                $vendor->video_media_cnt = ($vendor->video_media_cnt) + 1;
                $vendor->save();
                //保存供应商报价信息
                $vendor_bind_uuid = $venderVideoBind->uuid;
                $vendorPrice_array = $val['price_json_info'];
                foreach($vendorPrice_array as $k=>$v){
                    $platform_uuid_arr = (new Query())
                    ->select(['platform.uuid'])
                    ->from(['video' => MediaVideo::tableName()])
                    ->leftJoin(['platform' => VideoPlatformCommonInfo::tableName()],'video.uuid = platform.video_uuid ')
                    ->leftJoin(['bind' => VendorVideoBind::tableName()],'video.uuid = bind.video_uuid')
                    ->andWhere(['bind.uuid'=>$vendor_bind_uuid])
                    ->andWhere(['platform.platform_type'=>$v['platform_type']])
                    ->one();
                    $platform_uuid = $platform_uuid_arr['uuid'];//对应平台uuid
                    $vendorPrice = new VideoVendorPrice();
                    $vendorPrice->uuid = PlatformHelper::getUUID();
                    $vendorPrice->vendor_bind_uuid = $vendor_bind_uuid;
                    $vendorPrice->platform_uuid = $platform_uuid;
                    $vendorPrice->platform_type = $v['platform_type'];
                    $vendorPrice->is_main_platform = $v['is_main'];
                    $vendorPrice->price_orig_one = $v['orig_one'];
                    $vendorPrice->price_orig_two = $v['orig_two'];
                    $vendorPrice->price_retail_one = $v['retail_one'];
                    $vendorPrice->price_retail_two = $v['retail_two'];
                    $vendorPrice->price_execute_one = $v['execute_one'];
                    $vendorPrice->price_execute_two = $v['execute_two'];
                    $vendorPrice->price_config = json_encode($v);
                    $vendorPrice->create_time = time();
                    $vendorPrice->update_time = time();
                    $vendorPrice->save();
                }
            }
            return ['err_code' => 0, 'err_msg' => '添加成功'];
        } else {
            return $this->render('create');
        }
    }

    /**
     * 基础信息列表
     */
    public function actionList(){
        $request = Yii::$app->request;
        $search_type = $request->get("type");//查询类型 1待审核 2已审核 3未通过 4待更新
        $vendor_uuid = $request->get("vendor_uuid",'');//媒体主
        $query = (new Query())
        ->select([
            'video.uuid as video_uuid',
            'video.nickname',
            'video.media_cate',
            'video.main_platform',
            'platform.uuid as platform_uuid',
            'platform.platform_type',
            'platform.account_name',
            'platform.account_id',
            'platform.follower_num',
            'platform.status',
            'platform.is_put',
            'platform.is_top',
            'platform.is_push',
            'platform.create_time',
            'platform.update_time',
            'bind.active_end_time',
            'price.price_config',
            'price.price_orig_one',
            'price.price_orig_two',
            'vendor.name',
        ])
        ->from(['video' => MediaVideo::tableName()])
        ->leftJoin(['platform' => VideoPlatformCommonInfo::tableName()],'video.uuid = platform.video_uuid ');
        //存在供应商时查询
        $vendor_name = '';
        if(!empty($vendor_uuid)){//供应商对应的资源列表
            $vendor = MediaVendor::findOne(['uuid'=>$vendor_uuid]);
            $vendor_name = $vendor->name;
            $query->leftJoin(['bind' => VendorVideoBind::tableName()],'video.uuid = bind.video_uuid')
            ->leftJoin(['vendor' => MediaVendor::tableName()],'vendor.uuid = bind.vendor_uuid')
            ->leftJoin(['price' => VideoVendorPrice::tableName()],'bind.uuid = price.vendor_bind_uuid and platform.uuid = price.platform_uuid')
            ->orderBy(['platform.is_top' => SORT_DESC,'video.update_time' => SORT_DESC])
            ->andWhere(['bind.vendor_uuid'=>$vendor_uuid]);

        }else{
            $query->leftJoin(['bind' => VendorVideoBind::tableName()],'video.uuid = bind.video_uuid and bind.is_pref_vendor = 1')
            ->leftJoin(['vendor' => MediaVendor::tableName()],'vendor.uuid = bind.vendor_uuid')
            ->leftJoin(['price' => VideoVendorPrice::tableName()],'bind.uuid = price.vendor_bind_uuid and platform.uuid = price.platform_uuid')
            ->orderBy(['platform.is_top' => SORT_DESC,'video.update_time' => SORT_DESC]);
        }

        if($search_type==1){//待审核
            $query->andWhere(['platform.status'=>0]);
        }
        if($search_type==2){//已审核
            $query->andWhere(['platform.status'=>1]);
        }
        if($search_type==3){//未通过
            $query->andWhere(['platform.status'=>2]);
        }
        if($search_type==4){//带更新
            $need_update_time = strtotime("+14 day");//十四天后的时间
            $query->andWhere(['<=','bind.active_end_time',$need_update_time]);
            $query->andWhere(['platform.status'=>1]);
            $query->andWhere(['platform.is_put'=>1]);
            $query->orderBy(['bind.active_end_time' => SORT_DESC]);
        }
        $page = 0;//默认第一页
        if ($request->isPost) {//搜索条件
            $page = intval($request->post('page'));
            $search_name = trim($request->post('search_name'));
            $search_vendor = trim($request->post('search_vendor'));
            $platform_type = trim($request->post('platform_type'));
            $media_cate = trim($request->post('media_cate'));
            $status = trim($request->post('status'));
            $is_put = trim($request->post('is_put'));
            $price_min = $request->post('price_min');
            $price_max = $request->post('price_max');
            if (!empty($search_name)){//艺人名称/账号ID
                $query->andWhere(['or', ['like', 'video.nickname', $search_name], ['like', 'platform.account_name', $search_name], ['like', 'platform.account_id', $search_name]]);
            }
            if (!empty($search_vendor)){//自媒体主
                $query->andWhere(['like', 'vendor.name', $search_vendor]);
            }
            if ($platform_type != -1){ //平台类型
                $query->andWhere(['platform.platform_type'=>$platform_type]);
            }
            if ($status != -1){ //资源状态
                $query->andWhere(['platform.status'=>$status]);
            }
            if ($is_put != -1){ //是否上架
                $query->andWhere(['platform.is_put'=>$is_put]);
            }
            if ($media_cate != -1){//标签
                $query->andWhere(['like', 'video.media_cate', "#".$media_cate."#"]);
            }
            //参考报价
            $query = $this->getOrRangeSearch($query,$price_min,$price_max,'price.price_orig_one','price.price_orig_two');
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
            "search_type"=>$search_type,
            "vendor_uuid"=>$vendor_uuid,
            "vendor_name"=>$vendor_name,
        ]);
    }


    /**
     *
     * @return array
     */
    public function actionDetail(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            $video_uuid= $request->get('uuid');
            $query = (new Query())
            ->select([
                'video.uuid as video_uuid',
                'video.nickname',
                'video.name as realname',
                'video.sex',
                'video.address',
                'video.coop_remark',
                'video.media_cate',
                'video.main_platform',
                'platform.uuid as platform_uuid',
                'platform.account_name',
                'platform.account_id',
                'platform.follower_num',
                'platform.url',
                'platform.avg_watch_num',
                'platform.person_sign',
                'platform.auth_type',
                'platform.status',
                'platform.remark',
                'platform.audit_time',
                'platform.platform_type',
                'bind.active_end_time',
                'price.price_config',
                'price.price_orig_one',
                'price.price_orig_two',
                'vendor.name',
            ])
            ->from(['video' => MediaVideo::tableName()])
            ->leftJoin(['platform' => VideoPlatformCommonInfo::tableName()],'video.uuid = platform.video_uuid ')
            ->leftJoin(['bind' => VendorVideoBind::tableName()],'video.uuid = bind.video_uuid and bind.is_pref_vendor=1')
            ->leftJoin(['vendor' => MediaVendor::tableName()],'vendor.uuid = bind.vendor_uuid')
            ->leftJoin(['price' => VideoVendorPrice::tableName()],'bind.uuid = price.vendor_bind_uuid and platform.uuid = price.platform_uuid')
            ->andWhere(['video.uuid'=>$video_uuid]);
            $command = $query->createCommand();
            $rows = $command->queryAll();
            if(!empty($rows)){
                return $this->render('detail',['data'=>$rows]);
            }else{
                return $this->redirect('?r=video/media/list');
            }
        }else{
            return $this->redirect('?r=video/media/list');
        }

    }

    //增加新的平台信息
    public function actionAddPlatformInfo(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $videoPlatform = new VideoPlatformCommonInfo();
            $videoPlatform->uuid = PlatformHelper::getUUID();
            $videoPlatform->video_uuid = trim($request->post('video_uuid'));
            $videoPlatform->platform_type =$request->post('type');
            $videoPlatform->account_name = trim($request->post('account_name'));
            $videoPlatform->account_id = trim($request->post('account_id'));
            $videoPlatform->follower_num = intval($request->post('follower_num'));
            $videoPlatform->url = trim($request->post('url'));
            $videoPlatform->avg_watch_num = trim($request->post('avg_watch_num'));
            $videoPlatform->person_sign = trim($request->post('person_sign'));
            $videoPlatform->auth_type = $request->post('auth_status');
            $videoPlatform->remark = trim($request->post('remark'));
            $videoPlatform->fail_reason = trim($request->post('fail_reason'));
            $videoPlatform->status = $request->post('audit_status');
            $videoPlatform->create_time = time();
            $videoPlatform->update_time = time();
            $videoPlatform->audit_time = time();
            $videoPlatform->save();
            return json_encode(['err_code' => 0, 'err_msg' => '添加成功！']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '添加失败！']);
        }
    }

    //获取平台信息
    public function actionGetPlatformInfo(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $platform_uuid = trim($request->post('platform_uuid'));
            $platform = VideoPlatformCommonInfo::find()->asArray()->where(['uuid'=>$platform_uuid])->one();
            $platform['audit_time'] = date('Y-m-d',$platform['audit_time']);
            return json_encode(['err_code' => 0, 'err_msg' => '获取成功！','data'=>$platform]);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '获取失败！']);
        }
    }

    //修改or审核平台信息
    public function actionUpdatePlatformInfo(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $platform_uuid = trim($request->post('platform_uuid'));
            $videoPlatform = VideoPlatformCommonInfo::findOne(['uuid'=>$platform_uuid]);
            $videoPlatform->account_name = trim($request->post('account_name'));
            $videoPlatform->account_id = trim($request->post('account_id'));
            $videoPlatform->follower_num = intval($request->post('follower_num'));
            $videoPlatform->url = trim($request->post('url'));
            $videoPlatform->avg_watch_num = trim($request->post('avg_watch_num'));
            $videoPlatform->person_sign = trim($request->post('person_sign'));
            $videoPlatform->auth_type = $request->post('auth_status');
            $videoPlatform->remark = trim($request->post('remark'));
            $videoPlatform->fail_reason = trim($request->post('fail_reason'));
            $videoPlatform->status = $request->post('audit_status');
            if($request->post('audit_status') != 1){//平台未审核时下架
                $videoPlatform->is_put = 0;
            }
            $videoPlatform->update_time = time();
            $videoPlatform->audit_time = time();
            $videoPlatform->save();
            return json_encode(['err_code' => 0, 'err_msg' => '审核成功！']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '审核失败！']);
        }
    }

    //移除平台
    public function actionDeletePlatformInfo(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $platform_uuid = trim($request->post('platform_uuid'));
            VideoPlatformCommonInfo::deleteAll(['uuid'=>$platform_uuid]);
            VideoVendorPrice::deleteAll(['platform_uuid'=>$platform_uuid]);//删除平台报价信息
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功！']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败！']);
        }
    }

    //视频资源添加供应商
    public function actionAddVendor(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $video_uuid = $request->post('video_uuid');
            $vendorUUID = $request->post('vendor_uuid');
            $vendorVideoBind = VendorVideoBind::findOne(['video_uuid' => $video_uuid, 'vendor_uuid' => $vendorUUID]);
            if ($vendorVideoBind !== null) {
                return json_encode(['err_code' => 1, 'err_msg' => '已经存在']);
            }
            if ($vendorVideoBind === null){
                $vendorVideoBind = new VendorVideoBind();
                $vendorVideoBind->uuid = PlatformHelper::getUUID();
                $vendorVideoBind->video_uuid = $video_uuid;
                $vendorVideoBind->vendor_uuid = $vendorUUID;
                $vendorVideoBind->create_time = time();
                $vendorVideoBind->save();
                //vendor表微博资源的改动
                $vendor = MediaVendor::findOne(['uuid'=>$vendorUUID]);
                $vendor->video_media_cnt = ($vendor->video_media_cnt) + 1;
                $vendor->save();
            }
            return json_encode(['err_code' => 0, 'err_msg' => '添加成功!']);
        }
    }

    /**
     * 获取视频供应商列表
     */
    public function actionGetListOfVendor(){
        $request = Yii::$app->request;
        $video_uuid = $request->post('video_uuid');
        $vendorList = (new Query())
        ->select([
            'vendor.name',
            'vendor.register_type',
            'vendor.contact_person',
            'vendor.contact1',
            'bind.status',
            'bind.is_pref_vendor',
            'bind.uuid as vendor_bind_uuid',
        ])
        ->from(['bind' => VendorVideoBind::tableName()])
        ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  bind.vendor_uuid')
        ->leftJoin(['video' => MediaVideo::tableName()], 'video.uuid  =  bind.video_uuid')
        ->where(['bind.video_uuid' => $video_uuid])
        ->orderBy(['bind.create_time' => SORT_ASC])
        ->all();
        return json_encode(['err_code' => 0, 'err_msg' => '获取成功', 'vendor_list' => $vendorList]);
    }

    /**
     * 获得视频媒体主信息
     * @return array
     * @throws ErrorException
     */
    public function actionGetVendorInfo(){
        $request = Yii::$app->request;
        $bind_uuid = $request->post('bind_uuid');
        $vendorinfo = (new Query())
        ->select([
            'bind.uuid as bind_uuid',
            'bind.status',
            'bind.cooperate_level',
            'bind.account_period',
            'bind.belong_type',
            'bind.is_pref_vendor',
            'bind.active_end_time',
            'platform.platform_type',
            'platform.status as platform_status',
            'platform.is_put',
            'price.price_orig_one',
            'price.price_orig_two',
            'price.price_retail_one',
            'price.price_retail_two',
            'price.price_execute_one',
            'price.price_execute_two',
            'price.is_main_platform',
            'vendor.name',
        ])
        ->from(['bind' => VendorVideoBind::tableName()])
        ->leftJoin(['video' => MediaVideo::tableName()],'video.uuid = bind.video_uuid')
        ->leftJoin(['platform' => VideoPlatformCommonInfo::tableName()],'video.uuid = platform.video_uuid ')
        ->leftJoin(['vendor' => MediaVendor::tableName()],'vendor.uuid = bind.vendor_uuid')
        ->leftJoin(['price' => VideoVendorPrice::tableName()],'bind.uuid = price.vendor_bind_uuid and platform.uuid = price.platform_uuid')
        ->where(['bind.uuid' => $bind_uuid])
        ->orderBy(['bind.create_time' => SORT_ASC])
        ->all();

        //价格有效期
        foreach($vendorinfo as $k=>$v){
            $vendorinfo[$k]['active_end_time'] = ( $vendorinfo[$k]['active_end_time']>0)? date("Y-m-d H:i",  $vendorinfo[$k]['active_end_time']):"";
        }
        if (isset($vendorinfo)) {
            return json_encode(['err_code' => 0, 'vendorinfo' => $vendorinfo]);
        } else {
            throw new ErrorException('Cannot find such weibo vendor');
        }
    }

    /**
     * 从视频资源移除媒体主
     */
    public function actionDeleteVendor(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $bindUUID = $request->post('bind_uuid');
            $Bind = VendorVideoBind::findOne(['uuid'=>$bindUUID]);
            //vendor表微博资源的改动
            $vendor = MediaVendor::findOne(['uuid'=>$Bind->vendor_uuid]);
            $vendor->video_media_cnt = ($vendor->video_media_cnt) - 1;
            $vendor->save();
            VendorVideoBind::deleteAll(['uuid' => $bindUUID]);
            return json_encode(['err_code' => 0, 'err_msg' => '移除成功!']);
        }
    }


    /**
     *  修改视频媒体主审核信息
     */
    public function actionSaveAuditVendor(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $video_uuid = $request->post("video_uuid");
            $bind_uuid = $request->post("bind_uuid");
            $is_pref_vendor = $request->post("is_pref_vendor");
            $price_array = $request->post("vendor_price_json");
            //供应商信息更新
            $Bind = VendorVideoBind::findOne(['uuid' => $bind_uuid]);
            $Bind->status = trim($request->post("status"));
            $Bind->belong_type = trim($request->post("belong_type"));
            $Bind->account_period = trim($request->post("account_period"));
            $Bind->cooperate_level = trim($request->post("cooperate_level"));
            $Bind->active_end_time = strtotime($request->post("active_end_time"));
            $Bind->update_time = time();
            $Bind->is_pref_vendor = $is_pref_vendor;
            if($is_pref_vendor == 1){//首选供应商更替
                VendorVideoBind::updateAll(['is_pref_vendor' =>0], ['video_uuid' => $video_uuid]);
                VendorVideoBind::updateAll(['is_pref_vendor' =>1], ['uuid' => $bind_uuid]);
            }
            $Bind->save();
            foreach($price_array as $k=>$v){
                //获取对应平台uuid
                $platform_uuid_arr = (new Query())
                    ->select(['platform.uuid'])
                    ->from(['video' => MediaVideo::tableName()])
                    ->leftJoin(['platform' => VideoPlatformCommonInfo::tableName()],'video.uuid = platform.video_uuid ')
                    ->leftJoin(['bind' => VendorVideoBind::tableName()],'video.uuid = bind.video_uuid')
                    ->andWhere(['bind.uuid'=>$bind_uuid])
                    ->andWhere(['platform.platform_type'=>$v['platform_type']])
                    ->one();
                $platform_uuid = $platform_uuid_arr['uuid'];//对应平台uuid
                //平台上下架
                $platform = VideoPlatformCommonInfo::findOne(['uuid'=>$platform_uuid]);
                $platform->is_put = $v['is_put'];
                $platform->save();
                //更新or新建
                $price_info = VideoVendorPrice::findOne(['vendor_bind_uuid'=>$bind_uuid,'platform_uuid'=>$platform_uuid]);
                if($price_info == null){//add
                    $vendorPrice = new VideoVendorPrice();
                    $vendorPrice->uuid = PlatformHelper::getUUID();
                    $vendorPrice->vendor_bind_uuid = $bind_uuid;
                    $vendorPrice->platform_uuid = $platform_uuid;
                    $vendorPrice->platform_type = $v['platform_type'];
                    $vendorPrice->is_main_platform = $v['is_main'];
                    $vendorPrice->price_orig_one = $v['orig_one'];
                    $vendorPrice->price_orig_two = $v['orig_two'];
                    $vendorPrice->price_retail_one = $v['retail_one'];
                    $vendorPrice->price_retail_two = $v['retail_two'];
                    $vendorPrice->price_execute_one = $v['execute_one'];
                    $vendorPrice->price_execute_two = $v['execute_two'];
                    $vendorPrice->price_config = json_encode($v);
                    $vendorPrice->create_time = time();
                    $vendorPrice->update_time = time();
                    $vendorPrice->save();
                }else{//update
                    $price_info->platform_type = $v['platform_type'];
                    $price_info->is_main_platform = $v['is_main'];
                    $price_info->price_orig_one = $v['orig_one'];
                    $price_info->price_orig_two = $v['orig_two'];
                    $price_info->price_retail_one = $v['retail_one'];
                    $price_info->price_retail_two = $v['retail_two'];
                    $price_info->price_execute_one = $v['execute_one'];
                    $price_info->price_execute_two = $v['execute_two'];
                    $price_info->price_config = json_encode($v);
                    $price_info->update_time = time();
                    $price_info->save();
                }
            }
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    //判断无首选供应商时视频资源下架
    public function actionDownMedia(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $video_uuid = $request->post('video_uuid');
            $Bind = VendorVideoBind::findAll(['is_pref_vendor' =>1,'video_uuid'=>$video_uuid]);
            if(empty($Bind)){//不存在首选供应商时下架
                $platform = VideoPlatformCommonInfo::findOne(['uuid' => $video_uuid]);
                $platform->is_put = 0;
                $platform->save();
            }
            return json_encode(['err_code' => 0, 'err_msg' => '修改成功!']);
        }
    }

    /**
     *  审核视频资源的下一步
     */
    public function actionSaveAuditVideo(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $video_uuid = $request->post("video_uuid");
            $video = MediaVideo::findOne(['uuid' => $video_uuid]);
            $video->nickname = trim($request->post("nickname"));
            $video->name = trim($request->post("realname"));
            $video->sex = trim($request->post("sex"));
            $video->coop_remark = trim($request->post("coop_remark"));
            $video->address = trim($request->post("area"));
            $video->media_cate = trim($request->post("cate"));
            $video->main_platform = trim($request->post("main_platform"));
            $video->update_time = time();
            $video->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    //检查艺人名称是否存在
    public function actionCheckNickname(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $nickname = $request->post('nickname');
            $video = MediaVideo::findOne(['nickname' => $nickname]);
            if ($video !== null) {
                return json_encode(['err_code' => 1, 'err_msg' => '该账号已经存在！']);
            } else {
                return json_encode(['err_code' => 0, 'err_msg' => '该账号已经在系统中存在！']);
            }
        }
    }

    /**
     *  视频平台上下架
     */
    public function actionPutUpDown(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $platform_uuid = $request->post("platform_uuid");
            $type = $request->post("type");
            $Platform = VideoPlatformCommonInfo::findOne(['uuid' => $platform_uuid]);
            if($type == "up"){//上架
                $Bind = VendorVideoBind::findAll(['is_pref_vendor' =>1,'video_uuid'=>$Platform->video_uuid]);
                if(!empty($Bind)){//存在首选供应商
                    $Platform->is_put = 1;
                }else{
                    return json_encode(array('err_code'=>1,'err_msg'=>'没有首选供应商，无法上架！'));
                }
            }
            if($type == "down"){//下架
                //VendorVideoBind::updateAll(['is_pref_vendor' =>0], ['weibo_uuid' => $weibo_uuid]);//解除首选供应商
                $Platform->is_put = 0;
                $Platform->is_top = 0;
            }
            $Platform->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    /**
     *  视频资源置顶
     */
    public function actionPutTopDown(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $platform_uuid = $request->post("platform_uuid");
            $type = $request->post("type");
            $platform = VideoPlatformCommonInfo::findOne(['uuid' => $platform_uuid]);
            if($type == "top"){
                $platform->is_top = 1;
            }
            if($type == "down"){
                $platform->is_top = 0;
            }
            $platform->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    /**
     *  视频资源置顶
     */
    public function actionPushTopDown(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $platform_uuid = $request->post("platform_uuid");
            $type = $request->post("type");
            $platform = VideoPlatformCommonInfo::findOne(['uuid' => $platform_uuid]);
            if($type == "top"){
                $platform->is_push = 1;
            }
            if($type == "down"){
                $platform->is_push = 0;
            }
            $platform->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }


    //同步价格有效期的价格
    public function actionGetVendorActiveTime(){
        $request = Yii::$app->request;
        $bind_uuid = $request->get('bind_uuid');
        $vendor_uuid = $request->get('vendor_uuid');
        if(!empty($bind_uuid)){
            $vendorinfo = (new Query())
                ->select(['vendor.active_end_time'])
                ->from(['vendor_bind' => VendorVideoBind::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  vendor_bind.vendor_uuid')
                ->where(['vendor_bind.uuid' => $bind_uuid])
                ->one();
        }
        if(!empty($vendor_uuid)){
            $vendorinfo = (new Query())
                ->select(['vendor.active_end_time'])
                ->from(['vendor' => MediaVendor::tableName()])
                ->where(['vendor.uuid' => $vendor_uuid])
                ->one();
        }
        $vendorinfo['active_end_time'] = ($vendorinfo['active_end_time']>0)? date("Y-m-d H:i", $vendorinfo['active_end_time']):"";
        if (isset($vendorinfo)) {
            return json_encode(['err_code' => 0, 'vendorinfo' => $vendorinfo]);
        } else {
            throw new ErrorException('Cannot find such weibo vendor');
        }

    }

    //范围选择查询(单一)
    public function getAndRangeSearch($query,$start,$end,$selector){
        if (!empty($start) && !empty($end)){
            $query->andWhere(['between',$selector,$start,$end]);
        }elseif(!empty($start)){
            $query->andWhere(['>=',$selector,$start]);
        }elseif(!empty($end)){
            $query->andWhere(['<=',$selector,$end]);
        }
        return $query;
    }

    //范围选择查询（两个or）
    public function getOrRangeSearch($query,$start,$end,$selector_one,$selector_two){
        if (!empty($start) && !empty($end)){
            $query->andWhere(['or',['between',$selector_one,$start,$end],['between',$selector_two,$start,$end]]);
        }elseif(!empty($start)){
            $query->andWhere(['or',['>=',$selector_one,$start],['>=',$selector_two,$start]]);
        }elseif(!empty($end)){
            $query->andWhere(['or',['<=',$selector_one,$end],['<=',$selector_two,$end]]);
        }
        return $query;
    }

}