<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/8/16 4:31 PM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\helpers\MediaHelper;
use common\models\AdOwner;
use common\models\MediaCollectLibraryGroup;
use common\models\MediaCollectLibraryGroupWeixinItem;
use common\models\MediaVendor;
use common\models\MediaVendorBind;
use common\models\MediaWeixin;
use common\models\UserAccount;
use frontend\helpers\SiteHelper;
use wom\controllers\BaseAppController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Response;
use PHPExcel;
use PHPExcel_Writer_Excel2007;
//use PHPExcel_Writer_Excel5;

/**
 * 广告主个人中心/微信媒体库管理
 * Class AdminWeixinMediaLibController
 * @package wom\modules\adOwner\controllers\AdminController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminWeixinMediaLibController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 媒体库分组管理
     * @return string
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        $pageNum = 0;
        if ($request->isPost) {
            $libName = $request->post('lib-name', ''); // 微信媒体库名称
            $weixinName = $request->post('weixin-name', ''); // 公众号名称 (名称或者id)
            $pageNum = $request->post('page', 0);
        }

        $loginAccountInfo = $this->getLoginAccountInfo();

        // 构造查询的query
        $query = (new Query())
            ->select([
                'library_group.uuid AS lib_uuid',
                'library_group.group_name',
                'library_group.media_cnt',
                'library_group.total_fan_cnt',
                'library_group.extra_data',
                'library_group.last_update_time'
            ])
            ->distinct()
            ->from(['library_group' => MediaCollectLibraryGroup::tableName()])
            ->where(['library_group.ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'library_group.cate' => MediaCollectLibraryGroup::CATE_WEIXIN, 'library_group.status' => MediaCollectLibraryGroup::STATUS_OK])
            ->orderBy(['last_update_time' => SORT_DESC]);

        if (!empty($libName)) {
            $query->andWhere(['like', 'library_group.group_name', $libName]);
        }
        if (!empty($weixinName)) {
            $query->leftJoin(['library_group_item' => MediaCollectLibraryGroupWeixinItem::tableName()], 'library_group_item.group_uuid = library_group.uuid')
                ->andWhere(['or', ['like', 'library_group_item.weixin_name', $weixinName], ['like', 'library_group_item.weixin_media_uuid', $weixinName]]);
        }
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 10
//            ]
//        ]);
//
//        return $this->render('list', [
//            'dataProvider' => $dataProvider,
//        ]);

        $pager = new Pagination(['totalCount' => $query->count()]);
        $pager->pageSize = 10;
        $pager->page = $pageNum;
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
     * 新建媒体库
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $libName = $request->post("media_lib_name");
            $loginAccountInfo = $this->getLoginAccountInfo();
            $group = new MediaCollectLibraryGroup();
            $group->ad_owner_uuid = $loginAccountInfo['ad-owner-uuid'];
            $group->group_name = $libName;
            $group->cate = MediaCollectLibraryGroup::CATE_WEIXIN;
            $group->extra_data = json_encode(['total_m_1_retail_price'=>0,'total_m_1_avg_read_num'=>0]);
            if ($group->save()) {
                return ['err_code' => 0, 'err_msg' => '新建成功', 'redirect_url' => Url::to(['/ad-owner/admin-weixin-media-lib/list'])];
            } else {
                return ['err_code' => 1, 'err_msg' => '新建失败'];
            }
        }
    }

    /**
     * 媒体库资源管理
     */
    public function actionDetail()
    {
        $request = Yii::$app->request;
        $libUUID = $request->get('lib_uuid');

        $weixinMediaLib = MediaCollectLibraryGroup::find()
            ->where(['uuid' => $libUUID, 'cate' => MediaCollectLibraryGroup::CATE_WEIXIN])
            ->one();

        $query = (new Query())
            ->select([
                'lib_item.group_uuid AS lib_uuid',
                'lib_item.uuid AS item_uuid',
                'weixin.uuid AS media_uuid',
                'weixin.public_name',
                'weixin.public_id',
                'weixin.real_public_id',
                'weixin.follower_num',
                'weixin.pub_config',
                'weixin.m_head_avg_view_cnt AS head_avg_view_cnt',
                'weixin.m_wmi AS wmi',
                'weixin.put_up',
                'weixin.active_end_time'
            ])
            ->from(['lib_item' => MediaCollectLibraryGroupWeixinItem::tableName()])
            ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = lib_item.weixin_media_uuid')
            ->where(['lib_item.group_uuid' => $libUUID]);

        // 默认排序
        $query->orderBy(['lib_item.add_time' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        // 获取该媒体库里的账号uuid,以','分隔
        $mediaItemList = $query->all();
        $mediaUUIDList = '';
        foreach ($mediaItemList as $mediaItem) {
            $mediaUUIDList .= $mediaItem['media_uuid'] . ',';
        }

        return $this->render('detail', [
            'dataProvider' => $dataProvider,
            'weixinMediaLib' => $weixinMediaLib,
            'mediaUUIDList' => $mediaUUIDList
        ]);
    }

    /**
     * 删除媒体库
     */
    public function actionDelete()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $libUUID = $request->get("lib_uuid");

            $effectRow = MediaCollectLibraryGroup::updateAll(['status' => MediaCollectLibraryGroup::STATUS_DELETED], ['uuid' => $libUUID]);
            if ($effectRow >= 1) {
                return ['err_code' => 0, 'err_msg' => '删除成功', 'redirect_url' => Url::to(['/ad-owner/admin-weixin-media-lib/list'])];
            } else {
                return ['err_code' => 1, 'err_msg' => '删除失败'];
            }
        }
    }

    /**
     * 将资源从媒体库中删除
     */
    public function actionDeleteMedia()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $libUUID = $request->post("lib_uuid");
            $selectedMediaStrWithComma = $request->post("selected_media");
            $selectedMediaList = explode(',', $selectedMediaStrWithComma);
            $selectedMediaList = array_filter($selectedMediaList);

            foreach ($selectedMediaList as $key=>$val){
                // 待删的媒体库资源
                $libItemList = (new Query())
                    ->select([
                        'weixin.follower_num',
                        'weixin.retail_price_m_1_min',
                        'weixin.m_head_avg_view_cnt',
                    ])
                    ->from(['lib_item' => MediaCollectLibraryGroupWeixinItem::tableName()])
                    ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = lib_item.weixin_media_uuid')
                    ->where(['lib_item.group_uuid' => $libUUID, 'lib_item.weixin_media_uuid' => trim($val)])
                    ->all();
                // 媒体库
                $groupLib = MediaCollectLibraryGroup::find()->where(['uuid' => $libUUID])->one();

                $extraData = json_decode($groupLib->extra_data, true);
                $totalM1RetailPrice = $extraData['total_m_1_retail_price'];
                $totalM1AvgReadNum = $extraData['total_m_1_avg_read_num'];

                $mediaCountToDelete = 0;
                $totalFollowerCount = 0;
                foreach($libItemList as $item){
                    $mediaCountToDelete++;
                    $totalFollowerCount += $item['follower_num'];
                    $totalM1RetailPrice = $totalM1RetailPrice - $item['retail_price_m_1_min'];
                    $totalM1AvgReadNum = $totalM1AvgReadNum - $item['m_head_avg_view_cnt'];
                }
                $groupLib->media_cnt = $groupLib->media_cnt - $mediaCountToDelete;
                $groupLib->total_fan_cnt = $groupLib->total_fan_cnt - $totalFollowerCount;
                $groupLib->extra_data = json_encode(['total_m_1_retail_price' => $totalM1RetailPrice, 'total_m_1_avg_read_num' => $totalM1AvgReadNum]);
                $groupLib->save();

                // 删除媒体库资源
                MediaCollectLibraryGroupWeixinItem::deleteAll(['group_uuid' => $libUUID, 'weixin_media_uuid' => $val]);

            }

            return ['err_code' => 0, 'err_msg' => '删除成功'];
        }else{
            return ['err_code' => 1, 'err_msg' => '删除失败'];
        }
    }

    /**
     * 获取微信媒体库列表
     */
    public function actionGetAll()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isPost) {
            $mediaLibName = $request->post('media-lib-name');
        }
        $loginAccountInfo = $this->getLoginAccountInfo();
        if (empty($mediaLibName)) {
            $weixinMedialibList = MediaCollectLibraryGroup::find()
                ->where(['ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'cate' => $this->getWeixinPlatformCode(), 'status' => MediaCollectLibraryGroup::STATUS_OK])
                ->orderBy(['last_update_time' => SORT_DESC])
                ->limit(10)
                ->all();
        } else {
            $weixinMedialibList = MediaCollectLibraryGroup::find()
                ->where(['ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'cate' => $this->getWeixinPlatformCode(), 'status' => MediaCollectLibraryGroup::STATUS_OK])
                ->andWhere(['like', 'group_name', $mediaLibName])
                ->orderBy(['last_update_time' => SORT_DESC])
                ->limit(10)
                ->all();
        }
        return ['err_code' => 0, 'weixin_media_lib_list' => $weixinMedialibList];
    }

    /**
     * 重新分组
     * TODO
     */
    public function actionRegroup()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $item_uuid = $request->post('item_uuid');
            $group_uuid = $request->post('group_uuid');//
            $group_uuid_array = $request->post('group_uuid_arr'); //需要新添的媒体库
            $item = MediaCollectLibraryGroupWeixinItem::findOne(['uuid'=>$item_uuid]);
            $mediaWeixin = MediaWeixin::findOne(['uuid'=>$item->weixin_media_uuid]);
            //媒体库变动
            $groupLib = MediaCollectLibraryGroup::findOne(['uuid'=>$group_uuid]);
            $groupLib->media_cnt = $groupLib->media_cnt -1;
            $groupLib->total_fan_cnt = $groupLib->total_fan_cnt - $mediaWeixin->follower_num;
            $total_price = json_decode($groupLib->extra_data)->total_m_1_retail_price - ($mediaWeixin->retail_price_m_1_min);
            $total_read_num = json_decode($groupLib->extra_data)->total_m_1_avg_read_num - ($mediaWeixin->m_head_avg_view_cnt);
            $groupLib->extra_data = json_encode(['total_m_1_retail_price'=>$total_price,'total_m_1_avg_read_num'=>$total_read_num]);
            $groupLib->last_update_time = time();
            $groupLib->save();
            //媒体库资源删除
            MediaCollectLibraryGroupWeixinItem::deleteAll(['uuid'=>$item_uuid]);
            //将资源添加到其他媒体库
            foreach($group_uuid_array as $key=>$val){
                $group = MediaCollectLibraryGroup::findOne(['uuid'=>$val]);
                $group->media_cnt = $group->media_cnt +1;
                $group->total_fan_cnt = $group->total_fan_cnt + $mediaWeixin->follower_num;
                $total_price = json_decode($group->extra_data)->total_m_1_retail_price + ($mediaWeixin->retail_price_m_1_min);
                $total_read_num = json_decode($group->extra_data)->total_m_1_avg_read_num + ($mediaWeixin->m_head_avg_view_cnt);
                $group->extra_data = json_encode(['total_m_1_retail_price'=>$total_price,'total_m_1_avg_read_num'=>$total_read_num]);
                $group->last_update_time = time();
                $group->save();

                $itemAdd = new MediaCollectLibraryGroupWeixinItem();
                $itemAdd->uuid = PlatformHelper::getUUID();
                $itemAdd->group_uuid = $val;
                $itemAdd->weixin_media_uuid = $mediaWeixin->uuid;
                $itemAdd->weixin_name = $mediaWeixin->public_name;
                $itemAdd->add_time = time();
                $itemAdd->save();
            }
            return ['err_code' => 0, 'err_msg' => '重新分组成功'];
        }else{
            return ['err_code' => 1, 'err_msg' => '重新分组失败'];
        }
    }

    /**
     * 获得微信媒体库资源列表的查询query
     * @return mixed
     */
    private function getWeixinMediaLibDetailQuery()
    {
        $request = Yii::$app->request;
        $group_uuid = $request->get('uuid');
        if ($request->isPost) {
            $libName = $request->post('lib-name');// 微信媒体库名称
            $weixin = $request->post('weixin'); // 微信账号(名称或者id)
        }

        // 构造查询的query
        $query = (new Query())
            ->select([
                'library_media.uuid',
                'library_media.weixin_media_uuid',
                'media.public_name',
                'media.public_id',
                'media.follower_num',
                'media.pub_config',
                'media.m_head_avg_view_cnt as head_avg_view_cnt',
                'media.m_wmi as wmi',
                'media.put_up',
                'media.active_end_time'
            ])
            ->from(['library_media' => MediaCollectLibraryGroupWeixinItem::tableName()])
            ->where(['library_media.group_uuid' => $group_uuid]);
        $query->leftJoin([
            'media' => MediaWeixin::tableName()],
            'media.uuid = library_media.weixin_media_uuid');
        // 默认排序
        $query->orderBy(['library_media.add_time' => SORT_DESC]);
        if (isset($libName)) {
            $query->andWhere();
        }
        if (isset($weixin)) {
            $query->andWhere();
        }

        return $query;
    }

    /**
     * 获取视频媒体库资源列表
     */
    public function actionGetGroupItem(){
        $request = Yii::$app->request;
        $group_uuid = $request->get('group_uuid');
        $mediaList = MediaCollectLibraryGroupWeixinItem::find()
            ->select('weixin_media_uuid')
            ->where(['group_uuid'=>$group_uuid])
            ->asArray()
            ->all();
        return json_encode(['err_code' => 0, 'weixin_media_list' => $mediaList]);
    }

    //导出媒体库里的全部资源
    public function actionExportLibMedia(){
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isGet){
            $groupUuid = $request->get("lib_uuid");
            if(!empty($groupUuid)){
                //账户信息
                $loginAccountInfo = $this->getLoginAccountInfo();
                $account_info = UserAccount::findOne(["uuid"=>$loginAccountInfo['account-uuid']]);//账户信息
                //Excel导出
                $objPHPExcel = new PHPExcel();
                $fileName = PlatformHelper::getUUID().'.xlsx';
                $objPHPExcel->getActiveSheet()->setCellValue('A1','序号');
                $objPHPExcel->getActiveSheet()->setCellValue('B1','微信名称');
                $objPHPExcel->getActiveSheet()->setCellValue('C1','微信账号');
                $objPHPExcel->getActiveSheet()->setCellValue('D1','认证状态');
                $objPHPExcel->getActiveSheet()->setCellValue('E1','粉丝数');
                $objPHPExcel->getActiveSheet()->setCellValue('F1','地域');
                $objPHPExcel->getActiveSheet()->setCellValue('G1','分类');
                $objPHPExcel->getActiveSheet()->setCellValue('H1','头条平均阅读数');
                $objPHPExcel->getActiveSheet()->setCellValue('I1','单图文价格');
                $objPHPExcel->getActiveSheet()->setCellValue('J1','多图文头条');
                $objPHPExcel->getActiveSheet()->setCellValue('K1','多图文2');
                $objPHPExcel->getActiveSheet()->setCellValue('L1','多图文3');
                $objPHPExcel->getActiveSheet()->setCellValue('M1','价格有效期');
                $objPHPExcel->getActiveSheet()->setCellValue('N1','更新时间');
                $objPHPExcel->getActiveSheet()->setCellValue('O1','简介');
                $objPHPExcel->getActiveSheet()->setCellValue('P1','备注');
                if($account_info->is_public_account==0){//对内部导出
                    $objPHPExcel->getActiveSheet()->setCellValue('Q1','供应商名称');
                }
                //微信资源
                $weixinItem = MediaCollectLibraryGroupWeixinItem::findAll(['group_uuid' => trim($groupUuid)]);
                foreach($weixinItem as $key=>$item){
                    $weixinUuid = $item->weixin_media_uuid;
                    $mediaWeixin = MediaWeixin::findOne(['uuid'=>$weixinUuid]);
                    $vendorBind = MediaVendorBind::findOne(['media_uuid'=>$weixinUuid,'is_pref_vendor'=>1]);
                    $mediaVendor = MediaVendor::findOne(['uuid'=>$vendorBind->vendor_uuid]);
                    switch ($mediaWeixin->account_cert){
                        case 0: $cert = "未知";break;
                        case 1: $cert = "认证";break;
                        case 2: $cert = "未认证";break;
                        default: $cert = "未知";break;
                    }
                    //资源标签
                    $media_cate ="";
                    $mediaCate = MediaHelper::parseMediaCate($mediaWeixin->media_cate);
                    foreach(json_decode($mediaCate) as $cate){
                        $media_cate .= $cate."/";
                    }
                    $media_cate = substr($media_cate,0,-1);
                    //粉丝区域
                    $follower_area = "";
                    $followerArea = MediaHelper::parseCity($mediaWeixin->follower_area);
                    foreach(json_decode($followerArea) as $area){
                        $follower_area .= $area."/";
                    }
                    $follower_area = substr($follower_area,0,-1);
                    $priceDetail = MediaHelper::parseMediaWeixinRetailPrice($mediaWeixin->pub_config);
                    //Excel赋值
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($key+2), ($key+1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($key+2), ($mediaWeixin->public_name));
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($key+2), ($mediaWeixin->public_id));
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($key+2), ($cert));
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($key+2), ($mediaWeixin->follower_num));
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($key+2), ($follower_area));
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($key+2), ($media_cate));
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($key+2), (($mediaWeixin->m_head_avg_view_cnt==-1)?0:$mediaWeixin->m_head_avg_view_cnt));
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($key+2), ($priceDetail['s']['price_label']));
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($key+2), ($priceDetail['m_1']['price_label']));
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($key+2), ($priceDetail['m_2']['price_label']));
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($key+2), ($priceDetail['m_3']['price_label']));
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($key+2), ((!empty($mediaWeixin->active_end_time))?date('Y-m-d',$mediaWeixin->active_end_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($key+2), ((!empty($mediaWeixin->last_update_time))?date('Y-m-d',$mediaWeixin->last_update_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . ($key+2), ($mediaWeixin->account_short_desc));
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . ($key+2), ($mediaWeixin->comment));
                    if($account_info->is_public_account==0){//对内部导出
                        $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($key+2), ($mediaVendor->name));
                    }
                }
                //保存excel—2007格式
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($fileName);
                return ['err_code'=>0,'filename'=>$fileName];
            }else{
                return $this->redirect('index.php?r=ad-owner/admin-weixin-media-lib/list');
            }
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
                $objPHPExcel->getActiveSheet()->setCellValue('B1','微信名称');
                $objPHPExcel->getActiveSheet()->setCellValue('C1','微信账号');
                $objPHPExcel->getActiveSheet()->setCellValue('D1','认证状态');
                $objPHPExcel->getActiveSheet()->setCellValue('E1','粉丝数');
                $objPHPExcel->getActiveSheet()->setCellValue('F1','地域');
                $objPHPExcel->getActiveSheet()->setCellValue('G1','分类');
                $objPHPExcel->getActiveSheet()->setCellValue('H1','头条平均阅读数');
                $objPHPExcel->getActiveSheet()->setCellValue('I1','单图文价格');
                $objPHPExcel->getActiveSheet()->setCellValue('J1','多图文头条');
                $objPHPExcel->getActiveSheet()->setCellValue('K1','多图文2');
                $objPHPExcel->getActiveSheet()->setCellValue('L1','多图文3');
                $objPHPExcel->getActiveSheet()->setCellValue('M1','价格有效期');
                $objPHPExcel->getActiveSheet()->setCellValue('N1','更新时间');
                $objPHPExcel->getActiveSheet()->setCellValue('O1','简介');
                $objPHPExcel->getActiveSheet()->setCellValue('P1','备注');
                if($account_info->is_public_account==0){//对内部导出
                    $objPHPExcel->getActiveSheet()->setCellValue('Q1','供应商名称');
                }
                foreach($itemUuidArray as $key=>$itemUuid){

                    $item = MediaCollectLibraryGroupWeixinItem::findOne(['uuid'=>trim($itemUuid)]);
                    $weixinUuid = $item->weixin_media_uuid;
                    $mediaWeixin = MediaWeixin::findOne(['uuid'=>$weixinUuid]);
                    $vendorBind = MediaVendorBind::findOne(['media_uuid'=>$weixinUuid,'is_pref_vendor'=>1]);
                    $mediaVendor = MediaVendor::findOne(['uuid'=>$vendorBind->vendor_uuid]);
                    switch ($mediaWeixin->account_cert){
                        case 0: $cert = "未知";break;
                        case 1: $cert = "认证";break;
                        case 2: $cert = "未认证";break;
                        default: $cert = "未知";break;
                    }
                    //资源标签
                    $media_cate ="";
                    $mediaCate = MediaHelper::parseMediaCate($mediaWeixin->media_cate);
                    foreach(json_decode($mediaCate) as $cate){
                        $media_cate .= $cate."/";
                    }
                    $media_cate = substr($media_cate,0,-1);
                    //粉丝区域
                    $follower_area = "";
                    $followerArea = MediaHelper::parseCity($mediaWeixin->follower_area);
                    foreach(json_decode($followerArea) as $area){
                        $follower_area .= $area."/";
                    }
                    $follower_area = substr($follower_area,0,-1);
                    $priceDetail = MediaHelper::parseMediaWeixinRetailPrice($mediaWeixin->pub_config);

                    //Excel赋值
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($key+2), ($key+1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($key+2), ($mediaWeixin->public_name));
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($key+2), ($mediaWeixin->public_id));
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($key+2), ($cert));
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($key+2), ($mediaWeixin->follower_num));
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($key+2), ($follower_area));
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($key+2), ($media_cate));
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($key+2), (($mediaWeixin->m_head_avg_view_cnt==-1)?0:$mediaWeixin->m_head_avg_view_cnt));
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($key+2), ($priceDetail['s']['price_label']));
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($key+2), ($priceDetail['m_1']['price_label']));
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($key+2), ($priceDetail['m_2']['price_label']));
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($key+2), ($priceDetail['m_3']['price_label']));
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($key+2), ((!empty($mediaWeixin->active_end_time))?date('Y-m-d',$mediaWeixin->active_end_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($key+2), ((!empty($mediaWeixin->last_update_time))?date('Y-m-d',$mediaWeixin->last_update_time):"/"));
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . ($key+2), ($mediaWeixin->account_short_desc));
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . ($key+2), ($mediaWeixin->comment));
                    if($account_info->is_public_account==0){//对内部导出
                        $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($key+2), ($mediaVendor->name));
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
