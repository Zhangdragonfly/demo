<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\Pjax;
use common\helpers\MediaHelper;
use yii\helpers\Url;
use common\models\WomHomePageMediaWeixin;

$weixinOrderList = Yii::$app->urlManager->createUrl(array('weixin/order/list'));
$getMediaVendorInfoUrl = Yii::$app->urlManager->createUrl(array('media/vendor/get'));
$updateMediaVendorInfoUrl = Yii::$app->urlManager->createUrl(array('media/vendor/update'));
$weixinCreateUrl = Yii::$app->urlManager->createUrl(array('weixin/media/create'));
$deleteMediaVendorInfoUrl = Yii::$app->urlManager->createUrl(array('media/vendor/delete'));

AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

$videoList = <<<JS
        //表头一直悬浮在列表头部
            $(function() {
              $(window).scroll(function() {
                  var headFix = $("#header-fixed");
                  var _head = $("#fixed-header-data-table");
                  var headFixTh = headFix.find("thead tr th");
                  var _headTh = _head.find("thead tr th");
                  headFix.width(_head.width());
                  for(var i=1;i<=_headTh.length;i++){
                    headFix.find("thead tr th:nth-child("+i+")").width(_head.find("thead tr th:nth-child("+i+")").width());
                  }
                  var difference = _head.offset().top - $(this).scrollTop();
                  (difference < 54) ? headFix.show() : headFix.hide();
                })
            });
        // 控制左侧导航选中
        if(!$('#website-manage .home-manage .weixin-list').hasClass('active')){
            $('.menu-level-1').each(function(){
                 $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                 $(this).removeClass('active');
            });

            $('#website-manage.menu-level-1').addClass('active');
            $('#website-manage.menu-level-1 .menu-level-2.home-manage').addClass('active');
            $('#website-manage.menu-level-1 .menu-level-2.home-manage .menu-level-3.weixin-list').addClass('active');
        }

        $('.main-stage').on('click', '.table-media-weixin .btn-update', function(){
            var get_media_info_url = $(this).attr('data-get-media-info-url');
            $.ajax({
                url: get_media_info_url,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        var media_info = resp.home_media_video;
                        var this_modal = $('#modal-media-update');
                        this_modal.find('.form-media .uuid').val(media_info.uuid);
                        this_modal.find('.form-media .media-name').val(media_info.media_name);
                        this_modal.find('.form-media .follower-num').val(media_info.follower_num);
                        this_modal.find('.form-media .avg-view-num').val(media_info.avg_view_num);
                        this_modal.find('.form-media .short-desc').val(media_info.short_desc);
                        this_modal.modal('show');
                    } else {
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    return false;
                }
            });
        });
        $('#modal-media-update').on('click', '.btn-commit', function(){
            var this_modal = $('#modal-media-update');
            var uuid = this_modal.find('.form-media .uuid').val();
            var media_name = $.trim(this_modal.find('.form-media .media-name').val());
            var follower_num = $.trim(this_modal.find('.form-media .follower-num').val());
            var avg_view_num = $.trim(this_modal.find('.form-media .avg-view-num').val());
            var short_desc = $.trim(this_modal.find('.form-media .short-desc').val());

            if(follower_num == ''){
                follower_num = 0;
            }
            if(isNaN(follower_num)){
                swal({title: "", text: "粉丝数应该为数字!", type: "error"});
                return false;
            }
            if(avg_view_num == ''){
                avg_view_num = 0;
            }
            if(isNaN(avg_view_num)){
                swal({title: "", text: "平均观看人数应该为数字!", type: "error"});
                return false;
            }

            var update_media_info_url = this_modal.find('.update-media-url').val();
            swal({
                        title: '确认保存么？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                }, function(){
                    $.ajax({
                        url: update_media_info_url,
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {uuid: uuid, media_name: media_name, follower_num: follower_num, avg_view_num: avg_view_num, short_desc: short_desc},
                        success: function (resp) {
                        if (resp.err_code == 0) {
                            this_modal.modal('hide');
                            $('.main-stage .weixin-form .btn-submit').trigger('click');
                        } else {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                });
            });
        });

        $('.main-stage').on('click', '.weixin-form .btn-update-home', function(){
            $('#modal-update-home').modal('show');
        });

        // 添加资源
        $('.main-stage').on('click', '.weixin-form .btn-add-media', function(){
            $('#modal-media-add .media-weixin-search-result').empty();
            var form_media = $('#modal-media-add .form-media-weixin-to-add');
            form_media.find('.group-media-name .media-name').text('');
            form_media.find('.group-media-cate .media-name').text('');
            form_media.find('.group-show-latest-7-head-view-num .show-latest-7-head-view-num').val(0);
            form_media.find('.group-short-desc .short-desc').val('');
            $('#modal-media-add').modal('show');
        });
        $('#modal-media-add').on('click', '.form-search-media .btn-search', function(){
            var search_media_url = $('#modal-media-add .search-media-url').val();
            var media_name = $('#modal-media-add .form-search-media .input-name').val();
            $.ajax({
                 url: search_media_url,
                 type: 'POST',
                 cache: false,
                 dataType: 'json',
                 data: {media_name: media_name},
                 success: function (resp) {
                      if (resp.err_code == 0) {
                            
                      } else {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                            return false;
                      }
                 },
                 error: function (XMLHttpRequest, msg, errorThrown) {
                      swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                      return false;
                 }
            })
        });


JS;
$this->registerJs($videoList);

AppAsset::addScript($this, '@web/plugins/moment/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">网站管理</a></li>
        <li><a href="javascript:;">首页管理</a></li>
        <li class="active">微信</li>
    </ol>

    <h1 class="page-header">微信</h1>

    <div class="row">
        <div class="col-md-12 main-stage">
            <?php Pjax::begin(['linkSelector' => false]);
            $pjax_video_list_js = <<<JS
        //分页处理样式
        $(".pagination li a").each(function(){
            $(this).removeAttr("href");
            $(this).attr("style","cursor:pointer;");
        });
        //分页处理
        $(".pagination li a").click(function(){
            $(".main-stage .weixin-form input.page").attr("value", $(this).attr("data-page"));
            $(".main-stage .weixin-form").submit();
        });
JS;
            $this->registerJs($pjax_video_list_js);
            ?>

            <div class="panel panel-inverse pjax-area">
                <?= Html::beginForm(['/website/home/weixin/list'], 'post', ['data-pjax' => '', 'class' => 'weixin-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>公众号名称</label>
                                <input type="text" name="media-name"
                                       value="<?php echo Yii::$app->request->post('media-name', ''); ?>"
                                       placeholder="请输入公众号名称" class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary btn-submit"
                                       value="查&nbsp;&nbsp;询"/>

                                <input type="button" class="btn btn-sm btn-primary btn-add-media"
                                       value="添加资源"/>

                                <input type="button" class="btn btn-sm btn-primary btn-update-home"
                                       value="更新首页"/>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="page" type="hidden" name="page"
                       value="<?php echo Yii::$app->request->post('page', 0); ?>">
                <?= Html::endForm() ?>

                <div class="panel-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => [
                            'nextPageLabel' => '下一页',
                            'prevPageLabel' => '上一页',
                            'firstPageLabel' => '首页',
                            'lastPageLabel' => '尾页',
                            'maxButtonCount' => 10,
                        ],
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-media-weixin', 'id' => 'fixed-header-data-table'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['data-sort-ignore' => 'true']
                            ],
                            [
                                'header' => '公众号',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['media_name'] . '<br>' . $model['weixin_id'];
                                },
                            ],
                            [
                                'header' => '分类',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'platform'],
                                'value' => function ($model, $key, $index, $column) {
                                    return MediaHelper::getMediaCateList()[$model['media_cate']];
                                },
                            ],
                            [
                                'header' => '简介',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'short-desc'],
                                'value' => function ($model, $key, $index, $column) {
                                    return empty($model['short_desc']) ? '' : $model['short_desc'];
                                },
                            ],
                            [
                                'header' => '粉丝数',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'follower-num'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['follower_num'] == 0 ? ' - ' : $model['follower_num'];
                                },
                            ],
                            [
                                'header' => '头条平均阅读数',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'head-avg-view-num'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['m_head_avg_view_num'] == 0 ? ' - ' : $model['m_head_avg_view_num'];
                                },
                            ],
                            [
                                'header' => '状态',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'status'],
                                'value' => function ($model, $key, $index, $column) {
                                    if ($model['status'] == WomHomePageMediaWeixin::STATUS_IN_HOME) {
                                        return '已设置上首页';
                                    } else if ($model['status'] == WomHomePageMediaWeixin::STATUS_TO_PUT_IN_HOME) {
                                        return '待上首页';
                                    } else if ($model['status'] == WomHomePageMediaWeixin::STATUS_DELETED) {
                                        return '已删除';
                                    } else {
                                        return '未知';
                                    }
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{update}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::button('更新', ['class' => 'btn btn-link btn-xs btn-update', 'data-get-media-info-url' => Url::to(['/website/home/weixin/get-media-info', 'uuid' => $model['uuid']])]);
                                    },
                                ],
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => '']
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
            <?php Pjax::end() ?>
            <table class="footable table table-striped toggle-arrow-tiny table-media-vendor" id="header-fixed"
                   style="position: fixed;top: 54px;display: none;margin-left: 15px">
                <thead>
                <tr>
                    <th data-sort-ignore="true">#</th>
                    <th data-sort-ignore="true">艺人名称</th>
                    <th data-sort-ignore="true">所属平台</th>
                    <th data-sort-ignore="true">简介</th>
                    <th data-sort-ignore="true">粉丝数</th>
                    <th data-sort-ignore="true">平均观看人数</th>
                    <th data-sort-ignore="true">操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- 添加资源 -->
