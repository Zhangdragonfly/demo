<?php

namespace weixin\api\base;

/**
 * Class JsApiTicket
 * @package weixin\api\base
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class JsApiTicket extends Behavior
{
    const CACHE_KEY = 'js_api_ticket';
    const URL_GET_TICKET = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';

    /**
     *
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
                $this->owner->cache->set($this->getCacheKey(), $result['ticket'], $result['expires_in']);
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