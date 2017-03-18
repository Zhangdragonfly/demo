<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 7/7/16 2:03 PM
 */
namespace admin\modules\system\controllers;
use admin\controllers\BaseAppController;
use yii\web\Response;
use Yii;

/**
 * Account controller
 */
class AccountController extends BaseAppController
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
    public function actionChangePwd(){
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $userinfo = $this->getCurrentLoginUser();
            $new_pwd = Yii::$app->security->generatePasswordHash($request->post('new_pwd'));
            $update_res = $this->update_pwd($new_pwd,$userinfo['login_account']);
            if($update_res){
                return ['err_code' => 0];
            }
        } else {
            return $this->render("change-pwd");
        }
    }


    //修改密码
    public function update_pwd($pwd,$account){
        $connection  = Yii::$app->db;
        $sql = "UPDATE wom_admin_account SET login_password = '{$pwd}' WHERE login_account  = '{$account}'";
        $res = $connection->createCommand($sql)->execute();
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return string
     */
    public function actionChangeSuccess(){
        $request = Yii::$app->request;
        return $this->render("change-success");

    }


}
