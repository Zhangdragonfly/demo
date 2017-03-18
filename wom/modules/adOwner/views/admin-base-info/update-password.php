<?php
/**
 * 修改密码
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/20/16 11:29
 */
use wom\assets\AppAsset;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/dep/layer/skin/wom.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/media-vendor-user-admin/user-manage.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/media-vendor-user-admin/user-manage.js');
$this->title = '修改密码';
?>
<?php $this->beginBlock('level-1-nav'); ?>
个人设置
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
修改登录密码
<?php $this->endBlock(); ?>
<!--右侧内容-->
<div class="content content-modify-password shadow fr">
    <h2 class="color-main">修改登录密码</h2>
    <div class="prev-password column">
        <span class="info-title">原登录密码:</span><input type="password" placeholder="请输入原密码">
        <p class="tips">您输入的密码不正确</p>
    </div>
    <div class="new-password column">
        <span class="info-title">新设密码:</span><input class="input-prev-password" type="password" placeholder="请输入包含6~20位字母或数字的新密码">
        <p class="tips">密码格式不正确</p>
    </div>
    <div class="confirm-password column">
        <span class="info-title">确认新密码:</span><input type="password" placeholder="请再输入一遍新密码">
        <p class="tips">两次密码不一致</p>
    </div>
    <button class="btn-save-modify-password btn btn-danger bg-main">保存</button>
</div>


