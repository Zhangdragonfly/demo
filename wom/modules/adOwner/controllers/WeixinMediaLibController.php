<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 9/23/16 10:32 AM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\models\AdOwner;
use common\models\MediaCollectLibraryGroup;
use common\models\MediaCollectLibraryGroupWeixinItem;
use common\models\MediaWeixin;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Response;
use yii;
use yii\helpers\Url;

/**
 * Class WeixinMediaLibController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class WeixinMediaLibController extends AdOwnerBaseAppController
{
    /**
     * 往微信媒体库中添加媒体资源
     */
    public function actionAddMedia()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaLibUUID = $request->post('media_lib_uuid'); // 媒体库uuid
            $mediaLibName = $request->post('media_lib_name'); // 媒体库名称
            $selectedMediaUUIDList = explode(',', $request->post('selected_media_uuid_list')); // 选择的资源
            if (isset($mediaLibUUID) && $mediaLibUUID != -1) {
                // 添加资源到已有的媒体库

                $lib = MediaCollectLibraryGroup::find()
                    ->where(['uuid' => $mediaLibUUID, 'cate' => MediaCollectLibraryGroup::CATE_WEIXIN])
                    ->one();

                // 已经加入媒体库的资源
                $weixinLibItemList = (new Query())
                    ->select(['weixin_lib_item.weixin_media_uuid', 'weixin.public_name', 'weixin.follower_num', 'weixin.retail_price_s_min', 'weixin.retail_price_m_1_min', 'weixin.retail_price_m_2_min', 'weixin.retail_price_m_3_min', 'weixin.m_head_avg_view_cnt'])
                    ->from(['weixin_lib_item' => MediaCollectLibraryGroupWeixinItem::tableName()])
                    ->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid  =  weixin_lib_item.weixin_media_uuid')
                    ->where(['weixin_lib_item.group_uuid' => $mediaLibUUID])
                    ->all();

                $weixinLibItemUUIDList = [];
                foreach ($weixinLibItemList as $weixinLibItem) {
                    $weixinLibItemUUIDList[$weixinLibItem['weixin_media_uuid']] = $weixinLibItem;
                }

                $extraDataArray = json_decode($lib->extra_data, true);
                foreach (array_filter($selectedMediaUUIDList) as $mediaUUID) {

                    // 避免重复添加某个账号到媒体库
                    if (array_key_exists($mediaUUID, $weixinLibItemUUIDList)) {
                        continue;
                    }

                    // 新增
                    $mediaWeixin = MediaWeixin::findOne(['uuid'=>$mediaUUID]);
                    $weixinLibItem = new MediaCollectLibraryGroupWeixinItem();
                    $weixinLibItem->group_uuid = $mediaLibUUID;
                    $weixinLibItem->weixin_media_uuid = $mediaUUID;
                    $weixinLibItem->weixin_name = $mediaWeixin->public_name;
                    $weixinLibItem->save();

                    $lib->media_cnt = $lib->media_cnt + 1;
                    $lib->total_fan_cnt = $lib->total_fan_cnt + $mediaWeixin->follower_num;
                    $extraDataArray['total_m_1_retail_price'] = $extraDataArray['total_m_1_retail_price'] +  $mediaWeixin->retail_price_m_1_min;
                    $extraDataArray['total_m_1_avg_read_num'] = $extraDataArray['total_m_1_avg_read_num'] +  $mediaWeixin->m_head_avg_view_cnt;
                }
                $lib->extra_data = json_encode($extraDataArray);
                $lib->save();

                return ['err_code' => 0, 'err_msg' => '添加成功', 'redirect_url' => Url::to(['/ad-owner/admin-weixin-media-lib/detail'])."&lib_uuid=".$mediaLibUUID];
            } else {
                // 新增媒体库并添加资源到该媒体库
                $loginAccountInfo = $this->getLoginAccountInfo(); // 登录账号
                $weixinMediaLibUUID = PlatformHelper::getUUID();
                $weixinMediaLib = new MediaCollectLibraryGroup();
                $weixinMediaLib->uuid = $weixinMediaLibUUID;
                $weixinMediaLib->group_name = $mediaLibName;
                $weixinMediaLib->ad_owner_uuid = $loginAccountInfo['ad-owner-uuid'];
                $weixinMediaLib->cate = MediaCollectLibraryGroup::CATE_WEIXIN;
                $weixinMediaLib->media_cnt = count($selectedMediaUUIDList);

                $total_m_1_retail_price = 0;
                $total_m_1_avg_read_num = 0;
                $mediaWeixinList = MediaWeixin::find()
                    ->where(['uuid' => $selectedMediaUUIDList])
                    ->all();
                foreach ($mediaWeixinList as $weixin) {
                    $weixinMediaLib->total_fan_cnt = $weixinMediaLib->total_fan_cnt + $weixin->follower_num;
                    $total_m_1_retail_price = $total_m_1_retail_price + $weixin->retail_price_m_1_min;
                    $total_m_1_avg_read_num = $total_m_1_avg_read_num + $weixin->m_head_avg_view_cnt;

                    // 添加weixin item
                    $weixinLibItem = new MediaCollectLibraryGroupWeixinItem();
                    $weixinLibItem->group_uuid = $weixinMediaLibUUID;
                    $weixinLibItem->weixin_media_uuid = $weixin->uuid;
                    $weixinLibItem->weixin_name = $weixin->public_name;
                    $weixinLibItem->save();
                }
                $weixinMediaLib->extra_data = json_encode(['total_m_1_retail_price' => $total_m_1_retail_price, 'total_m_1_avg_read_num' => $total_m_1_avg_read_num]);
                $weixinMediaLib->save();
                return ['err_code' => 0, 'err_msg' => '添加成功', 'redirect_url' => Url::to(['/ad-owner/admin-weixin-media-lib/detail'])."&lib_uuid=".$weixinMediaLibUUID];
            }
        }
    }

    /**
     * 获取微信媒体库列表
     */
    public function actionGetAll()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isPost){
            $mediaLibName = $request->post('media_lib_name');
        }
        $loginAccountInfo = $this->getLoginAccountInfo();
        if(empty($mediaLibName)){
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
}