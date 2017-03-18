<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 8/24/16 10:25 AM
 */
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;

use yii\grid\GridView;
use admin\assets\AppAsset;
use common\helpers\MediaHelper;

$addHomeUrl = Url::to(['add-home']);

$mediaTypeCode = MediaHelper::getWeixinInfo()['code'];
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

AppAsset::addScript($this, '@web/plugins/select2/dist/js/select2.min.js');
AppAsset::addCss($this, '@web/plugins/select2/dist/css/select2.min.css');

AppAsset::addScript($this, '@web/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');
$js = <<<JS

    $('.pjax-area .form-search').on('click','.btn-submit',function() {
         var followerCntMin = $(".form-search input[name='follower-cnt-min']").val();
         var followerCntMax = $(".form-search input[name='follower-cnt-max']").val();
         if(followerCntMin == ''){
              followerCntMin = 0;
         }
         if(followerCntMax == ''){
              followerCntMax = 0;
         }
         if(isNaN(followerCntMin) || isNaN(followerCntMax)){
              swal('', '粉丝数区间需要填写数字!', 'error');
              return false;
         }
         followerCntMin = parseInt(followerCntMin)
         followerCntMax = parseInt(followerCntMax)
         if(followerCntMin > followerCntMax){
              swal('', '粉丝数区间填写有误!', 'error');
              return false;
         }
    });

    // 有效日期
    $("#modal-set-vendor .area-vendor-detail .active-end-time").datepicker({
        language: "zh-CN",
        todayHighlight: true,
        autoclose: true
    });
JS;

//$this->registerJs($js);




$navJs = <<<JS
$(function(){
    // 控制左侧导航选中
    if(!$('#home .home-list').hasClass('active')){
        $('.menu-level-1').each(function(){
             $(this).removeClass('active');
        });
        $('.menu-level-2').each(function(){
             $(this).removeClass('active');
        });
        $('.menu-level-3').each(function(){
             $(this).removeClass('active');
        });

        $('#home.menu-level-1').addClass('active');
        $('#home.menu-level-1 .menu-level-2.home-list').addClass('active');
    }
    // 提交
                $(".modal-select-cate .btn-commit").click(function(){
                    var cate = $('.modal-select-cate .cate-select option:selected').val();
                    var mediaUuid = $('.modal-select-cate .media-uuid').val();
                    if(cate == -1){
                        swal('', '请选择分类!', 'warning');
                        return false;
                    }

                    $.post('$addHomeUrl',{
                       mediaType: 1,
                       mediaUuid: mediaUuid,
                       cate: cate
                    },function(data, status){
                        if(status == 'success'){
                            if(data.err_code == 0){
                                //$('.main-stage .pjax-area .form-search .btn-submit').trigger('click');
                                swal('', data.err_msg, 'success');
                                $('.modal-select-cate').modal('hide');
                            }
                        }else{
                           swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        }

                    });

                });
});
JS;
$this->registerJS($navJs);
?>

<ol class="breadcrumb pull-right">
    <li><a href="javascript:;">首页</a></li>
    <li><a href="<?= Url::to(['list']) ?>">列表</a></li>
    <li class="active">添加</li>
</ol>
<div id="content" class="content">
    <h1 class="page-header">添加</h1>

    <div class="row">
        <div class="col-md-12 main-stage">
            <?php
            $pJs = <<<JS
                //分页处理样式
                $(".pagination li a").each(function(){
                    $(this).removeAttr("href");
                    $(this).attr("style","cursor:pointer;");
                });
                //分页处理
                $(".pagination li a").click(function(){
                    $("input.page").attr("value", $(this).attr("data-page"));
                    $(".weixin-search-form").submit();
                });
                //查询
                $(".weixin-search-form .btn-submit").click(function(){
                    //$(".weixin-search-form").submit();
                });

                //$('.form-search .media-vendor').select2();

                // 加入首页
                $(function(){
                    // 选择分类
                    $(".btn-add-home").click(function(){
                        var mediaUuid = $(this).attr('data-uuid');
                        $('.modal-select-cate .media-uuid').val(mediaUuid);
                        $(".modal-select-cate").modal('show');
                    });
                });


