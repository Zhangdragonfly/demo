$(function(){
    //鼠标移入显示账号全称
    $("a[data-title]").each(function() {
        showFullName(this);
    });
    //鼠标移入显示活动名称全称
    $('.active-full-name').each(function(){
        showFullName(this);
    })
    //账号ID显示全称
    $('.ewm-ID em').each(function(){
        showFullName(this);
    })
    //显示全称封装
    function showFullName(_this){
        var title = $(_this).attr('data-title');
        if (title == undefined || title == "") return;
        $(_this).data('data-title', title).hover(function () {
                var offset = $(_this).offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($(".table")).css({ top: offset.top + $(_this).outerHeight() + 10, left: offset.left + $(_this).outerWidth() - 6 }).fadeIn(function () {
                });
            },
            function(){ $(".show-all-info").remove();
            }
        );
    }
    //表格二维码图片显示
    $('.ewm').hover(function(){
        $(this).siblings('img').css({display:'block'});
    },function(){
        $(this).siblings('img').css({display:'none'});
    })
    //~~~~~~判断有无流水信息~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 10){
            $(".table-footer").hide();
            if(resourceLength < 1){
                $(".no-resource").show();
            }else{
                $(".no-resource").hide();
            }
        }else{
            $(".table-footer").show();
        }
    }
    isResource();

})
