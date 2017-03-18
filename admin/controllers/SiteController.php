<?php
namespace admin\controllers;

use admin\models\Account;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseAppController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'login';
        return $this->render('login');
    }

    public function actionHome()
    {
        return $this->render('home');
    }

    /**
     * 账号登录
     */
    public function actionLogin()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $loginAccount = $request->post('login_account');
            $loginPassword = $request->post('login_password');
            $account = Account::findByAccount($loginAccount);
            if ($account === null) {
                return ['err_code' => 0, 'err_msg' => '账号不存在!'];
            }

            // 账号+密码验证
            if ($account->validatePassword($loginPassword)) {

                // TODO 权限认证

                if (Yii::$app->user->login($account)) {
                    $account->last_login_time = time();
                    $account->save();
                    return ['err_code' => 1, 'err_msg' => '登录成功!'];
                } else {
                    return ['err_code' => 0, 'err_msg' => '系统出错!'];
                }
            } else {
                return ['err_code' => 2, 'err_msg' => '密码错误!'];
            }
        } else {
            $this->layout = 'login';

            Yii::trace('login admin', 'dev\#' . __METHOD__);

            return $this->render('login');
        }
    }

    /**
     * 退出
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->redirect(['/site/index']);
    }
}
