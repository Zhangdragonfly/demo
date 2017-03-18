<?php
/**
 * 找回密码第一步
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 2016/11/17 15:00
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

<input type="hidden" id="id-is-phone-exist-url" value="<?= Url::to(['/site/account/is-phone-exist']) ?>">
<input type="hidden" id="id-send-mobile-captcha-url" value="<?= Url::to(['/site/account/send-mobile-verify-code']) ?>">
<input type="hidden" id="id-forget-password-url" value="<?= Url::to(['/site/account/forget-password', 'step' => '_step_', 'cell_phone' => '_cell_phone_']) ?>">

<div class="content-wrap">
    <h3>忘记密码</h3>
    <div class="content">
        <span class="top-line"></span>
        <div class="step clearfix">
            <span class="fill">输入绑定手机号</span>
            <span class="complete">完成</span>
        </div>
        <form action="post">
            <div class="input-group">
                <span class="title"><i></i>手机号码:</span>
                <input class="cell-phone" type="text" placeholder="请输入绑定的手机号码">
                <div class="tips">请输入手机号码</div>
            </div>
            <div class="input-group code-insure">
                <span class="title"><i></i>手机验证码:</span>
                <input class="verify-code" type="text" placeholder="请输入手机获取的验证码">
                <span class="get-code bg-main color-fff" data-url="<?php echo Yii::$app->urlManager->createUrl(array('site/account/send-mobile-captcha'));?>">获取手机验证码</span>
                <span class="unclick color-fff"> <i>60</i> s </span>
                <div class="tips">请输入正确的手机验证码</div>
            </div>
            <a href="javascript:void(0)" class="next-step last-func btn bg-main color-fff">下一步</a>
        </form>
    </div>
</div>
