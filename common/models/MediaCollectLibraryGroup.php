<?php

namespace common\models;

use common\helpers\PlatformHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "media_collect_library_group".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $ad_owner_uuid
 * @property string $group_name
 * @property integer $cate
 * @property integer $media_cnt
 * @property integer $total_fan_cnt
 * @property integer $create_time
 * @property integer $last_update_time
 */
class MediaCollectLibraryGroup extends ActiveRecord
{
    const CATE_WEIXIN = 1; // 微信
    const CATE_WEIBO = 2; // 微博
    const CATE_VIDEO = 3; // 视频

    const STATUS_OK = 1; // 正常
    const STATUS_DELETED = 0; // 删除

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_collect_library_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['uuid', 'default', 'value' => PlatformHelper::getUUID()],
            ['status', 'default', 'value' => self::STATUS_OK]
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'last_update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_update_time']
                ],
            ]
        ];
    }
}
