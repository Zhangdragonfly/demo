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

AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/js/weibo/media-list.js');

?>
<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微博</a></li>
        <li><a href="javascript:;">资源管理</a></li>
        <li class="active">全部资源</li>
    </ol>

    <h1 class="page-header">全部资源<?php echo (!empty($vendor_name))? "(".$vendor_name.")":""; ?></h1>

    <div class="row">
        <input type="hidden" name="search_type" value="<?=$search_type?>">
        <div class="col-md-12 main-stage">
    <?php Pjax::begin();?>
<?php
$dateJs = <<<JS
   weiboList();
JS;
$this->registerJs($dateJs); ?>
            <div class="panel panel-inverse pjax-area">
                <?= Html::beginForm(Yii::$app->urlManager->createUrl(array('weibo/media/list'))."&type=".$search_type."&vendor_uuid=".$vendor_uuid, 'post', ['data-pjax' => '', 'class' => 'weibo-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">微博/自媒体主名称</label>
                                <input type="text" class="form-control" name="search_name" value="<?php echo Yii::$app->request->post('search_name', $vendor_name); ?>" placeholder="微博/自媒体主名称" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>标签</label>
                                <select name="media_cate" class="form-control">
                                    <option value="-1" selected>不限</option>
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

                        <div class="col-md-2"  <?php if($search_type==4){echo "style='display:block;'";}?> hidden>
                            <div class="form-group" >
                                <label>过期时间</label>
                                <input type="text" class="form-control expire-time" name="expire_start_time" value="<?php echo Yii::$app->request->post('expire_start_time', ''); ?>"  class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-2" <?php if($search_type==4){echo "style='display:block;'";}?> hidden>
                            <div class="form-group">
                                <label>到</label>
                                <input type="text" class="form-control expire-time" name="expire_end_time" value="<?php echo Yii::$app->request->post('expire_end_time', ''); ?>"  class="form-control ">
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-3" <?php if($search_type==1 ||$search_type==3 ||$search_type==4){echo "style='display:none;'";}?>>
                            <div class="form-group">
                                <label>上下架</label>
                                <select name="is_put" class="form-control ">
                                    <option value="-1" selected>不限</option>
                                    <option value="0" <?php echo Yii::$app->request->post('is_put', -1) == 0 ? 'selected' : '' ?>>已下架</option>
                                    <option value="1" <?php echo Yii::$app->request->post('is_put', -1) == 1 ? 'selected' : '' ?>>已上架</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>报价类型</label>
                                <select name="price_search_type" class="form-control">
                                    <option value="-1" selected>不限</option>
                                    <option value="1" <?php echo Yii::$app->request->post('price_search_type', -1) == 1 ? 'selected' : '' ?>>软广</option>
                                    <option value="2" <?php echo Yii::$app->request->post('price_search_type', -1) == 2 ? 'selected' : '' ?>>微任务</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 price_start" style="display: <?php echo Yii::$app->request->post('price_search_type', -1) == -1 ? 'none' : '' ?>">
                            <div class="form-group">
                                <label>价格</label>
                                <input type="text" class="form-control" name="price_start" value="<?php echo Yii::$app->request->post('price_start', ''); ?>"  class="form-control ">
                            </div>
                        </div>

                        <div class="col-md-2 price_end" style="display:  <?php echo Yii::$app->request->post('price_search_type', -1) == -1 ? 'none' : '' ?>">
                            <div class="form-group">
                                <label>到</label>
                                <input type="text" class="form-control" name="price_end" value="<?php echo Yii::$app->request->post('price_end', ''); ?>"  class="form-control ">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>1.0备注</label>
                                <input type="text" class="form-control" name="accept_remark_one" value="<?php echo Yii::$app->request->post('accept_remark_one', ''); ?>"  class="form-control ">
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30">
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
                                'header' => '微博名称',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'weibo-name'],
                                'value' => function ($model) {
                                    $media_level = "";
                                    switch($model['media_level']){
                                        case 1: $media_level = "<span style='color:#00acac;'>蓝V</span>";break;
                                        case 2: $media_level = "<span style='color:#00acac;'>黄V</span>";break;
                                        case 3: $media_level = "<span style='color:#00acac;'>草根</span>";break;
                                        case 4: $media_level = "<span style='color:#00acac;'>达人</span>";break;
                                       default: $media_level = "<span style='color:#00acac;'>未知</span>";
                                    }
                                    return $model['weibo_name']."<br>".$media_level;
                                },
                            ],
                            [
                                'header' => '首选自媒体主',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'contact','style'=>'width:150px;'],
                                'value' => function ($model) {
                                    return  $model['name'];
                                },
                            ],
                            [
                                'header' => '1.0备注',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-info','style'=>'width:100px;'],
                                'value' => function ($model) {
                                    return  $model['accept_remark_one'];
                                },
                            ],
                            [
                                'header' => '标签',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'login-account'],
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
                                'header' => '价格有效期',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-info','style'=>'color:red;'],
                                'value' => function ($model) {
                                    return  ($model['active_end_time']!=0)?date('Y-m-d',$model['active_end_time']):"(未设置)";
                                },
                            ],

                            [
                                'header' => '平台合作价',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'balance','style'=>'color:#337ab7;'],
                                'value' => function ($model) {
                                    return   "软广直发".$model['soft_direct_price_orig']."<br>软广转发".$model['soft_transfer_price_orig']."<br>微任务直发".$model['micro_direct_price_orig']."<br>微任务转发".$model['micro_transfer_price_orig'];
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
                                'template' => '{vendor-info}<br>{is-put}<br>{is-push}<br>{is-top}',
                                'buttons' => [
                                    'vendor-info' => function ($url, $model) {
                                        if($model['status'] == 1) {
                                            return Html::button('修改', ['class' => 'btn btn-link btn-xs btn-update', 'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/detail')), 'data-uuid' => $model['uuid']]);
                                        }else{
                                            return Html::button('审核', ['class' => 'btn btn-link btn-xs btn-update', 'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/detail')), 'data-uuid' => $model['uuid']]);
                                        }
                                    },
                                    'is-put' => function ($url, $model) {
                                        if($model['status'] == 1){
                                            if($model['is_put']==1){
                                                return Html::button('下架', ['class' => 'btn btn-link btn-xs btn-put','data-uuid'=>$model['uuid'],'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/put-up-down')),'data-type'=>'down']);
                                            }
                                            if($model['is_put']==0){
                                                return Html::button('上架', ['class' => 'btn btn-link btn-xs btn-put','data-uuid'=>$model['uuid'],'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/put-up-down')),'data-type'=>'up']);
                                            }
                                        }else{
                                            return Html::button('删除', ['class' => 'btn btn-link btn-xs btn-delete','data-uuid'=>$model['uuid'],'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/delete-weibo'))]);
                                        }

                                    },
                                    'is-push' => function ($url, $model) {
                                        if($model['status'] == 1) {
                                            if ($model['is_push'] == 1) {
                                                return Html::button('取消主推', ['class' => 'btn btn-link btn-xs btn-push', 'data-uuid' => $model['uuid'], 'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/push-top-down')), 'data-type' => 'down']);
                                            }
                                            if ($model['is_push'] == 0) {
                                                return Html::button('主推', ['class' => 'btn btn-link btn-xs btn-push', 'data-uuid' => $model['uuid'], 'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/push-top-down')), 'data-type' => 'push']);
                                            }
                                        }
                                    },
                                    'is-top' => function ($url, $model) {
                                        if($model['status'] == 1) {
                                            if ($model['is_top'] == 1) {
                                                return Html::button('取消置顶', ['class' => 'btn btn-link btn-xs btn-top', 'data-uuid' => $model['uuid'], 'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/put-top-down')), 'data-type' => 'down']);
                                            }
                                            if ($model['is_top'] == 0) {
                                                return Html::button('置顶', ['class' => 'btn btn-link btn-xs btn-top', 'data-uuid' => $model['uuid'], 'data-url' => Yii::$app->urlManager->createUrl(array('/weibo/media/put-top-down')), 'data-type' => 'top']);
                                            }
                                        }
                                    }
                                ],
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => '']
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
            <?php Pjax::end() ?>
            <table class="footable table table-striped toggle-arrow-tiny table-media-vendor" id="header-fixed" style="position: fixed;top: 54px;display: none;margin-left: 15px">
                <thead>
                <tr>
                    <th data-sort-ignore="true">#</th>
                    <th data-sort-ignore="true">微博名称</th>
                    <th data-sort-ignore="true">首选自媒体主</th>
                    <th data-sort-ignore="true">1.0备注</th>
                    <th data-sort-ignore="true">标签</th>
                    <th data-sort-ignore="true">价格有效期</th>
                    <th data-sort-ignore="true">平台合作价</th>
                    <th data-sort-ignore="true">上架</th>
                    <th data-sort-ignore="true">审核状态</th>
                    <th data-sort-ignore="true">操作时间</th>
                    <th data-sort-ignore="true">操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
