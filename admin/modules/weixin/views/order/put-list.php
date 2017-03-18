<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use admin\assets\AppAsset;
use yii\widgets\Pjax;

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');

$orderJs = <<<JS

        // 控制左侧导航选中
        if(!$('#weixin .weixin-order-manage .put-list').hasClass('active')){
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
            $('#weixin.menu-level-1 .menu-level-2.weixin-order-manage').addClass('active');
            $('#weixin.menu-level-1 .menu-level-2.weixin-order-manage .menu-level-3.put-list').addClass('active');
        }
JS;
$this->registerJs($orderJs);

?>

<div id="content" class="content">

    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">投放管理</a></li>
        <li class="active">订单列表</li>
    </ol>

    <h1 class="page-header">订单列表</h1>

    <div class="row">
        <div class="col-md-12 main-stage">
            <?php Pjax::begin();
            $Js = <<<JS
JS;
            $this->registerJs($Js);
            ?>
            <div class="panel panel-inverse pjax-area">

                <?= Html::beginForm(['order/list'], 'post', ['data-pjax' => '', 'class' => 'order-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>订单ID</label>
                                <input type="text" name="order-id" value="<?php echo Yii::$app->request->post('order-id', ''); ?>" placeholder="请输入订单ID" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>订单状态</label>
                                <select name="order-status" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option value="1" <?php echo Yii::$app->request->post('order-status', -1) == 1 ? 'selected' : '' ?>>待提交</option>
                                    <option value="100" <?php echo Yii::$app->request->post('order-status', -1) == 100 ? 'selected' : '' ?>>待审核</option>
                                    <option value="5" <?php echo Yii::$app->request->post('order-status', -1) == 5 ? 'selected' : '' ?>>执行中</option>
                                    <option value="2" <?php echo Yii::$app->request->post('order-status', -1) == 2 ? 'selected' : '' ?>>已完成</option>
                                    <option value="3" <?php echo Yii::$app->request->post('order-status', -1) == 3 ? 'selected' : '' ?>>已取消</option>
                                    <option value="4" <?php echo Yii::$app->request->post('order-status', -1) == 4 ? 'selected' : '' ?>>审核未通过</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>活动名称</label>
                                <input type="text" name="plan-name" value="<?php echo Yii::$app->request->post('plan-name', ''); ?>" placeholder="请输入活动名称" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>投放账号/ID</label>
                                <input type="text" name="vendor-name" value="<?php echo Yii::$app->request->post('vendor-name', '') == '' ? Yii::$app->request->get('vendor-uuid', '') : Yii::$app->request->post('vendor-name', ''); ?>" placeholder="请输入资源名称或者ID" class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>投放位置</label>
                                <select name="order-status" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option value="1" <?php echo Yii::$app->request->post('order-status', -1) == 1 ? 'selected' : '' ?>>单图文</option>
                                    <option value="100" <?php echo Yii::$app->request->post('order-status', -1) == 100 ? 'selected' : '' ?>>多图文第一条</option>
                                    <option value="5" <?php echo Yii::$app->request->post('order-status', -1) == 5 ? 'selected' : '' ?>>多图文第二条</option>
                                    <option value="5" <?php echo Yii::$app->request->post('order-status', -1) == 5 ? 'selected' : '' ?>>多图文第3～N条</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>投放金额</label>
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="start" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" name="end"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>投放时间</label>
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="start" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" name="end"/>
                                </div>
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
                <input class="page" type="hidden" name="page">
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
                                        //return $model['order_uuid'];
                                    },
                                ],
                                [
                                    'header' => '投放账号',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'weixin-account'],
                                    'value' => function ($model, $key, $index, $column) {
                                        //return $model['weixin_public_name'] . '<br>' . $model['weixin_public_id'];
                                    },
                                ],
                                [
                                    'header' => '投放位置',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'position'],
                                    'value' => function ($model, $key, $index, $column) {
                                        //return $model['position_code'];
                                    },
                                ],
                                [
                                    'header' => '投放金额',
                                    'format' => 'text',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'execute-price'],
                                    'value' => function ($model, $key, $index, $column) {
                                        //return $model['execute_price'];
                                    },
                                ],
                                [
                                    'header' => '投放时间',
                                    'format' => 'text',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'order-create-time'],
                                    'value' => function ($model, $key, $index, $column) {
                                        //return $model['order_create_time'];
                                    },
                                ],
                                [
                                    'header' => '订单状态',
                                    'format' => 'text',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'order-status'],
                                    'value' => function ($model, $key, $index, $column) {
                                        //return \common\helpers\MediaHelper::getWeixinOrderStatusLabel($model['order_status']);
                                    },
                                ],
                                [
                                    'header' => '活动名称',
                                    'format' => 'text',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'plan-name'],
                                    'value' => function ($model, $key, $index, $column) {
                                        //return $model['plan_name'];
                                    },
                                ],
                                [
                                    'header' => '下单时间',
                                    'format' => 'text',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'execute-time'],
                                    'value' => function ($model, $key, $index, $column) {

                                    },
                                ],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{order-info}',
                                    'buttons' => [
                                        'order-info' => function ($url, $model) {
                                            return Html::button('订单详情', ['class' => 'btn btn-link btn-xs btn-order-info']);
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

