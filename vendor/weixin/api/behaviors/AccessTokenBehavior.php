<?php
/**
 * 获取access token
 *
 * access_token是公众号的全局唯一票据，公众号调用各接口时都需使用access_token。
 * 开发者需要进行妥善保存。access_token的存储至少要保留512个字符空间。access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效。
 *
 * http://mp.weixin.qq.com/wiki/15/54ce45d8d30b6bf6758f68d2e95bc627.html
 */

namespace weixin\api\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Class AccessTokenBehavior
 * @package weixin\api\behaviors
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AccessTokenBehavior extends Behavior
{
    const CACHE_KEY = 'wechat_access_token';
    const URL_GET_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token';

    /**
     * 获取access_token
     * （有效期7200秒，开发者必须在自己的服务全局缓存access_token）
     * @return null
     */
    public function getAccessToken()
    {
        if (!$accessToken = $this->owner->cache->get($this->getCacheKey())) {
            $result = $this->owner->http(self::URL_GET_TOKEN, [
                'grant_type' => 'client_credential',
                'appid' => $this->owner->appID,
                'secret' => $this->owner->appSecret,
            ]);
            if ($result) {
                $result = json_decode($result, true);
                $this->owner->cache->set($this->getCacheKey(), $result['access_token'], $result['expires_in'] - 10);
                $accessToken = $result['access_token'];
            } else {
                $accessToken = null;
            }
        }
        return $accessToken;
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return self::CACHE_KEY . '_' . $this->owner->appID;
    }
}