<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace wom\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package wom\assets
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        "yii\web\YiiAsset",
        'yii\bootstrap\BootstrapAsset',
        //'wom\assets\BootstrapAsset'
    ];

    // 定义按需加载JS方法，注意加载顺序在最后
    public static function addScript($view, $jsFile)
    {
        $view->registerJsFile($jsFile, [
            'depends' => ['yii\bootstrap\BootstrapAsset']
        ]);
    }

    // 定义按需加载css方法，注意加载顺序在最后
    public static function addCss($view, $cssFile)
    {
        $view->registerCssFile($cssFile, [
            'depends' => 'yii\bootstrap\BootstrapAsset'
        ]);
    }
}
