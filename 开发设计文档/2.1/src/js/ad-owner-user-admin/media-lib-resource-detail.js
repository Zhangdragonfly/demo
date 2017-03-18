$(function(){
//媒体库资源页
    //微信二维码的显示隐藏
    $('.little-code').hover(function(){
        $(this).parents().siblings(".weixin-code").css({display:'block'})
    },function(){
        $(this).parents().siblings(".weixin-code").css({display:'none'})
    })

    //~~~~~~判断有无资源~~~~~~
    function isResource(){
        var resourceLength =  $(".weixin-resource-list-table tbody").children("tr").length;
        if(resourceLength < 1){
            $(".no-resource").css("display","block");
        }else{
            $(".no-resource").css("display","none");
        }
    }
    isResource();

    //删除媒体库资源
    $(".weixin-resource-list-table").on("click",".delete",function(){
        var element_delete =  $(this).parents("tr");
        layer.confirm('您确定要删除账号吗？', {
                btn: ['确定','取消']
            }, function(){
                layer.msg('删除成功 !', {
                    icon: 1,
                    time:1000
                });
                element_delete.remove();
                isResource();

            }
        )
    });

//批量管理
    //移除
    $(".batch-manage").on("click",".remove",function(){
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
                layer.msg('删除成功 !', {
                    icon: 1,
                    time:1000
                });
            ready_remove.parents("tr").remove();
            isResource();
            }
        )
    })

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
})