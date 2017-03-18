$(function () {
    // 选择注册方式
    $('.choose-identity li i').click(function () {
        $(this).addClass('choosed');
        $(this).parent().siblings().children('i').removeClass('choosed');
    });

    //邮箱
    $('.email-regist').on("blur", function () {
        var email = $.trim($(this).val());
        var email_reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
        var this_tips = $(this).siblings(".tips");
        if (email == "") {
            this_tips.addClass("show").text("请输入邮箱");
        } else if (!email_reg.test(email)) {
            this_tips.addClass("show").text("邮箱格式不正确");
        } else {
            // ajax判断该邮箱是否已经注册
            var is_email_exist_url = $('input#id-is-email-exist-url').val();
            $.ajax({
                url: is_email_exist_url,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {email: email},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        if (resp.is_exist == 1) {
                            this_tips.addClass("show").text("邮箱已经存在");
                        } else {
                            this_tips.removeClass("show");
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
    });

    //密码
    $('.psd').on("blur", function () {
        var psd_val = $.trim($(this).val());
        var psd_reg = /^[a-zA-Z0-9]{6,20}$/;
        if (psd_val == "") {
            $(this).siblings(".tips").addClass("show").text("请输入密码");
        } else if (psd_val.length < 6 || psd_val.length > 20) {
            $(this).siblings(".tips").addClass("show").text("密码长度要求6-20位");
        } else if (!psd_reg.test(psd_val)) {
            $(this).siblings(".tips").addClass("show").text("密码由6-20位的字母或数字组成");
        } else {
            $(this).siblings(".tips").removeClass("show");
        }
    });

    // 确认密码
    $('.insure-psd').on('blur', function () {
        var insure_psd_val = $.trim($(this).val());
        var psd_val = $.trim($(".psd").val());
        if (insure_psd_val == '') {
            $(this).siblings(".tips").addClass("show").text("请输入确认密码");
        } else if (insure_psd_val != psd_val) {
            $(this).siblings(".tips").addClass("show").text("两次密码不一致");
        } else {
            $(this).siblings(".tips").removeClass("show");
        }
    });

    //手机号码
    $('.cell-phone').on('blur', function () {
        var phone = $.trim($(this).val());
        var cell_phone_reg = /^[1-9]\d{10}$/;
        var this_tips = $(this).siblings(".tips");
        if (phone == "") {
            this_tips.addClass("show").text("请输入手机号码");
        } else if (!cell_phone_reg.test(phone)) {
            this_tips.addClass("show").text("手机号码格式不正确");
        } else {
            // ajax判断该手机号是否已经注册
            var is_phone_exist_url = $('input#id-is-phone-exist-url').val();
            $.ajax({
                url: is_phone_exist_url,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {phone: phone},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        if (resp.is_exist == 1) {
                            this_tips.addClass("show").text("手机号已经存在");
                            return false;
                        } else {
                            this_tips.removeClass("show");
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
    });

    // 获取验证码
    $(".get-code").on("click", function () {
        var phone = $.trim($(".cell-phone").val());
        var phone_reg = /^[1-9]\d{10}$/;
        var this_tips = $(".cell-phone").siblings(".tips");
        if (phone == "") {
            this_tips.addClass("show").text("请输入手机号码");
            return false;
        } else if (!phone_reg.test(phone)) {
            this_tips.addClass("show").text("手机号码格式不正确");
            return false;
        } else {
            // ajax判断该手机号是否已经注册
            var is_phone_exist_url = $('input#id-is-phone-exist-url').val();
            $.ajax({
                url: is_phone_exist_url,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {phone: phone},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        if (resp.is_exist == 1) {
                            // 手机号存在
                            this_tips.addClass("show").text("手机号已经存在");
                            return false;
                        } else {
                            this_tips.removeClass("show");
                            sendPhoneVerifyCode(phone);
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

    //填写验证码
    $(".verify-code").on("blur", function () {
        var verify_code = $.trim($(this).val());
        var this_tips = $(this).siblings(".tips");
        if (verify_code == '') {
            this_tips.addClass("show").text("请输入验证码");
        } else {
            this_tips.removeClass("show");
        }
    });

    // 确认注册
    $(".insure-regist").on("click", function () {
        var reg_form = $('.area-reg-form form');
        var account_type = reg_form.find('.account-type').val();
        var reg_email = $.trim(reg_form.find('.email-regist').val());
        var pwd = $.trim(reg_form.find('.psd').val());
        var phone = $.trim(reg_form.find('.cell-phone').val());
        var verify_code = $.trim(reg_form.find('.verify-code').val());
        var agree_wom = reg_form.find('input:checked').length;
        reg_form.find('.input-group input.require').each(function () {
            if ($(this).val() == '') {
                $(this).siblings('.tips').addClass('show');
            }
        });
        if (agree_wom < 1) {
            $(".agree_serve").addClass('show');
        } else {
            $(".agree_serve").removeClass('show');
        }
        if ($(".tips.show").length == 0) {
            // 检查验证码是否正确
            var check_mobile_captcha_url = $('input#id-check-mobile-captcha-url').val();
            $.ajax({
                url: check_mobile_captcha_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {phone: phone, verify_code: verify_code},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        // 验证码匹配
                        $('.area-reg-form form').submit();
                    } else if (resp.err_code == 1) {
                        // 验证码不匹配
                        $('.verify-code').siblings(".tips").addClass("show").text("验证码有误");
                        return false;
                    } else if (resp.err_code == 2) {
                        $('.verify-code').siblings(".tips").addClass("show").text("系统出错");
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
    });

    /**
     * 待删:提交注册表单
     */
    function submit_reg_form(reg_form) {
        var account_reg_url = $('#id-account-reg-url').val();
        $.ajax({
            url: account_reg_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: reg_form,
            success: function (resp) {

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

    //~~~~~侧边栏联系方式~~~~~
    $(".side-bar li").hover(function () {
        $(this).css("background", "#c81624");
        $(this).children("div").stop().animate({
            left: '-155px'
        }, 500);
    }, function () {
        $(this).css("background", "#1e1e1e");
        $(this).children("div").stop().animate({
            left: '46px'
        }, 500);
    });
    $(".side-bar .top").on("click", function () {
        $('html,body').stop().animate({
            scrollTop: '0'
        }, 500)
    });
    // 注册用户类型
    $('.choose-identity .ad-owner i').click(function () {
        $('input.account-type').val(1);
    });
    $('.choose-identity .media-vendor i').click(function () {
        $('input.account-type').val(2);
    });
});