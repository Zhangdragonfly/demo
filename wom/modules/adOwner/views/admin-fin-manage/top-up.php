<?php
/**
 * 充值
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/21/16 10:45
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/pay.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/pay.js');
$this->title = '充值';
?>
<?php $this->beginBlock('level-1-nav'); ?>
    财务管理
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
    充值
<?php $this->endBlock(); ?>
<div class="content-wrap clearfix shadow">
    <input type="hidden" id="pay-form-url" value="<?= Url::to(['alipay/pay-form']) ?>">
    <input type="hidden" id="pay-offline-url" value="<?= Url::to(['admin-fin-manage/pay-offline']) ?>">
    <div class="pay-account">
        <span class="column-title">请输入充值金额 :</span>
        <input class="input-pay-account" type="text">
        <i class="tips color-main"></i>
    </div>
    <div class="pay-way clearfix">
        <span class="column-title fl">请选择支付方式 :</span>
        <ul class="clearfix fl">
            <li><i class="choosed"></i><span>支付宝</span></li>
            <li style="display: none"><i></i><span>微信支付</span></li>
            <li><i ></i><span>线下支付</span></li>
        </ul>
    </div>
    <button class="next-step btn btn-danger bg-main">下一步</button>
</div>
