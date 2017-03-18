<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\Pjax;
use common\models\AdOwnerFundChangeRecord;
use yii\helpers\Url;

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');

$allowCreditUrl = Url::to(['/ad/credit/allow-credit']);
$refuseCreditUrl = Url::to(['/ad/credit/refuse-credit']);

$creditList = <<<JS
        // 控制左侧导航选中
        if(!$('#ad-owner .ad-credit-list').hasClass('active')){
            $('.menu-level-1').each(function(){
                 $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                 $(this).removeClass('active');
            });
            $('.menu-level-3').each(function(){
                 $(this).removeClass('active');
            });

            $('#ad-owner.menu-level-1').addClass('active');
            $('#ad-owner.menu-level-1 .menu-level-2.ad-credit-list').addClass('active');
        }
JS;
$this->registerJs($creditList);
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">广告主管理</a></li>
        <li class="active">授信申请</li>
    </ol>

    <h1 class="page-header">授信申请</h1>

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
                $("input.page").attr("value", $(this).attr("data-page"));
                $(".ad-owner-credit-form").submit();
            });
            //查询计划
            $(".ad-owner-credit-form .btn-submit").click(function(){
                $(".ad-owner-credit-form").submit();
            });
            // 同意授信
            $(".btn-allow-credit").click(function(){
                var uuid = $(this).attr('data-uuid');
                swal({
                    title: "您确定要授信吗？",
                    text: "确认授信将增加该广告主可用授信金额",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    cancelButtonText: "关闭",
                    confirmButtonText: "是的，我要授信",
                    confirmButtonColor: "#ec6c62"
                }, function() {

                    $.post('$allowCreditUrl',
                     {
                        uuid: uuid
                     }, function(data, status) {
                        if(status == 'success'){
                            if(data.err_code == 0){
                                //location.reload();
                                $('.ad-owner-credit-form').submit();
                                $(".sweet-overlay").hide();
                                $(".showSweetAlert").hide();
                            }else{
                                swal('', data.err_msg, 'warning');
                            }

                        }else{
                            swal('', '系统异常', 'error');
                        }

                    })
                });
            });
            // 拒绝授信
            $(".btn-refuse-credit").click(function(){
                var uuid = $(this).attr('data-uuid');
                swal({
                    title: "您确定要拒绝授信吗？",
                    text: "拒绝授信，该广告主将无法通过授信金额支付订单",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    cancelButtonText: "关闭",
                    confirmButtonText: "是的，我拒绝授信",
                    confirmButtonColor: "#ec6c62"
                }, function() {
                    $.post('$refuseCreditUrl',
                    {
                        uuid: uuid
                    }, function(data, status) {
                        if(status == 'success'){
                            if(data.err_code == 0){
                                //location.reload();
                                $('.ad-owner-credit-form').submit();
                                $(".sweet-overlay").hide();
                                $(".showSweetAlert").hide();
                            }else{
                                swal('', data.err_msg, 'warning');
                            }

                        }else{
                            swal('', '系统异常', 'error');
                        }
                    })
                });
            });


