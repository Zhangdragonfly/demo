<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 1:20 PM
 */

namespace common\helpers;

use Yii;
use yii\base\Security;

/**
 * Class PlatformHelper
 * @package common\helpers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class PlatformHelper
{
    /**
     * 定金比例
     * @return mixed
     */
    public static function getGlobalDepositPercent()
    {
        return Yii::$app->params['platform.global-config']['deposit-percent'];
    }

    /**
     * 技术服务费率
     * @return mixed
     */
    public static function getGlobalServePercent()
    {
        return Yii::$app->params['platform.global-config']['serve-percent'];
    }

    /**
     * 零售价与媒体主报价之间的比率
     * @return mixed
     */
    public static function getGlobalOrigRetailRatio()
    {
        return Yii::$app->params['platform.global-config']['orig-retail-ratio'];
    }

    /**
     * 获取UUID
     * @return string
     */
    public static function getUUID()
    {
        $randomString = (new Security())->generateRandomString(7);
        $randomString = str_replace('-', '_', $randomString);
        return time() . $randomString;
    }
}