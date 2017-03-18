<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:33 PM
 */
use yii\grid\GridView;
use yii\helpers\Html;
use admin\assets\AppAsset;
use yii\widgets\Pjax;
use common\helpers\MediaHelper;
use common\helpers\PlatformHelper;

$fetchAllMediaExecutorUrl = Yii::$app->urlManager->createUrl(array('media/executor/fetch-list'));
$assignMediaExecutorUrl = Yii::$app->urlManager->createUrl(array('media/executor/assign-one'));
$getWeixinInfoUrl = Yii::$app->urlManager->createUrl(array('weixin/media/get-info'));
$weixinVerifyUrl = Yii::$app->urlManager->createUrl(array('weixin/media/verify'));
$getVendorListOfMediaUrl = Yii::$app->urlManager->createUrl(array('media/vendor/get-list-of-media'));
$getVendorInfoUrl = Yii::$app->urlManager->createUrl(array('weixin/vendor/get-info'));
$verifyVendorUrl = Yii::$app->urlManager->createUrl(array('weixin/vendor/verify'));
$deleteMediaUrl = Yii::$app->urlManager->createUrl(array('weixin/media/delete'));
$weixinPutupUrl = Yii::$app->urlManager->createUrl(array('weixin/media/put-up'));
$vendorSearchUrl = Yii::$app->urlManager->createUrl(array('media/vendor/search'));
$vendorCreateUrl = Yii::$app->urlManager->createUrl(array('media/vendor/create'));
$vendorFetchUrl = Yii::$app->urlManager->createUrl(array('media/vendor/get'));
$addVendorForMediaUrl = Yii::$app->urlManager->createUrl(array('weixin/media/add-vendor'));
$removeVendorForMediaUrl = Yii::$app->urlManager->createUrl(array('weixin/media/remove-vendor'));
$mediaWeixinSetTopUrl = Yii::$app->urlManager->createUrl(array('weixin/media/set-top'));
$mediaWeixinCancelTopUrl = Yii::$app->urlManager->createUrl(array('weixin/media/cancel-top'));
$mediaWeixinSetPushUrl = Yii::$app->urlManager->createUrl(array('weixin/media/set-push'));
$mediaWeixinCancelPushUrl = Yii::$app->urlManager->createUrl(array('weixin/media/cancel-push'));

$mediaTypeCode = MediaHelper::getWeixinInfo()['code'];

