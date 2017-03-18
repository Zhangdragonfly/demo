$(function(){
    //~~~~限制字符长度函数~~~~
    function constraintLength(){
        for(var i=0;i<$('.synopsis').length;i++){
            var strlen = $('.synopsis').eq(i).data('str');
            if($('.synopsis').eq(i).text().length>strlen){
                $('.synopsis').eq(i).text($('.synopsis').eq(i).text().trim().substr(0,strlen));
                $('.synopsis').eq(i).html($('.synopsis').eq(i).html()+"...");
            }
        }
    }
    // ~~~~~~~~~~微信/微博/视频收藏列表展示~~~~~~~~~
    $(".tab li").on("click",function(){
        $(this).addClass("click").siblings().removeClass("click");
        $(this).find("div").addClass("click");
        $(this).siblings().find("div").removeClass("click");
        $(this).find("i").addClass("show");
        $(this).siblings().find("i").removeClass("show");
        $(this).parents().siblings(".collection").eq($(this).index()).addClass("show").siblings().removeClass("show");
    })
    // ~~~~~~~~~~~分组筛选~~~~~~~~~~~
    $(function(){
        $(".weixin-group .filter").on("click",function(){
            $(this).addClass("on").siblings().removeClass("on");
        })
    })
    // ~~~~~~~~~~显示微信二维码~~~~~~~~~~
    $(".account dd p span").on("mouseenter",function(){
        $(this).siblings(".code").fadeIn();
    });
    $(".account dd p span").on("mouseout",function(){
        $(this).siblings(".code").fadeOut();
    })

    // ~~~模态框开发显示~~~~~
     $('#manage-group').modal({backdrop: 'static', keyboard: false});

// ~~~~~~~管理分组~~~~~~
    // 删除
    $(".modal-body .delete").on("click",function(){
        var _this = $(this);
        var group_name = $(this).parent().siblings("span").text();
        //layer.confirm("确定要删除&nbsp;'&nbsp;"+group_name+"&nbsp;'&nbsp;的分组吗？", {
        //    btn: ['确定','取消'] //按钮
        //}, function(){
        //    layer.msg('删除成功', {icon: 1,time:1000,});
        //}, function(){
        //    layer.msg('已取消删除', {
        //        time: 1500,
        //    });
        //});
        layer.confirm("您确定要删除&nbsp;'&nbsp;"+group_name+"&nbsp;'&nbsp;的分组吗？", {
                btn: ['确定','取消']
            }, function(){
                layer.msg('删除成功 !', {
                    icon: 1,
                    time:1000
                });
            },function(){
                layer.msg('已取消删除 !', {
                    icon: 6,
                    time:1000
                });
            }
        )


        $(".layui-layer-btn0").on("click",function(){
            _this.parents(".group-li").remove();
            var group_length = $(".modal-body ul li").length;
            if(group_length < 1){
                $(".modal-body").text("暂无分组，请添加");
            }
        })
    })
    // 编辑
    $(".modal-body .edit").on("click",function(){
        var input = $(this).parent().siblings("input");
        if (input.hasClass("show")) {
            input.removeClass("show");
        }else{
            input.addClass("show").parent(".group-li").siblings().find("input").removeClass("show");
        }
        var group_name_input = $(".modal-body li input");
        group_name_input.on("blur",function(){
            if ($(this).val() != "") {
                $(this).siblings(".group-name").text($(this).val());
                layer.msg('重新命名成功', {
                    icon: 1,
                    time:1000,
                });
            }
            $(this).removeClass("show");
            constraintLength();
        })
    })

})
