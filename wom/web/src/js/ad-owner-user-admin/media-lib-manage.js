$(function(){
    //~~~~~~判断有无资源~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 1){
            $(".no-lib").css("display","block");
        }else{
            $(".no-lib").css("display","none");
        }
    }
    isResource();

    // =======  微信媒体库管理 =======
    // 新建媒体库 - 显示弹框
    $('.weixin-media-lib-manage-stage').on('click', '.search-area .btn-create', function(){
        $('#modal-create-weixin-media-lib').modal('show');
    });

    // 删除媒体库资源
    $('.weixin-media-lib-manage-stage').on('click', '.result-area .remove', function(){
        var delete_url = $(this).attr('data-delete-url');
        wom_alert.confirm({
            content: '确定移除该媒体库吗?'
        },function(){
            $.ajax({
                url: delete_url,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        wom_alert.msg({
                            icon: "finish",
                            content: "移除成功!",
                            delay_time: 1500
                        });
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

    // 导出媒体库
    $('.weixin-media-lib-manage-stage').on('click', '.result-area .export', function(){
        var export_lib_url = $(this).attr('data-export-url');
        var delete_export_url = $("#id-delete-excel-url").val();

        wom_alert.confirm({
            content: '确定导出该媒体库吗?'
        },function(){
            $.ajax({
                url: export_lib_url,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        wom_alert.msg({
                            icon: "finish",
                            content: "导出成功!",
                            delay_time: 1500
                        });
                        //下载到本地
                        window.location.href = resp.filename;
                        //删除本地导出excel文件
                        $.ajax({
                            url: delete_export_url,
                            type: 'POST',
                            cache: false,
                            dataType: 'json',
                            data: {filename:resp.filename},
                            success: function (resp) {
                                if (resp.err_code == 0) {
                                    return false;
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

    // 新建媒体库 - 保存
    $('#modal-create-weixin-media-lib').on('click', '.btn-save', function(){
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
})