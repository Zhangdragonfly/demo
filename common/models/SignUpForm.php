<?php

namespace common\models;

use wom\helpers\Constants;
use Yii;
use yii\base\Model;
use common\helpers\PlatformHelper;

/**
 * Class SignUpForm
 * @package common\models
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class SignUpForm extends Model
{
    public $type;
    public $email;
    public $password;
    public $confirm_password;
    public $phone;
    public $verifyCode; //验证码
    public $agree_serve;

    public function rules()
    {
        return [
            ['type', 'required'],
            ['verifyCode', 'required', 'message' => '请输入手机验证码'],
            ['email', 'required', 'message' => '请填写完整邮箱信息'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['password', 'required', 'message' => '请输入密码'],
            ['password', 'string', 'length' => [6, 20]],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => '请确保两次密码一致'],
            ['phone', 'required', 'message' => '请输入手机号码'],
            ['phone', 'string', 'max' => 11, 'min' => 11, 'message' => '手机号格式为11位的数字'],
            ['agree_serve', 'required', 'requiredValue' => true, 'message' => '请确认已阅读并同意服务条款'],
        ];
    }

    /**
     * 广告主、媒体主的注册
     * @return array
     */
    public function signUp()
    {
        //默认注册广告主
        if (empty($this->type) || ($this->type != UserAccount::ACCOUNT_TYPE_AD_OWNER && $this->type != UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR)) {
            $this->type = UserAccount::ACCOUNT_TYPE_AD_OWNER;
        }
        if ($this->validate()) {
            //验证码检验
            $session = Yii::$app->session;
            $sentVerifyCode = $session->get('m_captcha.' . $this->phone);
            $verifyCode = $this->verifyCode;
            if ($sentVerifyCode != $verifyCode) {
                return ['err_code' => 1, 'err_msg' => '验证码错误!', 'type' => $this->type];
            }

            //账号是否存在
            $account = UserAccount::findOne(['email' => $this->email, 'type' => $this->type]);
            if ($account != null) {
                return ['err_code' => 1, 'err_msg' => '该账号已经存在!', 'type' => $this->type];
            }

            //手机号是否存在
            $account = UserAccount::findOne(['phone' => $this->phone, 'type' => $this->type]);
            if ($account != null) {
                return ['err_code' => 1, 'err_msg' => '该手机号已经存在!', 'type' => $this->type];
            }

            //账号注册
            $account = new UserAccount();
            $account->uuid = PlatformHelper::getUUID();
            $account->email = trim($this->email);
            $account->phone = $this->phone;
            $account->type = $this->type;
            $account->last_login_time = time();
            $account->setPassword($this->password);
            $account->generateAuthKey();
            $account->generatePasswordResetToken();
            if ($account->save()) {
                Yii::$app->user->login($account, 3600 * 24 * 30);

                //将登录账号信息记录在session里
                $session = Yii::$app->session;
                if (!$session->isActive) {
                    $session->open();
                }
                $session->set(Constants::SESSION_ACCOUNT_UUID, $account->uuid);
                $session->set(Constants::SESSION_ACCOUNT_TYPE, $this->type);
                $session->set(Constants::SESSION_USER_NAME, $account->email);

                if ($this->type == UserAccount::ACCOUNT_TYPE_AD_OWNER) {
                    // 广告主
                    $adOwner = new AdOwner();
                    $adOwner->uuid = PlatformHelper::getUUID();
                    $adOwner->account_uuid = $account->uuid;
                    $adOwner->contact_1 = $account->phone;
                    $adOwner->save();

                    $session->set(Constants::SESSION_AD_OWNER_UUID, $adOwner->uuid);

                } else if ($this->type == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR) {
                    // 媒体主
                    $mediaVendor = new MediaVendor();
                    $mediaVendor->uuid = PlatformHelper::getUUID();
                    $mediaVendor->account_uuid = $account->uuid;
                    $mediaVendor->contact1 = $account->phone;
                    $mediaVendor->save();

                    $session->set(Constants::SESSION_MEDIA_VENDOR_UUID, $mediaVendor->uuid);
                }

                return ['err_code' => 0, 'account' => $account, 'err_msg' => '注册成功!', 'type' => $this->type];
            } else {
                return ['err_code' => 1, 'err_msg' => '注册失败,请检查输入!', 'type' => $this->type];
            }
        } else {
            return ['err_code' => 1, 'err_msg' => '注册失败,请检查输入!', 'type' => $this->type];
        }
    }
}