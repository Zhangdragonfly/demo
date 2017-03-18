<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace common\models;
use common\helpers\PlatformHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class AdWeixinPlan
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdWeixinPlan extends ActiveRecord
{

    const STATUS_TO_PUBLISH = 0; // 待提交
    const STATUS_LABEL_TO_PUBLISH = '待提交';

    const STATUS_TO_PAY = 1; // 待支付
    const STATUS_LABEL_TO_PAY = '待支付';

    const STATUS_TO_DEAL = 10; // 待执行(2.1新增, 活动中选择的订单全部是预约类订单,不需要在线支付)
    const STATUS_LABEL_TO_DEAL = '待执行';

    const STATUS_IN_PROGRESS = 2; // 执行中 or 已支付
    const STATUS_LABEL_IN_PROGRESS = '执行中';

    const STATUS_FINISH = 3; // 已完成
    const STATUS_LABEL_FINISH = '已完成';

    const STATUS_CANCEL = 9; // 取消
    const STATUS_LABEL_CANCEL = '已取消';

    const STATUS_UNKNOWN = -1; // 未知
    const STATUS_LABEL_UNKNOWN = '未知';

    const PAY_STATUS_NOT = 0; // 未支付
    const PAY_STATUS_FINISH = 1; // 冻结定金


    public static function tableName()
    {
        return 'ad_weixin_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['uuid', 'default', 'value' => PlatformHelper::getUUID()],
            ['status', 'default', 'value' => self::STATUS_TO_PUBLISH]
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