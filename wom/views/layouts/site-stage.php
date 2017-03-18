<?php
/**
 * 登录/注册/忘记密码/投放流程
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/9/16 10:31 AM
 */

use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use wom\helpers\Constants;
use common\models\UserAccount;

AppAsset::register($this);

$loginStatus = 0;
if (Yii::$app->user->isGuest) {
    $loginStatus = 0;
}
$session = Yii::$app->session;
if (!$session->isActive) {
    $loginStatus = 0;
} else {
    $accountType = $session->get(Constants::SESSION_ACCOUNT_TYPE);
    if ($accountType == UserAccount::ACCOUNT_TYPE_AD_OWNER) {
        // 广告主登录
        $loginStatus = 1;
    } else if ($accountType == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR) {
        // 媒体主登录
        $loginStatus = 2;
    } else {
        $loginStatus = 0;
    }
}

$site_stage = <<<JS
    var login_status = '$loginStatus';

    function renderLoginAccountInfo(account_type){
        var getLoginAccountInfoUrl = $('input#id-get-login-account-info-url').val();
        $.ajax({
                url: getLoginAccountInfoUrl,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {account_type: account_type},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        if(resp.account_type == 1){
                            $('.login-success.ad-owner .user-name').text(resp.user_name);
                            $('.login-success.ad-owner .total-available-balance').text(resp.total_available_balance);
                            $('.login-success.ad-owner .total-frozen-amount').text(resp.total_frozen_amount);
                        } else {
                            $('.login-success.media-vendor .user-name').text(resp.user_name);
                            $('.login-success.media-vendor .total-available-balance').text(resp.total_available_balance);
                        }
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统出错",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错",
                        delay_time: 1500
                    });
                    return false;
                }
        });
    }
    if(login_status == 0){
        // 未登录
        $('.header .login-success.ad-owner').addClass('hidden');
        $('.header .login-success.media-vendor').addClass('hidden');
        $('.header .login-cancel').removeClass('hidden');
    } else if(login_status == 1){
        // 广告主登录
        $('.header .login-cancel').addClass('hidden');
        $('.header .login-success.media-vendor').addClass('hidden');
        $('.header .login-success.ad-owner').removeClass('hidden');

        renderLoginAccountInfo(1);
    } else if(login_status == 2){
        // 媒体主登录
        $('.header .login-cancel').addClass('hidden');
        $('.header .login-success.ad-owner').addClass('hidden');
        $('.header .login-success.media-vendor').removeClass('hidden');

        renderLoginAccountInfo(2);
    }

    // 退出
    $('.header .logout').on('click', function(){
        var logout_url = $('#id-logout-url').val();
        window.location.href = logout_url;
    });

    // 广告主个人中心首页
    $('.login-success.ad-owner .username').on('click', function(){
        var ad_owner_admin_index_url = $('input#id-ad-owner-admin-index-url').val();
        window.location.href = ad_owner_admin_index_url;
    });

    // 媒体主个人中心首页
    $('.login-success.media-vendor .username').on('click', function(){
        var media_vendor_admin_index_url = $('input#id-media-vendor-admin-index-url').val();
        window.location.href = media_vendor_admin_index_url;
    });
