<?php

namespace weixin\api\base;

use Yii;

/**
 * Class BindJsPay
 * @package weixin\api\base
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class BindJsPay extends Behavior
{
    public function bindJsPay($prepayId)
    {
        $data = [
            'appId' => $this->owner->appID,
            'timeStamp' => time(),
            'nonceStr' => Yii::$app->security->generateRandomString(),
            'package' => 'prepay_id=' . $prepayId,
            'signType' => 'MD5',
        ];
        $data['paySign'] = $this->owner->paySign($data);
        return $data;
    }
}