<?php
/**
 * 注册第二步
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM  BY Manson
 */
use wom\assets\AppAsset;
use common\models\UserAccount;
use yii\helpers\Url;

if($accountType == UserAccount::ACCOUNT_TYPE_AD_OWNER){
    $accountRoleName = "广告主";
}
if($accountType == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR){
    $accountRoleName = "媒体主";
}
$this->title = '注册成为' . $accountRoleName;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/login-regist/regist.css');

//$redirectUrl = Yii::$app->params['domain']['home']. "/index.php?r=site/account/regist-step-two";
//$redirectUrl = urlencode($redirectUrl);
AppAsset::addScript($this, 'http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js');
$boundJs = <<<JS

    //绑定
   //var obj = new WxLogin({
   //  id:"bound_weixin_container",
   //  appid: "wxc73f763daf51ab73",
   //  scope: "snsapi_login",
   //  redirect_uri: '',
   //  state: "state",
   //  style: "",
   //  href: "https://o9z65knq9.qnssl.com/common/css/weixin-login-qrcode.css"
   //});
   var redirect_url = $('input#bound-weixin-uuid-url').val();
    var obj = new WxLogin({
     id:"bound_weixin_container",
     appid: "wxc73f763daf51ab73",// wxb3e51573cdbcd9c0
     scope: "snsapi_login",
     redirect_uri: redirect_url,
     state: "state",
     style: "",
     href: "https://o9z65knq9.qnssl.com/common/css/weixin-login-qrcode.css"
   });
   $(".panelContent .impowerBox .qrcode").attr("width","134px");

JS;
$this->registerJs($boundJs);
$home = 'http://www.51wom.com';// http://www.yeexiao.com
?>
<div class="content-wrap">
    <input type="hidden" id="bound-weixin-uuid-url" value="<?= urlencode($home.Url::to(['/site/account/bound-weixin-uuid','email' => $accountForm['email']])) ?>">
    <h3>注册成为<?=$accountRoleName?> </h3>
    <div class="content">
        <span class="top-line"></span>
        <div class="step clearfix">
            <span class="fill">填写注册信息</span>
            <span class="complete has-complete">注册完成</span>
        </div>
        <div class="con-detail">
            <div class="detail-top clearfix">
                <div class="left-img fl"></div>
                <div class="right-con fl">
                    <p>您已成功完成<?= $accountRoleName ?>注册</p>
                    <p>您注册的邮箱为 : <span class="your-email"><?= $accountForm['email'] ?></span></p>
                    <p>
                        您可继续操作 :
                    <?php if($accountType == UserAccount::ACCOUNT_TYPE_AD_OWNER){ ?>
                        <a href="<?= Url::to(['/ad-owner/admin-base-info/index']) ?>">完善资料</a>
                    <?php } else if($accountType == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR){ ?>
                        <a href="<?= Url::to(['/media-vendor/admin-base-info/index']) ?>">完善资料</a>
                        <a href="javascript:void(0);" style="display: none;">入驻账号</a>
                    <?php } ?>

                    </p>
                </div>
            </div>
            <div class="detail-bot clearfix">
                <div class="left-img fl">
                    <div class="weixin-qr-code" id="bound_weixin_container"></div>
                </div>
                <div class="right fl">
                    <span>扫码即可绑定微信</span>
                    <span>绑定后可通过扫码登录，更快捷，更安全哦~</span>
                </div>
            </div>
        </div>
    </div>
</div>
