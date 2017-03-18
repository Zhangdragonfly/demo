// ~~~~~~~用户中心导航栏~~~~~~~~~
$(function(){
    $(".content-sidebar .unfold").find("span").on("click",function(){
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
        $(this).siblings("dl").slideToggle();
        var relative_span = $(this).parent().siblings().find("span");
        relative_span.removeClass("active");
        relative_span.siblings("dl").slideUp();
        $(this).parent().siblings().find("i").removeClass("rotate");
    })
})
// ~~~~~~~~~~~侧边栏联系方式~~~~~~~~
$(function(){
    $(".side-bar li").hover(function(){
        $(this).css("background","#c81624");
        $(this).children("div").stop().animate({
            left:'-155px'
        },500);
    },function(){
        $(this).css("background","#4c4c4c");
        $(this).children("div").stop().animate({
            left:'46px'
        },500);
    })
    $(".side-bar .top").on("click",function(){
        $('html,body').stop().animate({
            scrollTop:'0'
        },500)
    })
})