$weixinToVerifyJs = <<<JS

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
            if(!$('#weixin .media-manage .verify-succ').hasClass('active')){
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
                $('#weixin.menu-level-1 .menu-level-2.media-manage').addClass('active');
                $('#weixin.menu-level-1 .menu-level-2.media-manage .menu-level-3.verify-succ').addClass('active');
            }

            // ================ 分配媒介 ================
            $('.main-stage').on('click', '.table-media-list .btn-assign-media-executor', function(){
                var mediaUUID = $(this).attr('data-uuid');
                $('#modal-assign-media-executor .media-uuid').val(mediaUUID);
                $.ajax({
                    url: '$fetchAllMediaExecutorUrl',
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    data: {},
                    success: function (resp) {
                        var executorList = resp.executor_list;
                        var selectArea = $('#modal-assign-media-executor .media-executor-select');
                        selectArea.find('option').remove();
                        selectArea.append('<option value="-1">请选择</option>');
                        for(var idx in executorList){
                            selectArea.append('<option value="' + executorList[idx]['account_uuid'] + '">' + executorList[idx]['name'] + '</option>');
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    }
                });
                $('#modal-assign-media-executor').modal('show');
            });
            // 分配媒介运营
            $('#modal-assign-media-executor').on('click', '.btn-commit', function(){
                var executor_uuid = $('#modal-assign-media-executor .media-executor-select option:selected').val();
                var media_uuid = $('#modal-assign-media-executor .media-uuid').val();
                if(executor_uuid == -1){
                    swal('', '请选择媒介运营!', 'error');
                    return false;
                }
                $.ajax({
                    url: '$assignMediaExecutorUrl',
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {media_type: 1, executor_uuid: executor_uuid, media_uuid: media_uuid},
                    success: function (resp) {
                        if(resp.err_code == 0){
                            $('.main-stage .pjax-area .weixin-search-form .btn-submit').trigger('click');
                            //swal('', '分配成功!', 'success');
                            $('#modal-assign-media-executor').modal('hide');
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    }
                });
            });

            // =============================================
            // =============== 修改 - 修改资源  =============
            // =============================================

            // 列表页里的"修改"按钮
            $('.main-stage').on('click', '.table-media-list .btn-to-verify', function(){
                var this_modal = $('#modal-to-verify');
                var uuid = $(this).attr('data-uuid');
                var media_info = this_modal.find('#stage-to-verify-media-content .form-media-info');
                this_modal.find('.media-uuid').val(uuid);

                var nav_item_verify_media = this_modal.find('.nav-to-verify-media').closest('li');
                var nav_item_verify_vendor = this_modal.find('.nav-to-verify-vendor').closest('li');
                this_modal.find('.nav-to-verify-media').trigger('click');

                // 获取媒体基本信息
                $.ajax({
                        url: '$getWeixinInfoUrl',
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        data: {media_uuid: uuid},
                        success: function (resp) {
                                if(resp.err_code == 0){
                                    var weixin = resp.weixin;
                                    media_info.find('.input-public-id').text(weixin.public_id);
                                    media_info.find('.input-public-name').val(weixin.public_name);
                                    media_info.find('.input-follower-num').val(weixin.follower_num);
                                    media_info.find('.account-create-time').text(weixin.create_time);

                                    // 资源分类
                                    media_info.find('.media-cate .one-cate').each(function(){
                                        $(this).removeAttr('checked');
                                    });
                                    if(weixin.media_cate != '' && weixin.media_cate != null){
                                        var cate_list = weixin.media_cate.split('#');
                                        for(var i = 0; i < cate_list.length; i++){
                                            if(cate_list[i] == ''){
                                                continue;
                                            }
                                            media_info.find('.media-cate').find('.cate-' + cate_list[i]).prop('checked', true);
                                        }
                                    }

                                    // 账号地域属性
                                    media_info.find('.follower-area .one-area').each(function(){
                                        $(this).removeAttr('checked');
                                    });
                                    media_info.find('.area-other').removeAttr('checked');
                                    media_info.find('.follower-area-province').hide();
                                    media_info.find('.follower-area-city').hide();
                                    // follower_area 取值示例: #1#45#
                                    if(weixin.follower_area != '' && weixin.follower_area != null){
                                        // 设置所有"账号地域属性"不可选
                                        var area_list_temp = weixin.follower_area.split('#');
                                        var area_list = [];
                                        for(var i = 0; i < area_list_temp.length; i++){
                                            if(area_list_temp[i] == ''){
                                                continue;
                                            }
                                            area_list.push(area_list_temp[i]);
                                        }
                                        var area_out = 0;
                                        for(var i = 0; i < area_list.length; i++){
                                            var oneArea = media_info.find('.follower-area').find('.area-' + area_list[i]);
                                            if(oneArea.length > 0){
                                                // 在热门城市里
                                                area_out = 1;
                                                oneArea.prop('checked', true);
                                                break;
                                            }
                                        }
                                        if(area_out == 0 && area_list.length == 2){
                                            media_info.find('.area-other').prop('checked', true);
                                            media_info.find('.other-area-select .follower-area-province').show();
                                            media_info.find('.other-area-select .follower-area-city').show();

                                            var province = area_list[0];
                                            var city = area_list[1];

                                            media_info.find('.other-area-select .follower-area-province').val(province);
                                            media_info.find('.other-area-select .follower-area-province').trigger('change');
                                            media_info.find('.other-area-select .follower-area-city').val(city);
                                        } else if(area_out == 0 && area_list.length == 1){
                                            media_info.find('.area-other').prop('checked', true);
                                            media_info.find('.other-area-select .follower-area-province').show();
                                            media_info.find('.other-area-select .follower-area-city').show();

                                            var province = area_list[0];
                                            media_info.find('.other-area-select .follower-area-province').val(province);
                                            media_info.find('.other-area-select .follower-area-province').trigger('change');
                                        }
                                    }

                                    // 自媒体类型
                                    media_info.find('.media-weixin-belong-type input.one-type').each(function(){
                                        if($(this).val() == weixin.media_belong_type){
                                            $(this).attr('checked', true);
                                        } else {
                                            $(this).attr("checked", false);
                                        }
                                    });

                                    // 资源审核状态
                                    media_info.find('.status').val(weixin.status);
                                    // 备注
                                    media_info.find('.comment').val(weixin.comment);
                                    media_info.find('.t_comment').val(weixin.t_comment);

                                    this_modal.modal('show');
                                } else {
                                    swal({title: "系统出错", text: "请联系系统管理员", type: "error"});
                                }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错", text: "请联系系统管理员", type: "error"});
                        }
                });
            });

            // 保存资源
            $('#modal-to-verify').on('click', '.btn-commit-media', function(){
                var this_modal = $('#modal-to-verify');
                var media_info = this_modal.find('#stage-to-verify-media-content .form-media-info');
                var uuid = this_modal.find('.media-uuid').val();

                var public_name = $.trim(this_modal.find('.input-public-name').val());
                var public_id = $.trim(this_modal.find('.input-public-id').text());
                var follower_num = $.trim(this_modal.find('.input-follower-num').val());

                if(public_name == ''){
                    swal({title: "", text: "请输入微信公众号名称", type: "error"});
                    return false;
                }

                if(follower_num == ''){
                    follower_num = 0;
                } else if(isNaN(follower_num)){
                    swal({title: "", text: "粉丝数应该为整数", type: "error"});
                    return false;
                }
                follower_num = parseFloat(follower_num); // 单位为个

                var media_cate = '#';
                media_info.find('.media-cate .one-cate').each(function(){
                    if($(this).is(':checked')){
                        media_cate += $(this).val() + '#';
                    }
                });
                if(media_cate == '#'){
                    media_cate = '';
                }

                // 账号地域属性
                var follower_area = '#';
                media_info.find('.follower-area .one-area').each(function(){
                    if($(this).is(':checked')){
                        var province_code = '';
                        var city_code = $(this).val();
                        if(city_code == 289){
                            province_code = '19';
                            follower_area = follower_area + province_code + '#' + city_code + '#';
                        } else if(city_code == 291){
                            province_code = '19';
                            follower_area = follower_area + '19' + '#' + city_code + '#';
                        } else if(city_code == 175){
                            province_code = '11';
                            follower_area = follower_area + '11' + '#' + city_code + '#';
                        } else if(city_code == 275){
                            province_code = '18';
                            follower_area = follower_area + '18' + '#' + city_code + '#';
                        } else if(city_code == 258){
                            province_code = '17';
                            follower_area = follower_area + '17' + '#' + city_code + '#';
                        } else if(city_code == 162){
                            province_code = '10';
                            follower_area = follower_area + '10' + '#' + city_code + '#';
                        } else {
                            follower_area = follower_area + city_code + '#';
                        }
                    }
                });
                if(follower_area == '#'){
                    follower_area = '';
                }
                if(follower_area == '' && media_info.find('.follower-area .area-other').is(':checked')){
                    var province_code = media_info.find('.other-area-select .follower-area-province option:selected').val();
                    var city_code = media_info.find('.other-area-select .follower-area-city option:selected').val();
                    follower_area = '#' + province_code + '#' + city_code + '#';
                }
                var belong_type = "";
                media_info.find('.media-weixin-belong-type .checkbox-inline input').each(function(){
                    if($(this).is(':checked')){
                        belong_type = $(this).val();
                    }
                });

                var status = media_info.find('.status option:selected').val();
                var comment = $.trim(media_info.find('.comment').val());


                if(media_cate == ''){
                    swal({title: "", text: "请选择资源分类", type: "error"});
                    return false;
                }

                if(follower_area == ''){
                    swal({title: "", text: "请选择账号地域属性", type: "error"});
                    return false;
                }

                if(belong_type == ""){
                    swal({title: "", text: "请选择自媒体类型", type: "error"});
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
                            url: '$weixinVerifyUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {uuid: uuid, public_name: public_name, follower_num: follower_num, media_cate: media_cate, follower_area: follower_area, status: status, comment: comment,belong_type:belong_type},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal({title: "保存失败！", text: "请联系系统管理员", type: "error"});
                                    return false;
                                }else{
                                    swal({title: "保存成功！", text: "", type: "success",showConfirmButton: false,timer: 1000});
                                    //this_modal.modal('hide');
                                    //$('.main-stage .pjax-area .weixin-search-form .btn-submit').trigger('click');
                                    $('#modal-to-verify .modal-content .nav-to-verify-vendor').trigger('click');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                                return false;
                            }
                    });
                });
            });

            function getMediaCertInfo(cert_code){
                if(cert_code == 0){
                    return '未知';
                } else if(cert_code == 1){
                    return '已认证';
                } else if(cert_code == 2){
                    return '未认证';
                }
            }
            function getMediaActivatedInfo(is_activated){
                if(is_activated == 0){
                    return '是';
                } else if(is_activated == 1){
                    return '否';
                }
            }
            function getMediaPutupInfo(put_up){
                if(put_up == 0){
                    return '下架';
                } else if(put_up == 1){
                    return '上架';
                }
            }
            function getMediaStatusInfo(status){
                if(status == 0){
                    return '待审核';
                } else if(status == 1){
                    return '已通过';
                } else if(status == 2){
                    return '未通过';
                }
            }

            // 账号地域属性
            var form_media_info = $('#modal-to-verify #stage-to-verify-media-content .form-media-info');
            form_media_info.find('.follower-area .checkbox-inline input').click(function(){
                form_media_info.find(".follower-area .checkbox-inline input").not(this).attr("checked", false);
                form_media_info.find('.follower-area-province').hide();
                form_media_info.find('.follower-area-city').hide();
            });
            form_media_info.find('.other-area-select .follower-area-province').change(function() {
                var provinceCode = $(this).val();
                var followerAreaCitySelect = form_media_info.find('.other-area-select .follower-area-city');
                var cityList = getCityConfigWithProvinceCode(provinceCode);
                followerAreaCitySelect.find('option').remove();
                followerAreaCitySelect.append($('<option value=\"-1\" selected>不限</option>'));
                for(var i = 0; i < cityList.length; i++){
                    var code = cityList[i]['code'];
                    var label = cityList[i]['label'];
                    followerAreaCitySelect.append($('<option value=\"' + code + '\">' + label + '</option>'));
                }
            });
            form_media_info.find('.follower-area .area-other').change(function() {
                if($(this).is(':checked')){
                    form_media_info.find('.follower-area-province').show();
                    form_media_info.find('.follower-area-city').show();
                    form_media_info.find('.other-area-select .follower-area-province').trigger('change');
                }else{
                    form_media_info.find('.follower-area-province').hide();
                    form_media_info.find('.follower-area-city').hide();
                }
            });

            // 自媒体类型选择
            form_media_info.find('.media-weixin-belong-type .checkbox-inline input').click(function(){
                form_media_info.find('.media-weixin-belong-type .checkbox-inline input').not(this).attr("checked", false);
            });

            // =============================================
            // =============== 修改 - 审核媒体主  =============
            // =============================================

            // 点击"审核媒体主"按钮,获取媒体主列表
            $('#modal-to-verify').on('click', '.nav-to-verify-vendor', function(){
                var this_modal = $('#modal-to-verify');

                var media_uuid = $('#modal-to-verify .media-uuid').val();
                var vendor_verify_area = $('#modal-to-verify #stage-to-verify-vendor-content');
                vendor_verify_area.find('.area-vendor-detail').hide();

                $.ajax({
                        url: '$getVendorListOfMediaUrl',
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        data: {media_uuid: media_uuid, media_type: '$mediaTypeCode'},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                var vendorList = resp.vendor_list;
                                var vendorArea = vendor_verify_area.find('.table-vendor tbody');
                                vendorArea.find('tr').remove();

                                for(var i = 0; i < vendorList.length; i++){
                                    var vendor = vendorList[i];
                                    var vendor_uuid = vendor['vendor_uuid'];
                                    var bind_uuid = vendor['media_vendor_bind_uuid'];
                                    var vendor_name = vendor['vendor_name'];
                                    var contact_info = vendor['contact_info'];
                                    var register_type = vendor['register_type'];
                                    var is_activated = vendor['is_activated'];
                                    var bind_status = vendor['bind_status'];
                                    var is_pref_vendor = vendor['is_pref_vendor'];

                                    var activate_label = '';
                                    var bind_status_label = '';
                                    var action_cell = '';
                                    var is_pref_vendor_label = '';
                                    var register_type_label = '';
                                    var contact_info_label = '';
                                    // 媒体主名称
                                    if(vendor_name == ''){
                                        vendor_name = '未填写';
                                    }

                                    //注册渠道
                                    if(register_type == 1){
                                        register_type_label = '前端注册';
                                    } else if(register_type == 2){
                                        register_type_label = 'admin录入';
                                    } else {
                                        register_type_label = '未知';
                                    }

                                    if(contact_info == '' || contact_info == null){
                                        contact_info_label = '未填写';
                                    } else {
                                        var contact_info_arr = JSON.parse(contact_info);
                                        for(var j = 0; j < contact_info_arr.length; j++){
                                            var contact_person = contact_info_arr[j]['contact_person'];
                                            var contact_phone = contact_info_arr[j]['contact_phone'];
                                            var weixin = contact_info_arr[j]['weixin'];
                                            var qq = contact_info_arr[j]['qq'];
                                            contact_info_label += '联系人:' + contact_person + ', 电话:' + contact_phone + ', 微信:' + weixin + ', QQ:' + qq + '<br>';
                                        }
                                    }

                                    // 激活
                                    if(is_activated == 0){
                                        activate_label = '否';
                                    } else if(is_activated == 1){
                                        activate_label = '是';
                                    } else {
                                       activate_label = '未知';
                                    }
                                    // 状态
                                    if(bind_status == 0){
                                        // 待审核
                                        bind_status_label = '<span class="bind-status-label">待审核</span>';
                                        action_cell = '<a href="javascript:;" class="btn btn-primary btn-xs m-r-5 btn-vendor-to-verify" data-uuid="' + bind_uuid + '">审核</a>' +
                                                        '<a href="javascript:;" class="btn btn-white btn-xs m-r-5 btn-vendor-to-delete" data-uuid="' + bind_uuid + '">移除</a>';
                                    } else if(bind_status == 1){
                                        // 审核通过
                                        bind_status_label = '<span class="bind-status-label">已通过</span>';
                                        action_cell = '<a href="javascript:;" class="btn btn-primary btn-xs m-r-5 btn-vendor-to-verify" data-uuid="' + bind_uuid + '">编辑</a>' +
                                                        '<a href="javascript:;" class="btn btn-white btn-xs m-r-5 btn-vendor-to-delete" data-uuid="' + bind_uuid + '">移除</a>';
                                    } else if(bind_status == 2){
                                        // 审核未通过
                                        bind_status_label = '<span class="bind-status-label">未通过</span>';
                                        action_cell = '<a href="javascript:;" class="btn btn-primary btn-xs m-r-5 btn-vendor-to-verify" data-uuid="' + bind_uuid + '">审核</a>' +
                                                        '<a href="javascript:;" class="btn btn-white btn-xs m-r-5 btn-vendor-to-delete" data-uuid="' + bind_uuid + '">移除</a>';
                                    } else if(bind_status == 5){
                                        bind_status_label = '<span class="bind-status-label">未报价</span>';
                                        action_cell = '<a href="javascript:;" class="btn btn-primary btn-xs m-r-5 btn-vendor-to-verify" data-uuid="' + bind_uuid + '">审核</a>' +
                                                        '<a href="javascript:;" class="btn btn-white btn-xs m-r-5 btn-vendor-to-delete" data-uuid="' + bind_uuid + '">移除</a>';
                                    } else {
                                        bind_status_label = '<span class="bind-status-label">未知</span>';
                                        action_cell = '<a href="javascript:;" class="btn btn-primary btn-xs m-r-5 btn-vendor-to-verify" data-uuid="' + bind_uuid + '">审核</a>' +
                                                        '<a href="javascript:;" class="btn btn-white btn-xs m-r-5 btn-vendor-to-delete" data-uuid="' + bind_uuid + '">移除</a>';
                                    }
                                    if(is_pref_vendor == 0) {
                                        is_pref_vendor_label = '否';
                                        var oneLine = '<tr class="table-line" data-is-prefer="' + is_pref_vendor + '" data-uuid="' + bind_uuid + '" data-vendor="' + vendor_uuid + '" id="' + bind_uuid + '"><td>' + (i + 1) + '</td><td>' + vendor_name + '</td><td>' + register_type_label + '</td><td>' + contact_info_label + '</td><td>' + activate_label + '</td><td class="is-pref-vendor-label">' + is_pref_vendor_label + '</td><td>' + bind_status_label + '</td><td>' + action_cell + '</td></tr>';
                                    } else {
                                        is_pref_vendor_label = '是';
                                        var oneLine = '<tr class="table-line" data-is-prefer="' + is_pref_vendor + '" data-uuid="' + bind_uuid + '" data-vendor="' + vendor_uuid + '" id="' + bind_uuid + '" style="color: #ef0a0a"><td>' + (i + 1) + '</td><td>' + vendor_name + '</td><td>' + register_type_label + '</td><td>' + contact_info_label + '</td><td>' + activate_label + '</td><td class="is-pref-vendor-label">' + is_pref_vendor_label + '</td><td>' + bind_status_label + '</td><td>' + action_cell + '</td></tr>';
                                    }
                                    vendorArea.append(oneLine);
                                }
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        }
                });
            });

            // 点击某个媒体主的"修改"按钮
            $('#modal-to-verify #stage-to-verify-vendor-content .table-vendor').on('click', '.btn-vendor-to-verify', function(){
                var vendor_verify_area = $('#modal-to-verify #stage-to-verify-vendor-content');
                // 设置当前行选中
                vendor_verify_area.find('.table-vendor .table-line').each(function(){
                    $(this).removeClass('active');
                });
                $(this).closest('tr').addClass('active');

                var bind_uuid = $(this).closest('tr').attr('data-uuid');
                var vendor_uuid = $(this).closest('tr').attr('data-vendor');

                // 获取媒体主信息
                $.ajax({
                    url: '$getVendorInfoUrl',
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    data: {bind_uuid: bind_uuid},
                    success: function (resp) {
                        if(resp.err_code == 0){
                            var vendor = resp.vendor;
                            var global_serve_percent = resp.global_serve_percent;
                            var global_deposit_percent = resp.global_deposit_percent;

                            var vendorDetailArea = vendor_verify_area.find('.area-vendor-detail');

                            vendorDetailArea.attr('data-bind-uuid', bind_uuid);
                            vendorDetailArea.attr('data-vendor', vendor_uuid);

                            //列表信息开始
                            vendorDetailArea.find('.global-serve-percent').text(global_serve_percent);
                            vendorDetailArea.find('.global-deposit-percent').text(global_deposit_percent);

                            if(vendor['pub_config'] != null && vendor['pub_config'] != ""){
                                var pub_config =  $.parseJSON(vendor['pub_config']);
                                var pos_s_pub_type = pub_config['pos_s']['pub_type'];
                                var pos_m_1_pub_type = pub_config['pos_m_1']['pub_type'];
                                var pos_m_2_pub_type = pub_config['pos_m_2']['pub_type'];
                                var pos_m_3_pub_type = pub_config['pos_m_3']['pub_type'];

                                var s_pub_type_name = getPubTypeName(pos_s_pub_type);
                                var m_1_pub_type_name = getPubTypeName(pos_m_1_pub_type);
                                var m_2_pub_type_name = getPubTypeName(pos_m_2_pub_type);
                                var m_3_pub_type_name = getPubTypeName(pos_m_3_pub_type);

                                // 单图文
                                vendorDetailArea.find('.one-pos.pos-s .pub-type').find('input[type=radio][name=pos-s-pub-type][value=' + pos_s_pub_type + ']').attr('checked', 'checked');
                                vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').val(pub_config['pos_s']['orig_price_min']);
                                vendorDetailArea.find('.one-pos.pos-s .orig-price').attr('data-orig-price-min', pub_config['pos_s']['orig_price_min']);
                                vendorDetailArea.find('.one-pos.pos-s .orig-price').attr('data-orig-price-max', pub_config['pos_s']['orig_price_max']);
                                vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').val(pub_config['pos_s']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-s .retail-price').attr('data-retail-price-min', pub_config['pos_s']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-s .retail-price').attr('data-retail-price-max', pub_config['pos_s']['retail_price_max']);
                                vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').val(pub_config['pos_s']['execute_price']);
                                vendorDetailArea.find('.one-pos.pos-s .execute-price').attr('data-execute-price', pub_config['pos_s']['execute_price']);
                                if(pos_s_pub_type == 0){
                                    // 不接单
                                    vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').val(0);
                                    vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').val(0);
                                    vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').val(0);
                                } else {
                                    vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').prop('disabled', false);
                                    vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').prop('disabled', false);
                                    vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').prop('disabled', false);
                                }

                                // 多图文头条
                                vendorDetailArea.find('.one-pos.pos-m-1 .pub-type').find('input[type=radio][name=pos-m-1-pub-type][value=' + pos_m_1_pub_type + ']').attr('checked', 'checked');
                                vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').val(pub_config['pos_m_1']['orig_price_min']); // 报价
                                vendorDetailArea.find('.one-pos.pos-m-1 .orig-price').attr('data-orig-price-min', pub_config['pos_m_1']['orig_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-1 .orig-price').attr('data-orig-price-max', pub_config['pos_m_1']['orig_price_max']);
                                vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').val(pub_config['pos_m_1']['retail_price_min']); // 零售价
                                vendorDetailArea.find('.one-pos.pos-m-1 .retail-price').attr('data-retail-price-min', pub_config['pos_m_1']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-1 .retail-price').attr('data-retail-price-max', pub_config['pos_m_1']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').val(pub_config['pos_m_1']['execute_price']);
                                vendorDetailArea.find('.one-pos.pos-m-1 .execute-price').attr('data-execute-price', pub_config['pos_m_1']['execute_price']);
                                if(pos_m_1_pub_type == 0){
                                    vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').val(0);
                                    vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').val(0);
                                    vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').val(0);
                                } else {
                                    vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').prop('disabled', false);
                                    vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').prop('disabled', false);
                                    vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').prop('disabled', false);
                                }

                                // 多图文第2条
                                vendorDetailArea.find('.one-pos.pos-m-2 .pub-type').find('input[type=radio][name=pos-m-2-pub-type][value=' + pos_m_2_pub_type + ']').attr('checked', 'checked');
                                vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').val(pub_config['pos_m_2']['orig_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-2 .orig-price').attr('data-orig-price-min', pub_config['pos_m_2']['orig_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-2 .orig-price').attr('data-orig-price-max', pub_config['pos_m_2']['orig_price_max']);
                                vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').val(pub_config['pos_m_2']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-2 .retail-price').attr('data-retail-price-min', pub_config['pos_m_2']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-2 .retail-price').attr('data-retail-price-max', pub_config['pos_m_2']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').val(pub_config['pos_m_2']['execute_price']);
                                vendorDetailArea.find('.one-pos.pos-m-2 .execute-price').attr('data-execute-price', pub_config['pos_m_2']['execute_price']);
                                if(pos_m_2_pub_type == 0){
                                    vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').val(0);
                                    vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').val(0);
                                    vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').val(0);
                                } else {
                                    vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').prop('disabled', false);
                                    vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').prop('disabled', false);
                                    vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').prop('disabled', false);
                                }

                                // 多图文第3-N条
                                vendorDetailArea.find('.one-pos.pos-m-3 .pub-type').find('input[type=radio][name=pos-m-3-pub-type][value=' + pos_m_3_pub_type + ']').attr('checked', 'checked');
                                vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').val(pub_config['pos_m_3']['orig_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-3 .orig-price').attr('data-orig-price-min', pub_config['pos_m_3']['orig_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-3 .orig-price').attr('data-orig-price-max', pub_config['pos_m_3']['orig_price_max']);
                                vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').val(pub_config['pos_m_3']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-3 .retail-price').attr('data-retail-price-min', pub_config['pos_m_3']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-3 .retail-price').attr('data-retail-price-max', pub_config['pos_m_3']['retail_price_min']);
                                vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').val(pub_config['pos_m_3']['execute_price']);
                                vendorDetailArea.find('.one-pos.pos-m-3 .execute-price').attr('data-execute-price', pub_config['pos_m_3']['execute_price']);
                                if(pos_m_3_pub_type == 0){
                                    vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').prop('disabled', true);
                                    vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').val(0);
                                    vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').val(0);
                                    vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').val(0);
                                } else {
                                    vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').prop('disabled', false);
                                    vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').prop('disabled', false);
                                    vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').prop('disabled', false);
                                }
                            } else {
                                vendorDetailArea.find('.one-pos.pos-s .pub-type').find('input[type=radio][name=pos-s-pub-type][value=0]').attr('checked', 'checked');
                                vendorDetailArea.find('.one-pos.pos-m-1 .pub-type').find('input[type=radio][name=pos-m-1-pub-type][value=0]').attr('checked', 'checked');
                                vendorDetailArea.find('.one-pos.pos-m-2 .pub-type').find('input[type=radio][name=pos-m-2-pub-type][value=0]').attr('checked', 'checked');
                                vendorDetailArea.find('.one-pos.pos-m-3 .pub-type').find('input[type=radio][name=pos-m-3-pub-type][value=0]').attr('checked', 'checked');
                                vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').prop('disabled', true);
                                vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').prop('disabled', true);
                            }

                            //技术服务费率
                            if(vendor['serve_percent_config'] != null && vendor['serve_percent_config'] != ""){
                                var serve_percent_config = $.parseJSON(vendor['serve_percent_config']);
                                vendorDetailArea.find('.table-price-set .pos-s .serve-percent-val').val(serve_percent_config['pos_s']);
                                vendorDetailArea.find('.table-price-set .pos-m-1 .serve-percent-val').val(serve_percent_config['pos_m_1']);
                                vendorDetailArea.find('.table-price-set .pos-m-2 .serve-percent-val').val(serve_percent_config['pos_m_2']);
                                vendorDetailArea.find('.table-price-set .pos-m-3 .serve-percent-val').val(serve_percent_config['pos_m_3']);
                            } else {
                                vendorDetailArea.find('.table-price-set .pos-s .serve-percent-val').val(global_serve_percent);
                                vendorDetailArea.find('.table-price-set .pos-m-1 .serve-percent-val').val(global_serve_percent);
                                vendorDetailArea.find('.table-price-set .pos-m-2 .serve-percent-val').val(global_serve_percent);
                                vendorDetailArea.find('.table-price-set .pos-m-3 .serve-percent-val').val(global_serve_percent);
                            }

                            //定金比例
                            if(vendor['deposit_percent_config'] != null && vendor['deposit_percent_config'] != ""){
                                var deposit_percent_config = $.parseJSON(vendor['deposit_percent_config']);
                                vendorDetailArea.find('.table-price-set .pos-s .deposit-percent-val').val(deposit_percent_config['pos_s']);
                                vendorDetailArea.find('.table-price-set .pos-m-1 .deposit-percent-val').val(deposit_percent_config['pos_m_1']);
                                vendorDetailArea.find('.table-price-set .pos-m-2 .deposit-percent-val').val(deposit_percent_config['pos_m_2']);
                                vendorDetailArea.find('.table-price-set .pos-m-3 .deposit-percent-val').val(deposit_percent_config['pos_m_3']);
                            } else {
                                vendorDetailArea.find('.table-price-set .pos-s .deposit-percent-val').val(global_deposit_percent);
                                vendorDetailArea.find('.table-price-set .pos-m-1 .deposit-percent-val').val(global_deposit_percent);
                                vendorDetailArea.find('.table-price-set .pos-m-2 .deposit-percent-val').val(global_deposit_percent);
                                vendorDetailArea.find('.table-price-set .pos-m-3 .deposit-percent-val').val(global_deposit_percent);
                            }

                            var vendorName = vendor['vendor_name'];
                            if(vendorName == ''){
                                vendorName = vendor['contact_person'];
                            }

                            var contact1 = vendor['contact1'];
                            if(contact1 == ''){
                                contact1 = '无';
                            }
                            var contact2 = vendor['contact2'];
                            if(contact2 == ''){
                                contact2 = '无';
                            }

                            //媒体主信息
                            vendorDetailArea.find('.vendor-name').text('媒体主: ' + vendorName);
                            vendorDetailArea.find('.form-vendor .media-ownership').val(vendor['media_ownership']);
                            vendorDetailArea.find('.form-vendor .vendor-name').text(vendorName);
                            if(vendor['active_end_time'] == -1 || vendor['active_end_time'] == ''){
                                vendorDetailArea.find('.form-vendor .active-end-time').val('');
                            } else {
                                vendorDetailArea.find('.form-vendor .active-end-time').val(vendor['active_end_time']);
                            }
                            vendorDetailArea.find('.form-vendor .contact-person').text(vendor['contact_person']);
                            vendorDetailArea.find('.form-vendor .contact-1').text(contact1);
                            vendorDetailArea.find('.form-vendor .contact-2').text(contact2);
                            vendorDetailArea.find('.form-vendor .execute-level').val(vendor['coop_level']);  //配合度

                            vendorDetailArea.find('.form-vendor .vendor-bind-status').val(vendor['bind_status']);
                            vendorDetailArea.find('.form-vendor .pref-vendor').val(vendor['is_pref_vendor']);
                            if(vendor['bind_status'] == 1 && vendor['is_pref_vendor'] == 1){
                                // 审核通过 && 首选
                                vendorDetailArea.find('.form-vendor .vendor-bind-status').attr('disabled', true);
                                vendorDetailArea.find('.form-vendor .pref-vendor').attr('disabled', true);
                            } else {
                                vendorDetailArea.find('.form-vendor .vendor-bind-status').removeAttr('disabled');
                                vendorDetailArea.find('.form-vendor .pref-vendor').removeAttr('disabled');
                                if(vendor['bind_status'] == 1){
                                    // 媒体主审核通过
                                    vendorDetailArea.find('.form-vendor .pref-vendor-select').show();
                                } else {
                                    // 媒体主待审核 or 审核未通过
                                    vendorDetailArea.find('.form-vendor .pref-vendor-select').hide();
                                }
                            }

                            vendorDetailArea.find('.form-vendor .pay-period').val(vendor['pay_period']); //账期
                            vendorDetailArea.show();
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                        swal({title: "系统有误!", text: "请联系系统管理员", type: "error"});
                    }
                });
            });

            $('#modal-to-verify .origin-price-val-min').blur(function(){
                var origin_price_val_min = $.trim($(this).val());
                if(origin_price_val_min == '' || isNaN(origin_price_val_min)){
                    $(this).val('');
                    $(this).closest('.one-pos').find('.retail-price-val-min').val('');
                } else {
                    $(this).closest('.one-pos').find('.retail-price-val-min').val(getDefaultRetailPrice(origin_price_val_min));
                    $(this).closest('.one-pos').find('.execute-price-val').val(origin_price_val_min);
                }
            });
            // 选择"不接单",平台合作价/零售价/执行价 不能填写
            $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail .one-pos.pos-s .pub-type input:radio[name="pos-s-pub-type"]').change(function() {
                var vendorDetailArea = $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail');
                var pub_type = vendorDetailArea.find('.one-pos.pos-s .pub-type input:radio[name="pos-s-pub-type"]:checked').val();
                if(pub_type == 0){
                    vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').val('0');
                    vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').attr('disabled', 'true');

                    vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').val('0');
                    vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').attr('disabled', 'true');

                    vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').val('0');
                    vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').attr('disabled', 'true');
                } else {
                    var origin_price_min = vendorDetailArea.find('.one-pos.pos-s .orig-price').attr('data-orig-price-min');
                    vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').val(origin_price_min);
                    vendorDetailArea.find('.one-pos.pos-s .orig-price .origin-price-val-min').removeAttr('disabled');

                    var retail_price_min = vendorDetailArea.find('.one-pos.pos-s .retail-price').attr('data-retail-price-min');
                    if(!isNaN(retail_price_min) && retail_price_min > 0){
                        vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').val(retail_price_min);
                    } else {
                        vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').val(origin_price_min * globalOrigRetailRatio);
                    }
                    vendorDetailArea.find('.one-pos.pos-s .retail-price .retail-price-val-min').removeAttr('disabled');

                    var execute_price = vendorDetailArea.find('.one-pos.pos-s .execute-price').attr('data-execute-price');
                    if(!isNaN(execute_price) && execute_price > 0){
                        vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').val(execute_price);
                    } else {
                        vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').val('0');
                    }
                    vendorDetailArea.find('.one-pos.pos-s .execute-price .execute-price-val').removeAttr('disabled');
                }
            });
            $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail .one-pos.pos-m-1 .pub-type input:radio[name="pos-m-1-pub-type"]').change(function() {
                var vendorDetailArea = $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail');
                var pub_type = vendorDetailArea.find('.one-pos.pos-m-1 .pub-type input:radio[name="pos-m-1-pub-type"]:checked').val();
                if(pub_type == 0){
                    vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').attr('disabled', 'true');

                    vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').attr('disabled', 'true');

                    vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').attr('disabled', 'true');
                } else {
                    var origin_price_min = vendorDetailArea.find('.one-pos.pos-m-1 .orig-price').attr('data-orig-price-min');
                    vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').val(origin_price_min);
                    vendorDetailArea.find('.one-pos.pos-m-1 .orig-price .origin-price-val-min').removeAttr('disabled');

                    var retail_price_min = vendorDetailArea.find('.one-pos.pos-m-1 .retail-price').attr('data-retail-price-min');
                    if(!isNaN(retail_price_min) && retail_price_min > 0){
                        vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').val(retail_price_min);
                    } else {
                        vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').val(origin_price_min * globalOrigRetailRatio);
                    }
                    vendorDetailArea.find('.one-pos.pos-m-1 .retail-price .retail-price-val-min').removeAttr('disabled');

                    var execute_price = vendorDetailArea.find('.one-pos.pos-m-1 .execute-price').attr('data-execute-price');
                    if(!isNaN(execute_price) && execute_price > 0){
                        vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').val(execute_price);
                    } else {
                        vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').val('0');
                    }
                    vendorDetailArea.find('.one-pos.pos-m-1 .execute-price .execute-price-val').removeAttr('disabled');
                }
            });
            $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail .one-pos.pos-m-2 .pub-type input:radio[name="pos-m-2-pub-type"]').change(function() {
                var vendorDetailArea = $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail');
                var pub_type = vendorDetailArea.find('.one-pos.pos-m-2 .pub-type input:radio[name="pos-m-2-pub-type"]:checked').val();
                if(pub_type == 0){
                    vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').attr('disabled', 'true');

                    vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').attr('disabled', 'true');

                    vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').attr('disabled', 'true');
                } else {
                    var origin_price_min = vendorDetailArea.find('.one-pos.pos-m-2 .orig-price').attr('data-orig-price-min');
                    vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').val(origin_price_min);
                    vendorDetailArea.find('.one-pos.pos-m-2 .orig-price .origin-price-val-min').removeAttr('disabled');

                    var retail_price_min = vendorDetailArea.find('.one-pos.pos-m-2 .retail-price').attr('data-retail-price-min');
                    if(!isNaN(retail_price_min) && retail_price_min > 0){
                        vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').val(retail_price_min);
                    } else {
                        vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').val(origin_price_min * globalOrigRetailRatio);
                    }
                    vendorDetailArea.find('.one-pos.pos-m-2 .retail-price .retail-price-val-min').removeAttr('disabled');

                    var execute_price = vendorDetailArea.find('.one-pos.pos-m-2 .execute-price').attr('data-execute-price');
                    if(!isNaN(execute_price) && execute_price > 0){
                        vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').val(execute_price);
                    } else {
                        vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').val('0');
                    }
                    vendorDetailArea.find('.one-pos.pos-m-2 .execute-price .execute-price-val').removeAttr('disabled');
                }
            });
            $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail .one-pos.pos-m-3 .pub-type input:radio[name="pos-m-3-pub-type"]').change(function() {
                var vendorDetailArea = $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail');
                var pub_type = vendorDetailArea.find('.one-pos.pos-m-3 .pub-type input:radio[name="pos-m-3-pub-type"]:checked').val();
                if(pub_type == 0){
                    vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').attr('disabled', 'true');

                    vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').attr('disabled', 'true');

                    vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').val('0');
                    vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').attr('disabled', 'true');
                } else {
                    var origin_price_min = vendorDetailArea.find('.one-pos.pos-m-3 .orig-price').attr('data-orig-price-min');
                    vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').val(origin_price_min);
                    vendorDetailArea.find('.one-pos.pos-m-3 .orig-price .origin-price-val-min').removeAttr('disabled');

                    var retail_price_min = vendorDetailArea.find('.one-pos.pos-m-3 .retail-price').attr('data-retail-price-min');
                    if(!isNaN(retail_price_min) && retail_price_min > 0){
                        vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').val(retail_price_min);
                    } else {
                        vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').val(origin_price_min * globalOrigRetailRatio);
                    }
                    vendorDetailArea.find('.one-pos.pos-m-3 .retail-price .retail-price-val-min').removeAttr('disabled');

                    var execute_price = vendorDetailArea.find('.one-pos.pos-m-3 .execute-price').attr('data-execute-price');
                    if(!isNaN(execute_price) && execute_price > 0){
                        vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').val(execute_price);
                    } else {
                        vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').val('0');
                    }
                    vendorDetailArea.find('.one-pos.pos-m-3 .execute-price .execute-price-val').removeAttr('disabled');
                }
            });

            // 保存媒体主信息
            $('#modal-to-verify').on('click', '.btn-commit-vendor', function(){
                var vendorDetailArea = $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail');

                var pub_config_obj = new Object();
                var deposit_percent_config_obj = new Object();
                var serve_percent_config_obj = new Object();

                var has_error_of_origin_price = 0;
                var has_error_of_retail_price = 0;
                var has_error_of_coop_price = 0;
                var has_error_of_serve_percent = 0;
                var has_error_of_deposit_percent = 0;

                var pub_type = '';
                var orig_price_val_min = 0;
                var orig_price_val_max = 0;
                var retail_price_val_min = 0;
                var retail_price_val_max = 0;
                var coop_price_val = 0;
                var serve_percent_val = 0;
                var deposit_percent_val = 0;
                var has_origin_pub = 0;
                var has_direct_pub = 0;


                var active_end_time = $.trim(vendorDetailArea.find('.active-end-time').val());

                vendorDetailArea.find('.table-price-set .one-pos').each(function(){
                    if($(this).hasClass('pos-s')){
                        pub_type = $(this).find('.pub-type input:radio[name="pos-s-pub-type"]:checked').val(); // 发布类型
                    } else if($(this).hasClass('pos-m-1')){
                        pub_type = $(this).find('.pub-type input:radio[name="pos-m-1-pub-type"]:checked').val();
                    } else if($(this).hasClass('pos-m-2')){
                        pub_type = $(this).find('.pub-type input:radio[name="pos-m-2-pub-type"]:checked').val();
                    } else if($(this).hasClass('pos-m-3')){
                        pub_type = $(this).find('.pub-type input:radio[name="pos-m-3-pub-type"]:checked').val();
                    }

                    orig_price_val_min = $.trim($(this).find('.orig-price .origin-price-val-min').val()); // 报价min
                    orig_price_val_max = orig_price_val_min; // 报价max
                    retail_price_val_min = $.trim($(this).find('.retail-price .retail-price-val-min').val()); // 零售价min
                    retail_price_val_max = retail_price_val_min; // 零售价max
                    coop_price_val = $.trim($(this).find('.execute-price .execute-price-val').val()); // 执行价

                    // 技术服务费率和定金比例 在下一个版本考虑
                    serve_percent_val = $.trim($(this).find('.serve-percent .serve-percent-val').val()); // 技术服务费率
                    deposit_percent_val = $.trim($(this).find('.deposit-percent .deposit-percent-val').val()); // 定金比例
                    if((pub_type == 1 || pub_type == 2) && isNaN(orig_price_val_min) && orig_price_val_min <= 0){
                        has_error_of_origin_price++;
                    }
                    if((pub_type == 1 || pub_type == 2) && isNaN(retail_price_val_min) && retail_price_val_min <= 0){
                        has_error_of_retail_price++;
                    }
                    if((pub_type == 1 || pub_type == 2) && isNaN(coop_price_val) && coop_price_val <= 0){
                        has_error_of_coop_price++;
                    }
                    if(isNaN(serve_percent_val) || serve_percent_val < 0 || serve_percent_val >= 1){
                        has_error_of_serve_percent++;
                    }
                    if(isNaN(deposit_percent_val) || deposit_percent_val < 0 || deposit_percent_val > 1){
                        has_error_of_deposit_percent++;
                    }

                    if($(this).hasClass('pos-s')){
                        // 单图文
                        pub_config_obj['pos_s'] = {'pub_type': parseInt(pub_type), 'orig_price_min': parseFloat(orig_price_val_min), 'orig_price_max': parseFloat(orig_price_val_max), 'retail_price_min': parseFloat(retail_price_val_min), 'retail_price_max': parseFloat(retail_price_val_max), 'execute_price': parseFloat(coop_price_val), 'active_end_time': active_end_time};
                        deposit_percent_config_obj['pos_s'] = parseFloat(deposit_percent_val);
                        serve_percent_config_obj['pos_s'] = parseFloat(serve_percent_val);
                        if(parseInt(pub_type)== 2){
                            has_origin_pub = 1;
                        }
                        if(parseInt(pub_type)== 1){
                            has_direct_pub = 1;
                        }
                    }
                    if($(this).hasClass('pos-m-1')){
                        // 多图文第1条
                        pub_config_obj['pos_m_1'] = {'pub_type': parseInt(pub_type), 'orig_price_min': parseFloat(orig_price_val_min), 'orig_price_max': parseFloat(orig_price_val_max), 'retail_price_min': parseFloat(retail_price_val_min), 'retail_price_max': parseFloat(retail_price_val_max), 'execute_price': parseFloat(coop_price_val), 'active_end_time': active_end_time};
                        deposit_percent_config_obj['pos_m_1'] = parseFloat(deposit_percent_val);
                        serve_percent_config_obj['pos_m_1'] = parseFloat(serve_percent_val);
                        if(parseInt(pub_type)== 2){
                            has_origin_pub = 1;
                        }
                        if(parseInt(pub_type)== 1){
                            has_direct_pub = 1;
                        }
                    }
                    if($(this).hasClass('pos-m-2')){
                        // 多图文第2条
                        pub_config_obj['pos_m_2'] = {'pub_type': parseInt(pub_type), 'orig_price_min': parseFloat(orig_price_val_min), 'orig_price_max': parseFloat(orig_price_val_max), 'retail_price_min': parseFloat(retail_price_val_min), 'retail_price_max': parseFloat(retail_price_val_max), 'execute_price': parseFloat(coop_price_val), 'active_end_time': active_end_time};
                        deposit_percent_config_obj['pos_m_2'] = parseFloat(deposit_percent_val);
                        serve_percent_config_obj['pos_m_2'] = parseFloat(serve_percent_val);
                        if(parseInt(pub_type)== 2){
                            has_origin_pub = 1;
                        }
                        if(parseInt(pub_type)== 1){
                            has_direct_pub = 1;
                        }
                    }
                    if($(this).hasClass('pos-m-3')){
                        // 多图文第3-n条
                        pub_config_obj['pos_m_3'] = {'pub_type': parseInt(pub_type), 'orig_price_min': parseFloat(orig_price_val_min), 'orig_price_max': parseFloat(orig_price_val_max), 'retail_price_min': parseFloat(retail_price_val_min), 'retail_price_max': parseFloat(retail_price_val_max), 'execute_price': parseFloat(coop_price_val), 'active_end_time': active_end_time};
                        deposit_percent_config_obj['pos_m_3'] = parseFloat(deposit_percent_val);
                        serve_percent_config_obj['pos_m_3'] = parseFloat(serve_percent_val);
                        if(parseInt(pub_type)== 2){
                            has_origin_pub = 1;
                        }
                        if(parseInt(pub_type)== 1){
                            has_direct_pub = 1;
                        }
                    }
                });

                if(has_error_of_retail_price > 0){
                    swal('', '零售价填写有问题,请检查!', 'error');
                    return false;
                }
                if(has_error_of_coop_price > 0){
                    swal('', '平台合作价填写有问题,请检查!', 'error');
                    return false;
                }
                if(active_end_time == ''){
                    swal('', '报价有效日期不能为空!', 'error');
                    return false;
                }
                //if(has_error_of_serve_percent > 0){
                //    swal('', '技术服务费率填写有问题,请检查!', 'error');
                //    return false;
                //}
                //if(has_error_of_deposit_percent > 0){
                //    swal('', '定金比例填写有问题,请检查!', 'error');
                //    return false;
                //}

                //console.log(pub_config_obj);
                //console.log(deposit_percent_config_obj);
                //console.log(serve_percent_config_obj);

                var bind_uuid = vendorDetailArea.attr('data-bind-uuid');
                var vendor_bind_status = vendorDetailArea.find('.form-vendor .vendor-bind-status').val();
                var pay_period = vendorDetailArea.find('.form-vendor .pay-period').val();
                var coop_level = vendorDetailArea.find('.form-vendor .execute-level').val();
                var is_pref_vendor = 0;
                if(vendor_bind_status == 0 || vendor_bind_status == 2){
                    // 待审核 || 未通过
                    is_pref_vendor = 0;
                } else {
                    is_pref_vendor = vendorDetailArea.find('.form-vendor .pref-vendor').val();
                }
                var media_ownership = vendorDetailArea.find('.form-vendor .media-ownership').val();

                var pub_config = JSON.stringify(pub_config_obj);
                var deposit_percent = JSON.stringify(deposit_percent_config_obj);
                var serve_percent = JSON.stringify(serve_percent_config_obj);
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
                            url: '$verifyVendorUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {  'bind_uuid': bind_uuid,
                                     'pub_config': pub_config,
                                     'active_end_time': active_end_time,
                                     'deposit_percent_config': deposit_percent,
                                     'serve_percent_config': serve_percent,
                                     'vendor_bind_status': vendor_bind_status,
                                     'pay_period': pay_period,
                                     'coop_level': coop_level,
                                     'is_pref_vendor': is_pref_vendor,
                                     'has_origin_pub': has_origin_pub,
                                     'has_direct_pub': has_direct_pub,
                                     'media_ownership': media_ownership},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal('', '保存失败！', 'error');
                                }else{
                                    //swal('', '保存成功!', 'success');
                                    vendorDetailArea.hide();

                                    var bind_status_label = '';
                                    var is_pref_vendor_label = '';
                                    if (vendor_bind_status == 0) {
                                        bind_status_label = '待审核';
                                        is_pref_vendor_label = '否';
                                    } else if (vendor_bind_status == 1) {
                                        bind_status_label = '已通过';
                                    } else if (vendor_bind_status == 2) {
                                        bind_status_label = '未通过';
                                        is_pref_vendor_label = '否';
                                    }

                                    $('#modal-to-verify .table-vendor .table-line').each(function(){
                                        $(this).css('color', '#707478');
                                    });

                                    if(is_pref_vendor_label == ''){
                                        if (is_pref_vendor == 0){
                                           is_pref_vendor_label = '否';
                                        } else {
                                            is_pref_vendor_label = '是';

                                            $('#modal-to-verify #stage-to-verify-vendor-content .table-vendor').find('#' + bind_uuid).css('color', '#ef0a0a');

                                            // 其他行设置为否
                                            $('#modal-to-verify .table-vendor .table-line').each(function(){
                                                $(this).find('.is-pref-vendor-label').text('否');
                                            });
                                        }
                                    }
                                    $('#modal-to-verify #stage-to-verify-vendor-content .table-vendor').find('#' + bind_uuid).find('.is-pref-vendor-label').text(is_pref_vendor_label);
                                    $('#modal-to-verify #stage-to-verify-vendor-content .table-vendor').find('#' + bind_uuid).find('.bind-status-label').text(bind_status_label);

                                    // 首选媒体主
                                    if(is_pref_vendor == 1){
                                        $('#modal-to-verify #stage-to-verify-vendor-content .table-vendor .table-line').attr('data-is-prefer', 0);
                                        $('#modal-to-verify #stage-to-verify-vendor-content .table-vendor').find('#' + bind_uuid).attr('data-is-prefer', 1);
                                    }

                                    $('#modal-to-verify').modal('hide');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                    swal('', '系统出错！', 'error');
                                    return false;
                            }
                    });
                });
            });
            $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail .vendor-bind-status').change(function() {
                var status = $(this).val();
                if(status == 0 || status == 2){
                    // 待审核 or 审核不通过
                    $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail .pref-vendor-select').hide();
                } else if(status == 1){
                    // 审核通过
                    $('#modal-to-verify #stage-to-verify-vendor-content .area-vendor-detail .pref-vendor-select').show();
                }
            });

            // 获取发布类型名称
            function getPubTypeName(pub_type){
                //0 不接单 1 纯发布 2 原创+发布
                if(pub_type == 0){
                    return '不接单';
                } else if(pub_type == 1){
                    return '纯发布';
                } else if(pub_type == 2){
                    return '原创+发布';
                } else {
                    return '未知';
                }
            }

            $('#modal-to-verify #stage-to-verify-vendor-content').on('click', '.table-vendor .btn-vendor-to-delete', function(){
                var is_pref_vendor = $(this).closest('tr.table-line').attr('data-is-prefer');
                var this_line = $(this).closest('tr.table-line');
                if(is_pref_vendor == 1){
                    swal('操作失败!', '注:首选媒体主不能移除,只有该账号其他媒体主设置为首选后,该媒体主才能移除', 'error');
                    return false;
                }
                var bind_uuid = $(this).closest('tr').attr('data-uuid');
                swal({
                        title: '确认移除该媒体主么？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                },function () {
                    $.ajax({
                        url: '$removeVendorForMediaUrl',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {bind_uuid: bind_uuid},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                //swal('', '移除成功!', 'success');
                                this_line.remove(); // 移除该行
                            } else if(resp.err_code == 1){
                                swal('', '移除失败!', 'error');
                                return false;
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "", text: "系统出错！", type: "error"});
                        }
                    });
                });
            });

            // =============== 添加媒体主 ===============
            $('#modal-to-verify #stage-to-verify-vendor-content .btn-to-add-vendor').on('click', function(){
                $('#modal-to-verify .nav-to-add-vendor').trigger('click');
            });
            // 添加媒体主快捷按钮
            var area_add_vendor = $('#modal-to-verify #stage-to-add-vendor');
            var media_weixin_uuid = "";
            $('#modal-to-verify .nav-to-add-vendor').click(function(){
              media_weixin_uuid = $("#modal-to-verify .modal-body .media-uuid").val();
              area_add_vendor.find(".vendor-search-result").html("");
                   $.ajax({
                    url: '$vendorSearchUrl',
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {vendor_search: ""},
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "系统出错", text: "请联系系统管理员", type: "error"});
                            return false;
                        }else{
                            var vendor_list = resp.vendor_list;
                            if(vendor_list.length == 0){
                                area_add_vendor.find('.error-msg').show();
                                return false;
                            }else{
                                area_add_vendor.find('.error-msg').hide();
                                for(var i = 0; i < vendor_list.length; i++){
                                    var vendor_uuid = vendor_list[i].vendor_uuid;
                                    var vendor_name = vendor_list[i].vendor_name;
                                    var register_type = vendor_list[i].register_type;
                                    var comment = vendor_list[i].comment;
                                    // 注册渠道
                                    if(register_type == 1){
                                         register_type_label = '前端注册';
                                    } else if(register_type == 2){
                                         register_type_label = 'admin录入';
                                    } else {
                                         register_type_label = '未知';
                                    }

                                    // 联系人
                                    var contact_info_label = '';
                                    var contact_info = vendor_list[i].contact_info;
                                    if(contact_info == '' || contact_info == null){
                                        contact_info_label = '未填写';
                                    } else {
                                        var contact_info_arr = JSON.parse(contact_info);
                                        for(var j = 0; j < contact_info_arr.length; j++){
                                            var contact_person = contact_info_arr[j]['contact_person'];
                                            var contact_phone = contact_info_arr[j]['contact_phone'];
                                            var weixin = contact_info_arr[j]['weixin'];
                                            var qq = contact_info_arr[j]['qq'];
                                            contact_info_label += '联系人:' + contact_person + ', 电话:' + contact_phone + ', 微信:' + weixin + ', QQ:' + qq + '<br>';
                                        }
                                    }
                                    var one_vendor =  "<tr data-vendor='" + vendor_uuid + "'>"+
                                                "<td>" + vendor_name + "</td>"+
                                                "<td>" + register_type_label + "</td>"+
                                                "<td>" + contact_info_label + "</td>"+
                                                "<td>" + comment + "</td>"+
                                                "<td><input type='checkbox' class='vendor-select'></td>"+
                                                "</tr>"
                                    area_add_vendor.find(".vendor-search-result").append(one_vendor);
                                }
                                area_add_vendor.find('.btn-add-vendor').show();
                            }
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown){
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                });
            });

            //enter键搜索
            area_add_vendor.find("input[name='search_vendor_name']").keydown(function(event){
                if(event.keyCode==13){
                    area_add_vendor.find(".btn-vendor-search").click();
                    return false;
                }
            });
            // 添加媒体主 - 搜索
            area_add_vendor.find('.btn-vendor-search').click(function(){
                var vendor_search = area_add_vendor.find("input[name=search_vendor_name]").val();
                if(vendor_search ==""){
                    swal({title: "", text: "查询内容不能为空", type: "error"});
                    return false;
                }
                area_add_vendor.find('.vendor-search-result').html('');
                $.ajax({
                    url: '$vendorSearchUrl',
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {vendor_search: vendor_search},
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "系统出错", text: "请联系系统管理员", type: "error"});
                            return false;
                        }else{
                            var vendor_list = resp.vendor_list;
                            if(vendor_list.length == 0){
                                area_add_vendor.find('.error-msg').show();
                                return false;
                            }else{
                                area_add_vendor.find('.error-msg').hide();
                                for(var i = 0; i < vendor_list.length; i++){
                                    var vendor_uuid = vendor_list[i].vendor_uuid;
                                    var vendor_name = vendor_list[i].vendor_name;
                                    var register_type = vendor_list[i].register_type;
                                    var comment = vendor_list[i].comment;
                                    // 注册渠道
                                    if(register_type == 1){
                                         register_type_label = '前端注册';
                                    } else if(register_type == 2){
                                         register_type_label = 'admin录入';
                                    } else {
                                         register_type_label = '未知';
                                    }

                                    // 联系人
                                    var contact_info_label = '';
                                    var contact_info = vendor_list[i].contact_info;
                                    if(contact_info == '' || contact_info == null){
                                        contact_info_label = '未填写';
                                    } else {
                                        var contact_info_arr = JSON.parse(contact_info);
                                        for(var j = 0; j < contact_info_arr.length; j++){
                                            var contact_person = contact_info_arr[j]['contact_person'];
                                            var contact_phone = contact_info_arr[j]['contact_phone'];
                                            var weixin = contact_info_arr[j]['weixin'];
                                            var qq = contact_info_arr[j]['qq'];
                                            contact_info_label += '联系人:' + contact_person + ', 电话:' + contact_phone + ', 微信:' + weixin + ', QQ:' + qq + '<br>';
                                        }
                                    }
                                    var one_vendor =  "<tr data-vendor='" + vendor_uuid + "'>"+
                                                "<td>" + vendor_name + "</td>"+
                                                "<td>" + register_type_label + "</td>"+
                                                "<td>" + contact_info_label + "</td>"+
                                                "<td>" + comment + "</td>"+
                                                "<td><input type='checkbox' class='vendor-select'></td>"+
                                                "</tr>"
                                    area_add_vendor.find(".vendor-search-result").append(one_vendor);
                                }
                                area_add_vendor.find('.btn-add-vendor').show();
                            }
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown){
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                });
            });

            // 添加媒体主 - 保存
            area_add_vendor.find('.btn-add-vendor').on('click', function(){
                var media_uuid = $('#modal-to-verify .media-uuid').val();
                var vendor_uuid = '';
                var has_select_vendor = 0;
                area_add_vendor.find(".vendor-select").each(function(){
                    if($(this).is(':checked')){
                        vendor_uuid = $(this).closest('tr').attr('data-vendor');
                        has_select_vendor = 1;
                    }
                });

                if(has_select_vendor == 0){
                    swal({title: "", text: "请选择媒体主", type: "error"});
                    return false;
                }

                if(media_uuid == '' || vendor_uuid == ''){
                    swal({title: "", text: "系统出错", type: "error"});
                    return false;
                }

                swal({
                        title: '',
                        text: '确认添加该媒体主么？',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                },function () {
                    $.ajax({
                        url: '$addVendorForMediaUrl',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {media_uuid: media_uuid, vendor_uuid: vendor_uuid},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                //swal('添加成功', '', 'success');
                                $('#modal-to-verify .nav-to-verify-vendor').trigger('click');
                            } else if(resp.err_code == 1){
                                swal('添加失败', '该媒体主已经添加!', 'error');
                                return false;
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "", text: "系统出错！", type: "error"});
                        }
                    });
                });
            });
            // 添加媒体主 - 新建媒体主快捷按钮
            area_add_vendor.find(".btn-create-vendor").click(function(){
                window.open('$vendorCreateUrl');
            });

            // 添加媒体主 - 选择媒体主的复选框
            area_add_vendor.on('click', '.vendor-select', function() {
                if($(this).is(':checked')){
                    area_add_vendor.find('.vendor-search-result tr').hide();
                    $(this).closest('tr').show();
                } else {
                    area_add_vendor.find('.vendor-search-result tr').show();
                }
                area_add_vendor.find(".vendor-select").not(this).attr("checked", false);
            });

            // ================ 删除 ================
            // 点击"删除"按钮
            $('.main-stage').on('click', '.table-media-list .btn-to-delete', function(){
                var uuid = $(this).attr('data-uuid');
                swal({
                        title: '确认删除么？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                },function () {
                    $.ajax({
                        url: '$deleteMediaUrl',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {media_uuid: uuid},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                //swal('', '删除成功!', 'success');
                                $('.main-stage .pjax-area .weixin-search-form .btn-submit').trigger('click');
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "", text: "系统出错！", type: "error"});
                        }
                    });
                });
            });

            // 有效日期
            $("#modal-to-verify .form-vendor .active-end-time").datetimepicker({
                language: "zh-CN",
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                minView: 2,
                pickerPosition:'top-right'
            });


            // ================ 上下架 ================
            // 点击"上下架"按钮
            $('.main-stage').on('click', '.table-media-list .btn-put-up', function(){
                var uuid = $(this).attr('data-uuid');
                var is_put = $(this).attr('data-put');
                $.ajax({
                        url: '$getWeixinInfoUrl',
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        data: {media_uuid: uuid},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                var weixin = resp.weixin;
                                var media_status = weixin.status;
                                var has_pref_vendor = weixin.has_pref_vendor;
                                if(media_status == 1 && has_pref_vendor == 1){
                                    // 设置可以上下架
                                    $('#modal-put-up .put-up-warning').hide();
                                    $('#modal-put-up .put-up-set-form').show();
                                    $('#modal-put-up .btn-commit').show();
                                } else {
                                    // 不能设置上下架
                                    $('#modal-put-up .put-up-warning').empty();
                                    var append_msg = '只有 <strong>资源审核通过</strong> 且 <strong>设有首选媒体主</strong> 之后,该资源才能设置上下架.';
                                    $('#modal-put-up .put-up-set-form').hide();
                                    append_msg += '<br>目前:';
                                    if(media_status == 0){
                                        append_msg += '<br>资源未审核通过';
                                    }
                                    if(has_pref_vendor == 0){
                                        append_msg += '<br>未设置首选媒体主';
                                    }
                                    $('#modal-put-up .put-up-warning').append(append_msg);
                                    $('#modal-put-up .put-up-warning').show();
                                    $('#modal-put-up .btn-commit').hide();
                                }
                                //TODO
                                if(weixin.put_up == 1){
                                    $('#modal-put-up input:radio[name="put-up"]').eq(0).attr('checked', 'checked');
                                } else {
                                    $('#modal-put-up input:radio[name="put-up"]').eq(1).attr('checked', 'checked');
                                }
                                if(weixin.in_wom_rank == 1){
                                    $('#modal-put-up input:radio[name="in-wom-rank"]').eq(0).attr('checked', 'checked');
                                } else {
                                    $('#modal-put-up input:radio[name="in-wom-rank"]').eq(1).attr('checked', 'checked');
                                }
                                $('#modal-put-up .media-uuid').val(weixin.uuid);
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "", text: "系统出错！", type: "error"});
                        }
                });
                $('#modal-put-up').modal('show');
            });
            // 保存上下架设置
            $('#modal-put-up').on('click', '.btn-commit', function(){
                var media_uuid = $('#modal-put-up .media-uuid').val();
                var put_up = $('#modal-put-up input:radio[name="put-up"]:checked').val();
                var in_wom_rank = $('#modal-put-up input:radio[name="in-wom-rank"]:checked').val();
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
                            url: '$weixinPutupUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {'uuid': media_uuid, 'put_up': put_up, 'in_wom_rank': in_wom_rank},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal('', '保存失败！', 'error');
                                }else{
                                    //swal('', '保存成功!', 'success');
                                    $('#modal-put-up').modal('hide');
                                    $('.main-stage .pjax-area .weixin-search-form .btn-submit').trigger('click');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                    swal('', '系统出错！', 'error');
                                    return false;
                            }
                    });
                });
            });
            // 下架按钮控制
            $('#modal-put-up input:radio[name="put-up"]').change(function() {
                var put_up = $('#modal-put-up input:radio[name="put-up"]:checked').val();
                if(put_up == 0){
                    $('#modal-put-up input:radio[name="in-wom-rank"]').attr('disabled', 'true');
                    $('#modal-put-up input:radio[name="in-wom-rank"]').eq(1).attr('checked', 'checked');
                }else{
                    $('#modal-put-up input:radio[name="in-wom-rank"]').removeAttr('disabled');
                    $('#modal-put-up input:radio[name="in-wom-rank"]').eq(0).attr('checked', 'checked');
                }
            });

            // 置顶
            $('.main-stage').on('click', '.table-media-list .btn-set-top', function(){
                var media_uuid = $(this).attr('data-uuid');
                swal({
                        title: '确认置顶吗？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                },function () {
                    $.ajax({
                            url: '$mediaWeixinSetTopUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {media_uuid: media_uuid},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal('设置失败！', '请联系系统管理员', 'error');
                                }else{
                                    $('.main-stage .pjax-area .weixin-search-form .btn-submit').trigger('click');
                                    //swal('设置成功!', '', 'success');

                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                    swal('', '系统出错！', 'error');
                                    return false;
                            }
                    });
                });
            });

            // 取消置顶
            $('.main-stage').on('click', '.table-media-list .btn-cancel-top', function(){
                var media_uuid = $(this).attr('data-uuid');
                swal({
                        title: '确认取消置顶吗？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                },function () {
                    $.ajax({
                            url: '$mediaWeixinCancelTopUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {media_uuid: media_uuid},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal('设置失败！', '请联系系统管理员', 'error');
                                }else{
                                    $('.main-stage .pjax-area .weixin-search-form .btn-submit').trigger('click');
                                    //swal('设置成功!', '', 'success');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                    swal('', '取消出错！', 'error');
                                    return false;
                            }
                    });
                });
            });

            // 主推
            $('.main-stage').on('click', '.table-media-list .btn-set-push', function(){
                var media_uuid = $(this).attr('data-uuid');
                swal({
                        title: '确认主推吗？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                },function () {
                    $.ajax({
                            url: '$mediaWeixinSetPushUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {media_uuid: media_uuid},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal('设置失败！', '请联系系统管理员', 'error');
                                }else{
                                    $('.main-stage .pjax-area .weixin-search-form .btn-submit').trigger('click');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                    swal('', '系统出错！', 'error');
                                    return false;
                            }
                    });
                });
            });

            // 取消主推
            $('.main-stage').on('click', '.table-media-list .btn-cancel-push', function(){
                var media_uuid = $(this).attr('data-uuid');
                swal({
                        title: '确认取消置顶吗？',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonText: '取消',
                        closeOnConfirm: true
                },function () {
                    $.ajax({
                            url: '$mediaWeixinCancelPushUrl',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {media_uuid: media_uuid},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal('设置失败！', '请联系系统管理员', 'error');
                                }else{
                                    $('.main-stage .pjax-area .weixin-search-form .btn-submit').trigger('click');
                                    //swal('设置成功!', '', 'success');
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                    swal('', '取消出错！', 'error');
                                    return false;
                            }
                    });
                });
            });


            // 同步价格有效期
            $('#modal-to-verify .area-vendor-detail .sync-latest-active-end-time').on('click', function(){
                var vendor_verify_area = $('#modal-to-verify #stage-to-verify-vendor-content');
                var vendorDetailArea = vendor_verify_area.find('.area-vendor-detail');
                var vendor_uuid = vendorDetailArea.attr('data-vendor');
                $.ajax({
                        url: '$vendorFetchUrl',
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        data: {vendor_uuid: vendor_uuid},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                var vendor = resp.vendor;
                                if(vendor['active_end_time'] != ''){
                                    $('#modal-to-verify .area-vendor-detail .active-end-time').val(vendor['active_end_time']);
                                } else {
                                    swal({title: "", text: "该媒体主未设置报价有效期", type: "error"});
                                    return false;
                                }
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "", text: "系统出错！", type: "error"});
                        }
                });
            });