<div class="modal fade" id="modal-media-add" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">

            <input type="hidden" class="search-media-url"
                   value="<?= Url::to(['/website/home/weixin/search']) ?>">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">添加资源</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-inverse">
                    <form class="form-horizontal form-search-media">
                        <div class="form-group">
                            <label class="col-md-3 control-label">公众号名称/ID *: </label>
                            <div class="col-md-5">
                                <input type="text" class="form-control input-name" name="search_media_name"
                                       placeholder="公众号名称/ID" />
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-search" type="button">搜&nbsp;&nbsp;&nbsp;索
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="panel panel-inverse area-select-media">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <h1 style="font-size:16px;color:#00acac;">搜索结果列表</h1>
                            <table id="user" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>公众号名称</th>
                                    <th>公众号ID</th>
                                    <th>分类</th>
                                    <th>粉丝数</th>
                                    <th>选择</th>
                                </tr>
                                </thead>
                                <tbody class="media-weixin-search-result">

                                    <!-- ajax获取媒体列表-->

                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <form class="form-horizontal form-media-weixin-to-add">
                                <div class="form-group group-media-name">
                                    <label class="col-md-4 control-label">账号名称:</label>
                                    <div class="col-md-4">
                                        <span class="media-name"></span>
                                    </div>
                                </div>

                                <div class="form-group group-media-cate">
                                    <label class="col-md-4 control-label">分类:</label>
                                    <div class="col-md-4">

                                    </div>
                                </div>

                                <div class="form-group group-show-latest-7-head-view-num">
                                    <label class="col-md-4 control-label">显示头条阅读趋势:</label>
                                    <div class="col-md-4">
                                        <select name="show_latest_7_head_view_num"  class="form-control show-latest-7-head-view-num">
                                            <option value="0">否</option>
                                            <option value="1">是</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group group-short-desc">
                                    <label class="col-md-4 control-label">简介:</label>
                                    <div class="col-md-5">
                                        <textarea class="form-control short-desc" placeholder="请输入简介" rows="5"></textarea>
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
</div>

<!-- 更新资源信息 -->
<div class="modal fade" id="modal-media-update" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">

            <input type="hidden" class="update-media-url"
                   value="<?= Url::to(['/website/home/weixin/update-media-info']) ?>">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">信息管理</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-horizontal form-media">

                                <input type="hidden" class="uuid" value="">

                                <div class="form-group">
                                    <label class="col-md-3 control-label">艺人名称:</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control media-name" placeholder="请输入艺人名称"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">粉丝数:</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control follower-num" placeholder="请输入粉丝人数"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">平均观看人数:</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control avg-view-num" placeholder="请输入平均观看人数"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">简介: </label>
                                    <div class="col-md-5">
                                        <textarea class="form-control short-desc" placeholder="请输入艺人简介"
                                                  rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-4">
                                        <button class="btn btn-success btn-lg btn-commit" type="button">保&nbsp;&nbsp;&nbsp;存
                                        </button>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <button class="btn btn-default btn-lg" type="button" data-dismiss="modal">关&nbsp;&nbsp;&nbsp;闭
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 更新首页 -->
<div class="modal fade" id="modal-update-home" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">

            <input type="hidden" class="update-media-url"
                   value="<?= Url::to(['/website/home/weixin/update-media-info']) ?>">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">信息管理</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


