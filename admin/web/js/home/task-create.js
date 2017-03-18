
function taskCreate(){
    // 控制左侧导航选中
    if(!$('#website-manage .task-manage .task-create').hasClass('active')){
        $('.menu-level-1').each(function(){
            $(this).removeClass('active');
        });
        $('.menu-level-2').each(function(){
            $(this).removeClass('active');
        });
        $('.menu-level-3').each(function(){
            $(this).removeClass('active');
        });

        $('#website-manage.menu-level-1').addClass('active');
        $('#website-manage.menu-level-1 .menu-level-2.task-manage').addClass('active');
        $('#website-manage.menu-level-1 .menu-level-2.task-manage .menu-level-3.task-create').addClass('active');
    }


    //后台抓取视频资源
    $(".btn-submit-grab").click(function(){
        var media_type = $("input[name=media_type]:checked").val();
        var media_id = $(".media_id").val();
        var media_url = $(".media_url").val();
        var url = $(this).data('url');
        if(media_id =="" && media_url==""){
            swal({title: "抓取ID和抓取url至少填一个!", text: "", type: "warning"});
            return false;
        }
        swal({
            title: "确定抓取该资源?",
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
                    media_type:media_type,
                    media_id:media_id,
                    media_url:media_url,
                },
                beforeSend: function () {  //让提交按钮失效，以实现防止按钮重复点击
                    $(".btn-submit-grab").attr('disabled', 'disabled');
                },
                complete: function () { //按钮重新有效
                    $(".btn-submit-grab").removeAttr('disabled');
                },
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "保存失败！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }else{
                        swal({title: "提交成功！", text: "", type: "success"});
                        window.location.reload();
                        //window.location.href = "/index.php?r=video/media/list";
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    return false;
                }
            });
        });
    });






}