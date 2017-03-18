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

$mediaCreateUrl = Yii::$app->urlManager->createUrl(array('weixin/media/create'));
$vendorSearchUrl = Yii::$app->urlManager->createUrl(array('media/vendor/search'));
$uploadMediaWeixin = Yii::$app->urlManager->createUrl(array('weixin/media/upload-in-batch'));
$mediaWeixinVerifySuccListUrl = Yii::$app->urlManager->createUrl(array('weixin/media/verify-succ-list'));
$vendorCreateUrl = Yii::$app->urlManager->createUrl(array('media/vendor/create'));
$weixinCheckExistUrl = Yii::$app->urlManager->createUrl(array('weixin/media/check-exist'));
$getVendorInfoUrl = Yii::$app->urlManager->createUrl(array('weixin/vendor/get-info'));

AppAsset::addScript($this, '@web/js/helpers/default-retail-price.js');
AppAsset::addScript($this, '@web/js/helpers/base-helper.js');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

AppAsset::addScript($this, '@web/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">资源管理</a></li>
        <li class="active">入驻账号</li>
    </ol>

    <h1 class="page-header">入驻账号</h1>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li class="active"><a href="#create-one-weixin-media" data-toggle="tab">入驻账号</a></li>
                <li class="" style="display: none"><a href="#upload-weixin-media-in-batch" data-toggle="tab">批量导入</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="create-one-weixin-media">
                    <div class="panel panel-inverse">
                        <div class="panel-body">
                            <form class="form-horizontal form-media">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">公众号ID *: </label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control weixin-id" placeholder="请输入公众号ID（必填）"
                                               maxlength="45"/>
                                    </div>
                                    <div class="col-md-2" style="display: none">
                                        <a href="javascript:;" class="btn btn-primary btn-check-exist">检测是否存在</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">公众号名称 *: </label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control weixin-name" placeholder="请输入公众号名称（必填）"
                                               maxlength="45"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">粉丝数 *:
                                        <br>
                                    </label>

                                    <div class="col-md-5">
                                        <input type="text" class="form-control follower-num" placeholder="请输入公众号粉丝数（必填）"
                                               maxlength="45"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">资源分类 *:
                                        <br>
                                        <small class="text-danger">(多选)</small>
                                    </label>
                                    <div class="col-md-8 media-cate">
                                        <?php
                                        $mediaCateList = MediaHelper::getMediaCateList();
                                        foreach ($mediaCateList as $code => $cate) { ?>
                                            <label class="checkbox-inline "><input type="checkbox"
                                                                                   value="<?php echo $code; ?>"
                                                                                   class="<?php echo 'one-cate cate-' . $code; ?>"><?php echo $cate; ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">账号地域属性 *:
                                    </label>
                                    <div class="col-md-8 follower-area">
                                        <label class="checkbox-inline"><input type="checkbox" value="0"
                                                                              class="one-area area-0" data-province="-1"
                                                                              checked>全国</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="1"
                                                                              class="one-area area-1"
                                                                              data-province="-1">北京</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="9"
                                                                              class="one-area area-9"
                                                                              data-province="-1">上海</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="289"
                                                                              class="one-area area-289"
                                                                              data-province="19">广州</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="291"
                                                                              class="one-area area-291"
                                                                              data-province="19">深圳</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="175"
                                                                              class="one-area area-175"
                                                                              data-province="11">杭州</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="2"
                                                                              class="one-area area-2"
                                                                              data-province="-1">天津</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="275"
                                                                              class="one-area area-275"
                                                                              data-province="18">长沙</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="258"
                                                                              class="one-area area-258"
                                                                              data-province="17">武汉</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="162"
                                                                              class="one-area area-162"
                                                                              data-province="10">南京</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="22"
                                                                              class="one-area area-22"
                                                                              data-province="-1">重庆</label>
                                        <label class="checkbox-inline"><input type="checkbox" value="35"
                                                                              class="one-area area-35"
                                                                              data-province="-1">海外</label>
                                        <label class="checkbox-inline"><input type="checkbox"
                                                                              class="one-area area-other">其他城市</label>

                                    </div>
                                </div>

                                <div class="form-group other-area-select">
                                    <label class="col-md-3 control-label">&nbsp;&nbsp;&nbsp;</label>
                                    <div class="col-md-9">
                                        <select class="form-control input-inline follower-area-province"
                                                style="display: none">
                                            <option value="10">江苏</option>
                                            <option value="11">浙江</option>
                                            <option value="12">安徽</option>
                                            <option value="13">福建</option>
                                            <option value="14">江西</option>
                                            <option value="15">山东</option>
                                            <option value="16">河南</option>
                                            <option value="17">湖北</option>
                                            <option value="18">湖南</option>
                                            <option value="19">广东</option>
                                            <option value="30">宁夏</option>
                                            <option value="31">新疆</option>
                                            <option value="4">山西</option>
                                            <option value="3">河北</option>
                                            <option value="5">内蒙古</option>
                                            <option value="6">辽宁</option>
                                            <option value="7">吉林</option>
                                            <option value="8">黑龙江</option>
                                            <option value="20">广西</option>
                                            <option value="21">海南</option>
                                            <option value="23">四川</option>
                                            <option value="24">贵州</option>
                                            <option value="25">云南</option>
                                            <option value="26">西藏</option>
                                            <option value="27">陕西</option>
                                            <option value="28">甘肃</option>
                                            <option value="29">青海</option>
                                            <option value="32">台湾</option>
                                            <option value="33">香港</option>
                                            <option value="34">澳门</option>
                                            <option value="34">其他</option>
                                        </select>
                                        <select class="form-control input-inline follower-area-city"
                                                style="display: none">
                                            <option value="-1" selected>不限</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">自媒体类型 *:
                                    </label>
                                    <div class="col-md-6 media-weixin-belong-type">
                                        <?php
                                        $mediaWeixinBelongTypeList = MediaHelper::getMediaWeixinBelongType();
                                        foreach ($mediaWeixinBelongTypeList as $code => $type) { ?>
                                            <label class="checkbox-inline"><input type="checkbox"
                                                                                   value="<?php echo $code; ?>"
                                                                                   class="<?php echo 'one-type type-' . $code; ?>"><?php echo $type; ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">接单备注:
                                    </label>
                                    <div class="col-md-6">
                                        <textarea class="form-control comment" placeholder="请输入接单备注信息" rows="3"></textarea>
                                    </div>
                                </div>

                                <br/>
                                <br/>
                                <br/>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">
                                        媒体主列表*：
                                    </label>
                                    <div class="col-md-9 media-vendor">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>首选</th>
                                                <th>媒体主名称</th>
                                                <th>报价</th>
                                                <th>价格有效期</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>

                                            <tbody class="add-vendor-list">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="" style="text-align: right;">
                                        <button class="btn btn-primary btn-xs btn-add-media-vendor" type="button">添加媒体主
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <button class="btn btn-success btn-lg btn-commit" type="button">保&nbsp;&nbsp;&nbsp;存
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="upload-weixin-media-in-batch">
                    <div class="panel panel-inverse">
                        <div class="panel-body">
                            <?php Pjax::begin();
                            $Js = <<<JS

            //分页处理样式
            $(".pagination li a").each(function(){
                $(this).removeAttr("href");
                $(this).attr("style","cursor:pointer;");
            });
            //分页处理
            $(".pagination li a").click(function(){
                $(".form-upload-search input.page").attr("value", $(this).attr("data-page"));
                $(".form-upload-search").submit();
            });
            //查询计划
            $(".form-upload-search .btn-submit").click(function(){
                $(".form-upload-search").submit();
            });
JS;
                            $this->registerJs($Js);
                            ?>
                            <div class="panel panel-inverse pjax-area">
                                <?= Html::beginForm(['media/upload-in-batch'], 'post', ['data-pjax' => '', 'class' => 'form-upload-in-batch']); ?>
                                <div class="form-upload-search">
                                    <div class="row m-l-30 m-r-30">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>导入时间</label>
                                                <input type="text" name="create-time-range"
                                                       value="<?php echo Yii::$app->request->post('create-time-range', ''); ?>"
                                                       placeholder="" class="form-control input-sm create-time-range">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row m-l-30">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="button" class="btn btn-primary btn-submit"
                                                       value="查&nbsp;&nbsp;&nbsp;&nbsp;询"/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary btn-upload">
                                                    <i class="fa fa-upload"></i>
                                                    <span>批量导入</span>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input class="page" type="hidden" name="page"
                                       value="<?php echo Yii::$app->request->post('page', 0); ?>">
                                <?= Html::endForm() ?>

                                <div class="panel-body">
                                    <?php if ($dataProvider !== null) { ?>
                                        <?= GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'pager' => [
                                                'nextPageLabel' => '下一页',
                                                'prevPageLabel' => '上一页',
                                                'firstPageLabel' => '首页',
                                                'lastPageLabel' => '尾页',
                                                'maxButtonCount' => 10,
                                            ],
                                            'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-upload-item'],
                                            'columns' => [
                                                [
                                                    'class' => 'yii\grid\SerialColumn',
                                                    'headerOptions' => ['data-sort-ignore' => 'true']
                                                ],
                                                [
                                                    'header' => '总条数',
                                                    'format' => 'html',
                                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                                    'contentOptions' => ['class' => 'total-cnt'],
                                                    'value' => function ($model, $key, $index, $column) {
                                                        return $model['total_cnt'];
                                                    },
                                                ],
                                                [
                                                    'header' => '成功条数',
                                                    'format' => 'html',
                                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                                    'contentOptions' => ['class' => 'succ-cnt'],
                                                    'value' => function ($model, $key, $index, $column) {
                                                        return $model['succ_cnt'];
                                                    },
                                                ],
                                                [
                                                    'header' => '失败条数',
                                                    'format' => 'html',
                                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                                    'contentOptions' => ['class' => 'fail-cnt'],
                                                    'value' => function ($model, $key, $index, $column) {
                                                        return $model['fail_cnt'];
                                                    },
                                                ],
                                                [
                                                    'header' => '操作人',
                                                    'format' => 'html',
                                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                                    'contentOptions' => ['class' => 'operator-name'],
                                                    'value' => function ($model, $key, $index, $column) {
                                                        return $model['operator_name'];
                                                    },
                                                ],
                                                [
                                                    'header' => '操作时间',
                                                    'format' => 'text',
                                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                                    'contentOptions' => ['class' => 'create-time'],
                                                    'value' => function ($model, $key, $index, $column) {
                                                        return date('Y-m-d H:i', $model['create_time']);
                                                    },
                                                ]
                                            ]
                                        ]);
                                    } ?>
                                </div>
                            </div>
                            <?php Pjax::end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 添加媒体主 -->
