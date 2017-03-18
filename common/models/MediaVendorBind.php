<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * 媒体与供应商绑定表
 * Class AccountMediaVendor
 * @package common\models
 */
class MediaVendorBind extends ActiveRecord
{
    const STATUS_TO_VERIFY = 0; // 待审核
    const STATUS_VERIFY_OK = 1; // 审核通过
    const STATUS_VERIFY_FAIL = 2; // 审核未通过
    const STATUS_Not_Filled = 5; // 审核未通过
    const STATUS_DELETED = -1; // 已经解除绑定（删除）

    public static function tableName()
    {
        return 'media_vendor_bind';
    }

    public function rules()
    {
        return [];
    }

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