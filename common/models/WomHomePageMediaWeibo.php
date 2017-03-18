<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/29/16 15:16
 */
namespace common\models;

use yii\db\ActiveRecord;

/**
 * 首页微博媒体资源
 * Class WomHomePageMediaWeibo
 * @package common\models
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class WomHomePageMediaWeibo extends ActiveRecord
{
    const STATUS_IN_HOME = 1; // 已设置上首页
    const STATUS_TO_PUT_IN_HOME = 0; // 待上首页
    const STATUS_DELETED = -1; // 已删除

    public static function tableName()
    {
        return 'wom_home_page_media_weibo';
    }
}