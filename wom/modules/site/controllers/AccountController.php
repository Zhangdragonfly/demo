<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM
 */

namespace wom\modules\site\controllers;

use common\models\AdOwner;
use common\models\MediaVendor;
use common\models\UserAccount;
use wom\controllers\BaseAppController;
use common\helpers\SendNoticeHelper;
use common\models\SignUpForm;
use common\models\LoginForm;
use wom\helpers\Constants;
use Yii;
use yii\web\Response;

/**
 * Class AccountController
 * @package wom\modules\site\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AccountController extends BaseAppController
{
    public $layout = '//site-stage';

    /**
     * 广告主/媒体主注册
     */
    public function actionRegister()
    {
        $request = Yii::$app->request;
        $accountForm = new SignUpForm();
        if ($request->isPost) {
            if ($accountForm->load($request->post())) {
                $rtn = $accountForm->signUp();
                if ($rtn['err_code'] == 0) {
                    // 注册成功
                    return $this->render('regist-step-two', [
                        'accountForm' => $rtn['account'],
                        'accountType' => $rtn['type']
                    ]);
                } else {
                    // 注册失败
                    return $this->render('regist-step-one', [
                        'accountForm' => $accountForm,
                        'accountType' => $rtn['type'],
                        'errMsg' => $rtn['err_msg']
                    ]);
                }
            }
        }
        if ($request->isGet) { // 处理get访问请求
            $accountType = $request->get('type', UserAccount::ACCOUNT_TYPE_AD_OWNER);
            if ($accountType != UserAccount::ACCOUNT_TYPE_AD_OWNER && $accountType != UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR) {
                $accountType = UserAccount::ACCOUNT_TYPE_AD_OWNER;
            }
            return $this->render('regist-step-one', [
                'accountForm' => $accountForm,
                'accountType' => $accountType
            ]);
        }
    }

    /**
     * 微信扫码注册
     */
    public function actionRegisterByQrCode()
    {
        $request = Yii::$app->request;
        $accountForm = new SignUpForm();
        if ($request->isPost) { // 处理post提交请求
            $unionid = $request->post('unionid');
            if ($accountForm->load($request->post())) {
                $rtn = $accountForm->signUp();
                if ($rtn['err_code'] == 0) {
                    // 添加 unionid
                    $account = $rtn['account'];
                    $account->weixin_open_platform_unionid = $unionid;
                    $account->save();
                    // 注册成功
                    return $this->render('regist-step-two', [
                        'accountForm' => $rtn['account'],
                        'accountType' => $rtn['type']
                    ]);
                } else {
                    // 注册失败
                    return $this->render('regist-by-qr-code', [
                        'accountForm' => $accountForm,
                        'accountType' => $rtn['type'],
                        'errMsg' => $rtn['err_msg']
                    ]);
                }
            }
        }
        if ($request->isGet) {
            return $this->render('regist-by-qr-code', [
                'accountForm' => $accountForm
            ]);
        }
    }

    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $request = Yii::$app->request;

        // post请求
        $loginForm = new LoginForm();
        if ($request->isPost) {
            if ($loginForm->load($request->post()) && $loginForm->login()) {
                return $this->goHome();
            } else {
                $errMsg = "用户名或密码不正确";
                return $this->render('login', [
                    'loginForm' => $loginForm,
                    'accountType' => $loginForm['type'],
                    'errMsg' => $errMsg,
                ]);
            }
        }
        // 加载页面
        if ($request->isGet) {
            $type = $request->get('type', UserAccount::ACCOUNT_TYPE_AD_OWNER);
            if ($type != UserAccount::ACCOUNT_TYPE_AD_OWNER && $type != UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR) {
                $type = UserAccount::ACCOUNT_TYPE_AD_OWNER;
            }
            return $this->render('login', [
                'loginForm' => $loginForm,
                'accountType' => $type
            ]);
        }
    }

    /**
     * 微信扫码登录
     */
    public function actionQrCodeLogin()
    {
        $request = Yii::$app->request;

        $code = $request->get("code");
        //通过code获取access_token
        $accessTokenJson = file_get_contents(("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxc73f763daf51ab73&secret=38c269dd4b51b695cee7d43c32f9201e&code=" . $code . "&grant_type=authorization_code"));

        $accessTokenArray = json_decode($accessTokenJson, true);
        if (array_key_exists('unionid', $accessTokenArray)) {
            $unionid = $accessTokenArray['unionid'];
            $account = UserAccount::findOne(['weixin_open_platform_unionid' => $unionid]);
            //判断是否注册
            if (empty($account)) {
                //未注册
                $this->redirect(array("register-by-qr-code", 'unionid' => $unionid));
            } else {
                //已注册, 登录
                if (Yii::$app->user->login($account)) {
                    //将登录账号信息记录在session里
                    $session = Yii::$app->session;
                    if (!$session->isActive) {
                        $session->open();
                    }
                    $session->set(Constants::SESSION_ACCOUNT_UUID, $account->uuid);
                    $session->set(Constants::SESSION_ACCOUNT_TYPE, $account->type);
                    $session->set(Constants::SESSION_USER_NAME, $account->email);
                    if ($account->type == UserAccount::ACCOUNT_TYPE_AD_OWNER) {
                        // 广告主
                        $adOwner = AdOwner::find()
                            ->where(['account_uuid' => $account->uuid])
                            ->one();
                        $session->set(Constants::SESSION_AD_OWNER_UUID, $adOwner->uuid);
                    } elseif ($account->type == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR) {
                        // 媒体主
                        $mediaVendor = MediaVendor::find()
                            ->where(['account_uuid' => $account->uuid])
                            ->one();
                        $session->set(Constants::SESSION_MEDIA_VENDOR_UUID, $mediaVendor->uuid);
                    }
                    //更新登录时间
                    $account->last_login_time = time();
                    $account->save();
                    $this->redirect(array("/weixin/media/list"));
                } else {
                    $this->redirect(array("login"));
                }
            }
        }
    }

    /**
     * 已注册用户绑定微信
     */
    public function actionBoundWeixinUuid()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $email = $request->get("email");
        //更新account表的 weixin_open_platform_unionid
        $code = $request->get("code");

        $unionId = $this->getWeixinUnionId($code);
        //判断 unionId 是否已经被其他账户绑定
        $account = UserAccount::findOne(['weixin_open_platform_unionid' => $unionId, 'email' => $email]);
        if (empty($account)) {
            $account = UserAccount::findOne(['email' => $email]);
            //判断该账号的 unionId 是否为空
            $platformUnionId = $account->weixin_open_platform_unionid;
            if (empty($platformUnionId)) {
                $account->weixin_open_platform_unionid = $unionId;
                $account->last_login_time = time();
                $account->last_update_time = time();
                if ($account->save()) {
                    $this->redirect(array("/weixin/media/list"));
                }
            } else {
                return ['err_code' => 1, '该账号的 unionId 已经绑定过了'];
            }
        } else {
            return ['err_code' => 1, '该微信 unionId 已经被绑定过了'];
        }
    }

    /**
     * 获取微信 unionid
     * @param int $code
     * @return int
     */
    public function getWeixinUnionId($code)
    {
        $accessTokenJson = file_get_contents(("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxc73f763daf51ab73&secret=38c269dd4b51b695cee7d43c32f9201e&code=" . $code . "&grant_type=authorization_code"));
        $accessTokenArray = json_decode($accessTokenJson, true);
        if (array_key_exists('unionid', $accessTokenArray)) {
            $unionId = $accessTokenArray['unionid'];
        } else {
            $unionId = 0;
        }
        return $unionId;
    }

    /**
     * 忘记密码/找回密码
     */
    public function actionForgetPassword()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $step = $request->get('step', 1);
            if ($step == 1) {// 第1步
                return $this->render('forget-password-step-one');
            } else if ($step == 2) { // 第2步
                return $this->render('forget-password-step-two');
            } else if ($step == 3) { // 第3步
                return $this->render('forget-password-complete');
            }
        }

        if ($request->isPost) {
            $step = $request->post('step', 1);
            if ($step == 1) {//验证码检验
                $phone = $request->post("cell_phone");
                $verifyCode = $request->post("verify_code");

                $session = Yii::$app->session;
                $sentVerifyCode = $session->get('m_captcha.' . $phone);

                if ($sentVerifyCode != $verifyCode) {
                    return json_encode(['err_code' => 1, 'err_msg' => '验证码错误!']);
                } else {
                    return json_encode(['err_code' => 0, 'err_msg' => '验证成功!']);
                }
            }
            if ($step == 2) {//重置密码
                $cell_phone = $request->post("cell_phone");
                $psd_val = $request->post("psd_val");
                $insure_psd_val = $request->post("insure_psd_val");
                if ($psd_val == $insure_psd_val) {
                    $userAccount = UserAccount::findOne(['phone' => $cell_phone]);
                    $userAccount->setPassword($psd_val);
                    $userAccount->last_update_time = time();
                    $userAccount->save();
                    return json_encode(['err_code' => 0, 'err_msg' => '重置密码成功!']);
                } else {
                    return json_encode(['err_code' => 1, 'err_msg' => '重置密码失败!']);
                }
            }
        }
    }

    /**
     * 发送手机验证码
     */
    public function actionSendMobileVerifyCode()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $phoneNum = $request->post("cell_phone");

            $verifyCode = rand(1000, 9999); // 生成4位随机验证码"6532";
            $aliveMinutes = 5; // 保留的分钟数

            // 记录在session中
            $session = Yii::$app->session;
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('m_captcha.' . $phoneNum, $verifyCode);
            $session->set('m_captcha.lifetime', 60 * $aliveMinutes);

            SendNoticeHelper::send(SendNoticeHelper::TYPE_SMS, $phoneNum, 44998, array($verifyCode, $aliveMinutes));

            return json_encode(['err_code' => 0, 'err_msg' => '发送成功，请查看手机短信']);
        }
    }

    /**
     * 检查手机验证码
     */
    public function actionCheckMobileCaptcha()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $phoneNum = $request->post('phone');
            $verifyCode = $request->post('verify_code');

            $session = Yii::$app->session;
            if (!$session->isActive) {
                return json_encode(['err_code' => 2, 'err_msg' => '系统出错!']);
            }
            $sentVerifyCode = $session->get('m_captcha.' . $phoneNum);

            // Yii::trace('sent verify code: ' . $sentVerifyCode . ', verify code:' . $verifyCode, 'dev\#' . __METHOD__);

            if ($sentVerifyCode == $verifyCode) {
                return json_encode(['err_code' => 0, 'err_msg' => '验证码正确!']);
            } else {
                return json_encode(['err_code' => 1, 'err_msg' => '验证码错误!']);
            }
        }
    }

    /**
     * 检查手机号是否存在
     */
    public function actionIsPhoneExist()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $phone = $request->get("phone");
            $userAccount = UserAccount::findOne(['phone' => $phone]);
            if (isset($userAccount)) {
                return json_encode(['err_code' => 0, 'is_exist' => 1, 'err_msg' => '手机号存在']);
            } else {
                return json_encode(['err_code' => 0, 'is_exist' => 0, 'err_msg' => '手机号不存在']);
            }
        }
    }

    /**
     * 检查邮箱是否存在
     */
    public function actionIsEmailExist()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $email = $request->get("email");
            $userAccount = UserAccount::findOne(['email' => $email]);
            if (isset($userAccount)) {
                return json_encode(['err_code' => 0, 'is_exist' => 1, 'err_msg' => '手机号存在']);
            } else {
                return json_encode(['err_code' => 0, 'is_exist' => 0, 'err_msg' => '手机号不存在']);
            }
        }
    }

    /**
     * 检查是否登录
     * @return array
     */
    public function actionCheckLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $loginAccountInfo = $this->getLoginAccountInfo();
        if ($loginAccountInfo === null) {
            return ['is_logined' => 0];
        } else {
            return ['is_logined' => 1, 'uuid' => $loginAccountInfo['uuid']];
        }
    }

    /**
     * 获取登录用户的信息
     */
    public function actionGetLoginAccountInfo()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $accountType = $request->get("account_type");
            if ($accountType == UserAccount::ACCOUNT_TYPE_AD_OWNER) {
                // 广告主
                $loginAccountInfo = $this->getLoginAccountInfo();
                if($loginAccountInfo == false){
                    return ['err_code' => 2, 'err_msg' => 'no login'];
                }
                $adOwnerUUID = $loginAccountInfo['ad-owner-uuid'];
                $userName = $loginAccountInfo['user-name'];
                $adOwner = AdOwner::find()
                    ->where(['uuid' => $adOwnerUUID])
                    ->one();

                if (isset($adOwner)) {
                    return ['err_code' => 0, 'account_type' => $accountType, 'user_name' => $userName, 'total_available_balance' => $adOwner->total_available_balance, 'total_frozen_amount' => $adOwner->total_frozen_amount];
                } else {
                    return ['err_code' => 1, 'err_msg' => 'system error'];
                }
            } else {
                // 媒体主
                $loginAccountInfo = $this->getLoginAccountInfo();
                if($loginAccountInfo == false){
                    return ['err_code' => 2, 'err_msg' => 'no login'];
                }
                $mediaVendorUUID = $loginAccountInfo['media-vendor-uuid'];
                $userName = $loginAccountInfo['user-name'];
                $mediaVendor = MediaVendor::find()
                    ->where(['uuid' => $mediaVendorUUID])
                    ->one();

                if (isset($mediaVendor)) {
                    return ['err_code' => 0, 'account_type' => $accountType, 'user_name' => $userName, 'total_available_balance' => $mediaVendor->balance];
                } else {
                    return ['err_code' => 1, 'err_msg' => 'system error'];
                }
            }
        }
    }

    /**
     * 退出系统
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->redirect(['/site/account/login', 'type' => 1]);
    }
}