JS;

$this->registerJs($weixinToVerifyJs);

AppAsset::addScript($this, '@web/js/helpers/default-retail-price.js');
AppAsset::addScript($this, '@web/js/helpers/base-helper.js');
AppAsset::addScript($this, '@web/js/helpers/number-helper.js');

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

AppAsset::addScript($this, '@web/plugins/moment/moment.min.js');

AppAsset::addScript($this, '@web/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
//AppAsset::addScript($this, '@web/plugins/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js');

AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');

//AppAsset::addScript($this, '@web/plugins/DataTables/media/js/jquery.dataTables.min.js');
//AppAsset::addScript($this, '@web/plugins/DataTables/media/js/dataTables.bootstrap.min.js');
//AppAsset::addScript($this, '@web/plugins/DataTables/extensions/FixedHeader/js/dataTables.fixedHeader.min.js');
//AppAsset::addScript($this, '@web/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js');
///AppAsset::addScript($this, '@web/js/table-manage-fixed-header.demo.min.js');
?>

<style>
    #modal-to-verify .area-vendor-detail .table-price-set input {
        max-width: 80px;
    }

    .table-line.active {
        background: #f0f3f5;
    }

    .table-search-vendor-list .table > thead > tr > th, .table-search-vendor-list .table > tbody > tr > th, .table-search-vendor-list .table > tfoot > tr > th, .table-search-vendor-list .table > thead > tr > td, .table-search-vendor-list .table > tbody > tr > td, .table-search-vendor-list .table > tfoot > tr > td{
        max-width: 80px;
        word-break: break-all;
    }
