//微博资源详情js
function videoDetail(){

    //审核不通原因
    $("#modal-dialog-add").find(".audit_status").change(function(){
        if($(this).children("option:selected").val()==2){
            $("#modal-dialog-add").find(".fail_reason").removeAttr("disabled");
        }else{
            $("#modal-dialog-add").find(".fail_reason").val("");
            $("#modal-dialog-add").find(".fail_reason").attr("disabled","disabled");
        }
    });
    $("#modal-dialog-audit").find(".audit_status").change(function(){
        if($(this).children("option:selected").val()==2){
            $("#modal-dialog-audit").find(".fail_reason").removeAttr("disabled");
        }else{
            $("#modal-dialog-audit").find(".fail_reason").val("");
            $("#modal-dialog-audit").find(".fail_reason").attr("disabled","disabled");
        }
    });

    //添加平台信息
    $(".btn-save-add-platform").click(function(){
        var modal_add = $("#modal-dialog-add");
        var url = $(this).data("url");
        var video_uuid = $(this).data("uuid");
        var type = modal_add.find("input[name=platform_type]:checked").data("type");//平台类型
        var account_name =  modal_add.find(".account_name").val();
        var account_id =  modal_add.find(".account_id").val();
        var follower_num =  modal_add.find(".follower_num").val();
        var auth_status =  modal_add.find(".auth_status").children("option:selected").val();
        var video_url =  modal_add.find(".url").val();
        var avg_watch_num =  modal_add.find(".avg_watch_num").val();
        var person_sign =  modal_add.find(".person_sign").val();
        var remark =  modal_add.find(".remark").val();
        var audit_status =  modal_add.find(".audit_status").children("option:selected").val();
        var fail_reason =  modal_add.find(".fail_reason").val();
        if(account_name =="" || follower_num ==""){
            swal({title: "存在必填项未填!", text: "", type: "warning"});
            return false;
        }
        swal({
            title: "确定添加该平台?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: true
        },
        function(){
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    video_uuid:video_uuid,
                    type: type,
                    account_name:account_name,
                    account_id:account_id,
                    follower_num:follower_num,
                    auth_status:auth_status,
                    url:video_url,
                    avg_watch_num:avg_watch_num,
                    person_sign:person_sign,
                    remark:remark,
                    audit_status:audit_status,
                    fail_reason:fail_reason,
                },
                success: function (resp) {
                    if(resp.err_code == 0){
                        swal({title: "添加成功", text: "", type: "success"});
                        window.location.reload();
                        return false;
                    }else{
                        swal({title: "添加失败！", text: "", type: "error"});
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "", text: "系统出错！", type: "error"});
                }
            });
        });
    });

    //主打平台单选
    $("input[name=is_main]").click(function(){
        $("input[name=is_main]").not(this).removeAttr("checked");
    });

    //修改or审核平台信息
    $(".platform-to-verify").click(function(){
        var url = "index.php?r=video/media/get-platform-info";
        var platform_uuid = $(this).data('uuid');
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {platform_uuid:platform_uuid},
            success: function (resp) {
                if(resp.err_code == 0){
                    var platform = resp.data;
                    var modal_audit =  $("#modal-dialog-audit");
                    modal_audit.find(".account_name").val(platform['account_name']);
                    modal_audit.find(".account_id").val(platform['account_id']);
                    modal_audit.find(".follower_num").val(platform['follower_num']);
                    modal_audit.find(".url").val(platform['url']);
                    modal_audit.find(".avg_watch_num").val(platform['avg_watch_num']);
                    modal_audit.find(".person_sign").val(platform['person_sign']);
                    modal_audit.find(".remark").val(platform['remark']);
                    modal_audit.find(".fail_reason").val(platform['fail_reason']);
                    modal_audit.find(".audit_time").text(platform['audit_time']);
                    modal_audit.find(".audit_status").children("option").each(function(){
                        console.log($(this).val());
                        if($(this).val()==platform['status']){
                            $(this).attr("selected","selected");
                        }
                    });
                    modal_audit.find(".auth_status").children("option").each(function(){
                        console.log($(this).val());
                        if($(this).val()==platform['auth_type']){
                            $(this).attr("selected","selected");
                        }
                    });
                    modal_audit.find(".btn-update-platform").attr("data-uuid",platform['uuid']);
                    modal_audit.modal('show');
                    return false;
                }else{
                    swal({title: "获取失败！", text: "", type: "error"});
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                swal({title: "", text: "系统出错！", type: "error"});
            }
        });
    });

    //审核or修改平台信息的保存
    $(".btn-update-platform").click(function(){
        var url = "index.php?r=video/media/update-platform-info";
        var modal_audit = $("#modal-dialog-audit");
        var platform_uuid = $(this).data("uuid");
        var type = modal_audit.find("input[name=platform_type]:checked").data("type");//平台类型
        var account_name =  modal_audit.find(".account_name").val();
        var account_id =  modal_audit.find(".account_id").val();
        var follower_num =  modal_audit.find(".follower_num").val();
        var auth_status =  modal_audit.find(".auth_status").children("option:selected").val();
        var video_url =  modal_audit.find(".url").val();
        var avg_watch_num =  modal_audit.find(".avg_watch_num").val();
        var person_sign =  modal_audit.find(".person_sign").val();
        var remark =  modal_audit.find(".remark").val();
        var audit_status =  modal_audit.find(".audit_status").children("option:selected").val();
        var fail_reason =  modal_audit.find(".fail_reason").val();
        if(account_name =="" || follower_num ==""){
            swal({title: "存在必填项未填!", text: "", type: "warning"});
            return false;
        }
        swal({
            title: "确定添加该平台?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: true
        },
        function(){
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    platform_uuid:platform_uuid,
                    type: type,
                    account_name:account_name,
                    account_id:account_id,
                    follower_num:follower_num,
                    auth_status:auth_status,
                    url:video_url,
                    avg_watch_num:avg_watch_num,
                    person_sign:person_sign,
                    remark:remark,
                    audit_status:audit_status,
                    fail_reason:fail_reason,
                },
                success: function (resp) {
                    if(resp.err_code == 0){
                        swal({title: "审核成功！", text: "", type: "success"});
                        window.location.reload();
                        return false;
                    }else{
                        swal({title: "审核失败！", text: "", type: "error"});
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "", text: "系统出错！", type: "error"});
                }
            });
        });
    });

    //移除平台信息
    $(".platform-to-delete").click(function(){
        if($(this).parents("tr").find("input[name=is_main]").is(":checked")){
            swal({title: "主打平台，不可以删除！", text: "", type: "error"});
        }else{
            var url = "index.php?r=video/media/delete-platform-info";
            var platform_uuid = $(this).data('uuid');
            swal({
                    title: "确定删除该平台?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                    cancelButtonText: "取消",
                    closeOnConfirm: true
            },
            function(){
                $.ajax({
                    url: url,
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {platform_uuid:platform_uuid},
                    success: function (resp) {
                        if(resp.err_code == 0){
                            swal({title: "删除成功！", text: "", type: "success"});
                            window.location.reload();
                            return false;
                        }else{
                            swal({title: "删除失败！", text: "", type: "error"});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                        swal({title: "", text: "系统出错！", type: "error"});
                    }
                });
            });
        }
    });


    //////////////////////////////////////////////////添加自媒体主操作/////////////////////////////////////////////////
    //审核媒体主中添加媒体主button
    $(".btn-to-add-vendor").click(function(){
        $(".nav .add-vendor").click();
    });

    //添加媒体主button
    $(".nav .add-vendor").click(function(){
        //是否有主打平台判断
        var main_platform_num = $(".tbody-video-platform-list").children("tr").find("input:checked").length;
        if(main_platform_num <=0){
            swal({title: "未设置主打平台，无法添加媒体主!", text: "", type: "warning"});
            return false;
        }
        var url = $(this).data("url");
        $(".vendor-search-result").html("");
        $.ajax({
            url: url,
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
                        $('.error-msg').show();
                        return false;
                    }else{
                        $('.error-msg').hide();
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
                                    var contact_phone = contact_info_arr[j]['contact_phone'].length == 0 ? '无' : contact_info_arr[j]['contact_phone'];
                                    var weixin = contact_info_arr[j]['weixin'].length == 0 ? '无' : contact_info_arr[j]['weixin'];
                                    var qq = contact_info_arr[j]['qq'].length == 0 ? '无' : contact_info_arr[j]['qq'];
                                    contact_info_label += '联系人:' + contact_person + ', 电话:' + contact_phone + ', 微信:' + weixin + ', QQ:' + qq + '<br>';
                                }
                            }
                            var one_vendor ="<tr data-vendor='" + vendor_uuid + "' data-name='" + vendor_name + "'>"+
                                "<td>" + vendor_name + "</td>"+
                                "<td>" + register_type_label + "</td>"+
                                "<td>" + contact_info_label + "</td>"+
                                "<td>" + comment + "</td>"+
                                "<td><input type='checkbox' class='vendor-select'></td>"+
                                "</tr>";
                            $(".vendor-search-result").append(one_vendor);
                        }
                        $('.btn-add-vendor').show();
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
    $("input[name='search_vendor_name']").keydown(function(event){
        if(event.keyCode==13){
            $(".btn-vendor-search").click();
            return false;
        }
    });

    // 添加媒体主 - 搜索
    $('.btn-vendor-search').click(function(){
        var url = $(this).data("url");
        var vendor_search =$("input[name=search_vendor_name]").val();
        if(vendor_search ==""){
            swal({title: "", text: "查询内容不能为空", type: "error"});
            return false;
        }
        $('.vendor-search-result').html('');
        $.ajax({
            url: url,
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
                        $('.error-msg').show();
                        return false;
                    }else{
                        $('.error-msg').hide();
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
                                    var contact_phone = contact_info_arr[j]['contact_phone'].length == 0 ? '无' : contact_info_arr[j]['contact_phone'];
                                    var weixin = contact_info_arr[j]['weixin'].length == 0 ? '无' : contact_info_arr[j]['weixin'];
                                    var qq = contact_info_arr[j]['qq'].length == 0 ? '无' : contact_info_arr[j]['qq'];
                                    contact_info_label += '联系人:' + contact_person + ', 电话:' + contact_phone + ', 微信:' + weixin + ', QQ:' + qq + '<br>';
                                }
                            }
                            var one_vendor =  "<tr data-vendor='" + vendor_uuid + "' data-name='" + vendor_name + "'>"+
                                "<td>" + vendor_name + "</td>"+
                                "<td>" + register_type_label + "</td>"+
                                "<td>" + contact_info_label + "</td>"+
                                "<td>" + comment + "</td>"+
                                "<td><input type='checkbox' class='vendor-select'></td>"+
                                "</tr>";
                            $(".vendor-search-result").append(one_vendor);
                        }
                        $('.btn-add-vendor').show();
                    }
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown){
                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                return false;
            }
        });
    });

    // 添加媒体主 - 新建媒体主快捷按钮
    $(".btn-create-vendor").click(function(){
        var url = $(this).data("url");
        window.open(url);
    });

    // 添加媒体主 - 选择媒体主的复选框
    $('body').on('click', '.vendor-select', function() {
        if($(this).is(':checked')){
            $('.vendor-search-result tr').hide();
            $(this).closest('tr').show();
        } else {
            $('.vendor-search-result tr').show();
        }
        $(".vendor-select").not(this).attr("checked", false);
    });

    // 添加媒体主 - 保存
    $('.btn-add-vendor').on('click', function(){
        var video_uuid = $('input[name=video_uuid]').val();
        var url = $(this).data("url");
        var vendor_uuid = '';
        var has_select_vendor = 0;
        $(".vendor-select").each(function(){
            if($(this).is(':checked')){
                vendor_uuid = $(this).closest('tr').attr('data-vendor');
                has_select_vendor = 1;
            }
        });
        if(has_select_vendor == 0){
            swal({title: "", text: "请选择媒体主", type: "error"});
            return false;
        }
        if(video_uuid == '' || vendor_uuid == ''){
            swal({title: "", text: "获取媒体主信息失败", type: "error"});
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
                url: url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {video_uuid: video_uuid, vendor_uuid: vendor_uuid},
                success: function (resp) {
                    if(resp.err_code == 0){
                        $('.audit-vendor').click();
                    } else if(resp.err_code == 1){
                        swal({title: "该媒体主已添加！", text: "", type: "warning",timer:1000});
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "", text: "系统出错！", type: "error"});
                }
            });
        });
    });


    // 点击"审核媒体主"按钮,获取媒体主列表
    $('.audit-vendor').click(function(){
        //是否有主打平台判断
        var main_platform_num = $(".tbody-video-platform-list").children("tr").find("input:checked").length;
        if(main_platform_num <=0){
            swal({title: "未设置主打平台，无法审核媒体主!", text: "", type: "warning"});
            return false;
        }
        $(".media-vendor-detail").css("display","none");
        var video_uuid = $('input[name=video_uuid]').val();
        var url = $(this).data("url");
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {video_uuid: video_uuid},
            success: function (resp) {
                if(resp.err_code == 0){
                    $('.tbody-video-vendor-list').find('tr').remove();
                    var vendorList = resp.vendor_list;
                    for(var i = 0; i < vendorList.length; i++){
                        var vendor = vendorList[i];
                        var bind_uuid = vendor['vendor_bind_uuid'];
                        var vendor_name = vendor['name'];
                        var register_type = vendor['register_type'];
                        var bind_status = vendor['status'];
                        var contact_person = vendor['contact_person'];
                        var contacts = vendor['contact1'];
                        var is_pref_vendor = vendor['is_pref_vendor'];
                        if(vendor_name == ''){// 媒体主名称
                            vendor_name = '未填写';
                        }
                        if(register_type == 1){//注册渠道
                            var register_type_label = '前端注册';
                        } else if(register_type == 2){
                            var register_type_label = 'admin录入';
                        } else {
                            var register_type_label = '未知';
                        }
                        // 状态
                        if(bind_status == 0 || bind_status == 2){  // 待审核或未通过
                            var bind_status_label = '<span class="bind-status-label">待审核</span>';
                            var action_cell = '<a href="javascript:;" class="btn btn-primary btn-xs m-r-5 btn-vendor-to-verify" data-uuid="' + bind_uuid + '">审核</a>' +
                                '<a href="javascript:;" class="btn btn-white btn-xs m-r-5 btn-vendor-to-delete" data-uuid="' + bind_uuid + '">移除</a>';
                        } else if(bind_status == 1){ // 审核通过
                            var bind_status_label = '<span class="bind-status-label">已通过</span>';
                            var action_cell = '<a href="javascript:;" class="btn btn-primary btn-xs m-r-5 btn-vendor-to-verify" data-uuid="' + bind_uuid + '">编辑</a>' +
                                '<a href="javascript:;" class="btn btn-white btn-xs m-r-5 btn-vendor-to-delete" data-uuid="' + bind_uuid + '">移除</a>';
                        }
                        if(is_pref_vendor == 0) {
                            var is_pref_vendor_label = '否';
                            var oneLine = '<tr data-is-prefer="' + is_pref_vendor + '" data-uuid="' + bind_uuid + '" id="' + bind_uuid + '"><td>' + (i + 1) + '</td><td>' + vendor_name + '</td><td>' + register_type_label + '</td><td>' + contact_person + '</td><td>' + contacts + '</td><td class="is-pref-vendor-label">' + is_pref_vendor_label + '</td><td>' + bind_status_label + '</td><td>' + action_cell + '</td></tr>';
                        } else {
                            var is_pref_vendor_label = '是';
                            var oneLine = '<tr data-is-prefer="' + is_pref_vendor + '" data-uuid="' + bind_uuid + '" id="' + bind_uuid + '" style="color:red;"><td>' + (i + 1) + '</td><td>' + vendor_name + '</td><td>' + register_type_label + '</td><td>' + contact_person + '</td><td>' + contacts + '</td><td class="is-pref-vendor-label">' + is_pref_vendor_label + '</td><td>' + bind_status_label + '</td><td>' + action_cell + '</td></tr>';
                        }
                        $('.tbody-video-vendor-list').append(oneLine);
                    }
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
            }
        });
    });


    // 列表页里的"审核"按钮
    $('body').on('click', '.btn-vendor-to-verify', function(){
        var bind_uuid = $(this).attr('data-uuid');
        var is_prefer = $(this).closest("tr").attr('data-is-prefer');
        var url = $("input[name=get_vendor_info]").val();
        $(".tbody-vendor-price-list").children("tr").remove();
        //return false;
        // 获取媒体基本信息
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {bind_uuid: bind_uuid},
            success: function (resp) {
                if(resp.err_code == 0){
                    var vendorinfo = resp.vendorinfo;
                    for(i in vendorinfo){
                        var platform_type = vendorinfo[i]['platform_type'];
                        if(platform_type==1){var paltform_name = "花椒"};
                        if(platform_type==2){var paltform_name = "熊猫"};
                        if(platform_type==3){var paltform_name = "哈尼"};
                        if(platform_type==4){var paltform_name = "美拍"};
                        if(platform_type==5){var paltform_name = "秒拍"};
                        if(platform_type==6){var paltform_name = "斗鱼"};
                        if(platform_type==7){var paltform_name = "映客"};
                        if(platform_type==8){var paltform_name = "淘宝"};
                        if(platform_type==9){var paltform_name = "一直播"};
                        if(platform_type==10){var paltform_name = "快手"};
                        if(platform_type==11){var paltform_name = "小咖秀"};
                        if(platform_type==12){var paltform_name = "B站"};
                        if(vendorinfo[i]['price_orig_one']==null){vendorinfo[i]['price_orig_one'] = 0};
                        if(vendorinfo[i]['price_orig_two']==null){vendorinfo[i]['price_orig_two'] = 0};
                        if(vendorinfo[i]['price_retail_one']==null){vendorinfo[i]['price_retail_one'] = 0};
                        if(vendorinfo[i]['price_retail_two']==null){vendorinfo[i]['price_retail_two'] = 0};
                        if(vendorinfo[i]['price_execute_one']==null){vendorinfo[i]['price_execute_one'] = 0};
                        if(vendorinfo[i]['price_execute_two']==null){vendorinfo[i]['price_execute_two'] = 0};
                        if(vendorinfo[i]['is_main_platform'] == 1){
                            var one_vendor_info = "<tr is_main='1' audit_status="+vendorinfo[i]['platform_status']+" data-type="+platform_type+">";
                        }else{
                            var one_vendor_info = "<tr is_main='0' audit_status="+vendorinfo[i]['platform_status']+" data-type="+platform_type+">";
                        }
                        if(platform_type == 5){
                            one_vendor_info += "<td style='line-height:65px;'>"+paltform_name+"</td>" +
                                "<td><br>原创视频<br>视频转发</td>";
                        }else{
                            one_vendor_info += "<td style='line-height:65px;'>"+paltform_name+"</td>" +
                                "<td><br>线上直播<br>线下活动</td>";
                        }
                        one_vendor_info += "<td>" +
                            "<input value="+vendorinfo[i]['price_orig_one']+"  name='orig_one' type='text' class='form-control col-md-4'/>" +
                            "<input value="+vendorinfo[i]['price_orig_two']+"  name='orig_two' type='text' class='form-control col-md-4'/>" +
                            "</td>" +
                            "<td>" +
                            "<input value="+vendorinfo[i]['price_retail_one']+"  name='retail_one' type='text' class='form-control col-md-4'/>" +
                            "<input value="+vendorinfo[i]['price_retail_two']+"  name='retail_two' type='text' class='form-control col-md-4'/>" +
                            "</td>" +
                            "<td>" +
                            "<input value="+vendorinfo[i]['price_execute_one']+"  name='execute_one' type='text' class='form-control col-md-4'/>" +
                            "<input value="+vendorinfo[i]['price_execute_two']+"  name='execute_two' type='text' class='form-control col-md-4'/>" +
                            "</td>" +
                            "<td>";
                        if(vendorinfo[i]['platform_status']!=1){
                            one_vendor_info +=   "<input type='checkbox' name='is-up-select' disabled='disabled'><br></td></tr>";
                        }else{
                            if(vendorinfo[i]['is_put']==1){
                                one_vendor_info +=   "<input type='checkbox' name='is-up-select' checked='checked'><br></td></tr>";
                            }else{
                                one_vendor_info +=   "<input type='checkbox' name='is-up-select'><br></td></tr>";
                            }
                        }
                        $(".tbody-vendor-price-list").append(one_vendor_info);
                    }

                    $("input[name=active_end_time]").val(vendorinfo[0]['active_end_time']);//价格有效期
                    // 资源分类
                    $('input[name=belong_type]').each(function(){
                        if($(this).val()==vendorinfo[0]['belong_type']){
                            $(this).attr("checked",true);
                        }
                    });
                    // 配合度
                    $('input[name=cooperate_level]').each(function(){
                        if($(this).val()==vendorinfo[0]['cooperate_level']){
                            $(this).attr("checked",true);
                        }
                    });
                    // 账期
                    $('input[name=account_period]').each(function(){
                        if($(this).val()==vendorinfo[0]['account_period']){
                            $(this).attr("checked",true);
                        }
                    });
                    // 设置为首选供应商
                    $('input[name=is_pref_vendor]').each(function(){
                        if($(this).val()==vendorinfo[0]['is_pref_vendor']){
                            $(this).attr("checked",true);
                        }
                    });
                    //审核状态
                    $('select[name=status]').children('option').each(function(){
                        if($(this).val()==vendorinfo[0]['status']){
                            $(this).attr("selected",true);
                        }
                    });
                    //是否为首选媒体主
                    $("input[name=is_pref_vendor]").removeAttr("disabled");
                    $('select[name=status]').removeAttr("disabled");
                    if($("select[name=status]").children("option:selected").val() != 1){
                        $("input[name=is_pref_vendor]").attr("disabled",true);
                    }else{
                        $("input[name=is_pref_vendor]").removeAttr("disabled");
                    }

                    $(".media-vendor-detail .legend-vendor-name").children("span").text(vendorinfo[0]['name']);
                    $(".media-vendor-detail .legend-vendor-name").children("span").attr("data-uuid",vendorinfo[0]['bind_uuid']);
                    $(".media-vendor-detail").css("display","block");
                } else {
                    swal({title: "系统出错", text: "请联系系统管理员", type: "error"});
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                swal({title: "系统出错", text: "请联系系统管理员", type: "error"});
            }
        });
    });

    ////审核媒体主时的逻辑判断js
    $("select[name=status]").change(function(){
        if($(this).val() != 1){
            $("input[name=is_pref_vendor]").attr("disabled",true);
            $("input[name=is_pref_vendor]").each(function(){
                ($(this).val()==0)? $(this).attr("checked",true):$(this).removeAttr("checked");
            });
            $("input[name=is-up-select]").each(function(){
                $(this).removeAttr("checked");
                $(this).attr("disabled","disabled");
            });
        }else{
            $("input[name=is_pref_vendor]").removeAttr("disabled");
            $("input[name=is-up-select]").removeAttr("disabled");
        }
    });

     //同步价格有效期
    $('.sync-latest-active-end-time').on('click', function(){
        var vendor_bind_uuid =  $(".media-vendor-detail .legend-vendor-name").children("span").data("uuid");;
        var url = $("input[name=get_vendor_active]").val();
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {bind_uuid: vendor_bind_uuid},
            success: function (resp) {
                if(resp.err_code == 0){
                    var vendor = resp.vendorinfo;
                    if(vendor['active_end_time'] != ''){
                        $('input[name=active_end_time]').val(vendor['active_end_time']);
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

    //移除媒体主
    $('body').on('click', '.btn-vendor-to-delete', function(){
        var bind_uuid = $(this).closest('tr').attr('data-uuid');
        var is_pref_vendor = $(this).closest('tr').attr('data-is-prefer');
        var url = $("input[name=delete_vendor]").val();
        if(is_pref_vendor == 1){
            swal('操作失败!', '注:首选媒体主不能移除,只有该账号其他媒体主设置为首选后,该媒体主才能移除', 'error');
            return false;
        }
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
                url: url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {bind_uuid: bind_uuid},
                success: function (resp) {
                    if(resp.err_code == 0){
                        $('.audit-vendor').click();
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

    //是否上架
    $("body").on("click","input[name=is-up-select]",function(){
        var platform_status = $(this).closest("tr").attr("audit_status");
        if(platform_status != 1){
            swal({title: "该媒体主对应的资源未审核！", text: "", type: "warning",timer:2000});
            return false;
        }
    });

    //审核媒体主保存操作
    var vendor_price_json = {};
    $(".btn-audit-vendor-save").click(function(){
        var url = $(this).data('url');
        var bind_uuid =  $(".media-vendor-detail .legend-vendor-name").children("span").data("uuid");
        var video_uuid = $(this).data('uuid');
        var status = $("select[name=status]").children("option:selected").val();
        var is_pref_vendor =  $("input[name=is_pref_vendor]:checked").val();
        var active_end_time = $("input[name=active_end_time]").val();
        var belong_type =  $("input[name=belong_type]:checked").val();
        var cooperate_level =  $("input[name=cooperate_level]:checked").val();
        var account_period =  $("input[name=account_period]:checked").val();
        $(".tbody-vendor-price-list").children("tr").each(function(){
            if($(this).children("td").find("input[name=is-up-select]").is(":checked") && status == 1){
                var is_put = 1;
            }else{
                var is_put = 0;
            }
            var vendor_price_info = {};
            vendor_price_info['is_main'] = $(this).attr("is_main");
            vendor_price_info['platform_type']  = $(this).data("type");
            vendor_price_info['orig_one']  = $(this).children("td").find("input[name=orig_one]").val();
            vendor_price_info['orig_two']  = $(this).children("td").find("input[name=orig_two]").val();
            vendor_price_info['retail_one']  = $(this).children("td").find("input[name=retail_one]").val();
            vendor_price_info['retail_two']  = $(this).children("td").find("input[name=retail_two]").val();
            vendor_price_info['execute_one']  = $(this).children("td").find("input[name=execute_one]").val();
            vendor_price_info['execute_two']  = $(this).children("td").find("input[name=execute_two]").val();
            vendor_price_info['is_put']  = is_put;
            var mydate = new Date();
            var key = mydate.getTime();
            vendor_price_json[key] = vendor_price_info;
        });
        if(active_end_time==""){
            swal({title: "价格有效期未填！", text: "", type: "error",timer:1000});
            return false;
        }
        //console.log(vendor_price_json);
        swal({
            title: "确定提交修改?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: false
        },
        function(){
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    video_uuid:video_uuid,
                    bind_uuid:bind_uuid,
                    status:status,
                    is_pref_vendor:is_pref_vendor,
                    belong_type:belong_type,
                    cooperate_level:cooperate_level,
                    account_period:account_period,
                    active_end_time:active_end_time,
                    vendor_price_json:vendor_price_json,
                },
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "审核失败！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }else{
                        $.ajax({//无首选供应商时资源下架
                            url: '/index.php?r=video/media/down-media',
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {video_uuid:video_uuid},
                            success: function (resp) {
                                if(resp.err_code == 1){
                                    swal({title: "修改失败！", text: "请联系系统管理员", type: "error"});
                                    return false;
                                }else{
                                    swal({title: "审核成功！", text: "", type: "success"});
                                    //window.open("index.php?r=video/media/list");
                                    window.opener = null;
                                    window.open('', '_self');
                                    window.close();
                                }
                            },
                            error: function (XMLHttpRequest, msg, errorThrown) {
                                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                                return false;
                            }
                        });
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    return false;
                }
            });
        });
    });

    //审核资源中的下一步
    $(".btn-update-media-video").click(function(){
        var url = $(this).data('url');
        var video_uuid = $(this).data('uuid');
        var nickname = $("input[name=nickname]").val();
        var realname = $("input[name=realname]").val();
        var sex = $("select[name=sex]").children("option:selected").val();
        var main_platform = $(".tbody-video-platform-list").find("input[name=is_main]:checked").closest("tr").attr("platform-type");
        var coop_remark = $("textarea[name=coop_remark]").val();
        var cate ="#";
        $("input[name=media_cate]:checked").each(function(){
            cate+=$(this).val()+"#";
        });
        var area ="#";
        $("input[name=address]:checked").each(function(){
            area+=$(this).val()+"#";
        });
        if(nickname==''|| cate=="#" || area=="#"){
            swal({title: "存在必填项未填!", text: "", type: "error"});
            return false;
        }
        //是否有主打平台判断
        var main_platform_num = $(".tbody-video-platform-list").children("tr").find("input:checked").length;
        if(main_platform_num <=0){
            swal({title: "未设置主打平台，无法审核!", text: "", type: "warning"});
            return false;
        }
        swal({
                title: "确定提交修改?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                closeOnConfirm: false
            },
            function(){
                $.ajax({
                    url: url,
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {
                        video_uuid:video_uuid,
                        nickname:nickname,
                        realname:realname,
                        coop_remark:coop_remark,
                        main_platform:main_platform,
                        sex:sex,
                        cate:cate,
                        area:area
                    },
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "审核失败！", text: "请联系系统管理员", type: "error"});
                            return false;
                        }else{
                            swal({title: "审核成功！", text: "", type: "success",timer:1000});
                            $(".audit-vendor").click();
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                });
            });
    });

    //地域默认全国
    var area_defult = 0;
    $(".one-area").each(function(){
        if($(this).is(":checked")){
            area_defult+=1;
        }
    });
    if(area_defult ==0){
        $(".area-0").attr("checked",true);
    }

    //时间插件
    $('.active_end_time').daterangepicker({
        'singleDatePicker': true,
        'format': 'YYYY-MM-DD',
        'autoApply': false,
        'opens': 'center',
        'timePicker': false,
        'timePicker24Hour': false,
        'startDate' : new Date()
    });

    //地域单选
    $(".one-area").click(function(){
        $(".one-area").not(this).removeAttr("checked");
    });

    platformPriceTransfer('one');
    platformPriceTransfer('two');
    //视频平台价格系数转换
    function platformPriceTransfer(selector_type){
        $('body').on('input',"input[name=orig_"+selector_type+"]",function(){
            var orig_price = $(this).val();
            if(orig_price>=50000){
                var retail_price = (orig_price*1.5).toFixed(2);
                // var execute_price =(orig_price*1.5).toFixed(2);
            }
            if(orig_price>20000 &&orig_price<50000 ){
                var retail_price = (orig_price*1.7).toFixed(2);
                // var execute_price = (orig_price*1.7).toFixed(2);
            }
            if(orig_price<=20000 ){
                var retail_price = (orig_price*2).toFixed(2);
                // var execute_price = (orig_price*2).toFixed(2);
            }
            $(this).closest('tr').find("input[name=retail_"+selector_type+"]").val(retail_price);
            $(this).closest('tr').find("input[name=execute_"+selector_type+"]").val(orig_price);
        });
    }


}