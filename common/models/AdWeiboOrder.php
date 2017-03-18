<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "video_media_laifeng".
 */
class AdWeiboOrder extends \yii\db\ActiveRecord
{
    // 订单状态
    const ORDER_STATUS_TO_SUBMIT = 0; // 待提交
    const ORDER_STATUS_ALREADY_SUBMIT = 1; // 已提交
    const ORDER_STATUS_FINISHED = 2; // 已完成
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_weibo_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }


}
