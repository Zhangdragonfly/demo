<?php
/**
 * 登录
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM  BY Manson
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\UserAccount;

if($accountType == UserAccount::ACCOUNT_TYPE_AD_OWNER){
    $accountRoleName = "广告主";
}
if($accountType == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR){
    $accountRoleName = "媒体主";
}
$this->title =  $accountRoleName.'登录';
$home = 'http://www.51wom.com';
AppAsset::register($this);

AppAsset::addCss($this, '@web/src/css/login-regist/login.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addScript($this, '@web/src/js/login-regist/login.js');
AppAsset::addScript($this, 'http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js');
?>
<div class="content">
    <div class="ad-login-con">
        <div class="login-top clearfix">
            <span></span>
            <h3><?=$accountRoleName?>登录</h3>
        </div>
        <div class="login-bottom clearfix">
            <input type="hidden" id="login-by-qr-code-url" value="<?= urlencode($home.Url::to(['qr-code-login'])) ?>">
            <div class="weixin-login">
                <div class="weixin-login-top clearfix"><i></i><h4>微信登录</h4></div>
                <div class="weixin-qr-code" id="ad_owner_login_container"></div>
                <p>请使用微信扫描二维码登录<span class="womi">沃米优选</span></p>
            </div>
            <div class="line"></div>
            <?php $form = ActiveForm::begin(['action' => ['/site/account/login'], 'method'=>'post']); ?>
            <div class="account-login">
                <div class="input-group">
                    <span>用户名:</span>
                    <?= Html::input('text', 'LoginForm[username]', $loginForm->username, ['placeholder'=>'请输入邮箱']) ?>
                    <?= Html::hiddenInput('LoginForm[type]', $accountType) ?>
                    <div class="insure-name insure" hidden <?php if(!empty($loginForm->errors['username']) || !empty($errMsg)){echo "style='display:block;'";} ?>>
                        <?php if(!empty($loginForm->errors['username'])){echo $loginForm->errors['username'][0];}if(!empty($errMsg)){print_r($errMsg);} ?>
                    </div>
                </div>
                <div class="input-group">
                    <span>密码:</span>
                    <?= Html::input('password', 'LoginForm[password]', $loginForm->password,['placeholder'=>'请输入密码']) ?>
                    <div class="insure-name insure" hidden <?php if(!empty($loginForm->errors['password'])){echo "style='display:block;'";} ?>>
                        <?php if(!empty($loginForm->errors['password'])){echo $loginForm->errors['password'][0];} ?>
                    </div>
<!--                    <div class="insure-psd insure" hidden>请输入正确的密码</div>-->
                </div>
                <div class="remember-forget">
                    <?= Html::input('checkbox', 'LoginForm[rememberMe]', $loginForm->rememberMe) ?>记住用户名
                    <a href="<?= Url::to(['/site/account/forget-password'])?>" target="_blank">忘记密码?</a>
                </div>
                <button class="login btn btn-danger bg-main">登 录</button>
                <div class="regist clearfix">
                    <?php if($accountType == UserAccount::ACCOUNT_TYPE_AD_OWNER){
                        echo '<a class="fl" href="' . Url::to(['/site/account/login', 'type' => UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR]) . '">媒体主登录</a>';
                    }else{
                        echo '<a class="fl" href="' . Url::to(['/site/account/login', 'type' => UserAccount::ACCOUNT_TYPE_AD_OWNER]) . '">广告主登录</a>';
                    } ?>
                        <span class="fr">
                            <span class="no-account font-12">没有账号？</span>
                            <?php if($accountType == UserAccount::ACCOUNT_TYPE_AD_OWNER){
                                echo '<a class="regist-now color-main" href="' . Url::to(['/site/account/register', 'type' => UserAccount::ACCOUNT_TYPE_AD_OWNER]) . '">立即注册</a>';
                            }else{
                                echo '<a class="regist-now color-main" href="' . Url::to(['/site/account/register','type' => UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR]) . '">立即注册</a>';
                            } ?>
                        </span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
