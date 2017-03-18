$(function(){
    // ~~~~~~~~~视频微信微博订单选项卡~~~~~~
    function resourceNavTab(_thisLi,_thisResource){
        _thisLi.addClass("span-choosed").siblings().removeClass("span-choosed");
        _thisResource.find(".resource-con").removeClass("show").eq(_thisLi.index()).addClass("show");
    }
    $(".order-tab span").on("click",function(){
        $(this).addClass("span-choosed").siblings().removeClass("span-choosed");
        $(".tab-con").removeClass("show").eq($(this).index()).addClass("show");
    });
    //限制字数
    (function plainContentLengthLimit(){
        $('.plain-text-length-limit').each(function(){
            var content = $(this).text().trim();
            var length_limit = $(this).attr('data-limit');
            var content_length = content.length;
            if(length_limit == undefined){
                length_limit = 5;
            }
            if(content_length > length_limit){
                $(this).text(content.substr(0, length_limit) + '...');
            }
            $(this).attr('data-value', content);
        })
    })();
    //备注展示
    $('.order-remark').hover(function () {
        $(this).siblings('.whole-remark').stop().show();
    }, function () {
        $(this).siblings('.whole-remark').stop().hide();
    });










})