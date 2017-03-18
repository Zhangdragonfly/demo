<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/8/16 4:31 PM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\helpers\MediaHelper;
use common\models\MediaCollectLibraryGroupWeiboItem;
use wom\controllers\BaseAppController;
use common\models\MediaCollectLibraryGroup;
use common\models\MediaWeibo;
use common\models\WeiboVendorBind;
use common\models\MediaVendor;
use common\models\AdWeiboPlan;
use common\models\AdWeiboOrder;
use common\models\UserAccount;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use yii;
use PHPExcel;
use PHPExcel_Writer_Excel2007;
//use PHPExcel_Writer_Excel5;


/**
 * 广告主个人中心/微博媒体库管理
 * Class AdminWeiboMediaLibController
 * @package wom\modules\adOwner\controllers\AdminController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminWeiboMediaLibController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 微博媒体库管理列表
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
        ->leftJoin(['item' => MediaCollectLibraryGroupWeiboItem::tableName()], 'group.uuid = item.group_uuid')
        ->andWhere(['group.ad_owner_uuid'=>$ad_owner_uuid])
        ->andWhere(['group.cate'=>MediaCollectLibraryGroup::CATE_WEIBO])
        ->andWhere(['group.status'=>MediaCollectLibraryGroup::STATUS_OK])
        ->orderBy(['group.last_update_time' => SORT_DESC]);
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $weibo_name = $request->post('weibo_name');
            $group_name = $request->post('group_name');
            if (!empty($group_name)){
                $query->andWhere(['or', ['like', 'group.group_name', $group_name]]);
            }
            if (!empty($weibo_name)){
                $query->andWhere(['or', ['like', 'item.weibo_name', $weibo_name]]);
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
     * 微博媒体库资源详情列表
     */
    public function actionDetail(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        $group_uuid = $request->get('group_uuid');
        $weiboGroup = MediaCollectLibraryGroup::findOne(['uuid'=>$group_uuid]);
        $page = $request->post('page',0);
        $query = (new Query())
        ->select('
             item.uuid,
             item.group_uuid,
             weibo.weibo_url,
             weibo.weibo_name,
             weibo.media_level,
             weibo.media_cate,
             weibo.follower_num,
             weibo.is_put,
             weibo.accept_remark,
             weibo.intro,
             weibo.update_time,
             bind.soft_direct_price_retail as sd_price,
             bind.soft_transfer_price_retail as st_price,
             bind.micro_direct_price_retail as md_price,
             bind.micro_transfer_price_retail as mt_price,
             bind.active_end_time,
             group.group_name,
             group.media_cnt,
        ')
        ->from(['weibo' => MediaWeibo::tableName()])
        ->leftJoin(['bind'=> WeiboVendorBind::tableName()], 'bind.weibo_uuid  =  weibo.uuid  and  bind.is_pref_vendor = 1')
        ->leftJoin(['vendor'=>MediaVendor::tableName()], 'bind.vendor_uuid  =  vendor.uuid ')
        ->leftJoin(['item'=>MediaCollectLibraryGroupWeiboItem::tableName()], 'item.weibo_media_uuid  =  weibo.uuid ')
        ->leftJoin(['group'=>MediaCollectLibraryGroup::tableName()], 'group.uuid  =  item.group_uuid ')
        //->andWhere(['=', 'weibo.is_put', 1])
        ->andWhere(['=', 'weibo.status', 1])
        ->andWhere(['group.ad_owner_uuid'=>$ad_owner_uuid])
        ->andWhere(['group.uuid'=>$group_uuid])
        ->orderBy(['weibo.is_top' => SORT_DESC,'weibo.update_time' => SORT_DESC]);
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
            'group_uuid' =>  $weiboGroup->uuid,
            'group_name' =>  $weiboGroup->group_name,
            'pager' => $pager
        ]);

    }

    /**
     * 新建微博媒体库
     */
    public function actionAddWeiboLib(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        if ($request->isPost) {
            $group_name = $request->post('group_name');
            $libGroup = new MediaCollectLibraryGroup();
            $libGroup->uuid = PlatformHelper::getUUID();
            $libGroup->ad_owner_uuid = $ad_owner_uuid;
            $libGroup->group_name = $group_name;
            $libGroup->cate = MediaCollectLibraryGroup::CATE_WEIBO;
            $libGroup->status = MediaCollectLibraryGroup::STATUS_OK;
            $libGroup->extra_data = json_encode(['total_micro_transfer_retail_price'=>0]);
            $libGroup->create_time = time();
            $libGroup->last_update_time = time();
            $libGroup->save();
            return json_encode(['err_code' => 0, 'err_msg' => '新建成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '新建失败']);
        }
    }

    /**
     * 删微博媒体库
     */
    public function actionDeleteGroup(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $group_uuid = $request->post('group_uuid');
            $libGroup = MediaCollectLibraryGroup::findOne(['uuid'=>$group_uuid]);
            $libGroup->status = MediaCollectLibraryGroup::STATUS_DELETED;
            $libGroup->save();
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功', 'redirect_url' => Url::to(['/ad-owner/admin-weibo-media-lib/list'])]);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败', 'redirect_url' => Url::to(['/ad-owner/admin-weibo-media-lib/list'])]);
        }
    }

     /**
     * 单个删除微博媒体库中的资源
     */
    public function actionRemoveMedia(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $media_uuid = $request->post('media_uuid');
            $item = MediaCollectLibraryGroupWeiboItem::findOne(['uuid'=>$media_uuid]);
            $mediaWeibo = MediaWeibo::findOne(['uuid'=>$item->weibo_media_uuid]);
            $weiboBind = WeiboVendorBind::findOne(['weibo_uuid'=>$item->weibo_media_uuid,'is_pref_vendor'=>1]);
            //媒体库变动
            $groupLib = MediaCollectLibraryGroup::findOne(['uuid'=>$item->group_uuid]);
            $groupLib->media_cnt = $groupLib->media_cnt -1;
            $groupLib->total_fan_cnt = $groupLib->total_fan_cnt - $mediaWeibo->follower_num;
            $extradata = json_decode($groupLib->extra_data)->total_micro_transfer_retail_price - ($weiboBind->micro_transfer_price_retail);
            $groupLib->extra_data = json_encode(['total_micro_transfer_retail_price'=>$extradata]);
            $groupLib->last_update_time = time();
            $groupLib->save();
            //媒体库资源删除
            MediaCollectLibraryGroupWeiboItem::deleteAll(['uuid'=>$media_uuid]);
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败']);
        }
    }



    /**
     * 批量删除微博媒体库中的资源
     */
    public function actionRemoveMediaBatch(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $delete_item_uuid_array = $request->post('delete_item_uuid');
            foreach($delete_item_uuid_array as $key=>$val){
                $item = MediaCollectLibraryGroupWeiboItem::findOne(['uuid'=>$val]);
                $mediaWeibo = MediaWeibo::findOne(['uuid'=>$item->weibo_media_uuid]);
                $weiboBind = WeiboVendorBind::findOne(['weibo_uuid'=>$item->weibo_media_uuid,'is_pref_vendor'=>1]);
                //媒体库变动
                $groupLib = MediaCollectLibraryGroup::findOne(['uuid'=>$item->group_uuid]);
                $groupLib->media_cnt = $groupLib->media_cnt -1;
                $groupLib->total_fan_cnt = $groupLib->total_fan_cnt - $mediaWeibo->follower_num;
                $extradata = json_decode($groupLib->extra_data)->total_micro_transfer_retail_price - ($weiboBind->micro_transfer_price_retail);
                $groupLib->extra_data = json_encode(['total_micro_transfer_retail_price'=>$extradata]);
                $groupLib->last_update_time = time();
                $groupLib->save();
                //媒体库资源删除
                MediaCollectLibraryGroupWeiboItem::deleteAll(['uuid'=>$val]);
            }
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败']);
        }
    }



    /**
     * 获取微博媒体库列表
     */
    public function actionGetAllLib(){
        $request = Yii::$app->request;
        $loginAccountInfo = $this->getLoginAccountInfo();
        $weiboMedialibList = MediaCollectLibraryGroup::find()
        ->where(['ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'cate' => $this->getWeiboPlatformCode(), 'status' => MediaCollectLibraryGroup::STATUS_OK])
        ->orderBy(['last_update_time' => SORT_DESC])
        ->asArray()
        ->limit(10)
        ->all();
        return json_encode(['err_code' => 0, 'weibo_media_lib_list' => $weiboMedialibList]);
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
            $item = MediaCollectLibraryGroupWeiboItem::findOne(['uuid'=>$item_uuid]);
            $mediaWeibo = MediaWeibo::findOne(['uuid'=>$item->weibo_media_uuid]);
            $weiboBind = WeiboVendorBind::findOne(['weibo_uuid'=>$item->weibo_media_uuid,'is_pref_vendor'=>1]);
            //媒体库变动
            $groupLib = MediaCollectLibraryGroup::findOne(['uuid'=>$group_uuid]);
            $groupLib->media_cnt = $groupLib->media_cnt -1;
            $groupLib->total_fan_cnt = $groupLib->total_fan_cnt - $mediaWeibo->follower_num;
            $extradata = json_decode($groupLib->extra_data)->total_micro_transfer_retail_price - ($weiboBind->micro_transfer_price_retail);
            $groupLib->extra_data = json_encode(['total_micro_transfer_retail_price'=>$extradata]);
            $groupLib->last_update_time = time();
            $groupLib->save();
            //媒体库资源删除
            MediaCollectLibraryGroupWeiboItem::deleteAll(['uuid'=>$item_uuid]);
            //将资源添加到其他媒体库
            foreach($group_uuid_array as $key=>$val){
                $group = MediaCollectLibraryGroup::findOne(['uuid'=>$val]);
                $group->media_cnt = $group->media_cnt + 1;
                $group->total_fan_cnt = $group->total_fan_cnt + $mediaWeibo->follower_num;


                var_dump($group->extra_data);
                die();
                $extradata = json_decode($group->extra_data)->total_micro_transfer_retail_price + ($weiboBind->micro_transfer_price_retail);
                $group->extra_data = json_encode(['total_micro_transfer_retail_price'=>$extradata]);
                $group->last_update_time = time();
                $group->save();

                $itemAdd = new MediaCollectLibraryGroupWeiboItem();
                $itemAdd->uuid = PlatformHelper::getUUID();
                $itemAdd->group_uuid = $val;
                $itemAdd->weibo_media_uuid = $mediaWeibo->uuid;
                $itemAdd->weibo_name = $mediaWeibo->weibo_name;
                $itemAdd->add_time = time();
                $itemAdd->save();
            }
            return json_encode(['err_code' => 0, 'err_msg' => '分组成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '分组失败']);
        }
    }



    /**
     * 微博立即预约生成plan和order
     */
    public function actionCreateWeiboPlan(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $create_item_uuid_array = $request->post('create_item_uuid');
            //新增微博plan
            $weiboPlan = new AdWeiboPlan();
            $weiboPlan->uuid = PlatformHelper::getUUID();
            $weiboPlan->ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
            $weiboPlan->create_time = time();
            $weiboPlan->update_time = time();
            $weiboPlan->save();
            $plan_uuid = $weiboPlan->uuid;
            foreach($create_item_uuid_array as $key=>$item_uuid) {
                $item = MediaCollectLibraryGroupWeiboItem::findOne(['uuid'=>$item_uuid]);
                $weibo_uuid = $item->weibo_media_uuid;
                $mediaWeibo = MediaWeibo::findOne(['uuid' => $weibo_uuid]); //微博资源信息
                $vendorBindInfo = WeiboVendorBind::findOne(['weibo_uuid' => $weibo_uuid, 'is_pref_vendor' => 1]);//供应商信息
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
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '保存失败']);
        }
    }


    /**
     * 获取微博媒体库资源列表
     */
    public function actionGetGroupItem(){
        $request = Yii::$app->request;
        $group_uuid = $request->get('group_uuid');
        $mediaList = MediaCollectLibraryGroupWeiboItem::find()
            ->select('weibo_media_uuid')
            ->where(['group_uuid'=>$group_uuid])
            ->asArray()
            ->all();
        return json_encode(['err_code' => 0, 'weibo_media_list' => $mediaList]);
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
                $objPHPExcel->getActiveSheet()->setCellValue('B1','微博昵称');
                $objPHPExcel->getActiveSheet()->setCellValue('C1','微博链接');
                $objPHPExcel->getActiveSheet()->setCellValue('D1','地域');
                $objPHPExcel->getActiveSheet()->setCellValue('E1','分类');
                $objPHPExcel->getActiveSheet()->setCellValue('F1','认证');
                $objPHPExcel->getActiveSheet()->setCellValue('G1','粉丝数');
                $objPHPExcel->getActiveSheet()->setCellValue('H1','微任务转发价');
                $objPHPExcel->getActiveSheet()->setCellValue('I1','微任务直发价');
                $objPHPExcel->getActiveSheet()->setCellValue('J1','软广转发价');
                $objPHPExcel->getActiveSheet()->setCellValue('K1','软广直发价');
                $objPHPExcel->getActiveSheet()->setCellValue('L1','价格有效期');
                $objPHPExcel->getActiveSheet()->setCellValue('M1','更新时间');
                $objPHPExcel->getActiveSheet()->setCellValue('N1','接单备注');
                if($account_info->is_public_account==0){//对内部导出
                    $objPHPExcel->getActiveSheet()->setCellValue('O1','供应商名称');
                }
                //微博资源
                $weixinItem = MediaCollectLibraryGroupWeiboItem::findAll(['group_uuid' => $groupUuid]);
                foreach($weixinItem as $key=>$item){
                    $weiboUuid = $item->weibo_media_uuid;
                    $mediaWeibo = MediaWeibo::findOne(['uuid'=>$weiboUuid]);
                    $vendorBind = WeiboVendorBind::findOne(['weibo_uuid'=>$weiboUuid,'is_pref_vendor'=>1]);
                    $mediaVendor = MediaVendor::findOne(['uuid'=>$vendorBind->vendor_uuid]);
                    //资源标签
                    $media_cate ="";
                    $mediaCate = MediaHelper::parseMediaCate($mediaWeibo->media_cate);
                    foreach(json_decode($mediaCate) as $cate){
                        $media_cate .= $cate."/";
                    }
                    $media_cate = substr($media_cate,0,-1);
                    //粉丝区域
                    $follower_area = "";
                    $followerArea = MediaHelper::parseCity($mediaWeibo->follower_area);
                    foreach(json_decode($followerArea) as $area){
                        $follower_area .= $area."/";
                    }
                    $follower_area = substr($follower_area,0,-1);
                    //认证
                    switch($mediaWeibo->media_level){
                        case 0:$media_level = "/";break;
                        case 1:$media_level = "蓝V";break;
                        case 2:$media_level = "黄V";break;
                        case 3:$media_level = "草根";break;
                        case 4:$media_level = "达人";break;
                        default:$media_level = "/";break;
                    }
                    //excel赋值
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($key+2), ($key+1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($key+2), ($mediaWeibo->weibo_name));
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($key+2), ($mediaWeibo->weibo_url));
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($key+2), ($follower_area));
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($key+2), ($media_cate));
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($key+2), ($media_level));
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($key+2), ($mediaWeibo->follower_num));
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($key+2), ($vendorBind->micro_transfer_price_retail));
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($key+2), ($vendorBind->micro_direct_price_retail));
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($key+2), ($vendorBind->soft_transfer_price_retail));
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($key+2), ($vendorBind->soft_direct_price_retail));
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($key+2), ((!empty($vendorBind->active_end_time))?date('Y-m-d',$vendorBind->active_end_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($key+2), ((!empty($mediaWeibo->update_time))?date('Y-m-d',$mediaWeibo->update_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($key+2), ($mediaWeibo->accept_remark));
                    if($account_info->is_public_account==0){//对内部导出
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . ($key+2), ($mediaVendor->name));
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
                $objPHPExcel->getActiveSheet()->setCellValue('B1','微博昵称');
                $objPHPExcel->getActiveSheet()->setCellValue('C1','微博链接');
                $objPHPExcel->getActiveSheet()->setCellValue('D1','地域');
                $objPHPExcel->getActiveSheet()->setCellValue('E1','分类');
                $objPHPExcel->getActiveSheet()->setCellValue('F1','认证');
                $objPHPExcel->getActiveSheet()->setCellValue('G1','粉丝数');
                $objPHPExcel->getActiveSheet()->setCellValue('H1','微任务转发价');
                $objPHPExcel->getActiveSheet()->setCellValue('I1','微任务直发价');
                $objPHPExcel->getActiveSheet()->setCellValue('J1','软广转发价');
                $objPHPExcel->getActiveSheet()->setCellValue('K1','软广直发价');
                $objPHPExcel->getActiveSheet()->setCellValue('L1','价格有效期');
                $objPHPExcel->getActiveSheet()->setCellValue('M1','更新时间');
                $objPHPExcel->getActiveSheet()->setCellValue('N1','接单备注');
                if($account_info->is_public_account==0){//对内部导出
                    $objPHPExcel->getActiveSheet()->setCellValue('O1','供应商名称');
                }
                foreach($itemUuidArray as $key=>$itemUuid){
                    $item = MediaCollectLibraryGroupWeiboItem::findOne(['uuid'=>$itemUuid]);
                    $weiboUuid = $item->weibo_media_uuid;
                    $mediaWeibo = MediaWeibo::findOne(['uuid'=>$weiboUuid]);
                    $vendorBind = WeiboVendorBind::findOne(['weibo_uuid'=>$weiboUuid,'is_pref_vendor'=>1]);
                    $mediaVendor = MediaVendor::findOne(['uuid'=>$vendorBind->vendor_uuid]);
                    //资源标签
                    $media_cate ="";
                    $mediaCate = MediaHelper::parseMediaCate($mediaWeibo->media_cate);
                    foreach(json_decode($mediaCate) as $cate){
                        $media_cate .= $cate."/";
                    }
                    $media_cate = substr($media_cate,0,-1);
                    //粉丝区域
                    $follower_area = "";
                    $followerArea = MediaHelper::parseCity($mediaWeibo->follower_area);
                    foreach(json_decode($followerArea) as $area){
                        $follower_area .= $area."/";
                    }
                    $follower_area = substr($follower_area,0,-1);
                    //认证
                    switch($mediaWeibo->media_level){
                        case 0:$media_level = "/";break;
                        case 1:$media_level = "蓝V";break;
                        case 2:$media_level = "黄V";break;
                        case 3:$media_level = "草根";break;
                        case 4:$media_level = "达人";break;
                        default:$media_level = "/";break;
                    }
                    //excel赋值
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($key+2), ($key+1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($key+2), ($mediaWeibo->weibo_name));
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($key+2), ($mediaWeibo->weibo_url));
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($key+2), ($follower_area));
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($key+2), ($media_cate));
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($key+2), ($media_level));
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($key+2), ($mediaWeibo->follower_num));
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($key+2), ($vendorBind->micro_transfer_price_retail));
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($key+2), ($vendorBind->micro_direct_price_retail));
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($key+2), ($vendorBind->soft_transfer_price_retail));
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($key+2), ($vendorBind->soft_direct_price_retail));
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($key+2), ((!empty($vendorBind->active_end_time))?date('Y-m-d',$vendorBind->active_end_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($key+2), ((!empty($mediaWeibo->update_time))?date('Y-m-d',$mediaWeibo->update_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($key+2), ($mediaWeibo->accept_remark));
                    if($account_info->is_public_account==0){//对内部导出
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . ($key+2), ($mediaVendor->name));
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
