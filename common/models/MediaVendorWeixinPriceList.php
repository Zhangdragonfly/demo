<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:24 PM
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "media_vendor_weixin_price_list".
 */
class MediaVendorWeixinPriceList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'media_vendor_weixin_price_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['uuid', 'bind_uuid', 'orig_price_s_min', 'orig_price_s_max', 'orig_price_m_1_min', 'orig_price_m_1_max', 'orig_price_m_2_min', 'orig_price_m_2_max', 'orig_price_m_3_min', 'orig_price_m_3_max'], 'required'],
//            [['s_pub_type', 'm_1_pub_type', 'm_2_pub_type', 'm_3_pub_type', 'prmt_start_time', 'prmt_end_time'], 'integer'],
//            [['orig_price_s_min', 'orig_price_s_max', 'orig_price_m_1_min', 'orig_price_m_1_max', 'orig_price_m_2_min', 'orig_price_m_2_max', 'orig_price_m_3_min', 'orig_price_m_3_max', 'retail_price_s_min', 'retail_price_s_max', 'retail_price_m_1_min', 'retail_price_m_1_max', 'retail_price_m_2_min', 'retail_price_m_2_max', 'retail_price_m_3_min', 'retail_price_m_3_max', 'coop_price_s', 'coop_price_m_1', 'coop_price_m_2', 'coop_price_m_3', 'prmt_price_s', 'prmt_price_m_1', 'prmt_price_m_2', 'prmt_price_m_3', 'prmt_coop_price_s', 'prmt_coop_price_m_1', 'prmt_coop_price_m_2', 'prmt_coop_price_m_3'], 'number'],
//            [['pub_config', 'deposit_percent_config', 'serve_percent_config'], 'string'],
//            [['uuid', 'bind_uuid'], 'string', 'max' => 45],
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

    /**
     * 待删
     * @param $uuid
     * @param $position
     * @return mixed|string
     */
    public static function getPrice($uuid, $position)
    {
        $sPrice = '';
        $aPrice = self::find()->asArray()->where('bind_uuid =' . $uuid)->one();
        $type = $aPrice['m_1_pub_type'];
        if ($type == 1) {
            switch ($position) {
                case 1:
                    $sPrice = $aPrice['orig_price_s_min'] . '~' . $aPrice['orig_price_s_min'];
                    break;
                case 2:
                    $sPrice = $aPrice['orig_price_m_1_min'] . '~' . $aPrice['orig_price_m_1_max'];
                    break;
                case 3:
                    $sPrice = $aPrice['orig_price_m_2_min'] . '~' . $aPrice['orig_price_m_2_max'];
                    break;
                case 4:
                    $sPrice = $aPrice['orig_price_m_3_min'] . '~' . $aPrice['orig_price_m_3_max'];
                    break;
            }
        } else {
            switch ($position) {
                case 1:
                    $sPrice = $aPrice['orig_price_s_max'];
                    break;
                case 2:
                    $sPrice = $aPrice['orig_price_m_1_max'];
                    break;
                case 3:
                    $sPrice = $aPrice['orig_price_m_2_max'];
                    break;
                case 4:
                    $sPrice = $aPrice['orig_price_m_3_max'];
                    break;
            }
        }
        return $sPrice;
    }

}
