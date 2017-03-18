$(function(){
    //~~~~~~判断有无资源~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 10){
            $(".table-footer").css("display","none");
            if(resourceLength < 1){
                $(".no-order").css("display","block");
            }else{
                $(".no-order").css("display","none");
            }
        }else{
            $(".table-footer").css("display","block");
        }
    }
    isResource();

    //~~~~~删除已选择的资源~~~~~
    $(".weibo-order-table").on("click",".delete",function(){
        var element_delete =  $(this).parents("tr");
        layer.confirm('您确定要删除该账号吗？', {
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

    //鼠标放上去显示完整信息ID篇
    $("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($(".weibo-table")).css({ top: offset.top + a.outerHeight() + 6, left: offset.left + a.outerWidth() + 1 }).fadeIn(function () {
                });
            },
            function(){ $(".show-all-info").remove();
            }
        );
    });
    //~~~~~~~固定modal~~~~~~
    //为每个查看需求添加href属性,调用modal
    $(".check-order-require").on("click",function(){
        $(this).attr('data-target','#check-order-require');
    })
})