<?php
/**
 * 付款成功
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/21/16 15:16
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/pay.success.css');

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
    立即充值
<?php $this->endBlock(); ?>
<div class="content shadow">
    <div class="in-content clearfix">
        <span class="pic-l fl"></span>
        <div class="continue-operate fl">
            <h3>恭喜你，充值成功</h3>
            <ul class="clearfix">
                <li>您可继续操作：</li>
                <li><a href="<?= Url::to(['admin-weixin-order/list']) ?>">进入订单管理</a></li>
                <li><a href="<?= Url::to(['admin-fin-manage/weixin-trade-list']) ?>">进入财务管理</a></li>
            </ul>
        </div>
    </div>
</div>