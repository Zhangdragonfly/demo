<?php
namespace admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Class BaseAppController
 * @package admin\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class BaseAppController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            /**
             * access control filter may run before actions to ensure that they are allowed to be accessed by particular end users
             */
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // 允许guests用户访问
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['index', 'login'],
                        'roles' => ['?'] // a guest user
                    ],
                    [
                        // 登录用户访问
                        'allow' => true,
                        'controllers' => [], // all controllers
                        'actions' => [], // all actions
                        'roles' => ['@'], // an authenticated user
                        'denyCallback' => function ($rule, $action) {
                            $this->redirect('site\login');
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * 获取当前登录账号的信息
     * @return array
     */
    public function getCurrentLoginUser()
    {
        $accountUUID = Yii::$app->user->id;                             // account uuid
        $loginAccount = Yii::$app->user->identity->getLoginAccount();   // login account
        $userName = Yii::$app->user->identity->getUserName();           // user name

        //Yii::trace('account uuid = ' . $accountUUID . '; login account = ' . $loginAccount . '; user name : ' . $userName, 'dev\#' . __METHOD__);
        return ['account_uuid' => $accountUUID, 'login_account' => $loginAccount, 'user_name' => $userName];
    }
}
