<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * 广告主信息表
 * Class AdOwner
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdVideoOrderContent extends ActiveRecord
{

    public static function tableName()
    {
        return 'ad_video_order_content';
    }

    public function rules()
    {
        return [];
    }

}