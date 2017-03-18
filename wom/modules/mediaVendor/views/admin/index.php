<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/8/16 9:10 AM
 */

use wom\assets\AppAsset;

AppAsset::register($this);

AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');

$this->title = '媒体主个人中心首页';
?>