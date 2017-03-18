$(function(){
/*****************基础资料**************/
    //字数限制函数
    function fontNumberLimit(element,location,num){
        var fontNumber = element.val().length;
        if (fontNumber <= num) {
            location.text(num - fontNumber);
        }
    }
    //公司简介字数限制
    $(".enterprise-info").on("input","textarea",function(){
        fontNumberLimit($(this),$(".enterprise-info .tips em"),120);
    });

    //基础资料保存
    $('.btn-save-basic-data').on("click",function(){
        var nickname = $.trim($(".nickname input").val()),
            location = $.trim($(".location input").val()),
            contact_person = $.trim($(".contact-person input").val()),
            weixin = $.trim($(".weixin input").val()),
            qq = $.trim($(".qq input").val()),
            company_name = $.trim($(".company-name input").val()),
            company_site = $.trim($(".company-site input").val()),
            company_address = $.trim($(".company-address input").val()),
            company_synopsis = $.trim($(".company-synopsis textarea").val());
            /**
             *
             * write your code here...
             * **/
            wom_alert.msg({
                icon:"finish",
                content:"保存成功!",
                delay_time:1500
            })
    })

/*********修改手机号码modal层*******/
        //获取验证码
        $(".modify-phone-number .get-code").on("click",function(){
            var cell_phone_val = $.trim($(".modify-phone-number .input-phone-number").val());
                cell_phone_reg= /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/,
                _this_tips = $(".modify-phone-number .new-phone-number").children(".tips");
            if(cell_phone_val == ""){
                _this_tips.addClass("show");
                return false;
            }else if(!cell_phone_reg.test(cell_phone_val)){
                _this_tips.addClass("show").text("手机号码格式不正确");
                return false;
            } else{
                _this_tips.removeClass("show");
                var whole_time = 60,active_time = 1;
                $(this).hide();
                $(".modify-phone-number .unclick").show();
                function update() {
                    if (active_time == whole_time) {
                        $(".modify-phone-number .unclick").hide();
                        $(".get-code").show();
                        clearInterval(timer);
                        surplus_time = 60;
                        $(".modify-phone-number .unclick").children("i").text(surplus_time);
                        return false;
                    } else {
                        var surplus_time = whole_time - active_time;
                        $(".modify-phone-number .unclick").children("i").text(surplus_time);
                    }
                    active_time++;
                }
                timer = setInterval(update, 1000);
            }
        })

        //修改手机号码点击保存
        $(".btn-save-modify-phone-number").on("click",function(){
            var verify_code_val = $.trim($(".verify-code").val());
            $(".modify-phone-number .column").children("input").each(function(){
                if($(this).val() == "") {
                    $(this).siblings(".tips").addClass("show");
                }
            })
            if(verify_code_val != ""){
                $(".code-insure").children(".tips").removeClass("show");
            }
            if($(".modify-phone-number .show").length == 0){
                $(this).attr("data-dismiss","modal");
                wom_alert.msg({
                    icon:"finish",
                    content:"保存成功!",
                    delay_time:"1000"
                })
            }
        })

/********修改登录密码*********/
    //失焦时验证原登录密码的正确性
    $(".content-modify-password .prev-password").on("blur","input",function(){
        var now_password_val = $.trim($(this).val());
    })

    //新设密码
    $('.content-modify-password .new-password input').on("blur",function(){
        var psd_val = $.trim($(this).val());
        var psd_reg= /^[a-zA-Z0-9]{6,20}$/;
        if(psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请输入密码");
        }else if( psd_val.length < 6 ||  psd_val.length >20){
            $(this).siblings(".tips").addClass("show").text("密码长度6-20位");
        }else if(!psd_reg.test(psd_val)){
            $(this).siblings(".tips").addClass("show").text("密码格式不正确");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //确认新密码
    $('.content-modify-password .confirm-password input').on("blur",function(){
        var insure_psd_val = $.trim($(this).val());
        var psd_val = $.trim($(".content-modify-password .new-password input").val());
        if(insure_psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请确认密码");
        }else if( insure_psd_val != psd_val){
            $(this).siblings(".tips").addClass("show").text("两次密码不一致");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //修改登录密码保存
    $(".btn-save-modify-password").on("click",function(){
        var verify_code_val = $.trim($(".content-modify-password .verify-code").val());
        $(".content-modify-password .column").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        })
        if(verify_code_val != ""){
            $(".content-modify-password .code-insure").children(".tips").removeClass("show");
        }
        if($(".content-modify-password .show").length == 0){
            wom_alert.msg({
                icon:"finish",
                content:"修改登录密码成功!",
                delay_time:1500
            })
        }
    })

/*********新设支付密码***********/
    //新设支付密码
    $('.set-paypassword input').on("blur",function(){
        var psd_val = $.trim($(this).val());
        var psd_reg= /^[a-zA-Z0-9]{6,20}$/;
        if(psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请输入密码");
        }else if( psd_val.length < 6 ||  psd_val.length >20){
            $(this).siblings(".tips").addClass("show").text("密码长度6-20位");
        }else if(!psd_reg.test(psd_val)){
            $(this).siblings(".tips").addClass("show").text("密码格式不正确");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //确认密码
    $('.confirm-paypassword input').on("blur",function(){
        var insure_psd_val = $.trim($(this).val());
        var psd_val = $.trim($(".set-paypassword input").val());
        if(insure_psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请确认密码");
        }else if( insure_psd_val != psd_val){
            $(this).siblings(".tips").addClass("show").text("两次密码不一致");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //手机号码
    $('.content-set-paypassword .phone-number').on("blur","input",function(){
        var cell_phone_val = $.trim($(this).val());
        var cell_phone_reg= /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if(cell_phone_val == ""){
            $(this).siblings(".tips").addClass("show").text("请输入手机号码");
        }else if(!cell_phone_reg.test(cell_phone_val)){
            $(this).siblings(".tips").addClass("show").text("手机号码格式不正确");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //获取验证码
    $(".content-set-paypassword .get-code").on("click",function(){
        var cell_phone_val = $.trim($(".content-set-paypassword .input-phone-number").val());
        cell_phone_reg= /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/,
            _this_tips = $(".phone-number").children(".tips");
        if(cell_phone_val == ""){
            _this_tips.addClass("show");
        }else if(!cell_phone_reg.test(cell_phone_val)){
            _this_tips.addClass("show").text("手机号码格式不正确");
        } else{
            _this_tips.removeClass("show");
            var whole_time = 60,active_time = 1;
            $(this).hide();
            $(".content-set-paypassword .unclick").show();
            function update() {
                if (active_time == whole_time) {
                    $(".content-set-paypassword .unclick").hide();
                    $(".get-code").show();
                    clearInterval(timer);
                    surplus_time = 60;
                    $(".content-set-paypassword .unclick").children("i").text(surplus_time);
                    return false;
                } else {
                    var surplus_time = whole_time - active_time;
                    $(".content-set-paypassword .unclick").children("i").text(surplus_time);
                }
                active_time++;
            }
            timer = setInterval(update, 1000);
        }
    })
    //新设支付密码保存
    $(".btn-save-set-paypassword").on("click",function(){
        var verify_code_val = $.trim($(".content-set-paypassword .verify-code").val());
        $(".content-set-paypassword .column").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        })
        if(verify_code_val != ""){
            $(".code-insure").children(".tips").removeClass("show");
        }
        if($(".content-set-paypassword .show").length == 0){
           wom_alert.msg({
               icon:"finish",
               content:"新设支付密码成功!",
               delay_time:1500
           })
        }
    })

})
