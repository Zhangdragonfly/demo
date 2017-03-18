<?php
/**
 * 基本信息
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/19/16 15:55
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

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
$this->title = '基本资料';
?>
<?php $this->beginBlock('level-1-nav'); ?>
个人设置
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
基础资料
<?php $this->endBlock(); ?>
<!--右侧内容-->
<div class="content shadow fr">
    <div class="info clearfix">
        <!-- 验证手机号 -->
        <input type="hidden" id="is-phone-exist-url" value="<?= Url::to(['/site/account/is-phone-exist']) ?>">
        <!-- 获取验证码 -->
        <input type="hidden" id="get-verify-code-url" value="<?= Url::to(['/site/account/send-mobile-verify-code']) ?>">
        <!-- 更改绑定手机号 -->
        <input type="hidden" id="update-phone-url" value="<?= Url::to(['update-phone']) ?>">
        <!-- 获取账号信息 -->
        <input type="hidden" id="id-get-login-account-info-url" value="<?= Url::to(['/site/account/get-login-account-info']) ?>">
        <div class="basic-info fl">
            <h3>基础信息</h3>
            <div class="nickname column">
                <span class="info-title">昵称:</span><input type="text" value="<?= $info['nickname'] ?>">
            </div>
            <div class="email column">
                <span class="info-title">注册邮箱:</span><span class="regist-email"><?= $info['email'] ?></span>
            </div>
            <div class="regist-time column">
                <span class="info-title">注册时间:</span><span class="regist-time"><?= date('Y.m.d H:i',$info['create_time']) ?></span>
            </div>
            <div class="location column">
                <span class="info-title">所在地:</span><input type="text" value="<?= $info['location'] ?>">
            </div>
            <div class="contact-person column">
                <span class="info-title">联系人:</span><input type="text" value="<?= $info['contact_name'] ?>">
            </div>
            <div class="phone-number column">
                <span class="info-title">手机:</span><span class="regist-email"><?= $info['phone'] ?></span><span class="modify-phone-number color-main" data-target="#modify-phone-number" data-toggle="modal">修改</span>
            </div>
            <div class="weixin column">
                <span class="info-title">微信号:</span><input type="text" value="<?= $info['weixin'] ?>">
            </div>
            <div class="qq column">
                <span class="info-title">qq:</span><input type="text" value="<?= $info['qq'] ?>">
            </div>
        </div>
        <div class="enterprise-info fl">
            <h3>企业信息</h3>
            <div class="company-name column">
                <span class="info-title">公司名称:</span><input type="text" value="<?= $info['comp_name'] ?>">
            </div>
            <div class="company-site column">
                <span class="info-title">公司网址:</span><input type="text" value="<?= $info['comp_website'] ?>">
            </div>
            <div class="company-address column">
                <span class="info-title">公司地址:</span><input type="text" value="<?= $info['comp_address'] ?>">
            </div>
            <div class="company-synopsis column clearfix">
                <span class="info-title fl">公司简介:</span><textarea class="fl" name="" id="" cols="30" rows="10" maxlength="120" value="<?= $info['comp_desc'] ?>"></textarea>
            </div>
            <p class="tips">您还可输入 <em class="color-main">120</em> 个字</p>
        </div>
    </div>
    <button class="btn-save-basic-data btn btn-danger bg-main">保存</button>
</div>
<!--修改手机号码modal框-->
<div id="modify-phone-number" class="modal modify-phone-number modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="color-main font-600">绑定新手机</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="new-phone-number column">
                    <span class="info-title">输入新手机号:</span>
                    <input class="input-phone-number" type="text" placeholder="输入新手机号码">
                    <div class="tips">请输入手机号码</div>
                </div>
                <div class="code-insure column">
                    <span class="info-title">验证码:</span>
                    <input class="verify-code" type="text" placeholder="请输入获取的验证码">
                    <span class="get-code bg-main color-fff">获取手机验证码</span>
                    <span class="unclick color-fff"><i>60</i> s</span>
                    <div class="tips">请输入手机验证码</div>
                </div>
                <button class="btn-save-modify-phone-number btn btn-danger bg-main">保存</button>
            </div>
        </div>
    </div>
</div>

