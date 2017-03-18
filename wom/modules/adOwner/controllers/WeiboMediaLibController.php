<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 9/23/16 10:32 AM
 */
namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\models\AdOwner;
use common\models\MediaCollectLibraryGroup;
use common\models\MediaCollectLibraryGroupWeiboItem;
use common\models\MediaWeibo;
use common\models\WeiboVendorBind;
use frontend\helpers\SiteHelper;
use wom\controllers\BaseAppController;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Response;
use yii;

/**
 * 微信媒体库
 * Class WeiboMediaLibController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class WeiboMediaLibController extends AdOwnerBaseAppController
{

    /**
     * 往微博媒体库中添加媒体资源
     */
    public function actionAddMediaToLib(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaLibUUID = $request->post('media_lib_uuid'); // 媒体库uuid
            $mediaLibName = $request->post('media_lib_name'); // 媒体库名称
            $selectedMediaUUIDList = explode(',', $request->post('selected_media_uuid_list')); // 选择的资源(array())
            if (isset($mediaLibUUID) && $mediaLibUUID != -1) {// 添加资源到已有的媒体库
                $libGroup = MediaCollectLibraryGroup::findOne(['uuid' => $mediaLibUUID, 'cate' => MediaCollectLibraryGroup::CATE_WEIBO]);
                // 已经加入媒体库的资源
                $weiboLibItemList = (new Query())
                ->select(['item.weibo_media_uuid',])
                ->from(['item' => MediaCollectLibraryGroupWeiboItem::tableName()])
                ->where(['item.group_uuid' => $mediaLibUUID])
                ->all();
                $weiboLibItemUUIDList = [];
                foreach ($weiboLibItemList as $weiboLibItem) {
                    $weiboLibItemUUIDList[$weiboLibItem['weibo_media_uuid']] = $weiboLibItem;
                }

                $extraDataArray = json_decode($libGroup->extra_data, true);
                $media_cnt = $libGroup->media_cnt;
                $total_fan_cnt = $libGroup->total_fan_cnt;
                foreach (array_filter($selectedMediaUUIDList) as $mediaUUID) {
                    if (array_key_exists($mediaUUID, $weiboLibItemUUIDList)) {//避免重复添加某个账号到媒体库
                        continue;
                    }
                    $mediaWeibo = MediaWeibo::findOne(['uuid'=>$mediaUUID]);
                    $weiboVendorBind = WeiboVendorBind::findOne(['weibo_uuid'=>$mediaUUID,'is_pref_vendor'=>1]);
                    $weiboLibItem = new MediaCollectLibraryGroupWeiboItem();
                    $weiboLibItem->uuid = PlatformHelper::getUUID();
                    $weiboLibItem->group_uuid = $mediaLibUUID;
                    $weiboLibItem->weibo_media_uuid = $mediaUUID;
                    $weiboLibItem->weibo_name = $mediaWeibo->weibo_name;
                    $weiboLibItem->add_time = time();
                    $weiboLibItem->save();
                    $media_cnt = $media_cnt + 1;
                    $total_fan_cnt = $total_fan_cnt + $mediaWeibo->follower_num;
                    $extraDataArray['total_micro_transfer_retail_price'] =  $extraDataArray['total_micro_transfer_retail_price']+$weiboVendorBind->micro_transfer_price_retail;
                }
                $libGroup->media_cnt = $media_cnt;
                $libGroup->total_fan_cnt = $total_fan_cnt;
                $libGroup->extra_data = json_encode($extraDataArray);
                $libGroup->save();
                return ['err_code' => 0, 'err_msg' => '添加成功', 'redirect_url' => Url::to(['/ad-owner/admin-weibo-media-lib/detail'])."&group_uuid=".$mediaLibUUID];
            } else { // 新增媒体库并添加资源到该媒体库
                $loginAccountInfo = $this->getLoginAccountInfo(); // 登录账号
                //新建媒体库
                $weiboLibGroupUuid = PlatformHelper::getUUID();
                $weiboMediaLib = new MediaCollectLibraryGroup();
                $weiboMediaLib->uuid = $weiboLibGroupUuid;
                $weiboMediaLib->group_name = $mediaLibName;
                $weiboMediaLib->ad_owner_uuid = $loginAccountInfo['ad-owner-uuid'];
                $weiboMediaLib->cate = MediaCollectLibraryGroup::CATE_WEIBO;
                $weiboMediaLib->media_cnt = count(array_filter($selectedMediaUUIDList));
                $weiboMediaLib->create_time = time();
                $weiboMediaLib->last_update_time = time();
                //$weiboMediaLib->save();
                $total_fan_cnt = 0;
                $total_mt_retail_price = 0;
                foreach(array_filter($selectedMediaUUIDList) as $key => $val){
                    $mediaWeibo = MediaWeibo::findOne(['uuid'=>$val]);
                    $weiboVendorBind = WeiboVendorBind::findOne(['weibo_uuid'=>$val,'is_pref_vendor'=>1]);
                    $total_fan_cnt = $total_fan_cnt + $mediaWeibo->follower_num;
                    $total_mt_retail_price = $total_mt_retail_price + $weiboVendorBind->micro_transfer_price_retail;
                    // 添加weibo_item
                    $weiboLibItem = new MediaCollectLibraryGroupWeiboItem();
                    $weiboLibItem->uuid =  PlatformHelper::getUUID();
                    $weiboLibItem->group_uuid = $weiboLibGroupUuid;
                    $weiboLibItem->weibo_media_uuid = $mediaWeibo->uuid;
                    $weiboLibItem->weibo_name = $mediaWeibo->weibo_name;
                    $weiboLibItem->add_time = time();
                    $weiboLibItem->save();
                }
                $weiboMediaLib->total_fan_cnt = $total_fan_cnt;
                $weiboMediaLib->extra_data = json_encode(['total_micro_transfer_retail_price' => $total_mt_retail_price]);
                $weiboMediaLib->save();
                return ['err_code' => 0, 'err_msg' => '添加成功', 'redirect_url' =>  Url::to(['/ad-owner/admin-weibo-media-lib/detail'])."&group_uuid=".$weiboLibGroupUuid];
            }
        }
    }

    /**
     * 往已存在的媒体库中添加媒体资源
     */
    public function actionAddMediaToExistLib(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaLibUUID = $request->post('media_lib_uuid'); // 媒体库uuid
            $selectedMediaUUIDList = $request->post('selected_media_uuid_list');
            if(!is_array($selectedMediaUUIDList)){
                $selectedMediaUUIDList = explode(',', $request->post('selected_media_uuid_list')); // 选择的资源(array())
            }
            if (isset($mediaLibUUID) && $mediaLibUUID != -1) {// 添加资源到已有的媒体库
                $libGroup = MediaCollectLibraryGroup::findOne(['uuid' => $mediaLibUUID, 'cate' => MediaCollectLibraryGroup::CATE_WEIBO]);
                // 已经加入媒体库的资源
                $weiboLibItemList = (new Query())
                    ->select(['item.weibo_media_uuid',])
                    ->from(['item' => MediaCollectLibraryGroupWeiboItem::tableName()])
                    ->where(['item.group_uuid' => $mediaLibUUID])
                    ->all();
                $weiboLibItemUUIDList = [];
                foreach ($weiboLibItemList as $weiboLibItem) {
                    $weiboLibItemUUIDList[$weiboLibItem['weibo_media_uuid']] = $weiboLibItem;
                }

                $extraDataArray = json_decode($libGroup->extra_data, true);
                $media_cnt = $libGroup->media_cnt;
                $total_fan_cnt = $libGroup->total_fan_cnt;
                foreach (array_filter($selectedMediaUUIDList) as $mediaUUID) {
                    if (array_key_exists($mediaUUID, $weiboLibItemUUIDList)) {//避免重复添加某个账号到媒体库
                        continue;
                    }
                    $mediaWeibo = MediaWeibo::findOne(['uuid'=>$mediaUUID]);
                    $weiboVendorBind = WeiboVendorBind::findOne(['weibo_uuid'=>$mediaUUID,'is_pref_vendor'=>1]);
                    $weiboLibItem = new MediaCollectLibraryGroupWeiboItem();
                    $weiboLibItem->uuid = PlatformHelper::getUUID();
                    $weiboLibItem->group_uuid = $mediaLibUUID;
                    $weiboLibItem->weibo_media_uuid = $mediaUUID;
                    $weiboLibItem->weibo_name = $mediaWeibo->weibo_name;
                    $weiboLibItem->add_time = time();
                    $weiboLibItem->save();
                    $media_cnt = $media_cnt + 1;
                    $total_fan_cnt = $total_fan_cnt + $mediaWeibo->follower_num;
                    $extraDataArray['total_micro_transfer_retail_price'] =  $extraDataArray['total_micro_transfer_retail_price']+$weiboVendorBind->micro_transfer_price_retail;
                }
                $libGroup->media_cnt = $media_cnt;
                $libGroup->total_fan_cnt = $total_fan_cnt;
                $libGroup->extra_data = json_encode($extraDataArray);
                $libGroup->save();
                return ['err_code' => 0, 'redirect_url' => Url::to(['/ad-owner/admin-weibo-media-lib/detail'])."&group_uuid=".$mediaLibUUID];
            }
        }
    }


    /**
     * 获取微博媒体库列表
     */
    public function actionGetMediaLibList(){
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isPost){
            $mediaLibName = $request->post('media_lib_name');
        }
        $loginAccountInfo = $this->getLoginAccountInfo();
        if(empty($mediaLibName)){
            $weiboMedialibList = MediaCollectLibraryGroup::find()
                ->where(['ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'cate' => $this->getWeiboPlatformCode(), 'status' => MediaCollectLibraryGroup::STATUS_OK])
                ->orderBy(['last_update_time' => SORT_DESC])
                ->limit(10)
                ->all();
        } else {
            $weiboMedialibList = MediaCollectLibraryGroup::find()
                ->where(['ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'cate' => $this->getWeiboPlatformCode(), 'status' => MediaCollectLibraryGroup::STATUS_OK])
                ->andWhere(['like', 'group_name', $mediaLibName])
                ->orderBy(['last_update_time' => SORT_DESC])
                ->limit(10)
                ->all();
        }
        return ['err_code' => 0, 'weibo_media_lib_list' => $weiboMedialibList];
    }

}