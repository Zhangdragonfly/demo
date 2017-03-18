<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace wom\assets;

use yii\web\AssetBundle;

/**
 * Class JqueryAsset
 * @package wom\assets
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class JqueryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/dep';
    public $js = [
        'js/jquery-1.10.2.min.js'
    ];
}
