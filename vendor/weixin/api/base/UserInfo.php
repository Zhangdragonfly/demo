<?php

namespace weixin\api\base;

use Yii;
use yii\helpers\Url;
use yii\base\Behavior;

/**
 * Class UserInfo
 * @package weixin\api\base
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class UserInfo extends Behavior
{
    const URL = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
    const ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * 静默授权方式获取用户基本信息
     * @return mixed|null
     */
    public function getBaseInfo()
    {
        $code = Yii::$app->request->get('code', null);
        if ($code == null) {
            $query = [
                'appid' => $this->owner->appID,
                'redirect_uri' => Url::current([], true),
                'response_type' => 'code',
                'scope' => 'snsapi_base',
                'state' => 'abc',
            ];

            $url = self::URL . http_build_query($query) . '#wechat_redirect';
            Yii::$app->controller->redirect($url);
        } else {
            $result = $this->owner->http(self::ACCESS_TOKEN, [
                'appid' => $this->owner->appID,
                'secret' => $this->owner->appSecret,
                'code' => $code,
                'grant_type' => 'authorization_code',
            ]);
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

    }
}