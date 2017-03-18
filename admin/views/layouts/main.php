<?php
/* @var $this \yii\web\View */
/* @var $content string */

use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->registerJs('
    $(document).ready(function() {
         App.init();
         Dashboard.init();
      });
    ', \yii\web\View::POS_END);
?>
<?php $this->beginPage() ?>

    <!DOCTYPE html>
    <!--[if IE 8]>
    <html lang="zh-CN" class="ie8"> <![endif]-->
    <!--[if !IE]><!-->
    <html lang="zh-CN">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <!--[if lt IE 9]>
    <script src="crossbrowserjs/html5shiv.js"></script>
    <script src="crossbrowserjs/respond.min.js"></script>
    <script src="crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->

    <div id="page-loader" class="fade in"><span class="spinner"></span></div>

    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <div id="header" class="header navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="#" class="navbar-brand"><span class="navbar-logo"></span>沃米优选后台系统</a>
                    <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <ul class="nav navbar-nav navbar-right">
<!--                    <li class="dropdown">-->
<!--                        <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">-->
<!--                            <i class="fa fa-bell-o"></i>-->
<!--                            <span class="label">5</span>-->
<!--                        </a>-->
<!--                        <ul class="dropdown-menu media-list pull-right animated fadeInDown">-->
<!--                            <li class="dropdown-header">Notifications (5)</li>-->
<!--                            <li class="media">-->
<!--                                <a href="javascript:;">-->
<!--                                    <div class="media-left"><i class="fa fa-bug media-object bg-red"></i></div>-->
<!--                                    <div class="media-body">-->
<!--                                        <h6 class="media-heading">Server Error Reports</h6>-->
<!--                                        <div class="text-muted f-s-11">3 minutes ago</div>-->
<!--                                    </div>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="dropdown-footer text-center">-->
<!--                                <a href="javascript:;">View more</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
                    <li class="dropdown navbar-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="images/back/profile-avatar.jpg" alt=""/>
                            <span class="hidden-xs">沃小米</span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu animated fadeInLeft">
<!--                            <li class="arrow"></li>-->
<!--                            <li><a href="javascript:;">Edit Profile</a></li>-->
<!--                            <li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a>-->
<!--                            </li>-->
<!--                            <li><a href="javascript:;">Calendar</a></li>-->
<!--                            <li><a href="javascript:;">Setting</a></li>-->
<!--                            <li class="divider"></li>-->
                            <li><a href="<?php echo Yii::$app->urlManager->createUrl(array('site/logout')); ?>">退出</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div id="sidebar" class="sidebar">
            <div data-scrollbar="true" data-height="100%">
                <ul class="nav">
                    <li class="nav-profile">
                        <div class="image">
                            <a href="javascript:;"><img src="assets/img/profile-avatar.jpg" alt=""/></a>
                        </div>
                        <div class="info">
                            沃小米
                            <small>系统管理员</small>
                        </div>
                    </li>
                </ul>
                <ul class="nav">
                    <li class="has-sub menu-level-1" id="weixin">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-weixin"></i>
                            <span>微信</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="has-sub menu-level-2 media-manage">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    资源管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 to-create" style=""><a
                                            href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/media/create')); ?>">入驻账号</a>
                                    </li>
                                    <li class="menu-level-3 to-list"><a
                                            href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/media/list')); ?>">全部资源</a>
                                    </li>
                                    <li class="menu-level-3 to-verify"><a
                                            href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/media/to-verify-list')); ?>">待审核</a>
                                    </li>
                                    <li class="menu-level-3 verify-succ"><a
                                            href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/media/verify-succ-list')); ?>">审核通过</a>
                                    </li>
                                    <li class="menu-level-3 verify-invalid"><a
                                            href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/media/verify-invalid-list')); ?>">无效账号</a>
                                    </li>
                                    <li class="menu-level-3 to-update"><a
                                            href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/media/to-update-list')); ?>">待更新</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-sub menu-level-2 trans-manage">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    活动管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 plan-list"><a
                                            href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/plan/list')); ?>">活动列表</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-sub menu-level-2 weixin-order-manage">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    订单管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 order-list"><a
                                                href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/order/order-list')); ?>">预约订单列表</a>
                                    </li>
                                    <li class="menu-level-3 put-list"><a
                                                href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/order/put-list')); ?>">直投订单列表</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-sub menu-level-2 price-record" style="display: none;">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    价格记录
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 price-chart">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('weixin/record/chart')); ?>">价格趋势图</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-sub menu-level-1" id="weibo">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-weibo"></i>
                            <span>微博</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="has-sub menu-level-2 weibo-media-manage">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    资源管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 weibo-media-create">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('weibo/media/create')); ?>">入驻账号</a>
                                    </li>
                                    <li class="menu-level-3 weibo-media-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('weibo/media/list')); ?>">全部资源</a>
                                    </li>
                                    <li class="menu-level-3 weibo-to-verify-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('weibo/media/list'))."&type=1"; ?>">待审核</a>
                                    </li>
                                    <li class="menu-level-3 weibo-success-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('weibo/media/list'))."&type=2"; ?>">已审核</a>
                                    </li>
                                    <li class="menu-level-3 weibo-fail-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('weibo/media/list'))."&type=3"; ?>">未通过</a>
                                    </li>
                                    <li class="menu-level-3 weibo-update-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('weibo/media/list'))."&type=4"; ?>">待更新</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="has-sub menu-level-2 weibo-order-manage">

                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    投放管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 weibo-order-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('weibo/order/list')); ?>">订单列表</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-sub menu-level-1" id="video">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-video-camera"></i>
                            <span>视频直播</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="has-sub menu-level-2 media-manage">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    资源管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 to-create">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('video/media/create')); ?>">入驻账号</a>
                                    </li>
                                    <li class="menu-level-3 to-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('video/media/list')); ?>">全部资源</a>
                                    </li>
                                    <li class="menu-level-3 to-verify-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('video/media/list'))."&type=1"; ?>">待审核</a>
                                    </li>
                                    <li class="menu-level-3 to-success-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('video/media/list'))."&type=2"; ?>">已审核</a>
                                    </li>
                                    <li class="menu-level-3 to-fail-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('video/media/list'))."&type=3"; ?>">未通过</a>
                                    </li>
                                    <li class="menu-level-3 to-update-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('video/media/list'))."&type=4"; ?>">待更新</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="has-sub menu-level-2 video-order-manage">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    投放管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 order-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('video/order/list')); ?>">订单列表</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-sub menu-level-1" id="media-vendor">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-users"></i>
                            <span>媒体主管理</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="menu-level-2 media-vendor-create"><a
                                    href="<?php echo Yii::$app->urlManager->createUrl(array('media/vendor/create')); ?>">添加媒体主</a>
                            </li>
                            <li class="menu-level-2 media-vendor-list"><a
                                    href="<?php echo Yii::$app->urlManager->createUrl(array('media/vendor/list')); ?>">媒体主列表</a>
                            </li>
                        </ul>
                    </li>

                    <li class="has-sub menu-level-1" id="ad-owner">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-users"></i>
                            <span>广告主管理</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="menu-level-2 ad-owner-list"><a
                                    href="<?php echo Yii::$app->urlManager->createUrl(array('ad/owner/list')); ?>">广告主列表</a>
                            </li>
                            <li class="menu-level-2 ad-credit-list"><a
                                    href="<?php echo Yii::$app->urlManager->createUrl(array('ad/credit/list')); ?>">授信申请</a>
                            </li>
                        </ul>
                    </li>

                    <li class="has-sub menu-level-1" id="website-manage">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-home"></i>
                            <span>网站管理</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="has-sub menu-level-2 home-manage">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    首页管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('/home/weixin/list')); ?>">微信资源 TEMP</a>
                                    </li>
                                    <li class="menu-level-3">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('/home/video/list')); ?>">视频网红 TEMP</a>
                                    </li>
                                    <li class="menu-level-3 weixin-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('/website/home/weixin/list')); ?>">微信</a>
                                    </li>
                                    <li class="menu-level-3 video-list">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('/website/home/video/list')); ?>">视频网红</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="has-sub menu-level-2 task-manage">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    任务管理
                                </a>
                                <ul class="sub-menu">
                                    <li class="menu-level-3 task-create">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(array('/home/task/create')); ?>">创建任务</a>
                                    </li>
