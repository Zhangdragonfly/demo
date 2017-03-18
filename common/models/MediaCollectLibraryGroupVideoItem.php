<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "media_collect_library_group_video_item".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $group_uuid
 * @property string $video_media_uuid
 * @property integer $add_time
 */
class MediaCollectLibraryGroupVideoItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_collect_library_group_video_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'group_uuid', 'video_media_uuid', 'add_time'], 'required'],
            [['add_time'], 'integer'],
            [['uuid', 'group_uuid', 'video_media_uuid'], 'string', 'max' => 45],
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
            'video_media_uuid' => 'Video Media Uuid',
            'add_time' => 'Add Time',
        ];
    }
}
