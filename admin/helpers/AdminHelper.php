<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */

namespace admin\helpers;

use yii;

/**
 * Class AdminHelper
 * @package admin\helpers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminHelper
{
    /**
     * 分页每页的大小
     * @return mixed
     */
    public static function getPageSize()
    {
        return Yii::$app->params['admin.page-size'];
    }

    /**
     * 获取供应商账号默认密码
     * @return mixed
     */
    public static function getVendorAccountDefaultPassword()
    {
        return Yii::$app->params['vendor.account-default-password'];
    }
} 