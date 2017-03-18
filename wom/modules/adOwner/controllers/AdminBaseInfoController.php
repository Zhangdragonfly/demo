<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/19/16 15:44
 */
namespace wom\modules\adOwner\controllers;
use common\models\AdOwner;
use common\models\UserAccount;
use yii\web\Response;
use yii;
/**
 * Class AdminBaseInfoController 基本信息
 * @package wom\modules\adOwner\controllers
 * @since 1.0
 */
class AdminBaseInfoController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';
    /**
     * 基本资料
     * @return array|string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $contactPerson = $request->post('contact_person');
            $weixin = $request->post('weixin');
            $qq = $request->post('qq');
            $companyName = $request->post('company_name');
            $companySite = $request->post('company_site');
            $companyAddress = $request->post('company_address');
            $companySynopsis = $request->post('company_synopsis');
            $location = $request->post('location');
            $nickname = $request->post('nickname');

            $adOwner = AdOwner::findOne(['uuid' => self::getLoginAccountInfo()['ad-owner-uuid']]);
            $adOwner->contact_name = $contactPerson;
            $adOwner->weixin = $weixin;
            $adOwner->qq = $qq;
            $adOwner->location = $location;
            $adOwner->nickname = $nickname;
            $adOwner->comp_name = $companyName;
            $adOwner->comp_website = $companySite;
            $adOwner->comp_address = $companyAddress;
            $adOwner->comp_desc = $companySynopsis;
            if($adOwner->save()){
                return ['err_code' => 0, 'err_msg' => '保存成功'];
            }else{
                return ['err_code' => 1, 'err_msg' => '保存失败'];
            }
        }else{
            $row = (new yii\db\Query())
            ->select([
                'email',
                'phone',
                'account.create_time',
                'weixin',
                'qq',
                'comp_name',
                'comp_website',
                'comp_address',
                'comp_desc',
                'contact_name',
                'nickname',
                'location',
            ])
            ->from(['account' => UserAccount::tableName()])
            ->where(['adOwner.uuid' => self::getLoginAccountInfo()['ad-owner-uuid']])
            ->leftJoin(['adOwner' => AdOwner::tableName()], 'account.uuid = adOwner.account_uuid')
            ->one();
            return $this->render('index', ['info' => $row]);
        }
    }

    /**
     * 修改密码
     */
    public function actionUpdatePassword()
    {
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $password = $request->post('password');
            $newPassword = $request->post('new_password');
            $userAccount = UserAccount::findOne(['uuid' => self::getLoginAccountInfo()['account-uuid']]);
            if(Yii::$app->security->validatePassword($password, $userAccount->password)){
                $userAccount->password = Yii::$app->security->generatePasswordHash($newPassword);
                $userAccount->last_update_time = time();
                if($userAccount->save()){
                    return ['err_code' => 0, 'err_msg' => '密码修改成功'];
                }else{
                    return ['err_code' => 2, 'err_msg' => '密码修改失败'];
                }
            }else{
                return ['err_code' => 1, 'err_msg' => '密码验证失败'];
            }
        }else{
            return $this->render('update-password');
        }
    }

    /**
     * 设置支付密码
     */
    public function actionSetPayPassword()
    {
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $payPassword = $request->post('pay_password');
            $verifyCode = $request->post('verify_code');
            $cellPhone = $request->post("cell_phone");
            $session = Yii::$app->session;
            $send_verifyCode = $session->get('m_captcha.' . $cellPhone);

            $verifyCode = intval($verifyCode);
            if($send_verifyCode == $verifyCode){
                $adOwner = AdOwner::findOne(['uuid' => self::getLoginAccountInfo()['ad-owner-uuid']]);
                $adOwner->pay_pwd = Yii::$app->security->generatePasswordHash($payPassword);
                if($adOwner->save()){
                    return ['err_code' => 0, 'err_msg' => '支付密码设置成功!'];
                }else{
                    return ['err_code' => 2, 'err_msg' => '支付密码设置失败!'];
                }
            }else{
                return ['err_code' => 1, 'err_msg' => '验证码错误!'];
            }
        }else{
            return $this->render('set-pay-password');
        }
    }

    /**
     * 更改绑定手机号码
     */
    public function actionUpdatePhone()
    {
        $request = Yii::$app->request;
        if($request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $verifyCode = $request->post('verify_code');
            $cellPhone = $request->post("cell_phone");
            $session = Yii::$app->session;
            $send_verifyCode = $session->get('m_captcha.' . $cellPhone);

            $verifyCode = intval($verifyCode);
            if($send_verifyCode == $verifyCode){
                // 判断新手机号是否已存在
                $userAccount = UserAccount::findOne(['phone' => $cellPhone]);
                if(empty($userAccount)){
                    $userAccount = UserAccount::findOne(['uuid' => self::getLoginAccountInfo()['account-uuid']]);
                    $userAccount->phone = $cellPhone;
                    $userAccount->last_update_time = time();
                    if($userAccount->save()){
                        return ['err_code' => 0, 'err_msg' => '手机号绑定成功'];
                    }else{
                        return ['err_code' => 2, 'err_msg' => '手机号绑定失败'];
                    }
                }else{
                    return ['err_code' => 3, 'err_msg' => '手机号已被占用'];
                }
            }else{
                return ['err_code' => 1, 'err_msg' => '验证码错误!'];
            }
        }else{
            return $this->render('set-pay-password');
        }
    }

}