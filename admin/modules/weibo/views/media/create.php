<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 25/10/16 2:24 PM By Manson
 */
use admin\assets\AppAsset;
use yii\helpers\Html;
use common\helpers\MediaHelper;

AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/js/weibo/create-media.js');
?>

<div id="content" class="content">
    <input type="hidden" name="get_vendor_active_time" value="<?= Yii::$app->urlManager->createUrl(array('weibo/media/get-vendor-active-time'))?>">
    <div class="row">
        <div class="col-md-22">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#default-tab-1" data-toggle="tab" class="create-media">入驻账号</a></li>
                <li class=""><a href="#default-tab-2" data-toggle="tab" class="add-vendor" data-url="<?=Yii::$app->urlManager->createUrl(array('media/vendor/search'))?>">添加媒体主</a></li>
            </ul>

            <div class="tab-content">
                <!--审核资源-->
                <div class="tab-pane fade active in" id="default-tab-1">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                        <div class="panel-body">
                            <!--基础信息-->
                            <legend class="meida-info" style="color: #00acac;">资源信息</legend>
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">*账号名称
                                        <br>
                                        <small class="text-danger">(必填)</small>
                                    </label>
                                    <div class="col-md-3">
                                        <input name="weibo_name" type="text" class="form-control"placeholder="账号名称" />
                                    </div>

                                    <label class="col-md-1 control-label">*粉丝数
                                    </label>
                                    <div class="col-md-2">
                                        <input name="follower_num" type="text" class="form-control"  placeholder="粉丝数" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">*链接
                                        <br>
                                        <small class="text-danger">(必填)</small>
                                    </label>
                                    <div class="col-md-3">
                                        <input name="weibo_url" type="text" class="form-control" placeholder="链接" />
                                    </div>

                                    <label class="col-md-1 control-label">认证</label>
                                    <div class="col-md-2">
                                        <select name="media_level" class="form-control">
                                            <option  value="-1" >不限</option>
                                            <option  value="1" >蓝V</option>
                                            <option  value="2" >黄V</option>
                                            <option  value="3" >草根</option>
                                            <option  value="4" >达人</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">简介</label>
                                    <div class="col-md-3">
                                        <textarea  name="intro" class="form-control"  rows="5"></textarea>
                                    </div>

                                    <label class="col-md-1 control-label">接单备注</label>
                                    <div class="col-md-4">
                                        <textarea name="accept_remark" class="form-control comment" rows="5"></textarea>
                                    </div>
                                </div>
                                <!-- 属性信息-->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">资源分类 *:
                                        <br>
                                        <small class="text-danger">(多选)</small>
                                    </label>
                                    <div class="col-md-8 media-cate">
                                        <?php
                                        //$cate_array =array_filter(explode('#',$data['media_cate']));//选中资源
                                        $mediaCateList = MediaHelper::getMediaCateList();//全部资源
                                        foreach ($mediaCateList as $code => $cate) { ?>
                                            <label class="checkbox-inline ">
                                                <input type="checkbox" name="media_cate" value="<?php echo $code; ?>" class="<?php echo 'one-cate cate-' . $code; ?>"><?php echo $cate; ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">账号地域属性 *:
                                        <br>
                                        <small class="text-danger">(单选)</small>
                                    </label>
                                    <div class="col-md-8 follower-area">
                                        <?php
                                        //$area_array =array_filter(explode('#',$data['follower_area']));//选中资源
                                        $mediaCityList = MediaHelper::getCityList();
                                        foreach ($mediaCityList as $code => $cate) { ?>
                                            <label class="checkbox-inline ">
                                                <input type="checkbox" name="follower_area" value="<?php echo $code; ?>" class="<?php echo 'one-area area-'.$code; ?>"><?php echo $cate; ?>
                                            </label>
                                        <?php }?>
                                    </div>
                                </div>
                                <!--媒体主列表-->
                                <legend class="vendor-list" style="color: #00acac;">媒体主列表
                                    <div style="float: right;">
                                        <button class="btn btn-warning btn-sm btn-to-add-vendor" type="button">添加媒体主</button>
                                    </div>
                                </legend>
                                <table class="table table-vendor">
                                    <thead>
                                    <tr>
                                        <th>首选媒体主</th>
                                        <th>媒体主名称</th>
                                        <th>平台合作价</th>
                                        <th>价格有效期</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table tbody-vendor">
                                    <!--媒体主列表-->
                                    </tbody>
                                </table>
                            </form>

                            <div class="form-group">
                                <label class="col-md-1 control-label"></label>
                                <div class="col-md-9"style="text-align: left;">
                                    <button type="submit" class="btn btn-success submit btn-update-base" data-uuid="" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/create'))?>">保存</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade in" id="default-tab-2">

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
                                                <th style="min-width: 150px">位置</th>
                                                <th style="min-width: 150px">平台合作价(元)</th>
                                                <th style="min-width: 150px">零售价(元)</th>
                                                <th style="min-width: 150px">执行价(元)</th>
                                            </tr>
                                            </thead>
                                            <tbody class="price-info-tbody">
                                            <tr class="one-pos pos-s">
                                                <td>软广直发</td>
                                                <td class="orig-price">
                                                    <div class="input-group">
                                                        <input name="soft_d_orig" type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
                                                <td class="retail-price">
                                                    <div class="input-group">
                                                        <input name="soft_d_retail" type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
                                                <td class="execute-price">
                                                    <div class="input-group">
                                                        <input name="soft_d_execute" type="text" class="form-control col-md-6" value="" placeholder="" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="one-pos pos-m-1">
                                                <td>软广转发</td>
                                                <td class="orig-price">
                                                    <div class="input-group">
                                                        <input name="soft_t_orig"type="text" class="form-control col-md-6" value="" placeholder="" />
                                                    </div>
                                                </td>
                                                <td class="retail-price">
                                                    <div class="input-group">
                                                        <input name="soft_t_retail"type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
                                                <td class="execute-price">
                                                    <div class="input-group">
                                                        <input name="soft_t_execute"type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="one-pos pos-m-2">
                                                <td>微任务直发</td>
                                                <td class="orig-price">
                                                    <div class="input-group">
                                                        <input name="mic_d_orig"type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
                                                <td class="retail-price">
                                                    <div class="input-group">
                                                        <input name="mic_d_retail"type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
                                                <td class="execute-price">
                                                    <div class="input-group">
                                                        <input name="mic_d_execute"type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="one-pos pos-m-3">
                                                <td>微任务转发</td>
                                                <td class="orig-price">
                                                    <div class="input-group">
                                                        <input name="mic_t_orig" type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
                                                <td class="retail-price">
                                                    <div class="input-group">
                                                        <input name="mic_t_retail"type="text" class="form-control col-md-6" value="" placeholder="" />
                                                    </div>
                                                </td>
                                                <td class="execute-price">
                                                    <div class="input-group">
                                                        <input name="mic_t_execute"type="text" class="form-control col-md-6" value=""placeholder="" />
                                                    </div>
                                                </td>
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
                                <button type="button" class="btn btn-success btn-add-vendor" style="display: none" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/add-vendor'))?>">添&nbsp;&nbsp;&nbsp;&nbsp;加</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<?php
$Js = <<<JS
    weiboCreate();
JS;
$this->registerJs($Js);
?>
