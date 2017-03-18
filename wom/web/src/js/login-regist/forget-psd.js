$(function(){
    //手机号码
    $('.cell-phone').on("blur",function(){
        var cell_phone = $.trim($(this).val());
        var cell_phone_reg= /^[1-9]\d{10}$/;
        if(cell_phone == ""){
            $(this).siblings(".tips").addClass("show").text("请输入手机号码");
        }else if(!cell_phone_reg.test(cell_phone)){
            $(this).siblings(".tips").addClass("show").text("手机号码格式不正确");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
        //验证手机是否存在
        var is_phone_exist_url = $('#id-is-phone-exist-url').val();
        $.ajax({
            url: is_phone_exist_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {phone: cell_phone},
            success: function (resp) {
                if(resp.err_code == 0 && resp.is_exist == 0){
                    $(".cell-phone").siblings(".tips").addClass("show").text("手机号码不存在");
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                return false;
            }
        });
    });

    //获取验证码
    $(".get-code").on("click",function(){
        var cell_phone = $.trim($(".cell-phone").val());
        var cell_phone_reg= /^[1-9]\d{10}$/;
        var this_tips = $(".cell-phone").siblings(".tips");
        if(cell_phone == ""){
            this_tips.addClass("show");
            return false;
        }else if(!cell_phone_reg.test(cell_phone)){
            this_tips.addClass("show").text("手机号码格式不正确");
            return false;
        } else {
            // ajax判断该手机号是否已经注册
            var is_phone_exist_url = $('#id-is-phone-exist-url').val();
            $.ajax({
                url: is_phone_exist_url,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {phone: cell_phone},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        if (resp.is_exist == 0) {
                            // 手机号不存在
                            this_tips.addClass("show").text("手机号不存在");
                            return false;
                        } else {
                            this_tips.removeClass("show");
                            sendPhoneVerifyCode(cell_phone);
                        }
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统出错",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    return false;
                }
            });
        }
    });

    /**
     * 发送短信验证码
     */
    function sendPhoneVerifyCode(phone) {
        var whole_time = 60;
        var active_time = 1;
        $(".get-code").hide();
        $(".unclick").show();
        function update() {
            if (active_time == whole_time) {
                $(".unclick").hide();
                $(".get-code").show();
                clearInterval(timer);
                surplus_time = 60;
                $(".unclick").children("i").text(surplus_time);
                return false;
            } else {
                var surplus_time = whole_time - active_time;
                $(".unclick").children("i").text(surplus_time);
            }
            active_time++;
        }

        timer = setInterval(update, 1000);

        // 请求后端发送验证码
        var send_mobile_captcha_url = $('#id-send-mobile-captcha-url').val();
        $.ajax({
            url: send_mobile_captcha_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {cell_phone: phone},
            success: function (resp) {
                if (resp.err_code == 0) {
                    var verify_code = resp.verify_code;
                } else if (resp.err_code == 1) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错",
                    delay_time: 1500
                });
                return false;
            }
        });
    }

    //下一步
    $(".next-step").on("click",function(){
        var cell_phone = $.trim($(".cell-phone").val());
        var verify_code = $.trim($(".verify-code").val());
        $(".input-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        });
        if(verify_code != ""){
            $(".code-insure").children(".tips").removeClass("show");
        }

        var forget_pwd_url = $('#id-forget-password-url').val();
        if($(".tips.show").length == 0){
            $.ajax({
                url: forget_pwd_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {cell_phone: cell_phone, verify_code: verify_code, step: 1},
                success: function (resp) {
                    if(resp.err_code == 0){
                        forget_pwd_url = forget_pwd_url.replace('_cell_phone_', cell_phone).replace('_step_', 2);
                        window.location.href = forget_pwd_url;
                    } else if(resp.err_code == 1){
                        $(".code-insure").children(".tips").addClass("show");
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    return false;
                }
            });
        }
    });

    //密码
    $('.psd').on("blur",function(){
        var psd_val = $.trim($(this).val());
        var psd_reg= /^[a-zA-Z0-9]{6,20}$/;
        if(psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请输入密码");
        }else if( psd_val.length < 6 ||  psd_val.length >20){
            $(this).siblings(".tips").addClass("show").text("密码长度要求6-20位");
        }else if(!psd_reg.test(psd_val)){
            $(this).siblings(".tips").addClass("show").text("密码由6-20位的字母或数字组成");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    });

    //确认密码
    $('.insure-psd').on("blur",function(){
        var insure_psd_val = $.trim($(this).val());
        var psd_val = $.trim($(".psd").val());
        if(insure_psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请确认密码");
        }else if( insure_psd_val != psd_val){
            $(this).siblings(".tips").addClass("show").text("两次密码不一致");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    });

    //确认重置密码
    $(".affirm-change").on("click",function(){
        var psd_val = $.trim($(".psd").val());
        var insure_psd_val = $.trim($(".insure-psd").val());
        var cell_phone = $.trim($("input[name=cell_phone]").val());
        var verify_code_val = $.trim($(".verify-code").val());
        $(".input-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        });
        if(verify_code_val != ""){
            $(".code-insure").children(".tips").removeClass("show");
        }

        var forget_pwd_url = $('#id-forget-password-url').val();
        if($(".tips.show").length == 0){
            $.ajax({
                url: forget_pwd_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    cell_phone:cell_phone,
                    psd_val:psd_val,
                    insure_psd_val:insure_psd_val,
                    step:2
                },
                success: function (resp) {
                    if(resp.err_code == 0){
                        forget_pwd_url = forget_pwd_url.replace('_step_', 3);
                        window.location.href = forget_pwd_url;
                    } else if(resp.err_code == 1){
                        $(".code-insure").children(".tips").addClass("show");
                        $(".code-insure").children(".tips").text("重置密码失败");
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    return false;
                }
            });

        }
    })

    // ~~~~~~~~~~~侧边栏联系方式~~~~~~~~
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