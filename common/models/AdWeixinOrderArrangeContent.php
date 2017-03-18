<?php

namespace common\models;

use common\helpers\PlatformHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * 原创约稿的内容
 * Class AdWeixinOrderArrangeContent
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdWeixinOrderArrangeContent extends ActiveRecord
{
    public static function tableName()
    {
        return 'ad_weixin_order_arrange_content';
    }

    public function rules()
    {
        return [
            ['uuid', 'default', 'value' => PlatformHelper::getUUID()]
        ];
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'last_update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_update_time']
                ],
            ]
        ];
    }
}