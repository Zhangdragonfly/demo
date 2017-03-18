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
class WomFundAccount extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'wom_fund_account';
    }


    public function rules()
    {
        return [];
    }
}