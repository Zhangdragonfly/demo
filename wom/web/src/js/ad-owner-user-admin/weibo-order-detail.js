$(function(){
    //鼠标放上去显示完整信息ID篇
    $("a[data-value]").each(function() {
        var a = $(this);
        var value = a.attr('data-value');
        if (value == undefined || value == "") return;
        a.data('data-value', value).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+value+"</div>").appendTo($(".table-normal")).css({ top: offset.top + a.outerHeight(), left: offset.left + a.outerWidth() -10 }).fadeIn(function () {
                });
            },
            function(){ $(".show-all-info").remove();
            }
        );
    });
})