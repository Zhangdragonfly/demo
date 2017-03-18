<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\Pjax;

$weixinAdPlanListUrl = Yii::$app->urlManager->createUrl(array('weixin/plan/list'));
$getAdOwnerDetail = Yii::$app->urlManager->createUrl(array('ad/owner/detail'));
$setAdOwnerFundAndPayPass = Yii::$app->urlManager->createUrl(array('ad/owner/set-fund-and-pay-pass'));
$adOwnerDeleteUrl = Yii::$app->urlManager->createUrl(array('ad/owner/delete'));

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');

$adOwnerJs = <<<JS
    //表头一直悬浮在列表头部
    $(function() {
      $(window).scroll(function() {
          var headFix = $("#header-fixed");
          var _head = $("#fixed-header-data-table");
          var headFixTh = headFix.find("thead tr th");
          var _headTh = _head.find("thead tr th");
          headFix.width(_head.width());
          for(var i=1;i<=_headTh.length;i++){
            headFix.find("thead tr th:nth-child("+i+")").width(_head.find("thead tr th:nth-child("+i+")").width());
          }
          var difference = _head.offset().top - $(this).scrollTop();
          (difference < 54) ? headFix.show() : headFix.hide();
       })
    });
	
	

        // 控制左侧导航选中
        if(!$('#ad-owner .ad-owner-list').hasClass('active')){
            $('.menu-level-1').each(function(){
                 $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                 $(this).removeClass('active');
            });

            $('#ad-owner.menu-level-1').addClass('active');
            $('#ad-owner.menu-level-1 .menu-level-2.ad-owner-list').addClass('active');
        }

        $('.ad-owner-form .plan-create-time-range').daterangepicker({
                'singleDatePicker': false,
                'format': 'YYYY-MM-DD',
                'autoApply': true,
                'opens': 'center',
                'drops': 'down',
                'timePicker': false,
                'timePicker24Hour': false,
                'startDate' : new Date()
        });

        $('#modal-media-type-select').on('click','.btn-commit',function() {
                var mediaType = $('#modal-media-type-select').find('.media-type option:selected').val();
                var uuid = $('#modal-media-type-select').find('.ad-owner-uuid').val();
                var url = '';
                if(mediaType == 1){
                    url = '$weixinAdPlanListUrl' + '&ad_owner_uuid=' + uuid;
                }
                window.open(url, '_blank');
        });
