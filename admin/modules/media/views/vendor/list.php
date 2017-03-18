<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\Pjax;

$weixinOrderList = Yii::$app->urlManager->createUrl(array('weixin/order/list'));
$getMediaVendorInfoUrl = Yii::$app->urlManager->createUrl(array('media/vendor/get'));
$updateMediaVendorInfoUrl = Yii::$app->urlManager->createUrl(array('media/vendor/update'));
$weixinCreateUrl = Yii::$app->urlManager->createUrl(array('weixin/media/create'));
$deleteMediaVendorInfoUrl = Yii::$app->urlManager->createUrl(array('media/vendor/delete'));

AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

$vendOrorderJs = <<<JS
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
        if(!$('#media-vendor .media-vendor-list').hasClass('active')){
            $('.menu-level-1').each(function(){
                 $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                 $(this).removeClass('active');
            });

            $('#media-vendor.menu-level-1').addClass('active');
            $('#media-vendor.menu-level-1 .menu-level-2.media-vendor-list').addClass('active');
        }

        // 获取媒体主信息
        var contact_list = []; // 联系人
        var form_vendor = $('#modal-media-vendor-info .form-vendor');
        $('.main-stage').on('click', '.table-media-vendor .btn-vendor-info', function(){
            var media_vendor_uuid = $(this).attr('data-vendor-uuid');
            $.ajax({
                url: '$getMediaVendorInfoUrl',
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {vendor_uuid: media_vendor_uuid},
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "获取失败！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }else{
                        var vendor = resp.vendor;
                        form_vendor.find('.vendor-uuid').val(vendor.uuid);
                        form_vendor.find('.name').val(vendor.name);
                        form_vendor.find('.table-contact tbody tr').remove();

                        contact_list = JSON.parse(vendor.contact_info);
                        if(contact_list != null){
                           var one_contact;
                            var contact_person;
                            var contact_phone;
                            var weixin;
                            var qq;
                            var add_time;
                            for(var i = 0; i < contact_list.length; i++){
                                one_contact = contact_list[i];
                                contact_person = one_contact['contact_person'];
                                contact_phone = one_contact['contact_phone'];
                                weixin = one_contact['weixin'];
                                qq = one_contact['qq'];
                                add_time = one_contact['add_time'];
                                form_vendor.find('.table-contact tbody').append('<tr data-time="' + add_time + '"><td>' + contact_person + '</td><td>' + contact_phone + '</td><td>' + weixin + '</td><td>' + qq + '</td><td><a href="javascript:void(0)" class="btn-delete">删除</a></td></tr>');
                            } 
                            
                        }else{
                            contact_list = [];
                        }
                                                   
                        form_vendor.find('.active-end-time').val(vendor.active_end_time);
                        form_vendor.find('.comment').val(vendor.comment);
                        form_vendor.find('.pay_user').val(vendor.pay_user);
                        form_vendor.find('.bank_name').val(vendor.bank_name);
                        form_vendor.find('.bank_account').val(vendor.bank_account);
                        $("input[name=pay_type]").each(function(){
                            if($(this).val() == vendor.pay_type){
                                $(this).attr("checked","checked");
                            }
                        });

                        $('#modal-media-vendor-info').modal('show');
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    return false;
                }
            });
        });

        // 更新媒体主信息
        $('#modal-media-vendor-info .form-vendor').on('click', '.btn-commit', function(){
            var vendor_uuid = form_vendor.find('.vendor-uuid').val();
            var vendor_name = $.trim(form_vendor.find('.name').val());
            var comment = $.trim(form_vendor.find('.comment').val());
            var pay_user = $.trim(form_vendor.find('.pay_user').val());
            var bank_name = $.trim(form_vendor.find('.bank_name').val());
            var bank_account = $.trim(form_vendor.find('.bank_account').val());
            var pay_type = $("input[name=pay_type]:checked").val();

            var active_end_time = $.trim(form_vendor.find('.active-end-time').val());

            if(vendor_name == ''){
                swal({title: "", text: "媒体主名称不能为空", type: "error"});
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
                        closeOnConfirm: true
            },function () {
                    $.ajax({
                            url: '$updateMediaVendorInfoUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {
                                vendor_uuid: vendor_uuid,
                                vendor_name: vendor_name,
                                contact_list: JSON.stringify(contact_list),
                                 active_end_time: active_end_time,
                                 comment: comment,
                                 pay_user:pay_user,
                                 bank_name:bank_name,
                                 bank_account:bank_account,
                                 pay_type:pay_type
                             },
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal({title: "更新失败！", text: "请联系系统管理员", type: "error"});
                                    return false;
                                }else{
                                    //swal({title: "更新成功！", text: "", type: "success"});
                                    $('#modal-media-vendor-info').modal('hide');
                                    $('.main-stage').find('.pjax-area .vendor-form .btn-submit').trigger('click');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                                return false;
                            }
                    });
            });
        });

        //查询计划
        $('.main-stage').on('click', '.vendor-form .btn-submit', function(){
            $(".vendor-form").submit();
        });

        // 订单列表
        $('.main-stage .table-media-vendor').on('click', '.btn-order-list', function(){
            var vendorUUID = $(this).attr('data-vendor-uuid');
            var vendorName = $(this).attr('data-vendor-name');
            $('#modal-media-type-select').find('.vendor-uuid').val(vendorUUID);
            $('#modal-media-type-select').find('.vendor-name').val(vendorName);
            $('#modal-media-type-select').modal('show');
        });

        $('#modal-media-type-select').on('click', '.btn-commit',function() {
            var mediaType = $('#modal-media-type-select').find('.media-type-select option:selected').val();
            var vendorUUID = $('#modal-media-type-select').find('.vendor-uuid').val();
            var vendorName = $('#modal-media-type-select').find('.vendor-name').val();
            var url = '';
            if(mediaType == 1){
                // 微信
                url = '$weixinOrderList' + '&vender-uuid=' + vendorUUID;
            }
            window.open(url);
            $('#modal-media-type-select').modal('hide');
        });

        $('.main-stage').on('click', '.table-media-vendor .btn-add-weixin', function(){
            var vendorUUID = $(this).attr('data-vendor-uuid');
            var url = '$weixinCreateUrl' + '&vender-uuid=' + vendorUUID;
            window.open(url);
        });

        // 添加联系人
        var form_contact = $('#modal-media-vendor-info .form-contact');
        $('#modal-media-vendor-info .form-vendor .add-one-contact').on('click', function(){
            form_contact.show();
            form_contact.find('.form-header').text('添加联系人');
            form_contact.find('.contact-person').val('');
            form_contact.find('.contact-phone').val('');
            form_contact.find('.weixin').val('');
            form_contact.find('.qq').val('');
        });

        // 删除联系人
        $('#modal-media-vendor-info .form-vendor').on('click', '.btn-delete', function(){
            var add_time = $(this).closest('tr').attr('data-time');
            var _contact_list = [];
            for(var i = 0; i < contact_list.length; i++){
                var contact = contact_list[i];
                if(contact['add_time'] == add_time){
                    continue;
                } else {
                    _contact_list.push(contact);
                }
            }
            contact_list = _contact_list;
            $(this).closest('tr').remove();
        });
        
        //保存
        $('#modal-media-vendor-info .btn-save-contact').on('click', function(){
            var add_contact_form = $('#modal-media-vendor-info .form-contact');
            var contact_person = $.trim(add_contact_form.find('.contact-person').val());
            var contact_phone = $.trim(add_contact_form.find('.contact-phone').val());
            var weixin = $.trim(add_contact_form.find('.weixin').val());
            var qq = $.trim(add_contact_form.find('.qq').val());
            if(contact_person == ''){
                swal('', '联系人不能为空', 'error');
                return false;
            }

            if(contact_phone == '' && weixin == '' && qq == ''){
                swal('', '联系电话/微信/QQ, 请至少填写一个', 'error');
                return false;
            }
            var contact_obj = new Object();
            var add_time = new Date().getTime();
            contact_obj['contact_person'] = contact_person;
            contact_obj['contact_phone'] = contact_phone;
            contact_obj['weixin'] = weixin;
            contact_obj['qq'] = qq;
            contact_obj['add_time'] = add_time;
            contact_list.push(contact_obj); // 添加联系人

            add_contact_form.find('.contact-person').val('');
            add_contact_form.find('.contact-phone').val('');
            add_contact_form.find('.weixin').val('');
            add_contact_form.find('.qq').val('');
            add_contact_form.hide();

            $('#modal-media-vendor-info .form-vendor .table-contact tbody').append('<tr data-time="' + add_time + '"><td>' + contact_person + '</td><td>' + contact_phone + '</td><td>' + weixin + '</td><td>' + qq + '</td><td><a href="javascript:void(0)" class="btn-delete">删除</a></td></tr>');
        });

       


        // 有效日期
        $("#modal-media-vendor-info .form-vendor .active-end-time").datetimepicker({
                    language: "zh-CN",
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                    autoclose: true,
                    minView: 2,
                    pickerPosition:'top-right'
        });
