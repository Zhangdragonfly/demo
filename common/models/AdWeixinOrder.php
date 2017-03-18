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
 * Class AdWeixinOrder
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdWeixinOrder extends ActiveRecord
{
    // 订单类型
    const ORDER_TYPE_DIRECT_PUB = 1; // 直接投放
    const ORDER_TYPE_ORIGIN_PUB = 2; // 原创约稿

    // 订单状态
    const ORDER_STATUS_DELETED = -2;      //删除
    const ORDER_STATUS_TO_SUBMIT = -1;   //待提交
    const ORDER_STATUS_TO_PAY = 0;        //待支付
    const ORDER_STATUS_TO_ACCEPT = 1;      //待接单 or 待审核
    const ORDER_STATUS_REFUSE = 2;        //拒单 or 审核失败
    const ORDER_STATUS_FlOW = 3;        //流单
    const ORDER_STATUS_CANCElED = 4;    //已取消
    const ORDER_STATUS_FINISHED = 5;    //已完成
    const ORDER_STATUS_TO_SUBMIT_LINK = 6;      //待提交执行链接
    const ORDER_STATUS_TO_SUBMIT_RESULT = 7;    //待提交效果截图

    const ORDER_STATUS_CONFIRM_LINK = 8;   //确认执行链接 //待提交效果截图
    const ORDER_STATUS_TO_CONFTRM_FEEDBACK = 9;//待执行反馈



    public static function tableName()
    {
        return 'ad_weixin_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['uuid', 'default', 'value' => PlatformHelper::getUUID()],
            ['order_code', 'default', 'value' => PlatformHelper::getUUID()],
            ['position_content_conf', 'default', 'value' => json_encode(['pos_s' => 0, 'pos_s_selected' => 0, 'pos_m_1' => 0, 'pos_m_1_selected' => 0, 'pos_m_2' => 0, 'pos_m_2_selected' => 0, 'pos_m_3' => 0, 'pos_m_3_selected' => 0])],
            ['status', 'default', 'value' => self::ORDER_STATUS_TO_SUBMIT]
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