JS;
$this->registerJs($adOwnerJs);
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">广告主管理</a></li>
        <li class="active">广告主列表</li>
    </ol>

    <h1 class="page-header">广告主列表</h1>

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
                $(".ad-owner-form input.page").attr("value", $(this).attr("data-page"));
                $(".ad-owner-form").submit();
            });
            //查询计划
            $(".ad-owner-form .btn-submit").click(function(){
                $(".ad-owner-form").submit();
            });

            // 信息管理
            $('.tab-ad-owner').on('click', '.btn-owner-detail', function(){
                var uuid = $(this).attr('data-uuid');
                $('#modal-ad-owner-detail .ad-owner-uuid').val(uuid);
                $.ajax({
                        url: '$getAdOwnerDetail',
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        data: {'ad-owner-uuid': uuid},
                        success: function (resp) {
                                if(resp.err_code == 0){
                                    var data = resp.ad_owner;
                                    var comp_name = data.comp_name;
                                    var login_account = data.login_account;
                                    var contact_name = data.contact_name == '' ? '-' : data.contact_name;
                                    var contact_1 = data.contact_1 == '' ? '-' : data.contact_1;
                                    var contact_2 = data.contact_2 == '' ? '-' : data.contact_2;
                                    var qq = data.qq == '' ? '-' : data.qq;
                                    var weixin = data.weixin == '' ? '-' : data.weixin;
                                    var comp_address = data.comp_address == '' ? '-' : data.comp_address;
                                    var comp_website = data.comp_website == '' ? '-' : data.comp_website;
                                    var comp_desc = data.comp_desc == '' ? '-' : data.comp_desc;
                                    var account_status = data.account_status;
                                    var total_available_topup = data.total_available_topup;
                                    var total_available_credit = data.total_available_credit;
                                    var total_frozen_topup = data.total_frozen_topup;
                                    var total_frozen_credit = data.total_frozen_credit;

                                    var status_label = '';
                                    if(account_status == '1'){
                                        status_label = '有效'
                                    }else if(account_status == '0'){
                                        status_label = '无效';
                                    }else{
                                        status_label = '未知';
                                    }
                                    var content = $('#modal-ad-owner-detail .tab-content');
                                    // 广告主信息
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(0).find('td').eq(1).text(comp_name);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(1).find('td').eq(1).text(login_account);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(2).find('td').eq(1).text(status_label)
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(3).find('td').eq(1).text(contact_name);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(4).find('td').eq(1).text(contact_1);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(5).find('td').eq(1).text(contact_2);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(6).find('td').eq(1).text(qq);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(7).find('td').eq(1).text(weixin);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(8).find('td').eq(1).text(comp_address);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(9).find('td').eq(1).text(comp_website);
                                    content.find('#nav-ad-owner-info .table-bordered tr').eq(10).find('td').eq(1).text(comp_desc);

                                    // 账户资金
                                    content.find('#nav-ad-owner-fund .total-available-topup').text(total_available_topup);
                                    content.find('#nav-ad-owner-fund .total-available-credit').text(total_available_credit);
                                    content.find('#nav-ad-owner-fund .total-frozen-topup').text(total_frozen_topup);
                                    content.find('#nav-ad-owner-fund .total-frozen-credit').text(total_frozen_credit);
                                    content.find('#nav-ad-owner-fund .select-field').removeAttr('checked');
                                    content.find('#nav-ad-owner-fund .input-field').val('');
                                    content.find('#nav-ad-owner-fund .input-field').attr('disabled', true);

                                    $('#modal-ad-owner-detail').modal('show');
                                } else {
                                    swal({title: "系统出错", text: "请联系系统管理员", type: "error"});
                                }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错", text: "请联系系统管理员", type: "error"});
                        }
                });
            });
            // 打开确认按钮
            $('#modal-ad-owner-detail .modal-content .modal-body').on('click','ul li',function() {
                if($(this).hasClass('display-foot')){
                    $('#modal-ad-owner-detail .modal-footer').find('.btn-fund-commit').show();
                }else{
                    $('#modal-ad-owner-detail .modal-footer').find('.btn-fund-commit').hide();
                }
            });
            // 充值/授信/重置支付密码
            $('#modal-ad-owner-detail #nav-ad-owner-fund .input-group-addon').on('click', '.select-field', function() {
                if($(this).is(':checked')){
                    $(this).parent().siblings().attr('disabled', false);
                }else{
                    $(this).parent().siblings().attr('disabled', true);
                    $(this).parent().siblings().val('');
                }
            });
            // 账户资金确认
            $('#modal-ad-owner-detail .btn-fund-commit').on('click', function() {
                var ad_owner_uuid = $('#modal-ad-owner-detail .ad-owner-uuid').val();
                var fund_area = $('#modal-ad-owner-detail #nav-ad-owner-fund');
                var topup_amount = $.trim(fund_area.find('.topup-amount').val());
                var credit_amount = $.trim(fund_area.find('.credit-amount').val());
                var reset_password = $.trim(fund_area.find('.reset-password').val());

                if(fund_area.find('.to-input-topup-amount').is(':checked') && (topup_amount == '' || topup_amount <= 0)){
                    swal({title: "", text: "请检查充值金额填写是否正确!", type: "error"});
                    return false;
                }
                if(fund_area.find('.to-input-credit-amount').is(':checked') && (credit_amount == '' || credit_amount <= 0)){
                    swal({title: "", text: "请检查授信金额填写是否正确!", type: "error"});
                    return false;
                }
                if(fund_area.find('.to-reset-pay-pass').is(':checked') && (reset_password == '' || reset_password <= 0)){
                    swal({title: "", text: "请检查重置支付密码填写是否正确!", type: "error"});
                    return false;
                }
                if(topup_amount == ''){
                    topup_amount = 0;
                }
                if(credit_amount == ''){
                    credit_amount = 0;
                }
                if(topup_amount == 0 && credit_amount == 0 && reset_password == ''){
                    return false;
                }
                swal({
                        title: '确认保存么？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: false
                },function () {
                    $.ajax({
                            url: '$setAdOwnerFundAndPayPass',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {ad_owner_uuid: ad_owner_uuid, topup_amount: topup_amount, credit_amount: credit_amount, reset_password: reset_password},
                            success: function (resp) {
                                if(resp.err_code == 200){
                                    swal({title: "", text: "广告主不存在!", type: "error"});
                                    return false;
                                }else{
                                    swal({title: "保存成功！", text: "", type: "success"});
                                    $('#modal-ad-owner-detail').modal('hide');
                                    $('.form-search .btn-submit').trigger('click');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                                return false;
                            }
                    });
                });
            })

            // 查看计划列表
            $('.tab-ad-owner').on('click', '.btn-plan-list', function(){
                $('#modal-media-type-select').modal('show');
                var uuid = $(this).attr('data-uuid');
                $('#modal-media-type-select').find('.ad-owner-uuid').val(uuid);
            });

            // 分配销售
            $('.tab-ad-owner').on('click', '.btn-assign-seller', function(){
                $('#modal-assign-seller').modal('show');
            });

            // 删除广告主
            $('.tab-ad-owner').on('click', '.btn-delete', function(){
                var uuid = $(this).attr('data-uuid');
                swal({
                        title: '确认删除么？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: false
                },function () {
                    $.ajax({
                            url: '$adOwnerDeleteUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {uuid: uuid},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal({title: "删除失败！", text: "请联系系统管理员", type: "error"});
                                    return false;
                                }else{
                                    swal({title: "删除成功！", text: "", type: "success"});
                                    $('.form-search .btn-submit').trigger('click');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                                return false;
                            }
                    });
                });
            });
