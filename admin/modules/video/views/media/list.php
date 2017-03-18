<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 28/11/16 2:33 PM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\Pjax;
use common\helpers\MediaHelper;

//$addMeipai = Yii::$app->urlManager->createUrl('video/meipai/create');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/js/video/video-list.js');
$this->title = '全部资源';

?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">视频</a></li>
        <li><a href="javascript:;">资源管理</a></li>
        <li class="active">全部资源</li>
    </ol>

    <h1 class="page-header">全部资源<?php echo (!empty($vendor_name))? "(".$vendor_name.")":""; ?></h1>
    <?php Pjax::begin();?>
<?php
$BaseJs = <<<JS
    videoList();
JS;
$this->registerJs($BaseJs); ?>
    <div class="row">
        <input type="hidden" name="search_type" value="<?=$search_type?>">
        <div class="col-md-12 main-stage">
            <div class="panel panel-inverse pjax-area">
                <?= Html::beginForm(Yii::$app->urlManager->createUrl(array('video/media/list'))."&type=".$search_type."&vendor_uuid=".$vendor_uuid, 'post', ['data-pjax' => '', 'class' => 'video-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">艺人名称/账号ID</label>
                                <input type="text" class="form-control" name="search_name" value="<?php echo Yii::$app->request->post('search_name', ""); ?>" placeholder="艺人名称/账号ID" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">自媒体主名称</label>
                                <input type="text" class="form-control" name="search_vendor" value="<?php echo Yii::$app->request->post('search_vendor', ""); ?>" placeholder="自媒体主名称" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2" >
                            <div class="form-group">
                                <label>平台</label>
                                <select name="platform_type" class="form-control">
                                    <option value="-1" selected>全部</option>
                                    <?php foreach(MediaHelper::getVideoPlatformType() as $k=>$v){?>
                                        <option value="<?=$k;?>"<?php echo Yii::$app->request->post('platform_type', -1) == $k ? 'selected' : ''?> ><?=$v;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>标签</label>
                                <select name="media_cate" class="form-control">
                                    <option value="-1" selected>全部</option>
                                    <?php foreach(MediaHelper::getMediaCateList() as $k=>$v){?>
                                        <option value="<?=$k;?>"<?php echo Yii::$app->request->post('media_cate', -1) == $k ? 'selected' : ''?> ><?=$v;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" <?php if($search_type!=""){echo "style='display:none;'";}?>>
                            <div class="form-group">
                                <label>审核状态</label>
                                <select name="status" class="form-control">
                                    <option value="-1" selected>不限</option>
                                    <option value="0" <?php echo Yii::$app->request->post('status', -1) == 0 ? 'selected' : '' ?>>待审核</option>
                                    <option value="1" <?php echo Yii::$app->request->post('status', -1) == 1 ? 'selected' : '' ?>>已审核</option>
                                    <option value="2" <?php echo Yii::$app->request->post('status', -1) == 2 ? 'selected' : '' ?>>未通过</option>
                                </select>
                            </div>
                        </div>


                    </div>

                    <div class="row m-l-30 m-r-30">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>参考报价</label>
                                <div class="input-group">
                                    <input type="text" value="<?php echo Yii::$app->request->post('price_min', ''); ?>" class="form-control input-sm" name="price_min">
                                    <span class="input-group-addon"> - </span>
                                    <input type="text" value="<?php echo Yii::$app->request->post('price_max', ''); ?>" class="form-control input-sm" name="price_max">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2" <?php if($search_type==1 ||$search_type==3 ||$search_type==4){echo "style='display:none;'";}?>>
                            <div class="form-group">
                                <label>上下架</label>
                                <select name="is_put" class="form-control ">
                                    <option value="-1" selected>不限</option>
                                    <option value="0" <?php echo Yii::$app->request->post('is_put', -1) == 0 ? 'selected' : '' ?>>已下架</option>
                                    <option value="1" <?php echo Yii::$app->request->post('is_put', -1) == 1 ? 'selected' : '' ?>>已上架</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary btn-submit btnSearch" value="查&nbsp;&nbsp;询"/>
                            </div>
                        </div>
                    </div>

                    <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">
                    <?= Html::endForm() ?>
                </div>

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
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-media-vendor', 'id' => 'fixed-header-data-table'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['data-sort-ignore' => 'true']
                            ],
                            [
                                'header' => '平台昵称/ID',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'weibo-name'],
                                'value' => function ($model) {
                                    return "<span style='color:#00acac;'>"
                                            .$model['account_name']."</span><br>"
                                            .$model['nickname']."<br>"
                                            .$model['account_id']."<br><span style='color:#337ab7;'>"
                                            .($model['follower_num']/10000)."万</span>";
                                },
                            ],
                            [
                                'header' => '入驻平台',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'contact'],
                                'value' => function ($model) {
                                    foreach(MediaHelper::getVideoPlatformType() as $k=>$v){
                                        if($model['platform_type']==$k){
                                            $platform = $v;
                                        }
                                    };
                                    if($model['platform_type'] == $model['main_platform']){
                                        return $platform."<br><span style='color:red;'>(主打平台)</span>";
                                    }else{
                                        return $platform;
                                    }

                                },
                            ],
                            [
                                'header' => '首选媒体主',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-info','style'=>'color:#00acac;'],
                                'value' => function ($model) {
                                    return  $model['name'];
                                },
                            ],
                            [
                                'header' => '标签',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-cate'],
                                'value' => function ($model) {
                                    $media_cate_str = "";
                                    $media_cate_array = json_decode(MediaHelper::parseMediaCate($model['media_cate']));
                                    if(!empty($media_cate_array)){
                                        foreach($media_cate_array as $val){
                                            $media_cate_str .= $val."<br/>";
                                        }
                                    }
                                    return $media_cate_str;
                                },
                            ],
                            [
                                'header' => '平台合作价',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'balance','style'=>'color:#337ab7;'],
                                'value' => function ($model) {
                                    $platform_type = $model['platform_type'];
                                    if($platform_type == 5){//秒拍
                                        return   "原创视频 ".$model['price_orig_one']."<br>视频转发 ".$model['price_orig_two'];
                                    }else{//其他平台
                                        return   "线上直播 ".$model['price_orig_one']."<br>线下活动 ".$model['price_orig_two'];
                                    }
                                },
                            ],
                            [
                                'header' => '价格有效期',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-info','style'=>'color:red;'],
                                'value' => function ($model) {
                                    return  ($model['active_end_time']!=0)?date('Y-m-d',$model['active_end_time']):"(未设置)";
                                },
                            ],
                            [
                                'header' => '上架',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'balance','style'=>'color:#00acac;'],
                                'value' => function ($model) {
                                    switch($model['is_put']){
                                        case 0:return "下架";break;
                                        case 1:return "上架";break;
                                        default:return "未知";
                                    }
                                },
                            ],
                            [
                                'header' => '审核状态',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'balance','style'=>'color:red;'],
                                'value' => function ($model) {
                                    switch($model['status']){
                                        case 0:return "待审核";break;
                                        case 1:return "已审核";break;
                                        case 2:return "未通过";break;
                                        default:return "未知";
                                    }
                                },
                            ],
                            [
                                'header' => '操作时间',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-info'],
                                'value' => function ($model) {
                                    $create_time =  !empty($model['create_time'])?date('Y-m-d',$model['create_time']):"";
                                    $update_time =  !empty($model['update_time'])?date('Y-m-d',$model['update_time']):"";
                                    return "入驻时间<br>".$create_time."<br>更新时间<br>".$update_time;
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{vendor-info}<br>{is-put}<br>{is-top}<br>{is-push}',
                                'buttons' => [
                                    'vendor-info' => function ($url, $model) {
                                        if($model['status'] == 1) {
                                            return Html::button('修改', ['class' => 'btn btn-link btn-xs btn-update', 'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/detail')), 'data-uuid' => $model['video_uuid']]);
                                        }else{
                                            return Html::button('审核', ['class' => 'btn btn-link btn-xs btn-update', 'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/detail')), 'data-uuid' => $model['video_uuid']]);
                                        }
                                    },
                                    'is-put' => function ($url, $model) {
                                        if($model['status'] == 1){
                                            if($model['is_put']==1){
                                                return Html::button('下架', ['class' => 'btn btn-link btn-xs btn-put','data-uuid'=>$model['platform_uuid'],'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/put-up-down')),'data-type'=>'down']);
                                            }
                                            if($model['is_put']==0){
                                                return Html::button('上架', ['class' => 'btn btn-link btn-xs btn-put','data-uuid'=>$model['platform_uuid'],'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/put-up-down')),'data-type'=>'up']);
                                            }
                                        }else{
                                            return Html::button('删除', ['class' => 'btn btn-link btn-xs btn-delete','data-uuid'=>$model['platform_uuid'],'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/delete-platform-info'))]);
                                        }
                                    },
                                    'is-top' => function ($url, $model) {
                                        if($model['status'] == 1) {
                                            if ($model['is_top'] == 1) {
                                                return Html::button('取消置顶', ['class' => 'btn btn-link btn-xs btn-top', 'data-uuid' => $model['platform_uuid'], 'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/put-top-down')), 'data-type' => 'down']);
                                            }
                                            if ($model['is_top'] == 0) {
                                                return Html::button('置顶', ['class' => 'btn btn-link btn-xs btn-top', 'data-uuid' => $model['platform_uuid'], 'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/put-top-down')), 'data-type' => 'top']);
                                            }
                                        }
                                    } ,
                                    'is-push' => function ($url, $model) {
                                        if($model['status'] == 1) {
                                            if ($model['is_push'] == 1) {
                                                return Html::button('取消主推', ['class' => 'btn btn-link btn-xs btn-push', 'data-uuid' => $model['platform_uuid'], 'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/push-top-down')), 'data-type' => 'down']);
                                            }
                                            if ($model['is_push'] == 0) {
                                                return Html::button('主推', ['class' => 'btn btn-link btn-xs btn-push', 'data-uuid' => $model['platform_uuid'], 'data-url' => Yii::$app->urlManager->createUrl(array('/video/media/push-top-down')), 'data-type' => 'top']);
                                            }
                                        }
                                    }
                                ],
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                            ],
                        ]
                    ]); ?>

                </div>
            </div>
            <?php Pjax::end() ?>
<!--            <table class="footable table table-striped toggle-arrow-tiny table-media-vendor" id="header-fixed" style="position: fixed;top: 54px;display: none;margin-left: 15px">-->
<!--                <thead>-->
<!--                <tr>-->
<!--                    <th data-sort-ignore="true">#</th>-->
<!--                    <th data-sort-ignore="true">微博名称</th>-->
<!--                    <th data-sort-ignore="true">首选自媒体主</th>-->
<!--                    <th data-sort-ignore="true">1.0备注</th>-->
<!--                    <th data-sort-ignore="true">标签</th>-->
<!--                    <th data-sort-ignore="true">价格有效期</th>-->
<!--                    <th data-sort-ignore="true">平台合作价</th>-->
<!--                    <th data-sort-ignore="true">上架</th>-->
<!--                    <th data-sort-ignore="true">审核状态</th>-->
<!--                    <th data-sort-ignore="true">操作时间</th>-->
<!--                    <th data-sort-ignore="true">操作</th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--            </table>-->
<!---->

        </div>
    </div>
</div>
