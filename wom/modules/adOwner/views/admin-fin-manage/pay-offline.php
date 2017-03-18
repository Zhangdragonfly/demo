<?php
/**
 * 线下支付
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/21/16 15:33
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
$this->title = '线下支付提示页面';
?>
<?php $this->beginBlock('level-1-nav'); ?>
    财务管理
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
    充值
<?php $this->endBlock(); ?>
<div class="content-wrap clearfix shadow">
    <div class="pay-detail-show">
        <div class="service-img fl"></div>
        <div class="pay-detail-info fl">
            <ul>
                <li>
                    <span>转账银行 :</span>
                    <span>民生银行</span>
                </li>
                <li>
                    <span>开户银行 :</span>
                    <span>民生银行上海普陀分行</span>
                </li>
                <li>
                    <span>银行转账 :</span>
                    <span>6227623217625637625</span>
                </li>
                <li>
                    <span>公司名称 :</span>
                    <span>上海谦玛网络科技股份有限公司</span>
                </li>
                <li class="color-main">注 : 转账成功之后,请及时与客户联系确认, 联系电话 : 4008789551</li>
            </ul>
        </div>
    </div>
    <button class="return btn btn-danger bg-main" onclick="javascript:history.go(-1);">返回</button>
</div>