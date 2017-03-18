<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/29/16 11:29 AM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\MediaHelper;
use common\models\AdWeixinOrder;
use common\models\AdWeixinPlan;
use common\models\MediaWeixin;
use wom\controllers\BaseAppController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Response;

/**
 * 广告主个人中心/报表管理/微信
 * Class AdminWeixinReportController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminWeixinReportController extends BaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 微信报表列表
     */
    public function actionList()
    {
        return $this->render('list', [
        ]);
    }

    public function actionDetail()
    {
        $this->layout = '//site-stage';
        return $this->render('detail', [
        ]);
    }
}