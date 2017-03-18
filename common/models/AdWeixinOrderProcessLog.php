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
class AdWeixinOrderProcessLog extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'ad_weixin_order_process_log';
    }


    public function rules()
    {
        return [];
    }
}