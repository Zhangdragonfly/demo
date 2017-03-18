<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "media_collect_library_group_weibo_item".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $group_uuid
 * @property string $weibo_media_uuid
 * @property integer $add_time
 */
class MediaCollectLibraryGroupWeiboItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_collect_library_group_weibo_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'group_uuid', 'weibo_media_uuid', 'add_time'], 'required'],
            [['add_time'], 'integer'],
            [['uuid', 'group_uuid', 'weibo_media_uuid'], 'string', 'max' => 5000],
            [['uuid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'group_uuid' => 'Group Uuid',
            'weibo_media_uuid' => 'Weibo Media Uuid',
            'add_time' => 'Add Time',
        ];
    }
}
