<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 媒介运营人员绑定表
 * Class MediaExecutorAssign
 * @package admin\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 *
 * @property integer $id
 * @property string $uuid
 * @property string $media_type
 * @property string $executor_uuid
 * @property string $media_uuid
 * @property string $assign_time
 */
class MediaExecutorAssign extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_executor_assign';
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