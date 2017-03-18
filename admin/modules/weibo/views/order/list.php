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

//$updateMedia = Yii::$app->urlManager->createUrl(array('weibo/order/detail'));


AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/js/weibo/order-list.js');

?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微博管理</a></li>
        <li class="active">微博资源列表</li>
    </ol>

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
        <?= Html::beginForm(['order/list'], 'post', ['data-pjax' => '', 'class' => 'weibo-order-form']); ?>
        <div class="p-t-30 form-search">
            <div class="row m-l-30 m-r-30">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">订单ID</label>
                        <input type="text" class="form-control" name="order_uuid" value="<?php echo Yii::$app->request->post('order_uuid', ''); ?>" placeholder="订单ID" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">活动名称</label>
                        <input type="text" class="form-control" name="plan_name" value="<?php echo Yii::$app->request->post('plan_name', ''); ?>" placeholder="活动名称" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">预约账号</label>
                        <input type="text" class="form-control" name="search_name" value="<?php echo Yii::$app->request->post('search_name', ''); ?>" placeholder="账号名称" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>预约订单状态</label>
                        <select name="status" class="form-control">
                            <option value="-1" selected>不限</option>
                            <option value="0" <?php echo Yii::$app->request->post('status', -1) == 0 ? 'selected' : '' ?>>未提交</option>
                            <option value="1" <?php echo Yii::$app->request->post('status', -1) == 1 ? 'selected' : '' ?>>已提交</option>
                            <option value="2" <?php echo Yii::$app->request->post('status', -1) == 2 ? 'selected' : '' ?>>已完成</option>
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
                        'header' => '订单ID',
                        'format' => 'html',
                        'headerOptions' => ['data-sort-ignore' => 'true'],
                        'contentOptions' => ['class' => 'weibo-name'],
                        'value' => function ($model) {
                            return $model['order_uuid'];
                        },
                    ],
                    [
                        'header' => '预约活动名称',
                        'format' => 'html',
                        'headerOptions' => ['data-sort-ignore' => 'true'],
                        'contentOptions' => ['class' => 'contact'],
                        'value' => function ($model) {
                            return  $model['plan_name'];
                        },
                    ],
                    [
                        'header' => '账号名称',
                        'format' => 'text',
                        'headerOptions' => ['data-sort-ignore' => 'true'],
                        'contentOptions' => ['class' => 'login-account'],
                        'value' => function ($model) {
                            return  $model['weibo_name'];
                        },
                    ],
                    [
                        'header' => '预约价格位置',
                        'format' => 'html',
                        'headerOptions' => ['data-sort-ignore' => 'true'],
                        'contentOptions' => ['class' => 'media-info','style'=>'color:#337ab7;'],
                        'value' => function ($model) {
                            switch($model['sub_type']){
                                case 1:return "软广直发";break;
                                case 2:return "软广转发";break;
                                case 3:return "微任务直发";break;
                                case 4:return "微任务转发";break;
                                default:return "未知";
                            }
                        },
                    ],
                    [
                        'header' => '参考价',
                        'format' => 'html',
                        'headerOptions' => ['data-sort-ignore' => 'true'],
                        'contentOptions' => ['class' => 'balance','style'=>'color:#337ab7;'],
                        'value' => function ($model) {
                            return  $model['price'];
                        },
                    ],
                    [
                        'header' => '预计投放时间',
                        'format' => 'html',
                        'headerOptions' => ['data-sort-ignore' => 'true'],
                        'contentOptions' => ['class' => 'balance'],
                        'value' => function ($model) {
                            $start_time = (!empty($model['execute_start_time']))?date('Y-m-d',$model['execute_start_time']):"/";
                            $end_time = (!empty($model['execute_end_time']))?date('Y-m-d',$model['execute_end_time']):"/";
                            return  $start_time."<br>~<br>".$end_time;
                        },
                    ],
                    [
                        'header' => '预约订单状态',
                        'format' => 'text',
                        'headerOptions' => ['data-sort-ignore' => 'true'],
                        'contentOptions' => ['class' => 'balance','style'=>'color:red;'],
                        'value' => function ($model) {
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
                        'template' => '{detail-info}',
                        'buttons' => [
                            'detail-info' => function ($url, $model) {
                                return Html::button('查看订单详情', ['class' => 'btn btn-link btn-xs btn-detail','data-uuid'=>$model['order_uuid'],'data-url'=>Yii::$app->urlManager->createUrl(array('weibo/order/detail'))]);
                               // return Html::button('编辑', ['class' => 'btn btn-link btn-xs btn-update','data-uuid'=>$model['uuid']]).Html::button('删除', ['class' => 'btn btn-link btn-xs btn-delete','data-uuid'=>$model['uuid']]);
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
    <table class="footable table table-striped toggle-arrow-tiny table-media-vendor" id="header-fixed" style="position: fixed;top: 54px;display: none;margin-left: 15px">
        <thead>
            <tr>
                <th data-sort-ignore="true">订单ID</th>
                <th data-sort-ignore="true">预约名称</th>
                <th data-sort-ignore="true">预约账号</th>
                <th data-sort-ignore="true">软广参考价</th>
                <th data-sort-ignore="true">微任务参考价</th>
                <th data-sort-ignore="true">预计投放时间</th>
                <th data-sort-ignore="true">预约订单状态</th>
                <th data-sort-ignore="true">操作</th>
            </tr>
        </thead>
    </table>
</div>
</div>
</div>
