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
            return false;
        }else{
            if(isNaN(pay_account)){
                tips.show().text("请输入数字");
                return false;
            }
            tips.hide();
        }
        var pay_way_current = $('.pay-way i.choosed').parent().find('span').text();
        var pay_form_url = $('input#pay-form-url').val();
        var pay_offline_url = $('input#pay-offline-url').val();
        if(pay_way_current == '支付宝'){
            // 打开支付宝付款页面
            window.open(pay_form_url+'&amount='+pay_account);
        }else if(pay_way_current == '线下支付'){
            location.href = pay_offline_url;
        }

    })
})