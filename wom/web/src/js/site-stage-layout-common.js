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
        $(this).siblings("dl").slideToggle(200);
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
        relative_span.siblings("dl").slideUp(200);
        relative_span.siblings("dl").removeClass("show");
    })

    // 判断用户中心导航栏的展示信息
    //给对应的标签添加如下类名即可 i class = "rotate" title class="active"  dl  class = "show"  a class = "on-click"
    function whichNavShow(){
        var breadcrumb_level_one_con = $.trim($(".breadcrumb-level-one").text()),
            breadcrumb_level_two_con = $.trim($(".breadcrumb-level-two").text());
        $(".fold").each(function(){
            var _this = $(this),
                rotate_icon = _this.children("i"),
                unfold_dl = _this.children("dl"),
                nav_level_one = _this.children(".title");
            if(nav_level_one.text() == breadcrumb_level_one_con){
                nav_level_one.addClass("active");
                rotate_icon.addClass("rotate");
                unfold_dl.addClass("show");
                var nav_level_two = $(this).find("a");
                nav_level_two.each(function(){
                    if($(this).text() == breadcrumb_level_two_con){
                        $(this).addClass("on-click");
                    }
                })
            }
        })
    }
    whichNavShow();

    //~~~~~标题字段解释~~~~~
    $('.thead-title').hover(function(){
        $(this).children('.explain-title').stop().slideDown(0);
    },function(){
        $(this).children('.explain-title').stop().slideUp(0);
    });

    ////选择下拉单
    //$('.dropdown .dropdown-menu').on('click','li',selectedOption);
    //// 下拉单选择某一个
    //function selectedOption(){
    //    var _text = $(this).text();
    //    $(this).parent().prev().find('span:eq(0)').text(_text);
    //}
})