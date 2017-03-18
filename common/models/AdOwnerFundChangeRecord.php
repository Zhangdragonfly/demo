<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * 广告主充值&授信记录表
 * Class AdOwnerFundChangeRecord
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdOwnerFundChangeRecord extends ActiveRecord
{
    // 类型
    const TYPE_CREDIT = 1; // 授信
    const TYPE_TOP_UP_ONLINE = 2; // 线上充值
    const TYPE_TOP_UP_OFFLINE = 3; // 线下充值

    // 状态
    const STATUS_DEFAULT = 0; // 授信待处理(默认)
    const STATUS_SUCCESS = 1; // 成功
    const STATUS_CANCEL = 2; // 取消

    public static function tableName()
    {
        return 'ad_owner_fund_change_record';
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