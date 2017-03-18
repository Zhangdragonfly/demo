<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * 媒体资源导入记录表
 * Class MediaVideoUploadRecordItem
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaVideoUploadRecordItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'media-video-upload-record-item';
    }

    public function rules()
    {
        return [];
    }
}