<?php
/**
 * 第一次扫码,跳转绑定邮箱和手机号
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM  BY Manson
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use Yii;

$this->title = '绑定邮箱和手机号';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/login-regist/regist.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/login-regist/regist-step-one.js');

?>
<div class="content-wrap">

    <input type="hidden" id="id-send-mobile-captcha-url" value="<?= Url::to(['/site/account/send-mobile-captcha']) ?>">
    <input type="hidden" id="id-is-email-exist-url" value="<?= Url::to(['/site/account/is-email-exist']) ?>">
    <input type="hidden" id="id-is-phone-exist-url" value="<?= Url::to(['/site/account/is-phone-exist']) ?>">
    <input type="hidden" id="id-check-mobile-captcha-url" value="<?= Url::to(['/site/account/check-mobile-captcha']) ?>">

    <h3>绑定邮箱和手机号</h3>
    <div class="content">
        <span class="top-line"></span>
        <div class="step clearfix">
            <span class="fill">填写注册信息</span>
            <span class="complete">注册完成</span>
        </div>

        <div class="area-reg-form">
            <?php $form = ActiveForm::begin(['action' => [''], 'method' => 'post', 'class' => 'form-regist']); ?>

            <input type="hidden" name="unionid" value="<?= Yii::$app->request->get('unionid') ?>">

            <?= Html::input('hidden', 'SignUpForm[type]', 1, ['class' => 'account-type']) ?>

            <ul class="choose-identity font-600 color-main clearfix">
                <li class="ad-owner fl"><i class="choosed"></i><span>广告主</span></li>
                <li class="media-vendor fl"><i></i><span>自媒体主</span></li>
            </ul>

            <div class="input-group">
                <span class="title"><i></i>注册邮箱:</span>
                <?= Html::input('text', 'SignUpForm[email]', $accountForm->email, ['class' => 'email-regist require', 'placeholder'=>'请输入注册邮箱']) ?>
                <div class="tips">请填写完整邮箱信息</div>
                <div class="tips"  <?php if(!empty($accountForm->errors['email'])){echo "style='display:block;'";} ?>><?php if(!empty($accountForm->errors['email'])){echo $accountForm->errors['email'][0];} ?></div>
            </div>

            <div class="input-group">
                <span class="title"><i></i>输入密码:</span>
                <?= Html::input('password', 'SignUpForm[password]', $accountForm->password, ['class' => 'psd require', 'placeholder'=>'请输入6-20位由字母或数字组成的密码']) ?>
                <div class="tips">请输入密码</div>
                <div class="tips"  <?php if(!empty($accountForm->errors['password'])){echo "style='display:block;'";} ?>><?php if(!empty($accountForm->errors['password'])){echo $accountForm->errors['password'][0];} ?></div>
            </div>

            <div class="input-group">
                <span class="title"><i></i>确认密码:</span>
                <?= Html::input('password', 'SignUpForm[confirm_password]', $accountForm->confirm_password, ['class' =>'insure-psd require','placeholder'=>'请确保两次密码一致']) ?>
                <div class="tips">请确保两次密码一致</div>
                <div class="tips"  <?php if(!empty($accountForm->errors['confirm_password'])){echo "style='display:block;'";} ?>><?php if(!empty($accountForm->errors['confirm_password'])){echo $accountForm->errors['confirm_password'][0];} ?></div>
            </div>

            <div class="input-group">
                <span class="title"><i></i>手机号码:</span>
                <?= Html::input('text', 'SignUpForm[phone]', $accountForm->phone, ['class' => 'cell-phone require', 'placeholder'=>'绑定联系人手机号']) ?>
                <div class="tips">请输入手机号码 </div>
                <div class="tips"  <?php if(!empty($accountForm->errors['phone'])){echo "style='display:block;'";} ?>><?php if(!empty($accountForm->errors['phone'])){echo $accountForm->errors['phone'][0];} ?></div>
            </div>

            <div class="input-group code-insure">
                <span class="title"><i></i>手机验证码:</span>

                <?= Html::input('text', 'SignUpForm[verifyCode]', $accountForm->verifyCode, ['class' => 'verify-code require', 'placeholder'=>'请输入手机获取的验证码']) ?>

                <span class="get-code bg-main color-fff">获取手机验证码</span>
                <span class="unclick color-fff"><i>300</i> s</span>
                <div class="tips">请输入手机验证码</div>
                <div class="tips" <?php if(!empty($accountForm->errors['verifyCode'])){echo "style='display:block;'";} ?>><?php if(!empty($accountForm->errors['verifyCode'])){echo $accountForm->errors['verifyCode'][0];} ?></div>
            </div>

            <div class="agree">
                <?= Html::input('checkbox', 'SignUpForm[agree_serve]', $accountForm->agree_serve,['checked' =>'checked']) ?>
                <span>我同意<a href="javascript:void(0)">《沃米优选服务条款》</a></span>
                <span class="agree_serve">* 请确认已阅读并同意服务条款</span>
                <span class="agree_serve"  <?php if(!empty($accountForm->errors['agree_serve'])){echo "style='display:block;'";} ?>><?php if(!empty($accountForm->errors['agree_serve'])){echo $accountForm->errors['agree_serve'][0];} ?></span>
            </div>

            <a href="javascript:void(0)" class="ad-insure-regist insure-regist last-func btn bg-main color-fff">确认注册</a>

            <?php if(!empty($errMsg)){ ?>
                <span class="err_msg"><?= $errMsg ?></span>
            <?php } ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
