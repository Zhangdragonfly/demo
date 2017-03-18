<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/24/16 11:39 AM
 */

namespace common\models;

use yii\db\ActiveRecord;


class MediaWeibo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_weibo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }

}