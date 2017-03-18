<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace wom\assets;

use yii\web\AssetBundle;

/**
 * Class BootstrapAsset
 * @package wom\assets
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class BootstrapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/dep';
    public $css = [
        'css/bootstrap-3.3.6.min.css'
    ];
    public $js = [
        'js/bootstrap-3.3.6.min.js'
    ];
    public $depends = [
        'wom\assets\JqueryAsset'
    ];
}
