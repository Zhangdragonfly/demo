<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/8/16 4:31 PM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\helpers\MediaHelper;
use common\models\MediaCollectLibraryGroupVideoItem;
use wom\controllers\BaseAppController;
use common\models\MediaCollectLibraryGroup;
use common\models\MediaVideo;
use common\models\VendorVideoBind;
use common\models\VideoPlatformCommonInfo;
use common\models\VideoVendorPrice;
use common\models\MediaVendor;
use common\models\AdVideoPlan;
use common\models\UserAccount;
use common\models\AdVideoOrder;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use yii;
use PHPExcel;
use PHPExcel_Writer_Excel2007;
use PHPExcel_Writer_Excel5;


/**
 * 广告主个人中心/视频媒体库管理
 * Class AdminvideoMediaLibController
 * @package wom\modules\adOwner\controllers\AdminController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminVideoMediaLibController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 视频媒体库管理列表
     */
    public function actionList(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        $query = (new Query())
        ->select([
            'group.uuid as group_uuid',
            'group.group_name',
            'group.media_cnt',
            'group.total_fan_cnt',
            'group.extra_data',
            'group.last_update_time',
        ])
        ->distinct()
        ->from(['group' => MediaCollectLibraryGroup::tableName()])
        ->leftJoin(['item' => MediaCollectLibraryGroupVideoItem::tableName()], 'group.uuid = item.group_uuid')
        ->andWhere(['group.ad_owner_uuid'=>$ad_owner_uuid])
        ->andWhere(['group.cate'=>MediaCollectLibraryGroup::CATE_VIDEO])
        ->andWhere(['group.status'=>MediaCollectLibraryGroup::STATUS_OK])
        ->orderBy(['group.last_update_time' => SORT_DESC]);
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $platform_name = $request->post('platform_name');
            $group_name = $request->post('group_name');
            if (!empty($group_name)){
                $query->andWhere(['or', ['like', 'group.group_name', $group_name]]);
            }
            if (!empty($platform_name)){
                $query->andWhere(['or', ['like', 'item.platform_name', $platform_name]]);
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

    /**
     * 视频媒体库资源详情列表
     */
    public function actionDetail(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        $group_uuid = $request->get('group_uuid');
        $videoGroup = MediaCollectLibraryGroup::findOne(['uuid'=>$group_uuid]);
        $page = $request->post('page',0);

        $query = (new Query())
        ->select('
            item.uuid,
            item.group_uuid,
            group.group_name,
            group.media_cnt,
            item.group_uuid,
            video.uuid as video_uuid,
            video.nickname,
            video.media_cate,
            video.address,
            video.sex,
            video.main_platform,
            platform.uuid as platform_uuid,
            platform.platform_type,
            platform.account_name,
            platform.account_id,
            platform.follower_num,
            platform.avg_watch_num,
            platform.status,
            platform.is_put,
            platform.is_top,
            platform.is_push,
            platform.url,
            platform.avatar,
            platform.create_time,
            platform.update_time,
            bind.active_end_time,
            price.price_config,
            price.price_orig_one,
            price.price_orig_two,
        ')
        ->from(['video' => MediaVideo::tableName()])
        ->leftJoin(['platform' => VideoPlatformCommonInfo::tableName()],'video.uuid = platform.video_uuid ')
        ->leftJoin(['bind' => VendorVideoBind::tableName()],'video.uuid = bind.video_uuid and bind.is_pref_vendor = 1')
        ->leftJoin(['vendor' => MediaVendor::tableName()],'vendor.uuid = bind.vendor_uuid')
        ->leftJoin(['price' => VideoVendorPrice::tableName()],'bind.uuid = price.vendor_bind_uuid and platform.uuid = price.platform_uuid')
        ->leftJoin(['item'=>MediaCollectLibraryGroupVideoItem::tableName()], 'item.video_media_uuid  =  platform.uuid ')
        ->leftJoin(['group'=>MediaCollectLibraryGroup::tableName()], 'group.uuid  =  item.group_uuid ')
        //->andWhere(['=', 'platform.is_put', 1])//已上架
        ->andWhere(['=', 'platform.status', 1])//已审核
        ->andWhere(['group.ad_owner_uuid'=>$ad_owner_uuid])
        ->andWhere(['group.uuid'=>$group_uuid])
        ->orderBy(['platform.is_top' => SORT_DESC,'video.update_time' => SORT_DESC]);

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
        return $this->render('detail', [
            'dataProvider' =>  $dataProvider->getModels(),
            'group_uuid' =>  $videoGroup->uuid,
            'group_name' =>  $videoGroup->group_name,
            'pager' => $pager
        ]);

    }

    /**
     * 新建视频媒体库
     */
    public function actionAddVideoLib(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        if ($request->isPost) {
            $group_name = $request->post('group_name');
            $libGroup = new MediaCollectLibraryGroup();
            $libGroup->uuid = PlatformHelper::getUUID();
            $libGroup->ad_owner_uuid = $ad_owner_uuid;
            $libGroup->group_name = $group_name;
            $libGroup->cate = MediaCollectLibraryGroup::CATE_VIDEO;
            $libGroup->status = MediaCollectLibraryGroup::STATUS_OK;
            $libGroup->extra_data = json_encode(['total_avg_watch_num'=>0]);
            $libGroup->create_time = time();
            $libGroup->last_update_time = time();
            $libGroup->save();
            return json_encode(['err_code' => 0, 'err_msg' => '新建成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '新建失败']);
        }
    }

    /**
     * 删视频媒体库
     */
    public function actionDeleteGroup(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $group_uuid = $request->post('group_uuid');
            $libGroup = MediaCollectLibraryGroup::findOne(['uuid'=>$group_uuid]);
            $libGroup->status = MediaCollectLibraryGroup::STATUS_DELETED;
            $libGroup->save();
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功', 'redirect_url' => Url::to(['/ad-owner/admin-video-media-lib/list'])]);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败', 'redirect_url' => Url::to(['/ad-owner/admin-video-media-lib/list'])]);
        }
    }

    /**
     * 单个删除视频媒体库中的资源
     */
    public function actionRemoveMedia(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $media_uuid = $request->post('media_uuid');
            $item = MediaCollectLibraryGroupVideoItem::findOne(['uuid'=>$media_uuid]);
            $platform = VideoPlatformCommonInfo::findOne(['uuid'=>$item->video_media_uuid]);
            //媒体库变动
            $groupLib = MediaCollectLibraryGroup::findOne(['uuid'=>$item->group_uuid]);
            $groupLib->media_cnt = $groupLib->media_cnt -1;
            $groupLib->total_fan_cnt = $groupLib->total_fan_cnt - $platform->follower_num;
            $extradata = json_decode($groupLib->extra_data)->total_avg_watch_num - ($platform->avg_watch_num);
            $groupLib->extra_data = json_encode(['total_avg_watch_num'=>$extradata]);
            $groupLib->last_update_time = time();
            $groupLib->save();
            //媒体库资源删除
            MediaCollectLibraryGroupVideoItem::deleteAll(['uuid'=>$media_uuid]);
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败']);
        }
    }


    /**
     * 获取视频媒体库列表
     */
    public function actionGetAllLib(){
        $request = Yii::$app->request;
        $loginAccountInfo = $this->getLoginAccountInfo();
        $videoMedialibList = MediaCollectLibraryGroup::find()
            ->where(['ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'cate' => $this->getVideoPlatformCode(), 'status' => MediaCollectLibraryGroup::STATUS_OK])
            ->orderBy(['last_update_time' => SORT_DESC])
            ->asArray()
            ->limit(10)
            ->all();
        return json_encode(['err_code' => 0, 'video_media_lib_list' => $videoMedialibList]);
    }

    /**
     * 重新分组
     */
    public function actionReGroup(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        if($request->isPost){
            $item_uuid = $request->post('item_uuid');
            $group_uuid = $request->post('group_uuid');//
            $group_uuid_array = $request->post('group_uuid_arr'); //需要新添的媒体库
            $item = MediaCollectLibraryGroupVideoItem::findOne(['uuid'=>$item_uuid]);
            $platform = VideoPlatformCommonInfo::findOne(['uuid'=>$item->video_media_uuid]);
            //媒体库变动
            $groupLib = MediaCollectLibraryGroup::findOne(['uuid'=>$group_uuid]);
            $groupLib->media_cnt = $groupLib->media_cnt -1;
            $groupLib->total_fan_cnt = $groupLib->total_fan_cnt - $platform->follower_num;
            $extradata = json_decode($groupLib->extra_data)->total_avg_watch_num - ($platform->avg_watch_num);
            $groupLib->extra_data = json_encode(['total_avg_watch_num'=>$extradata]);
            $groupLib->last_update_time = time();
            $groupLib->save();
            //媒体库资源删除
            MediaCollectLibraryGroupVideoItem::deleteAll(['uuid'=>$item_uuid]);
            //将资源添加到其他媒体库
            foreach($group_uuid_array as $key=>$val){
                $group = MediaCollectLibraryGroup::findOne(['uuid'=>$val]);
                $group->media_cnt = $group->media_cnt + 1;
                $group->total_fan_cnt = $group->total_fan_cnt + $platform->follower_num;
                $extradata = json_decode($group->extra_data)->total_avg_watch_num + ($platform->avg_watch_num);
                $group->extra_data = json_encode(['total_avg_watch_num'=>$extradata]);
                $group->last_update_time = time();
                $group->save();

                $itemAdd = new MediaCollectLibraryGroupVideoItem();
                $itemAdd->uuid = PlatformHelper::getUUID();
                $itemAdd->group_uuid = $val;
                $itemAdd->video_media_uuid = $platform->uuid;
                $itemAdd->platform_name = $platform->account_name;
                $itemAdd->add_time = time();
                $itemAdd->save();
            }
            return json_encode(['err_code' => 0, 'err_msg' => '分组成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '分组失败']);
        }
    }



    /**
     * 批量删除视频媒体库中的资源
     */
    public function actionRemoveMediaBatch(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $delete_item_uuid_array = $request->post('delete_item_uuid');
            foreach($delete_item_uuid_array as $key=>$val){
                $item = MediaCollectLibraryGroupVideoItem::findOne(['uuid'=>$val]);
                $platform = VideoPlatformCommonInfo::findOne(['uuid'=>$item->video_media_uuid]);
                //媒体库变动
                $groupLib = MediaCollectLibraryGroup::findOne(['uuid'=>$item->group_uuid]);
                $groupLib->media_cnt = $groupLib->media_cnt -1;
                $groupLib->total_fan_cnt = $groupLib->total_fan_cnt - $platform->follower_num;
                $extradata = json_decode($groupLib->extra_data)->total_avg_watch_num - ($platform->avg_watch_num);
                $groupLib->extra_data = json_encode(['total_avg_watch_num'=>$extradata]);
                $groupLib->last_update_time = time();
                $groupLib->save();
                //媒体库资源删除
                MediaCollectLibraryGroupVideoItem::deleteAll(['uuid'=>$val]);
            }
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败']);
        }
    }


    /**
     * 视频立即预约生成plan和order
     */
    public function actionCreateVideoPlan(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $create_item_uuid_array = $request->post('create_item_uuid');
            //新增视频plan
            $videoPlan = new AdVideoPlan();
            $videoPlan->uuid = PlatformHelper::getUUID();
            $videoPlan->ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
            $videoPlan->create_time = time();
            $videoPlan->last_update_time = time();
            $videoPlan->save();
            $plan_uuid = $videoPlan->uuid;
            foreach($create_item_uuid_array as $key=>$item_uuid) {
                $item = MediaCollectLibraryGroupVideoItem::findOne(['uuid'=>$item_uuid]);
                $platform_uuid = $item->video_media_uuid;
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
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '保存失败']);
        }
    }


    /**
     * 获取视频媒体库资源列表
     */
    public function actionGetGroupItem(){
        $request = Yii::$app->request;
        $group_uuid = $request->get('group_uuid');
        $mediaList = MediaCollectLibraryGroupVideoItem::find()
            ->select('video_media_uuid')
            ->where(['group_uuid'=>$group_uuid])
            ->asArray()
            ->all();
        return json_encode(['err_code' => 0, 'video_media_list' => $mediaList]);
    }



    //导出媒体库里的全部资源
    public function actionExportLibMedia(){
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isPost){
            $groupUuid = $request->post("group_uuid");
            if(!empty($groupUuid)){
                //账户信息
                $loginAccountInfo = $this->getLoginAccountInfo();
                $account_info = UserAccount::findOne(["uuid"=>$loginAccountInfo['account-uuid']]);//账户信息
                //Excel导出
                $objPHPExcel = new PHPExcel();
                $fileName = PlatformHelper::getUUID().'.xlsx';
                $objPHPExcel->getActiveSheet()->setCellValue('A1','序号');
                $objPHPExcel->getActiveSheet()->setCellValue('B1','艺人名称');
                $objPHPExcel->getActiveSheet()->setCellValue('C1','艺人分类');
                $objPHPExcel->getActiveSheet()->setCellValue('D1','所在地');
                $objPHPExcel->getActiveSheet()->setCellValue('E1','入驻平台');
                $objPHPExcel->getActiveSheet()->setCellValue('F1','平台名称');
                $objPHPExcel->getActiveSheet()->setCellValue('G1','平台ID');
                $objPHPExcel->getActiveSheet()->setCellValue('H1','粉丝数');
                $objPHPExcel->getActiveSheet()->setCellValue('I1','平均观看人数');
                $objPHPExcel->getActiveSheet()->setCellValue('J1','线上直播');
                $objPHPExcel->getActiveSheet()->setCellValue('K1','线下活动');
                $objPHPExcel->getActiveSheet()->setCellValue('L1','原创视频');
                $objPHPExcel->getActiveSheet()->setCellValue('M1','视频转发');
                $objPHPExcel->getActiveSheet()->setCellValue('N1','价格有效期');
                $objPHPExcel->getActiveSheet()->setCellValue('O1','更新时间');
                $objPHPExcel->getActiveSheet()->setCellValue('P1','合作备注');
                $objPHPExcel->getActiveSheet()->setCellValue('Q1','个性签名');
                if($account_info->is_public_account==0){//对内部导出
                    $objPHPExcel->getActiveSheet()->setCellValue('R1','供应商名称');
                }
                //微信资源
                $videoItem = MediaCollectLibraryGroupVideoItem::findAll(['group_uuid' => $groupUuid]);
                foreach($videoItem as $key=>$item){
                    $platformUuid = $item->video_media_uuid;
                    $platform = VideoPlatformCommonInfo::findOne(['uuid'=>$platformUuid]);
                    $mediaVideo = MediaVideo::findOne(['uuid'=>$platform->video_uuid]);
                    $vendorBind = VendorVideoBind::findOne(['video_uuid'=>$platform->video_uuid,'is_pref_vendor'=>1]);
                    $videoPrice = VideoVendorPrice::findOne(['vendor_bind_uuid'=>$vendorBind->uuid,'platform_uuid'=>$platformUuid]);
                    $mediaVendor = MediaVendor::findOne(['uuid'=>$vendorBind->vendor_uuid]);
                    //资源标签
                    $media_cate ="";
                    $mediaCate = MediaHelper::parseVideoMediaCate($mediaVideo->media_cate);
                    foreach(json_decode($mediaCate) as $cate){
                        $media_cate .= $cate."/";
                    }
                    $media_cate = substr($media_cate,0,-1);
                    //粉丝区域
                    $address = "";
                    $followerArea = MediaHelper::parseCity($mediaVideo->address);
                    foreach(json_decode($followerArea) as $area){
                        $address .= $area."/";
                    }
                    $address = substr($address,0,-1);
                    //平台类型
                    $platformTagList = MediaHelper::getMediaVideoPlatformList();
                    foreach($platformTagList as $k=>$v){
                        if($platform->platform_type == $k){
                            $platform_type = $v;
                        }
                    }
                    //Excel赋值
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($key+2), ($key+1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($key+2), ($mediaVideo->nickname));
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($key+2), ($media_cate));
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($key+2), ($address));
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($key+2), ($platform_type));
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($key+2), ($platform->account_name));
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($key+2), ($platform->account_id));
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($key+2), ($platform->follower_num));
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($key+2), ($platform->avg_watch_num));
                    if($platform->platform_type == 5){//原创视频、视频转发
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($key+2), ("/"));
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($key+2), ("/"));
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($key+2), ($videoPrice->price_retail_one));
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($key+2), ($videoPrice->price_retail_two));
                    }else{//线下活动、线上直播
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($key+2), ($videoPrice->price_retail_one));
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($key+2), ($videoPrice->price_retail_two));
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($key+2), ("/"));
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($key+2), ("/"));
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($key+2), ((!empty($vendorBind->active_end_time))?date('Y-m-d',$vendorBind->active_end_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . ($key+2), ((!empty($mediaVideo->update_time))?date('Y-m-d',$mediaVideo->update_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . ($key+2), ($platform->remark));
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($key+2), ($platform->person_sign));
                    if($account_info->is_public_account==0){//对内部导出
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . ($key+2), ($mediaVendor->name));
                    }
                }
                //保存excel—2007格式
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($fileName);
                return ['err_code'=>0,'filename'=>$fileName];
            }else{
                return $this->redirect('index.php?r=ad-owner/admin-weibo-media-lib/list');
            }
        }else{
            return $this->redirect('index.php?r=ad-owner/admin-weibo-media-lib/list');
        }
    }


    //导出媒体库里的选中资源
    public function actionExportMedia(){
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isPost) {
            $item_uuid_arr = $request->post('item_uuid_arr');
            $itemUuidArray = explode(",",$item_uuid_arr);
            $itemUuidArray = array_filter($itemUuidArray);
            if(!empty($itemUuidArray)){
                //账户信息
                $loginAccountInfo = $this->getLoginAccountInfo();
                $account_info = UserAccount::findOne(["uuid"=>$loginAccountInfo['account-uuid']]);//账户信息
                //Excel导出
                $objPHPExcel = new PHPExcel();
                $fileName = PlatformHelper::getUUID().'.xlsx';
                $objPHPExcel->getActiveSheet()->setCellValue('A1','序号');
                $objPHPExcel->getActiveSheet()->setCellValue('B1','艺人名称');
                $objPHPExcel->getActiveSheet()->setCellValue('C1','艺人分类');
                $objPHPExcel->getActiveSheet()->setCellValue('D1','所在地');
                $objPHPExcel->getActiveSheet()->setCellValue('E1','入驻平台');
                $objPHPExcel->getActiveSheet()->setCellValue('F1','平台名称');
                $objPHPExcel->getActiveSheet()->setCellValue('G1','平台ID');
                $objPHPExcel->getActiveSheet()->setCellValue('H1','粉丝数');
                $objPHPExcel->getActiveSheet()->setCellValue('I1','平均观看人数');
                $objPHPExcel->getActiveSheet()->setCellValue('J1','线上直播');
                $objPHPExcel->getActiveSheet()->setCellValue('K1','线下活动');
                $objPHPExcel->getActiveSheet()->setCellValue('L1','原创视频');
                $objPHPExcel->getActiveSheet()->setCellValue('M1','视频转发');
                $objPHPExcel->getActiveSheet()->setCellValue('N1','价格有效期');
                $objPHPExcel->getActiveSheet()->setCellValue('O1','更新时间');
                $objPHPExcel->getActiveSheet()->setCellValue('P1','合作备注');
                $objPHPExcel->getActiveSheet()->setCellValue('Q1','个性签名');
                if($account_info->is_public_account==0){//对内部导出
                    $objPHPExcel->getActiveSheet()->setCellValue('R1','供应商名称');
                }
                foreach($itemUuidArray as $key=>$itemUuid){
                    $item = MediaCollectLibraryGroupVideoItem::findOne(['uuid'=>$itemUuid]);
                    $platformUuid = $item->video_media_uuid;
                    $platform = VideoPlatformCommonInfo::findOne(['uuid'=>$platformUuid]);
                    $mediaVideo = MediaVideo::findOne(['uuid'=>$platform->video_uuid]);
                    $vendorBind = VendorVideoBind::findOne(['video_uuid'=>$platform->video_uuid,'is_pref_vendor'=>1]);
                    $videoPrice = VideoVendorPrice::findOne(['vendor_bind_uuid'=>$vendorBind->uuid,'platform_uuid'=>$platformUuid]);
                    $mediaVendor = MediaVendor::findOne(['uuid'=>$vendorBind->vendor_uuid]);
                    //资源标签
                    $media_cate ="";
                    $mediaCate = MediaHelper::parseVideoMediaCate($mediaVideo->media_cate);
                    foreach(json_decode($mediaCate) as $cate){
                        $media_cate .= $cate."/";
                    }
                    $media_cate = substr($media_cate,0,-1);
                    //粉丝区域
                    $address = "";
                    $followerArea = MediaHelper::parseCity($mediaVideo->address);
                    foreach(json_decode($followerArea) as $area){
                        $address .= $area."/";
                    }
                    $address = substr($address,0,-1);
                    //平台类型
                    $platformTagList = MediaHelper::getMediaVideoPlatformList();
                    foreach($platformTagList as $k=>$v){
                        if($platform->platform_type == $k){
                            $platform_type = $v;
                        }
                    }
                    //Excel赋值
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($key+2), ($key+1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($key+2), ($mediaVideo->nickname));
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($key+2), ($media_cate));
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($key+2), ($address));
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($key+2), ($platform_type));
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($key+2), ($platform->account_name));
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($key+2), ($platform->account_id));
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($key+2), ($platform->follower_num));
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($key+2), ($platform->avg_watch_num));
                    if($platform->platform_type == 5){//原创视频、视频转发
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($key+2), ("/"));
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($key+2), ("/"));
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($key+2), ($videoPrice->price_retail_one));
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($key+2), ($videoPrice->price_retail_two));
                    }else{//线下活动、线上直播
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($key+2), ($videoPrice->price_retail_one));
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($key+2), ($videoPrice->price_retail_two));
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($key+2), ("/"));
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($key+2), ("/"));
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($key+2), ((!empty($vendorBind->active_end_time))?date('Y-m-d',$vendorBind->active_end_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . ($key+2), ((!empty($mediaVideo->update_time))?date('Y-m-d',$mediaVideo->update_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . ($key+2), ($platform->remark));
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($key+2), ($platform->person_sign));
                    if($account_info->is_public_account==0){//对内部导出
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . ($key+2), ($mediaVendor->name));
                    }
                }
                //保存excel—2007格式
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($fileName);
                return ['err_code'=>0,'filename'=>$fileName];
            }
        }
    }

    //删除导出的excel文件
    public function actionDeleteExportExcel(){
        $request = Yii::$app->request;
        if($request->isPost) {
            $fileName = $request->post('filename');
            if(!empty($fileName)){
                $excelFile = $_SERVER["DOCUMENT_ROOT"]."/".$fileName;
                if (file_exists ($excelFile)) {
                    unlink ($excelFile );
                }
            }
            return json_encode(['err_code'=>0,'err_msg'=>'删除成功！']);
        }else{
            return json_encode(['err_code'=>1,'err_msg'=>'删除失败！']);
        }

    }

}