<div class="modal fade" id="modal-add-media-vendor" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog modal-blg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">添加媒体主</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-inverse">
                    <div class="panel-body">

                        <div class="alert alert-danger fade in m-b-15">
                            <strong>注意:  </strong>
                            1. 可从系统中搜索已经存在的媒体主  2. 如果在系统不存在,可以"新建媒体主"
                            <span class="close" data-dismiss="alert">×</span>
                        </div>

                    </div>

                    <form class="form-horizontal form-search-media-vendor">
                        <div class="form-group">
                            <label class="col-md-3 control-label">媒体主 *: </label>
                            <div class="col-md-5">
                                <input type="text" class="form-control input-name" name="search_vendor_name"
                                       placeholder="媒体主名称\联系方式\QQ\微信" />
                                <span class="error-msg" style="color:red;display:none;font-size:16px;">媒体主不存在，请去 "新建媒体主"</span>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-search" type="button">搜&nbsp;&nbsp;&nbsp;索
                                </button>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary btn-create-vendor" type="button">新建媒体主</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="panel panel-inverse area-select-vendor">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <h1 style="font-size:16px;color:#00acac;">搜索结果列表</h1>
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
                        </div>

                        <form class="form-horizontal form-vendor-price-list">
                            <table class="table table-bordered table-price">
                                <thead>
                                <tr>
                                    <th>位置</th>
                                    <th>投放形式</th>
                                    <th>平台合作价(元)</th>
                                    <th>零售价(元)</th>
                                    <th>执行价(元)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="one-pos pos-s">
                                    <td>单图文</td>
                                    <td class="pub-type">
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-s-pub-type" value="1" checked="">
                                            纯发布
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-s-pub-type" value="2">
                                            原创+发布
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-s-pub-type" value="0">
                                            不接单
                                        </label>
                                    </td>
                                    <td class="orig-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field origin-price-val-min"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                    <td class="retail-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field retail-price-val-min"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                    <td class="execute-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field execute-price-val"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="one-pos pos-m-1">
                                    <td>多图文头条</td>
                                    <td class="pub-type">
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-1-pub-type" value="1" checked="">
                                            纯发布
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-1-pub-type" value="2">
                                            原创+发布
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-1-pub-type" value="0">
                                            不接单
                                        </label>
                                    </td>
                                    <td class="orig-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field origin-price-val-min"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                    <td class="retail-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field retail-price-val-min"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                    <td class="execute-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field execute-price-val"
                                                   placeholder="必填项">
                                        </div>
                                    </td>

                                </tr>
                                <tr class="one-pos pos-m-2">
                                    <td>多图文第2条</td>
                                    <td class="pub-type">
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-2-pub-type" value="1" checked="">
                                            纯发布
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-2-pub-type" value="2">
                                            原创+发布
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-2-pub-type" value="0">
                                            不接单
                                        </label>
                                    </td>
                                    <td class="orig-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field origin-price-val-min"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                    <td class="retail-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field retail-price-val-min"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                    <td class="execute-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field execute-price-val"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="one-pos pos-m-3">
                                    <td>多图文第3-N条</td>
                                    <td class="pub-type">
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-3-pub-type" value="1" checked="">
                                            纯发布
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-3-pub-type" value="2">
                                            原创+发布
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pos-m-3-pub-type" value="0">
                                            不接单
                                        </label>
                                    </td>
                                    <td class="orig-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field origin-price-val-min"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                    <td class="retail-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field retail-price-val-min"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                    <td class="execute-price">
                                        <div class="input-group">
                                            <input type="text" class="form-control is-price-field execute-price-val"
                                                   placeholder="必填项">
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>


                            <div class="form-horizontal form-vendor">
                                <div class="row">
                                    <div class="col-md-5 m-l-40">
