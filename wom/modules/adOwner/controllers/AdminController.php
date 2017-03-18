<?php

/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/8/16 9:07 AM
 */

namespace wom\modules\adOwner\controllers;

use wom\controllers\BaseAppController;
use Yii;
/**
 * Class AdminController
 * @package wom\modules\adOwner\controllers\AdminController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminController extends BaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 广告主用户中心首页
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            // 加载页面
            return $this->render('index');
        }
    }

}
