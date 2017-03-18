<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/23/16 15:17
 */
namespace common\models;

use yii\db\ActiveRecord;

/**
 * 微信直投订单时间点
 * Class WomDirectOrderTimeLineCtl
 * @package common\models
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class WomDirectOrderTimeLineCtl extends ActiveRecord{

    public static function tableName()
    {
        return 'wom_direct_order_timeline_ctl';
    }
}