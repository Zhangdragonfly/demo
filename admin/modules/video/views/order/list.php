<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\Pjax;
use common\helpers\DateTimeHelper;
use common\helpers\MediaHelper;

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');
AppAsset::addScript($this, '@web/js/video/order-list.js');


?>
<div id="content" class="content">
<ol class="breadcrumb pull-right">
    <li><a href="javascript:;">视频</a></li>
    <li><a href="javascript:;">投放管理</a></li>
    <li class="active">订单列表</li>
</ol>
<h1 class="page-header">订单列表</h1>
<div class="row">
    <div class="col-md-12 main-stage">
        <?php Pjax::begin();?>
<?php
$dateJs = <<<JS
    orderList();
JS;
$this->registerJs($dateJs);
?>
        <div class="panel panel-inverse pjax-area">
            <?= Html::beginForm(['order/list'], 'post', ['data-pjax' => '','class' => 'video-order-form']); ?>
            <div class="p-t-30 form-search">
                <div class="row m-l-30 m-r-30">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">活动名称</label>
                            <input type="text" class="form-control" name="plan_name" value="<?php echo Yii::$app->request->post('plan_name', ''); ?>" placeholder="活动名称" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>预约平台名称</label>
                            <input type="text" name="account_name" value="<?php echo Yii::$app->request->post('account_name', ''); ?>" placeholder="请输入平台名称" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">预约平台ID</label>
                            <input type="text" class="form-control input-sm" name="account_id" value="<?php echo Yii::$app->request->post('account_id', ''); ?>" placeholder="请输入平台ID" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>订单状态</label>
                            <select name="order_status" class="form-control input-sm">
                                <option value="-1" selected>不限</option>
                                <option value="0" <?php echo Yii::$app->request->post('order_status', -1) == 0? 'selected' : '' ?>>未提交</option>
                                <option value="1" <?php echo Yii::$app->request->post('order_status', -1) == 1 ? 'selected' : '' ?>>已提交</option>
                                <option value="2" <?php echo Yii::$app->request->post('order_status', -1) == 2 ? 'selected' : '' ?>>已完成</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row m-l-30 m-r-30">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>预约投放时间</label>
                            <input type="text" class="form-control plan-time" name="execute_start_time" value="<?php echo Yii::$app->request->post('execute_start_time', ''); ?>"  class="form-control ">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>到</label>
                            <input type="text" class="form-control plan-time" name="execute_end_time" value="<?php echo Yii::$app->request->post('execute_end_time', ''); ?>"  class="form-control ">
                        </div>
                    </div>
                </div>

                <div class="row m-l-30">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="submit" class="btn btn-sm btn-primary btn-submit" value="查&nbsp;&nbsp;询"/>
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
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny tab-order'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['data-sort-ignore' => 'true']
                            ],
                            [
                                'header' => '订单ID',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'order-uuid'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['order_uuid'];
                                },
                            ],
                            [
                                'header' => '预约活动名称',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'plan-name' ],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['plan_name'];
                                },
                            ],
                            [
                                'header' => '预约平台名称',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'account-name','style'=>'color:#00acac'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['account_name'];
                                },
                            ],
                            [
                                'header' => '预约平台ID',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'account-id'],
                                'value' => function ($model, $key, $index, $column) {
                                    foreach(MediaHelper::getVideoPlatformType() as $k=>$v){
                                        if($model['platform_type']==$k){
                                            $platform = $v;
                                        }
                                    };
                                    return $platform."<br>".$model['account_id'];
                                },
                            ],
                            [
                                'header' => '预约价格形式',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'sub-type','style'=>'color:#337ab7;'],
                                'value' => function ($model, $key, $index, $column) {
                                    switch($model['sub_type']){
                                        case 1:return "线上直播";break;
                                        case 2:return "线下活动";break;
                                        case 3:return "原创视频";break;
                                        case 4:return "视频转发";break;
                                        default:return "未知";
                                    }
                                },
                            ],
                            [
                                'header' => '活动参考价',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'order-price','style'=>'color:#337ab7;'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['price'];
                                },
                            ],
                            [
                                'header' => '预计投放时间',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'execute-time'],
                                'value' => function ($model, $key, $index, $column) {
                                    $starTime = DateTimeHelper::getFormattedDateTime($model['execute_start_time']);
                                    $wndTime = DateTimeHelper::getFormattedDateTime($model['execute_end_time']);
                                    return $starTime.'<br>~<br>'.$wndTime;
                                },
                            ],
                            [
                                'header' => '订单状态',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'order-status','style'=>'color:red;' ],
                                'value' => function ($model, $key, $index, $column) {
                                    switch($model['status']){
                                        case 0:return "未提交";break;
                                        case 1:return "已提交";break;
                                        case 2:return "已完成";break;
                                        default:return "未知";
                                    }
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{order-info}',
                                'buttons' => [
                                    'order-info' => function ($url, $model) {
                                        return Html::button('查看订单详情', ['class' => 'btn btn-link btn-xs btn-order-info','data-uuid'=>$model['order_uuid'],'data-url'=>Yii::$app->urlManager->createUrl(array('video/order/detail'))]);
                                    }
                                ],
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => '']
                            ],
                        ]
                    ]);
                } ?>
                <?php Pjax::end() ?>

            </div>
        </div>
    </div>
</div>
</div>