JS;
$this->registerJs($vendOrorderJs);

AppAsset::addScript($this, '@web/plugins/moment/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">媒体主管理</a></li>
        <li class="active">媒体主列表</li>
    </ol>

    <h1 class="page-header">媒体主列表</h1>

    <div class="row">
        <div class="col-md-12 main-stage">
            <?php Pjax::begin(['linkSelector'=>false]);
            $Js = <<<JS
        $('.main-stage .vendor-form .order-time-range').daterangepicker({
            'singleDatePicker': false,
            'format': 'YYYY-MM-DD',
            'autoApply': true,
            'opens': 'center',
            'drops': 'down',
            'timePicker': false,
            'timePicker24Hour': false,
            'startDate' : new Date()
        });
        //分页处理样式
        $(".pagination li a").each(function(){
            $(this).removeAttr("href");
            $(this).attr("style","cursor:pointer;");
        });
        //分页处理
        $(".pagination li a").click(function(){
            $(".main-stage .vendor-form input.page").attr("value", $(this).attr("data-page"));
            $(".main-stage .vendor-form").submit();
        });
        
         //删除媒体主
        $(".btn-delete-vendor").click(function(){
            var vendor_uuid = $(this).data("vendor-uuid");
            swal({
                        title: '确认删除该媒体主么？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
            },function () {
                $.ajax({
                        url: '$deleteMediaVendorInfoUrl',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: { vendor_uuid: vendor_uuid},
                        success: function (resp) {
                            if(resp.err_code == 1){
                                swal({title: "删除失败！", text: "请联系系统管理员", type: "error"});
                                return false;
                            }else{
                                 swal({title: "删除成功！", text: "", type: "success",timer:1000});
                                 window.location.reload();
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
                <?= Html::beginForm(['vendor/list'], 'post', ['data-pjax' => '', 'class' => 'vendor-form']); ?>
                <div class="p-t-30 form-search">
                    <div class="row m-l-30 m-r-30">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>媒体主or联系方式</label>
                                <input type="text" name="contact"
                                       value="<?php echo Yii::$app->request->post('contact', ''); ?>"
                                       placeholder="请输入媒体主/注册账号/联系电话/微信/QQ" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-md-2" style="display: none">
                            <div class="form-group">
                                <label>媒介运营</label>
                                <select name="media-executor" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option value="1">Tony</option>
                                    <option value="2">Jack</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="button" class="btn btn-sm btn-primary btn-submit"
                                       value="查&nbsp;&nbsp;询"/>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="page" type="hidden" name="page"
                       value="<?php echo Yii::$app->request->post('page', 0); ?>">
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
                        'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-media-vendor', 'id' => 'fixed-header-data-table'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['data-sort-ignore' => 'true']
                            ],
                            [
                                'header' => '媒体主',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'vendor-name'],
                                'value' => function ($model, $key, $index, $column) {
                                    if (empty($model['vendor_name'])) {
                                        return $model['contact_person'];
                                    }
                                    return $model['vendor_name'];
                                },
                            ],
                            [
                                'header' => '联系方式',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'contact'],
                                'value' => function ($model, $key, $index, $column) {
                                    $contactInfo = '';
                                    if (!empty($model['contact_person'])) {
                                        $contactInfo .= '联系人: ' . $model['contact_person'];
                                    }
                                    if (empty($model['contact1'])) {
                                        $contactInfo .= '<br>' . '电话1: 无';
                                    } else {
                                        $contactInfo .= '<br>' . '电话1: ' . $model['contact1'];
                                    }
                                    if (empty($model['contact2'])) {
                                        $contactInfo .= '<br>' . '电话2: 无';
                                    } else {
                                        $contactInfo .= '<br>' . '电话2: ' . $model['contact2'];
                                    }
                                    if (empty($model['weixin'])) {
                                        $contactInfo .= '<br>' . '微信: 无';
                                    } else {
                                        $contactInfo .= '<br>' . '微信: ' . $model['weixin'];
                                    }
                                    if (empty($model['qq'])) {
                                        $contactInfo .= '<br>' . 'QQ: 无';
                                    } else {
                                        $contactInfo .= '<br>' . 'QQ: ' . $model['qq'];
                                    }
                                    return $contactInfo;
                                },
                            ],
                            [
                                'header' => '注册账号',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'login-account'],
                                'value' => function ($model, $key, $index, $column) {
                                    if(empty($model['login_account']) || $model['login_account'] == -1){
                                        return '无';
                                    } else {
                                        return $model['login_account'];
                                    }
                                },
                            ],
                            [
                                'header' => '入驻',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'login-account'],
                                'value' => function ($model, $key, $index, $column) {
                                    if ($model['register_type'] == \common\models\MediaVendor::REGISTER_TYPE_ADMIN){
                                        return 'admin后台入驻';
                                    } else if ($model['register_type'] == \common\models\MediaVendor::REGISTER_TYPE_SELF) {
                                        return '前台入驻';
                                    } else {
                                        return '未知';
                                    }
                                },
                            ],
                            [
                                'header' => '资源数量',
                                'format' => 'html',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'media-info'],
                                'value' => function ($model, $key, $index, $column) {
                                    return '微信:<a href="'.Yii::$app->urlManager->createUrl(array('weixin/media/list')).'&vendor_uuid='.$model['vendor_uuid'].'">  ' . $model['weixin_media_cnt'] . '</a><br>'
                                    . '微博:<a href="'.Yii::$app->urlManager->createUrl(array('weibo/media/list')).'&vendor_uuid='.$model['vendor_uuid'].'"> ' . $model['weibo_media_cnt'] . ' </a><br>'
                                    . '视频:<a href="'.Yii::$app->urlManager->createUrl(array('video/media/list')).'&vendor_uuid='.$model['vendor_uuid'].'"> ' . $model['video_media_cnt'].'</a>';
                                },
                            ],
                            [
                                'header' => '账户余额',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'balance'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['balance'];
                                },
                            ],
                            [
                                'header' => '可提现金额',
                                'format' => 'text',
                                'headerOptions' => ['data-sort-ignore' => 'true'],
                                'contentOptions' => ['class' => 'withdraw-amount'],
                                'value' => function ($model, $key, $index, $column) {
                                    return $model['withdraw_amount'];
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{vendor-info}<br>{order-list}<br>{add-weixin}<br>{delete-vendor}',
                                'buttons' => [
                                    'vendor-info' => function ($url, $model) {
                                        return Html::button('信息管理', ['class' => 'btn btn-link btn-xs btn-vendor-info', 'data-vendor-uuid' => $model['vendor_uuid']]);
                                    },
                                    'order-list' => function ($url, $model) {
                                        return Html::button('订单列表', ['class' => 'btn btn-link btn-xs btn-order-list', 'data-vendor-uuid' => $model['vendor_uuid'], 'data-vendor-name' => $model['vendor_name']]);
                                    },
                                    'add-weixin' => function ($url, $model) {
                                        return Html::button('添加公众号', ['class' => 'btn btn-link btn-xs btn-add-weixin', 'data-vendor-uuid' => $model['vendor_uuid']]);
                                    },
                                    'delete-vendor' => function ($url, $model) {
                                        return Html::button('删除', ['class' => 'btn btn-link btn-xs btn-delete-vendor', 'data-vendor-uuid' => $model['vendor_uuid']]);
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
            <table class="footable table table-striped toggle-arrow-tiny table-media-vendor" id="header-fixed" style="position: fixed;top: 54px;display: none;margin-left: 15px"><thead>
                <tr><th data-sort-ignore="true">#</th><th data-sort-ignore="true">媒体主</th><th data-sort-ignore="true">联系方式</th><th data-sort-ignore="true">注册账号</th><th data-sort-ignore="true">入驻</th><th data-sort-ignore="true">资源数量</th><th data-sort-ignore="true">账户余额</th><th data-sort-ignore="true">可提现金额</th><th data-sort-ignore="true">操作</th></tr>
                </thead></table>
        </div>
    </div>
</div>

<!-- 媒体主信息 -->
<div class="modal fade" id="modal-media-vendor-info" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog modal-blg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">信息管理</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7" style="border-right: 1px solid #e7eaec;">
                                <div class="form-horizontal form-vendor">
                                    <input type="hidden" class="vendor-uuid" value="">

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">媒体主 *:</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control name" placeholder="请输入公司名称或者主要联系人 (必填)"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">联系人 *: </label>
                                        <div class="col-md-9">
                                            <table class="table table-contact">
                                                <thead>
                                                <tr>
                                                    <th>姓名</th>
                                                    <th>电话</th>
                                                    <th>微信</th>
                                                    <th>QQ</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            <p>
                                                <a href="javascript:;" class="btn btn-primary btn-xs m-r-5 add-one-contact">添加联系人</a>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">报价有效期: </label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control active-end-time"
                                                   placeholder="请填写报价有效期" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">备注: </label>
                                        <div class="col-md-8">
                                            <textarea class="form-control comment" placeholder="请输入备注信息"
                                                      rows="5"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">支付名称: </label>
                                        <div class="col-md-3">
                                            <input type="text" name="pay_user" class="form-control pay_user" placeholder="公司或者个人" value="">
                                        </div>
                                        <label class="col-md-2 control-label">开户行: </label>
                                        <div class="col-md-3">
                                            <input type="text" name="bank_name" class="form-control bank_name" placeholder="银行名字及分行" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">银行账号: </label>
                                        <div class="col-md-3">
                                            <input type="text" name="bank_account" class="form-control bank_account" placeholder="银行账号" value="">
                                        </div>
                                        <label class="col-md-2 control-label">支付类型: </label>
                                        <div class="col-md-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="pay_type" value="1" />银行卡
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pay_type" value="2" />支付宝
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-offset-4">
                                            <button class="btn btn-success btn-lg btn-commit" type="button">确&nbsp;&nbsp;&nbsp;认
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-horizontal form-contact" style="display: none">
                                    <p class="text-left form-header"></p>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">联系人:
                                            <br>
                                            <small class="text-danger">(必填)</small>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control contact-person" placeholder="请输入联系人姓名(必填)" data-parsley-required="true"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">联系电话: </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control contact-phone" placeholder="请输入联系电话"/>
                                            <span class="help-block">联系电话/微信/QQ,请至少填写一个</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">微信: </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control weixin" placeholder="请输入微信"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">QQ: </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control qq" placeholder="请输入QQ"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-offset-3 col-lg-9">
                                            <button class="btn btn-primary btn-save-contact" type="button">保&nbsp;&nbsp;&nbsp;存
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 订单列表 -->
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
                            <input type="hidden" class="vendor-uuid" value="">
                            <input type="hidden" class="vendor-name" value="">
                            <div class="form-group">
                                <label class="col-md-3 control-label">媒体类型:</label>
                                <div class="col-md-6">
                                    <select class="form-control media-type-select">
                                        <option value="1" selected>微信</option>
                                        <option value="2">微博</option>
                                        <option value="3">视频直播</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div data-vendor-uuid="" class="btn btn-sm btn-success btn-commit">确&nbsp;&nbsp;认</div>
                <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">关闭</a>
            </div>
        </div>
    </div>
</div>



