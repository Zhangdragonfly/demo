$(function(){
    //获取验证码
    $(".code-insure .get-code").on("click",function(){
            var whole_time = 60,active_time = 1;
            $(this).hide();
            $(".code-insure .unclick").show();
            function update() {
                if (active_time == whole_time) {
                    $(".code-insure .unclick").hide();
                    $(".get-code").show();
                    clearInterval(timer);
                    surplus_time = 60;
                    $(".code-insure .unclick").children("i").text(surplus_time);
                    return false;
                } else {
                    var surplus_time = whole_time - active_time;
                    $(".code-insure .unclick").children("i").text(surplus_time);
                }
                active_time++;
            }
            timer = setInterval(update, 1000);
    })

    //提交申请
    $(".submit-application").on("click",function(){
        var max_widthdraw_deposit_account = parseInt($(".max-widthdraw-deposit-account").text()),
            widthdraw_deposit_account_thistime = parseInt($.trim($(".widthdraw-deposit-account-thistime").val())),
            payee = $.trim($(".input-payee").val()),
            account_number = $.trim($(".input-account-number").val()),
            verify_code = $.trim($(".verify-code").val());
        $(".column .must-fill").each(function(){
            var column_title = $(this).siblings(".column-title").children("em").text();
            if($(this).val() == ""){
                wom_alert.msg({
                    icon:"warning",
                    content:"请输入"+column_title,
                    delay_time:1500
                })
                return false;
            }else{
                if(isNaN(widthdraw_deposit_account_thistime)){
                    wom_alert.msg({
                        icon:"warning",
                        content:"提现金额必须为数字",
                        delay_time:1500
                    })
                    return false;
                }
                if(widthdraw_deposit_account_thistime > max_widthdraw_deposit_account){
                    wom_alert.msg({
                        icon:"warning",
                        content:"提现金额超出最大可提金额",
                        delay_time:1500
                    })
                    return false;
                }
                wom_alert.msg({
                    icon:"finish",
                    content:"提现申请已提交成功!",
                    delay_time:1500
                })
            }
        })

    })




})