$(function(){
    //选择支付方式
    $('.pay-way li i').click(function(){
        $(this).addClass('choosed');
        $(this).parent().siblings().children('i').removeClass('choosed');
    })
    //点击下一步
    $(".next-step").on("click",function(){
        var pay_account = $(".input-pay-account").val();
        var tips = $(".pay-account .tips");
        if( pay_account == ""){
            tips.show().text("请输入充值金额");
        }else{
            if(isNaN(pay_account)){
                tips.show().text("请输入数字");
                return false;
            }
            tips.hide();
        }
    })
})