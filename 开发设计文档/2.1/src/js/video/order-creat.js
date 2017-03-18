$(function() {
    //**************时间戳或者日期的互相转化*****************
    $.extend({
        myTime: {
            /**
             * 当前时间戳
             * @return <int>        unix时间戳(秒)
             */
            CurTime: function () {
                return Date.parse(new Date()) / 1000;
            },
            /**
             * 日期 转换为 Unix时间戳
             * @param <string> 2014-01-01 20:20:20  日期格式
             * @return <int>        unix时间戳(秒)
             */
            DateToUnix: function (string) {
                var f = string.split(' ', 2);
                var d = (f[0] ? f[0] : '').split('-', 3);
                var t = (f[1] ? f[1] : '').split(':', 3);
                return (new Date(
                        parseInt(d[0], 10) || null,
                        (parseInt(d[1], 10) || 1) - 1,
                        parseInt(d[2], 10) || null,
                        parseInt(t[0], 10) || null,
                        parseInt(t[1], 10) || null,
                        parseInt(t[2], 10) || null
                    )).getTime() / 1000;
            },
            /**
             * 时间戳转换日期
             * @param <int> unixTime    待时间戳(秒)
             * @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)
             * @param <int>  timeZone   时区
             */
            UnixToDate: function (unixTime, isFull, timeZone) {
                if (unixTime === '') {
                    return '';
                } else {
                    if (typeof (timeZone) == 'number') {
                        unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
                    }
                    var time = new Date(unixTime * 1000);
                    var ymdhis = "";
                    ymdhis += time.getUTCFullYear() + "-";
                    ymdhis += (time.getUTCMonth() + 1) + "-";
                    ymdhis += time.getUTCDate();
                    if (isFull === true) {
                        ymdhis += " " + time.getUTCHours() + ":";
                        ymdhis += time.getUTCMinutes() + ":";
                        ymdhis += time.getUTCSeconds();
                    }
                    return ymdhis;
                }
            }
        }
    });
//*******************************

    //~~~~~~判断有无资源~~~~~~
    function isResource() {
        var resourceLength = $(".source-choosed-table tbody").children("tr").length;
        $(".source-choosed-count").text(resourceLength);
        if (resourceLength < 1) {
            $(".no-resource").css("display", "block");
        } else {
            $(".no-resource").css("display", "none");
        }
    }
    isResource();

    //~~~~~删除已选择的资源~~~~~
    $(".source-choosed-table").on("click", ".delete", function () {
        var element_delete = $(this).parents("tr");
        layer.confirm('您确定要删除该账号吗？', {
                btn: ['确定', '取消']
            }, function () {
                layer.msg('删除成功 !', {
                    icon: 1,
                    time: 1000
                });
                element_delete.remove();
                isResource();
            }
        )
    });

    //~~~~~选择下拉单~~~~~
    $('.dropdown .dropdown-menu').on('click', 'li', function () {
        var price_location = $(this).text();
        var correspond_price = $(this).parents("tr").children(".price");
        if (price_location == "软广直发") {
            correspond_price.text(1000);
        } else if (price_location == "软广转发") {
            correspond_price.text(2000);
        } else if (price_location == "微任务直发") {
            correspond_price.text(3000);
        } else {
            correspond_price.text(4000);
        }
    })

    //~~~~~接单备注~~~~~
    $('.remark').hover(function () {
        $(this).children('.whole-remark').stop().slideDown(0);
    }, function () {
        $(this).children('.whole-remark').stop().slideUp(0);
    });

    //~~~~~填写预约需求~~~~~
    //预约名称
    $(".booking-name").on("blur", function () {
        var booking_name = $.trim($(this).val());
        if (booking_name == "") {
            $(this).siblings(".tips").addClass("show");
        } else {
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //获取当前时间的毫秒数
    function getNowTime(){
        var now_time = new Date();
        var now_millisecond = now_time.getTime();
        return now_millisecond;
    };

    //预约执行时间
    $(function(){
        var plan_time_tips = $(".plan-time").children(".tips");
        var now = getNowTime().toString().substr(0,10);
        //开始时间
        $("#publish-start-time").on("blur",function(){
            var publish_start_time = $(".plan-time").children('#publish-start-time').val();
            var start = $.myTime.DateToUnix(publish_start_time);
            console.log(start);
            console.log(now);
            console.log(publish_start_time);
            if(publish_start_time != ""){
                if(start < now){
                    plan_time_tips.addClass("show").html("<i>!</i>预约执行时间必须大于当前时间");
                    return false;
                }
                plan_time_tips.removeClass("show");

            }else{
                plan_time_tips.addClass("show");
            }
        })
        //结束时间
        $("#publish-end-time").on("blur",function(){
            var publish_start_time = $(".plan-time").children('#publish-start-time').val();
            var publish_end_time = $(".plan-time").children('#publish-end-time').val();
            var start = $.myTime.DateToUnix(publish_start_time);
            var end = $.myTime.DateToUnix(publish_end_time);

            if(publish_start_time != "" && publish_end_time != ""){
                if (start >= end) {
                    plan_time_tips.addClass("show").html("<i>!</i>请选择正确的预约执行时间");
                    return false;
                }else if(start < now || end < now){
                    plan_time_tips.addClass("show").html("<i>!</i>预约执行时间必须大于当前时间");
                    return false;
                }
                plan_time_tips.removeClass("show");

            }else{
                plan_time_tips.addClass("show");
            }
        })
    })
    //联系人
    $(".contact").on("blur", function () {
        var contact = $.trim($(this).val());
        if (contact == "") {
            $(this).siblings(".tips").addClass("show");
        } else {
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //手机号码
    $('.phone-number').on("blur", function () {
        var phone_val = $.trim($(this).val());
        var phone_reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if (phone_val == "") {
            $(this).siblings(".tips").addClass("show").html("<i>!</i>请输入手机号码");
        } else if (!phone_reg.test(phone_val)) {
            $(this).siblings(".tips").addClass("show").html("<i>!</i>手机号码格式不正确");
        } else {
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //预约需求
    $("textarea").on("blur", function () {
        var contact = $.trim($(this).val());
        if (contact == "") {
            $(this).parents(".booking-require-wrap").children(".tips").addClass("show");
        } else {
            $(this).parents(".booking-require-wrap").children(".tips").removeClass("show");
        }
    })
    //需求反馈时间
    $("#feedback-time").on("blur",function(){
        var feedback_time = $(".feedback").children('#feedback-time').val();
        var feedback = $.myTime.DateToUnix(feedback_time);
        var now = getNowTime().toString().substr(0,10);
        if($(this).val != ""){
            if(feedback < now){
                $(this).siblings(".tips").addClass("show").html("<i>!</i>需求反馈时间必须大于当前时间");
                return false;
            }
            $(this).siblings(".tips").removeClass("show");
        }
    })
    //确认提交
    $(".submit").on("click", function(){
        var booking_require = $.trim($("textarea").val());
        $(".input-group").children("input").each(function () {
            if ($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        })
        if (booking_require == "") {
            $(".booking-require-wrap").children(".tips").addClass("show");
        }
        if ($(".show").length == 0) {
            layer.msg('您的预约订单已提交完成', {
                icon: 1,
                time: 1500
            });
        }
    })

    //~~~~~预约需求字数统计~~~~~~
    //聚焦、失焦事件
    $(function () {
        var title_bool = true;
        var bool = true;
        $(".booking-require").on("focus", function () {
            if (bool) {
                $(".booking-require-wrap").children(".sweet-tips").html("还可以输入 <em> 2000 </em> 个字");
                bool = false;
            }
        })
        $(".booking-require").on("blur", function () {
            if ($(".booking-require").val() == "") {
                $(".booking-require-wrap").children(".sweet-tips").html("不要超过 <em> 2000 </em> 个字");
                bool = true;
            }
        })
    })
    //将文本进行转换，得到总的字符数。
    function getLength(str) {
        // 匹配中文字符的正则表达式： [\u4e00-\u9fa5]
        return String(str).replace(/[\u4e00-\u9fa5]/g, 'aa').length;
    }

    //replace() 方法用于在字符串中用一些字符替换另一些字符，或替换一个与正则表达式匹配的子串。
    $(".booking-require").on("input", function () {
        var titleNumber = Math.ceil(getLength($(".booking-require").val()) / 2);
        if (titleNumber <= 2000) {
            $(".sweet-tips").children("em").html(2000 - titleNumber);
        } else {
            $(".booking-require-wrap").children(".sweet-tips").html("已超出<em></em>字");
            $(".sweet-tips").children("em").html(titleNumber - 2000);
        }
    })

    //视频显示完整账号ID
    $("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title)
            .removeAttr('data-title')
            .hover(
            function () {
                var offset = a.offset();
                $("<div class='show-all-info'></div>").appendTo($(".table-wrap")).html(title).css({ top: offset.top + 24, left: offset.left-5 + a.outerWidth()}).fadeIn(function () {
                    var pop = $(this);
                });
            },
            function(){
                $(".show-all-info").remove();
            }
        );
    });

    //显示完整账号ID
    $("table td").children("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title)
            .removeAttr('data-title')
            .hover(
                function () {
                    var offset = a.offset();
                    $("<div class='show-all-info'></div>").appendTo($(".source-choosed")).html(title).css({ top: offset.top + 10, left: offset.left + a.outerWidth()}).fadeIn(function () {
                        var pop = $(this);
                    });
                },
                function(){
                    $(".show-all-info").remove();
                }
            );
    });
})