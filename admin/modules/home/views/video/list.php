<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 8/24/16 10:25 AM
 */
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use common\models\WomHomePageMedia;
use common\helpers\MediaHelper;
use admin\assets\AppAsset;

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

$changeStatusUrl = Url::to(['change-status']);
$updateUrl = Url::to(['update']);
$deleteUrl = Url::to(['delete']);
$showDescUrl = Url::to(['show-desc']);
$submitDescUrl = Url::to(['submit-desc']);
$js = <<<JS
$(function(){
    // 控制左侧导航选中
    if(!$('#website-manage .home-list').hasClass('active')){
        $('.menu-level-1').each(function(){
             $(this).removeClass('active');
        });
        $('.menu-level-2').each(function(){
             $(this).removeClass('active');
        });
        $('.menu-level-3').each(function(){
             $(this).removeClass('active');
        });

        $('#website-manage.menu-level-1').addClass('active');
        $('#website-manage.menu-level-1 .menu-level-2.video-list').addClass('active');
    };
    // 简介(提交)
    $(".modal-desc .desc-submit").click(function(){
         var uuid = $(this).attr('data-value');
         var desc = $(".modal-desc .desc-content").val();
         $.post('$submitDescUrl',
             {
                uuid: uuid,
                desc: desc
             }, function(data, status) {
                if(status == 'success'){
                    if(data.err_code == 0){
                        swal('',data.err_msg, 'success');
                    }else{
                        swal('', data.err_msg, 'warning');
                    }
                }else{
                    swal('', '系统异常', 'error');
                }

            });

    });
});
JS;
$this->registerJS($js);
?>

<ol class="breadcrumb pull-right">
    <li><a href="javascript:;">首页</a></li>
    <li class="active">列表</li>
