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
 *广告主交易流水记录表
 * Class AdOwnerFundChangeRecord
 * @package common\models
 * @author Tom <tom@51wom.com>
 * @since 1.0
 */
class AdOwnerAllTradeRecord extends ActiveRecord
{
    const MEDIA_TYPE_WEIXIN = 1; // 微信

    const TYPE_FREEZE = 1; // 冻结
    const TYPE_THAW = 2; // 解冻
    const TYPE_SUCCESS_PAID = 3; // 成功支付


    public static function tableName()
    {
        return 'ad_owner_all_trade_record';
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