<?php
/**
 * 设置支付密码
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/20/16 15:26
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/dep/layer/skin/wom.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/user-manage.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/user-manage.js');
$this->title = '修改密码';
?>
<?php $this->beginBlock('level-1-nav'); ?>
个人设置
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
新设支付密码
<?php $this->endBlock(); ?>
<!--右侧内容-->
<div class="content content-set-paypassword shadow fr">
    <!-- 验证手机号 -->
    <input type="hidden" id="is-phone-exist-url" value="<?= Url::to(['/site/account/is-phone-exist']) ?>">
    <!-- 获取验证码 -->
    <input type="hidden" id="get-verify-code-url" value="<?= Url::to(['/site/account/send-mobile-verify-code']) ?>">
    <h2 class="color-main">新设支付密码</h2>
    <div class="set-paypassword column">
        <span class="info-title">新设支付密码:</span>
        <input type="password" placeholder="请输入包含6~20位字母或数字的新密码">
        <p class="tips">请输入密码</p>
    </div>
    <div class="confirm-paypassword column">
        <span class="info-title">确认密码:</span>
        <input type="password" placeholder="请再输入一遍密码">
        <p class="tips">两次密码不一致</p>
    </div>
    <div class="phone-number column">
        <span class="info-title">手机号:</span>
        <input class="input-phone-number" type="text" placeholder="输入绑定的手机号">
        <div class="tips">请输入手机号码</div>
    </div>
    <div class="code-insure column">
        <span class="info-title">验证码:</span>
        <input class="verify-code" type="text" placeholder="请输入验证码">
        <span class="get-code bg-main color-fff">获取手机验证码</span>
        <span class="unclick color-fff fr"><i>60</i> s</span>
        <p class="tips">请输入手机验证码</p>
    </div>
    <button class="btn-save-set-paypassword btn btn-danger bg-main">保存</button>
</div>

