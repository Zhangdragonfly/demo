<?php

namespace weixin\api\behaviors;

use Yii;
use yii\helpers\Url;
use yii\base\Behavior;

/**
 * Class UserInfoBehavior
 * @package weixin\api\behaviors
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class UserInfoBehavior extends Behavior
{
    const AUTH_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
    const GET_ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * 静默授权方式（snsapi_base）获取用户基本信息(access_token、openid)
     *
     * 通过code换取的是一个特殊的网页授权access_token,与基础支持中的access_token（该access_token用于调用其他接口）不同.
     * @return array|null
     */
    public function getBaseInfo()
    {
        $code = Yii::$app->request->get('code', null);

        // Yii::trace('Code is ' . $code, 'dev\*' . __METHOD__);

        if ($code == null) {
            $params = [
                'appid' => $this->owner->appID,
                'redirect_uri' => Url::current([], true),
                'response_type' => 'code',
                'scope' => 'snsapi_base',
                'state' => 'redirect',
            ];

            $url = self::AUTH_URL . http_build_query($params) . '#wechat_redirect';

            // Yii::trace('Url is ' . $url, 'dev\*' . __METHOD__);

            Yii::$app->controller->redirect($url);
        } else {
            $params = [
                'appid' => $this->owner->appID,
                'secret' => $this->owner->appSecret,
                'code' => $code,
                'grant_type' => 'authorization_code'
            ];

            /*
             返回结果
                {
                    "access_token":"ACCESS_TOKEN",
                    "expires_in":7200,
                    "refresh_token":"REFRESH_TOKEN",
                    "openid":"OPENID",
                    "scope":"SCOPE",
                    "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
                }
             */
            $result = $this->owner->http(self::GET_ACCESS_TOKEN_URL, $params);

            if ($result) {
                return json_decode($result, true);
            } else {
                return null;
            }
        }
    }

    /**
     *
     */
    public function getFullInfo()
    {
        $code = Yii::$app->request->get('code', null);
        if ($code == null) {

            Yii::trace('Code is null', 'dev\*' . __METHOD__);

            $params = [
                'appid' => $this->owner->appID,
                'redirect_uri' => Url::current([], true),
                'response_type' => 'code',
                'scope' => 'snsapi_userinfo',
                'state' => '',
            ];

            $url = self::AUTH_URL . http_build_query($params) . '#wechat_redirect';
            $resp = Yii::$app->controller->redirect($url);

            Yii::trace('Resp : ' . $resp, 'dev\*' . __METHOD__);
        } else {

            Yii::trace('Code is ' . $code, 'dev\*' . __METHOD__);

            $params = [
                'appid' => $this->owner->appID,
                'secret' => $this->owner->appSecret,
                'code' => $code,
                'grant_type' => 'authorization_code'
            ];

            $result = $this->owner->http(self::GET_ACCESS_TOKEN_URL, $params);

            if ($result) {
                return json_decode($result, true);
            } else {
                return null;
            }
        }
    }
}