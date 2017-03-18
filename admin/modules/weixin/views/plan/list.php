<?php
/**
 * 计划列表
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:34 PM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\Pjax;
use common\models\AdWeixinPlan;

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

$planJs = <<<JS

        // 控制左侧导航选中
        if(!$('#weixin .trans-manage .plan-list').hasClass('active')){
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
            $('#weixin.menu-level-1 .menu-level-2.trans-manage').addClass('active');
            $('#weixin.menu-level-1 .menu-level-2.trans-manage .menu-level-3.plan-list').addClass('active');
        } 
JS;
$this->registerJs($planJs);
?>

<div id="content" class="content">

    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">活动管理</a></li>
        <li class="active">活动列表</li>
    </ol>

    <h1 class="page-header">计划列表</h1>

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
                $(".plan-form input.page").attr("value", $(this).attr("data-page"));
                $(".plan-form").submit();
            });
            //查询
            $(".plan-form .btn-submit").click(function(){
                $(".plan-form").submit();
            });
            //查看详情
            $('.table-plan').on('click', '.btn-view-detail', function(){
                var url = $(this).attr('data-url');
                window.open(url);
            });
JS;
            $this->registerJs($Js);
            ?>
            <div class="panel panel-inverse pjax-area">
                <?= Html::beginForm(['plan/list'], 'post', ['data-pjax' => '', 'class' => 'plan-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>活动状态</label>
                                <select name="plan-status" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option value="0" <?php echo Yii::$app->request->post('plan-status', -1) == AdWeixinPlan::STATUS_TO_PUBLISH ? 'selected' : '' ?>>
                                        待提交
                                    </option>
                                    <option value="1" <?php echo Yii::$app->request->post('plan-status', -1) == AdWeixinPlan::STATUS_IN_PROGRESS ? 'selected' : '' ?>>
                                        待支付
                                    </option>
                                    <option value="2" <?php echo Yii::$app->request->post('plan-status', -1) == AdWeixinPlan::STATUS_IN_PROGRESS ? 'selected' : '' ?>>
                                        执行中
                                    </option>
                                    <option value="3" <?php echo Yii::$app->request->post('plan-status', -1) == AdWeixinPlan::STATUS_FINISH ? 'selected' : '' ?>>
                                        已完成
                                    </option>
                                    <option value="9" <?php echo Yii::$app->request->post('plan-status', -1) == AdWeixinPlan::STATUS_FINISH ? 'selected' : '' ?>>
                                        已取消
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>活动ID/名称</label>
                                <input type="text" name="search-name" value="<?php echo Yii::$app->request->post('search-name', ''); ?>" placeholder="请输入活动ID/名称" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>下单客户名称</label>
                                <input type="text" name="ad-owner-name" value="<?php echo Yii::$app->request->post('ad-owner-name', ''); ?>" placeholder="请输入广告主or联系人名称" class="form-control input-sm">
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
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => [
                            'nextPageLabel' => '下一页',
                            'prevPageLabel' => '上一页',
                            'firstPageLabel' => '首页',
                            'lastPageLabel' => '尾页',
                            'maxButtonCount' => 10,
                        ],
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-plan'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['data-sort-ignore' => 'true']
                            ],
                            [
                                'header' => '活动ID',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'plan-uuid'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['plan_uuid'];
                                },
                            ],
                            [
                                'header' => '活动名称',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'plan-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['plan_name'];
                                },
                            ],
                            [
                                'header' => '总粉丝量',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'comp-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['total_follower_num'];
                                },
                            ],
                            [
                                'header' => '预计投放总金额(元)',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'budget'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['total_price_amount_min'];
                                }
                            ],
                            [
                                'header' => '活动状态',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => function ($model, $key, $index, $column) {
                                    return ['class' => 'plan-status'];
                                },
                                'value' => function ($model, $key, $index, $column) {
                                    if ($model['plan_status'] == AdWeixinPlan::STATUS_TO_PUBLISH) {
                                        return '<span class="label label-warning">待提交</span>';
                                    } else if ($model['plan_status'] == AdWeixinPlan::STATUS_TO_PAY) {
                                        return '<span class="label label-warning">待支付</span>';
                                    } else if ($model['plan_status'] == AdWeixinPlan::STATUS_IN_PROGRESS) {
                                        return '<span class="label label-success">执行中</span>';
                                    } else if ($model['plan_status'] == AdWeixinPlan::STATUS_FINISH) {
                                        return '<span class="label label-inverse">已完成</span>';
                                    } else if ($model['plan_status'] == AdWeixinPlan::STATUS_CANCEL) {
                                        return '<span class="label label-inverse">已取消</span>';
                                    } else {
                                        return '<span class="label label-default">未知</span>';
                                    }
                                }
                            ],
                            [
                                'header' => '下单客户名称',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'create-time'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['contact_name'];
                                }
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{view-detail}<br>{view-res}',
                                'buttons' => [
                                    'view-detail' => function ($url, $model) {
                                        return Html::button('查看详情', ['class' => 'btn btn-link btn-xs btn-view-detail', 'data-url' => Yii::$app->urlManager->createUrl(array('weixin/plan/detail', 'plan-uuid' => $model['plan_uuid']))]);
                                    },
                                    'view-res' => function ($url, $model) {
                                        if($model['plan_status'] == AdWeixinPlan::STATUS_FINISH){
                                            return Html::button('查看报告', ['class' => 'btn btn-link btn-xs btn-view-res', 'data-url' => Yii::$app->urlManager->createUrl(array('weixin/plan/result', 'plan-uuid' => $model['plan_uuid']))]);
                                        }
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
        </div>
    </div>

</div>
