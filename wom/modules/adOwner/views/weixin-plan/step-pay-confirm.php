<?php
/**
 * 在线支付
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/28/16/ 20:51
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
$this->title = '支付页面';

$payCommitUrl = Url::to(['/site/payment/ad-owner-weixin-plan']);
$payFormUrl = Url::to(['alipay/pay-form']);

$js = <<<JS

$('.pay-way li i').click(function(){
     $(this).addClass('pay-way-current');
     $(this).parent().siblings().children('i').removeClass('pay-way-current');
     // 如果选择的是支付宝,则不需要输入"支付密码"
     if($(this).hasClass('ali-pay')){
        $('.pay-psw').hide();
     } else if($(this).hasClass('wom-wallet')){
        $('.pay-psw').show();
     }
});

// 判断余额是否充足
var total_price_pay_online = $('.total-price-pay-online').val();

$('.btn-commit').on('click', function(){
    var pay_way_current = $('.pay-way i.pay-way-current');
    var plan_uuid = $('#id-plan-uuid').val();
    var order_uuid = $('#id-order-uuid').val();
    if(pay_way_current.hasClass('ali-pay')){
        // 打开支付宝付款页面
        window.open('$payFormUrl' + '&plan=' + plan_uuid + '&order_uuid' + order_uuid);
        return false;
    } else if(pay_way_current.hasClass('wom-wallet')){
        // 检查支付密码
        var password = $.trim($('.pay-psw .password-input').val());
        if(password == ''){
            wom_alert.msg({
                    icon: "error",
                    content: "支付密码不能为空!",
                    delay_time: 1500
            });
            return false;
        }

        // TODO

        $.ajax({
                url: '$payCommitUrl',
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {plan_uuid: plan_uuid,order_uuid:order_uuid},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        window.location.href = resp.redirect_url;
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统异常!",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统异常!",
                        delay_time: 1500
                    });
                    return false;
                }
        });
    }
});
JS;
$this->registerJs($js);
?>

<input id="id-plan-uuid" type="hidden" value="<?= $plan->uuid ?>">
<input id="id-order-uuid" type="hidden" value="<?= Yii::$app->request->get('order_uuid',0) ?>">
<input id="id-pay-commit-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/pay-commit', 'plan_uuid' => $plan->uuid]) ?>">

<!-- 内容部分-->
<div class="create-step">
    <ul class="clearfix">
        <li>1.创建活动</li>
        <li>2.选择投放账号</li>
        <li>3.填写投放内容</li>
        <li class="last-step">4.提交并付款<em></em></li>
    </ul>
</div>

<div class="pay-content">
    <h2>付款确认</h2>
    <input class="total-price-pay-online" type="hidden" value="<?= empty($total_price_pay_online)? "0.00" : $total_price_pay_online ?>">
    <div class="sum-count clearfix">
        <div class="pay-count fl">
            <ol class="clearfix">
                <li><span>投放总金额：</span><i class="set-font-color"><?= empty($total_price_pay_online)? "0.00" : $total_price_pay_online ?></i>元</li>
                <li><span>本次需支付金额：</span><i class="set-font-color"><?= empty($total_price_pay_online)? "0.00" : $total_price_pay_online ?></i>元。</li>
                <li>请选择以下支付方式进行支付，如有问题请联系客服</li>
            </ol>
            <p class="set-font-color">【备注：只需支付直接投放的费用，原创类投放位置待平台审核完，之后再确认付款】</p>
        </div>
        <a class="btn btn-danger fr">账户充值</a>
    </div>

    <div class="pay-way">
        <ul class="clearfix">
            <li><i class="wom-wallet pay-way-current"></i><span>账户支付</span></li>
            <li><i class="ali-pay"></i><span>支付宝</span></li>
        </ul>
    </div>

    <div class="pay-psw clearfix">
        <div class="psw fl">
            <?php if($balanceIsAvailable == 1){ ?>
                <span>支付密码　</span>
                <input type="password" class="form-control password-input">
            <?php } else { ?>
                <span class="pay-alert"><i></i><em>账户余额不足，请</em><a href="#">充值</a></span>
            <?php } ?>
        </div>
        <p class="fr"><i class="set-font-color">！</i><span>提示：如果没有支付密码，请去<a href="#" class="set-font-color">个人中心</a>设置</a></span></p>
    </div>

    <button class="btn btn-danger btn-commit">立即支付</button>
</div>