</style>

<div id="content" class="content">

    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">资源管理</a></li>
        <li class="active">审核通过</li>
    </ol>

    <h1 class="page-header">审核通过</h1>

    <div class="row">
        <div class="col-md-12 main-stage">
            <?php Pjax::begin();
            $Js = <<<JS

            //分页处理样式
            $(".main-stage .pagination li a").each(function(){
                $(this).removeAttr("href");
                $(this).attr("style","cursor:pointer;");
            });
            //分页处理
            $(".main-stage .pagination li a").click(function(){
                $(".weixin-search-form input.page").attr("value", $(this).attr("data-page"));
                $(".weixin-search-form").submit();
            });

            // ================= 搜索 =================
            // 查询计划
            $('.main-stage .weixin-search-form').on('click', '.btn-submit', function(){
                var followerCntMin = $.trim($(".weixin-search-form input[name='follower-cnt-min']").val());
                var followerCntMax = $.trim($(".weixin-search-form input[name='follower-cnt-max']").val());
                if(followerCntMin == ''){
                      followerCntMin = 0;
                }
                if(followerCntMax == ''){
                      followerCntMax = 0;
                }
                 if(isNaN(followerCntMin) || isNaN(followerCntMax)){
                      swal('', '粉丝数区间需要填写数字!', 'error');
                      return false;
                 }
                 followerCntMin = parseInt(followerCntMin);
                 followerCntMax = parseInt(followerCntMax);
                 if(followerCntMin > followerCntMax){
                      swal('', '粉丝数区间填写有误!', 'error');
                      return false;
                 }
                 $('.main-stage .weixin-search-form').submit();
            });

            // 价格有效日期
            $('.pjax-area .weixin-search-form .active-end-time-range').daterangepicker({
                'singleDatePicker': false,
                'format': 'YYYY-MM-DD',
                'autoApply': true,
                'opens': 'center',
                'drops': 'down',
                'timePicker': false,
                'timePicker24Hour': false,
                'startDate' : new Date()
            });

