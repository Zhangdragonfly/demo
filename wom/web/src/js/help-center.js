$(function(){
    //控制导航选中
    $('.nav .help-center').addClass('active-nav');
    var oLi = $('.helpMainList li'),
        sideLi = $('.helpContList li'),
        aBtn = oLi.find('a'),
        sideABtn = sideLi.find('a'),
        showBox = $('.helpCenterMain .helpMainDiv');

    //侧边栏一级切换
    sideABtn.on('click',function() {
        var self = $(this),
            oUl = self.parents('ul'),
            index = self.parent('li').index(),
            type = parseInt(oUl.attr('data-help-type'));


        $('.helpCenterMain').eq(index).show().siblings('.helpCenterMain').hide();
        $('.helpCenterSide').find('li').removeClass('current')

        if(!self.parent('li').hasClass('current')) {
            self.parent('li').addClass('current');
            $('.helpCenterMain').eq(type).show().siblings('.helpCenterMain').hide();
            $('.helpCenterMain').eq(type).find('.helpMainDiv').eq(index).stop(true,true).show().siblings().hide();
        }

    })

    //主内容二级切换
    aBtn.on('click',function() {
        var self = $(this),
            oParent = self.parent('li'),
            next = oParent.next('.helpMainTxt');

        if(!oParent.hasClass('current')) {
            oParent.addClass('current').siblings('li').removeClass('current');
            self.parents('.helpMainDiv').find('.helpMainTxt').slideUp('fast');
            next.stop(true,true).slideDown('fast')
        }else {
            oParent.removeClass('current');
            next.stop(true,true).slideUp('fast')
        }

    })
    /*特例样式处理*/
    $('.u-nav li').eq(4).attr('id','current_nav').addClass('current').siblings().removeAttr('id');

    //根据哈希值显示对应的内容
    var hType = window.location.hash || '#adv';
    switch(hType) {
        case '#adv':
            $('.helpContList[data-help-type="0"]').find('li').eq(0).addClass('current');
            $('.helpCenterMain>.helpMainDiv').eq(0).show();
            break;

        case '#med':
            $('.helpContList[data-help-type="1"]').find('li').eq(0).addClass('current');
            $('.helpCenterMain').eq(1).show().siblings('.helpCenterMain').hide();
            $('.helpCenterMain').eq(1).find('.helpMainDiv').eq(0).show();
            break;

        case '#order':
            $('.helpContList[data-help-type="1"]').find('li').eq(1).addClass('current');
            $('.helpCenterMain').eq(1).show().siblings('.helpCenterMain').hide();
            $('.helpCenterMain').eq(1).find('.helpMainDiv').eq(1).show();
            break;

        default:
            $('.helpContList[data-help-type="0"]').find('li').eq(0).addClass('current');
            $('.helpCenterMain>.helpMainDiv').eq(0).show();
            break;

    }

    function test(){
        var obj=document.getElementById('hidden')
        if(obj.style.display=="none"){
            obj.style.display="";
        }
        else if(obj.style.display!="none"){
            obj.style.display="none";
        }
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
            $("input[name=search-media]").attr("placeholder","输入账号名称/ID");
        }
        if(type=="weibo"){
            $("input[name=search-media]").attr("data-url",weibo_list_url);
            $("input[name=search-media]").attr("placeholder","输入账号名称");
        }
        if(type=="video"){
            $("input[name=search-media]").attr("data-url",video_list_url);
            $("input[name=search-media]").attr("placeholder","输入平台名称/ID");
        }
    }
    //顶部搜索
    $(".search-media").click(function(){
        var search_url = $("input[name=search-media]").data('url');
        var search_name = $("input[name=search-media]").val();
        if(search_name == ""){
            return false;
        }
        window.location.href = search_url+"&search_name="+search_name;
    });
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






