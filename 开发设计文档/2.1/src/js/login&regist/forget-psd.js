$(function(){
    //手机号码
    $('.cell-phone').on("blur",function(){
        var cell_phone_val = $.trim($(this).val());
        var cell_phone_reg= /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if(cell_phone_val == ""){
            $(this).siblings(".tips").addClass("show").text("请输入手机号码");
        }else if(!cell_phone_reg.test(cell_phone_val)){
            $(this).siblings(".tips").addClass("show").text("手机号码格式不正确");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })

    //获取验证码
    $(".get-code").on("click",function(){
        var cell_phone_val = $.trim($(".cell-phone").val());
        var cell_phone_reg= /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if(cell_phone_val == ""){
            $(".cell-phone").siblings(".tips").addClass("show");
            return false;
        }else if(!cell_phone_reg.test(cell_phone_val)){
            $(".cell-phone").siblings(".tips").addClass("show").text("手机号码格式不正确");
            return false;
        } else{
            $(".cell-phone").siblings(".tips").removeClass("show");
            var whole_time = 66,active_time = 1;
            $(this).hide();
            $(".unclick").show();
            function update() {
                if (active_time == whole_time) {
                    $(".unclick").hide();
                    $(".get-code").show();
                    clearInterval(timer);
                    surplus_time = 66;
                    $(".unclick").children("i").text(surplus_time);
                    return false;
                } else {
                    var surplus_time = whole_time - active_time;
                    $(".unclick").children("i").text(surplus_time);
                }
                active_time++;
            }
            timer = setInterval(update, 1000);
        }
    })

    //下一步
    $(".next-step").on("click",function(){
        var verify_code_val = $.trim($(".verify-code").val());
        $(".input-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        })
        if(verify_code_val != ""){
            $(".code-insure").children(".tips").removeClass("show");
        }
        if($(".show").length == 0){
            window.location.href = "forget-psd-step-two.html";
        }
    })

    //密码
    $('.psd').on("blur",function(){
        var psd_val = $.trim($(this).val());
        var psd_reg= /^(?![a-zA-Z0-9]+$)(?![^a-zA-Z/D]+$)(?![^0-9/D]+$).{8,20}$/
        if(psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请输入密码");
        }else if( psd_val.length < 8 ||  psd_val.length >20){
            $(this).siblings(".tips").addClass("show").text("密码长度要求8-20位");
        }else if(!psd_reg.test(psd_val)){
            $(this).siblings(".tips").addClass("show").text("密码必须包含数字,字母,特殊字符");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })

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
    })

    //确认重置密码
    $(".affirm-change").on("click",function(){
        var verify_code_val = $.trim($(".verify-code").val());
        $(".input-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        })
        if(verify_code_val != ""){
            $(".code-insure").children(".tips").removeClass("show");
        }
        if($(".show").length == 0){
            window.location.href="forget-psd-complete.html";
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