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
use common\helpers\DateTimeHelper;
use common\models\MediaVendor;
use common\models\VideoMediaBaseInfo;

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
        $('#home.menu-level-1 .menu-level-2.video-list').addClass('active');
    }
    // 提交
                $(".modal-select-cate .btn-commit").click(function(){
                    var cate = $('.modal-select-cate .cate-select option:selected').val();
                    var mediaUuid = $('.modal-select-cate .media-uuid').val();
                    if(cate == -1){
                        swal('', '请选择分类!', 'warning');
                        console.log('请选择分类');
                        return false;
                    }

                    $.post('$addHomeUrl',{
                       mediaType: 3,
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
                // 搜索
                function doSearch(){
                    $(".video-search-form").submit();
                }
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

                //查询
                $(".video-search-form .btn-submit").click(function(){
                    doSearch();
                });

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

                <?= Html::beginForm(['add'], 'post', ['data-pjax' => '','class'=>'video-search-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>名称</label>
                                <input type="text" name="name"
                                       value="<?php echo Yii::$app->request->post('name', ''); ?>"
                                       placeholder="请输入中文名或者英文名" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>出生日期</label>
                                <input type="text"
                                       value="<?php echo Yii::$app->request->post('birth-date-rang', ''); ?>"
                                       class="form-control input-sm birth-date-rang" name="birth-date-rang" placeholder="请输入出生日期">
                            </div>
                        </div>

                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>行业分类</label>
                                <select name="media-tag"  class="form-control platform-type">
                                    <option value="-1" selected>不限</option>
                                    <?php foreach(MediaHelper::getMediaVideoCateList() as $k => $v){?>
                                        <option <?php echo Yii::$app->request->post('media-tag') == $k?'selected':''?> value="<?php echo $k?>" ><?php echo $v?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
<!--                                <label>主播风格</label>-->
                                <!--                            <select name="media-style" class="form-control">-->
                                <!--                                <option value="-1" selected>不限</option>-->
                                <!--                                --><?php //foreach(MediaHelper::getVideoStyleList() as $k => $v){?>
                                <!--                                    <option --><?php //echo Yii::$app->request->post('media-style') == $k?'selected':''?><!-- value="--><?php //echo $k?><!--" >--><?php //echo $v?><!--</option>-->
                                <!--                                --><?php //}?>
                                <!--                            </select>-->
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
<!--                                <label>所在地</label>-->
                                <!--                            <select name="media-area" class="form-control">-->
                                <!--                                <option value="-1" selected>不限</option>-->
                                <!--                                --><?php //foreach(MediaHelper::getVideoFollowerAreaList() as $k => $v){?>
                                <!--                                    <option --><?php //echo Yii::$app->request->post('media-area') == $k?'selected':''?><!-- value="--><?php //echo $k?><!--" >--><?php //echo $v?><!--</option>-->
                                <!--                                --><?php //}?>
                                <!--                            </select>-->
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
<!--                                <label>平台</label>-->
                                <!--                            <select name="account-type" class="form-control">-->
                                <!--                                <option value="-1" selected>不限</option>-->
                                <!--                                --><?php //foreach(MediaHelper::getVideoAccountTypeList() as $k => $v){?>
                                <!--                                    <option --><?php //echo Yii::$app->request->post('account-type') == $k?'selected':''?><!-- value="--><?php //echo $k?><!--" >--><?php //echo $v?><!--</option>-->
                                <!--                                --><?php //}?>
                                <!--                            </select>-->
                            </div>
                        </div>

                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="button" class="btn btn-sm btn-primary btn-submit btnSearch" value="查&nbsp;&nbsp;询"/>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="page" type="hidden" name="page">
                <?= Html::endForm() ?>
                <div class="panel-body">

                    <?php if ($dataProvider !== null) { ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-video-base-info'],
                            'columns' => [
                                [

                                    'header' => '名称',
                                    'format' => 'html',
                                    'value' => function($model){
                                        return "<span>艺名:{$model['stage_name']}</span><br/><span >中文:{$model['name_cn']}</span><br/><span >英文:{$model['name_en']}</span>";
                                    }
                                ],
                                [
                                    'header' => '联系方式',
                                    'format' => 'html',
                                    'value' => function($model){
                                        return "<span>手机:{$model['mobile_phone']}</span><br/><span >QQ:{$model['qq']}</span><br/><span >微信:{$model['weixin']}</span>";
                                    }
                                ],
                                [
                                    'header' => '个人信息',
                                    'format' => 'html',
                                    'value' => function($model){
                                        $sex = $model['sex']==1?'男':'女';
                                        $data = DateTimeHelper::getFormattedDateTime($model['birth_date']);
                                        return "<span>性别:".$sex."</span><br/><span >出生日期:".$data."</span><br/><span >所在地:{$model['current_address']}</span>";
                                    }
                                ],
                                [
                                    'header' => '供应商',
                                    'format' => 'text',
                                    'value' => function($model){
                                        $vendor = MediaVendor::findOne(['uuid' => $model['pref_vendor_uuid']]);
                                        return $vendor->name;
                                    }
                                ],
                                [
                                    'header' => '主打平台',
                                    'format' => 'text',
                                    'value' => function($model){
                                        $mainPlatform = VideoMediaBaseInfo::getMainPlatformName($model['main_platform']);
                                        return $mainPlatform;
                                    }
                                ],

                                [
                                    'header' => '入驻平台',
                                    'format' => 'html',
                                    'value' => function($model){
                                        $platforms=VideoMediaBaseInfo::getAllBaseMedia($model['uuid']);
                                        $existPlatform = implode('<br/>',$platforms);
                                        return "<span class=''>".$existPlatform."</span>";
                                    }
                                ],
                                [
                                    'header' => '状态',
                                    'format' => 'html',
                                    'value' => function($model){
                                        if($model['put'] == VideoMediaBaseInfo::ACCOUNT_PUT_SUCCESS){
                                            return "<span class='label label-success'>已上架</span>";
                                        }else{
                                            return "<span class='label label-warning'>未上架</span>";
                                        }

                                    }
                                ],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{add}',
                                    'buttons' => [
                                        'add' => function ($url, $model) {
                                            return Html::button('加入首页', ['class' => 'btn btn-link btn-xs btn-add-home', 'data-uuid' => $model['media_uuid']]);
                                        },
                                        'headerOptions' => ['data-sort-ignore' => 'true'],
                                        'contentOptions' => ['class' => '']
                                    ]
                                ],
                            ]
                        ]);
                    } ?>
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
                                        <option value="1">花椒</option>
                                        <option value="4">美拍</option>
                                        <option value="5">秒拍</option>
                                        <option value="6">斗鱼</option>
                                        <option value="7">映客</option>
                                        <option value="2">熊猫</option>
                                        <option value="9">一直播</option>
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
