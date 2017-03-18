<?php
/**
 * 找回密码第二步
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 2016/11/17 15:06
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

$this->title = '找回密码';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/login-regist/regist.css');

AppAsset::addScript($this, '@web/src/js/login-regist/forget-psd.js');
?>

<input type="hidden" id="id-forget-password-url" value="<?= Url::to(['/site/account/forget-password', 'step' => '_step_', 'cell_phone' => '_cell_phone_']) ?>">

<div class="content-wrap">
    <input type="hidden" name="cell_phone" value="<?=yii::$app->request->get('cell_phone')?>">
    <h3>找回密码</h3>
    <div class="content">
        <span class="top-line"></span>
        <div class="step clearfix">
            <span class="fill">输入绑定手机号</span>
            <span class="complete psd-bg-complete">完成</span>
        </div>
        <form action="post">
            <div class="input-group">
                <span class="title"><i></i>重置密码:</span>
                <input class="psd" type="password" placeholder="请输入6-20位由字母或数字组成的密码">
                <div class="tips">请输入正确的密码</div>
            </div>
            <div class="input-group">
                <span class="title"><i></i>确认密码:</span>
                <input class="insure-psd" type="password" placeholder="请确保两次密码一致">
                <div class="tips">请确保两次密码一致</div>
            </div>
            <a href="javascript:void(0)" class="affirm-change last-func btn bg-main color-fff">确认</a>
        </form>
    </div>
</div>

