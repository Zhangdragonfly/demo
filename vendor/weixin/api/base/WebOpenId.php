<?php

namespace weixin\api\base;

use Yii;

/**
 * Class WebOpenId
 * @package weixin\api\base
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class WebOpenId extends Behavior
{
    const SESSION_KEY = 'openid';

    public function getOpenId()
    {
        if (!$openid = Yii::$app->session->get(self::SESSION_KEY)) {
            if ($info = $this->owner->getBaseUserInfo()) {
                $openid = $info['openid'];
                Yii::$app->session->set(self::SESSION_KEY, $openid);
            } else {
                $openid = null;
            }
        }
        return $openid;
    }
}