$(function(){
//媒体库资源页
    //微信二维码的显示隐藏
    $('.little-code').hover(function(){
        $(this).parents().siblings(".weixin-code").css({display:'block'})
    },function(){
        $(this).parents().siblings(".weixin-code").css({display:'none'})
    });
    //全选
    var check_all = $(".check-all input");
    var check_single = $(".table :checkbox");

    check_all.on("click",function(){
        if(this.checked){
            check_single.prop("checked",true);
        }else{
            check_single.prop("checked",false);
        }
    })
    //判断全选复选框的选中与否
    check_single.click(function(){
        allchk();
    });
    function allchk(){
        var chknum = check_single.size();//选项总个数
        var chk = 0; //已选中的个数
        check_single.each(function () {
            if($(this).prop("checked") == true){
                chk++;
            }
        });
        if(chknum == chk){ //全选时
            check_all.prop("checked",true);
        }else{  //不全选时
            check_all.prop("checked",false);
        }
    }

    // 移除媒体库某个账号
    $(".weixin-resource-list-table .delete").on("click", function(){
        var del_media_from_lib_url = $('#id-del-media-from-lib-url').val();
        var lib_uuid = $('#id-media-lib-uuid').val();
        var selected_media = $(this).attr("data-media-uuid");
        var this_media = $(this).closest('tr');

        wom_alert.confirm({
            content: '确定移除吗?'
        },function(){
            $.ajax({
                url: del_media_from_lib_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {lib_uuid: lib_uuid, selected_media: selected_media},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        wom_alert.msg({
                            icon: "finish",
                            content: "删除成功!",
                            delay_time: 1500
                        });
                        window.location.reload();
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统错误，请联系管理员!",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 1500
                    });
                    return false;
                }
            });
        });
    });

    // 重新分组 - 弹框打开
    $(".weixin-resource-list-table .re-group").on("click", function(){
        // 获取分组列表
        var get_all_weixin_media_lib_url = $('#id-get-all-weixin-media-lib-url').val();
        //var re_group_url = $('#id-re-group-url').val();
        var item_uuid = $(this).attr("data-item-uuid");
        var lib_uuid = $(this).attr("data-lib-uuid");

        $.ajax({
            url: get_all_weixin_media_lib_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {},
            success: function (resp) {
                if (resp.err_code == 0) {
                    // TODO
                    var weixin_media_lib_list = resp.weixin_media_lib_list;
                    $("#modal-re-group .group-name ul li").remove(); // 清空
                    for (var i = 0; i < weixin_media_lib_list.length; i++) {
                        $("#modal-re-group .group-name ul ").append('<li data-uuid="' + weixin_media_lib_list[i].uuid + '"><input class="fl" name="select-group-name" type="checkbox"><span class="fl">' + weixin_media_lib_list[i].group_name + '</span></li>');
                    }
                    $('#modal-re-group .btn-submit-regroup').attr('item-uuid',item_uuid);
                    $('#modal-re-group .btn-submit-regroup').attr('group-uuid',lib_uuid);
                    $('#modal-re-group').modal('show');
                    
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统错误，请联系管理员!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });

    //重新分组-提交
    $("#modal-re-group .btn-commit").on("click", function(){
        var url = $(this).attr("data-url");
        var item_uuid = $(this).attr("item-uuid");//重新分组的资源uuid
        var group_uuid = $(this).attr("group-uuid");//原媒体库uuid
        var group_uuid_arr =[];//重新分组的媒体库uuid
        $('input[name="select-group-name"]:checked').each(function(){
            group_uuid_arr.push($(this).parent('li').data('uuid'));
        });
        if(group_uuid_arr==""){
            wom_alert.msg({
                icon: "error",
                content: "请选择媒体库!",
                delay_time: 1500
            });
            return false;
        }
        $.post(url,{
            item_uuid: item_uuid,
            group_uuid_arr:group_uuid_arr,
            group_uuid:group_uuid
        },function(data, status){
            if(status == 'success'){
                if(data.err_code == 0){
                    wom_alert.msg({
                        icon: "finish",
                        content: "分组成功!",
                        delay_time: 1500
                    });
                    $('#regrouping').modal('hide');
                    window.location.reload();
                }else{
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 1500
                    });
                    return false;
                }
            }else{
                wom_alert.msg({
                    icon: "error",
                    content: "系统错误，请联系管理员!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });

    // ======  批量管理  ======
    function selectMedia(){
        var media_selected_cnt = 0;
        var selected_media_uuid_list = '';
        $(".weixin-resource-list-table [name=media-select]").each(function(){
            console.log(132);
            if($(this).is(':checked')){
                media_selected_cnt++;
                selected_media_uuid_list += $(this).attr('data-item-uuid') + ',';
                addMediaIntoCookie($(this).attr('data-item-uuid'));
            }else{
                deleteMediaInCookie($(this).attr('data-item-uuid'));
            }
        });
        if(media_selected_cnt == 0){
            wom_alert.msg({
                icon: "error",
                content: "请至少选择一个账号!",
                delay_time: 1500
            });
            return false;
        }
        return selected_media_uuid_list;
    }

    function selectDeleteMedia(){
        var media_selected_cnt = 0;
        var selected_media_uuid_list = '';
        $(".weixin-resource-list-table [name='media-select']").each(function(){
            if($(this).is(':checked')){
                media_selected_cnt++;
                selected_media_uuid_list += $(this).attr('data-media-uuid') + ',';
                //addMediaIntoCookie($(this).attr('data-media-uuid'));
            }else{
                //deleteMediaInCookie($(this).attr('data-media-uuid'));
            }
        });
        if(media_selected_cnt == 0){
            wom_alert.msg({
                icon: "error",
                content: "请至少选择一个账号!",
                delay_time: 1500
            });
            return false;
        }
        return selected_media_uuid_list;
    }

    // 根据weixin media uuid从cookie里移除给定的资源
    function deleteMediaInCookie(media_uuid) {
        var weixin_media_selected_to_export = Cookies.get('weixin-media-selected-to-export');
        if (weixin_media_selected_to_export != '' && weixin_media_selected_to_export != undefined) {
            weixin_media_selected_to_export = weixin_media_selected_to_export.replace(media_uuid + ',', '');
            Cookies.set('weixin-media-selected-to-export', weixin_media_selected_to_export, {expires: 7});
        }
    }

    // 将选中资源的media uuid加入cookie
    function addMediaIntoCookie(media_uuid) {
        var weixin_media_selected_to_export = Cookies.get('weixin-media-selected-to-export');
        if (weixin_media_selected_to_export == undefined || weixin_media_selected_to_export == '') {
            Cookies.set('weixin-media-selected-to-export', media_uuid + ',');
        } else if (weixin_media_selected_to_export.indexOf(media_uuid) < 0) {
            Cookies.set('weixin-media-selected-to-export', weixin_media_selected_to_export + media_uuid + ',');
        }
    }


    // 批量移除媒体库里的媒体
    $('.batch-manage .btn-delete').on('click', function(){
        var selected_media = selectDeleteMedia();
        if(selected_media === false){
            return false;
        }

        var del_media_from_lib_url = $('#id-del-media-from-lib-url').val();
        var lib_uuid = $('#id-media-lib-uuid').val();

        wom_alert.confirm({
            content: '确定移除吗?'
        },function(){
            $.ajax({
                url: del_media_from_lib_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {lib_uuid: lib_uuid, selected_media: selected_media},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        wom_alert.msg({
                            icon: "finish",
                            content: "删除成功!",
                            delay_time: 1500
                        });
                        window.location.reload();
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统错误，请联系管理员!",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 1500
                    });
                    return false;
                }
            });
        });
    });

    // 在媒体库的详情里继续添加媒体
    $('.batch-manage .btn-add-more').on('click', function(){
        var weixin_media_select_url = $('#id-weixin-media-select-url').val();
        var media_uuid_in_lib = $('#id-media-uuid-list-in-this-lib').val();
        var media_lib_uuid = $('#id-media-lib-uuid').val();

        // 将放到cookie里
        var weixin_media_lib_id_in_cookie = 'weixin-media-lib-' + media_lib_uuid;
        Cookies.remove(weixin_media_lib_id_in_cookie);
        Cookies.set(weixin_media_lib_id_in_cookie, media_uuid_in_lib);

        window.location.href = weixin_media_select_url;
    });

    // 在媒体库的详情里选择账号去投放
    $('.batch-manage .btn-put-in').on('click', function(){
        var selected_media = selectMedia();
        if(selected_media === false){
            return false;
        }
        var create_plan_url = $('#id-plan-create-url').val();

        Cookies.set('weixin-media-selected-to-put-in', selected_media);
        console.log(Cookies.get('weixin-media-selected-to-put-in'));

        // 创建plan
        window.location.href = create_plan_url;
    });

    // 导出媒体库里的资源
    $('.batch-manage .btn-export').on('click', function(){
        //console.log( Cookies.get('weixin-media-selected-to-export'));
        var media_export_url = $('#id-media-export-url').val();
        var selected_media = selectMedia();
        if(selected_media == false){
            return false;
        }
        wom_alert.confirm({
            content: '确定导出媒体库资源吗?'
        },function(){
            $.ajax({
                url: media_export_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {item_uuid_arr:selected_media},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        wom_alert.msg({
                            icon: "finish",
                            content: "导出成功!",
                            delay_time: 1500
                        });
                        Cookies.remove('weixin-media-selected-to-export');
                        window.location.href = resp.filename;
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统错误，请联系管理员!",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 1500
                    });
                    return false;
                }
            });
        });
    });

    //鼠标放上去显示完整信息
    $("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($(".shadow")).css({ top: offset.top + a.outerHeight() + 6, left: offset.left + a.outerWidth() + 1 }).fadeIn(function () {
                });
            },
            function(){
                $(".show-all-info").remove();
            }
        );
    });

    // 新建媒体库 - 显示弹框
    $('.resource-list-table-top .create').on('click', function(){
        $('#modal-create-weixin-media-lib').modal('show');
    });

    // 新建媒体库 - 保存
    $('#modal-create-weixin-media-lib .btn-save').on('click', function(){
        var media_lib_create_url = $('#id-weixin-media-lib-create-url').val();
        var lib_name = $.trim($('#modal-create-weixin-media-lib .input-lib-name').val());

        if(lib_name == ''){
            wom_alert.msg({
                icon: "error",
                content: "媒体库名称不能为空!",
                delay_time: 1500
            });
            return false;
        }

        wom_alert.confirm({
            content: '确认保存吗?'
        },function(){
            $.ajax({
                url: media_lib_create_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {media_lib_name: lib_name},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        // 跳转到个人中心 > 微信媒体库
                        window.location.href = resp.redirect_url;
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统错误，请联系管理员!",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 1500
                    });
                    return false;
                }
            });
        });
    });

    // 重新分组modal层全选JS
    $('.bd-foot input').click(function(){
        var _check = $(this).is(':checked');
        if(_check){
            $(this).parent().prev().find('input').each(function(){
                if($(this).is(':checked')) return;
                $(this).prop('checked',true);
            })
        } else {
            $(this).parent().prev().find('input').prop('checked',false);
        }
    });
})