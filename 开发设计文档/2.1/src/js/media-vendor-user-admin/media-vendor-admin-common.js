$(function(){
    // ~~~~~ 限制字符串长度 ~~~~~
    (function constrainhelptLength(){
        for(var i=0;i<$('.synopsis').length;i++){
            var strlen = $('.synopsis').eq(i).data('str');
            if($('.synopsis').eq(i).text().length>strlen){
                $('.synopsis').eq(i).text($('.synopsis').eq(i).text().trim().substr(0,strlen));
                $('.synopsis').eq(i).html($('.synopsis').eq(i).html()+"...");
            }
        }
    })();

    //用户中心导航栏
    $(".fold").find(".title").on("click",function(){
        $(this).siblings("dl").slideToggle();
        $(this).siblings("dl").removeClass("show");
        $(this).parent().siblings().find("i").removeClass("rotate");
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
        }else{
            $(this).addClass("active");
        }
        if ($(this).siblings("i").hasClass("rotate")) {
            $(this).siblings("i").removeClass("rotate");
        }else{
            $(this).siblings("i").addClass("rotate");
        }
        var relative_span = $(this).parent().siblings().find(".title");
        relative_span.removeClass("active");
        relative_span.siblings("dl").slideUp();
        relative_span.siblings("dl").removeClass("show");
    })

    //~~~~~侧边栏联系方式~~~~~
    $(".side-bar li").hover(function(){
        $(this).css("background","#c81624");
        $(this).children("div").stop().animate({
            left:'-155px'
        },500);
    },function(){
        $(this).css("background","#1e1e1e");
        $(this).children("div").stop().animate({
            left:'46px'
        },500);
    })
    $(".side-bar .top").on("click",function(){
        $('html,body').stop().animate({
            scrollTop:'0'
        },500)
    })

    //标题字段解释
    $('.thead-title').hover(function(){
        $(this).children('.explain-title').stop().slideDown(0);
    },function(){
        $(this).children('.explain-title').stop().slideUp(0);
    });

    //~~~~~~判断有无资源~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 10){
            $(".table-footer").css("display","none");
            if(resourceLength < 1){
                $(".no-lib").css("display","block");
            }else{
                $(".no-lib").css("display","none");
            }
        }else{
            $(".table-footer").css("display","block");
        }
    }
    isResource();

    //删除资源
    $(".table").on("click",".remove",function(){
        var element_delete =  $(this).parents("tr");
        wom_alert.confirm({
            content:"确定删除该媒体库吗？",
        }, function(){
             rtn_status = {
                icon : "finish",
                msg: "删除成功",
                delay_time: 1000
            }
            return rtn_status;
        });
        element_delete.remove();
        isResource();
    });

})