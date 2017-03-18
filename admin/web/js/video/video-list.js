//微博资源列表js
function videoList(){
    // 控制左侧导航选中
    var search_type = $("input[name=search_type]").val();
    if(search_type ==0){//全部资源
        LeftNavigationChose("#video",".media-manage",".to-list");
    }
    if(search_type == 1){//待审核
        LeftNavigationChose("#video",".media-manage",".to-verify-list");
    }
    if(search_type == 2){//已审核
        LeftNavigationChose("#video",".media-manage",".to-success-list");
    }
    if(search_type == 3){//未通过
        LeftNavigationChose("#video",".media-manage",".to-fail-list");
    }
    if(search_type == 4){//待更新
        LeftNavigationChose("#video",".media-manage",".to-update-list");
    }
    function LeftNavigationChose(first_li,second_li,third_li){
        if(!$(first_li+second_li+third_li).hasClass('active')){
            $('.menu-level-1').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-2').each(function(){
                $(this).removeClass('active');
            });
            $('.menu-level-3').each(function(){
                $(this).removeClass('active');
            });
            $(first_li+'.menu-level-1').addClass('active');
            $(first_li+'.menu-level-1 .menu-level-2'+second_li).addClass('active');
            $(first_li+'.menu-level-1 .menu-level-2'+second_li+' .menu-level-3'+third_li).addClass('active');
        }
    }
    //分页处理样式
    $(".pagination li a").each(function(){
        $(this).removeAttr("href");
        $(this).attr("style","cursor:pointer;");
    });
    //分页处理
    $(".pagination li a").click(function(){
        $(".main-stage .video-form input.page").attr("value", $(this).attr("data-page"));
        $(".main-stage .video-form").submit();
    });

    //修改or审核
    $(".btn-update").click(function(){
        var url = $(this).data('url');
        var video_uuid = $(this).data('uuid');
        window.open(url+"&uuid="+video_uuid);
    });

    //上下架
    $(".btn-put").click(function(){
        var type = $(this).data('type');
        var url = $(this).data('url');
        var platform_uuid = $(this).data('uuid');
        if(type == "down"){//下架
            swal({
                //title: "确认下架并解除首选供应商！",
                title: "确认下架！",
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
                    data: {platform_uuid:platform_uuid,type:type},
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
                    data: {platform_uuid:platform_uuid,type:type},
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
        var type = $(this).data('type');
        var url = $(this).data('url');
        var platform_uuid = $(this).data('uuid');
        swal({
            title: "确定该资源置顶?",
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
                data: {platform_uuid:platform_uuid,type:type},
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "置顶失败！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }else{
                        swal({title: "置顶成功！", text: "", type: "success"});
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
        var type = $(this).data('type');
        var url = $(this).data('url');
        var platform_uuid = $(this).data('uuid');
        swal({
            title: "确定该资源主推?",
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
                data: {platform_uuid:platform_uuid,type:type},
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "主推失败！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }else{
                        swal({title: "主推成功！", text: "", type: "success"});
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

    //删除视频平台
    $(".btn-delete").click(function(){
        var platform_uuid = $(this).data('uuid');
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
                data: {platform_uuid: platform_uuid},
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



}
