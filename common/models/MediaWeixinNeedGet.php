<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */

namespace common\models;

use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * 待获取资源表
 * Class MediaWeixinNeedGet
 * @package common\models
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class MediaWeixinNeedGet extends ActiveRecord
{
    // *** 状态 ***
    const STATUS_DEFAULT = 0; // 默认
    const STATUS_GET = 1; // 已获取
    const STATUS_INVALID = -1; // 无效


    public static function tableName()
    {
        return 'media_weixin_need_get';
    }

    public function rules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time']
                ],
            ]
        ];
    }

}