<?php
/**
 * 找回密码第二步
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 2016/11/17 15:25
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

$this->title = '找回密码';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/login-regist/regist.css');

AppAsset::addScript($this, '@web/src/js/login-regist/forget-psd.js');
?>

<input type="hidden" id="id-login-url" value="<?= Url::to(['/site/account/login']) ?>">

<div class="content-wrap">
    <h3>找回密码</h3>
    <div class="content">
        <span class="top-line"></span>
        <div class="step clearfix">
            <span class="fill">输入绑定手机号</span>
            <span class="complete psd-bg-complete">完成</span>
        </div>
        <div class="complete clearfix">
            <div class="img fl"></div>
            <div class="con fl">
                <h3>您的密码重置成功</h3>
                <span class="kill-time color-main"><i>5</i> s 后自动跳转到登录页面</span>
                <p class="back">您可继续操作: <a class="color-main" href="<?= Url::to(['/site/account/login']) ?>">返回登录页面</a></p>
            </div>
        </div>
    </div>
</div>

<?php
$Js = <<<JS

    var login_url = $('input#id-login-url').val();
    //(找回密码完成)倒计时5秒跳转
    $(function(){
        var whole_time = 5,active_time = 1;
        function update() {
            if (active_time == whole_time) {
                clearInterval(timer);
                window.location.href=login_url;
                return false;
            } else {
                var surplus_time = whole_time - active_time;
                $(".kill-time").children("i").text(surplus_time);
            }
            active_time++;
        }
        timer = setInterval(update, 1000);
    })
JS;
$this->registerJs($Js);
?>
