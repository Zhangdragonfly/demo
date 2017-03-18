<?php

namespace weixin\api\base;

/**
 * Class AccessToken
 * @package weixin\api\base
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AccessToken extends Behavior
{
    const CACHE_KEY = 'wechat_access_token';
    const GET_ACCESS_TOKEN_WITH_CODE_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    public function getAccessToken()
    {
        if (!$accessToken = $this->owner->cache->get($this->getCacheKey())) {
            $result = $this->owner->http('https://api.weixin.qq.com/cgi-bin/token', [
                'grant_type' => 'client_credential',
                'appid' => $this->owner->appID,
                'secret' => $this->owner->appSecret,
            ]);
            if ($result) {
                $result = json_decode($result, true);
                $this->owner->cache->set($this->getCacheKey(), $result['access_token'], $result['expires_in']);
                $accessToken = $result['access_token'];
            } else {
                $accessToken = null;
            }
        }
        return $accessToken;
    }

    public function getCacheKey()
    {
        return self::CACHE_KEY . $this->owner->appID;
    }

    /**
     * 使用code来获取access token
     * @param $code
     * @return bool|mixed
     */
    public function getAccessTokenByCode($code)
    {
        $params = [
            'appid' => $this->owner->appID,
            'secret' => $this->owner->appSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
        /*
             正确返回
                {
                    "access_token":"ACCESS_TOKEN",
                    "expires_in":7200,
                    "refresh_token":"REFRESH_TOKEN",
                    "openid":"OPENID",
                    "scope":"SCOPE",
                    "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
                }
             错误返回
                {"errcode":40029,"errmsg":"invalid code"}
             */
        $result = $this->owner->http(self::GET_ACCESS_TOKEN_WITH_CODE_URL, $params);
        if ($result) {
            $result = json_decode($result, true);
            return isset($result['errcode']) ? false : $result;
        } else {
            return false;
        }
    }
}