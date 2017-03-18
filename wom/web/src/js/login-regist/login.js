$(function(){
    $(".phone-email").on("blur",function(){

    });
    var redirect_url = $('input#login-by-qr-code-url').val();
    var obj = new WxLogin({
     id:"ad_owner_login_container",
     appid: "wxc73f763daf51ab73",//wxb3e51573cdbcd9c0
     scope: "snsapi_login",
     redirect_uri: redirect_url,
     state: "state",
     style: "",
     href: "https://o9z65knq9.qnssl.com/common/css/weixin-login-qrcode.css"
   });

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

})