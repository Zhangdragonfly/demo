$(function(){
    //条件选择更多开始.
    $('.filter-area ul li:gt(23)').css('display','none');
    $('.select-more').click(function(){
        if($('.filter-area ul li:gt(23)').css('display') == 'none'){
            $('.filter-area ul li:gt(23)').stop().slideDown(200);
            $(this).text('收起');
        } else {
            $('.filter-area ul li:gt(23)').stop().slideUp(200);
            $(this).text('更多');
        }
    })
    //标题解释
    $('.thead-title').hover(function(){
        $(this).children('.explain-title').stop().slideDown(0);
    },function(){
        $(this).children('.explain-title').stop().slideUp(0);
    });
    // 购物车联系方式开始
    $('.contact-bg').hover(function(){
        $(this).next('span').css({display:'block',right:'46px'})
        $(this).next('span').addClass('contact-way-on');
    }, function(){
        $(this).next('span').css({display:'none',right:'-216px'})
        $(this).next('span').removeClass('contact-way-on');
    });
    //购物车箭头开关事件
    $('.shopping-column span').click(function(){
        $('.contact').stop().animate({right:'0'});
        $('.shopping').stop().animate({right:'-420px'});
    });
    //表格二维码图片显示
    $('.ewm-ID').hover(function(){
        $(this).children('img').css({display:'block'});
    },function(){
        $(this).children('img').css({display:'none'});
    })
    //购物车是否开放
    $('.contact').on('click','.contact-shopping-cart',shoppingSwitch);
    // ~~~~~~~限制字符串长度~~~~~~~~
    (function constraintLength(){
        for(var i=0;i<$('.synopsis').length;i++){
            var strlen = $('.synopsis').eq(i).data('str');
            if($('.synopsis').eq(i).text().length>strlen){
                $('.synopsis').eq(i).text($('.synopsis').eq(i).text().trim().substr(0,strlen));
                $('.synopsis').eq(i).html($('.synopsis').eq(i).html()+"...");
            }
        }
    })();

})
    //判断购物车是否开放
    function shoppingSwitch(){
        var _deriction_switch = $(this).parents('.contact').siblings('.shopping');
        if(_deriction_switch.css('right') == '-420px'){
            _deriction_switch.stop().animate({ right:'0'},500);
            $(this).parents('.contact').stop().animate({right:'360px'},500);
        } else {
            _deriction_switch.stop().animate({right:'-420px'},500);
            $(this).parents('.contact').stop().animate({right:'0'},500);
        }
    }
//滚动定位
$(function(){
    var _table_header_t = $('.table-title').offset().top;
    var _window_w = $(window).width();
    var _window_h = $(window).height();
    var AscrollTd = $('#scrollTd td');
    var AscrollTh = $('#scrollTh th');
    var arr = [];

    for (var i = 0; i < AscrollTd.length; i++) {
        arr.push(AscrollTd[i].offsetWidth);
        AscrollTh[i].style.width = AscrollTd[i].offsetWidth + 'px'
    }
    $(window).scroll(function(){
        var _window_scroll_t = $(window).scrollTop();

        if( _window_scroll_t>=_table_header_t){
            $('.table-item .table').css({marginTop: '124px'});
            $('.table-item .table-head').css({borderBottom: '1px solid #ddd'});
            $('.table-title').addClass('locate');
            $('.table-item .table-head').addClass('thead-nav');
        }else{
            $('.table-item .table').css({marginTop: '0'});
            $('.table-item .table-head').css({borderBottom: '0'});
            $('.table-title').removeClass('locate');
            $('.table-item .table-head').removeClass('thead-nav');
        }
    })
    $('.resource-table').css({height:_window_h-270+'px'});
    $(window).resize(function(){
        var _window_h = $(window).height();

        _window_w = $(window).width();
        $('.resource-table').css({height:_window_h-270+'px'});
    });
});