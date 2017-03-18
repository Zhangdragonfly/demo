<?php
/**
 * 媒体主用户中心的layout
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:25 PM
 */

use yii\helpers\Url;
use wom\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$admin_media_vendor = <<<JS
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
                        if(resp.account_type == 2){
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
    renderLoginAccountInfo(2);

    // 退出
    $('.header .logout').on('click', function(){
        var logout_url = $('#id-logout-url').val();
        window.location.href = logout_url;
    });

    // 媒体主个人中心首页
    $('.login-success.media-vendor .username').on('click', function(){
        var media_vendor_admin_index_url = $('input#id-media-vendor-admin-index-url').val();
        window.location.href = media_vendor_admin_index_url;
    });
JS;
$this->registerJs($admin_media_vendor);
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
<input id="id-media-vendor-admin-index-url" type="hidden" value="<?= Url::to(['/media-vendor/admin/index']) ?>">

<!-- 头部 -->
<div class="header">
    <div class="head-top-wrap">
        <div class="head-top clearfix login-success media-vendor">
            <h3 class="welcome">欢迎来到沃米优选! 自媒体价值发现者</h3>
            <div class="enter clearfix">
                <ul class="clearfix">
                    <li><a class="username color-main" href="javascript:;">欢迎您! <em class="user-name"></em></a></li>
                    <li>账户金额: <span class="total-available-balance">0</span></li>
                    <li><a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a></li>
                    <li class="logout"><a href="javascript:;">退出</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="head-bottom">
        <h1><a href="<?= Url::home() ?>"><img src="../../src/images/logo.png" alt="沃米优选"></a></h1>
        <div class="nav">
            <ul class="clearfix">
                <li><a href="<?= Url::home() ?>">首页</a></li>
                <li><a href="<?= Url::to(['/weixin/media/list']) ?>">微信营销</a></li>
                <li><a href="<?= Url::to(['/video/media/list']) ?>">视频网红</a></li>
                <li><a href="<?= Url::to(['/weibo/media/list']) ?>">新浪微博</a></li>
                <li><a href="<?= Url::to(['/site/solution/index']) ?>">解决方案</a></li>
                <li><a href="<?= Url::to(['/site/case/index']) ?>">案例中心</a></li>
                <li class="about-us"><a href="<?= Url::to(['/site/about-us/index']) ?>">关于我们</a></li>
            </ul>
        </div>
    </div>
    <!-- 面包屑 -->
    <div class="bread">
        <ol class="breadcrumb font-500">
            当前位置：
            <li><a href="<?= Url::home() ?>">首页</a></li>
            <li><a href="#">个人中心</a></li>
            <li class="breadcrumb-level-one"><a href="#">
                    <?php if (isset($this->blocks['level-1-nav'])): ?>
                        <?= $this->blocks['level-1-nav'] ?>
                    <?php else: ?>
                        默认
                    <?php endif; ?>
                </a></li>
            <li class="breadcrumb-level-two active color-main">
                <?php if (isset($this->blocks['level-2-nav'])): ?>
                    <?= $this->blocks['level-2-nav'] ?>
                <?php else: ?>
                    默认
                <?php endif; ?></li>
        </ol>
    </div>
</div>

<!-- 主要内容部分 -->
<div class="content-wrap clearfix">
    <!--左侧导航栏-->
    <div class="content-sidebar fl shadow">
        <ul>
            <li class="fold">
                <a class="title" href="javascript:;"><i class="icon"></i>用户中心</a>
            </li>
            <li class="fold">
                <i class="rotate-icon"></i>
                <span class="title"><i class="icon"></i>订单管理</span>
                <dl>
                    <dd><a href="<?= Url::to(['/media-vendor/admin-weixin-order/list']) ?>">微信</a></dd>
                </dl>
            </li>
            <li class="fold">
                <a class="title" href="javascript:;"><i class="icon"></i>账号管理</a>
            </li>
            <li class="fold">
                <i class="rotate-icon"></i>
                <span class="title"><i class="icon"></i>财务管理</span>
                <dl>
                    <dd><a href="#">全部流水</a></dd>
                    <dd><a href="#">消费记录</a></dd>
                    <dd><a href="#">充值记录</a></dd>
                </dl>
            </li>
            <li class="fold">
                <a class="title" href="javascript:;"><i class="icon"></i>消息中心</a>
            </li>
            <li class="fold">
                <i class="rotate-icon"></i>
                <span class="title"><i class="icon"></i>个人设置</span>
                <dl>
                    <dd><a href="<?= Url::to(['/media-vendor/admin-base-info/index']) ?>">基础资料</a></dd>
                    <dd><a href="<?= Url::to(['/media-vendor/admin-base-info/update-password']) ?>">修改登录密码</a></dd>
                </dl>
            </li>
        </ul>
    </div>
    <!--右侧内容-->
    <?= $content ?>
</div>
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