</ol>
<div id="content" class="content">
    <h1 class="page-header">列表</h1>
    <a class="btn btn-success" href="<?= Url::to(['add']) ?>">添加</a>

    <div class="row">
        <div class="col-md-12 main-stage">
            <?php Pjax::begin(['linkSelector' => false]);
                $searchJs = <<<JS
                // 搜索
                function doSearch(){
                   $(".video-search-form").submit();
                }
                // 关闭提示框
                function closeAlert(){
                    $(".sweet-overlay").hide();
                    $(".showSweetAlert").hide();
                    $("body").removeClass("stop-scrolling");
                }
                // 刷新当前页
                function refreshCurrent(){
                   var page = $(".pagination li.active a").attr("data-page");
                   $("input.page").attr("value", page);
                   doSearch();
                }
                // 删除时刷新
                function refreshByDel(){
                    // 当前页是否只有一条记录
                   if($(".summary b:last").text() + '-' + $(".summary b:last").text() == $(".summary b:first").text()){
                        var page = $(".pagination li.active a").attr("data-page");
                        $("input.page").attr("value", page-1);
                        doSearch();
                   }else{
                        refreshCurrent();
                   }
                }

                // 分页
                $(function(){
                    //分页处理样式
                    $(".pagination li a").each(function(){
                        $(this).removeAttr("href");
                        $(this).attr("style","cursor:pointer;");
                    });
                    //分页处理
                    $(".pagination li a").click(function(){
                        $("input.page").attr("value", $(this).attr("data-page"));
                        doSearch();
                    });
                });

                // 改变状态、更新、简介 和 删除
                $(function(){
                    // 显示
                    $(".btn-show").click(function(){
                        var uuid = $(this).attr('data-uuid');
                        swal({
                            title: "您确定要显示在首页吗？",
                            text: "确定将显示在首页,所属分类只展示前8条显示数据",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            cancelButtonText: "关闭",
                            confirmButtonText: "是的，我要显示",
                            confirmButtonColor: "#ec6c62"
                        }, function() {

                            $.post('$changeStatusUrl',
                             {
                                uuid: uuid,
                                status: 1
                             }, function(data, status) {
                                if(status == 'success'){
                                    if(data.err_code == 0){
                                        closeAlert();
                                        refreshCurrent();

                                    }else{
                                        swal('', data.err_msg, 'warning');
                                    }

                                }else{
                                    swal('', '系统异常', 'error');
                                }

                            })
                        });

                    });
                    // 隐藏
                    $(".btn-hidden").click(function(){

                        var uuid = $(this).attr('data-uuid');
                        swal({
                            title: "您确定要在首页隐藏该账号吗？",
                            text: "确定将在首页隐藏该账号,所属分类少于8条显示数据将不展示",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            cancelButtonText: "关闭",
                            confirmButtonText: "是的，我要隐藏",
                            confirmButtonColor: "#ec6c62"
                        }, function() {

                            $.post('$changeStatusUrl',
                             {
                                uuid: uuid,
                                status: 0
                             }, function(data, status) {
                                if(status == 'success'){
                                    if(data.err_code == 0){
                                        closeAlert();
                                        refreshCurrent();
                                    }else{
                                        swal('', data.err_msg, 'warning');
                                    }

                                }else{
                                    swal('', '系统异常', 'error');
                                }

                            })
                        });
                    });
                    // 更新
                    $(".btn-update").click(function(){

                        var uuid = $(this).attr('data-uuid');
                        swal({
                            title: "您确定要更新该账号吗？",
                            text: "确定将更新该账号，获取最新信息",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            cancelButtonText: "关闭",
                            confirmButtonText: "是的，我要更新",
                            confirmButtonColor: "#ec6c62"
                        }, function() {

                            $.post('$updateUrl',
                             {
                                uuid: uuid,
                                status: 0
                             }, function(data, status) {
                                if(status == 'success'){
                                    if(data.err_code == 0){
                                        closeAlert();
                                        refreshCurrent();
                                    }else{
                                        swal('', data.err_msg, 'warning');
                                    }

                                }else{
                                    swal('', '系统异常', 'error');
                                }

                            })
                        });
                    });
                    // 简介(显示)
                    $(".btn-desc").click(function(){
                         var uuid = $(this).attr('data-uuid');
                         $.post('$showDescUrl',
                             {
                                uuid: uuid
                             }, function(data, status) {
                                if(status == 'success'){
                                    if(data.err_code == 0){
                                        $(".modal-desc .desc-submit").attr('data-value', uuid);
                                        $(".modal-desc .desc-content").val(data.err_msg);
                                    }else{
                                        swal('', data.err_msg, 'warning');
                                    }
                                }else{
                                    swal('', '系统异常', 'error');
                                }

                            });
                    });

                    // 删除
                    $(".btn-delete").click(function(){

                        var uuid = $(this).attr('data-uuid');
                        swal({
                            title: "您确定要删除该账号吗？",
                            text: "确定将删除该账号,所属分类少于8条显示数据将不展示",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            cancelButtonText: "关闭",
                            confirmButtonText: "是的，我要删除",
                            confirmButtonColor: "#ec6c62"
                        }, function() {

                            $.post('$deleteUrl',
                             {
                                uuid: uuid
                             }, function(data, status) {
                                if(status == 'success'){
                                    if(data.err_code == 0){
                                        closeAlert();
                                        refreshByDel();
                                    }else{
                                        swal('', data.err_msg, 'warning');
                                    }

                                }else{
                                    swal('', '系统异常', 'error');
                                }

                            })
                        });
                    });

                });