JS;
$this->registerJs($site_stage);

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="zh-CN">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <meta name="keywords" content="谦玛网络，谦玛互动，沃米优选，上海谦玛，谦玛广告">
        <meta name="description" content="微信公众号推广,微信朋友圈推广，微博推广,软文发稿,媒体推广,自媒体营销,广告投放,网络广告,APP广告">
        <link rel="shortcut icon" href="../src/images/icon.png">
        <?php $this->head() ?>
        <script>
            var _hmt = _hmt || [];
            (function() {
                var hm = document.createElement("script");
                hm.src = "https://hm.baidu.com/hm.js?9d21dbb3c4c10177842bc6fca694fc82";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();
        </script>
    </head>

    <body>
    <?php $this->beginBody() ?>

    <input id="id-logout-url" type="hidden" value="<?= Url::to(['/site/account/logout']) ?>">
    <input id="id-weixin-media-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-media-lib/list']) ?>">
    <input id="id-get-login-account-info-url" type="hidden" value="<?= Url::to(['/site/account/get-login-account-info']) ?>">
    <input id="id-ad-owner-admin-index-url" type="hidden" value="<?= Url::to(['/ad-owner/admin/index']) ?>">
    <input id="id-media-vendor-admin-index-url" type="hidden" value="<?= Url::to(['/media-vendor/admin/index']) ?>">

    <!-- 头部 -->
    <div class="header">
        <div class="head-top-wrap">

            <!-- 未登录 -->
            <div class="head-top clearfix login-cancel">
                <h3 class="welcome">欢迎来到沃米优选! 自媒体价值发现者</h3>
                <div class="enter clearfix">
                    <a href="<?= Url::to(['/site/account/login', 'type' => 1]) ?>" class="entrance">广告主入口</a>
                    <a href="<?= Url::to(['/site/account/login', 'type' => 2]) ?>" class="entrance">媒体主入口</a>
                    <a class="help" href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a>
                    <span class="tel">400-878-9551</span><i></i>
                </div>
            </div>

            <!-- 广告主登录 -->
            <div class="head-top clearfix login-success ad-owner hidden">
                <h3 class="welcome">欢迎来到沃米优选! 自媒体价值发现者</h3>
                <div class="enter clearfix">
                    <ul class="clearfix">
                        <li><a class="username color-main" href="javascript:;">欢迎您! <em class="user-name"></em></a></li>
                        <li><a class="my-lib color-main" href="<?= Url::to(['/ad-owner/admin-weixin-media-lib/list']) ?>">我的媒体库</a></li>
                        <li>可用金额: <span class="total-available-balance">0</span></li>
                        <li>冻结金额: <span class="total-frozen-amount">0</span></li>
                        <li><a href="<?= Url::to(['/ad-owner/admin-fin-manage/top-up']) ?>">立即充值</a></li>
                        <li><a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a></li>
                        <li><a href="javascript:;" class="logout">退出</a></li>
                    </ul>
                </div>
            </div>

            <!-- 媒体主登录 -->
            <div class="head-top clearfix login-success media-vendor hidden">
                <h3 class="welcome">欢迎来到沃米优选! 自媒体价值发现者</h3>
                <div class="enter clearfix">
                    <ul class="clearfix">
                        <li><a class="username color-main" href="javascript:;">欢迎您! <em class="user-name"></em></a></li>
                        <li>账户金额: <span class="total-available-balance">0</span></li>
                        <li><a href="javascript:;">立即充值</a></li>
                        <li><a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a></li>
                        <li><a href="javascript:;" class="logout">退出</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="head-bottom">
            <h1><a href="<?= Url::home() ?>"><img src="../src/images/logo.png" alt="沃米优选"></a></h1>
            <div class="nav">
                <ul class="clearfix">
                    <li><a class="" href="<?= Url::home() ?>">首页</a></li>
                    <li><a href="<?= Url::to(['/weixin/media/list']) ?>">微信营销</a></li>
                    <li><a href="<?= Url::to(['/video/media/list']) ?>">视频网红</a></li>
                    <li><a href="<?= Url::to(['/weibo/media/list']) ?>">新浪微博</a></li>
                    <li><a href="<?= Url::to(['/site/solution/index']) ?>">解决方案</a></li>
                    <li><a href="<?= Url::to(['/site/case/index']) ?>">案例中心</a></li>
                    <li class="about-us"><a href="<?= Url::to(['/site/about-us/index']) ?>">关于我们</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 主要内容部分 -->
    <?= $content; ?>

    <!--侧边栏联系方式-->
    <div class="side-bar">
        <ul>
            <li class="tel">
                <div class="tel-con detail">
                    <span></span>
                    <p>客服电话</p>
                    <p>400-878-9551</p>
                </div>
                <i></i>
            </li>
            <li class="qq">
                <div class="qq-con detail">
                    <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=2&uin=800187006" target="_blank">
                        <span></span>
                        <p>客服QQ</p>
                        <p>800187006</p>
                        <p>点击直接与客服沟通</p>
                    </a>
                </div>
                <i></i>
            </li>
            <li class="weixin">
                <div class="weixin-con detail">
                    <p>微信扫一扫</p>
                    <span></span>
                </div>
                <i></i>
            </li>
            <li class="top">
                <i></i>
            </li>
        </ul>
    </div>

    <!--底部-->
    <div class="footer-wrap">
        <div class="footer font-12">
            <ul>
                <li class="about-us clearfix"><span></span><a href="<?= Url::to(['/site/solution/index']) ?>" target="_blank">解决方案</a><i></i></li>
                <li class="contact-us clearfix"><span></span><a href="<?= Url::to(['/site/case/index']) ?>" target="_blank">案例中心</a><i></i></li>
                <li class="service clearfix"><span></span><a href="<?= Url::to(['/site/about-us/index']) ?>" target="_blank">关于我们</a><i></i></li>
                <li class="help clearfix"><span></span><a href="<?= Url::to(['/site/help-center/index']) ?>" target="_blank">帮助中心</a><i></i></li>
            </ul>
            <div class="footer-bot">
                <p><span> Copyright 2016 &nbsp; www.51wom.com &nbsp; All Rights Reserved</span> 沃米优选:自媒体价值发现者 版权所有
                    沪ICP备11022867号-1</p>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>