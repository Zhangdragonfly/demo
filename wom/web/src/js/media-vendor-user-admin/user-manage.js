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
            nickname = $.trim($(".nickname input").val());
            location = $.trim($(".location input").val());
        var url = "";
        $.post(url,{
            contact_person: contact_person,
            weixin: weixin,
            qq: qq,
            company_name: company_name,
            company_site: company_site,
            company_address: company_address,
            company_synopsis: company_synopsis,
            nickname: nickname,
            location: location,
        },function(data, status){
            if(status == 'success'){
                wom_alert.msg({
                    icon:"finish",
                    content:data.err_msg,
                    delay_time:1500
                });
            }else{
                wom_alert.msg({
                    icon:"error",
                    content:"系统异常!",
                    delay_time:1500
                });
            }
        });
    })

/*********修改手机号码modal层*******/
        //手机号码
        $('.modify-phone-number .new-phone-number').on("blur","input",function(){
            var url = $('input#is-phone-exist-url').val();
            var cell_phone_val = $.trim($(this).val());
            var cell_phone_reg= /^[1-9]\d{10}$/;
            if(cell_phone_val == ""){
                $(this).siblings(".tips").addClass("show").text("请输入手机号码");
                return false;
            }else if(!cell_phone_reg.test(cell_phone_val)){
                $(this).siblings(".tips").addClass("show").text("手机号码格式不正确");
                return false;
            }else{
                $(this).siblings(".tips").removeClass("show");
            }
            //验证手机是否存在
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {cell_phone:cell_phone_val},
                success: function (resp) {
                    if(resp.err_code == 0){

                        return false;
                    } else if(resp.err_code == 1){
                        //$(".new-phone-number .tips").addClass("show").text("手机号码不存在");
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    return false;
                }
            });
        })
        //获取验证码
        $(".modify-phone-number .get-code").on("click",function(){
            var url = $('input#get-verify-code-url').val();
            var cell_phone_val = $.trim($(".modify-phone-number .input-phone-number").val());
                cell_phone_reg= /^[1-9]\d{10}$/,
                _this_tips = $(".modify-phone-number .new-phone-number").children(".tips");
            if(cell_phone_val == ""){
                _this_tips.addClass("show");
                return false;
            }else if(!cell_phone_reg.test(cell_phone_val)){
                _this_tips.addClass("show").text("手机号码格式不正确");
                return false;
            } else{
                // ajax判断该手机号是否已经注册
                var is_phone_exist_url = $('input#is-phone-exist-url').val();
                $.ajax({
                    url: is_phone_exist_url,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    data: {phone: cell_phone_val},
                    success: function (resp) {
                        if (resp.err_code == 0) {
                            if (resp.is_exist == 1) {
                                // 手机号存在
                                wom_alert.msg({
                                    icon: "error",
                                    content: "手机号已经存在",
                                    delay_time: 1500
                                });
                                return false;
                            }else{
                                _this_tips.removeClass("show");
                                var whole_time = 60,active_time = 1;
                                $(".modify-phone-number .get-code").hide();
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
                                //请求后端发送验证码
                                $.ajax({
                                    url: url,
                                    type: 'POST',
                                    cache: false,
                                    dataType: 'json',
                                    data: {cell_phone:cell_phone_val},
                                    success: function (resp) {
                                        if(resp.err_code == 0){
                                            return false;
                                        } else if(resp.err_code == 1){
                                            return false;
                                        }
                                    },
                                    error: function (XMLHttpRequest, msg, errorThrown) {
                                        return false;
                                    }
                                });
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
                        wom_alert.msg({
                            icon: "error",
                            content: "系统出错",
                            delay_time: 1500
                        });
                        return false;
                    }
                });

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
                var verify_code = $('.code-insure input').val();
                var cell_phone = $('.new-phone-number input').val();
                var url = $('input#update-phone-url').val();
                $.post(url,{
                    cell_phone: cell_phone,
                    verify_code: verify_code
                },function(data, status){
                    if(status == 'success'){
                        if(data.err_code == 0){
                            wom_alert.msg({
                                icon:"finish",
                                content:data.err_msg,
                                delay_time:1500
                            });
                            $('.regist-email').text(cell_phone);
                        }else{
                            wom_alert.msg({
                                icon:"error",
                                content:data.err_msg,
                                delay_time:1500
                            })
                        }

                    }else{
                        wom_alert.msg({
                            icon:"error",
                            content:"系统异常!",
                            delay_time:1500
                        })
                    }
                });
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
            }else{
                $(this).siblings(".tips").removeClass("show");
            }
        })
        if(verify_code_val != ""){
            $(".content-modify-password .code-insure").children(".tips").removeClass("show");
        }
        if($(".content-modify-password .show").length == 0){
            var password = $('.prev-password input').val();
            var new_password = $('.new-password input').val();
            $.post('',{
                password: password,
                new_password: new_password
            },function(data, status){
                 if(status == 'success'){
                     if(data.err_code == 0){
                         wom_alert.msg({
                             icon:"finish",
                             content:data.err_msg,
                             delay_time:1500
                         })
                     }else{
                         wom_alert.msg({
                             icon:"error",
                             content:data.err_msg,
                             delay_time:1500
                         })
                     }

                 }else{
                     wom_alert.msg({
                         icon:"error",
                         content:"系统异常!",
                         delay_time:1500
                     })
                 }
            });

        }
    })
})
