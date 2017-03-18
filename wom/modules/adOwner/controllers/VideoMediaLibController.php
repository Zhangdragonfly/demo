<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 9/23/16 10:32 AM
 */
namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\models\AdOwner;
use common\models\MediaCollectLibraryGroup;
use common\models\MediaCollectLibraryGroupVideoItem;
use common\models\VideoPlatformCommonInfo;
use common\models\VideoVendorPrice;
use frontend\helpers\SiteHelper;
use wom\controllers\BaseAppController;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\db\Query;
use yii\web\Response;
use yii;

/**
 * 视频媒体库
 * Class VideoMediaLibController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class VideoMediaLibController extends BaseAppController
{

    /**
     * 往视频媒体库中添加媒体资源
     */
    public function actionAddMediaToLib(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaLibUUID = $request->post('media_lib_uuid'); // 媒体库uuid
            $mediaLibName = $request->post('media_lib_name'); // 媒体库名称
            $selectedMediaUUIDList = explode(',', $request->post('selected_media_uuid_list')); // 选择的资源(array())
            if (isset($mediaLibUUID) && $mediaLibUUID != -1) {// 添加资源到已有的媒体库
                $libGroup = MediaCollectLibraryGroup::findOne(['uuid' => $mediaLibUUID, 'cate' => MediaCollectLibraryGroup::CATE_VIDEO]);
                // 已经加入媒体库的资源
                $videoLibItemList = (new Query())
                    ->select(['item.video_media_uuid',])
                    ->from(['item' => MediaCollectLibraryGroupVideoItem::tableName()])
                    ->where(['item.group_uuid' => $mediaLibUUID])
                    ->all();
                $videoLibItemUUIDList = [];
                foreach ($videoLibItemList as $videoLibItem) {
                    $videoLibItemUUIDList[$videoLibItem['video_media_uuid']] = $videoLibItem;
                }

                $extraDataArray = json_decode($libGroup->extra_data, true);
                $media_cnt = $libGroup->media_cnt;
                $total_fan_cnt = $libGroup->total_fan_cnt;
                foreach (array_filter($selectedMediaUUIDList) as $mediaUUID) {
                    if (array_key_exists($mediaUUID, $videoLibItemUUIDList)) {//避免重复添加某个账号到媒体库
                        continue;
                    }
                    $platformInfo = VideoPlatformCommonInfo::findOne(['uuid'=>$mediaUUID]);
                    $videoLibItem = new MediaCollectLibraryGroupVideoItem();
                    $videoLibItem->uuid = PlatformHelper::getUUID();
                    $videoLibItem->group_uuid = $mediaLibUUID;
                    $videoLibItem->video_media_uuid = $mediaUUID;
                    $videoLibItem->platform_name = $platformInfo->account_name;
                    $videoLibItem->add_time = time();
                    $videoLibItem->save();
                    $media_cnt = $media_cnt + 1;
                    $total_fan_cnt = $total_fan_cnt + $platformInfo->follower_num;
                    $extraDataArray['total_avg_watch_num'] =  $extraDataArray['total_avg_watch_num']+$platformInfo->avg_watch_num;
                }
                $libGroup->media_cnt = $media_cnt;
                $libGroup->total_fan_cnt = $total_fan_cnt;
                $libGroup->extra_data = json_encode($extraDataArray);
                $libGroup->save();
                return ['err_code' => 0, 'err_msg' => '添加成功', 'redirect_url' => Url::to(['/ad-owner/admin-video-media-lib/detail'])."&group_uuid=".$mediaLibUUID];
            } else { // 新增媒体库并添加资源到该媒体库
                $loginAccountInfo = $this->getLoginAccountInfo(); // 登录账号
                //新建媒体库
                $videoLibGroupUuid = PlatformHelper::getUUID();
                $videoMediaLib = new MediaCollectLibraryGroup();
                $videoMediaLib->uuid = $videoLibGroupUuid;
                $videoMediaLib->group_name = $mediaLibName;
                $videoMediaLib->ad_owner_uuid = $loginAccountInfo['ad-owner-uuid'];
                $videoMediaLib->cate = MediaCollectLibraryGroup::CATE_VIDEO;
                $videoMediaLib->media_cnt = count(array_filter($selectedMediaUUIDList));
                $videoMediaLib->create_time = time();
                $videoMediaLib->last_update_time = time();
                //$videoMediaLib->save();
                $total_fan_cnt = 0;
                $total_avg_watch_num = 0;
                foreach(array_filter($selectedMediaUUIDList) as $key => $val){
                    $platformInfo = VideoPlatformCommonInfo::findOne(['uuid'=>$val]);
                    $total_fan_cnt = $total_fan_cnt + $platformInfo->follower_num;
                    $total_avg_watch_num = $total_avg_watch_num + $platformInfo->avg_watch_num;
                    // 添加video_item
                    $videoLibItem = new MediaCollectLibraryGroupVideoItem();
                    $videoLibItem->uuid =  PlatformHelper::getUUID();
                    $videoLibItem->group_uuid = $videoLibGroupUuid;
                    $videoLibItem->video_media_uuid = $val;
                    $videoLibItem->platform_name = $platformInfo->account_name;
                    $videoLibItem->add_time = time();
                    $videoLibItem->save();
                }
                $videoMediaLib->total_fan_cnt = $total_fan_cnt;
                $videoMediaLib->extra_data = json_encode(['total_avg_watch_num' => $total_avg_watch_num]);
                $videoMediaLib->save();
                return ['err_code' => 0, 'err_msg' => '添加成功', 'redirect_url' =>Url::to(['/ad-owner/admin-video-media-lib/detail'])."&group_uuid=".$videoLibGroupUuid];
            }
        }
    }

    /**
     * 往视频媒体库中添加媒体资源
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
                $libGroup = MediaCollectLibraryGroup::findOne(['uuid' => $mediaLibUUID, 'cate' => MediaCollectLibraryGroup::CATE_VIDEO]);
                // 已经加入媒体库的资源
                $videoLibItemList = (new Query())
                    ->select(['item.video_media_uuid',])
                    ->from(['item' => MediaCollectLibraryGroupVideoItem::tableName()])
                    ->where(['item.group_uuid' => $mediaLibUUID])
                    ->all();
                $videoLibItemUUIDList = [];
                foreach ($videoLibItemList as $videoLibItem) {
                    $videoLibItemUUIDList[$videoLibItem['video_media_uuid']] = $videoLibItem;
                }
                $extraDataArray = json_decode($libGroup->extra_data, true);
                $media_cnt = $libGroup->media_cnt;
                $total_fan_cnt = $libGroup->total_fan_cnt;
                foreach (array_filter($selectedMediaUUIDList) as $mediaUUID) {
                    if (array_key_exists($mediaUUID, $videoLibItemUUIDList)) {//避免重复添加某个账号到媒体库
                        continue;
                    }
                    $platformInfo = VideoPlatformCommonInfo::findOne(['uuid'=>$mediaUUID]);
                    $videoLibItem = new MediaCollectLibraryGroupVideoItem();
                    $videoLibItem->uuid = PlatformHelper::getUUID();
                    $videoLibItem->group_uuid = $mediaLibUUID;
                    $videoLibItem->video_media_uuid = $mediaUUID;
                    $videoLibItem->platform_name = $platformInfo->account_name;
                    $videoLibItem->add_time = time();
                    $videoLibItem->save();
                    $media_cnt = $media_cnt + 1;
                    $total_fan_cnt = $total_fan_cnt + $platformInfo->follower_num;
                    $extraDataArray['total_avg_watch_num'] =  $extraDataArray['total_avg_watch_num']+$platformInfo->avg_watch_num;
                }
                $libGroup->media_cnt = $media_cnt;
                $libGroup->total_fan_cnt = $total_fan_cnt;
                $libGroup->extra_data = json_encode($extraDataArray);
                $libGroup->save();
                return ['err_code' => 0, 'err_msg' => '添加成功', 'redirect_url' => Url::to(['/ad-owner/admin-video-media-lib/detail'])."&group_uuid=".$mediaLibUUID];
            }
        }
    }

    /**
     * 获取视频媒体库列表
     */
    public function actionGetMediaLibList(){
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isPost){
            $mediaLibName = $request->post('media_lib_name');
        }
        $loginAccountInfo = $this->getLoginAccountInfo();
        if(empty($mediaLibName)){
            $videoMedialibList = MediaCollectLibraryGroup::find()
                ->where(['ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'cate' => $this->getVideoPlatformCode(), 'status' => MediaCollectLibraryGroup::STATUS_OK])
                ->orderBy(['last_update_time' => SORT_DESC])
                ->limit(10)
                ->all();
        } else {
            $videoMedialibList = MediaCollectLibraryGroup::find()
                ->where(['ad_owner_uuid' => $loginAccountInfo['ad-owner-uuid'], 'cate' => $this->getVideoPlatformCode(), 'status' => MediaCollectLibraryGroup::STATUS_OK])
                ->andWhere(['like', 'group_name', $mediaLibName])
                ->orderBy(['last_update_time' => SORT_DESC])
                ->limit(10)
                ->all();
        }
        return ['err_code' => 0, 'video_media_lib_list' => $videoMedialibList];
    }


    
}