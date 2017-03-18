<?php
/**
 * 计划详情页
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 2:38 PM
 */

use yii\helpers\Html;
use common\helpers\MediaHelper;
use common\models\AdWeixinOrder;

$planDetailJs = <<<JS

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

        $('.table-order').on('click', '.btn-order-detail', function(){
            $('#modal-weixin-order-detail').modal('show');
        });
JS;
$this->registerJs($planDetailJs);
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">活动管理</a></li>
        <li><a href="javascript:;">活动详情</a></li>
    </ol>

    <h1 class="page-header">活动详情</h1>

    <div class="row">
        <div class="col-md-12 ui-sortable">
            <div class="panel panel-inverse" data-sortable-id="ui-general-5">
                <div class="panel-body">
                    <legend>活动内容</legend>
                    <div class="row">
                        <div class="col-md-5">
                            <dl class="dl-horizontal">
                                <dt>计划名称:</dt>
                                <dd>
                                    <span class="plan-name"><?php echo $plan['plan_name']; ?></span>
                                </dd>
                                <dt>计划状态:</dt>
                                <dd>
                                    <span class="ad-owner"><?php echo MediaHelper::getPlanStatusLabel($plan['status']); ?></span>
                                </dd>
                                <dl class="dl-horizontal">
                                    <dt>需求描述:</dt>
                                    <dd>
                                        <textarea class="plan-name" cols="100" rows="5" disabled="disabled"><?php echo $plan['plan_name']; ?></textarea>
                                    </dd>
                                </dl>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 ui-sortable">
            <div class="panel panel-inverse" data-sortable-id="ui-general-5">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-2">账号数:
                                <span class="total-price-amount"><?php echo $plan['media_amount']; ?></span>
                        </div>
                        <div class="col-md-2">预计总阅读量:
                            <span class="total-price-amount">test</span>
                        </div>
                        <div class="col-md-2">总粉丝数:
                            <span class="total-price-amount"><?php echo $plan['total_follower_num']; ?></span>
                        </div>
                        <div class="col-md-2">投放总金额:
                            <span class="total-price-amount"><?php echo $plan['total_price_amount_min']; ?></span>
                        </div>
                        <div class="col-md-2" style="color: red;">支付金额:
                            <span class="total-price-amount"><?php echo $plan['total_price_amount_min']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <?= \yii\grid\GridView::widget([
                        'dataProvider' => $orderProvider,
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-order'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['data-sort-ignore' => 'true']
                            ],
                            [
                                'header' => '账号名称',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['public_name'] . '<br>' . $model['public_id'];
                                },
                            ],
                            [
                                'header' => '投放位置',
                                'format' => 'text',
                                'value' => function ($model) {
                                    return MediaHelper::getWeixinPubPosLabel($model['position_code']);
                                    //return $model['position_code'];
                                }
                            ],
                            [
                                'header' => '参考报价(元)',
                                'format' => 'text',
                                'value' => function ($model) {
                                    return $model['price_min'];
                                }
                            ],
                            [
                                'header' => '订单状态',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return MediaHelper::getWeixinOrderStatusLabel($model['order_status'],2);
                                }
                            ],
                            [
                                'header' => '备注',
                                'format' => 'html',
                                'value' => function ($model) {
                                    $mediaRetailPriceArray = MediaHelper::parseMediaWeixinRetailPrice($model['pub_config']);
                                    return "多图文第一条：".$mediaRetailPriceArray['m_1']['pub_type_label']."<br>
                                            多图文第二条：".$mediaRetailPriceArray['m_2']['pub_type_label']."<br>
                                            多图文第3-N条：".$mediaRetailPriceArray['m_3']['pub_type_label']."<br>
                                            单图文：".$mediaRetailPriceArray['s']['pub_type_label'];
                                }
                            ],

                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
