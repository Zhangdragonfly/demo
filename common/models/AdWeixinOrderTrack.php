<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/29/16 20:13
 */

namespace common\models;

/**
 * 订单反馈信息表
 * Class AccountMediaVendor
 * @package common\models
 */
class AdWeixinOrderTrack extends \yii\db\ActiveRecord
{
    // 用户类型
    const AD_OWNER = 1; // 广告主
    const MEDIA_VENDOR = 2; // 自媒体主

    // 反馈类型

    // 媒体主
    const TRACK_TYPE_CREATE_ORDER = 1; //新建订单
    const TRACK_TYPE_ACCEPT_ORDER = 2; //接单
    const TRACK_TYPE_REFUSED_ORDER = 3; //拒单
    const TRACK_TYPE_FLOW_ORDER = 4; //流单

    const TRACK_TYPE_PREVIEW_ORDER = 18; //提交预览(2.1移除)

    const TRACK_TYPE_PUBLISH_ORDER = 22; //投放
    const TRACK_TYPE_FEEDBACK_EXECUTE_LINK = 23; //反馈执行链接
    const TRACK_TYPE_RESULT_ORDER = 24; //提交效果截图
    const TRACK_TYPE_OUTLINE_ORDER = 10; //提交大纲
    const TRACK_TYPE_CONTENT_ORDER = 14; //提交内容

    // 广告主
    const TRACK_TYPE_TO_SUBMIT_OUTLINE = 10; // 确认下单
    const TRACK_TYPE_TO_SUBMIT_CONTENT = 14; // 确认大纲
    const TRACK_TYPE_TO_SUBMIT_PREVIEW = 18; // 确认内容
    const TRACK_TYPE_TO_SUBMIT_RESULT = 24; // 确认预览
    const TRACK_TYPE_FINISHED = 5; // 确认效果
    
    public static function tableName()
    {
        return 'ad_weixin_order_track';
    }


    public function rules()
    {
        return [];
    }
}