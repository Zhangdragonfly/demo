//微博资源入驻js
function videoCreate(){
    // 控制左侧导航选中
    if(!$('#video .media-manage .to-create').hasClass('active')){
        $('.menu-level-1').each(function(){
            $(this).removeClass('active');
        });
        $('.menu-level-2').each(function(){
            $(this).removeClass('active');
        });
        $('.menu-level-3').each(function(){
            $(this).removeClass('active');
        });

        $('#video.menu-level-1').addClass('active');
        $('#video.menu-level-1 .menu-level-2.media-manage').addClass('active');
        $('#video.menu-level-1 .menu-level-2.media-manage .menu-level-3.to-create').addClass('active');
    }

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

    //清除平台信息缓存
    $(".add-platform-info").click(function(){
        $(".platform-info").children().find("input").val("");
        $(".platform-info").children().find("textarea").val("");
        $(".platform-info").children().find("select").val("-1");
    });

    //添加平台信息的保存
    var platform_info_json ={};
    $(".btn-save-platform").click(function(){
        var platform_info = {};
        var type = $("input[name=platform_type]:checked").data("type");                    //平台类型
        var platform = $("input[name=platform_type]:checked").parent("label").text();//平台名称
        var account_name = $(".account_name").val();
        var account_id = $(".account_id").val();
        var follower_num = $(".follower_num").val();
        var auth_status = $(".auth_status").children("option:selected").val();
        var url = $(".url").val();
        var avg_watch_sum = $(".avg_watch_num").val();
        var person_sign = $(".person_sign").val();
        var remark = $(".remark").val();
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
            var mydate = new Date();
            var platform_key = mydate.getTime();
            platform_info['type'] = type;
            platform_info['account_name'] = account_name;
            platform_info['account_id'] = account_id;
            platform_info['follower_num'] = follower_num;
            platform_info['auth_status'] = auth_status;
            platform_info['url'] = url;
            platform_info['avg_watch_sum'] = avg_watch_sum;
            platform_info['person_sign'] = person_sign;
            platform_info['remark'] = remark;
            platform_info_json[platform_key] = platform_info;
            if(auth_status == 1){ var auth = "已认证"};
            if(auth_status == 2){ var auth = "未认证"};
            if(auth_status == -1){ var auth = "未知"};
            var one_paltform = "<tr data-type="+type+" data-key="+platform_key+">" +
                "<td><label class='checkbox-inline'> <input type='checkbox' name='is_main_platform'>设为主打</label></td>" +
                "<td data-type="+type+">"+platform+"</td>" +
                "<td>"+account_name+"<br>"+account_id+"</td>" +
                "<td>"+follower_num+"</td>" +
                "<td>"+auth+"</td>" +
                "<td>"+url+"</td>" +
                "<td>"+person_sign+"</td>"+
                "<td>"+remark+"</td>" +
                "<td><button class='btn btn-primary btn-xs m-r-5 delete-platform-info' data-key="+platform_key+">移除</button></td>" +
            "</tr>";
            $(".add_paltform_list").append(one_paltform);
            $(".modal-header button").click();
        });
    });

    ////主打平台的radio切换
    $("body").on('click','input[name=is_main_platform]',function(){
        $("input[name=is_main_platform]").not(this).attr("checked",false);
    });

    //删除添加的平台信息
    $("body").on('click',".delete-platform-info",function(){
        var remove_tr = $(this).closest('tr');
        var platform_key =$(this).data('key');
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
            delete platform_info_json[platform_key];
            console.log(platform_info_json);
            remove_tr.remove();
        });
    });


    //////////////////////////////////////////////////添加自媒体主操作/////////////////////////////////////////////////

    //添加资源的添加媒体主button
    $(".add-vendor-info").click(function(){
        $(".add-vendor").click();
    });

    //添加媒体主button
    $(".nav .add-vendor").click(function(){
        //是否有主打平台判断
        var main_platform_num = $(".add_paltform_list").children("tr").find("input:checked").length;
        if(main_platform_num <=0){
            swal({title: "未设置主打平台，无法添加!", text: "", type: "warning"});
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
                        $('.panel-body-vendor-info').hide();
                    }
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown){
                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                return false;
            }
        });
        //自媒体报价信息类型（多平台）
        $(".price-info-tbody").children("tr").remove();
        var main_platform_key = $("input[name=is_main_platform]:checked").closest("tr").data("key");
        for(var key in platform_info_json){
            var platform_type = platform_info_json[key]['type'];
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
            if(main_platform_key == key){
                var one_vendor_info = "<tr is_main='1' data-type="+platform_type+" data-key="+key+">" ;

            }else{
                var one_vendor_info = "<tr is_main='0' data-type="+platform_type+" data-key="+key+">";
            }
            if(platform_type == 5){
                one_vendor_info += "<td style='line-height:65px;'>"+paltform_name+"</td>" +
                                    "<td><br>原创视频<br>视频转发</td>" +
                                    "<td><input name='orig_price_orig' type='text' class='form-control col-md-4'/><input name='tran_price_orig' type='text' class='form-control col-md-4'/></td>" +
                                    "<td><input name='orig_price_retail' type='text' class='form-control col-md-4'/><input name='tran_price_retail' type='text' class='form-control col-md-4'/></td>" +
                                    "<td><input name='orig_price_execute' type='text' class='form-control col-md-4'/><input name='tran_price_execute' type='text' class='form-control col-md-4'/></td>" +
                                    "</tr>";
            }else{
                one_vendor_info += "<td style='line-height:65px;'>"+paltform_name+"</td>" +
                                    "<td><br>线上直播<br>线下活动</td>" +
                                    "<td><input name='online_price_orig' type='text' class='form-control col-md-4'/><input name='offline_price_orig' type='text' class='form-control col-md-4'/></td>" +
                                    "<td><input name='online_price_retail' type='text' class='form-control col-md-4'/><input name='offline_price_retail' type='text' class='form-control col-md-4'/></td>" +
                                    "<td><input name='online_price_execute' type='text' class='form-control col-md-4'/><input name='offline_price_execute' type='text' class='form-control col-md-4'/></td>" +
                                    "</tr>";
            }
            $(".price-info-tbody").append(one_vendor_info);
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

    //enter键搜索
    $("input[name='search_vendor_name']").keydown(function(event){
        if(event.keyCode==13){
            $(".btn-vendor-search").click();
            return false;
        }
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

    //添加媒体主的添加button
    var vendor_info_json = {};//自媒体主信息json
    $(".btn-add-vendor").click(function(){
        var price_label = "";
        var vendor_info = {};
        var vendor_uuid = $(".vendor-select:checked").parents("tr").data("vendor");
        var vendor_name = $(".vendor-select:checked").parents("tr").data("name");
        var belong_type = $("input[name=belong_type]:checked").val();
        var cooperate_level = $("input[name=cooperate_level]:checked").val();
        var account_period = $("input[name=account_period]:checked").val();
        var active_end_time = $("input[name=active_end_time]").val();
        //自媒体主报价信json
        var price_json_info = {};
        $(".price-info-tbody").children("tr").each(function(){
            var price_info = {};
            var is_main = $(this).attr("is_main");
            var platform_type = $(this).data("type");
            var data_key = $(this).data("key");
            price_info['platform_type']  = platform_type;
            price_info['orig_one']  = $(this).children("td").eq(2).find("input").eq(0).val();
            price_info['orig_two']  = $(this).children("td").eq(2).find("input").eq(1).val();
            price_info['retail_one']  = $(this).children("td").eq(3).find("input").eq(0).val();
            price_info['retail_two']  = $(this).children("td").eq(3).find("input").eq(1).val();
            price_info['execute_one']  = $(this).children("td").eq(4).find("input").eq(0).val();
            price_info['execute_two']  = $(this).children("td").eq(4).find("input").eq(1).val();
            if(is_main == 1){//是否为主打平台
                price_info['is_main'] = 1;
                if(platform_type !=5){
                    price_label = "线上直播："+ price_info['orig_one']+"<br>线下活动："+ price_info['orig_two'];
                }else{
                    price_label = "原创视频："+ price_info['orig_one']+"<br>视频转发："+ price_info['orig_two'];
                }
            }else{
                price_info['is_main'] = 0;
            }
            price_json_info[data_key] = price_info;
        });
        vendor_info['vendor_uuid'] = vendor_uuid;
        vendor_info['vendor_name'] = vendor_name;
        vendor_info['belong_type'] = belong_type;
        vendor_info['cooperate_level'] = cooperate_level;
        vendor_info['account_period'] = account_period;
        vendor_info['active_end_time'] = active_end_time;
        vendor_info['price_json_info'] = price_json_info;
        vendor_info_json[vendor_uuid] = vendor_info;
        console.log(vendor_info_json);
        //提交判断
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
        $('.tbody-vendor-list').append(one_vendor);
        $('.create-media').click();
        //增加平台信息失效
        $(".add_paltform_list").children("tr").each(function(){
            $(this).find("input[name=is_main_platform]").attr("disabled","disabled");
            $(this).find("button").attr("disabled","disabled");
        });
    });

    ////首选媒体主的radio切换
    $("body").on('click','input[name=pref_vendor]',function(){
        $("input[name=pref_vendor]").not(this).attr("checked",false);
    });

    //删除供应商
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
            console.log(vendor_info_json);
        });
    });


    //入驻视频资源的保存
    $(".btn-save-video-media").click(function(){
        var url = $(this).data('url');
        var nickname = $("input[name=nickname]").val();
        var realname = $("input[name=realname]").val();
        var sex = $("select[name=sex]").children("option:selected").val();
        var coop_remark = $("textarea[name=coop_remark]").val();
        var pref_vendor = $("input[name=pref_vendor]:checked").closest("tr").data("uuid");
        var main_platform = $("input[name=is_main_platform]:checked").closest("tr").data("type");
        var cate ="#";
        $("input[name=media_cate]:checked").each(function(){
            cate+=$(this).val()+"#";
        });
        var area ="#";
        $("input[name=address]:checked").each(function(){
            area+=$(this).val()+"#";
        });
        if(nickname=='' || cate=="#" || area=="#"){
            swal({title: "存在必填项未填!", text: "", type: "warning"});
            return false;
        }
        var platform_len = $("input[name=is_main_platform]").length;
        if(platform_len == 0){
            swal({title: "请选择主打平台!", text: "", type: "warning"});
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
                    platform_info_json:platform_info_json,
                    pref_vendor:pref_vendor,
                    main_platform:main_platform,
                    nickname:nickname,
                    realname:realname,
                    sex:sex,
                    coop_remark:coop_remark,
                    cate:cate,
                    area:area,
                },
                beforeSend: function () {  //让提交按钮失效，以实现防止按钮重复点击
                    $(".btn-save-video-media").attr('disabled', 'disabled');
                },
                complete: function () { //按钮重新有效
                    $(".btn-save-video-media").removeAttr('disabled');
                },
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "保存失败！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }else{
                        swal({title: "入驻成功！", text: "", type: "success"});
                        //window.location.reload();
                        window.location.href = "/index.php?r=video/media/list";
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    return false;
                }
            });
        });

    });


    //检查视频账号是否存在
    $("input[name=nickname]").blur(function(){
        var nickname = $(this).val();
        $.ajax({
            url: '/index.php?r=video/media/check-nickname',
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {nickname: nickname},
            success: function (resp) {
                if(resp.err_code == 1){
                    swal({title: "该艺人系统已经存在，请勿重复录入!", text: "", type: "error"});
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


    platformPriceTransfer('online');
    platformPriceTransfer('offline');
    platformPriceTransfer('orig');
    platformPriceTransfer('tran');
    //视频平台价格系数转换
    function platformPriceTransfer(selector_type){
        $('body').on('input',"input[name="+selector_type+"_price_orig]",function(){
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
            $(this).closest('tr').find("input[name="+selector_type+"_price_retail]").val(retail_price);
            $(this).closest('tr').find("input[name="+selector_type+"_price_execute]").val(orig_price);

        });
    }





}