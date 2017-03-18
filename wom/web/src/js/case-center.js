$(function(){
    //选择下拉单
    $('.dropdown .dropdown-menu').on('click','li',selectedOption);

    //********************注释部分勿删除,暂时隐藏,后续添加***********************

    //banner图轮播
    ////ol居中
    //var w = $('.ad-banner ol').width();
    //$('.ad-banner ol').css({marginLeft:-parseInt(w/2)});
    ////banner图动画效果
    //var zindex = 10;
    //var mynum = 0;
    //var timer = null;
    //function autoPlay(){
    //    zindex++;
    //    mynum++;
    //    if(mynum > $('.slider-nav li').length){
    //        mynum = 0;
    //    }
    //    $('.slider-nav li').eq(mynum).addClass('active').siblings('li').removeClass('active');
    //    $('.ad-banner ul li').eq(mynum).css({zIndex:zindex}).hide().stop().fadeIn(500);;
    //}
    //timer=setInterval(autoPlay, 3000);
    //
    ////清除定时器
    //timer=setInterval(autoPlay, 3000);
    //$('.ad-banner').hover(function() {
    //    clearInterval(timer);
    //}, function() {
    //    timer=setInterval(autoPlay, 3000);
    //});
    //$('.slider-nav li').click(function(){
    //    //点击的时候，判断当前的累加器，和点击索引之间的关系
    //    zindex++;
    //    var index=$(this).index();
    //
    //    $(this).addClass('active').siblings('li').removeClass('active');
    //    $('.ad-banner ul li').eq(mynum).css({zIndex:zindex}).hide().stop().fadeIn(500);
    //
    //    mynum=index;
    //
    //})
    //案例中心tab
    $('.case-con-header ul li').click(function(){
        var _index = $(this).index();
        $(this).addClass('current-nav').siblings('li').removeClass('current-nav');
        $(this).parents('.case-con').find('.case-info').eq(_index).addClass('current-case-info').siblings('').removeClass('current-case-info');
    })
    ////案例中心轮播图
    //var _clone_case = $('.case-info-con:first').clone();
    //$('.part-case-info-con').append(_clone_case);
    //var totalWidth = $('.part-case-info-con .case-info-con').width()*5;
    //$('.part-case-info-con').width(totalWidth);
    ////点击事件动画
    //$('.slider-list li').click(function(){
    //    var _index = $(this).index();
    //    $(this).addClass('current-case').siblings('li').removeClass('current-case');
    //    $(this).parents('.case-info').find('.part-case-info-con').stop().animate({left:-_index*1102+46},500);
    //})
    ////点击右侧图标触发事件
    //var current_index = 0;
    //var timer_1 = null;
    //$('.case-info .next').click(function(){
    //    nextPlay($(this));
    //})
    //
    //function nextPlay(_this){
    //    current_index++;
    //    var _sign = _this.siblings('.slider-list').children('li');
    //    if(current_index > 3 ){
    //        _this.siblings('.part-case-info-con').css({left:46});
    //        current_index = 1;
    //    }
    //    _sign.eq(current_index).addClass('current-case').siblings('li').removeClass('current-case');
    //    _this.siblings('.part-case-info-con').stop().animate({left:-current_index*1102+46},500);
    //    if(current_index == 3){
    //        _sign.eq(0).addClass('current-case').siblings('li').removeClass('current-case');
    //    }
    //}
    //var _this = $('.case-info .next');
    //timer_1 = setInterval(function(){
    //    nextPlay(_this);
    //},3000);
    //// 点击右侧图标触发事件
    //$('.case-info .prev').click(function(){
    //    prevPlay($(this));
    //});
    //function prevPlay(){
    //    current_index--;
    //    var _sign = _this.siblings('.slider-list').children('li');
    //    if(current_index < 0){
    //        _this.siblings('.part-case-info-con').css({left:-3*1102 +46});
    //        current_index = 2;
    //    }
    //    _sign.eq(current_index).addClass('current-case').siblings('li').removeClass('current-case');
    //    _this.siblings('.part-case-info-con').stop().animate({left:-current_index*1102+46},500);
    //
    //    if(current_index == 3){
    //        _sign.eq(0).addClass('current-case').siblings('li').removeClass('current-case');
    //    }
    //}
    ////鼠标放上去的时候清除定时器
    //$('.case-info').hover(function(){
    //    clearInterval(timer_1);
    //},function(){
    //    timer_1 = setInterval(function(){
    //        nextPlay(_this);
    //    },3000);
    //})

    // 下拉单选择某一个
    function selectedOption(){
        var _txet = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_txet);
    }

    //~~~~~~~~~~~~~~~~~~~侧边栏联系方式~~~~~~~~~~~~~~~~~~~~~~
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
    //回车搜索
    $('input[name = search-media]').bind('keypress',function(event){
        if(event.keyCode == "13"){
            resourceSearch();
        }
    });
    //资源搜索
    function resourceSearch(){
        var search_url = $("input[name=search-media]").data('url');
        var search_name = $("input[name=search-media]").val();
        if(search_name == ""){
            window.location.href = search_url;
            return false;
        }
        window.location.href = search_url+"&search_name="+search_name;
    }
})



