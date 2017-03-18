<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 8/24/16 17:00
 */
namespace common\models;

use yii\db\ActiveRecord;

/**
 * 首页媒体资源
 * Class WomHomePageMedia
 * @package common\models
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class WomHomePageMedia extends ActiveRecord{

    // 类型
    const WEIXIN_MEDIA_TYPE = 1; // 微信
    const WEIBO_MEDIA_TYPE = 2; // 微博
    const VIDEO_MEDIA_TYPE = 3; // 视频直播

    // 状态
    const STATUS_SHOW = 1; // 在首页显示
    const STATUS_HIDDEN = 0; // 在首页隐藏


    public static function tableName()
    {
        return 'wom_home_page_media';
    }
}