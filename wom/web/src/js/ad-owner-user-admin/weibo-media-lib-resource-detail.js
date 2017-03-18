


function pjaxRefWeiboLibDetail(){
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
    // 分页样式
    $(".pagination li a").each(function () {
        $(this).removeAttr("href");
        $(this).attr("style", "cursor: pointer;");
    });
    $(".pagination li.disabled").each(function () {
        var label_text = $(this).text();
        $(this).find('span').after('<a>' + label_text + '</a>');
        $(this).find('span').remove();
    });
    //分页处理
    $(".pagination li a").click(function () {
        $("input.page").attr("value", $(this).attr("data-page"));
        $(".form-detail-search").submit();
    });




    //媒体库资源页
    //微信二维码的显示隐藏
    $('.little-code').hover(function(){
        $(this).parents().siblings(".weibo-code").css({display:'block'})
    },function(){
        $(this).parents().siblings(".weibo-code").css({display:'none'})
    })

    //~~~~~~判断有无资源~~~~~~
    function isResource(){
        var resourceLength =  $(".weibo-resource-list-table tbody").children("tr").length;
        if(resourceLength < 1){
            $(".no-resource").css("display","block");
        }else{
            $(".no-resource").css("display","none");
        }
    }
    isResource();

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

    //新建媒体库的保存
    $(".btn-new-lib-save").click(function(){
        var group_name = $(".new-media-lib").val();
        var new_weibo_lib_url = $('#id-new-weibo-lib-url').val();
        var weibo_lib_url = $('#id-weibo-lib-url').val();
        layer.confirm('您确定要新建吗？', {
            btn: ['确定','取消']
        }, function(){
            $.ajax({
                url: new_weibo_lib_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {group_name: group_name},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        wom_alert.msg({
                            icon: "finish",
                            content: "新建成功!",
                            delay_time: 1500
                        });
                        $("#create").modal("hide");
                        window.location.href = weibo_lib_url;
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
        })
    });

    //删除媒体库资源
    $(".weibo-resource-list-table").on("click",".delete",function(){
        var del_media_from_lib_url = $('#id-del-media-from-lib-url').val();
        var media_uuid = $(this).data("item-uuid");
        var element_delete =  $(this).parents("tr");
        layer.confirm('您确定要删除账号吗？', {
                btn: ['确定','取消']
            }, function(){
                $.ajax({
                    url: del_media_from_lib_url,
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {media_uuid: media_uuid},
                    success: function (resp) {
                        if (resp.err_code == 0) {
                            wom_alert.msg({
                                icon: "finish",
                                content: "删除成功!",
                                delay_time: 1500
                            });
                            element_delete.remove();
                            window.location.reload();
                            isResource();
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
            }
        )
    });

    // 重新分组 - 弹框打开
    $(".weibo-resource-list-table .re-group").on("click", function(){
        // 获取分组列表
        var get_all_media_lib_url = $('#id-get-all-weibo-media-lib-url').val();
        var media_uuid = $(this).data("item-uuid");
        var lib_uuid = $(this).data("group-uuid");
        $.ajax({
            url: get_all_media_lib_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {},
            success: function (resp) {
                if (resp.err_code == 0) {
                    var weibo_media_lib_list = resp.weibo_media_lib_list;
                    $("#regrouping .group-name ul li").remove(); // 清空
                    for (var i = 0; i < weibo_media_lib_list.length; i++) {
                        $("#regrouping .group-name ul ").append('<li data-uuid="' + weibo_media_lib_list[i].uuid + '"><input class="fl" name="select-group-name" type="checkbox"><span class="fl">' + weibo_media_lib_list[i].group_name + '</span></li>');
                    }
                    $('#regrouping .btn-submit-regroup').attr('item-uuid',media_uuid);
                    $('#regrouping .btn-submit-regroup').attr('group-uuid',lib_uuid);
                    $('#regrouping').modal('show');
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
    $('#regrouping .btn-submit-regroup').on("click", function(){
        var re_group_url = $('#id-re-group-url').val();
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
        $.ajax({
            url: re_group_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                item_uuid: item_uuid,
                group_uuid_arr:group_uuid_arr,
                group_uuid:group_uuid
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    wom_alert.msg({
                        icon: "finish",
                        content: "分组成功!",
                        delay_time: 1500
                    });
                    $('#regrouping').modal('hide');
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


    /////////////////////////////////////////////批量管理//////////////////////////////////////////////////////
    //移除
    $(".batch-manage").on("click",".remove",function(){
        var delete_batch_url = $("#id-del-media-from-lib-batch-url").val();
        var delete_item_uuid = [];
        $("tbody").find("input:checked").each(function(){
            delete_item_uuid.push($(this).closest("tr").data('uuid'));
        });
        var ready_remove = $("tbody").find("input:checked");
        if(ready_remove.length < 1){
            layer.msg("请选择账号!",{
                icon:0,
                time: 1500
            });
            return false;
        }
        layer.confirm('您确定要删除账号吗？', {
                btn: ['确定','取消']
            }, function(){
                $.ajax({
                    url: delete_batch_url,
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {delete_item_uuid: delete_item_uuid},
                    success: function (resp) {
                        if (resp.err_code == 0) {
                            layer.msg('删除成功 !', {
                                icon: 1,
                                time:1000
                            });
                            ready_remove.parents("tr").remove();
                            window.location.reload();
                            isResource();
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
            }
        )
    })

    //投放资源
    $(".batch-manage").on("click",".throw",function(){
        var create_batch_url = $("#id-create-plan-media-from-url").val();
        var create_plan_url = $("#id-create-plan-order-url").val();
        var create_item_uuid = [];
        $("tbody").find("input:checked").each(function(){
            create_item_uuid.push($(this).closest("tr").data('uuid'));
        });
        var ready_remove = $("tbody").find("input:checked");
        if(ready_remove.length < 1){
            layer.msg("请选择账号!",{
                icon:0,
                time: 1500
            });
            return false;
        }
        $.ajax({
            url: create_batch_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {create_item_uuid: create_item_uuid},
            success: function (resp) {
                if (resp.err_code == 0) {
                    window.location.href = create_plan_url+"&plan_uuid="+resp.plan_uuid;
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
    })

    //添加资源
    $(".batch-manage").on("click",".add",function(){
        var group_uuid = $(this).data('uuid');
        var weibo_list_url =  $("#id-weibo-list-url").val();
        window.location.href = weibo_list_url+"&group_uuid="+group_uuid;
    });


    //导出资源
    $(".batch-manage").on("click",".export",function(){
        var media_export_url = $('#id-media-export-url').val();
        var media_selected_cnt = 0;
        $(".weibo-resource-list-table [name='media-selected']").each(function(){
            if($(this).is(':checked')){
                media_selected_cnt++;
                addMediaIntoCookie($(this).closest('tr').data('uuid'));
            }else{
                deleteMediaInCookie($(this).closest('tr').data('uuid'));
            }
        });
        // if(media_selected_cnt == 0){
        //     layer.msg("请选择账号!",{
        //         icon:0,
        //         time: 1500
        //     });
        //     return false;
        // }
        var item_uuid_arr = Cookies.get('weibo-media-selected-to-export');//导出资源的cookie
        if(item_uuid_arr == "" || item_uuid_arr == undefined){
            layer.msg("请选择账号!",{
                icon:0,
                time: 1500
            });
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
                data: {item_uuid_arr:item_uuid_arr},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        wom_alert.msg({
                            icon: "finish",
                            content: "导出成功!",
                            delay_time: 1500
                        });
                        Cookies.remove('weibo-media-selected-to-export');
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

    // //根据导出资源的cookie选中列表页
    // $(".weibo-resource-list-table [name='media-selected']").each(function(){
    //     console.log(Cookies.get('weibo-media-selected-to-export'));
    //     var export_cookie = Cookies.get('weibo-media-selected-to-export');
    // });


    // 根据weibo media uuid从cookie里移除给定的资源
    function deleteMediaInCookie(media_uuid) {
        var weibo_media_selected_to_export = Cookies.get('weibo-media-selected-to-export');
        console.log(weibo_media_selected_to_export);
        if (weibo_media_selected_to_export != '' && weibo_media_selected_to_export != undefined  ) {
            weibo_media_selected_to_export = weibo_media_selected_to_export.replace(media_uuid + ',', '');
            Cookies.set('weibo-media-selected-to-export', weibo_media_selected_to_export, {expires: 7});
        }
    }

    // 将选中资源的media uuid加入cookie
    function addMediaIntoCookie(media_uuid) {
        var weibo_media_selected_to_export = Cookies.get('weibo-media-selected-to-export');
        if (weibo_media_selected_to_export == undefined || weibo_media_selected_to_export == '') {
            Cookies.set('weibo-media-selected-to-export', media_uuid + ',');
        } else if (weibo_media_selected_to_export.indexOf(media_uuid) < 0) {
            Cookies.set('weibo-media-selected-to-export', weibo_media_selected_to_export + media_uuid + ',');
        }
    }

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





}