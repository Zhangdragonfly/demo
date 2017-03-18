$(function(){
    //鼠标移入显示账号全称
    $("a[data-title]").each(function() {
        showFullName(this);
    });
    //鼠标移入显示活动名称全称
    $('.remark').each(function(){
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

    //选择下拉单
    $('.search-quick .dropdown-menu').on('click','li',selectedOption);
    //下拉单选择某一个
    function selectedOption(){
        var _text = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }
})