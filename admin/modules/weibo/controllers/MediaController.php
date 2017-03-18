<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 25/10/16 2:24 PM By Manson
 */
namespace admin\modules\weibo\controllers;

use admin\controllers\BaseAppController;
use common\helpers\MediaHelper;
use yii\data\ActiveDataProvider;
use admin\helpers\AdminHelper;
use common\models\MediaWeibo;
use common\models\MediaVendor;
use common\models\WeiboVendorBind;
use common\helpers\PlatformHelper;
use yii\db\Query;
use Yii;
/**
 * 微博媒体资源控制类
 * Class MediaController
 */
class MediaController extends BaseAppController{


    /**
     * 微博资源入驻页面  //TODO
     */
    public function actionCreate(){
        $request = Yii::$app->request;
        if ($request->isPost){
            $vendo_bind_array = $request->post('vendor_info_json');
            $pref_vendor = $request->post('pref_vendor');
            //微博资源保存
            $media_weibo = new MediaWeibo();
            $media_weibo->uuid = PlatformHelper::getUUID();
            $media_weibo->weibo_name = trim($request->post('weibo_name'));
            $media_weibo->follower_num = $request->post('follower_num');
            $media_weibo->weibo_url = trim($request->post('weibo_url'));
            $media_weibo->media_level = trim($request->post('media_level'));
            $media_weibo->intro = trim($request->post('intro'));
            $media_weibo->accept_remark = trim($request->post('accept_remark'));
            $media_weibo->follower_area = trim($request->post('area'));
            $media_weibo->media_cate = trim($request->post('cate'));
            $media_weibo->status = 1;
            $media_weibo->is_put = 1;
            $media_weibo->create_time = time();
            $media_weibo->audit_time = time();
            $media_weibo->update_time = time();
            $media_weibo->enter_time = time();
            $media_weibo->save();
            //weibo_vendor_bind 保存
            foreach($vendo_bind_array as $key=>$val){
                $weibo_vendor_bind = new WeiboVendorBind();
                $weibo_vendor_bind->uuid = PlatformHelper::getUUID();
                $weibo_vendor_bind->weibo_uuid = $media_weibo->uuid;
                $weibo_vendor_bind->vendor_uuid = $val['vendor_uuid'];
                $weibo_vendor_bind->soft_direct_price_orig = $val['s_d_orig'];
                $weibo_vendor_bind->soft_transfer_price_orig = $val['s_t_orig'];
                $weibo_vendor_bind->soft_direct_price_retail = $val['s_d_retail'];
                $weibo_vendor_bind->soft_transfer_price_retail = $val['s_t_retail'];
                $weibo_vendor_bind->soft_direct_price_execute = $val['s_d_execute'];
                $weibo_vendor_bind->soft_transfer_price_execute = $val['s_t_execute'];
                $weibo_vendor_bind->micro_direct_price_execute = $val['m_d_execute'];
                $weibo_vendor_bind->micro_transfer_price_execute = $val['m_t_execute'];
                $weibo_vendor_bind->micro_direct_price_retail = $val['m_d_retail'];
                $weibo_vendor_bind->micro_transfer_price_retail = $val['m_t_retail'];
                $weibo_vendor_bind->micro_direct_price_orig = $val['m_d_orig'];
                $weibo_vendor_bind->micro_transfer_price_orig = $val['m_t_orig'];
                $weibo_vendor_bind->cooperate_level = $val['cooperate_level'];
                $weibo_vendor_bind->account_period = $val['account_period'];
                $weibo_vendor_bind->belong_type = $val['belong_type'];
                $weibo_vendor_bind->active_end_time = strtotime($val['active_end_time']);
                $weibo_vendor_bind->create_time = time();
                $weibo_vendor_bind->update_time = time();
                $weibo_vendor_bind->status = 1;
                if($key == $pref_vendor){
                    $weibo_vendor_bind->is_pref_vendor = 1;
                }
                $weibo_vendor_bind->save();

                //vendor表微博资源的改动
                $vendor = MediaVendor::findOne(['uuid'=>$val['vendor_uuid']]);
                $vendor->weibo_media_cnt = ($vendor->weibo_media_cnt) + 1;
                $vendor->save();

            }
            return json_encode(['err_code'=>0,'err_msg'=>'入驻成功！']);
        }
        return $this->render("create");
    }

