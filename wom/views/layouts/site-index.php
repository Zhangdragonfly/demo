<?php
/**
 * 网站首页的layout
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:23 PM
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
        $('.my-lib').addClass('hidden');
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

<!-- top -->
<div class="header">
    <div class="head-top-wrap">
        <div class="head-top clearfix login-cancel">
            <h3 class="welcome">欢迎来到沃米优选! 自媒体价值发现者</h3>
            <div class="enter clearfix">
                <a class="entrance" href="<?= Url::to(['/site/account/login', 'type' => 1]) ?>">广告主入口</a>
                <a class="entrance" href="<?= Url::to(['/site/account/login', 'type' => 2]) ?>">媒体主入口</a>
                <a class="help" href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a>
            </div>
        </div>
        <div class="head-top clearfix login-success ad-owner hidden">
            <h3 class="welcome">欢迎来到沃米优选! 自媒体价值发现者</h3>
            <div class="enter clearfix">
                <ul class="clearfix">
                    <li><a class="username" href="javascript:;">欢迎您! <em class="user-name"></em></a></li>
                    <li><a class="my-lib color-main" href="<?= Url::to(['/ad-owner/admin-weixin-media-lib/list']) ?>">我的媒体库</a></li>
                    <li>可用金额: <span class="total-available-balance">0</span></li>
                    <li>冻结金额: <span class="total-frozen-amount">0</span></li>
                    <li><a href="<?= Url::to(['/ad-owner/admin-fin-manage/top-up']) ?>">立即充值</a></li>
                    <li><a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a></li>
                    <li><a href="javascript:;" class="logout">退出</a></li>
                </ul>
            </div>
        </div>

        <div class="head-top clearfix login-success media-vendor hidden">
            <h3 class="welcome">欢迎来到沃米优选! 自媒体价值发现者</h3>
            <div class="enter clearfix">
                <ul class="clearfix">
                    <li><a class="username" href="javascript:;">欢迎您! <em class="user-name"></em></a></li>
                    <li><a class="my-lib color-main" href="javascript:;">我的媒体库</a></li>
                    <li><a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a></li>
                    <li><a href="javascript:;" class="logout">退出</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 头部-->
    <div class="head clearfix">
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
            <input class="fl" type="text" name="search-media" data-url="<?= Url::to(['/weixin/media/list']) ?>" placeholder="请输入微信账号/ID">
            <div class="btn fr search-media"><span></span></div>
        </div>
        <div class="fr contact-img"><span class="phone-num"></span></div>
    </div>
    <!-- 导航-->
    <div class="nav">
        <ul class="clearfix">
            <li><a href="<?= Url::to(['/wom/index']) ?>" class="wom-index">首页</a></li>
            <li><a href="<?= Url::to(['/weixin/media/list']) ?>" class="weixin-list">微信营销</a></li>
            <li><a href="<?= Url::to(['/video/media/list']) ?>" class="video-list">视频网红</a></li>
            <li><a href="<?= Url::to(['/weibo/media/list']) ?>" class="weibo-list">新浪微博</a></li>
            <li><a href="<?= Url::to(['/site/solution/index']) ?>">解决方案</a></li>
            <li><a href="<?= Url::to(['/site/case/index']) ?>">案例中心</a></li>
            <li><a href="<?= Url::to(['/site/about-us/index']) ?>">关于我们</a></li>
            <li><a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a></li>
        </ul>
    </div>
</div>
<!--banner部分-->
<div class="ad-banner">
    <ul class="ad-pic">
        <li style="opacity: 1" class="slide1"><a href="#"></a></li>
        <li class="slide2"><a href="#"></a></li>
        <li class="slide3"><a href="#"></a></li>
    </ul>
    <ul class="ad-dot clearfix">
        <li class="active"></li>
        <li></li>
        <li></li>
    </ul>
</div>
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
<div class="footer">
    <div class="footer-con clearfix">
        <div class="footer-left">
            <h2>沃米优选</h2>
            <h2>微信公众号</h2>
            <img src="../src/images/wechatcode.png" alt="沃米优选微信二维码">
        </div>
        <div class="footer-mid clearfix">
            <ul>
                <li><div class="footer-mid-icon shhyx"></div>社会化营销</li>
                <li><a href="<?= Url::to(['/weixin/media/list']) ?>" target="_blank">微信推广</a></li>
                <li><a href="<?= Url::to(['/weibo/media/list']) ?>" target="_blank">微博推广</a></li>
                <li><a href="<?= Url::to(['/video/media/list']) ?>" target="_blank">视频网红推广</a></li>
            </ul>
            <ul style="display: none">
                <li><div class="footer-mid-icon znsj"></div>智能数据</li>
                <li><a href="">沃米头条</a></li>
                <li><a href="">沃米指数</a></li>
                <li><a href="">沃米排行榜</a></li>
            </ul>
            <ul>
                <li><div class="footer-mid-icon jjfa"></div>解决方案</li>
                <li><a href="<?= Url::to(['/site/solution/index']) ?>" target="_blank">汽车行业</a></li>
                <li><a href="<?= Url::to(['/site/solution/index']) ?>" target="_blank">母婴行业</a></li>
                <li><a href="<?= Url::to(['/site/solution/index']) ?>" target="_blank">快消品行业</a></li>
                <li><a href="<?= Url::to(['/site/solution/index']) ?>" target="_blank">视频直播行业</a></li>
            </ul>
            <ul>
                <li><div class="footer-mid-icon jxal"></div>精选案例</li>
                <li><a href="<?= Url::to(['/site/case/index']) ?>" target="_blank">汽车客户案例</a></li>
                <li><a href="<?= Url::to(['/site/case/index']) ?>" target="_blank">母婴客户行业</a></li>
                <li><a href="<?= Url::to(['/site/case/index']) ?>" target="_blank">旅游客户行业</a></li>
                <li><a href="<?= Url::to(['/site/case/index']) ?>" target="_blank">视频直播客户服务</a></li>
            </ul>
            <ul>
                <li><div class="footer-mid-icon ppfw"></div>品牌服务</li>
                <li><a href="<?= Url::to(['/site/about-us/index']) ?>" target="_blank">关于我们</a></li>
                <li><span>媒体报道</span></li>
                <li><a href="#">平台导航</a></li>
                <li><a href="<?= Url::to(['/site/help-center/index']) ?>" target="_blank">帮助中心</a></li>
            </ul>
        </div>
        <div class="footer-right">
            <h2>400-878-9551</h2>
            <p>全国免费咨询热线</p>
            <a class="footer-qq" href="http://wpa.b.qq.com/cgi/wpa.php?ln=2&amp;uin=800187006" target="_blank">
                <i class="qq-icon"></i>QQ在线咨询
            </a>
            <a class="footer-email" href="javascript:;">
                <i class="email-icon"></i>service@51wom.com
            </a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>copyright ©2016 www.51wom.com All Rights Reserved 沃米优选：自媒体价值发现者 版权所有 <a href="http://www.miitbeian.gov.cn" style="color: #fff" target="_blank">沪ICP备11022867号</a></p>
        <div class="footer-bottom-img"></div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
