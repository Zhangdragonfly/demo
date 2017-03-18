<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * 自媒体供应商
 * Class MediaVendor
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaVendor extends ActiveRecord
{
    const REGISTER_TYPE_SELF = 1; // 自主注册
    const REGISTER_TYPE_ADMIN = 2; // admin录入
    const REGISTER_TYPE_UNKNOWN = -1; // 未知

    public static function tableName()
    {
        return 'media_vendor';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['last_update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_update_time']
                ],
            ]
        ];
    }
}