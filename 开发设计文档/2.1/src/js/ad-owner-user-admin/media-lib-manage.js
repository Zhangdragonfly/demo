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

    //删除媒体库资源
    $(".table").on("click",".remove",function(){
        var element_delete =  $(this).parents("tr");
        layer.confirm('您确定要删除该媒体库吗？', {
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

})