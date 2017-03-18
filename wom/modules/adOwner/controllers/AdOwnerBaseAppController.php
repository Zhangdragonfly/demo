<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/24/16 1:35 PM
 */

namespace wom\modules\adOwner\controllers;

use common\models\UserAccount;
use wom\controllers\BaseAppController;
use wom\helpers\Constants;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class AdOwnerBaseAppController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdOwnerBaseAppController extends BaseAppController
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 判断是否为游客
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (!isset(Yii::$app->user->identity['uuid'])) {
            return false;
        }

        // session是否有效
        $session = Yii::$app->session;
        if (!$session->isActive) {
            return false;
        }

        $accountType = $session->get(Constants::SESSION_ACCOUNT_TYPE);
        $userName = $session->get(Constants::SESSION_USER_NAME);

        if(!isset($accountType)){
            return false;
        }
        if(!isset($userName)){
            return false;
        }

        // 判断当前登录账号是否是广告主账号
        if ($accountType != UserAccount::ACCOUNT_TYPE_AD_OWNER) {
            // 当前登录用户不是广告主
            // $this->redirect(['/site/account/login']);
            echo json_encode(['err_code' => 2, 'err_msg' => '请使用广告主账号登录!']);
            return false;
        }

        return true;
    }
}