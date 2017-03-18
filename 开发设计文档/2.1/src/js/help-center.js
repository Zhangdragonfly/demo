$(function(){
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
})






