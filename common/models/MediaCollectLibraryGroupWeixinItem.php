<?php

namespace common\models;

use common\helpers\PlatformHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "media_collect_library_group_weixin_item".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $group_uuid
 * @property string $weixin_media_uuid
 * @property integer $add_time
 */
class MediaCollectLibraryGroupWeixinItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_collect_library_group_weixin_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['uuid', 'default', 'value' => PlatformHelper::getUUID()]
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['add_time']
                ],
            ]
        ];
    }
}