JS;
            $this->registerJs($Js);
            ?>
            <div class="panel panel-inverse pjax-area">

                <?= Html::beginForm(['credit/list'], 'post', ['data-pjax' => '', 'class' => 'ad-owner-credit-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>广告主or联系人名称</label>
                                <input type="text" name="ad-owner"
                                       value="<?php echo Yii::$app->request->post('ad-owner', ''); ?>"
                                       placeholder="请输入广告主or联系人名称" class="form-control input-sm ">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>授信状态</label>
                                <?php $status = Yii::$app->request->post('status'); ?>
                                <select name="status" class="form-control input-sm">
                                    <option value="-1">全部</option>
                                    <option value="0"
                                        <?php
                                        if (isset($status)) {
                                            echo $status == AdOwnerFundChangeRecord::STATUS_DEFAULT ? 'selected' : '';
                                        }
                                        ?>>未处理
                                    </option>
                                    <option
                                        value="1" <?= $status == AdOwnerFundChangeRecord::STATUS_SUCCESS ? 'selected' : '' ?> >
                                        同意授信
                                    </option>
                                    <option
                                        value="2" <?= $status == AdOwnerFundChangeRecord::STATUS_CANCEL ? 'selected' : '' ?> >
                                        拒绝授信
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="button" class="btn btn-sm btn-primary btn-submit" value="查&nbsp;&nbsp;询"/>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="page" type="hidden" name="page">
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
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny tab-credit-record'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['data-sort-ignore' => 'true']
                            ],
                            [
                                'header' => '广告主',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'ad-owner-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['comp_name'] . '<br>' . '联系人: ' . $model['ad_owner_contact_name'] . '<br>' . '联系方式: ' . $model['ad_owner_contact'];
                                },
                            ],
                            [
                                'header' => '计划名称',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'plan-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    if (empty($model['plan_name'])) {
                                        return ' - ';
                                    } else {
                                        return $model['plan_name'];
                                    }
                                },
                            ],
                            [
                                'header' => '授信金额(元)',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'credit-amount'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['credit_amount'];
                                },
                            ],
                            [
                                'header' => '申请时间',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'record-create-time'],
                                'value' => function ($model, $key, $index, $column) {
                                    return date('Y-m-d H:i', $model['record_create_time']);
                                },
                            ],
                            [
                                'header' => '处理状态',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'record-status'],
                                'value' => function ($model, $key, $index, $column) {
                                    switch ($model['record_status']) {
                                        case AdOwnerFundChangeRecord::STATUS_DEFAULT:
                                            $status = '未处理';
                                            break;
                                        case AdOwnerFundChangeRecord::STATUS_SUCCESS:
                                            $status = '已通过';
                                            break;
                                        case AdOwnerFundChangeRecord::STATUS_CANCEL:
                                            $status = '已拒绝';
                                            break;
                                        default:
                                            $status = '-';
                                            break;
                                    }
                                    return $status;
                                },
                            ],
                            [
                                'header' => '操作人',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => ''],
                                'value' => function ($model, $key, $index, $column) {
                                    if (!empty($model['operator_name'])) {
                                        return $model['operator_name'] . '<br>' . '处理时间: ' . date('Y-m-d H:i', $model['record_complete_time']);
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'header' => '备注',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'comment'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['comment'];
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{allow-credit}<br>{refuse-credit}',
                                'buttons' => [
                                    'allow-credit' => function ($url, $model) {
                                        if ($model['record_status'] == AdOwnerFundChangeRecord::STATUS_DEFAULT) {
                                            return Html::button('授信', ['class' => 'btn btn-link btn-xs btn-allow-credit', 'data-uuid' => $model['record_uuid']]);
                                        }
                                    },
                                    'refuse-credit' => function ($url, $model) {
                                        if ($model['record_status'] == AdOwnerFundChangeRecord::STATUS_DEFAULT) {
                                            return Html::button('拒绝', ['class' => 'btn btn-link btn-xs btn-refuse-credit', 'data-uuid' => $model['record_uuid']]);
                                        }
                                    }
                                ],
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => '']
                            ]
                        ]
                    ]); ?>
                </div>
            </div>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>

<!-- 授信 -->
<div class="modal fade" id="modal-deal-request-bak" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static" style="display: none">
    <div class="modal-dialog modal-blg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">授信</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#nav-ad-owner-info" data-toggle="tab">广告主信息</a></li>
                            <li class="display-foot"><a href="#nav-ad-owner-fund" data-toggle="tab">账户资金</a></li>
                            <li><a href="#nav-ad-owner-fund-history" data-toggle="tab">资金流水记录</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="nav-ad-owner-info">
                                <h3 class="m-t-10 text-center">广告主信息</h3>
                                <div class="panel panel-inverse">
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <td>广告主名称</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>注册邮箱</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>账号状态</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>联系人</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>电话1</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>电话2</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>微信/QQ</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>公司地址</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>公司网站</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>公司简介</td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-ad-owner-fund">
                                <h3 class="m-t-10 text-center">账户资金</h3>
                                <div class="panel panel-inverse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>当前可用充值金额: </label>
                                                    <p class="form-control-static total-available-topup"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>当前可用授信金额: </label>
                                                    <p class="form-control-static total-available-credit"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>当前冻结充值金额: </label>
                                                    <p class="form-control-static total-frozen-topup"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>当前冻结授信金额: </label>
                                                    <p class="form-control-static total-frozen-credit"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>授信: </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" class="check">
                                                        </span>
                                                        <input type="text" class="form-control credit-amount"
                                                               placeholder="请填写授信金额(元)" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>重置支付密码: </label>
                                                    <div class="input-group ">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" class="check">
                                                        </span>
                                                        <input type="password" class="form-control reset-password"
                                                               placeholder="请输入重置支付密码" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-ad-owner-fund-history">
                                <h3 class="m-t-10 text-center">资金流水记录</h3>
                                <div class="panel panel-inverse">
                                    <div class="panel-body">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Username</th>
                                                <th>Email Address</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Nicky Almera</td>
                                                <td>nicky@hotmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Edmund Wong</td>
                                                <td>edmund@yahoo.com</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Harvinder Singh</td>
                                                <td>harvinder@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Terry Khoo</td>
                                                <td>terry@gmail.com</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-success btn-commit"
                   style="display: none">确&nbsp;&nbsp;认</a>
                <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">关闭</a>
            </div>
        </div>
    </div>
</div>


