<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "video_media_laifeng".
 */
class AdVideoOrder extends \yii\db\ActiveRecord
{
    const ORDER_STATUS_DEFAULT = -1;//未填写完成
    const ORDER_STATUS_WAIT_SUBMIT = 0;//已提交
    const ORDER_STATUS_EXIST_SUBMIT = 1;//待接单
    const ORDER_STATUS_EXECUTE = 2;//执行中
    const ORDER_STATUS_FINISH = 3;//已完成
    const ORDER_STATUS_CANCEL = 9;//取消


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_video_order';
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