JS;
            ?>
            <?php Pjax::begin(['linkSelector' => false]);
                $this->registerJs($pJs);

            ?>
            <div class="panel panel-inverse pjax-area">

                <?= Html::beginForm(['add'], 'post', ['data-pjax' => '', 'class' => 'weixin-search-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>账号</label>
                                <input type="text" name="account"
                                       value="<?php echo Yii::$app->request->post('account', ''); ?>"
                                       placeholder="请输入账号名称或ID" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>粉丝数</label>
                                <div class="input-group">
                                    <input type="text"
                                           value="<?php echo Yii::$app->request->post('follower-cnt-min', ''); ?>"
                                           class="form-control input-sm" name="follower-cnt-min" placeholder="">
                                    <span class="input-group-addon"> - </span>
                                    <input type="text"
                                           value="<?php echo Yii::$app->request->post('follower-cnt-max', ''); ?>"
                                           class="form-control input-sm" name="follower-cnt-max" placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>资源分类</label>
                                <select name="media-cate" class="form-control input-sm">
                                    <option value="-1" <?php echo Yii::$app->request->post('media-cate', -1) == -1 ? 'selected' : ''?>>不限</option>
                                    <?php
                                    $mediaCateList = MediaHelper::getMediaCateList();
                                    foreach($mediaCateList as $code => $cate){
                                        ?>
                                        <option value="<?php echo $code;?>" <?php echo Yii::$app->request->post('media-cate', -1) == $code ? 'selected' : '' ?>><?php echo $cate?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>媒介运营</label>
                                <select name="media-executor" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option value="2">jack</option>
                                    <option value="3">tony</option>
                                    <option value="4">hellen</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1" style="visibility: hidden">
                            <div class="form-group">
                                <label>激活</label>
                                <select name="is-activated" class="form-control input-sm">

                                    <option
                                        value="1" <?php echo Yii::$app->request->post('is-activated', -1) == 1 ? 'selected' : '' ?>>
                                        是
                                    </option>
                                    <option
                                        value="0" <?php echo Yii::$app->request->post('is-activated', -1) == 0 ? 'selected' : '' ?>>
                                        否
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1" style="visibility: hidden">
                            <div class="form-group">
                                <label>上下架</label>
                                <select name="put-up" class="form-control input-sm">
                                    <option
                                        value="1" <?php echo Yii::$app->request->post('put-up', -1) == 1 ? 'selected' : '' ?>>
                                        上架
                                    </option>
                                    <option
                                        value="0" <?php echo Yii::$app->request->post('put-up', -1) == 0 ? 'selected' : '' ?>>
                                        下架
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary btn-submit" value="查&nbsp;&nbsp;&nbsp;&nbsp;询"/>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="page" type="hidden" name="page" value="">
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
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-weixin-verify-succ', 'data-page-size' => 10],
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
                                    return "<span>{$model['public_name']}</span><br/><span >{$model['public_id']}</span>";
                                },
                            ],
                            [
                                'header' => '粉丝数',
                                'attribute' => 'follower_num',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'follower-num'],
                            ],
                            [
                                'header' => '资源分类',
                                'attribute' => 'media_cate',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-cate'],
                                'value' => function ($model, $key, $index, $column) {
                                    $mediaCateJson = MediaHelper::parseMediaCate($model['media_cate']);
                                    $mediaCateList = json_decode($mediaCateJson, true);
                                    $label = '';
                                    if(empty($mediaCateList)){
                                        $label = '------';
                                    }else{
                                        foreach($mediaCateList as $code => $mediaCate){
                                            $label .= $mediaCate . '<br>';
                                        }
                                    }
                                    return $label;
                                },
                            ],
                            [
                                'header' => '激活',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'follower_num'],
                                'value' => function ($model, $key, $index, $column) {
                                    if ($model['is_activated'] == 1) {
                                        return '是';
                                    } else if ($model['is_activated'] == 0) {
                                        return '否';
                                    } else {
                                        return '未知';
                                    }
                                }
                            ],
                            [
                                'header' => '入驻时间',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => function ($model, $key, $index, $column) {
                                    return ['class' => 'create-time'];
                                },
                                'value' => function ($model, $key, $index, $column) {
                                    return date('Y-m-d', $model['create_time']);
                                }
                            ],
                            [
                                'header' => '媒介运营',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-executor'],
                                'value' => function ($model, $key, $index, $column) {
                                    if(empty($model['executor_name'])){
                                        return '未分配';
                                    } else {
                                        return $model['executor_name'];
                                    }
                                },
                            ],
                            [
                                'header' => '接单情况',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'follower_num'],
                                'value' => function ($model, $key, $index, $column) {
                                    return '接单数: ' . $model['order_finished_cnt'] . '<br>' . '拒单数: ' . $model['order_refuse_cnt'] . '<br>' . '流单数: ' . $model['order_abort_cnt'];
                                }
                            ],
                            [
                                'header' => '首选媒体主',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'is-pref-vendor'],
                                'value' => function ($model, $key, $index, $column) {
                                    if($model['has_pref_vendor'] == 0){
                                        return '无';
                                    } else {
                                        return $model['vendor_name'] == '' ? $model['vendor_contact_person'] : $model['vendor_name'];
                                    }
                                },
                            ],
                            [
                                'header' => '上下架',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'put-up'],
                                'value' => function ($model, $key, $index, $column) {
                                    if($model['put_up'] == 0){
                                        return '<span class="label label-default">下架</span>';
                                    } else {
                                        return '<span class="label label-success">上架</span>';
                                    }
                                },
                            ],
                            [
                                'header' => '审核时间',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'last-verify-time'],
                                'value' => function ($model, $key, $index, $column) {
                                    return date('Y-m-d H:i', $model['last_verify_time']);
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{add}',
                                'buttons' => [
                                    'add' => function ($url, $model) {
                                        return Html::button('加入首页', ['class' => 'btn btn-link btn-xs btn-add-home', 'data-uuid' => $model['media_uuid']]);
                                    }
                                ],
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => '']
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
            <?php  Pjax::end() ?>
        </div>
    </div>
</div>
<!-- 加入首页 -->
<div class="modal fade modal-select-cate" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">选择分类</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-inverse" >
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <input class="media-uuid" type="hidden" value="">
                            <div class="form-group">
                                <label class="col-md-3 control-label">选择分类:</label>
                                <div class="col-md-6">
                                    <select class="form-control cate-select">
                                        <option value="-1">请选择</option>
                                        <option value="4">汽车</option>
                                        <option value="10">母婴/育儿</option>
                                        <option value="7">IT/互联网</option>
                                        <option value="5">时尚</option>
                                        <option value="14">美食</option>
                                        <option value="2">生活</option>
                                        <option value="19">金融财经</option>
                                        <option value="13">家居房产</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-success btn-commit">确&nbsp;&nbsp;认</a>
                <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">关闭</a>
            </div>
        </div>
    </div>
</div>
