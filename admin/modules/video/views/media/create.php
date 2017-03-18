<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:33 PM
 */
use admin\assets\AppAsset;
use common\helpers\MediaHelper;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\grid\GridView;

AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addScript($this, '@web/js/video/media-create.js');
AppAsset::addScript($this, '@web/js/video/video-admin.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addCss($this, '@web/css/video/video-admin.css');

$videoJS = <<<JS
    videoCreate();
JS;
$this->registerJs($videoJS);
?>
    <!-- begin #content -->
    <div id="content" class="content">
        <input type="hidden" name="get_vendor_active_time" value="<?= Yii::$app->urlManager->createUrl(array('weibo/media/get-vendor-active-time'))?>">
        <div class="row">
            <div class="col-md-22">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#default-tab-one" data-toggle="tab" class="create-media">入驻账号</a></li>
                    <li class=""><a href="#default-tab-two" data-toggle="tab" class="add-vendor" data-url="<?=Yii::$app->urlManager->createUrl(array('media/vendor/search'))?>">添加媒体主</a></li>
                </ul>

                <div class="tab-content">
                    <!--审核资源-->
                    <div class="tab-pane fade active in" id="default-tab-one">
                        <div class="base-info-con" style="padding-top:20px;">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label class="col-md-4 control-label">* 艺人名称 ：</label>
                                        <div class="col-md-8">
                                            <input type="text" name="nickname" class="actor-name form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-md-4 control-label">真实姓名 ：</label>
                                        <div class="col-md-8">
                                            <input type="text" name="realname" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label class="col-md-4 control-label">性别 ：</label>
                                        <div class="col-md-8">
                                            <select name="sex" class="form-control">
                                                <option value="-1">未知</option>
                                                <option value="1">男</option>
                                                <option value="2">女</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label" style="width:134px">合作备注 ：</label>
                                    <div class="col-md-7">
                                        <textarea name="coop_remark" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-1 control-label" style="width:134px">* 艺人分类 ：
                                        <br>
                                        <small class="text-danger">(最多选6个)</small>
                                    </label>
                                    <div class="actor-classify col-md-7" style="position:relative">
                                        <?php
                                        $mediaCateList = MediaHelper::getMediaCateList();//全部资源
                                        foreach ($mediaCateList as $code => $cate) { ?>
                                            <label class="checkbox-inline ">
                                                <input type="checkbox" name="media_cate" value="<?php echo $code; ?>" class="<?php echo 'one-cate cate-' . $code; ?>"><?php echo $cate; ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-1 control-label" style="width:135px;">* 所在地 ：
                                        <br>
                                        <small class="text-danger">(单选)</small>
                                    </label>
                                    <div class="col-md-8 location">
                                        <?php
                                        $mediaCityList = MediaHelper::getCityList();
                                        foreach ($mediaCityList as $code => $cate) { ?>
                                            <label class="checkbox-inline ">
                                                <input type="checkbox" name="address" value="<?php echo $code; ?>" class="<?php echo 'one-area area-'.$code; ?>"><?php echo $cate; ?>
                                            </label>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="profile">
                                    <span>头像：</span>
                                    <div class="head-portrait">+</div>
                                </div>
                            </form>
                        </div>

                        <!-- 艺人入驻平台信息 -->
                        <div class="platform-info">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#default-tab-1" data-toggle="tab" aria-expanded="true">艺人入驻平台信息</a></li>
                                <!--                <li class=""><a href="#default-tab-2" data-toggle="tab"aria-expanded="false" disabled>关联其他平台</a></li>-->
                                <div style="float: right;margin:5px 6px 0 0;">
                                    <a href="#modal-dialog" class="btn btn-info btn-sm add-platform-info" data-toggle="modal" type="button">+ 添加平台信息</a>
                                    <!-- 添加平台信息modal -->
                                    <div class="modal fade in" id="modal-dialog" >
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title">添加平台信息</h4>
                                                </div>
                                                <div class="modal-body" style="padding-bottom:0">
                                                        <div class="form-group row">
                                                            <label class="col-md-4 control-label" style="text-align:right;width:112px;">选择平台 :</label>
                                                            <div class="platform-list col-md-9">
                                                                <?php foreach(MediaHelper::getVideoPlatformType() as $k=>$v){?>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="platform_type" data-type="<?=$k;?>" checked=""><?=$v;?>
                                                                    </label>
                                                                <?php }?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="title col-md-2 control-label" style="padding-right:0;">* 账号名称 ：</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="account_name form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="title col-md-2 control-label">账号ID ：</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="account_id form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="title col-md-2 control-label">* 粉丝数：</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="follower_num form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="title col-md-4 control-label">认证 ：</label>
                                                                <div class="col-md-8">
                                                                    <select class="auth_status form-control">
                                                                        <option value="-1">未知</option>
                                                                        <option value="1">已认证</option>
                                                                        <option value="2">未认证</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="title  col-md-2 control-label">链接 ：</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="url form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="title col-md-2 control-label">平均观看人数：</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="avg_watch_num form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="title col-md-2 control-label" style="width:120px">个性签名 ：</label>
                                                            <div class="col-md-7">
                                                                <textarea class="person_sign form-control" rows="1"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row"  style="border:none">
                                                            <label class="title col-md-2 control-label" style="width:120px">备注 ：</label>
                                                            <div class="col-md-7">
                                                                <textarea class="remark form-control" rows="5"></textarea>
                                                            </div>
                                                        </div>
                                                </div>
                                                <button style="margin-left:500px;" class="btn btn-success btn-save-platform">保存</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade active in" id="default-tab-1">
                                    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                                        <div class="panel-body">
                                            <table class="table table-vendor">
                                                <thead>
                                                <tr>
                                                    <th>设定主打平台</th>
                                                    <th>所在平台</th>
                                                    <th>账号名称、ID</th>
                                                    <th>粉丝数</th>
                                                    <th>认证</th>
                                                    <th>链接</th>
                                                    <th>个人签名</th>
                                                    <th>备注</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody class="add_paltform_list">
                                                <!--ajax添加平台信息 -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="default-tab-2">
                                <!--关联平台-->
                                </div>
                            </div>
                        </div>

                        <!-- 媒体主列表 -->
                        <div class="media-vendor-list">
                            <legend class="vendor-list" style="color: #00acac;">媒体主列表
                                <div style="float:right">
                                    <a href="#media-vendor-add" class="btn btn-info btn-sm add-vendor-info" data-toggle="modal" type="button">+ 添加媒体主</a>
                                </div>
                            </legend>
                            <table class="table table-vendor">
                                <thead>
                                <tr>
                                    <th>首选供应商</th>
                                    <th>媒体主名称</th>
                                    <th>平台合作价（主打平台）</th>
                                    <th>价格有效期</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody class="tbody-vendor-list">
                                    <!--媒体主列表-->
                                </tbody>
                            </table>
                        </div>
                        <button class="submit btn btn-success btn-save-video-media" style="margin-left:500px;" data-url="<?= Yii::$app->urlManager->createUrl(array('video/media/create'))?>">保存</button>
                    </div>

                    <!--审核媒体主-->
                    <div class="tab-pane fade" id="default-tab-two">
                        <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                            <div class="panel-body body-add-media-vendor">
                                <div class="alert alert-danger fade in m-b-15">
                                    <strong>注意:  </strong>
                                    1. 可从系统中搜索已经存在的媒体主  2. 如果在系统不存在,可以"新建媒体主"
                                    <span class="close" data-dismiss="alert">×</span>
                                </div>

                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">搜索已有媒体主*: </label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control input-name" name="search_vendor_name" placeholder="媒体主名称\联系方式\QQ\微信"/>
                                            <span class="error-msg" style="color:red;display:none;font-size:16px;">媒体主不存在，请去 "新建媒体主"</span>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-success btn-vendor-search" type="button" data-url="<?=Yii::$app->urlManager->createUrl(array('media/vendor/search'))?>">搜&nbsp;&nbsp;&nbsp;索</button>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-primary btn-create-vendor" type="button" data-url="<?=Yii::$app->urlManager->createUrl(array('media/vendor/create'))?>">新建媒体主</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive table-search-vendor-list">
                                    <h1 class="page-header" style="font-size:16px;color:#00acac;">搜索媒体主列表</h1>
                                    <table id="user" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>媒体主</th>
                                            <th>注册渠道</th>
                                            <th>联系人</th>
                                            <th>备注</th>
                                            <th>选择</th>
                                        </tr>
                                        </thead>
                                        <tbody class="vendor-search-result">
                                        <!-- ajax获取媒体主列表-->
                                        </tbody>
                                    </table>

                                    <!--自媒体主信息-->
                                    <div class="panel-body panel-body-vendor-info" style="display: none;">
                                        <legend class="vendor-name" style="color: #00acac;">自媒体主信息<span style="color: #348fe2;"></span></legend>
                                        <form class="form-horizontal">
                                            <table class="table table-bordered table-price-set">
                                                <thead>
                                                <tr>
                                                    <th style="min-width: 150px">平台</th>
                                                    <th style="min-width: 150px">位置</th>
                                                    <th style="min-width: 150px">平台合作价(元)</th>
                                                    <th style="min-width: 150px">零售价(元)</th>
                                                    <th style="min-width: 150px">执行价(元)</th>
                                                </tr>
                                                </thead>
                                                <tbody class="price-info-tbody">
                                                    <!--自媒体报价信息-->
                                                </tbody>

                                            </table>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">资源所属关系</label>
                                                <div class="col-md-5">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="belong_type" value="1" />自营
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="belong_type" value="2" />独家
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="belong_type" value="3" />代理
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="belong_type" value="0" checked/>其他
                                                    </label>
                                                </div>

                                                <label class="col-md-2 control-label">价格有效期</label>
                                                <div class="col-md-2">
                                                    <input type="text" name="active_end_time"class="form-control active_end_time" value=""/>
                                                    <a href="javascript:;" class="btn btn-primary btn-xs m-r-5 sync-latest-active-end-time">同步最新报价有效期</a>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">账期</label>
                                                <div class="col-md-5">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="account_period" value="1"  />季度
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="account_period" value="2" />半年
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="account_period" value="3" />一年
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="account_period" value="0" checked/>未设置
                                                    </label>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">配合度</label>
                                                <div class="col-md-5">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="cooperate_level" value="1" />高
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="cooperate_level" value="2" />中
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="cooperate_level" value="3" checked/>低
                                                    </label>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                </div>
                                <div style="text-align: center">
                                    <button type="button" class="btn btn-success btn-add-vendor" style="display: none" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/add-vendor'))?>">添 加</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



