JS;
            $this->registerJs($Js);
            ?>
            <div class="panel panel-inverse pjax-area">

                <?= Html::beginForm(['owner/list'], 'post', ['data-pjax' => '', 'class' => 'ad-owner-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>广告主or联系人名称</label>
                                <input type="text" name="comp-name"
                                       value="<?php echo Yii::$app->request->post('comp-name', ''); ?>"
                                       placeholder="请输入广告主or联系人名称" class="form-control input-sm ">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>注册邮箱</label>
                                <input type="text" name="login-account"
                                       value="<?php echo Yii::$app->request->post('login-account', ''); ?>"
                                       placeholder="请输入广告主注册邮箱" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>手机号</label>
                                <input type="text" name="contact"
                                       value="<?php echo Yii::$app->request->post('contact', ''); ?>"
                                       placeholder="请输入手机号" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-md-2" style="display: none">
                            <div class="form-group">
                                <label>销售人员</label>
                                <select name="seller" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option value="1">Tony</option>
                                    <option value="2">Jack</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30" style="display: none">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>最近下单时间</label>
                                <input type="text" name="plan-create-time-range"
                                       value="<?php echo Yii::$app->request->post('plan-create-time-range', ''); ?>"
                                       placeholder="" class="form-control input-sm plan-create-time-range">
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
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny tab-ad-owner','id'=>'fixed-header-data-table'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['data-sort-ignore' => 'true']
                            ],
                            [
                                'header' => '广告主',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'comp-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    if (empty($model['comp_name'])) {
                                        return $model['contact_name'] . '<br>' . $model['login_account'];
                                    } else {
                                        return $model['comp_name'] . '<br>' . $model['login_account'];
                                    }
                                },
                            ],
                            [
                                'header' => '联系方式',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'contact-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    $colVal = '联系人: ' . $model['contact_name'];
                                    if (empty($model['contact_2'])) {
                                        return $colVal . '<br>' . $model['contact_1'];
                                    } else {
                                        return $colVal . '<br>' . $model['contact_1'] . '<br>' . $model['contact_2'];
                                    }
                                },
                            ],
                            [
                                'header' => '下单量',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'order-cnt'],
                                'value' => function ($model, $key, $index, $column) {
                                    return 0;
                                },
                            ],
                            [
                                'header' => '累计投放金额',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'xxxx'],
                                'value' => function ($model, $key, $index, $column) {
                                    return 0;
                                },
                            ],
                            [
                                'header' => '可用充值(元)',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'xxxx'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['total_available_topup'];
                                },
                            ],
                            [
                                'header' => '可用授信(元)',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'xxxx'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['total_available_credit'];
                                },
                            ],
                            [
                                'header' => '最近下单时间',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'last-publish-plan-time'],
                                'value' => function ($model, $key, $index, $column) {
                                    if (empty($model['last_publish_plan_time'])) {
                                        return ' - ';
                                    }
                                    return $model['last_publish_plan_time'];
                                },
                            ],
                            [
                                'header' => '注册时间',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'last-publish-plan-time'],
                                'value' => function ($model, $key, $index, $column) {
                                    if (empty($model['create_time'])) {
                                        return ' - ';
                                    }
                                    return date('Y-m-d',$model['create_time']);
                                },
                            ],
                            [
                                'header' => '销售',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'seller-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    return '未分配';
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{owner-detail}<br>{plan-list}<br>{delete}',
                                'buttons' => [
                                    'owner-detail' => function ($url, $model) {
                                        return Html::button('信息管理', ['class' => 'btn btn-link btn-xs btn-owner-detail', 'data-uuid' => $model['ad_owner_uuid']]);
                                    },
                                    'plan-list' => function ($url, $model) {
                                        return Html::button('计划列表', ['class' => 'btn btn-link btn-xs btn-plan-list', 'data-uuid' => $model['ad_owner_uuid']]);
                                    },
                                    'assign-seller' => function ($url, $model) {
                                        return Html::button('分配销售', ['class' => 'btn btn-link btn-xs btn-assign-seller']);
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::button('删除', ['class' => 'btn btn-link btn-xs btn-delete', 'data-uuid' => $model['ad_owner_uuid']]);
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
            <table class="footable table table-striped toggle-arrow-tiny tab-ad-owner" id="header-fixed" style="position: fixed;top: 54px;display: none;margin-left: 15px"><thead>
                <tr><th data-sort-ignore="true">#</th><th data-sort-ignore="true">广告主</th><th data-sort-ignore="true">联系方式</th><th data-sort-ignore="true">下单量</th><th data-sort-ignore="true">累计投放金额</th><th data-sort-ignore="true">可用充值(元)</th><th data-sort-ignore="true">可用授信(元)</th><th data-sort-ignore="true">最近下单时间</th><th data-sort-ignore="true">销售</th><th data-sort-ignore="true">操作</th></tr>
                </thead></table>
        </div>
    </div>
</div>

<!-- 广告主信息 -->
<div class="modal fade" id="modal-ad-owner-detail" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog modal-blg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">信息管理</h4>
            </div>

            <div class="modal-body">

                <input type="hidden" class="ad-owner-uuid" value="">

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
                                                <td>QQ</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>微信</td>
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
                                                    <label>充值: </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox"
                                                                   class="select-field to-input-topup-amount">
                                                        </span>
                                                        <input type="text" class="form-control input-field topup-amount"
                                                               placeholder="请填写充值金额(元)" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>授信: </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox"
                                                                   class="select-field to-input-credit-amount">
                                                        </span>
                                                        <input type="text"
                                                               class="form-control input-field credit-amount"
                                                               placeholder="请填写授信金额(元)" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>重置支付密码: </label>
                                                    <div class="input-group ">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox"
                                                                   class="select-field to-reset-pay-pass">
                                                        </span>
                                                        <input type="password"
                                                               class="form-control input-field reset-password"
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
                <a href="javascript:;" class="btn btn-sm btn-success btn-fund-commit" style="display: none">确&nbsp;&nbsp;认</a>
                <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">关闭</a>
            </div>
        </div>
    </div>
</div>

<!-- 计划列表 -->
<div class="modal fade" id="modal-media-type-select" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">选择媒体类型</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-inverse">
                    <div class="panel-body">
                        <div class="form-horizontal">

                            <input type="hidden" class="ad-owner-uuid" value="">

                            <div class="form-group media-type">
                                <label class="col-md-3 control-label">媒体类型:</label>
                                <div class="col-md-6">
                                    <select class="form-control media-type">
                                        <option value="1" selected>微信</option>
                                        <option value="2">微博</option>
                                        <option value="3">直播平台</option>
                                        <option value="4">今日头条</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" data-ad-owner-uuid=""
                   class="btn btn-sm btn-success btn-commit">确&nbsp;&nbsp;认</a>
                <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">关闭</a>
            </div>
        </div>
    </div>
</div>

<!-- 分配销售 -->
<div class="modal fade" id="modal-assign-seller" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">分配销售</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-inverse">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">销售:</label>
                                <div class="col-md-6">
                                    <select class="form-control seller-select">
                                        <option value="" selected>请选择</option>
                                        <option value="">Tony</option>
                                        <option value="">Jack</option>
                                    </select>
                                </div>
                            </div>
                        </div>
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


