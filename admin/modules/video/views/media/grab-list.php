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

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/js/video/grab-list.js');
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">视频管理</a></li>
        <li class="active">抓取资源列表</li>
    </ol>

    <div class="row">
        <div class="col-md-12 main-stage">
            <?php Pjax::begin();?>
<?php
$dateJs = <<<JS
    grabList();
JS;
            $this->registerJs($dateJs);
            ?>
            <div class="panel panel-inverse pjax-area">
                <?= Html::beginForm(['media/grab-list'], 'post', ['data-pjax' => '', 'class' => 'grab-list-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">抓取ID</label>
                                <input type="text" class="form-control" name="grab_id" value="<?php echo Yii::$app->request->post('grab_id', ''); ?>"  class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">抓取url</label>
                                <input type="text" class="form-control" name="grab_url" value="<?php echo Yii::$app->request->post('grab_url', ''); ?>"class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>抓取状态</label>
                                <select name="status" class="form-control">
                                    <option value="-1" selected>不限</option>
                                    <option value="0" <?php echo Yii::$app->request->post('status', -1) == 0 ? 'selected' : '' ?>>待抓取</option>
                                    <option value="1" <?php echo Yii::$app->request->post('status', -1) == 1 ? 'selected' : '' ?>>已抓取</option>
                                </select>
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
                                'header' => '平台类型',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'balance','style'=>'color:red;'],
                                'value' => function ($model) {
                                    foreach(MediaHelper::getVideoPlatformType() as $k=>$v){
                                        if($model['platform_type']==$k){
                                            $platform = $v;
                                        }
                                    };
                                    return $platform;
                                },
                            ],
                            [
                                'header' => '抓取ID',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'weibo-name'],
                                'value' => function ($model) {
                                    return $model['grab_id'];
                                },
                            ],
                            [
                                'header' => '抓取url',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'login-account'],
                                'value' => function ($model) {
                                    return  $model['grab_url'];
                                },
                            ],
                            [
                                'header' => '新建时间',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'login-account'],
                                'value' => function ($model) {
                                    return  date('Y-m-d',$model['create_time']);
                                },
                            ],
                            [
                                'header' => '抓取状态',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'balance','style'=>'color:red;'],
                                'value' => function ($model) {
                                    switch($model['status']){
                                        case 0:return "待抓取";break;
                                        case 1:return "已抓取";break;
                                        default:return "待抓取";
                                    }
                                },
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