JS;
            $this->registerJs($Js);
            ?>
            <div class="panel panel-inverse pjax-area">
                <?= Html::beginForm(['media/verify-succ-list'], 'post', ['data-pjax' => '', 'class' => 'weixin-search-form']); ?>
                <div class="p-t-30">
                    <div class="row m-l-30">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>公众号</label>
                                <input type="text" name="account"
                                       value="<?php echo Yii::$app->request->post('account', ''); ?>"
                                       placeholder="请输入公众号名称或ID" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>资源分类</label>
                                <select name="media-cate" class="form-control input-sm">
                                    <option
                                        value="-1" <?php echo Yii::$app->request->post('media-cate', -1) == -1 ? 'selected' : '' ?>>
                                        不限
                                    </option>
                                    <?php
                                    $mediaCateList = MediaHelper::getMediaCateList();
                                    foreach ($mediaCateList as $code => $cate) {
                                        ?>
                                        <option
                                            value="<?php echo $code; ?>" <?php echo Yii::$app->request->post('media-cate', -1) == $code ? 'selected' : '' ?>><?php echo $cate ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>价格（多图文头条）</label>
                                <div class="input-group">
                                    <input type="text"
                                           value="<?php echo Yii::$app->request->post('price-cnt-min', ''); ?>"
                                           class="form-control input-sm" name="price-cnt-min" placeholder="">
                                    <span class="input-group-addon"> - </span>
                                    <input type="text"
                                           value="<?php echo Yii::$app->request->post('price-cnt-max', ''); ?>"
                                           class="form-control input-sm" name="price-cnt-max" placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2" style="display: none">
                            <div class="form-group">
                                <label>媒介运营</label>
                                <select name="media-executor" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option value="2">jack</option>
                                    <option value="3">tony</option>
                                    <option value="4">hellen</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>上下架</label>
                                <select name="is-put-up" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option
                                        value="1" <?php echo Yii::$app->request->post('is-put-up', -1) == 1 ? 'selected' : '' ?>>
                                        上架
                                    </option>
                                    <option
                                        value="0" <?php echo Yii::$app->request->post('is-put-up', -1) == 0 ? 'selected' : '' ?>>
                                        下架
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>价格过期日期</label>
                                <input type="text" name="active-end-time-range"
                                       value="<?php echo Yii::$app->request->post('active-end-time-range', ''); ?>"
                                       placeholder="请输入过期日期" class="form-control input-sm active-end-time-range">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>账号更新</label>
                                <select name="has-updated" class="form-control input-sm">
                                    <option value="-1" selected>不限</option>
                                    <option
                                        value="1" <?php echo Yii::$app->request->post('has-updated', -1) == 1 ? 'selected' : '' ?>>
                                        已更新
                                    </option>
                                    <option
                                        value="0" <?php echo Yii::$app->request->post('has-updated', -1) == 0 ? 'selected' : '' ?>>
                                        未更新
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>1.0备注</label>
                                <input type="text" name="remark" value="<?php echo Yii::$app->request->post('remark', ''); ?>" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row m-l-30">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="button" class="btn btn-sm btn-primary btn-submit"
                                       value="查&nbsp;&nbsp;&nbsp;&nbsp;询"/>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="page" type="hidden" name="page"
                       value="<?php echo Yii::$app->request->post('page', 0); ?>">
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
                            'tableOptions' => ['class' => 'footable table table-striped toggle-arrow-tiny table-media-list', 'id' => 'fixed-header-data-table'],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'headerOptions' => ['data-sort-ignore' => 'true']
                                ],
                                [
                                    'header' => '公众号',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'public-name', 'style' => 'max-width: 120px'],
                                    'value' => function ($model, $key, $index, $column) {
                                        $followNum = round($model['follower_num'] / 10000, 2);
                                        return "<span>{$model['public_name']}</span><br/>
                                                <span>{$model['public_id']}</span><br/>
                                                <span style='color:#23527c;'>{$followNum}万</span>";
                                    },
                                ],
                                [
                                    'header' => '资源分类',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'public-name', 'style' => 'max-width: 120px'],
                                    'value' => function ($model, $key, $index, $column) {
                                        $mediaCateLabel = '';
                                        $mediaCateJson = MediaHelper::parseMediaCate($model['media_cate']);
                                        if (empty($mediaCateJson)) {
                                            $mediaCateLabel .= '未设置';
                                        } else {
                                            $mediaCateList = json_decode($mediaCateJson, true);
                                            foreach ($mediaCateList as $code => $mediaCate) {
                                                $mediaCateLabel .= '[' . $mediaCate . '] <br>';
                                            }
                                        }
                                        return $mediaCateLabel;
                                    },
                                ],
                                [
                                    'header' => '首选媒体主',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'is-pref-vendor'],
                                    'value' => function ($model, $key, $index, $column) {
                                        return ($model['vendor_name'] == '' )? "<a title=联系人".$model['contact1'].$model['vendor_comment'].">".$model['vendor_contact_person']."</a>": "<a title=联系人：".$model['contact1']."接单备注：".$model['vendor_comment'].">".$model['vendor_name']."</a>";
                                    },
                                ],
                                [
                                    'header' => '价格(元)',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'price-list'],
                                    'value' => function ($model, $key, $index, $column) {
                                        $origPriceLabel = '';
                                        $originPriceArray = MediaHelper::parseMediaWeixinOriginPrice($model['pub_config']);
                                        if ($originPriceArray['s']['pub_type'] == 0) {
                                            $origPriceLabel .= $originPriceArray['s']['pos_label'] . ': ' . $originPriceArray['s']['pub_type_label'] . '<br>';
                                        } else {
                                            $origPriceLabel .= $originPriceArray['s']['pos_label'] . ': ' . $originPriceArray['s']['pub_type_label'] . '  ' . $originPriceArray['s']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_1']['pub_type'] == 0) {
                                            $origPriceLabel .= $originPriceArray['m_1']['pos_label'] . ': ' . $originPriceArray['m_1']['pub_type_label'] . '<br>';
                                        } else {
                                            $origPriceLabel .= $originPriceArray['m_1']['pos_label'] . ': ' . $originPriceArray['m_1']['pub_type_label'] . '  ' . $originPriceArray['m_1']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_2']['pub_type'] == 0) {
                                            $origPriceLabel .= $originPriceArray['m_2']['pos_label'] . ': ' . $originPriceArray['m_2']['pub_type_label'] . '<br>';
                                        } else {
                                            $origPriceLabel .= $originPriceArray['m_2']['pos_label'] . ': ' . $originPriceArray['m_2']['pub_type_label'] . '  ' . $originPriceArray['m_2']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_3']['pub_type'] == 0) {
                                            $origPriceLabel .= $originPriceArray['m_3']['pos_label'] . ': ' . $originPriceArray['m_3']['pub_type_label'] . '<br>';
                                        } else {
                                            $origPriceLabel .= $originPriceArray['m_3']['pos_label'] . ': ' . $originPriceArray['m_3']['pub_type_label'] . '  ' . $originPriceArray['m_3']['price_label'] . '<br>';
                                        }

                                        $retailPriceLabel = '【零售价】<br>';
                                        $retailPriceArray = MediaHelper::parseMediaWeixinRetailPrice($model['pub_config']);
                                        if ($retailPriceArray['s']['pub_type'] == 0) {
                                            $retailPriceLabel .= $retailPriceArray['s']['pos_label'] . ': ' . $retailPriceArray['s']['pub_type_label'] . '<br>';
                                        } else {
                                            $retailPriceLabel .= $retailPriceArray['s']['pos_label'] . ': ' . $retailPriceArray['s']['pub_type_label'] . '  ' . $retailPriceArray['s']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_1']['pub_type'] == 0) {
                                            $retailPriceLabel .= $retailPriceArray['m_1']['pos_label'] . ': ' . $retailPriceArray['m_1']['pub_type_label'] . '<br>';
                                        } else {
                                            $retailPriceLabel .= $retailPriceArray['m_1']['pos_label'] . ': ' . $retailPriceArray['m_1']['pub_type_label'] . '  ' . $retailPriceArray['m_1']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_2']['pub_type'] == 0) {
                                            $retailPriceLabel .= $retailPriceArray['m_2']['pos_label'] . ': ' . $retailPriceArray['m_2']['pub_type_label'] . '<br>';
                                        } else {
                                            $retailPriceLabel .= $retailPriceArray['m_2']['pos_label'] . ': ' . $retailPriceArray['m_2']['pub_type_label'] . '  ' . $retailPriceArray['m_2']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_3']['pub_type'] == 0) {
                                            $retailPriceLabel .= $retailPriceArray['m_3']['pos_label'] . ': ' . $retailPriceArray['m_3']['pub_type_label'] . '<br>';
                                        } else {
                                            $retailPriceLabel .= $retailPriceArray['m_3']['pos_label'] . ': ' . $retailPriceArray['m_3']['pub_type_label'] . '  ' . $retailPriceArray['m_3']['price_label'] . '<br>';
                                        }

                                        $executePriceLabel = '【执行价】<br>';
                                        $executePriceArray = MediaHelper::parseMediaWeixinExecutePrice($model['pub_config']);
                                        if ($executePriceArray['s']['pub_type'] == 0) {
                                            $executePriceLabel .= $executePriceArray['s']['pos_label'] . ': ' . $executePriceArray['s']['pub_type_label'] . '<br>';
                                        } else {
                                            $executePriceLabel .= $executePriceArray['s']['pos_label'] . ': ' . $executePriceArray['s']['pub_type_label'] . '  ' . $executePriceArray['s']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_1']['pub_type'] == 0) {
                                            $executePriceLabel .= $executePriceArray['m_1']['pos_label'] . ': ' . $executePriceArray['m_1']['pub_type_label'] . '<br>';
                                        } else {
                                            $executePriceLabel .= $executePriceArray['m_1']['pos_label'] . ': ' . $executePriceArray['m_1']['pub_type_label'] . '  ' . $executePriceArray['m_1']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_2']['pub_type'] == 0) {
                                            $executePriceLabel .= $executePriceArray['m_2']['pos_label'] . ': ' . $executePriceArray['m_2']['pub_type_label'] . '<br>';
                                        } else {
                                            $executePriceLabel .= $executePriceArray['m_2']['pos_label'] . ': ' . $executePriceArray['m_2']['pub_type_label'] . '  ' . $executePriceArray['m_2']['price_label'] . '<br>';
                                        }
                                        if ($originPriceArray['m_3']['pub_type'] == 0) {
                                            $executePriceLabel .= $executePriceArray['m_3']['pos_label'] . ': ' . $executePriceArray['m_3']['pub_type_label'] . '<br>';
                                        } else {
                                            $executePriceLabel .= $executePriceArray['m_3']['pos_label'] . ': ' . $executePriceArray['m_3']['pub_type_label'] . '  ' . $executePriceArray['m_3']['price_label'] . '<br>';
                                        }
                                        return  $origPriceLabel;
                                    },
                                ],
                                [
                                    'header' => '状态',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'put-up'],
                                    'value' => function ($model, $key, $index, $column) {
                                        if ($model['put_up'] == 0) {
                                            return '<span class="label label-default">下架</span>';
                                        } else {
                                            return '<span class="label label-success">上架</span>';
                                        }
                                    },
                                ],
                                [
                                    'header' => '更新',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'put-up'],
                                    'value' => function ($model, $key, $index, $column) {
                                        if ($model['has_updated'] == 0) {
                                            return '<span class="label label-default">未更新</span>';
                                        } else {
                                            return '<span class="label label-danger">已更新</span>';
                                        }
                                    },
                                ],
                                [
                                    'header' => '备注',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'comment', 'style' => 'max-width: 210px'],
                                    'value' => function ($model, $key, $index, $column) {
                                        $comment = '';
                                        if(!empty($model['comment'])) {
                                            $comment .= $model['comment'];
                                        }
                                        if(!empty($model['comment']) && !empty($model['t_comment'])) {
                                            $comment .= '<br>====================<br>';
                                        }
                                        if(!empty($model['t_comment'])){
                                            $comment .= '1.0系统的备注:<br>' . $model['t_comment'];
                                        }
                                        return $comment;
                                    },
                                ],
                                [
                                    'header' => '日期',
                                    'format' => 'html',
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => 'op-time'],
                                    'value' => function ($model, $key, $index, $column) {
                                        return '价格有效期 <br><span style="color:red;">' . date('Y-m-d', $model['active_end_time']) . '</span><br>' . '最后更新 <br>' . date('Y-m-d', $model['last_update_time']);
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{to-verify}<br>{put-up}<br>{set-top}<br>{set-push}',
                                    'buttons' => [
                                        'media-executor-assign' => function ($url, $model) {
                                            return Html::button('分配媒介', ['class' => 'btn btn-link btn-xs btn-assign-media-executor', 'data-uuid' => $model['media_uuid']]);
                                        },
                                        'to-verify' => function ($url, $model) {
                                            return Html::button('修改', ['class' => 'btn btn-link btn-xs btn-to-verify', 'data-uuid' => $model['media_uuid']]);
                                        },
                                        'put-up' => function ($url, $model) {
                                            if ($model['put_up'] == 1) {
                                                return Html::button('下架', ['class' => 'btn btn-link btn-xs btn-put-up', 'data-uuid' => $model['media_uuid'], 'data-put' => 1]);
                                            } else {
                                                return Html::button('上架', ['class' => 'btn btn-link btn-xs btn-put-up', 'data-uuid' => $model['media_uuid'], 'data-put' => 0]);
                                            }
                                        },
                                        'set-top' => function ($url, $model) {
                                            if ($model['cust_sort'] == 5) {
                                                return Html::button('取消置顶', ['class' => 'btn btn-link btn-xs btn-cancel-top', 'data-uuid' => $model['media_uuid']]);
                                            } else {
                                                return Html::button('置顶', ['class' => 'btn btn-link btn-xs btn-set-top', 'data-uuid' => $model['media_uuid']]);
                                            }
                                        },
                                        'set-push' => function ($url, $model) {
                                            if ($model['is_push'] == 1) {
                                                return Html::button('取消主推', ['class' => 'btn btn-link btn-xs btn-cancel-push', 'data-uuid' => $model['media_uuid']]);
                                            } else {
                                                return Html::button('主推', ['class' => 'btn btn-link btn-xs btn-set-push', 'data-uuid' => $model['media_uuid']]);
                                            }
                                        }
                                    ],
                                    'headerOptions' => ['data-sort-ignore' => 'true'],
                                    'contentOptions' => ['class' => '']
                                ],
                            ]
                        ]);
                    } ?>
                </div>
            </div>
            <?php Pjax::end() ?>
            <table id="header-fixed" class="footable table table-striped toggle-arrow-tiny table-media-list" style="position: fixed;top: 54px;display: none;margin-left: 15px">
                <thead>
                <tr>
                    <th data-sort-ignore="true">#</th>
                    <th data-sort-ignore="true">公众号</th>
                    <th data-sort-ignore="true">资源分类</th>
                    <th data-sort-ignore="true">首选媒体主</th>
                    <th data-sort-ignore="true">价格(元)</th>
                    <th data-sort-ignore="true">状态</th>
                    <th data-sort-ignore="true">更新</th>
                    <th data-sort-ignore="true">备注</th>
                    <th data-sort-ignore="true">日期</th>
                    <th data-sort-ignore="true">操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- 分配媒介 -->
