<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * 媒体资源批量导入记录表
 * Class MediaBatchUpload
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaBatchUpload extends ActiveRecord
{
    public static function tableName()
    {
        return 'media_batch_upload';
    }

    public function rules()
    {
        return [];
    }
}