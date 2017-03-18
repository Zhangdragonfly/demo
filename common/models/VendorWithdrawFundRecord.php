<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 16/5/17
 * Time: 16:38
 */

namespace common\models;

/**
 * WOM Admin账户表
 * Class AccountMediaVendor
 * @package common\models
 */
class VendorWithdrawFundRecord extends \yii\db\ActiveRecord
{
    const STATUS_CREATE = 1; // 新建
    const STATUS_IN_PROCESSING = 2; // 处理中
    const STATUS_FINISH = 3; // 完成
    public static function tableName()
    {
        return 'vendor_withdraw_fund_record';
    }


    public function rules()
    {
        return [];
    }
}