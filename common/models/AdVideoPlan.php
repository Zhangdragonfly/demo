<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 8/22/16 2:36 PM
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class AdVideoPlan
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdVideoPlan extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_video_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }
}
