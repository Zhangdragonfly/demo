<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 16/5/17
 * Time: 16:38
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * 供应商收入记录表
 * Class VendorInComeRecord
 * @package common\models
 * @author Tom <tom@51wom.com>
 * @since 1.0
 */
class VendorInComeRecord extends ActiveRecord
{
    public static function tableName()
    {
        return 'vendor_income_record';
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