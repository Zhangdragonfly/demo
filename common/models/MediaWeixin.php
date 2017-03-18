<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */

namespace common\models;

use common\helpers\DateTimeHelper;
use common\helpers\PlatformHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class MediaWeixin
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaWeixin extends ActiveRecord
{
    // *** 状态 ***
    const STATUS_INFO_TO_VERIFY = 0; // 资源信息待审核
    const STATUS_INFO_VERIFY_OK = 1; // 资源信息审核通过
    const STATUS_INFO_INVALID = 3; // 无效账号（采集程序判断）
    const STATUS_INFO_INVALID_MANUAL = 4; // 无效账号（人工判断）

    // *** 微信认证 ***
    const ACCOUNT_CERT_UNKNOWN = 0; // 未知
    const ACCOUNT_CERT_OK = 1; // 已认证
    const ACCOUNT_CERT_NOT = 2; // 未认证

    // *** 账号类型 ***
    const ACCOUNT_TYPE_UNKNOWN = 0; // 未知
    const ACCOUNT_TYPE_SUB = 1; // 订阅号
    const ACCOUNT_TYPE_SER = 2; // 服务号

    // 上下架
    const STATUS_PUT_UP = 1; // 上架
    const STATUS_PUT_DOWN = 0; // 下架

    public static function tableName()
    {
        return 'media_weixin';
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
     * 待删!
     * @param $pub_config
     * @return string
     */
    public static function getMediaPrice($pub_config)
    {
        $aPub_Config = json_decode($pub_config, true);
        switch ($aPub_Config['pos_m_1']['pub_type']) {
            case 0;
                $sDesc = '暂不接单';
                break;
            case 1:
                $sDesc = "
                    <span>零售价:<br/>{$aPub_Config['pos_m_1']['orig_price_max']}</span><br/>
                    <span>报价:<br/>{$aPub_Config['pos_m_1']['retail_price_max']}</span><br/>
                    <span>合作价:<br/>{$aPub_Config['pos_m_1']['coop_price']}</span>
                      ";
                break;
            case 2:
                $sDesc = "
                    <span>零售价:<br/>{$aPub_Config['pos_m_1']['orig_price_min']}~{$aPub_Config['pos_m_1']['orig_price_max']}</span><br/>
                    <span>报价:<br/>{$aPub_Config['pos_m_1']['retail_price_min']}~{$aPub_Config['pos_m_1']['retail_price_max']}</span><br/>
                    <span>合作价:<br/>{$aPub_Config['pos_m_1']['coop_price']}</span>
                      ";
                break;

        }
        return $sDesc;
    }

    /**
     * 获得微信认证信息
     */
    public function getCertInfo()
    {
        if ($this->account_cert == self::ACCOUNT_CERT_OK) {
            return '已认证';
        } else if ($this->account_cert == self::ACCOUNT_CERT_NOT) {
            return '未认证';
        } else {
            return '未知';
        }
    }

    /**
     * 获得激活信息
     */
    public function getActivateInfo()
    {
        if ($this->is_activated == 1) {
            return '是';
        } else if ($this->is_activated == 0) {
            return '否';
        }
    }

    /**
     * 获得上架信息
     */
    public function getPutUpInfo()
    {
        if ($this->put_up == 1) {
            return '是';
        } else if ($this->put_up == 0) {
            return '否';
        } else {
            return ' - ';
        }
    }

    /**
     * 状态
     */
    public function getStatusLabel()
    {
        if ($this->status == self::STATUS_TO_VERIFY) {
            return '待审核';
        } else if ($this->status == self::STATUS_VERIFY_OK) {
            return '已通过';
        } else if ($this->status == self::STATUS_VERIFY_FAIL) {
            return '未通过';
        }
    }

    /**
     * 获得入驻时间
     */
    public function getFormattedCreateTime()
    {
        return DateTimeHelper::getFormattedDateTime($this->create_time);
    }

    /**
     * 获得数据最后爬取时间
     */
    public function getFormattedLastCrawlTime()
    {
        return DateTimeHelper::getFormattedDateTime($this->last_crawl_time, 'Y-m-d H:i:s');
    }

    /**
     * 获取公众号id
     * 当real_public_id不为空时,获取的是real_public_id的值,否则获取的是public_id的值
     * 注:real_public_id来自采集程序同步,可能会有延迟;public_id来自手动输入或者迁移,可能会有大小写问题
     * @return mixed
     */
    public function actionGetPublicId()
    {
        if ($this->real_public_id != '') {
            return $this->real_public_id;
        } else {
            return $this->public_id;
        }
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