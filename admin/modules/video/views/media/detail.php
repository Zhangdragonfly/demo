<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/25/16 2:33 PM
 */
use admin\assets\AppAsset;
use common\helpers\MediaHelper;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\grid\GridView;

//$mediaVideoCreateUrl = Yii::$app->urlManager->createUrl(array('video/media/create'));
//$mediaVideoToVerifyListUrl = Yii::$app->urlManager->createUrl(array('video/media/to-verify-list'));
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addCss($this, '@web/css/video/video-admin.css');
AppAsset::addScript($this, '@web/js/video/video-detail.js');
AppAsset::addScript($this, '@web/js/video/video-admin.js');



$videoJS = <<<JS
    videoDetail();
JS;
$this->registerJs($videoJS);
?>
<!-- begin #content -->
<div id="content" class="content">
    <input type="hidden" name="video_uuid" value="<?= Yii::$app->request->get('uuid')?>">
    <input type="hidden" name="delete_vendor" value="<?= Yii::$app->urlManager->createUrl(array('video/media/delete-vendor'))?>">
    <input type="hidden" name="get_vendor_info" value="<?= Yii::$app->urlManager->createUrl(array('video/media/get-vendor-info'))?>">
    <input type="hidden" name="get_vendor_active" value="<?= Yii::$app->urlManager->createUrl(array('video/media/get-vendor-active-time'))?>">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">视频</a></li>
        <li><a href="javascript:;">资源管理</a></li>
        <li class="active">审核资源详情</li>
    </ol>

    <h1 class="page-header">审核资源/媒体主</h1>

    <!-- 审核资源/自媒体主 -->
    <div class="platform-info">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#default-tab-1" data-toggle="tab" class="audit-media" aria-expanded="true">审核资源</a></li>
            <li class=""><a href="#default-tab-2" data-toggle="tab" class="audit-vendor" data-url="<?=Yii::$app->urlManager->createUrl(array('video/media/get-list-of-vendor'))?>" aria-expanded="false">审核自媒体主</a></li>
            <li class=""><a href="#default-tab-3" data-toggle="tab" class="add-vendor" data-url="<?=Yii::$app->urlManager->createUrl(array('media/vendor/search'))?>" aria-expanded="false">添加自媒体主</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="default-tab-1">
                <!-- 艺人基础信息 -->
                <div class="base-info">
                    <legend class="vendor-list" style="color: #00acac;">艺人基础信息</legend>
                    <div class="base-info-con" style="padding-top:20px;">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label class="col-md-4 control-label">* 艺人名称 ：
                                        <br>
                                        <small class="text-danger">(可同主打平台)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="text" name="nickname" class="form-control" value="<?=!empty($data[0]['nickname'])?$data[0]['nickname']:"";?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-md-4 control-label">真实姓名 ：</label>
                                    <div class="col-md-8">
                                        <input type="text" name="realname" class="form-control" value="<?=!empty($data[0]['realname'])?$data[0]['realname']:"";?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label class="col-md-4 control-label">性别 ：</label>
                                    <div class="col-md-8">
                                        <select name="sex" class="form-control">
                                            <option  value="-1" <?=($data[0]['sex']==-1)?"selected":""; ?>>未知</option>
                                            <option  value="1" <?=($data[0]['sex']==1)?"selected":""; ?>>男</option>
                                            <option  value="2" <?=($data[0]['sex']==2)?"selected":""; ?>>女</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" style="width:134px">合作备注 ：</label>
                                <div class="col-md-7">
                                    <textarea name="coop_remark" class="form-control" rows="5"><?=!empty($data[0]['coop_remark'])?$data[0]['coop_remark']:"";?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-1 control-label" style="width:134px">* 艺人分类 ：
                                    <br>
                                    <small class="text-danger">(最多选6个)</small>
                                </label>
                                <div class="actor-classify col-md-7" style="position:relative">
                                    <?php
                                    $cate_array =array_filter(explode('#',$data[0]['media_cate']));//选中资源
                                    $mediaCateList = MediaHelper::getMediaCateList();//全部资源
                                    foreach ($mediaCateList as $code => $cate) { ?>
                                        <label class="checkbox-inline ">
                                            <input type="checkbox" name="media_cate" value="<?php echo $code; ?>" class="<?php echo 'one-cate cate-' . $code; ?>"  <?php foreach($cate_array as $k=>$v){if($v==$code)echo "checked";} ?>><?php echo $cate; ?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-1 control-label" style="width:134px">* 所在地:
                                    <br>
                                    <small class="text-danger">(必填)</small>
                                </label>
                                <div class="col-md-8 address">
                                    <?php
                                    $area_array =array_filter(explode('#',$data[0]['address']));//选中资源
                                    $mediaCityList = MediaHelper::getCityList();
                                    foreach ($mediaCityList as $code => $cate) { ?>
                                        <label class="checkbox-inline ">
                                            <input type="checkbox" name="address" value="<?php echo $code; ?>" class="<?php echo 'one-area area-'.$code; ?>" <?php foreach($area_array as $k=>$v){if($v==$code)echo "checked";} ?> ><?php echo $cate; ?>
                                        </label>
                                    <?php }?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- 艺人入驻平台信息 -->
                <div class="platform-info">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#default-tab-11" data-toggle="tab" class="audit-media" aria-expanded="true">艺人入驻平台信息</a></li>
<!--                        <li class=""><a href="#default-tab-12" data-toggle="tab" aria-expanded="false">关联其他平台</a></li>-->
                        <div style="float: right;margin:5px 6px 0 0;">
                            <a href="#modal-dialog-add" class="btn btn-info btn-sm add-platform-info" data-toggle="modal" type="button">+ 添加平台信息</a>
                            <!-- 添加平台信息modal -->
                            <div class="modal fade in" id="modal-dialog-add" >
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
                                                    <label class="title col-md-2 control-label">* 粉丝数 ：</label>
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

                                            <div class="form-group row" style="border:none">
                                                <div class="col-md-4">
                                                    <label class="title col-md-4 control-label">审核状态 ：</label>
                                                    <div class="col-md-8" style="margin-left:6px;">
                                                        <select name="audit_status"class="audit_status form-control">
                                                            <option value="0">待审核</option>
                                                            <option value="1">审核通过</option>
                                                            <option value="2">未通过</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="border:none">
                                                <label class="title col-md-2 control-label" style="width:140px;text-align:left;padding:0;">审核不通过原因 ：</label>
                                                <div class="col-md-7" style="padding:0;margin-left:-8px;">
                                                    <textarea class="fail_reason form-control" rows="5" disabled></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <button style="margin-left:500px;" class="btn btn-success btn-save-add-platform" data-url="<?=Yii::$app->urlManager->createUrl(array('video/media/add-platform-info'))?>" data-uuid="<?=$data[0]['video_uuid']?>">保存</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="default-tab-11">
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
                                            <th>状态</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody class="tbody-video-platform-list">
                                        <?php foreach($data as $k=>$v){

                                            foreach(MediaHelper::getVideoPlatformType() as $key=>$val){
                                                if($v['platform_type']==$key){
                                                    $platform = $val;
                                                }
                                            };
                                            switch($v['auth_type']){
                                                case 0:$auth_type = "未知";break;
                                                case 1:$auth_type = "已认证";break;
                                                case 2:$auth_type = "未认证";break;
                                                default:$auth_type = "未知";
                                            }
                                            switch($v['status']){
                                                case 0:$status = "待审核";break;
                                                case 1:$status = "已审核";break;
                                                case 2:$status = "未审核";break;
                                                default:$status = "未知";
                                            }
                                        ?>
                                            <tr data-uuid="<?=$v['platform_uuid']?>" platform-type="<?=$v['platform_type']?>">
                                                <td>
                                                    <label class="checkbox-inline">
                                                        <input name="is_main" type="checkbox" <?php if($v['platform_type']==$data[0]['main_platform']){echo "checked='checked'";}?> >主打平台
                                                    </label>
                                                </td>
                                                <td><?=$platform?></td>
                                                <td><?=$v['account_name']?><br><?=$v['account_id']?></td>
                                                <td><?=$v['follower_num']/10000?>万</td>
                                                <td><?=$auth_type?></td>
                                                <td><?=$v['url']?></td>
                                                <td><?=$v['person_sign']?></td>
                                                <td><span style="color:red;"><?=$status?></span></td>
                                                <td>
                                                    <a href="#modal-dialog-audit" class="btn btn-primary btn-xs m-r-5 platform-to-verify" data-uuid="<?=$v['platform_uuid']?>"><?=($v['status']==1)?"修改":"审核";?></a>
                                                    <a class="btn btn-white btn-xs m-r-5 platform-to-delete" data-uuid="<?=$v['platform_uuid']?>">移除</a>
                                                </td>
                                            </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="default-tab-12"></div>
                    </div>

                </div>
                <button class="submit btn btn-success btn-update-media-video" style="margin-left:500px;" data-uuid="<?=!empty($data[0]['video_uuid'])?$data[0]['video_uuid']:"";?>" data-url="<?=Yii::$app->urlManager->createUrl(array('video/media/save-audit-video'))?>">下一步</button>
            </div>
            <div class="tab-pane fade" id="default-tab-2">
                <!-- 媒体主审核 -->
                <div class="media-vendor-list">
                    <legend class="vendor-list" style="color: #00acac;">自媒体主审核
                        <div style="float:right">
                            <a class="btn btn-info btn-sm btn-to-add-vendor"  type="button">+ 添加自媒体主</a>
                        </div>
                    </legend>
                    <table class="table table-vendor">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>自媒体主名称</th>
                            <th>自媒体主来源</th>
                            <th>联系人</th>
                            <th>联系方式</th>
                            <th>首选供应商</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-video-vendor-list">
                        <!--供应商信息列表-->
                        </tbody>
                    </table>
                </div>
                <!-- 媒体主详情 -->
                <div class="media-vendor-detail">
                    <legend class="vendor-list legend-vendor-name" style="color: #00acac;">自媒体主：<span></span></legend>
                    <form class="form-horizontal" style="background:#fff;padding-bottom:20px;">
                        <table class="table table-bordered table-price-set">
                            <thead>
                            <tr>
                                <th style="width: 120px">平台</th>
                                <th style="width: 120px">位置</th>
                                <th style="width: 190px">平台合作价(元)</th>
                                <th style="width: 190px">零售价(元)</th>
                                <th style="width: 190px">执行价(元)</th>
                                <th style="width: 190px;color:red;">上架</th>
                            </tr>
                            </thead>
                            <tbody class="tbody-vendor-price-list">
                            <!--供应商价格信息列表 -->
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label class="col-md-2 control-label">资源所属关系 ：</label>
                            <div class="col-md-5">
                                <label class="radio-inline">
                                    <input type="radio" name="belong_type" value="1">自营
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="belong_type" value="2">独家
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="belong_type" value="3">代理
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="belong_type" value="0">其他
                                </label>
                            </div>
                            <label class="col-md-2 control-label">价格有效期 ：</label>
                            <div class="col-md-2">
                                <input type="text" name="active_end_time" class="form-control active_end_time" value="">
                                <a href="javascript:;" class="btn btn-primary btn-xs m-r-5 sync-latest-active-end-time">同步最新报价有效期</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">账期 ：</label>
                            <div class="col-md-5">
                                <label class="radio-inline">
                                    <input type="radio" name="account_period" value="1">季度
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="account_period" value="2">半年
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="account_period" value="3">一个月
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="account_period" value="0">无
                                </label>
                            </div>

                            <label class="col-md-2 control-label">媒体主审核状态 ：</label>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="-1">不限</option>
                                    <option value="0">待审核</option>
                                    <option value="1">已审核</option>
                                    <option value="2">未通过</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">配合度 ：</label>
                            <div class="col-md-5">
                                <label class="radio-inline">
                                    <input type="radio" name="cooperate_level" value="1" >高
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="cooperate_level" value="2">中
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="cooperate_level" value="3">低
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="cooperate_level" value="0">无
                                </label>
                            </div>

                            <label class="col-md-2 control-label">设置为首选供应商 ：</label>
                            <div class="col-md-2">
                                <label class="radio-inline">
                                    <input type="radio" name="is_pref_vendor" value="1">是
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_pref_vendor" value="0">否
                                </label>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="col-md-2 control-label">运营媒介 ：</label>
                            <div class="col-md-2">
                                <input name="name" type="text" class="form-control" value="" disabled="">
                            </div>
                        </div>
                        <button class="btn btn-success btn-commit btn-audit-vendor-save" type="button" style="display:block;margin:0 auto;"data-uuid="<?=!empty($data[0]['video_uuid'])?$data[0]['video_uuid']:"";?>" data-url="<?=Yii::$app->urlManager->createUrl(array('video/media/save-audit-vendor'))?>">保存</button>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="default-tab-3">
            <!--添加媒体主-->
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
                            <button type="button" class="btn btn-success btn-add-vendor" style="display: none" data-url="<?=Yii::$app->urlManager->createUrl(array('video/media/add-vendor'))?>">添 加</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>




<div class="modal fade in" id="modal-dialog-audit" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">审核平台信息</h4>
            </div>
            <div class="modal-body" style="padding-bottom:0">
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
                        <label class="title col-md-2 control-label">* 粉丝数 ：</label>
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

                <div class="form-group row" style="border:none">
                    <div class="col-md-4">
                        <label class="title col-md-4 control-label">审核状态 ：</label>
                        <div class="col-md-8" style="margin-left:6px;">
                            <select class="audit_status form-control">
                                <option value="0">待审核</option>
                                <option value="1">审核通过</option>
                                <option value="2">未通过</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" style="text-align:right;font-size:14px;">
                        审核时间：<span class="audit_time"></span>
                    </div>
                </div>
                <div class="form-group row" style="border:none">
                    <label class="title col-md-2 control-label" style="width:140px;text-align:left;padding:0;">审核不通过原因 ：</label>
                    <div class="col-md-7" style="padding:0;margin-left:-8px;">
                        <textarea class="fail_reason form-control" rows="5" disabled></textarea>
                    </div>
                </div>
            </div>
            <button style="margin-left:500px;" class="btn btn-success btn-update-platform">提交</button>
        </div>
    </div>
</div>