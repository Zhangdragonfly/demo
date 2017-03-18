<?php
namespace common\models;

use wom\helpers\Constants;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $type;
    public $username;
    public $password;
    public $rememberMe;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['type', 'required'],
            ['username', 'required', 'message' => '请输入账号'],
            ['password', 'required', 'message' => '请输入密码'],
        ];
    }

    /**
     * This method serves as the inline validation for password.
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($this->password, $password);
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        //默认广告主登录
        if (empty($this->type) || ($this->type != UserAccount::ACCOUNT_TYPE_AD_OWNER && $this->type != UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR)) {
            $this->type = UserAccount::ACCOUNT_TYPE_AD_OWNER;
        }
        if ($this->validate()) {
            $user = $this->getUser();
            if (isset($user) && $this->validatePassword($user->password)) {
                $rnt = Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);

                //将登录账号信息记录在session里
                $session = Yii::$app->session;
                if (!$session->isActive) {
                    $session->open();
                }
                $session->set(Constants::SESSION_ACCOUNT_UUID, $user->uuid);
                $session->set(Constants::SESSION_ACCOUNT_TYPE, $this->type);
                $session->set(Constants::SESSION_USER_NAME, $this->username);
                if ($this->type == UserAccount::ACCOUNT_TYPE_AD_OWNER) {
                    // 广告主
                    $adOwner = AdOwner::find()
                        ->where(['account_uuid' => $user->uuid])
                        ->one();
                    $session->set(Constants::SESSION_AD_OWNER_UUID, $adOwner->uuid);
                } elseif ($this->type == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR) {
                    // 媒体主
                    $mediaVendor = MediaVendor::find()
                        ->where(['account_uuid' => $user->uuid])
                        ->one();
                    $session->set(Constants::SESSION_MEDIA_VENDOR_UUID, $mediaVendor->uuid);
                }

                // Yii::trace('uuid : ' . $user->uuid . ', account type : ' . $this->type . ', username : ' . $this->username, 'dev\#' . __METHOD__);

                return $rnt;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserAccount::findByUsername(trim($this->username), $this->type);
        }
        return $this->_user;
    }
}
