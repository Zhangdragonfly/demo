<?php

namespace weixin\api\base;

use yii\base\Behavior as BaseBehavior;

/**
 * Class Behavior
 * @package weixin\api\base
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class Behavior extends BaseBehavior
{
    /**
     * @var Wechat
     */
    public $owner;
}