$(function(){
    //选择下拉单
    $('.dropdown .dropdown-menu').on('click','li',selectedOption);
    //banner图轮播
    //ol居中
    var w = $('.ad-banner ol').width();
    $('.ad-banner ol').css({marginLeft:-parseInt(w/2)});
    //banner图动画效果
    var zindex = 10;
    var mynum = 0;
    var timer = null;
    function autoPlay(){
        zindex++;
        mynum++;
        if(mynum > $('.slider-nav li').length){
            mynum = 0;
        }
        $('.slider-nav li').eq(mynum).addClass('active').siblings('li').removeClass('active');
        $('.ad-banner ul li').eq(mynum).css({zIndex:zindex}).hide().stop().fadeIn(500);;
    }
    timer=setInterval(autoPlay, 3000);

    //清除定时器
    timer=setInterval(autoPlay, 3000);
    $('.ad-banner').hover(function() {
        clearInterval(timer);
    }, function() {
        timer=setInterval(autoPlay, 3000);
    });
    $('.slider-nav li').click(function(){
        //点击的时候，判断当前的累加器，和点击索引之间的关系
        zindex++;
        var index=$(this).index();

        $(this).addClass('active').siblings('li').removeClass('active');
        $('.ad-banner ul li').eq(mynum).css({zIndex:zindex}).hide().stop().fadeIn(500);

        mynum=index;

    })
    //案例中心tab
    $('.case-con-header ul li').click(function(){
        var _index = $(this).index();
        $(this).addClass('current-nav').siblings('li').removeClass('current-nav');
        $(this).parents('.case-con').find('.case-info').eq(_index).addClass('current-case-info').siblings('').removeClass('current-case-info');
    })
    //案例中心轮播图
    var _clone_case = $('.case-info-con:first').clone();
    $('.part-case-info-con').append(_clone_case);
    var totalWidth = $('.part-case-info-con .case-info-con').width()*5;
    $('.part-case-info-con').width(totalWidth);
    //点击事件动画
    $('.slider-list li').click(function(){
        var _index = $(this).index();
        $(this).addClass('current-case').siblings('li').removeClass('current-case');
        $(this).parents('.case-info').find('.part-case-info-con').stop().animate({left:-_index*1102+46},500);
    })
    //点击右侧图标触发事件
    var current_index = 0;
    var timer_1 = null;
    $('.case-info .next').click(function(){
        nextPlay($(this));
    })

    function nextPlay(_this){
        current_index++;
        var _sign = _this.siblings('.slider-list').children('li');
        if(current_index > 3 ){
            _this.siblings('.part-case-info-con').css({left:46});
            current_index = 1;
        }
        _sign.eq(current_index).addClass('current-case').siblings('li').removeClass('current-case');
        _this.siblings('.part-case-info-con').stop().animate({left:-current_index*1102+46},500);
        if(current_index == 3){
            _sign.eq(0).addClass('current-case').siblings('li').removeClass('current-case');
        }
    }
    var _this = $('.case-info .next');
    timer_1 = setInterval(function(){
        nextPlay(_this);
    },3000);
    // 点击右侧图标触发事件
    $('.case-info .prev').click(function(){
        prevPlay($(this));
    });
    function prevPlay(){
        current_index--;
        var _sign = _this.siblings('.slider-list').children('li');
        if(current_index < 0){
            _this.siblings('.part-case-info-con').css({left:-3*1102 +46});
            current_index = 2;
        }
        _sign.eq(current_index).addClass('current-case').siblings('li').removeClass('current-case');
        _this.siblings('.part-case-info-con').stop().animate({left:-current_index*1102+46},500);

        if(current_index == 3){
            _sign.eq(0).addClass('current-case').siblings('li').removeClass('current-case');
        }
    }
    //鼠标放上去的时候清除定时器
    $('.case-info').hover(function(){
        clearInterval(timer_1);
    },function(){
        timer_1 = setInterval(function(){
            nextPlay(_this);
        },3000);
    })
})
    // 下拉单选择某一个
    function selectedOption(){
        var _txet = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_txet);
    }