<div class="modal fade" id="modal-assign-media-executor" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">分配媒介</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-inverse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <input class="media-uuid" type="hidden" value="">
                            <div class="form-group">
                                <label class="col-md-3 control-label">媒介运营:</label>
                                <div class="col-md-6">
                                    <select class="form-control media-executor-select">
                                        <option value="-1">请选择</option>
                                    </select>
                                </div>
                            </div>
                        </form>
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

<!-- 修改 -->
<div class="modal fade" id="modal-to-verify" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-blg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">修改</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="media-uuid" value="">

                <ul class="nav nav-pills">
                    <li class="active">
                        <a class="nav-to-verify-media" href="#stage-to-verify-media-content" data-toggle="tab">修改资源</a>
                    </li>
                    <li>
                        <a class="nav-to-verify-vendor" href="#stage-to-verify-vendor-content"
                           data-toggle="tab">修改媒体主</a>
                    </li>
                    <li>
                        <a class="nav-to-add-vendor" href="#stage-to-add-vendor" data-toggle="tab">添加媒体主</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="stage-to-verify-media-content">
                        <div class="panel panel-inverse">
                            <div class="panel-body">
                                <div class="row form-media-info">
                                    <div class="col-md-5" style="border-right: 1px solid #e7eaec;">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">公众号ID :</label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static input-public-id"></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">公众号名称 *:</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control input-public-name"
                                                           placeholder="请输入微信公众号名称" value="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">粉丝数 *:
                                                    <br>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control input-follower-num"
                                                           placeholder="请输入粉丝数" value="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">自媒体类型 *:
                                                </label>
                                                <div class="col-md-9 media-weixin-belong-type">
                                                    <?php
                                                    $mediaWeixinBelongTypeList = MediaHelper::getMediaWeixinBelongType();
                                                    foreach ($mediaWeixinBelongTypeList as $code => $type) { ?>
                                                        <label class="checkbox-inline "><input type="checkbox"
                                                                                               value="<?php echo $code; ?>"
                                                                                               class="<?php echo 'one-type type-' . $code; ?>"><?php echo $type; ?>
                                                        </label>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">入驻时间:</label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static account-create-time"></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">接单备注:</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control comment" placeholder="请输入接单备注信息"
                                                              rows="3"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">1.0系统的备注:</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control t_comment" placeholder=""
                                                              rows="3" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">资源分类 *:
                                                    <br>
                                                    <small class="text-danger">(多选)</small>
                                                </label>
                                                <div class="col-md-9 media-cate">
                                                    <?php
                                                    $mediaCateList = MediaHelper::getMediaCateList();
                                                    foreach ($mediaCateList as $code => $cate) { ?>
                                                        <label class="checkbox-inline "><input type="checkbox"
                                                                                               value="<?php echo $code; ?>"
                                                                                               class="<?php echo 'one-cate cate-' . $code; ?>"><?php echo $cate; ?>
                                                        </label>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">账号地域属性 *:
                                                </label>
                                                <div class="col-md-9 follower-area">
                                                    <label class="checkbox-inline"><input type="checkbox" value="0"
                                                                                          class="one-area area-0">全国</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="1"
                                                                                          class="one-area area-1">北京</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="9"
                                                                                          class="one-area area-9">上海</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="289"
                                                                                          class="one-area area-289">广州</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="291"
                                                                                          class="one-area area-291">深圳</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="175"
                                                                                          class="one-area area-175">杭州</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="2"
                                                                                          class="one-area area-2">天津</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="275"
                                                                                          class="one-area area-275">长沙</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="258"
                                                                                          class="one-area area-258">武汉</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="162"
                                                                                          class="one-area area-162">南京</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="22"
                                                                                          class="one-area area-22">重庆</label>
                                                    <label class="checkbox-inline"><input type="checkbox" value="35"
                                                                                          class="one-area area-35">海外</label>
                                                    <label class="checkbox-inline"><input type="checkbox"
                                                                                          class="area-other">其他城市</label>
                                                </div>
                                            </div>

                                            <div class="form-group other-area-select">
                                                <label class="col-md-3 control-label">&nbsp;&nbsp;&nbsp;</label>
                                                <div class="col-md-9">
                                                    <select class="form-control input-inline follower-area-province">
                                                        <option value="10">江苏</option>
                                                        <option value="11">浙江</option>
                                                        <option value="12">安徽</option>
                                                        <option value="13">福建</option>
                                                        <option value="14">江西</option>
                                                        <option value="15">山东</option>
                                                        <option value="16">河南</option>
                                                        <option value="17">湖北</option>
                                                        <option value="18">湖南</option>
                                                        <option value="19">广东</option>
                                                        <option value="30">宁夏</option>
                                                        <option value="31">新疆</option>
                                                        <option value="4">山西</option>
                                                        <option value="3">河北</option>
                                                        <option value="5">内蒙古</option>
                                                        <option value="6">辽宁</option>
                                                        <option value="7">吉林</option>
                                                        <option value="8">黑龙江</option>
                                                        <option value="20">广西</option>
                                                        <option value="21">海南</option>
                                                        <option value="23">四川</option>
                                                        <option value="24">贵州</option>
                                                        <option value="25">云南</option>
                                                        <option value="26">西藏</option>
                                                        <option value="27">陕西</option>
                                                        <option value="28">甘肃</option>
                                                        <option value="29">青海</option>
                                                        <option value="32">台湾</option>
                                                        <option value="33">香港</option>
                                                        <option value="34">澳门</option>
                                                        <option value="34">其他</option>
                                                    </select>
                                                    <select class="form-control input-inline follower-area-city">
                                                        <option value="-1" selected>不限</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">资源状态 *:
                                                </label>
                                                <div class="col-md-5">
                                                    <select class="form-control status">
                                                        <option value="0" selected>待审核</option>
                                                        <option value="1">审核通过</option>
                                                        <option value="4">无效账号</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="" style="text-align: center">
                                        <button type="button" class="btn btn-success btn-lg btn-commit-media">保&nbsp;&nbsp;&nbsp;&nbsp;存</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="stage-to-verify-vendor-content">

                        <div style="text-align: right">
                            <button class="btn btn-warning btn-sm btn-to-add-vendor" type="button">添加媒体主</button>
                        </div>

                        <table class="table table-vendor">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>媒体主名称</th>
                                <th>注册渠道</th>
                                <th>联系人</th>
                                <th>激活</th>
                                <th>首选媒体主</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- ajax媒体主列表 -->
                            </tbody>
                        </table>

                        <div class="panel panel-inverse area-vendor-detail" style="display: none" data-bind-uuid="">

                            <legend class="vendor-name"></legend>
                            <div class="panel-body">


                                <!--                        <p class="text-success"> 技术服务费率: <span class="global-serve-percent"></span></p>-->
                                <!--                        <p class="text-success">定金比例: <span class="global-deposit-percent"></span></p>-->

                                <div class="note note-danger" style="display: none">
                                    <h4>注意:</h4>
                                    <ol>
                                        <li>零售价表示平台对外显示价格, 须填写 >=0 的数字类型</li>
                                        <li>平台合作价表示平台与媒体主达成的合作价格, 须填写 >=0 的数字类型</li>
                                        <li>技术服务费率默认为 取值范围为 (0 - 1)</li>
                                        <li>定金比例取值范围为 (0 - 1]</li>
                                    </ol>
                                </div>

                                <table class="table table-bordered table-price-set">
                                    <thead>
                                    <tr>
                                        <th style="min-width: 150px">位置</th>
                                        <th style="min-width: 150px">投放形式</th>
                                        <th style="min-width: 150px">平台合作价(元)</th>
                                        <th style="min-width: 150px">零售价(元)</th>
                                        <th style="min-width: 150px">执行价(元)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="one-pos pos-s">
                                        <td>单图文</td>
                                        <td class="pub-type">
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-s-pub-type" value="1" checked="">
                                                纯发布
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-s-pub-type" value="2">
                                                原创+发布
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-s-pub-type" value="0">
                                                不接单
                                            </label>
                                        </td>
                                        <td class="orig-price">
                                            <input type="text"
                                                   class="form-control is-price-field origin-price-val-min"
                                                   placeholder="必填项" value="0">
                                        </td>
                                        <td class="retail-price">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control is-price-field retail-price-val-min"
                                                       placeholder="必填项" value="0">
                                            </div>
                                        </td>
                                        <td class="execute-price">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control is-price-field execute-price-val"
                                                       placeholder="必填项" value="0">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="one-pos pos-m-1">
                                        <td>多图文头条</td>
                                        <td class="pub-type">
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-1-pub-type" value="1" checked="">
                                                纯发布
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-1-pub-type" value="2">
                                                原创+发布
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-1-pub-type" value="0">
                                                不接单
                                            </label>
                                        </td>
                                        <td class="orig-price">
                                            <input type="text"
                                                   class="form-control is-price-field origin-price-val-min"
                                                   placeholder="必填项" value="0">
                                        </td>
                                        <td class="retail-price">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control is-price-field retail-price-val-min"
                                                       placeholder="必填项" value="0">
                                            </div>
                                        </td>
                                        <td class="execute-price">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control is-price-field execute-price-val"
                                                       placeholder="必填项" value="0">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="one-pos pos-m-2">
                                        <td>多图文第2条</td>
                                        <td class="pub-type">
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-2-pub-type" value="1" checked="">
                                                纯发布
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-2-pub-type" value="2">
                                                原创+发布
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-2-pub-type" value="0">
                                                不接单
                                            </label>
                                        </td>
                                        <td class="orig-price">
                                            <input type="text"
                                                   class="form-control is-price-field origin-price-val-min"
                                                   placeholder="必填项" value="0">
                                        </td>
                                        <td class="retail-price">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control is-price-field retail-price-val-min"
                                                       placeholder="必填项" value="0">
                                            </div>
                                        </td>
                                        <td class="execute-price">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control is-price-field execute-price-val"
                                                       placeholder="必填项" value="0">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="one-pos pos-m-3">
                                        <td>多图文第3-N条</td>
                                        <td class="pub-type">
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-3-pub-type" value="1" checked="">
                                                纯发布
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-3-pub-type" value="2">
                                                原创+发布
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pos-m-3-pub-type" value="0">
                                                不接单
                                            </label>
                                        </td>
                                        <td class="orig-price">
                                            <input type="text"
                                                   class="form-control is-price-field origin-price-val-min"
                                                   placeholder="必填项" value="0">
                                        </td>
                                        <td class="retail-price">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control is-price-field retail-price-val-min"
                                                       placeholder="必填项" value="0">
                                            </div>
                                        </td>
                                        <td class="execute-price">
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control is-price-field execute-price-val"
                                                       placeholder="必填项" value="0">
                                            </div>
                                        </td>
                                </table>

                                <div class="form-horizontal form-vendor">
                                    <div class="row">
                                        <div class="col-md-5 m-l-40">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">媒体主名称:</label>
                                                <div class="col-md-5">
                                                    <p class="form-control-static vendor-name"></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">资源所属关系:</label>
                                                <div class="col-md-4">
                                                    <select class="form-control media-ownership">
                                                        <?php
                                                        $ownershipList = MediaHelper::getMediaOwnershipList();
                                                        foreach ($ownershipList as $code => $ownership) { ?>
                                                            <option
                                                                value="<?php echo $code; ?>"><?php echo $ownership; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">配合度:</label>
                                                <div class="col-md-4">
                                                    <select class="form-control execute-level">
                                                        <option value="-1">未知</option>
                                                        <option value="1">高</option>
                                                        <option value="2">中</option>
                                                        <option value="3">低</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">账期:</label>
                                                <div class="col-md-4">
                                                    <select class="form-control pay-period">
                                                        <option value="-1">未知</option>
                                                        <option value="1">年框</option>
                                                        <option value="2">3个月以上</option>
                                                        <option value="3">1-3个月</option>
                                                        <option value="4">1-4周</option>
                                                        <option value="5">无</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">报价有效期:</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control active-end-time"
                                                           placeholder="" value="请填写报价有效期(必填项)">

                                                    <br>
                                                    <a href="javascript:;" class="btn btn-primary btn-xs m-r-5 sync-latest-active-end-time">同步最新报价有效期</a>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">媒体主审核状态:</label>
                                                <div class="col-md-4">
                                                    <select class="form-control vendor-bind-status">
                                                        <option value="0">待审核</option>
                                                        <option value="1">已通过</option>
                                                        <option value="2">未通过</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group pref-vendor-select">
                                                <label class="col-md-4 control-label">设为首选媒体主:</label>
                                                <div class="col-md-4">
                                                    <select class="form-control pref-vendor">
                                                        <option value="0">否</option>
                                                        <option value="1">是</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div style="text-align: center">
                                            <button type="button" class="btn btn-success btn-lg btn-commit-vendor">保&nbsp;&nbsp;&nbsp;&nbsp;存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="stage-to-add-vendor">
                        <div class="panel panel-inverse">
                            <div class="panel-body body-add-media-vendor">

                                <div class="alert alert-danger fade in m-b-15">
                                    <strong>注意: </strong>
                                    1. 可从系统中搜索已经存在的媒体主 2. 如果在系统不存在,可以"新建媒体主"
                                    <span class="close" data-dismiss="alert">×</span>
                                </div>

                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">搜索已有媒体主*: </label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control input-name" name="search_vendor_name"
                                                   placeholder="媒体主名称\联系方式\QQ\微信"/>
                                            <span class="error-msg"
                                                  style="color:red;display:none;font-size:16px;">媒体主不存在，请去 "新建媒体主"</span>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-success btn-vendor-search" type="button">搜&nbsp;&nbsp;&nbsp;索</button>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-primary btn-create-vendor" type="button"
                                            ">新建媒体主
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive table-search-vendor-list">
                                    <h1 class="page-header" style="font-size:16px;color:#00acac;">搜索媒体主列表</h1>
                                    <table id="user" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>媒体主</th>
                                            <th>注册渠道</th>
                                            <th>联系人</th>
                                            <th>备注</th>
                                            <th>选择</th>
                                        </tr>
                                        </thead>
                                        <tbody class="vendor-search-result">
                                        <!-- ajax获取媒体主列表-->
                                        </tbody>
                                    </table>
                                </div>

                                <div style="text-align: center">
                                    <button type="button" class="btn btn-success btn-lg btn-add-vendor"
                                            style="display: none">添&nbsp;&nbsp;&nbsp;&nbsp;加
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

<!-- 上下架 -->
<div class="modal fade" id="modal-put-up" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">资源上下架</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-inverse">
                    <div class="panel-body">

                        <div class="alert alert-danger fade in m-b-15 put-up-warning" style="display: none">

                        </div>

                        <div class="form-horizontal put-up-set-form" style="display: none;">
                            <input type="hidden" class="media-uuid" value="">
                            <div class="form-group put-up-select">
                                <label class="col-md-3 control-label">上下架:</label>
                                <div class="col-md-6">
                                    <label class="radio-inline">
                                        <input type="radio" name="put-up" value="1">
                                        上架
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="put-up" value="0">
                                        下架
                                    </label>
                                </div>
                            </div>

                            <div class="form-group in-wom-rank-select">
                                <label class="col-md-3 control-label">上沃米排行榜:</label>
                                <div class="col-md-6">
                                    <label class="radio-inline">
                                        <input type="radio" name="in-wom-rank" value="1">
                                        上架
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="in-wom-rank" value="0">
                                        下架
                                    </label>
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

<?php
$addMediaVendorJs = <<<JS

JS;
$this->registerJs($addMediaVendorJs);

?>
