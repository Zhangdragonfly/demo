$(function(){

    //媒体库的搜索
    $(".btn-search-lib").click(function(){
        $(".form-lib-search").submit();
    });

    //新建媒体库的保存
    $(".btn-new-lib-save").click(function(){
        var group_name = $(".new-media-lib").val();
        var new_video_lib_url = $('#id-new-video-lib-url').val();
        layer.confirm('您确定要新建吗？', {
            btn: ['确定','取消']
        }, function(){
            $.ajax({
                url: new_video_lib_url,
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
        })
    });


})

//pjax刷新JS
function pjaxReflib(){
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

    // 导出媒体库
    $('.video-table').on('click', '.export', function(){
        var export_url = $(this).data('url');
        var delete_export_url = $("#id-delete-excel-url").val();
        var group_uuid = $(this).data('uuid');

        wom_alert.confirm({
            content: '确定导出该媒体库吗?'
        },function(){
            $.ajax({
                url: export_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {group_uuid:group_uuid},
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

    // 删除媒体库资源
    $('.video-table .remove').click(function(){
        var delete_url = $(this).data('url');
        var group_uuid = $(this).data('uuid');
        wom_alert.confirm({
            content: '确定移除该媒体库吗?'
        },function(){
            $.ajax({
                url: delete_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {group_uuid:group_uuid},

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


}