
//微博资源入驻js
function weiboCreate(){

    //侧边栏选中
    if(!$('#weibo .weibo-media-manage .weibo-media-create').hasClass('active')){
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
        $('#weibo.menu-level-1 .menu-level-2.weibo-media-manage .menu-level-3.weibo-media-create').addClass('active');
    }

    //地域单选
    $(".one-area").click(function(){
        $(".one-area").not(this).removeAttr("checked");
    });

    //资源分类最多6个
    $("input[name=media_cate]").click(function(){
        var cate_length = $("input[name=media_cate]:checked").length;
        if(cate_length>6){
            swal({title: "", text: "最多选6个资源分类", type: "error"});
            return false;
        }
    });

    // 同步价格有效期
    $('.sync-latest-active-end-time').on('click', function(){
        var vendor_uuid = $(".vendor-select:checked").parents("tr").data("vendor");
        var url = $("input[name=get_vendor_active_time]").val();
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {vendor_uuid: vendor_uuid},
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
                        $('.panel-body-vendor-info').hide();
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

    //新建媒体主快捷按钮
    $(".btn-create-vendor").click(function(){
        var url = $(this).data("url");
        window.open(url);
    });

    // 添加媒体主 - 选择媒体主的复选框
    $('body').on('click', '.vendor-select', function() {
        if($(this).is(':checked')){
            $('.vendor-search-result tr').hide();
            $(this).closest('tr').show();
            $(".price-info-tbody input").val("0");//清空报价信息
            $("input[name=active_end_time]").val("");
            $("input[name=belong_type]").each(function(){
                if($(this).val()==0){
                    $(this).attr("checked",true);
                }
            });
            $("input[name=account_period]").each(function(){
                if($(this).val()==0){
                    $(this).attr("checked",true);
                }
            });
            $("input[name=cooperate_level]").each(function(){
                if($(this).val()==0){
                    $(this).attr("checked",true);
                }
            });
            $(".panel-body-vendor-info").show();
        } else{
            $('.vendor-search-result tr').show();
            $(".panel-body-vendor-info").hide();
        }
        $(".vendor-select").not(this).attr("checked", false);
    });



    // 添加媒体主 - 保存
    var vendor_info_json = {};
    $('.btn-add-vendor').on('click', function(){
        var vendor_info = {};
        var vendor_uuid = $(".vendor-select:checked").parents("tr").data("vendor");
        var vendor_name = $(".vendor-select:checked").parents("tr").data("name");
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
        var belong_type = $("input[name=belong_type]:checked").val();
        var cooperate_level = $("input[name=cooperate_level]:checked").val();
        var account_period = $("input[name=account_period]:checked").val();
        var active_end_time = $("input[name=active_end_time]").val();
        vendor_info['vendor_uuid'] = vendor_uuid;
        vendor_info['s_d_orig'] = s_d_orig;
        vendor_info['s_t_orig'] = s_t_orig;
        vendor_info['m_d_orig'] = m_d_orig;
        vendor_info['m_t_orig'] = m_t_orig;
        vendor_info['s_d_retail'] = s_d_retail;
        vendor_info['s_t_retail'] = s_t_retail;
        vendor_info['m_d_retail'] = m_d_retail;
        vendor_info['m_t_retail'] = m_t_retail;
        vendor_info['s_d_execute'] = s_d_execute;
        vendor_info['s_t_execute'] = s_t_execute;
        vendor_info['m_d_execute'] = m_d_execute;
        vendor_info['m_t_execute'] = m_t_execute;
        vendor_info['belong_type'] = belong_type;
        vendor_info['cooperate_level'] = cooperate_level;
        vendor_info['account_period'] = account_period;
        vendor_info['active_end_time'] = active_end_time;
        vendor_info_json[vendor_uuid] = vendor_info;

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
        if(vendor_uuid == ''){
            swal({title: "", text: "获取媒体主信息失败", type: "error"});
            return false;
        }
        if(active_end_time == ''){
            swal({title: "价格有效期未填！", text: "", type: "error"});
            return false;
        }
        var is_must_null = "";
        $(".table-price-set input").each(function(){
            if($(this).val()==""){
                is_must_null = 1;
            }
        });
        if(is_must_null==1){
            swal({title: "价格必填项未填!", text: "", type: "error"});
            return false;
        }

        console.log(vendor_info_json);
        var price_label =   "软广直发："+s_d_orig+
                            "<br>软广转发："+s_t_orig+
                            "<br>微任务直发："+m_d_orig+
                            "<br>微任务转发："+m_t_orig;
        var one_vendor =  "<tr data-uuid="+vendor_uuid+">";
        var length =  $('.tbody-vendor').children("tr").length;
        if(length==0){
            one_vendor+= "<th><input name='pref_vendor' type='radio' checked='true'></th>";
        }else{
            one_vendor+= "<th><input name='pref_vendor' type='radio'></th>";
        }
        one_vendor+= "<th>"+vendor_name+"</th>" +
                     "<th>"+price_label+"</th>" +
                     "<th>"+active_end_time+"</th>" +
                     "<th><span class='btn-delete' style='color:red;cursor:pointer;'>删除</span></th>" +
                     "</tr>";
        $('.tbody-vendor').append(one_vendor);
        $('.create-media').click();
    });

    //删除所选媒体主
    $('body').on('click','.btn-delete',function(){
        console.log(vendor_info_json);
        var remove_tr = $(this).closest('tr');
        var vendor_uuid = remove_tr.data('uuid');
        swal({
            title: '',
            text: '确认删除该媒体主么？',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            closeOnConfirm: true
        },function () {
            delete vendor_info_json[vendor_uuid];
            remove_tr.remove();
        });
    });

    ////首选媒体主的radio切换
    $("body").on('click','input[name=pref_vendor]',function(){
        $("input[name=pref_vendor]").not(this).attr("checked",false);
    });


    //入驻资源的保存
    $(".btn-update-base").click(function(){
        var url = $(this).data('url');
        var weibo_name = $("input[name=weibo_name]").val();
        var follower_num = $("input[name=follower_num]").val();
        var weibo_url = $("input[name=weibo_url]").val();
        var media_level = $("select[name=media_level]").children("option:selected").val();
        var intro = $("textarea[name=intro]").val();
        var accept_remark = $("textarea[name=accept_remark]").val();
        var pref_vendor = $("input[name=pref_vendor]:checked").closest("tr").data("uuid");
        var cate ="#";
        $("input[name=media_cate]:checked").each(function(){
            cate+=$(this).val()+"#";
        });
        var area ="#";
        $("input[name=follower_area]:checked").each(function(){
            area+=$(this).val()+"#";
        });
        if(weibo_url=='' || follower_num=='' || weibo_name=='' || cate=="#"|| area=="#"){
            swal({title: "存在必填项未填!", text: "", type: "warning"});
            return false;
        }
        var is_prefer_vendor_checked = "";
        $("input[name='pref_vendor']").each(function(){
            if($(this).is(':checked')){is_prefer_vendor_checked += $(this).val() + '#'; }
        });
        if(is_prefer_vendor_checked == ""){
            swal({title: "请选择首选媒体主!", text: "", type: "warning"});
            return false;
        }
        swal({
                title: "确定入驻账号?",
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
                        vendor_info_json:vendor_info_json,
                        pref_vendor:pref_vendor,
                        weibo_name:weibo_name,
                        follower_num:follower_num,
                        weibo_url:weibo_url,
                        media_level:media_level,
                        intro:intro,
                        accept_remark:accept_remark,
                        cate:cate,
                        area:area
                    },
                    beforeSend: function () {
                        //让提交按钮失效，以实现防止按钮重复点击
                        $(".btn-update-base").attr('disabled', 'disabled');
                    },
                    complete: function () {
                         //按钮重新有效
                        $(".btn-update-base").removeAttr('disabled');
                    },
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "保存失败！", text: "请联系系统管理员", type: "error"});
                            return false;
                        }else{
                            swal({title: "入驻成功！", text: "", type: "success"});
                            //window.location.reload();
                            window.location.href = "/index.php?r=weibo/media/list";
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                });
        });

    });

    //检查微博账号是否存在
    $("input[name=weibo_name]").blur(function(){
        var weibo_name = $(this).val();
        $.ajax({
            url: '/index.php?r=weibo/media/check-weibo-name',
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {weibo_name: weibo_name},
            success: function (resp) {
                if(resp.err_code == 1){
                    swal({title: "该微博系统已经存在，请勿重复录入!", text: "", type: "error"});
                    return false;
                } else {
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                return false;
            }
        });
    });

    //微博资源的添加媒体主button
    $(".btn-to-add-vendor").click(function(){
        $(".nav .add-vendor").click();
    });

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