//视频订单列表页
function grabList(){
    // 控制左侧导航选中
    if(!$('#video .media-manage .to-grab-list').hasClass('active')){
        $('.menu-level-1').each(function(){
            $(this).removeClass('active');
        });
        $('.menu-level-2').each(function(){
            $(this).removeClass('active');
        });
        $('.menu-level-3').each(function(){
            $(this).removeClass('active');
        });

        $('#video.menu-level-1').addClass('active');
        $('#video.menu-level-1 .menu-level-2.media-manage').addClass('active');
        $('#video.menu-level-1 .menu-level-2.media-manage .menu-level-3.to-grab-list').addClass('active');
    }

    //分页处理样式
    $(".pagination li a").each(function(){
        $(this).removeAttr("href");
        $(this).attr("style","cursor:pointer;");
    });
    //分页处理
    $(".pagination li a").click(function(){
        $(".main-stage .grab-list-form input.page").attr("value", $(this).attr("data-page"));
        $(".main-stage .grab-list-form").submit();
    });

}

