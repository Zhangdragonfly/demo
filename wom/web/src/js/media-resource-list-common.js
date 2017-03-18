$(function () {
    //条件选择更多开始.
    $('.filter-area ul li:gt(23)').css('display', 'none');
    $('.select-more').click(function () {
        if ($('.filter-area ul li:gt(23)').css('display') == 'none') {
            $('.filter-area ul li:gt(23)').stop().slideDown(200);
            $(this).text('收起');
        } else {
            $('.filter-area ul li:gt(23)').stop().slideUp(200);
            $(this).text('更多');
        }
    });
    //标题解释
    $('.thead-title').hover(function () {
        $(this).children('.explain-title').stop().slideDown(0);
    }, function () {
        $(this).children('.explain-title').stop().slideUp(0);
    });
    // 购物车联系方式开始
    $('.contact-bg').hover(function () {
        $(this).next('span').css({display: 'block', right: '46px'})
        $(this).next('span').addClass('contact-way-on');
    }, function () {
        $(this).next('span').css({display: 'none', right: '-216px'})
        $(this).next('span').removeClass('contact-way-on');
    });
    //购物车箭头开关事件
    $('.left-border span').click(function () {
        $('.contact').stop().animate({right: '0'});
        $('.right-box').stop().animate({right: '-420px'});
    });
    //表格二维码图片显示
    $('.account-id-area').hover(function () {
        $(this).children('img').css({display: 'block'});
    }, function () {
        $(this).children('img').css({display: 'none'});
    });
    //购物车是否开放
    $('.contact').on('click', '.contact-shopping-cart', shoppingSwitch);
    // ~~~~~~~限制字符串长度~~~~~~~~
    (function constraintLength() {
        for (var i = 0; i < $('.synopsis').length; i++) {
            var strlen = $('.synopsis').eq(i).data('str');
            if ($('.synopsis').eq(i).text().length > strlen) {
                $('.synopsis').eq(i).text($('.synopsis').eq(i).text().trim().substr(0, strlen));
                $('.synopsis').eq(i).html($('.synopsis').eq(i).html() + "...");
            }
        }
    })();
});
//判断购物车是否开放
function shoppingSwitch() {
    var _deriction_switch = $(this).parents('.contact').siblings('.right-box');
    if (_deriction_switch.css('right') == '-420px') {
        _deriction_switch.stop().animate({right: '0'}, 500);
        $(this).parents('.contact').stop().animate({right: '360px'}, 500);
    } else {
        _deriction_switch.stop().animate({right: '-420px'}, 500);
        $(this).parents('.contact').stop().animate({right: '0'}, 500);
    }
}
//滚动定位
$(function () {
    var _table_header_t = $('.table-title').offset().top;
    var _window_w = $(window).width();
    var _window_h = $(window).height();
    var AscrollTd = $('.media-stage #scrollTd td');
    var AscrollTh = $('.media-stage #scrollTh th');
    var arr = [];

    for (var i = 0; i < AscrollTd.length; i++) {
        arr.push(AscrollTd[i].offsetWidth);
        AscrollTh[i].style.width = AscrollTd[i].offsetWidth + 'px'
    }
    $(window).scroll(function () {
        var _window_scroll_t = $(window).scrollTop();
        if (_window_scroll_t >= _table_header_t + 42) {
            var condition_filter_H = $(".condition-filter").height();
            //$('.media-stage .table').css({marginTop: '124px'});
            $('.media-stage .table-head').css({borderBottom: '1px solid #ddd'});
            $('.table-title').addClass('locate');
            $('.media-stage .table-head').addClass('thead-nav');
        } else {
            $('.media-stage .table').css({marginTop: '0'});
            $('.media-stage .table-head').css({borderBottom: '0'});
            $('.table-title').removeClass('locate');
            $('.media-stage .table-head').removeClass('thead-nav');
        }
    })
    $('.card-body').css({height: _window_h - 200 + 'px'});
    $(window).resize(function () {
        var _window_h = $(window).height();
        _window_w = $(window).width();
        $('.card-body').css({height: _window_h - 200 + 'px'});
    });
});

//顶部搜索（微信、视频、微博）的下拉列表
$('.dropdown .dropdown-search-type').on('click','li',selectedTypeOption);
function selectedTypeOption(){
    var weixin_list_url = $("#id-weixin-media-list-url").val();
    var weibo_list_url = $("#id-weibo-media-list-url").val();
    var video_list_url = $("#id-video-media-list-url").val();
    var type = $(this).data('type');
    var _txet = $(this).text();
    $(this).parent().prev().find('span:eq(0)').text(_txet);
    if(type=="weixin"){
        $("input[name=search-media]").attr("data-url",weixin_list_url);
        $("input[name=search-media]").attr("placeholder","请输入微信账号/ID");
    }
    if(type=="weibo"){
        $("input[name=search-media]").attr("data-url",weibo_list_url);
        $("input[name=search-media]").attr("placeholder","请输入微博名称");
    }
    if(type=="video"){
        $("input[name=search-media]").attr("data-url",video_list_url);
        $("input[name=search-media]").attr("placeholder","请输入平台昵称/ID");
    }

}
//顶部搜索
$(".search-media").click(function(){
    resourceSearch();
});
//回车搜索
$('input[name = search-media]').bind('keypress',function(event){
    if(event.keyCode == "13"){
        resourceSearch();
    }
});
//锚点跳转函数
function anchorAdd() {
    $("html,body").animate({
        scrollTop: $(".media-stage").offset().top + "px"
    }, 500);
    return false;
}
$(window).load(function(){
    if($('input[name = search-media]').val() != ""){
        anchorAdd();
    }
})

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

//是否存在资源
function isResource(){
    var resourceLength =  $(".table tbody").children("tr").length;
    if(resourceLength < 1){
        $(".no-resource").css("display","block");
        $(".table").css("margin-bottom","0");
    }else{
        $(".no-resource").css("display","none");
        $(".table").css("margin-bottom","20px");
    }
}
isResource();
