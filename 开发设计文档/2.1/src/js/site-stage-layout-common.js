/**
 * Created by guxu on 10/8/16.
 */
$(function(){
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

    //~~~~~标题字段解释~~~~~
    $('.thead-title').hover(function(){
        $(this).children('.explain-title').stop().slideDown(0);
    },function(){
        $(this).children('.explain-title').stop().slideUp(0);
    });

    // ~~~~~限制字符串长度~~~~~
    (function constrainhelptLength(){
        for(var i=0;i<$('.synopsis').length;i++){
            var strlen = $('.synopsis').eq(i).data('str');
            if($('.synopsis').eq(i).text().length>strlen){
                $('.synopsis').eq(i).text($('.synopsis').eq(i).text().trim().substr(0,strlen));
                $('.synopsis').eq(i).html($('.synopsis').eq(i).html()+"...");
            }
        }
    })();
    //选择下拉单
    $('.dropdown .dropdown-menu').on('click','li',selectedOption);
    //下拉单选择某一个
    function selectedOption(){
        var _text = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }
})