JS;
            $this->registerJS($searchJs);

            ?>
            <?= Html::beginForm(['list'], 'post', ['data-pjax' => '', 'class' => 'video-search-form']); ?>

            <div class="col-md-3" style="float: none;padding-left: 0px;">
                <label>平台:</label>
                <select class="form-control input-sm" name="media_cate">
                    <?php $cate = Yii::$app->request->post("media_cate", -1) ?>
                    <option value="-1" <?= $cate == -1 ? 'selected' : '' ?>>请选择</option>
                    <option value="1" <?= $cate == 1 ? 'selected' : '' ?>>花椒</option>
                    <option value="4" <?= $cate == 4 ? 'selected' : '' ?>>美拍</option>
                    <option value="5" <?= $cate == 5 ? 'selected' : '' ?>>秒拍</option>
                    <option value="6" <?= $cate == 6 ? 'selected' : '' ?>>斗鱼</option>
                    <option value="7" <?= $cate == 7 ? 'selected' : '' ?>>映客</option>
                    <option value="2" <?= $cate == 2 ? 'selected' : '' ?>>熊猫</option>
                    <option value="9" <?= $cate == 9 ? 'selected' : '' ?>>一直播</option>
                </select>
                <input class="btn btn-sm btn-primary btn-submit" value="搜索" type="submit">
            </div>

            <input class="page" type="hidden" name="page" value="">
            <?= Html::endForm() ?>
            <div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'pager' => [
                        'nextPageLabel' => '下一页',
                        'prevPageLabel' => '上一页',
                        'firstPageLabel' => '首页',
                        'lastPageLabel' => '尾页',
                        'maxButtonCount' => 10,
                    ],
                    'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-weixin-list', 'data-page-size' => 10],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['data-sort-ignore' => 'true']
                        ],
                        [
                            'header' => '账号',
                            'format' => 'html',
                            'headerOptions' => ['data-sort-ignore' => 'true'],
                            'contentOptions' => ['class' => 'public-name'],
                            'value' => function ($model, $key, $index, $column) {
                                $info = json_decode($model['info'], true);
                                return "<span>{$model['public_name']}</span><br/>";
                            },
                        ],
                        [
                            'header' => '分类',
                            'format' => 'text',
                            'headerOptions' => ['data-sort-ignore' => 'true'],
                            'contentOptions' => ['class' => 'media_cate'],
                            'value' => function ($model, $key, $index, $column) {
                                $cateList = MediaHelper::getMediaVideoPlatformList();
                                foreach ($cateList as $code => $cate) {
                                     if($model['media_cate'] == '#'. $code. '#'){
                                         return $cate;
                                     }
                                }
                            }
                        ],
                        [
                            'header' => '状态',
                            'format' => 'text',
                            'headerOptions' => ['data-sort-ignore' => 'true'],
                            'contentOptions' => ['class' => 'status'],
                            'value' => function ($model, $key, $index, $column) {
                                if ($model['status'] == WomHomePageMedia::STATUS_SHOW) {
                                    return '显示';
                                } else if ($model['status'] == WomHomePageMedia::STATUS_HIDDEN) {
                                    return '隐藏';
                                } else {
                                    return '未知';
                                }
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'template' => '{show}{hidden}<br>{update}<br>{desc}<br>{delete}',
                            'buttons' => [
                                'show' => function ($url, $model){
                                    if($model['status'] == WomHomePageMedia::STATUS_HIDDEN){
                                        return Html::button('显示', ['class' => 'btn btn-link btn-xs btn-show', 'data-uuid' => $model['uuid']]);
                                    }

                                },
                                'hidden' => function($url, $model){
                                    if($model['status'] == WomHomePageMedia::STATUS_SHOW){
                                        return Html::button('隐藏', ['class' => 'btn btn-link btn-xs btn-hidden', 'data-uuid' => $model['uuid']]);
                                    }
                                },
                                'desc' => function($url, $model){
                                    return Html::button('简介', ['href' => '#modal-dialog', 'class' => 'btn btn-link btn-xs btn-desc', 'data-uuid' => $model['uuid'], 'data-toggle' => 'modal']);
                                },
                                'update' => function($url, $model){
                                    return Html::button('更新', ['class' => 'btn btn-link btn-xs btn-update', 'data-uuid' => $model['uuid']]);
                                },
                                'delete' => function($url, $model){
                                    return Html::button('删除', ['class' => 'btn btn-link btn-xs btn-delete', 'data-uuid' => $model['uuid']]);
                                }

                            ],
                            'headerOptions' => ['data-sort-ignore' => 'true'],
                            'contentOptions' => ['class' => '']
                        ],
                    ],

                ]); ?>
            </div>

            <?php Pjax::end()?>
        </div>
    </div>
</div>
<!-- 简介 -->
<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content modal-desc">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">简介</h4>
            </div>
            <div class="modal-body col-md-12">
                <div class="form-group">
                    <div class="col-md-13">
                        <textarea class="form-control desc-content" placeholder="简介" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">关闭</a>
                <a href="javascript:;" class="btn btn-sm btn-success desc-submit">确认</a>
            </div>
        </div>
    </div>
</div>


