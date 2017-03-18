$(function () {
    //一级导航事件
    $('.menu li').click(function(){
        $(this).addClass('current-side-nav').siblings().removeClass('current-side-nav');
    })
    //二级导航事件
    $('.menu-list a').click(function(){
        $(this).addClass('current-list-nav').siblings().removeClass('current-list-nav');
    })
});