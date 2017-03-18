<?php
/**
 * 资源列表页/详情页(待)/解决方案/案例中心/关于我们/帮助中心等的layout
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:24 PM
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

$media_stage = <<<JS
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
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    console.log('renderLoginAccountInfo');
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
        $('.login-success.ad-owner').addClass('hidden');
        $('.login-success.media-vendor').addClass('hidden');
        $('.login-cancel').removeClass('hidden');
    } else if(login_status == 1){
        // 广告主登录
        $('.login-cancel').addClass('hidden');
        $('.login-success.media-vendor').addClass('hidden');
        $('.login-success.ad-owner').removeClass('hidden');

        renderLoginAccountInfo(1);
    } else if(login_status == 2){
        // 媒体主登录
        $('.login-cancel').addClass('hidden');
        $('.login-success.ad-owner').addClass('hidden');
        $('.login-success.media-vendor').removeClass('hidden');

        renderLoginAccountInfo(2);
    }

    // 退出
    $('.login-success .logout').on('click', function(){
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
$this->registerJs($media_stage);

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
<input id="id-get-login-account-info-url" type="hidden" value="<?= Url::to(['/site/account/get-login-account-info']) ?>">
<input id="id-ad-owner-admin-index-url" type="hidden" value="<?= Url::to(['/ad-owner/admin/index']) ?>">
<input id="id-media-vendor-admin-index-url" type="hidden" value="<?= Url::to(['/media-vendor/admin/index']) ?>">
<input id="id-weixin-media-list-url" type="hidden" value="<?= Url::to(['/weixin/media/list']) ?>">
<input id="id-weibo-media-list-url" type="hidden" value="<?= Url::to(['/weibo/media/list']) ?>">
<input id="id-video-media-list-url" type="hidden" value="<?= Url::to(['/video/media/list']) ?>">

<!-- 未登录 -->
<div class="top clearfix login-cancel hidden">
    <span class="fl">欢迎来到沃米优选! 自媒体价值发现者</span>
    <div class="fr">
        <a href="<?= Url::to(['/site/account/login', 'type' => 1]) ?>" class="btn btn-danger">广告主入口</a>
        <a href="<?= Url::to(['/site/account/login', 'type' => 2]) ?>" class="btn btn-danger">媒体主入口</a>
        <a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a>
    </div>
</div>

<!-- 广告主登录 -->
<div class="head-top clearfix login-success ad-owner hidden">
    <h3 class="welcome fl">欢迎来到沃米优选! 自媒体价值发现者</h3>
    <div class="enter fr clearfix">
        <ul class="clearfix">
            <li><a class="username" href="javascript:;">欢迎您! <em class="user-name"></em></a></li>
            <li><a class="my-lib color-main" href="<?= Url::to(['/ad-owner/admin-weixin-media-lib/list']) ?>">我的媒体库</a>
            </li>
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
    <h3 class="welcome fl">欢迎来到沃米优选! 自媒体价值发现者</h3>
    <div class="enter fr clearfix">
        <ul class="clearfix">
            <li><a class="username" href="javascript:;">欢迎您! <em class="user-name"></em></a></li>
            <li>可用金额: <span class="total-available-balance">0</span></li>
            <li><a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a></li>
            <li><a href="javascript:;" class="logout">退出</a></li>
        </ul>
    </div>
</div>

<!-- 头部开始-->
<div class="header clearfix">
    <h1 class="fl"><a href="<?= Url::home() ?>">沃米优选</a></h1>
    <div class="search-key fl clearfix">
        <div class="dropdown fl">
            <div type="button" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span>微信公众号</span>
                <span class="caret"></span>
            </div>
            <ul class="dropdown-menu dropdown-search-type" role="menu" aria-labelledby="dLabel">
                <li class="weixin-search" data-type="weixin">微信公众号</li>
                <li class="video-search" data-type="video">视频网红</li>
                <li class="weibo-search" data-type="weibo">新浪微博</li>
            </ul>
        </div>
        <input class="fl" type="text" name="search-media" placeholder="请输入微信账号/ID" data-url="<?= Url::to(['/weixin/media/list']) ?>" value="<?=Yii::$app->request->get('search_name')?>">
        <div class="btn fr search-media"><span></span></div>
    </div>
    <div class="fr contact-img"><span class="phone-num"></span></div>
</div>

<!-- 导航开始 -->
<div class="nav">
    <div class="in-nav">
        <ul class="clearfix">
            <li><a href="<?= Url::to(['/wom/index']) ?>">首页</a></li>
            <li><a href="<?= Url::to(['/weixin/media/list']) ?>" class="weixin-list">微信营销</a></li>
            <li><a href="<?= Url::to(['/video/media/list']) ?>" class="video-list">视频网红</a></li>
            <li><a href="<?= Url::to(['/weibo/media/list']) ?>" class="weibo-list">新浪微博</a></li>
            <li><a href="<?= Url::to(['/site/solution/index']) ?>" class="solution">解决方案</a></li>
            <li><a href="<?= Url::to(['/site/case/index']) ?>" class="case">案例中心</a></li>
            <li><a href="<?= Url::to(['/site/about-us/index']) ?>" class="about-us">关于我们</a></li>
            <li><a href="<?= Url::to(['/site/help-center/index']) ?>" class="help-center">帮助中心</a></li>
        </ul>
    </div>
</div>

<?= $content ?>
<!-- 底部 -->
<div class="footer">
    <div class="in-footer">
        <a href="<?= Url::to(['/site/solution/index']) ?>" target="_blank"><i></i><span></span>解决方案</a>|
        <a href="<?= Url::to(['/site/case/index']) ?>" target="_blank"><i></i><span></span>案例中心</a>|
        <a href="<?= Url::to(['/site/about-us/index']) ?>" target="_blank"><i></i><span></span>关于我们</a>|
        <a href="<?= Url::to(['/site/help-center/index']) ?>" target="_blank"><i></i><span></span>帮助中心</a>
    </div>
    <p>Copyright ©2016 www.51wom.com All Rights Reserved 沃米优选：自媒体价值发现者 版权所有 沪ICP备11022867号-1</p>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