<!--                                        <div class="form-group">-->
<!--                                            <label class="col-md-4 control-label">媒体主名称:</label>-->
<!--                                            <div class="col-md-5">-->
<!--                                                <p class="form-control-static vendor-name"></p>-->
<!--                                            </div>-->
<!--                                        </div>-->

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">资源所属关系:</label>
                                            <div class="col-md-4">
                                                <select name="media_ownership" class="form-control media-ownership">
                                                    <?php
                                                    $ownershipList = MediaHelper::getMediaOwnershipList();
                                                    foreach ($ownershipList as $code => $ownership) { ?>
                                                        <option
                                                            value="<?php echo $code; ?>"><?php echo $ownership; ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">配合度:</label>
                                            <div class="col-md-4">
                                                <select name="coop_level"  class="form-control execute-level">
                                                    <option value="-1">未知</option>
                                                    <option value="1">高</option>
                                                    <option value="2">中</option>
                                                    <option value="3">低</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">账期:</label>
                                            <div class="col-md-4">
                                                <select  name="pay_period" class="form-control pay-period">
                                                    <option value="-1">未知</option>
                                                    <option value="1">年框</option>
                                                    <option value="2">3个月以上</option>
                                                    <option value="3">1-3个月</option>
                                                    <option value="4">1-4周</option>
                                                    <option value="5">无</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">报价有效期:</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control active-end-time" placeholder="" value="请填写报价有效期(必填项)">
                                                <br>
                                                <a href="javascript:;" class="btn btn-primary btn-xs m-r-5 sync-latest-active-end-time">同步最新报价有效期</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="" style="text-align: center">
                                    <a href="javascript:;" class="btn btn-success btn-lg btn-add-vendor">确&nbsp;&nbsp;&nbsp;&nbsp;认</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$weixinToCreateJs = <<<JS

            // 控制左侧导航选中
            if(!$('#weixin .media-manage .to-create').hasClass('active')){
                $('.menu-level-1').each(function(){
                     $(this).removeClass('active');
                });
                $('.menu-level-2').each(function(){
                     $(this).removeClass('active');
                });
                $('.menu-level-3').each(function(){
                     $(this).removeClass('active');
                });

                $('#weixin.menu-level-1').addClass('active');
                $('#weixin.menu-level-1 .menu-level-2.media-manage').addClass('active');
                $('#weixin.menu-level-1 .menu-level-2.media-manage .menu-level-3.to-create').addClass('active');
            }

            $('#create-one-weixin-media .form-media .weixin-id').blur(function(){
                 var weixin_id = $.trim($('#create-one-weixin-media .form-media .weixin-id').val());
                 if(weixin_id == ''){
                     $('#create-one-weixin-media .form-media .btn-commit').attr('disabled', false);
                     return false;
                 }
                 $.ajax({
                        url: '$weixinCheckExistUrl',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {weixin_id: weixin_id},
                        success: function (resp) {
                            if(resp.err_code == 2){
                                swal({title: "", text: resp.err_msg, type: "error"});
                                $('#create-one-weixin-media .form-media .btn-commit').attr('disabled', true);
                                return false;
                            } else if(resp.err_code == 3){
                                $('#create-one-weixin-media .form-media .btn-commit').attr('disabled', false);
                            } else {
                                swal({title: "", text: "系统出错", type: "error"});
                                return false;
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                            return false;
                        }
                 });
            });

            // 检测账号是否存在
            $('#create-one-weixin-media .form-media .btn-check-exist').on('click', function(){
                var weixin_id = $.trim($('#create-one-weixin-media .form-media .weixin-id').val());
                if(weixin_id == ''){
                    swal({title: "", text: "公众号ID不能为空", type: "error"});
                    return false;
                }
                $.ajax({
                        url: '$weixinCheckExistUrl',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {weixin_id: weixin_id},
                        success: function (resp) {
                            if(resp.err_code == 2){
                                swal({title: "", text: "该公众号系统已经存在!", type: "error"});
                                return false;
                            } else if(resp.err_code == 3){
                                swal({title: "", text: "该公众号系统中不存在,可以录入", type: "success"});
                            } else {
                                swal({title: "", text: "系统出错", type: "error"});
                                return false;
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                            return false;
                        }
                });
            });

            $("#create-one-weixin-media .form-media .follower-area .checkbox-inline input").click(function(){
                $(".follower-area .checkbox-inline input").not(this).attr("checked", false);
                $('#create-one-weixin-media .form-media .follower-area-province').hide();
                $('#create-one-weixin-media .form-media .follower-area-city').hide();
            });
            $('#create-one-weixin-media .form-media .other-area-select .follower-area-province').change(function() {
                var provinceCode = $(this).val();
                var followerAreaCitySelect = $('#create-one-weixin-media .form-media .other-area-select .follower-area-city');
                var cityList = getCityConfigWithProvinceCode(provinceCode);
                followerAreaCitySelect.find('option').remove();
                followerAreaCitySelect.append($('<option value=\"-1\" selected>不限</option>'));
                for(var i = 0; i < cityList.length; i++){
                    var code = cityList[i]['code'];
                    var label = cityList[i]['label'];
                    followerAreaCitySelect.append($('<option value=\"' + code + '\">' + label + '</option>'));
                }
            });
            $('#create-one-weixin-media .form-media .follower-area .area-other').change(function() {
                if($(this).is(':checked')){
                    $('#create-one-weixin-media .form-media .follower-area-province').show();
                    $('#create-one-weixin-media .form-media .follower-area-city').show();
                    $('#create-one-weixin-media .form-media').find('.other-area-select .follower-area-province').trigger('change');
                }else{
                    $('#create-one-weixin-media .form-media .follower-area-province').hide();
                    $('#create-one-weixin-media .form-media .follower-area-city').hide();
                }
            });

            //自媒体类型选择
            $(".media-weixin-belong-type .checkbox-inline input").click(function(){
                $(".media-weixin-belong-type .checkbox-inline input").not(this).attr("checked", false);
            });

            var modal_add_vendor = $('#modal-add-media-vendor');
            var area_form_search_vendor = modal_add_vendor.find('.form-search-media-vendor');
            var area_select_vendor = modal_add_vendor.find('.area-select-vendor');

            //添加媒体主button
            $('#create-one-weixin-media .form-media .btn-add-media-vendor').on('click', function(){
                area_select_vendor.hide();
                area_form_search_vendor.find('.error-msg').hide();
                modal_add_vendor.find("input[name='search_vendor_name']").val('');
                area_select_vendor.find(".vendor-search-result").html("");
                //媒体主默认列表
                     $.ajax({
                    url: '$vendorSearchUrl',
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {vendor_search: ""},
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "保存失败！", text: "请联系系统管理员", type: "error"});
                            return false;
                        } else {
                            var vendor_list = resp.vendor_list;
                            if(vendor_list.length ==0){
                                area_form_search_vendor.find('.error-msg').show();
                                return false;
                            } else {
                                area_form_search_vendor.find('.error-msg').hide();
                                for(var i = 0; i < vendor_list.length; i++){
                                    var vendor = vendor_list[i];
                                    var vendor_uuid = vendor['vendor_uuid'];
                                    var vendor_name = vendor['vendor_name'];

                                    //注册渠道
                                    var register_type = vendor['register_type'];
                                    var register_type_label = '';
                                    if(register_type == 1){
                                        register_type_label = '前端注册';
                                    } else if(register_type == 2){
                                        register_type_label = 'admin录入';
                                    } else {
                                        register_type_label = '未知';
                                    }

                                    // 联系人
                                    var contact_info_label = '';
                                    var contact_info = vendor.contact_info;
                                    if(contact_info == ''){
                                        contact_info_label = '未填写';
                                    } else {
                                        var contact_info_arr = JSON.parse(contact_info);
                                        for(var j = 0; j < contact_info_arr.length; j++){
                                            var contact_person = contact_info_arr[j]['contact_person'];
                                            var contact_phone = contact_info_arr[j]['contact_phone'].length == 0 ? '无' : contact_info_arr[j]['contact_phone'];
                                            var weixin = contact_info_arr[j]['weixin'].length == 0 ? '无' : contact_info_arr[j]['weixin'];
                                            var qq = contact_info_arr[j]['qq'].length == 0 ? '无' : contact_info_arr[j]['qq'];
                                            contact_info_label += '联系人:' + contact_person + ', 电话:' + contact_phone + ', 微信:' + weixin + ', QQ:' + qq + '<br>';
                                        }
                                    }

                                    var comment = vendor.comment;

                                    var one_vendor =  "<tr data-vendor='" + vendor_uuid + "' data-name='" + vendor_name + "'>"+
                                                "<td>" + vendor_name + "</td>"+
                                                "<td>" + register_type_label + "</td>"+
                                                "<td>" + contact_info_label + "</td>"+
                                                "<td>" + comment + "</td>"+
                                                "<td><input type='checkbox' class='vendor-select'></td>"+
                                                "</tr>";
                                    area_select_vendor.find(".vendor-search-result").append(one_vendor);
                                    area_select_vendor.show();
                                    area_select_vendor.find(".form-vendor-price-list").hide();
                                }
                            }
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown){
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                });
                modal_add_vendor.modal('show');
            });

            //enter键搜索
            modal_add_vendor.find("input[name='search_vendor_name']").keydown(function(event){
                if(event.keyCode==13){
                    area_form_search_vendor.find(".btn-search").click();
                    return false;
                }
            });
            //媒体主页面的搜索button
            area_form_search_vendor.find(".btn-search").click(function(){
                var vendor_search = modal_add_vendor.find("input[name='search_vendor_name']").val();
                if(vendor_search ==""){
                    swal({title: "", text: "请输入查询条件", type: "error"});
                    return false;
                }
                area_select_vendor.find('.vendor-search-result').html('');
                $.ajax({
                    url: '$vendorSearchUrl',
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {vendor_search: vendor_search},
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "保存失败！", text: "请联系系统管理员", type: "error"});
                            return false;
                        } else {
                            var vendor_list = resp.vendor_list;
                            if(vendor_list.length ==0){
                                area_form_search_vendor.find('.error-msg').show();
                                return false;
                            } else {
                                area_form_search_vendor.find('.error-msg').hide();
                                for(var i = 0; i < vendor_list.length; i++){
                                    var vendor = vendor_list[i];
                                    var vendor_uuid = vendor['vendor_uuid'];
                                    var vendor_name = vendor['vendor_name'];

                                    //注册渠道
                                    var register_type = vendor['register_type'];
                                    var register_type_label = '';
                                    if(register_type == 1){
                                        register_type_label = '前端注册';
                                    } else if(register_type == 2){
                                        register_type_label = 'admin录入';
                                    } else {
                                        register_type_label = '未知';
                                    }

                                    // 联系人
                                    var contact_info_label = '';
                                    var contact_info = vendor.contact_info;
                                    if(contact_info == ''){
                                        contact_info_label = '未填写';
                                    } else {
                                        var contact_info_arr = JSON.parse(contact_info);
                                        for(var j = 0; j < contact_info_arr.length; j++){
                                            var contact_person = contact_info_arr[j]['contact_person'];
                                            var contact_phone = contact_info_arr[j]['contact_phone'].length == 0 ? '无' : contact_info_arr[j]['contact_phone'];
                                            var weixin = contact_info_arr[j]['weixin'].length == 0 ? '无' : contact_info_arr[j]['weixin'];
                                            var qq = contact_info_arr[j]['qq'].length == 0 ? '无' : contact_info_arr[j]['qq'];
                                            contact_info_label += '联系人:' + contact_person + ', 电话:' + contact_phone + ', 微信:' + weixin + ', QQ:' + qq + '<br>';
                                        }
                                    }

                                    var comment = vendor.comment;

                                    var one_vendor =  "<tr data-vendor='" + vendor_uuid + "' data-name='" + vendor_name + "'>"+
                                                "<td>" + vendor_name + "</td>"+
                                                "<td>" + register_type_label + "</td>"+
                                                "<td>" + contact_info_label + "</td>"+
                                                "<td>" + comment + "</td>"+
                                                "<td><input type='checkbox' class='vendor-select'></td>"+
                                                "</tr>";
                                    area_select_vendor.find(".vendor-search-result").append(one_vendor);
                                    area_select_vendor.show();
                                    area_select_vendor.find(".form-vendor-price-list").hide();
                                }
                            }
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown){
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                });
            });

            // 新建媒体主快捷按钮
            $("#modal-add-media-vendor .btn-create-vendor").click(function(){
                window.open('$vendorCreateUrl');
            });

            area_select_vendor.find('.origin-price-val-min').blur(function(){
                var origin_price_val_min = $.trim($(this).val());
                if(origin_price_val_min == '' || isNaN(origin_price_val_min)){
                    $(this).val('');
                    $(this).closest('.one-pos').find('.retail-price-val-min').val('');
                } else {
                    var retail_price_val_min = getDefaultRetailPrice(origin_price_val_min);
                    $(this).closest('.one-pos').find('.retail-price-val-min').val(retail_price_val_min);
                }
            });

            //选择媒体主的复选框
            area_select_vendor.on("click", ".vendor-select", function() {
                area_select_vendor.find('.vendor-select').not(this).parents("tr").remove();
                if($(this).is(":checked")){
                    area_select_vendor.find('.form-vendor-price-list').show();
                    area_select_vendor.find('.active-end-time').val('');
                    area_select_vendor.find('.is-price-field').val('');
                    area_select_vendor.find("input[name='pos-s-pub-type']").eq(0).attr("checked", "checked");
                    area_select_vendor.find("input[name='pos-m-1-pub-type']").eq(0).attr("checked", "checked");
                    area_select_vendor.find("input[name='pos-m-2-pub-type']").eq(0).attr("checked", "checked");
                    area_select_vendor.find("input[name='pos-m-3-pub-type']").eq(0).attr("checked", "checked");
                    modal_add_vendor.find('input').removeAttr('readonly');
                }else{
                    area_select_vendor.find('.form-vendor-price-list').hide();
                    $('#create-one-weixin-media .form-media .btn-add-media-vendor').click();
                }
            });

            //输入报价的复选框
            area_select_vendor.find("input[name='pos-s-pub-type']").click(function(){
                area_select_vendor.find('.pos-s .is-price-field').val("");
                if($(this).val() == '1'|| $(this).val() == '2'){
                    area_select_vendor.find('.pos-s .is-price-field').removeAttr('readonly');
                }
                if($(this).val() == '0'){
                    area_select_vendor.find('.pos-s .is-price-field').attr('readonly', 'true');
                    area_select_vendor.find('.pos-s .is-price-field').val(0);
                }
            });
            area_select_vendor.find("input[name='pos-m-1-pub-type']").click(function(){
                area_select_vendor.find('.pos-m-1 .is-price-field').val('');
                if($(this).val() == '1'|| $(this).val() == '2' ){
                    area_select_vendor.find('.pos-m-1 .is-price-field').removeAttr('readonly');
                }
                if($(this).val() == '0'){
                    area_select_vendor.find('.pos-m-1 .is-price-field').attr('readonly', 'true');
                    area_select_vendor.find('.pos-m-1 .is-price-field').val(0);
                }
            });
            area_select_vendor.find("input[name='pos-m-2-pub-type']").click(function(){
                area_select_vendor.find('.pos-m-2 .is-price-field').val('');
                if($(this).val() == '1'|| $(this).val() == '2'){
                    area_select_vendor.find('.pos-m-2 .is-price-field').removeAttr('readonly');
                }
                if($(this).val() == '0'){
                    area_select_vendor.find('.pos-m-2 .is-price-field').attr('readonly', 'true');
                    area_select_vendor.find('.pos-m-2 .is-price-field').val(0);
                }
            });
            area_select_vendor.find("input[name='pos-m-3-pub-type']").click(function(){
                area_select_vendor.find('.pos-m-3 .is-price-field').val('');
                if($(this).val() == '1'|| $(this).val() == '2'){
                    area_select_vendor.find('.pos-m-3 .is-price-field').removeAttr('readonly');
                }
                if($(this).val() == '0'){
                    area_select_vendor.find('.pos-m-3 .is-price-field').attr('readonly', 'true');
                    area_select_vendor.find('.pos-m-3 .is-price-field').val(0);
                }
            });

            //平台合作价同步到执行价
            area_select_vendor.find('.pos-s .origin-price-val-min').blur(function(){
                area_select_vendor.find('.pos-s .execute-price-val').val($(this).val());
            });
            area_select_vendor.find('.pos-m-1 .origin-price-val-min').blur(function(){
                area_select_vendor.find('.pos-m-1 .execute-price-val').val($(this).val());
            });
            area_select_vendor.find('.pos-m-2 .origin-price-val-min').blur(function(){
                area_select_vendor.find('.pos-m-2 .execute-price-val').val($(this).val());
            });
            area_select_vendor.find('.pos-m-3 .origin-price-val-min').blur(function(){
                area_select_vendor.find('.pos-m-3 .execute-price-val').val($(this).val());
            });

            //选择媒体主的确认button
            var vendor_price_data_obj = {};
            var pub_config_obj = {};
            area_select_vendor.find('.btn-add-vendor').click(function(){
                var vendor_price_info_json = {};
                var vendor_uuid = "";
                var vendor_name = "";    //媒体主名称
                var media_ownership = $("select[name=media_ownership]").children("option:selected").val() ;  //资源所属关系
                var pay_period = $("select[name=pay_period]").children("option:selected").val();       //账期
                var coop_level = $("select[name=coop_level]").children("option:selected").val();       //配合度
                var active_end_time = $.trim(area_select_vendor.find(".active-end-time").val()); //价格有效期
                    //active_end_time = Date.parse(new Date(active_end_time))/1000;
                var pos_radio_0 = area_select_vendor.find(".pos-s").find("input[type='radio']:checked").val();  //单图文发布形式
                var pos_radio_1 = area_select_vendor.find(".pos-m-1").find("input[type='radio']:checked").val();  //多图文头条发布形式
                var pos_radio_2 = area_select_vendor.find(".pos-m-2").find("input[type='radio']:checked").val();  //多图文第2条发布形式
                var pos_radio_3 = area_select_vendor.find(".pos-m-3").find("input[type='radio']:checked").val();  //多图文3-N发布形式
                var has_origin_pub = 0;
                var has_direct_pub = 0;
                if(pos_radio_0 == 2 || pos_radio_1 == 2 || pos_radio_2 == 2 || pos_radio_3 == 2 ){
                    has_origin_pub = 1;
                }
                if(pos_radio_0 == 1 ||pos_radio_1 == 1 || pos_radio_2 == 1 || pos_radio_3 == 1 ){
                    has_direct_pub = 1;
                }
                var pos_radio_0_text_1 = $.trim(area_select_vendor.find(".pos-s .origin-price-val-min").val());
                var pos_radio_0_text_2 = $.trim(area_select_vendor.find(".pos-s .retail-price-val-min").val());
                var pos_radio_0_text_3 = $.trim(area_select_vendor.find(".pos-s .execute-price-val").val());
                var pos_radio_1_text_1 = $.trim(area_select_vendor.find(".pos-m-1 .origin-price-val-min").val());
                var pos_radio_1_text_2 = $.trim(area_select_vendor.find(".pos-m-1 .retail-price-val-min").val());
                var pos_radio_1_text_3 = $.trim(area_select_vendor.find(".pos-m-1 .execute-price-val").val());
                var pos_radio_2_text_1 = $.trim(area_select_vendor.find(".pos-m-2 .origin-price-val-min").val());
                var pos_radio_2_text_2 = $.trim(area_select_vendor.find(".pos-m-2 .retail-price-val-min").val());
                var pos_radio_2_text_3 = $.trim(area_select_vendor.find(".pos-m-2 .execute-price-val").val());
                var pos_radio_3_text_1 = $.trim(area_select_vendor.find(".pos-m-3 .origin-price-val-min").val());
                var pos_radio_3_text_2 = $.trim(area_select_vendor.find(".pos-m-3 .retail-price-val-min").val());
                var pos_radio_3_text_3 = $.trim(area_select_vendor.find(".pos-m-3 .execute-price-val").val());
                vendor_price_info_json['pos_s_radio']=pos_radio_0;   //单图文发布形式
                vendor_price_info_json['pos_m_1_radio']=pos_radio_1; //多图文头条发布形式
                vendor_price_info_json['pos_m_2_radio']=pos_radio_2; //多图文第2条发布形式
                vendor_price_info_json['pos_m_3_radio']=pos_radio_3; //多图文3-N发布形式
                vendor_price_info_json['pos_s_orig_price']=pos_radio_0_text_1;
                vendor_price_info_json['pos_s_retail_price']=pos_radio_0_text_2;
                vendor_price_info_json['pos_s_execute_price']=pos_radio_0_text_3;
                vendor_price_info_json['pos_m_1_orig_price']=pos_radio_1_text_1;
                vendor_price_info_json['pos_m_1_retail_price']=pos_radio_1_text_2;
                vendor_price_info_json['pos_m_1_execute_price']=pos_radio_1_text_3;
                vendor_price_info_json['pos_m_2_orig_price']=pos_radio_2_text_1;
                vendor_price_info_json['pos_m_2_retail_price']=pos_radio_2_text_2;
                vendor_price_info_json['pos_m_2_execute_price']=pos_radio_2_text_3;
                vendor_price_info_json['pos_m_3_orig_price']=pos_radio_3_text_1;
                vendor_price_info_json['pos_m_3_retail_price']=pos_radio_3_text_2;
                vendor_price_info_json['pos_m_3_execute_price'] = pos_radio_3_text_3;
                vendor_price_info_json['active_end_time'] = active_end_time;
                vendor_price_info_json['has_origin_pub'] = has_origin_pub;
                vendor_price_info_json['has_direct_pub'] = has_direct_pub;
                vendor_price_info_json['media_ownership'] = media_ownership;
                vendor_price_info_json['pay_period'] = pay_period;
                vendor_price_info_json['coop_level'] = coop_level;
                pub_config_obj['pos_s'] = { 'pub_type': parseInt(pos_radio_0),
                                            'orig_price_min': parseFloat(pos_radio_0_text_1),
                                            'orig_price_max': parseFloat(pos_radio_0_text_1),
                                            'retail_price_min': parseFloat(pos_radio_0_text_2),
                                            'retail_price_max': parseFloat(pos_radio_0_text_2),
                                            'execute_price': parseFloat(pos_radio_0_text_3),
                                            'active_end_time': active_end_time
                                          };

                pub_config_obj['pos_m_1'] = { 'pub_type': parseInt(pos_radio_1),
                                              'orig_price_min': parseFloat(pos_radio_1_text_1),
                                              'orig_price_max': parseFloat(pos_radio_1_text_1),
                                              'retail_price_min': parseFloat(pos_radio_1_text_2),
                                              'retail_price_max': parseFloat(pos_radio_1_text_2),
                                              'execute_price': parseFloat(pos_radio_1_text_3),
                                              'active_end_time': active_end_time
                                            };

                pub_config_obj['pos_m_2'] = { 'pub_type': parseInt(pos_radio_2),
                                              'orig_price_min': parseFloat(pos_radio_2_text_1),
                                              'orig_price_max': parseFloat(pos_radio_2_text_1),
                                              'retail_price_min': parseFloat(pos_radio_2_text_2),
                                              'retail_price_max': parseFloat(pos_radio_2_text_2),
                                              'execute_price': parseFloat(pos_radio_2_text_3),
                                              'active_end_time': active_end_time
                                            };

                pub_config_obj['pos_m_3'] = { 'pub_type': parseInt(pos_radio_3),
                                              'orig_price_min': parseFloat(pos_radio_3_text_1),
                                              'orig_price_max': parseFloat(pos_radio_3_text_1),
                                              'retail_price_min': parseFloat(pos_radio_3_text_2),
                                              'retail_price_max': parseFloat(pos_radio_3_text_2),
                                              'execute_price': parseFloat(pos_radio_3_text_3),
                                              'active_end_time': active_end_time
                                            };
                //单图文报价
                if(pos_radio_0 == '0'){
                    var pos_s_price = "【单图文 - "+switch_radio(pos_radio_0)+"】";
                }else {
                    var pos_s_price = "【单图文 - "+switch_radio(pos_radio_0)+"】报价："+pos_radio_0_text_1+"; 零售价："+pos_radio_0_text_2;
                }
                //多图文头条报价
                if(pos_radio_1 == '0'){
                    var pos_m_1_price = "【多图文头条 - "+switch_radio(pos_radio_1)+"】";
                }else {
                    var pos_m_1_price = "【多图文头条 - "+switch_radio(pos_radio_1)+"】报价："+pos_radio_1_text_1+"; 零售价："+pos_radio_1_text_2;
                }
                //多图文第二条报价
                if(pos_radio_2 == '0'){
                    var pos_m_2_price = "【多图文第2条 - "+switch_radio(pos_radio_2)+"】";
                }else {
                    var pos_m_2_price = "【多图文第2条 - "+switch_radio(pos_radio_2)+"】报价："+pos_radio_2_text_1+"; 零售价："+pos_radio_2_text_2;
                }
                //多图文头其他报价
                if(pos_radio_3 == '0'){
                    var pos_m_3_price = "【多图文3-N条 - "+switch_radio(pos_radio_3)+"】";
                }else {
                    var pos_m_3_price = "【多图文3-N条 - "+switch_radio(pos_radio_3)+"】报价："+pos_radio_3_text_1+"; 零售价："+pos_radio_3_text_2;
                }

                var is_must_null = "";
                $("#modal-add-media-vendor .area-select-vendor input").each(function(){
                    if($(this).val()==""){
                        is_must_null = 1;
                    }
                });
                var ischecked = "";
                area_select_vendor.find(".vendor-select").each(function(){
                    if($(this).is(":checked")){
                        vendor_uuid = $(this).closest('tr').attr('data-vendor');
                        vendor_name = $(this).closest('tr').attr('data-name');
                        vendor_price_info_json['vendor_uuid']=vendor_uuid;
                        vendor_price_info_json['vendor_name']=vendor_name;
                        vendor_price_data_obj[vendor_uuid] = vendor_price_info_json;
                        ischecked ="1";
                    }
                });
                if(ischecked == ""){
                    swal({title: "", text: "请输入媒体主的搜索条件", type: "error"});
                    return false;
                }else if(is_must_null == 1){
                    swal({title: "", text: "存在必填项未填！", type: "error"});
                    return false;
                }else{
                    swal({
                        title: '确认添加么？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                    },function (){
                        var length =  $('#create-one-weixin-media .form-media .add-vendor-list').children("tr").length;
                        if(length ==0){
                            var vendor =  "<tr data-vendor=" + vendor_uuid + ">"+
                                    "<td><input type='radio' name='is-prefer-vendor' value='" + vendor_name + "' checked></td>"+
                                    "<td>" + vendor_name + "</td>"+
                                    "<td>"+pos_s_price+"<br>"+pos_m_1_price+"<br>"+pos_m_2_price+"<br>"+pos_m_3_price+"</td>"+
                                    "<td>"+active_end_time+"</td>"+
                                    "<td><span class='btn-del' style='color:red;cursor:pointer;'>删除</span></td>"+
                                    "</tr>";
                        }else{
                            var vendor =  "<tr data-vendor=" + vendor_uuid + ">"+
                                    "<td><input type='radio' name='is-prefer-vendor' value='" + vendor_name + "'></td>"+
                                    "<td>" + vendor_name + "</td>"+
                                    "<td>"+pos_s_price+"<br>"+pos_m_1_price+"<br>"+pos_m_2_price+"<br>"+pos_m_3_price+"</td>"+
                                    "<td>"+active_end_time+"</td>"+
                                    "<td><span class='btn-del' style='color:red;cursor:pointer;'>删除</span></td>"+
                                    "</tr>";
                        }

                        $('#create-one-weixin-media .form-media .add-vendor-list').append(vendor);
                        $('#modal-add-media-vendor').modal('hide');
                    });
                }
            });

            //删除添加的媒体主信息
            $("#create-one-weixin-media .form-media .add-vendor-list").on("click", ".btn-del",function(){
                var uuid = $(this).parents("tr").data('uuid');
                swal({
                    title: '确认删除么？',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: '确认',
                    cancelButtonText: '取消',
                    closeOnConfirm: true
                },function (){
                    delete vendor_price_data_obj[uuid];
                    $("#create-one-weixin-media .form-media .add-vendor-list .btn-del").parents("tr").remove();
                });
            });

            //保存账号的保存button
            $('#create-one-weixin-media .form-media .btn-commit').on('click', function(){
                var form = $('#create-one-weixin-media .form-media');
                var weixin_id = $.trim(form.find('.weixin-id').val());
                var weixin_name = $.trim(form.find('.weixin-name').val());
                var follower_num = parseFloat($.trim(form.find('.follower-num').val()));
                var comment = $.trim(form.find('.comment').val());
                var prefer_vendor_uuid = $('#create-one-weixin-media .form-media input[name=is-prefer-vendor]:checked').closest('tr').attr('data-vendor'); //首选媒体主uuid
                var pub_config = JSON.stringify(pub_config_obj);//pub_config
                var belong_type = "";

                var is_must_null = "";
                $("#create-one-weixin-media .form-horizontal input").each(function(){
                    if($(this).val()==""){
                        is_must_null = 1;
                    }
                });
                if(is_must_null == 1){
                    swal({title: "", text: "存在必填项未填！", type: "error"});
                    return false;
                }

                var media_cate = '#';
                form.find('.media-cate .one-cate').each(function(){
                    if($(this).is(':checked')){media_cate += $(this).val() + '#'; }
                });
                if(media_cate == '#'){ media_cate = '';}
                if(media_cate == ''){
                    swal({title: "", text: "请选择资源分类", type: "error"});
                    return false;
                }

                // 账号地域属性
                var follower_area = '#';
                form.find('.follower-area .one-area').each(function(){
                    if($(this).is(':checked')){
                        var province_code = '';
                        var city_code = $(this).val();
                        if(city_code == 289){
                            province_code = '19';
                            follower_area = follower_area + province_code + '#' + city_code + '#';
                        } else if(city_code == 291){
                            province_code = '19';
                            follower_area = follower_area + '19' + '#' + city_code + '#';
                        } else if(city_code == 175){
                            province_code = '11';
                            follower_area = follower_area + '11' + '#' + city_code + '#';
                        } else if(city_code == 275){
                            province_code = '18';
                            follower_area = follower_area + '18' + '#' + city_code + '#';
                        } else if(city_code == 258){
                            province_code = '17';
                            follower_area = follower_area + '17' + '#' + city_code + '#';
                        } else if(city_code == 162){
                            province_code = '10';
                            follower_area = follower_area + '10' + '#' + city_code + '#';
                        } else {
                            follower_area = follower_area + city_code + '#';
                        }
                    }
                });
                if(follower_area == '#'){follower_area = ''; }
                if(follower_area == '' && form.find('.follower-area .area-other').is(':checked')){
                    var province_code = form.find('.other-area-select .follower-area-province option:selected').val();
                    var city_code = form.find('.other-area-select .follower-area-city option:selected').val();
                    follower_area = '#' + province_code + '#' + city_code + '#';
                }
                if(follower_area == ''){
                    swal({title: "", text: "请选择账号地域属性", type: "error"});
                    return false;
                }

                // 自媒体类型
                form.find('.media-weixin-belong-type .checkbox-inline input').each(function(){
                    if($(this).is(':checked')){
                        belong_type = $(this).val();
                    }
                });
                if(belong_type == ""){
                    swal({title: "", text: "请选择自媒体类型", type: "error"});
                    return false;
                }

                var is_prefer_vendor_checked = "";
                $("input[name='is-prefer-vendor']").each(function(){
                    if($(this).is(':checked')){is_prefer_vendor_checked += $(this).val() + '#'; }
                });
                if(is_prefer_vendor_checked == ""){
                    swal({title: "", text: "请选择首选媒体主", type: "error"});
                    return false;
                }

                swal({
                    title: '确认保存么？',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: '确认',
                    cancelButtonText: '取消',
                    closeOnConfirm: true
                },function () {
                    $.ajax({
                        url: '$mediaCreateUrl',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: { weixin_id: weixin_id,
                                weixin_name: weixin_name,
                                follower_num: follower_num,
                                media_cate: media_cate,
                                follower_area: follower_area,
                                belong_type:belong_type,
                                prefer_vendor_uuid:prefer_vendor_uuid,
                                vendor_price_data_obj:vendor_price_data_obj,
                                pub_config:pub_config,
                                comment: comment
                        },
                        beforeSend: function () {
                            //让提交按钮失效，以实现防止按钮重复点击
                            $('#create-one-weixin-media .form-media .btn-commit').attr('disabled', 'disabled');
                        },
                        complete: function () {
                             //按钮重新有效
                            $('#create-one-weixin-media .form-media .btn-commit').removeAttr('disabled');
                        },
                        success: function (resp) {
                            if(resp.err_code == 1){
                                swal({title: "保存失败！", text: "请联系系统管理员", type: "error"});
                                return false;
                            }else{
                                swal({title: "保存成功！", text: "", type: "success"});
                                window.location.href = '$mediaWeixinVerifySuccListUrl';
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                            return false;
                        }
                    });
              });
          });

            // 同步价格有效期
            $('.sync-latest-active-end-time').on('click', function(){
                var vendor_uuid = area_select_vendor.find(".vendor-select:checked").parents("tr").data("vendor");
                $.ajax({
                        url: '$getVendorInfoUrl',
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        data: {vendor_uuid: vendor_uuid},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                var vendor = resp.vendor;
                                if(vendor['active_end_time'] != ''){
                                    area_select_vendor.find('.active-end-time').val(vendor['active_end_time']);
                                } else {
                                    swal({title: "", text: "该媒体主未设置报价有效期", type: "error"});
                                    return false;
                                }
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "", text: "系统出错！", type: "error"});
                        }
                });
            });

            //价格有效时间
            area_select_vendor.find('.active-end-time').datetimepicker({
                language: "zh-CN",
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                minView: 2,
                pickerPosition:'top-right'
            });

            // 数值字符串转换
            function switch_radio(obj){
                switch (obj) {
                    case '0':
                      return "不接单";
                      break;
                    case '1':
                      return "纯发布";
                      break;
                    case '2':
                      return "原创+发布";
                      break;
                }
            }

            //时间戳转字符串
            function timeformat(time){
                var date = new Date(time);
                Y = date.getFullYear() + '-';
                M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
                D = date.getDate() + ' ';
                return Y+M+D;
            }
JS;

$this->registerJs($weixinToCreateJs);
?>
