<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */

namespace common\models;

use common\helpers\PlatformHelper;
use yii\db\ActiveRecord;

/**
 * 微信直投订单投放结果记录表
 * Class AdWeixinOrderPublishResult
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdWeixinOrderPublishResult extends ActiveRecord
{
    public static function tableName()
    {
        return 'ad_weixin_order_publish_result';
    }

    public function rules()
    {
        return [
            ['uuid', 'default', 'value' => PlatformHelper::getUUID()]
        ];
    }
}