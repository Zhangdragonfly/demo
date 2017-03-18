<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 16/5/17
 * Time: 16:38
 */

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 广告主提现记录表
 * Class AdOwnerWithdrawFundDetailRecord
 * @package common\models
 */
class AdOwnerWithdrawFundDetailRecord extends ActiveRecord
{
    const STATUS_CREATE = 1; // 新建
    const STATUS_IN_PROCESSING = 2; // 处理中
    const STATUS_FINISH = 3; // 完成


    public static function tableName()
    {
        return 'ad_owner_withdraw_fund_detail_record';
    }


    public function rules()
    {
        return [];
    }
}