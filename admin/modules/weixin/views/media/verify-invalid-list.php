<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:33 PM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use common\models\MediaWeixin;
use yii\widgets\Pjax;
use common\helpers\MediaHelper;
use common\helpers\PlatformHelper;

$fetchAllMediaExecutorUrl = Yii::$app->urlManager->createUrl(array('media/executor/fetch-list'));
$assignMediaExecutorUrl = Yii::$app->urlManager->createUrl(array('media/executor/assign-one'));
$getWeixinInfoUrl = Yii::$app->urlManager->createUrl(array('weixin/media/get-info'));
$weixinVerifyUrl = Yii::$app->urlManager->createUrl(array('weixin/media/verify'));
$getVendorListUrl = Yii::$app->urlManager->createUrl(array('media/vendor/get-list-of-media'));
$getVendorInfoUrl = Yii::$app->urlManager->createUrl(array('weixin/vendor/get-info'));
$verifyVendorUrl = Yii::$app->urlManager->createUrl(array('weixin/vendor/verify'));
$deleteMediaUrl = Yii::$app->urlManager->createUrl(array('weixin/media/delete'));
$vendorSearchUrl = Yii::$app->urlManager->createUrl(array('weixin/media/search'));
$vendorInfoSearchUrl = Yii::$app->urlManager->createUrl(array('weixin/media/search-price-info'));
$mediaAddVendorUrl = Yii::$app->urlManager->createUrl(array('weixin/media/add-vendor'));
$reVerifyUrl = Yii::$app->urlManager->createUrl(array('weixin/media/re-verify'));
$weixinToVerifyList = Yii::$app->urlManager->createUrl(array('weixin/media/to-verify-list'));

$weixinToVerifyJs = <<<JS
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
    if(!$('#weixin .media-manage .verify-invalid').hasClass('active')){
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
        $('#weixin.menu-level-1 .menu-level-2.media-manage .menu-level-3.verify-invalid').addClass('active');
    }

    $('.pjax-area .form-search').on('click','.btn-submit',function() {
         var followerCntMin = $.trim($(".form-search input[name='follower-cnt-min']").val());
         var followerCntMax = $.trim($(".form-search input[name='follower-cnt-max']").val());
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

            // 重新审核
            $('.main-stage').on('click', '.table-media-list .btn-re-verify', function(){
                var media_uuid = $(this).attr('data-uuid');
                swal({
                        title: '确认重新审核么？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: false
                },function () {
                    $.ajax({
                        url: '$reVerifyUrl',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {media_uuid: media_uuid},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                swal('', '请到 "微信资源管理/待审核" 里重新审核该资源!', 'success');
                                window.location.href = '$weixinToVerifyList';
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "", text: "系统出错！", type: "error"});
                        }
                    });
                });
            });
JS;

$this->registerJs($weixinToVerifyJs);

AppAsset::addScript($this, '@web/js/helpers/base-helper.js');
AppAsset::addScript($this, '@web/js/helpers/number-helper.js');

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

?>

<div id="content" class="content">

    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">资源管理</a></li>
        <li class="active">无效账号</li>
    </ol>

    <h1 class="page-header">无效账号</h1>

    <div class="row">
        <div class="col-md-12 main-stage">
            <?php Pjax::begin();
            $Js = <<<JS

            //分页处理样式
            $(".pagination li a").each(function(){
                $(this).removeAttr("href");
                $(this).attr("style","cursor:pointer;");
            });
            //分页处理
            $(".pagination li a").click(function(){
                $(".weixin-search-form input.page").attr("value", $(this).attr("data-page"));
                $(".weixin-search-form").submit();
            });
            //查询计划
            $(".weixin-search-form .btn-submit").click(function(){
                $(".weixin-search-form").submit();
            });

JS;
            $this->registerJs($Js);
            ?>
            <div class="panel panel-inverse pjax-area">
                <?= Html::beginForm(['media/verify-invalid-list'], 'post', ['data-pjax' => '', 'class' => 'weixin-search-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>公众号</label>
                                <input type="text" name="account"
                                       value="<?php echo Yii::$app->request->post('account', ''); ?>"
                                       placeholder="请输入公众号名称或ID" class="form-control input-sm">
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

                        <div class="col-md-2" style="display: none">
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

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>激活</label>
                                <select name="is-activated" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
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
                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary btn-submit" value="查&nbsp;&nbsp;&nbsp;&nbsp;询"/>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">
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
                            'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-media-list','id' => 'fixed-header-data-table'] ,
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'headerOptions' => ['data-sort-ignore' => 'true']
                                ],
                                [
                                    'header' => '公众号',
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
                                        if(empty($mediaCateJson)){
                                            return ' - ';
                                        }
                                        $mediaCateList = json_decode($mediaCateJson, true);
                                        $label = '';
                                        foreach($mediaCateList as $code => $mediaCate){
                                            $label .= $mediaCate . '<br>';
                                        }
                                        return $label;
                                    },
                                ],
                                [
                                    'header' => '激活',
                                    'format' => 'text',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'is-activated'],
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
                                    'header' => '审核状态',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'weixin-status'],
                                    'value' => function ($model, $key, $index, $column) {
                                        if ($model['weixin_status'] == MediaWeixin::STATUS_INFO_INVALID) {
                                            return '<span class="label label-warning">无效账号(采集程序判断)</span>';
                                        } else if ($model['weixin_status'] == MediaWeixin::STATUS_INFO_INVALID_MANUAL) {
                                            return '<span class="label label-warning">无效账号(人工判断)</span>';
                                        } else {
                                            return '<span class="label label-warning">未知</span>';
                                        }
                                    }
                                ],
                                [
                                    'header' => '首选媒体主',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'is-pref-vendor'],
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model['vendor_name'] == '' ? $model['vendor_contact_person'] : $model['vendor_name'];
                                    },
                                ],
                                [
                                    'header' => '操作时间',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'op-time'],
                                    'value' => function ($model, $key, $index, $column) {
                                        return '入驻时间: ' . date('Y-m-d H:i', $model['create_time']) . '<br>' . '审核时间: ' . date('Y-m-d H:i', $model['last_verify_time']) . '<br>' . '最后更新: ' . date('Y-m-d H:i', $model['last_update_time']);
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{re-verify}',
                                    'buttons' => [
                                        're-verify' => function ($url, $model) {
                                            return Html::button('重新审核', ['class' => 'btn btn-link btn-xs btn-re-verify', 'data-uuid' => $model['media_uuid']]);
                                        }
                                    ],
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => '']
                                ],
                            ]
                        ]);
                    } ?>
                </div>
            </div>
            <?php Pjax::end() ?>
            <table class="footable table table-striped toggle-arrow-tiny table-media-list" id="header-fixed" style="position: fixed;top: 54px;display: none;margin-left: 15px"><thead>
                <tr><th data-sort-ignore="true">#</th><th data-sort-ignore="true">公众号</th><th data-sort-ignore="true">粉丝数</th><th data-sort-ignore="true">资源分类</th><th data-sort-ignore="true">激活</th><th data-sort-ignore="true">媒介运营</th><th data-sort-ignore="true">审核状态</th><th data-sort-ignore="true">首选媒体主</th><th data-sort-ignore="true">操作时间</th><th data-sort-ignore="true">操作</th></tr>
                </thead></table>
        </div>
    </div>
</div>