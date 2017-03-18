
//微博列表页js
function weiboList(){
    //分页处理样式
    $(".pagination li a").each(function(){
        $(this).removeAttr("href");
        $(this).attr("style","cursor:pointer;");
    });
    //分页处理
    $(".pagination li a").click(function(){
        $(".main-stage .weibo-form input.page").attr("value", $(this).attr("data-page"));
        $(".main-stage .weibo-form").submit();
    });

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
    var search_type = $("input[name=search_type]").val();
    if(search_type ==0){//全部资源
        if(!$('#weibo .weibo-media-manage .weibo-media-list').hasClass('active')){
            $('.menu-level-1').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-3').each(function(){
                $(this).removeClass('active');
            });
            $('#weibo.menu-level-1').addClass('active');
            $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage').addClass('active');
            $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage .menu-level-3.weibo-media-list').addClass('active');
        }

    }
    if(search_type ==1){//待审核
        if(!$('#weibo .weibo-media-manage .weibo-to-verify-list').hasClass('active')){
            $('.menu-level-1').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-3').each(function(){
                $(this).removeClass('active');
            });
            $('#weibo.menu-level-1').addClass('active');
            $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage').addClass('active');
            $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage .menu-level-3.weibo-to-verify-list').addClass('active');
        }
    }

    if(search_type ==2){//审核未通过
        if(!$('#weibo .weibo-media-manage .weibo-success-list').hasClass('active')){
             $('.menu-level-1').each(function(){
                 $(this).removeClass('active');
             });
             $('.menu-level-2').each(function(){
                 $(this).removeClass('active');
             });
             $('.menu-level-3').each(function(){
                 $(this).removeClass('active');
             });
             $('#weibo.menu-level-1').addClass('active');
             $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage').addClass('active');
             $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage .menu-level-3.weibo-success-list').addClass('active');
         }
    }

    if(search_type ==3){
        if(!$('#weibo .weibo-media-manage .weibo-fail-list').hasClass('active')){
            $('.menu-level-1').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-3').each(function(){
                $(this).removeClass('active');
            });
            $('#weibo.menu-level-1').addClass('active');
            $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage').addClass('active');
            $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage .menu-level-3.weibo-fail-list').addClass('active');
        }
    }
   if(search_type ==4){
        if(!$('#weibo .weibo-media-manage .weibo-update-list').hasClass('active')){
            $('.menu-level-1').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-3').each(function(){
                $(this).removeClass('active');
            });
            $('#weibo.menu-level-1').addClass('active');
            $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage').addClass('active');
            $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage .menu-level-3.weibo-update-list').addClass('active');
        }
    }

    //搜索
    $(".weibo-form .btnSearch").click(function(){
        $(".weibo-form").submit();
    });

    //修改
    $(".btn-update").click(function(){
        var url = $(this).data('url');
        var weibo_uuid = $(this).data('uuid');
        window.open(url+"&uuid="+weibo_uuid);
        //window.location.href = url+"&uuid="+weibo_uuid;
    });

    //上下架
    $(".btn-put").click(function(){
        var type = $(this).data('type');
        var url = $(this).data('url');
        var weibo_uuid = $(this).data('uuid');
        if(type == "down"){//下架
            swal({
                    title: "确认下架并解除首选供应商！",
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
                        data: {weibo_uuid:weibo_uuid,type:type},
                        success: function (resp) {
                            if(resp.err_code == 1){
                                swal({title: "下架失败！", text: "请联系系统管理员", type: "error"});
                                return false;
                            }else{
                                swal({title: "下架成功！", text: "", type: "success"});
                                window.location.reload();
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                            return false;
                        }
                    });
                });
        }else{//上架
            swal({
                    title: "确认上架！",
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
                        data: {weibo_uuid:weibo_uuid,type:type},
                        success: function (resp) {
                            if(resp.err_code == 1){
                                swal({title: resp.err_msg, text:"", type: "error"});
                                return false;
                            }else{
                                swal({title: "上架成功！", text: "", type: "success"});
                                window.location.reload();
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                            return false;
                        }
                    });
            });
        }


    });

    //置顶
    $(".btn-top").click(function(){
        var weibo_uuid = $(this).data('uuid');
        var url = $(this).data('url');
        var type = $(this).data('type');
        swal({
            title: "确定该资源置顶/取消置顶?",
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
                data: {weibo_uuid:weibo_uuid,type:type},
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "置顶/取消置顶失败！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }else{
                        swal({title: "置顶/取消置顶成功！", text: "", type: "success"});
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

    //主推
    $(".btn-push").click(function(){
        var weibo_uuid = $(this).data('uuid');
        var url = $(this).data('url');
        var type = $(this).data('type');
        swal({
                title: "确定该资源主推/取消主推?",
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
                    data: {weibo_uuid:weibo_uuid,type:type},
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "主推/取消主推失败！", text: "请联系系统管理员", type: "error"});
                            return false;
                        }else{
                            swal({title: "主推/取消主推成功！", text: "", type: "success"});
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


    //报价查询类型
    $("select[name=price_search_type]").change(function(){
        var type = $(this).val();
        if(type == -1){
            $(".price_start").css("display","none");
            $(".price_end").css("display","none");
            $("input[name=price_start]").val("");
            $("input[name=price_end]").val("");
        }else{
            $(".price_start").css("display","block");
            $(".price_end").css("display","block");
        };
    });

    //删除微博资源
    $(".btn-delete").click(function(){
        var weibo_uuid = $(this).data("uuid");
        var url = $(this).data("url");
        swal({
            title: '确认删除该资源么？',
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
                data: {weibo_uuid: weibo_uuid},
                success: function (resp) {
                    if(resp.err_code == 0){
                        swal('删除成功！', '', 'success');
                        window.location.reload();
                    } else if(resp.err_code == 1){
                        swal('', '删除失败!', 'error');
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "", text: "系统出错！", type: "error"});
                }
            });
        });

    });


    //过期时间插件
    $('.expire-time').daterangepicker({
        'singleDatePicker': true,
        'format': 'YYYY-MM-DD',
        'autoApply': false,
        'opens': 'center',
        //'drops': 'down',
        'timePicker': false,
        'timePicker24Hour': false,
        'startDate' : new Date()
    });



}


//微博详情页js
function mediaDetail(){

    //审核资源信息
    $(".btn-update-base").click(function(){
        var weibo_uuid = $(this).data('uuid');
        var url = $(this).data('url');
        var weibo_name = $("input[name=weibo_name]").val();
        var follower_num = $("input[name=follower_num]").val();
        var weibo_url = $("input[name=weibo_url]").val();
        var intro = $("textarea[name=intro]").val();
        var accept_remark = $("textarea[name=accept_remark]").val();
        var media_level = $("select[name=media_level]").children("option:selected").val();
        var audit_status = $("select[name=audit_status]").children("option:selected").val();
        var cate ="#";
        $("input[name=media_cate]:checked").each(function(){
            cate+=$(this).val()+"#";
        });
        var area ="#";
        $("input[name=follower_area]:checked").each(function(){
            area+=$(this).val()+"#";
        });
        if(weibo_url=='' || follower_num=='' || weibo_name=='' || cate=="#"|| area=="#"){
            swal({title: "存在必填项未填!", text: "", type: "error"});
            return false;
        }
        //资源待审核时状态改为下架
        var type = $("select[name=audit_status]").children("option:selected").val();
        if(type !=1){
            $.ajax({
                url: '/index.php?r=weibo/media/put-up-down',
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {weibo_uuid:weibo_uuid,type:"down"},
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "系统出错！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    return false;
                }
            });
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
                        weibo_uuid:weibo_uuid,
                        weibo_name:weibo_name,
                        follower_num:follower_num,
                        weibo_url:weibo_url,
                        media_level:media_level,
                        intro:intro,
                        accept_remark:accept_remark,
                        audit_status:audit_status,
                        cate:cate,
                        area:area
                    },
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "修改失败！", text: "请联系系统管理员", type: "error"});
                            return false;
                        }else{
                            swal({title: "修改成功！", text: "", type: "success",timer:1000});
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


    //审核媒体主操作
    $(".btn-update-audit").click(function(){
        var bind_uuid =  $(".panel-body-vendor-info .vendor-name").children("span").data("uuid");
        var url = $(this).data('url');
        var weibo_uuid = $(this).data('uuid');
        var status = $("select[name=status]").children("option:selected").val();
        var is_top =  $("input[name=is_top]:checked").val();
        var is_put =  $("input[name=is_put]:checked").val();
        var is_pref_vendor =  $("input[name=is_pref_vendor]:checked").val();
        var active_end_time = $("input[name=active_end_time]").val();
        var belong_type =  $("input[name=belong_type]:checked").val();
        var cooperate_level =  $("input[name=cooperate_level]:checked").val();
        var account_period =  $("input[name=account_period]:checked").val();
        var s_d_orig = $("input[name=soft_d_orig]").val();
        var s_t_orig = $("input[name=soft_t_orig]").val();
        var m_d_orig = $("input[name=mic_d_orig]").val();
        var m_t_orig = $("input[name=mic_t_orig]").val();
        var s_d_retail = $("input[name=soft_d_retail]").val();
        var s_t_retail = $("input[name=soft_t_retail]").val();
        var m_d_retail = $("input[name=mic_d_retail]").val();
        var m_t_retail = $("input[name=mic_t_retail]").val();
        var s_d_execute = $("input[name=soft_d_execute]").val();
        var s_t_execute = $("input[name=soft_t_execute]").val();
        var m_d_execute = $("input[name=mic_d_execute]").val();
        var m_t_execute = $("input[name=mic_t_execute]").val();
        if(active_end_time==""){
            swal({title: "价格有效期未填！", text: "", type: "error",timer:1000});
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
                        weibo_uuid:weibo_uuid,
                        bind_uuid:bind_uuid,
                        status:status,
                        is_top:is_top,
                        is_put:is_put,
                        is_pref_vendor:is_pref_vendor,
                        belong_type:belong_type,
                        cooperate_level:cooperate_level,
                        account_period:account_period,
                        active_end_time:active_end_time,
                        s_d_orig:s_d_orig,
                        s_t_orig:s_t_orig,
                        m_d_orig:m_d_orig,
                        m_t_orig:m_t_orig,
                        s_d_retail:s_d_retail,
                        s_t_retail:s_t_retail,
                        m_d_retail:m_d_retail,
                        m_t_retail:m_t_retail,
                        s_d_execute:s_d_execute,
                        s_t_execute:s_t_execute,
                        m_d_execute:m_d_execute,
                        m_t_execute:m_t_execute,
                    },
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "审核失败！", text: "请联系系统管理员", type: "error"});
                            return false;
                        }else{
                            $.ajax({//无首选供应商时资源下架
                                url: '/index.php?r=weibo/media/down-media',
                                type: 'POST',
                                cache: false,
                                dataType: 'json',
                                data: {weibo_uuid:weibo_uuid},
                                success: function (resp) {
                                    if(resp.err_code == 1){
                                        swal({title: "修改失败！", text: "请联系系统管理员", type: "error"});
                                        return false;
                                    }else{
                                        swal({title: "审核成功！", text: "", type: "success"});
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


    //取消
    $(".btn-cancel").click(function(){
        window.opener = null;
        window.open('', '_self');
        window.close();
    });

    //审核媒体主中添加媒体主button
    $(".btn-to-add-vendor").click(function(){
        $(".nav .add-vendor").click();
    });

    //添加媒体主button
    $(".nav .add-vendor").click(function(){
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
        var media_uuid = $('input[name=media_uuid]').val();
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
        if(media_uuid == '' || vendor_uuid == ''){
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
                data: {media_uuid: media_uuid, vendor_uuid: vendor_uuid},
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
        $(".panel-body-vendor-info").css("display","none");
        var media_uuid = $('input[name=media_uuid]').val();
        var this_modal = $('#default-tab-2');
        var url = $(this).data("url");
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {media_uuid: media_uuid},
            success: function (resp) {
                if(resp.err_code == 0){
                    $('.table-vendor tbody').find('tr').remove();
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
                        var is_put = vendor['is_put'];
                        //资源上下架
                        (is_put == 1)?is_put ="上架":is_put ="下架";
                        // 媒体主名称
                        if(vendor_name == ''){
                            vendor_name = '未填写';
                        }
                        //注册渠道
                        if(register_type == 1){
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
                            var oneLine = '<tr data-is-prefer="' + is_pref_vendor + '" data-uuid="' + bind_uuid + '" id="' + bind_uuid + '"><td>' + (i + 1) + '</td><td>' + vendor_name + '</td><td>' + register_type_label + '</td><td>' + contact_person + '</td><td>' + contacts + '</td><td class="is-pref-vendor-label">' + is_pref_vendor_label + '</td><td>' + is_put + '</td><td>' + bind_status_label + '</td><td>' + action_cell + '</td></tr>';
                        } else {
                            var is_pref_vendor_label = '是';
                            var oneLine = '<tr data-is-prefer="' + is_pref_vendor + '" data-uuid="' + bind_uuid + '" id="' + bind_uuid + '" style="color: #ef0a0a"><td>' + (i + 1) + '</td><td>' + vendor_name + '</td><td>' + register_type_label + '</td><td>' + contact_person + '</td><td>' + contacts + '</td><td class="is-pref-vendor-label">' + is_pref_vendor_label + '</td><td>' +is_put + '</td><td>' + bind_status_label + '</td><td>' + action_cell + '</td></tr>';
                        }
                        $('.table-vendor tbody').append(oneLine);
                    }
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
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


    // 列表页里的"审核"按钮
    $('body').on('click', '.btn-vendor-to-verify', function(){
        var bind_uuid = $(this).attr('data-uuid');
        var is_prefer = $(this).closest("tr").attr('data-is-prefer');
        var url = $("input[name=get_vendor_info]").val();
        // 获取媒体基本信息
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {bind_uuid: bind_uuid},
            success: function (resp) {
                if(resp.err_code == 0){
                    var vendorinfo = resp.vendorinfo;
                    //价格
                    $("input[name=soft_d_orig]").val(vendorinfo['soft_direct_price_orig']);
                    $("input[name=soft_t_orig]").val(vendorinfo['soft_transfer_price_orig']);
                    $("input[name=mic_d_orig]").val(vendorinfo['micro_direct_price_orig']);
                    $("input[name=mic_t_orig]").val(vendorinfo['micro_transfer_price_orig']);
                    $("input[name=soft_d_retail]").val(vendorinfo['soft_direct_price_retail']);
                    $("input[name=soft_t_retail]").val(vendorinfo['soft_transfer_price_retail']);
                    $("input[name=mic_d_retail]").val(vendorinfo['micro_direct_price_retail']);
                    $("input[name=mic_t_retail]").val(vendorinfo['micro_transfer_price_retail']);
                    $("input[name=soft_d_execute]").val(vendorinfo['soft_direct_price_execute']);
                    $("input[name=soft_t_execute]").val(vendorinfo['soft_transfer_price_execute']);
                    $("input[name=mic_d_execute]").val(vendorinfo['micro_direct_price_execute']);
                    $("input[name=mic_t_execute]").val(vendorinfo['micro_transfer_price_execute']);
                    $("input[name=active_end_time]").val(vendorinfo['active_end_time']);//价格有效期
                    // 资源分类
                    $('input[name=belong_type]').each(function(){
                        if($(this).val()==vendorinfo['belong_type']){
                            $(this).attr("checked",true);
                        }
                    });
                    // 配合度
                    $('input[name=cooperate_level]').each(function(){
                        if($(this).val()==vendorinfo['cooperate_level']){
                            $(this).attr("checked",true);
                        }
                    });
                    // 账期
                    $('input[name=account_period]').each(function(){
                        if($(this).val()==vendorinfo['account_period']){
                            $(this).attr("checked",true);
                        }
                    });
                    // 设置为首选供应商
                    $('input[name=is_pref_vendor]').each(function(){
                        if($(this).val()==vendorinfo['is_pref_vendor']){
                            $(this).attr("checked",true);
                        }
                    });
                    // 是否上架
                    $('input[name=is_put]').each(function(){
                        if($(this).val()==vendorinfo['is_put']){
                            $(this).attr("checked",true);
                        }
                    });
                    // 是否置顶
                    $('input[name=is_top]').each(function(){
                        if($(this).val()==vendorinfo['is_top']){
                            $(this).attr("checked",true);
                        }
                    });
                    //审核状态
                    $('select[name=status]').children('option').each(function(){
                        if($(this).val()==vendorinfo['status']){
                            $(this).attr("selected",true);
                        }
                    });
                    //是否为首选媒体主
                    $("input[name=is_top]").removeAttr("disabled");
                    $("input[name=is_put]").removeAttr("disabled");
                    $("input[name=is_pref_vendor]").removeAttr("disabled");
                    $('select[name=status]').removeAttr("disabled");
                    if(is_prefer==1){
                        $(".is_top").css("display","block");
                        $(".is_put").css("display","block");
                        $(".label-is-top").css("display","block");
                        $(".label-is-put").css("display","block");
                    }else{
                        $(".is_top").css("display","none");
                        $(".is_put").css("display","none");
                        $(".label-is-top").css("display","none");
                        $(".label-is-put").css("display","none");
                    }
                    if($("select[name=status]").children("option:selected").val() != 1){
                        $("input[name=is_pref_vendor]").attr("disabled",true);
                    }else{
                        $("input[name=is_pref_vendor]").removeAttr("disabled");
                    }
                    $(".panel-body-vendor-info .vendor-name").children("span").text(vendorinfo['name']);
                    $(".panel-body-vendor-info .vendor-name").children("span").attr("data-uuid",vendorinfo['uuid']);
                    $(".panel-body-vendor-info").css("display","block");
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
            $("input[name=is_top]").attr("disabled",true);
            $("input[name=is_put]").attr("disabled",true);
            $("input[name=is_pref_vendor]").attr("disabled",true);
            $("input[name=is_pref_vendor]").each(function(){
                ($(this).val()==0)? $(this).attr("checked",true):$(this).removeAttr("checked");
            });
            $(".is_top").css("display","none");
            $(".is_put").css("display","none");
            $(".label-is-top").css("display","none");
            $(".label-is-put").css("display","none");
        }else{
            $("input[name=is_top]").removeAttr("disabled");
            $("input[name=is_put]").removeAttr("disabled");
            $("input[name=is_pref_vendor]").removeAttr("disabled");
        }
    });

    // 同步价格有效期
    $('.sync-latest-active-end-time').on('click', function(){
        var vendor_bind_uuid =  $(".panel-body-vendor-info .vendor-name").children("span").data("uuid");
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
                    console.log(vendor['active_end_time']);
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

    //审核媒体主设置首选媒体主
    $("input[name=is_pref_vendor]").click(function(){
        if($(this).val() == 1){
            $(".is_top").css("display","block");
            $(".is_put").css("display","block");
            $(".label-is-top").css("display","block");
            $(".label-is-put").css("display","block");
        }else{
            $(".is_top").css("display","none");
            $(".is_put").css("display","none");
            $(".label-is-top").css("display","none");
            $(".label-is-put").css("display","none");
        }
    });

    //是否上架
    $("input[name=is_put]").click(function(){
        if($(this).val() == 1){
            var media_status = $("select[name=audit_status]").children("option:selected").val();
            if(media_status != 1){
                swal({title: "该媒体主对应的资源未审核！", text: "", type: "warning",timer:1000});
                return false;
            }
        }
    });
    //是否置顶
    $("input[name=is_top]").click(function(){
        if($(this).val() == 1){
            var media_status = $("select[name=audit_status]").children("option:selected").val();
            if(media_status != 1){
                swal({title: "该媒体主对应的资源未审核！", text: "", type: "warning",timer:1000});
                return false;
            }
        }
    });

    //地域单选
    $(".one-area").click(function(){
        $(".one-area").not(this).removeAttr("checked");
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


    weiboPriceTransfer('soft_d');
    weiboPriceTransfer('soft_t');
    weiboPriceTransfer('mic_d');
    weiboPriceTransfer('mic_t');
    //微博价格系数转换
    function weiboPriceTransfer(selector_type){
        $('body').on('input',"input[name="+selector_type+"_orig]",function(){
            var orig_price = $(this).val();
            if(orig_price>8000){
                var retail_price = (orig_price*1.3).toFixed(2);
                // var execute_price =(orig_price*1.3).toFixed(2);
            }
            if(orig_price>3000 &&orig_price<=8000 ){
                var retail_price = (orig_price*1.5).toFixed(2);
                // var execute_price = (orig_price*1.5).toFixed(2);
            }
            if(orig_price>1000 &&orig_price<=3000 ){
                var retail_price = (orig_price*1.8).toFixed(2);
                // var execute_price = (orig_price*1.8).toFixed(2);
            }
            if(orig_price>200 &&orig_price<=1000 ){
                var retail_price = (orig_price*2.5).toFixed(2);
                // var execute_price = (orig_price*2.5).toFixed(2);
            }
            if(orig_price<=200){
                var retail_price = (orig_price*4).toFixed(2);
                // var execute_price = (orig_price*4).toFixed(2);
            }
            $("input[name="+selector_type+"_retail]").val(retail_price);
            $("input[name="+selector_type+"_execute]").val(orig_price);
        });
    }


}
