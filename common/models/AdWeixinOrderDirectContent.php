<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 16/5/17
 * Time: 16:38
 */

namespace common\models;

use common\helpers\PlatformHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * WOM Admin账户表
 * Class AccountMediaVendor
 * @package common\models
 */
class AdWeixinOrderDirectContent extends ActiveRecord
{
    public static function tableName()
    {
        return 'ad_weixin_order_direct_content';
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