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
class MediaStatusChangeLog extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'media_status_change_log';
    }


    public function rules()
    {
        return [];
    }
}