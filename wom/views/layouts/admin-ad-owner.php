<?php
/**
 * 广告主用户中心的layout
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:25 PM
 */

use yii\helpers\Url;
use wom\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$admin_ad_owner = <<<JS
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
    renderLoginAccountInfo(1);

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
JS;
$this->registerJs($admin_ad_owner);
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

    <!-- 头部 -->
    <div class="header">
        <div class="head-top-wrap">
            <div class="head-top clearfix login-success ad-owner">
                <h3 class="welcome">欢迎来到沃米优选! 自媒体价值发现者</h3>
                <div class="enter clearfix">
                    <ul class="clearfix">
                        <li><a class="username" href="javascript:;">欢迎您! <em class="user-name"></em></a></li>
                        <li><a class="my-lib color-main" href="<?= Url::to(['/ad-owner/admin-weixin-media-lib/list']) ?>">我的媒体库</a></li>
                        <li>可用金额: <span class="total-available-balance">0</span></li>
                        <li>冻结金额: <span class="total-frozen-amount">0</span></li>
                        <li><a href="<?= Url::to(['/ad-owner/admin-fin-manage/top-up']) ?>">立即充值</a></li>
                        <li><a href="<?= Url::to(['/site/help-center/index']) ?>">帮助中心</a></li>
                        <li><a class="logout" href="javascript:;">退出</a></li>
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
                <?php if (isset($this->blocks['level-1-nav'])): ?>
                    <li class="breadcrumb-level-one">
                        <a href="#"><?= $this->blocks['level-1-nav'] ?></a>
                    </li>
                <?php endif; ?>

                <?php if (isset($this->blocks['level-2-nav'])): ?>
                    <li class="breadcrumb-level-two active color-main">
                        <?= $this->blocks['level-2-nav'] ?>
                    </li>
                <?php endif; ?>
            </ol>
        </div>
    </div>
    <!-- 内容 -->
    <div class="content-wrap clearfix">
        <!--左侧导航栏-->
        <div class="content-sidebar fl shadow">
            <ul>
                <li class="fold">
                    <a class="title" href="javascript:;"><i class="icon"></i>用户中心</a>
                </li>
                <li class="fold">
                    <i class="rotate-icon"></i>
                    <span class="title"><i class="icon"></i>活动管理</span>
                    <dl>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-weixin-plan/list']) ?>">微信活动</a></dd>
                    </dl>
                </li>
                <li class="fold">
                    <i class="rotate-icon"></i>
                    <span class="title"><i class="icon"></i>订单管理</span>
                    <dl>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-weixin-order/list']) ?>">微信</a></dd>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-weibo-order/list']) ?>">微博</a></dd>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-video-order/list']) ?>">视频</a></dd>
                    </dl>
                </li>
                <li class="fold">
                    <i class="rotate-icon"></i>
                    <span class="title"><i class="icon"></i>媒体库管理</span>
                    <dl>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-weixin-media-lib/list']) ?>">微信</a></dd>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-weibo-media-lib/list']) ?>">微博</a></dd>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-video-media-lib/list']) ?>">视频</a></dd>
                    </dl>
                </li>
                <li class="fold">
                    <i class="rotate-icon"></i>
                    <span class="title"><i class="icon"></i>报表管理</span>
                    <dl>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-weixin-report/list']) ?>">微信报表</a></dd>
                    </dl>
                </li>
                <li class="fold">
                    <a class="title" href="<?= Url::to(['/ad-owner/admin-weixin-material-lib/list']) ?>"><i class="icon"></i>素材管理</a>
                </li>
                <li class="fold">
                    <i class="rotate-icon"></i>
                    <span class="title"><i class="icon"></i>财务管理</span>
                    <dl>
                        <dd>
                            <a href="<?= Url::to(['/ad-owner/admin-fin-manage/weixin-trade-list', 'type' => 1]) ?>">全部流水</a>
                        </dd>
                        <dd>
                            <a href="<?= Url::to(['/ad-owner/admin-fin-manage/weixin-trade-list', 'type' => 2]) ?>">消费记录</a>
                        </dd>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-fin-manage/fund-income-list']) ?>">充值记录</a></dd>
                    </dl>
                </li>
                <li class="fold">
                    <a class="title" href="javascript:;"><i class="icon"></i>消息中心</a>
                </li>
                <li class="fold">
                    <i class="rotate-icon"></i>
                    <span class="title"><i class="icon"></i>个人设置</span>
                    <dl>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-base-info/index']) ?>">基础资料</a></dd>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-base-info/update-password']) ?>">修改登录密码</a></dd>
                        <dd><a href="<?= Url::to(['/ad-owner/admin-base-info/set-pay-password']) ?>">新设支付密码</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
        <!--右侧内容-->
        <!-- 内容 -->
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