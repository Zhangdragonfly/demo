<?php

namespace weixin\api\base;

use Yii;
use yii\helpers\Url;

/**
 * Class ThirdPartyLogin
 * @package weixin\api\base
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class ThirdPartyLogin extends Behavior
{
    const OPENID_SESSION_KEY = 'wechat_openid';
    const UNION_ID_SESSION_KEY = 'wechat_union_id';
    const WEIXIN_QR_CONNECT_URL = 'https://open.weixin.qq.com/connect/qrconnect';
    const WEIXIN_USER_INFO_URL = 'https://api.weixin.qq.com/sns/userinfo';
    private $_info;

    /**
     * 获得微信用户基本信息
     * @return array|bool
     */
    public function getThirdPartyUserInfo()
    {
        if ($this->_info === null) {
            $accessToken = $this->goOAuth();
            $queryData = [
                'access_token' => $accessToken['access_token'],
                'openid' => $accessToken['openid'],
            ];
            $result = $this->owner->http(self::WEIXIN_USER_INFO_URL, $queryData);
            if ($result) {
                $result = json_decode($result, true);
                if (isset($result['errcode'])) {
                    return false;
                } else if(!array_key_exists('unionid', $result)) {
                    return false;
                } else {
                    $info = [
                        'unionid' => $result['unionid'],
                        'nickname' => $result['nickname'],
                        'headimgurl' => $result['headimgurl'],
                    ];
                    $this->_info = $info;
                }
            } else {
                return false;
            }
        }
        return $this->_info;
    }

    /**
     * 基于"微信OAuth2.0授权登录系统"获取code进而获取access_token
     * @return bool
     */
    protected function goOAuth()
    {
        $code = Yii::$app->request->get('code');
        if (empty($code)) {
            $queryData = [
                'appid' => $this->owner->appID,
                'redirect_uri' => Url::current([], true),
                'response_type' => 'code',
                'scope' => 'snsapi_login',
                'state' => Yii::$app->security->generateRandomString(),
            ];
            Yii::$app->controller->redirect(self::WEIXIN_QR_CONNECT_URL . '?' . http_build_query($queryData) . '#wechat_redirect');
        } else {
            $result = $this->owner->getAccessTokenByCode($code);
            if ($result) {
                return $result;
            } else {
                return false;
            }
        }
    }

}