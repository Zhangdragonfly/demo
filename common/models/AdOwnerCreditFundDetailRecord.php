<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 16/5/17
 * Time: 16:38
 */

namespace common\models;

/**
 * 授信记录详情记录表
 * Class AdOwnerCreditFundDetailRecord
 * @package common\models
 */
class AdOwnerCreditFundDetailRecord extends \yii\db\ActiveRecord
{
    // 状态
    const STATUS_SUCCESS = 1; // 成功
    const STATUS_CANCEL = 2; // 取消

    public static function tableName()
    {
        return 'ad_owner_credit_fund_detail_record';
    }


    public function rules()
    {
        return [];
    }
}