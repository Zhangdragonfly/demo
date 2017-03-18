<?php

namespace weixin\api\behaviors;

use yii\base\Behavior;

/**
 * Class JsApiTicketBehavior
 * @package weixin\api\behaviors
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class JsApiTicketBehavior extends Behavior
{
    const CACHE_KEY = 'js_api_ticket';
    const URL_GET_TICKET = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';

    /**
     * 获取Js Api ticket
     * jsapi_ticket是公众号用于调用微信JS接口的临时票据。正常情况下，jsapi_ticket的有效期为7200秒，通过access_token来获取。
     * 由于获取jsapi_ticket的api调用次数非常有限，频繁刷新jsapi_ticket会导致api调用受限，影响自身业务，开发者必须在自己的服务全局缓存jsapi_ticket 。
     * @return null
     */
    public function getJsApiTicket()
    {
        if (!$ticket = $this->owner->cache->get($this->getCacheKey())) {
            $result = $this->owner->http(self::URL_GET_TICKET, [
                'access_token' => $this->owner->getAccessToken(),
                'type' => 'jsapi',
            ]);
            $result = json_decode($result, true);
            if ($result && $result['errcode'] == 0) {
                $this->owner->cache->set($this->getCacheKey(), $result['ticket'], $result['expires_in'] - 10);
                $ticket = $result['ticket'];
            } else {
                $ticket = null;
            }
        }
        return $ticket;
    }

    /**
     * The key of the value in cache
     * @return string
     */
    protected function getCacheKey()
    {
        return self::CACHE_KEY . '_' . $this->owner->appID;
    }
}