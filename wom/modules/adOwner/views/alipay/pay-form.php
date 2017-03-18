<?php
/**
 * 支付表单
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/18/16/ 18:29
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/plan_media_common.css');
AppAsset::addCss($this, '@web/src/css/weixin/plan_order_pay.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
$js = <<<JS
$(".alipay").submit();
JS;
$this->registerJs($js);

$this->title = '支付页面';
?>
<form class="alipay" hidden action="<?= \yii\helpers\Url::to(['alipay/pay']) ?>" method="post">
    <div class="way">
        <p class="title">请填写充值金额，并选择相应的支付方式进行支付</p>
        <div class="content">
            <div class="payment payment-money">
                <div class="account-password">
                    <span class="text">充值金额：</span>

                    <input type="hidden" name="WIDout_trade_no" id="out_trade_no" value="">
                    <input type="hidden" name="WIDsubject" value="广告主充值">
                    <input type="text" name="WIDtotal_fee" value="<?= $amount ?>">
                    <input type="hidden" name="WIDbody" value="">
                    <input type="hidden" name="plan_uuid" value="<?= $plan_uuid ?>">
                    <input type="hidden" name="order_uuid" value="<?= $order_uuid ?>">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken();?>" />
                </div>
            </div>
            <div class="type" style="border:0;">
                <ul>

                    <li>
                        <span class="option"><i class="on" data-value="1"></i></span>
                        <div class="icon"></div>
                    </li>
                    <li style="display: none">
                        <span class="option"><i data-value="2"></i></span>
                        <div class="icon"><img src="../image/pay-icon-2.png"/></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="btn-box">
        <div class="btn submit bg-main color-fff">立即支付</div>
    </div>
</form>
