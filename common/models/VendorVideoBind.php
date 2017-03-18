<?php

namespace common\models;

use Yii;
use common\helpers\MediaHelper;
/**
 * This is the model class for table "video_media_base_info".
 *
 */
class VendorVideoBind extends \yii\db\ActiveRecord
{
    // *** 账号类型 ***
    const ACCOUNT_TYPE_HUAJIAO = 1; // 花椒
    const ACCOUNT_TYPE_PANDA = 2; // 熊猫
    const ACCOUNT_TYPE_HANI = 3; // 哈尼
    const ACCOUNT_TYPE_MEIPAI = 4; // 美拍
    const ACCOUNT_TYPE_MIAOPAI = 5; // 秒拍
    const ACCOUNT_TYPE_DOUYU = 6; // 斗鱼
    const ACCOUNT_TYPE_YINGKE = 7; // 映客
    const ACCOUNT_TYPE_TAOBAO = 8; // 淘宝
    const ACCOUNT_TYPE_YIZHIBO = 9; // 一直播

    const BIND_STATUS_WAIT_VERIFY = 0;//待审核
    const BIND_STATUS_VERIFY_SUCCESS = 1;//审核通过
    const BIND_STATUS_VERIFY_ERROR = 2;//审核未通过
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_video_bind';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }





}