    /**
     * 微博资源列表页
     */
    public function actionList(){
        $request = Yii::$app->request;
        $search_type = $request->get("type");//查询类型 1待审核 2已审核 3未通过 4待更新
        $vendor_uuid = $request->get("vendor_uuid",'');//媒体主
        $query = (new Query())
        ->select([
            'media_weibo.uuid',
            'media_weibo.weibo_name',
            'media_weibo.media_level',
            'media_weibo.media_cate',
            'media_weibo.follower_num',
            'media_weibo.is_put',
            'media_weibo.is_top',
            'media_weibo.is_push',
            'media_weibo.status',
            'media_weibo.create_time',
            'media_weibo.update_time',
            'media_weibo.accept_remark_one',
            'weibo_vendor_bind.soft_direct_price_orig',
            'weibo_vendor_bind.soft_transfer_price_orig',
            'weibo_vendor_bind.micro_direct_price_orig',
            'weibo_vendor_bind.micro_transfer_price_orig',
            'weibo_vendor_bind.soft_direct_price_retail',
            'weibo_vendor_bind.soft_transfer_price_retail',
            'weibo_vendor_bind.micro_direct_price_retail',
            'weibo_vendor_bind.micro_transfer_price_retail',
            'weibo_vendor_bind.soft_direct_price_execute',
            'weibo_vendor_bind.soft_transfer_price_execute',
            'weibo_vendor_bind.micro_direct_price_execute',
            'weibo_vendor_bind.micro_transfer_price_execute',
            'weibo_vendor_bind.active_end_time',
            'media_vendor.name'
        ])
        ->from(['media_weibo' => MediaWeibo::tableName()])
        ->orderBy(['media_weibo.is_top' => SORT_DESC,'media_weibo.update_time' => SORT_DESC]);

        $vendor_name = '';
        if(!empty($vendor_uuid)){//供应商对应的资源列表
            $vendor = MediaVendor::findOne(['uuid'=>$vendor_uuid]);
            $vendor_name = $vendor->name;
            $query->leftJoin(['weibo_vendor_bind'], 'weibo_vendor_bind.weibo_uuid  =  media_weibo.uuid')
                  ->leftJoin(['media_vendor'], 'weibo_vendor_bind.vendor_uuid  =  media_vendor.uuid ')
                  ->andWhere(['weibo_vendor_bind.vendor_uuid'=>$vendor_uuid]);
        }else{
            $query->leftJoin(['weibo_vendor_bind'], 'weibo_vendor_bind.weibo_uuid  =  media_weibo.uuid  and  weibo_vendor_bind.is_pref_vendor = 1')
                  ->leftJoin(['media_vendor'], 'weibo_vendor_bind.vendor_uuid  =  media_vendor.uuid ');
        }

        if($search_type==1){//待审核
            $query->andWhere(['media_weibo.status'=>0]);
        }
        if($search_type==2){//已审核
            $query->andWhere(['media_weibo.status'=>1]);
        }
        if($search_type==3){//未通过
            $query->andWhere(['media_weibo.status'=>2]);
        }
        if($search_type==4){//带更新
            $need_update_time = strtotime("+14 day");//十四天后的时间
            $query->andWhere(['<=','weibo_vendor_bind.active_end_time',$need_update_time]);
            $query->andWhere(['media_weibo.is_put'=>1]);
            $query->andWhere(['media_weibo.status'=>1]);
            $query->orderBy(['weibo_vendor_bind.active_end_time' => SORT_DESC]);
        }
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $search_name = $request->post('search_name');
            $status = $request->post('status');
            $media_cate = $request->post('media_cate');
            $is_put = $request->post('is_put');
            $follower_num_start = $request->post('follower_num_start');
            $follower_num_end = $request->post('follower_num_end');
            $price_search_type =  $request->post('price_search_type');//价格类型 1 软广 2 微任务
            $price_start = $request->post('price_start');
            $price_end = $request->post('price_end');
            $accept_remark_one = $request->post('accept_remark_one');//1.0接待备注 待删除
            $expire_start_time = strtotime($request->post('expire_start_time'));
            $expire_end_time = strtotime($request->post('expire_end_time'));
            if (!empty($accept_remark_one)){//1.0备注
                $query->andWhere(['or', ['like', 'media_weibo.accept_remark_one', $accept_remark_one]]);
            }
            if (!empty($search_name)){   //微博/自媒体搜索
                $query->andWhere(['or', ['like', 'media_vendor.name', $search_name], ['like', 'media_weibo.weibo_name', $search_name]]);
            }
            if ($status != -1){ //资源状态
                $query->andWhere(['media_weibo.status'=>$status]);
            }
            if ($is_put != -1){ //是否上架
                $query->andWhere(['media_weibo.is_put'=>$is_put]);
            }
            if ($media_cate != -1){  //标签
                $query->andWhere(['like', 'media_weibo.media_cate', "#".$media_cate."#"]);
            }
            //过期时间
            $query = $this->getRangeSearch($query,$expire_start_time,$expire_end_time,'weibo_vendor_bind.active_end_time');
            //微博合作价查询
            if($price_search_type != -1){
                if($price_search_type == 1){
                    $query = $this->getOrRangeSearch($query,$price_start,$price_end,'weibo_vendor_bind.soft_direct_price_orig','weibo_vendor_bind.soft_transfer_price_orig');
                }else{
                    $query = $this->getOrRangeSearch($query,$price_start,$price_end,'weibo_vendor_bind.micro_direct_price_orig','weibo_vendor_bind.micro_transfer_price_orig');
                }
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
            'dataProvider' => $dataProvider,
            "search_type"=>$search_type,
            "vendor_uuid"=>$vendor_uuid,
            "vendor_name"=>$vendor_name,
        ]);
    }

    /**
     *  微博资源详情页
     */
    public function actionDetail(){
        $request = Yii::$app->request;
        if ($request->isGet) {
            $weibo_uuid= $request->get('uuid');
            $query = (new Query())
            ->select([
                'media_weibo.*',
                'weibo_vendor_bind.soft_direct_price_orig',
                'weibo_vendor_bind.soft_transfer_price_orig',
                'weibo_vendor_bind.micro_direct_price_orig',
                'weibo_vendor_bind.micro_transfer_price_orig',
                'weibo_vendor_bind.soft_direct_price_retail',
                'weibo_vendor_bind.soft_transfer_price_retail',
                'weibo_vendor_bind.micro_direct_price_retail',
                'weibo_vendor_bind.micro_transfer_price_retail',
                'weibo_vendor_bind.soft_direct_price_execute',
                'weibo_vendor_bind.soft_transfer_price_execute',
                'weibo_vendor_bind.micro_direct_price_execute',
                'weibo_vendor_bind.micro_transfer_price_execute',
                'media_vendor.name'
            ])
            ->from(['media_weibo' => MediaWeibo::tableName()])
            ->leftJoin(['weibo_vendor_bind'], 'weibo_vendor_bind.weibo_uuid  =  media_weibo.uuid')
            ->leftJoin(['media_vendor'], 'weibo_vendor_bind.vendor_uuid  =  media_vendor.uuid')
            ->andWhere(['media_weibo.uuid'=>$weibo_uuid]);
            $command = $query->createCommand();
            $rows = $command->queryOne();
            if(!empty($rows)){
                return $this->render('detail',['data'=>$rows]);
            }else{
                return $this->redirect('?r=weibo/media/list');
            }
        }else{
            return $this->redirect('?r=weibo/media/list');
        }
    }

    /**
     *  微博资源上下架
     */
    public function actionPutUpDown(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $weibo_uuid = $request->post("weibo_uuid");
            $type = $request->post("type");
            $mediaWeibo = MediaWeibo::findOne(['uuid' => $weibo_uuid]);
            if($type == "up"){//上架
                $weiboVendorBind = WeiboVendorBind::findAll(['is_pref_vendor' =>1,'weibo_uuid'=>$weibo_uuid]);
                if(!empty($weiboVendorBind)){//存在首选供应商
                    $mediaWeibo->is_put = 1;
                    $mediaWeibo->put_down_time = 0;
                }else{
                    return json_encode(array('err_code'=>1,'err_msg'=>'没有首选供应商，无法上架！'));
                }
            }
            if($type == "down"){//下架
                WeiboVendorBind::updateAll(['is_pref_vendor' =>0], ['weibo_uuid' => $weibo_uuid]);//解除首选供应商
                $mediaWeibo->is_put = 0;
                $mediaWeibo->is_top = 0;
                $mediaWeibo->put_down_time = time();
            }
            $mediaWeibo->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    /**
     *  微博资源置顶
     */
    public function actionPutTopDown(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $weibo_uuid = $request->post("weibo_uuid");
            $type = $request->post("type");
            $mediaWeibo = MediaWeibo::findOne(['uuid' => $weibo_uuid]);
            if($type == "top"){
                $mediaWeibo->is_top = 1;
            }
            if($type == "down"){
                $mediaWeibo->is_top = 0;
            }
            $mediaWeibo->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    /**
     *  微博资源主推
     */
    public function actionPushTopDown(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $weibo_uuid = $request->post("weibo_uuid");
            $type = $request->post("type");
            $mediaWeibo = MediaWeibo::findOne(['uuid' => $weibo_uuid]);
            if($type == "push"){
                $mediaWeibo->is_push = 1;
            }
            if($type == "down"){
                $mediaWeibo->is_push = 0;
            }
            $mediaWeibo->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    /**
     *  修改微博资源基础信息
     */
    public function actionUpdateBase(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $weibo_uuid = $request->post("weibo_uuid");
            $mediaWeibo = MediaWeibo::findOne(['uuid' => $weibo_uuid]);
            $mediaWeibo->weibo_name = trim($request->post("weibo_name"));
            $mediaWeibo->follower_num = trim($request->post("follower_num"));
            $mediaWeibo->media_level = trim($request->post("media_level"));
            $mediaWeibo->weibo_url = trim($request->post("weibo_url"));
            $mediaWeibo->accept_remark = trim($request->post("accept_remark"));
            $mediaWeibo->intro = trim($request->post("intro"));
            $mediaWeibo->follower_area = trim($request->post("area"));
            $mediaWeibo->media_cate = trim($request->post("cate"));
            $mediaWeibo->status = trim($request->post("audit_status"));
            $mediaWeibo->audit_time = ($request->post("audit_status") ==0)? 0:time();
            $mediaWeibo->update_time = time();
            $mediaWeibo->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    /**
     *  修改微博媒体主审核信息
     */
    public function actionUpdateAudit(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $weibo_uuid = $request->post("weibo_uuid");
            $bind_uuid = $request->post("bind_uuid");
            $is_pref_vendor = $request->post("is_pref_vendor");
            //微博资源
            $Weibo = MediaWeibo::findOne(['uuid' => $weibo_uuid]);
            $Weibo->is_put = trim($request->post("is_put"));
            $Weibo->is_top = trim($request->post("is_top"));
            $Weibo->update_time = time();
            ($request->post("is_put") == 1)? $Weibo->put_down_time = 0:$Weibo->put_down_time = time();
            $Weibo->save();
            //weibo_vendor_bind
            $WeiboBind = WeiboVendorBind::findOne(['uuid' => $bind_uuid]);
            $WeiboBind->status = trim($request->post("status"));
            $WeiboBind->belong_type = trim($request->post("belong_type"));
            $WeiboBind->account_period = trim($request->post("account_period"));
            $WeiboBind->cooperate_level = trim($request->post("cooperate_level"));
            $WeiboBind->soft_direct_price_orig = trim($request->post("s_d_orig"));
            $WeiboBind->soft_transfer_price_orig = trim($request->post("s_t_orig"));
            $WeiboBind->soft_direct_price_retail = trim($request->post("s_d_retail"));
            $WeiboBind->soft_transfer_price_retail = trim($request->post("s_t_retail"));
            $WeiboBind->soft_direct_price_execute = trim($request->post("s_d_execute"));
            $WeiboBind->soft_transfer_price_execute = trim($request->post("s_t_execute"));
            $WeiboBind->micro_direct_price_execute = trim($request->post("m_d_execute"));
            $WeiboBind->micro_transfer_price_execute = trim($request->post("m_t_execute"));
            $WeiboBind->micro_direct_price_retail = trim($request->post("m_d_retail"));
            $WeiboBind->micro_transfer_price_retail = trim($request->post("m_t_retail"));
            $WeiboBind->micro_direct_price_orig = trim($request->post("m_d_orig"));
            $WeiboBind->micro_transfer_price_orig = trim($request->post("m_t_orig"));
            $WeiboBind->active_end_time = strtotime($request->post("active_end_time"));
            $WeiboBind->update_time = strtotime($request->post("update_time"));
            $WeiboBind->is_pref_vendor = $is_pref_vendor;
            if($is_pref_vendor == 1){//首选供应商更替
                WeiboVendorBind::updateAll(['is_pref_vendor' =>0], ['weibo_uuid' => $weibo_uuid]);
                WeiboVendorBind::updateAll(['is_pref_vendor' =>1], ['uuid' => $bind_uuid]);
            }

            $WeiboBind->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    /**
     * 获取微博供应商列表
     */
    public function actionGetListOfVendor()
    {
        $request = Yii::$app->request;
        $weibo_uuid = $request->get('media_uuid');
        $vendorList = (new Query())
        ->select([
            'vendor.name',
            'vendor.register_type',
            'vendor.contact_person',
            'vendor.contact1',
            'weibo_vendor_bind.status',
            'weibo_vendor_bind.is_pref_vendor',
            'weibo_vendor_bind.uuid as vendor_bind_uuid',
            'weibo_vendor_bind.soft_direct_price_orig',
            'weibo_vendor_bind.soft_transfer_price_orig',
            'weibo_vendor_bind.micro_direct_price_orig',
            'weibo_vendor_bind.micro_transfer_price_orig',
            'weibo_vendor_bind.soft_direct_price_retail',
            'weibo_vendor_bind.soft_transfer_price_retail',
            'weibo_vendor_bind.micro_direct_price_retail',
            'weibo_vendor_bind.micro_transfer_price_retail',
            'weibo_vendor_bind.soft_direct_price_execute',
            'weibo_vendor_bind.soft_transfer_price_execute',
            'weibo_vendor_bind.micro_direct_price_execute',
            'weibo_vendor_bind.micro_transfer_price_execute',
            'media_weibo.is_put',
        ])
        ->from(['weibo_vendor_bind' => WeiboVendorBind::tableName()])
        ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weibo_vendor_bind.vendor_uuid')
        ->leftJoin(['media_weibo' => MediaWeibo::tableName()], 'media_weibo.uuid  =  weibo_vendor_bind.weibo_uuid')
        ->where(['weibo_vendor_bind.weibo_uuid' => $weibo_uuid])
        ->orderBy(['weibo_vendor_bind.create_time' => SORT_ASC])
        ->all();
        return json_encode(['err_code' => 0, 'err_msg' => '获取成功', 'vendor_list' => $vendorList]);
    }

    /**
     * 为微博资源添加媒体主
     */
    public function actionAddVendor(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $mediaUUID = $request->post('media_uuid');
            $vendorUUID = $request->post('vendor_uuid');
            $weiboVendorBind = WeiboVendorBind::findOne(['weibo_uuid' => $mediaUUID, 'vendor_uuid' => $vendorUUID]);
            if ($weiboVendorBind !== null) {
                return json_encode(['err_code' => 1, 'err_msg' => '已经存在']);
            }
            if ($weiboVendorBind === null){
                $weiboVendorBind = new WeiboVendorBind();
                $weiboVendorBind->uuid = PlatformHelper::getUUID();
                $weiboVendorBind->weibo_uuid = $mediaUUID;
                $weiboVendorBind->vendor_uuid = $vendorUUID;
                $weiboVendorBind->create_time = time();
                $weiboVendorBind->save();
                //vendor表微博资源的改动
                $vendor = MediaVendor::findOne(['uuid'=>$vendorUUID]);
                $vendor->weibo_media_cnt = ($vendor->weibo_media_cnt) + 1;
                $vendor->save();
            }
            return json_encode(['err_code' => 0, 'err_msg' => '添加成功!']);
        }
    }

    /**
     * 从微博资源移除媒体主
     */
    public function actionRemoveVendor(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $bindUUID = $request->post('bind_uuid');
            $weiboBind = WeiboVendorBind::findOne(['uuid'=>$bindUUID]);
            //vendor表微博资源的改动
            $vendor = MediaVendor::findOne(['uuid'=>$weiboBind->vendor_uuid]);
            $vendor->weibo_media_cnt = ($vendor->weibo_media_cnt) - 1;
            $vendor->save();
            WeiboVendorBind::deleteAll(['uuid' => $bindUUID]);
            return json_encode(['err_code' => 0, 'err_msg' => '移除成功!']);
        }
    }

    /**
     * 获得微博媒体主信息
     * @return array
     * @throws ErrorException
     */
    public function actionGetVendorInfo(){
        $request = Yii::$app->request;
        $bind_uuid = $request->get('bind_uuid');
        $vendorinfo = (new Query())
        ->select([
            'weibo_vendor_bind.uuid',
            'weibo_vendor_bind.status',
            'weibo_vendor_bind.cooperate_level',
            'weibo_vendor_bind.account_period',
            'weibo_vendor_bind.belong_type',
            'weibo_vendor_bind.is_pref_vendor',
            'weibo_vendor_bind.active_end_time',
            'weibo_vendor_bind.soft_direct_price_orig',
            'weibo_vendor_bind.soft_transfer_price_orig',
            'weibo_vendor_bind.micro_direct_price_orig',
            'weibo_vendor_bind.micro_transfer_price_orig',
            'weibo_vendor_bind.soft_direct_price_retail',
            'weibo_vendor_bind.soft_transfer_price_retail',
            'weibo_vendor_bind.micro_direct_price_retail',
            'weibo_vendor_bind.micro_transfer_price_retail',
            'weibo_vendor_bind.soft_direct_price_execute',
            'weibo_vendor_bind.soft_transfer_price_execute',
            'weibo_vendor_bind.micro_direct_price_execute',
            'weibo_vendor_bind.micro_transfer_price_execute',
            'media_weibo.is_top',
            'media_weibo.is_put',
            'vendor.name',
        ])
        ->from(['weibo_vendor_bind' => WeiboVendorBind::tableName()])
        ->leftJoin(['media_weibo' => MediaWeibo::tableName()], 'media_weibo.uuid  =  weibo_vendor_bind.weibo_uuid')
        ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weibo_vendor_bind.vendor_uuid')
        ->where(['weibo_vendor_bind.uuid' => $bind_uuid])
        ->orderBy(['weibo_vendor_bind.create_time' => SORT_ASC])
        ->one();
        //价格有效期
        $vendorinfo['active_end_time'] = ($vendorinfo['active_end_time']>0)? date("Y-m-d H:i", $vendorinfo['active_end_time']):"";
        if (isset($vendorinfo)) {
            return json_encode(['err_code' => 0, 'vendorinfo' => $vendorinfo]);
        } else {
            throw new ErrorException('Cannot find such weibo vendor');
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
                ->from(['weibo_vendor_bind' => WeiboVendorBind::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weibo_vendor_bind.vendor_uuid')
                ->where(['weibo_vendor_bind.uuid' => $bind_uuid])
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

    //删除微博资源
    public function actionDeleteWeibo(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $weibo_uuid = $request->post('weibo_uuid');
            MediaWeibo::deleteAll(['uuid' => $weibo_uuid]);
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功!']);
        }
    }

    //判断无首选供应商时资源下架
    public function actionDownMedia(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $weibo_uuid = $request->post('weibo_uuid');
            $weiboVendorBind = WeiboVendorBind::findAll(['is_pref_vendor' =>1,'weibo_uuid'=>$weibo_uuid]);
            if(empty($weiboVendorBind)){//不存在首选供应商时下架
                $Weibo = MediaWeibo::findOne(['uuid' => $weibo_uuid]);
                $Weibo->is_put = 0;
                $Weibo->put_down_time = 0;
                $Weibo->save();
            }
            return json_encode(['err_code' => 0, 'err_msg' => '修改成功!']);
        }
    }

    //检查微博是否存在
    public function actionCheckWeiboName(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $weibo_name = $request->post('weibo_name');
            $weibo = MediaWeibo::findOne(['weibo_name' => $weibo_name]);
            if ($weibo !== null) {
                return json_encode(['err_code' => 1, 'err_msg' => '该账号已经存在！']);
            } else {
                return json_encode(['err_code' => 0, 'err_msg' => '该账号已经在系统中存在！']);
            }
        }
    }

    //范围选择查询
    public function getRangeSearch($query,$start,$end,$selector){
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