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
class AdOwner extends ActiveRecord
{
    const CUST_CREDIT_FUND_ALREADY = 1; // 已授信
    const CUST_CREDIT_FUND_NOT = 0; // 未授信

    public static function tableName()
    {
        return 'ad_owner';
    }

    public function rules()
    {
        return [];
    }

    /**
     * 验证支付密码
     * @param $password
     * @return bool
     */
    public function validatePayPassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->pay_pwd);
    }

    /**
     * 设置支付密码
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPayPassword($password)
    {
        $this->pay_pwd = Yii::$app->security->generatePasswordHash($password);
    }
}