<!--                                    <li class="menu-level-3 task-list">-->
<!--                                        <a href="--><?php //echo Yii::$app->urlManager->createUrl(array('/home/task/list')); ?><!--">任务列表</a>-->
<!--                                    </li>-->
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-sub" style="display: none">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cubes"></i>
                            <span>市场管理</span>
                        </a>
                        <ul class="sub-menu">
                            <li class=""><a href="extra_timeline.html">活动列表</a></li>
                            <li class=""><a href="extra_timeline.html">新建活动</a></li>
                        </ul>
                    </li>

                    <li class="has-sub" style="display: none">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cubes"></i>
                            <span>运营管理</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="extra_timeline.html">XXX</a></li>
                        </ul>
                    </li>

                    <li class="has-sub" style="display: none">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cubes"></i>
                            <span>财务管理</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="extra_timeline.html">XXX</a></li>
                        </ul>
                    </li>

                    <li class="has-sub" style="display: none">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cubes"></i>
                            <span>OEM管理</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="extra_timeline.html">所有OEM</a></li>
                            <li><a href="extra_timeline.html">开通OEM</a></li>
                        </ul>
                    </li>

                    <li class="has-sub" style="display: none">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cubes"></i>
                            <span>其他业务线</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="has-sub">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    谦玛精选
                                </a>
                            </li>
                            <li class="has-sub">
                                <a href="javascript:;">
                                    <b class="caret pull-right"></b>
                                    沃米排行榜
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="has-sub" id="sys-setting">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cubes"></i>
                            <span>系统管理</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="<?php echo Yii::$app->urlManager->createUrl(array('/system/account/change-pwd')); ?>">重置密码</a></li>
                        </ul>
                    </li>

                    <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i
                                class="fa fa-angle-double-left"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="sidebar-bg"></div>

        <?= $content ?>

        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade"
           data-click="scroll-top"><i
                class="fa fa-angle-up"></i></a>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>