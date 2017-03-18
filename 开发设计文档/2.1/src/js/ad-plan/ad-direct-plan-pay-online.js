// ~~~~~~~~~~~侧边栏联系方式~~~~~~~~
$(function(){
    $(".side-bar li").hover(function(){
        $(this).css("background","#c81624");
        $(this).children("div").stop().animate({
            left:'-155px'
        },500);
    },function(){
        $(this).css("background","#4c4c4c");
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
//~~~~~~~ 支付方式~~~~~~~~
    $(".pay-way .cost").on("click",function(){
        $(this).addClass("on").siblings().removeClass("on");
        $(".pay-account .balance").css("display","none");
        $(".balance-enough").css("display","none");
        $(".pay-insure").show();
        $(".no-pay-insure").hide();
    })
    $(".pay-way .wompay").on("click",function(){
        $(".pay-account .balance").css("display","block");
        var recharge = Number($(".recharge i").text());
        var credit = Number($(".credit-extension i").text());
        var pay_amount = Number($(".pay-amount i").text());
        var price_spread = recharge + credit - pay_amount;
        if(price_spread < pay_amount){
            $(".balance-enough").css("display","block");
            $(".pay-insure").hide();
            $(".no-pay-insure").show();
        }
    })