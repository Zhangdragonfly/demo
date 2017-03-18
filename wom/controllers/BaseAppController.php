<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/24/16 1:35 PM
 */

namespace wom\controllers;

use common\models\UserAccount;
use wom\helpers\Constants;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class BaseAppController
 * @package wom\controllers
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
                    // 允许guests用户访问
                    [
                        // 主站首页,错误页
                        'allow' => true,
                        'controllers' => ['wom'],
                        'actions' => ['index', 'error', 'weixin-list', 'weibo-list'],
                        'roles' => ['?']
                    ],
                    [
                        // 注册页,登录页,二维码扫码登录,忘记密码,发送手机二维码
                        'allow' => true,
                        'controllers' => ['site/account'],
                        'actions' => ['register', 'register-by-qr-code', 'login', 'qr-code-login', 'forget-password', 'send-mobile-verify-code', 'check-mobile-captcha', 'is-phone-exist', 'is-email-exist', 'get-login-account-info', 'check-login'],
                        'roles' => ['?']
                    ],
                    [
                        // 微信资源列表页/详情页
                        'allow' => true,
                        'controllers' => ['weixin/media'],
                        'actions' => ['list', 'detail', 'get-wmi', 'get-chart-data', 'get-article-data', 'get-shopping-car-cookie'],
                        'roles' => ['?']
                    ],
                    [
                        // 微博资源列表页/详情页
                        'allow' => true,
                        'controllers' => ['weibo/media'],
                        'actions' => ['list', 'detail', 'get-shopping-car-cookie'],
                        'roles' => ['?']
                    ],
                    [
                        // 视频资源列表页/详情页
                        'allow' => true,
                        'controllers' => ['video/media'],
                        'actions' => ['list', 'get-shopping-car-cookie'],
                        'roles' => ['?']
                    ],
                    [
                        // 案例中心,关于我们,解决方案
                        'allow' => true,
                        'controllers' => ['site/case', 'site/about-us', 'site/solution', 'site/help-center'],
                        'actions' => ['index'],
                        'roles' => ['?']
                    ],
                    [
                        // 需要登录用户访问
                        'allow' => true,
                        'controllers' => [],
                        'actions' => [],
                        'roles' => ['@']
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    //Yii::trace('deny callback', 'dev\#' . __METHOD__);
                    $this->redirect(['/site/account/login']);
                }
            ]
        ];
    }

    /**
     * 获得微信平台编码
     * @return mixed
     */
    public function getWeixinPlatformCode()
    {
        return Yii::$app->params['media.weixin'];
    }

    /**
     * 获得微博平台编码
     * @return mixed
     */
    public function getWeiboPlatformCode()
    {
        return Yii::$app->params['media.weibo'];
    }

    /**
     * 获得视频平台编码
     * @return mixed
     */
    public function getVideoPlatformCode()
    {
        return Yii::$app->params['media.video'];
    }

    /**
     * 获取当前登录用户的信息
     * @return array|null
     */
    public function getLoginAccountInfo()
    {
        $session = Yii::$app->session;
        $accountType = $session->get(Constants::SESSION_ACCOUNT_TYPE);
        $userName = $session->get(Constants::SESSION_USER_NAME);
        if ($accountType == UserAccount::ACCOUNT_TYPE_AD_OWNER) {
            // 广告主
            $adOwnerUUID = $session->get(Constants::SESSION_AD_OWNER_UUID);
            if (isset($adOwnerUUID)) {
                return ['account-uuid' => Yii::$app->user->identity['uuid'], 'ad-owner-uuid' => $adOwnerUUID, 'account-type' => $accountType, 'user-name' => $userName];
            } else {
                return false;
            }
        } elseif ($accountType == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR) {
            // 媒体主
            $mediaVendorUUID = $session->get(Constants::SESSION_MEDIA_VENDOR_UUID);
            if (isset($mediaVendorUUID)) {
                return ['account-uuid' => Yii::$app->user->identity['uuid'], 'media-vendor-uuid' => $mediaVendorUUID, 'account-type' => $accountType, 'user-name' => $userName];
            } else {
                return false;
            }
        }
    }
}