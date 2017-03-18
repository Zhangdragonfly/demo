$(function () {
    //鼠标放上去显示完整信息
    $("a[data-value]").each(function () {
        var a = $(this);
        var title = a.attr('data-value');
        if (title == undefined || title == "") return;
        a.data('data-value', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>" + title + "</div>").appendTo($(".table-wrap")).css({
                    top: offset.top + a.outerHeight(),
                    left: offset.left + a.outerWidth() + 1
                }).fadeIn(function () {
                });
            },
            function () {
                $(".show-all-info").remove();
            }
        );
    });

    //二维码显示
    $('.ewm').hover(function () {
        $(this).siblings('img').css({display: 'block'});
    }, function () {
        $(this).siblings('img').css({display: 'none'});
    });

    var weixin_pos_config = {
        'pos_s': '单图文',
        'pos_m_1': '多图文头条',
        'pos_m_2': '多图文2条',
        'pos_m_3': '多图文3-N条'
    };
    var pub_type_config = {
        '-1': '未设置',
        '0': '不接单',
        '1': '只发布',
        '2': '只原创'
    };

    var head_avg_read_num = 0,
        total_follower_num = 0,
        pay_all_sum = 0;

    // 页面渲染时判断显示的发布类型
    $('.source-choosed-table .one-account').each(function () {
        var order_available_pos_config = JSON.parse($(this).find('.order-available-pos-config').val());

        init_one_order_record(this, order_available_pos_config);
    });

    /**
     * 加载页面时,初始化每条记录
     * @param _this
     * @param _order_available_pos_config
     */
    function init_one_order_record(_this, _order_available_pos_config) {
        var selected_pos_code = '';
        var selected_pos_label = '';
        var has_add_content = 0;
        var retail_price = 0;
        var pub_type = -1;

        if (_order_available_pos_config.pos_s.is_selected == 1) {
            selected_pos_code = 'pos_s';
            selected_pos_label = weixin_pos_config['pos_s'];
            retail_price = _order_available_pos_config.pos_s.retail_price;
            has_add_content = _order_available_pos_config.pos_s.has_add_content;
            pub_type = _order_available_pos_config.pos_s.pub_type;

            // 控制备注里的高亮显示
            $(_this).find('.area-remark .one-pos').removeClass('set-font-color');
            $(_this).find('.area-remark .one-pos.pos-s').addClass('set-font-color');
        }
        if (_order_available_pos_config.pos_m_1.is_selected == 1) {
            selected_pos_code = 'pos_m_1';
            selected_pos_label = weixin_pos_config['pos_m_1'];
            retail_price = _order_available_pos_config.pos_m_1.retail_price;
            has_add_content = _order_available_pos_config.pos_m_1.has_add_content;
            pub_type = _order_available_pos_config.pos_m_1.pub_type;

            // 控制备注里的高亮显示
            $(_this).find('.area-remark .one-pos').removeClass('set-font-color');
            $(_this).find('.area-remark .one-pos.pos-m-1').addClass('set-font-color');
        }
        if (_order_available_pos_config.pos_m_2.is_selected == 1) {
            selected_pos_code = 'pos_m_2';
            selected_pos_label = weixin_pos_config['pos_m_2'];
            retail_price = _order_available_pos_config.pos_m_2.retail_price;
            has_add_content = _order_available_pos_config.pos_m_2.has_add_content;
            pub_type = _order_available_pos_config.pos_m_2.pub_type;

            // 控制备注里的高亮显示
            $(_this).find('.area-remark .one-pos').removeClass('set-font-color');
            $(_this).find('.area-remark .one-pos.pos-m-2').addClass('set-font-color');
        }
        if (_order_available_pos_config.pos_m_3.is_selected == 1) {
            selected_pos_code = 'pos_m_3';
            selected_pos_label = weixin_pos_config['pos_m_3'];
            retail_price = _order_available_pos_config.pos_m_3.retail_price;
            has_add_content = _order_available_pos_config.pos_m_3.has_add_content;
            pub_type = _order_available_pos_config.pos_m_3.pub_type;

            // 控制备注里的高亮显示
            $(_this).find('.area-remark .one-pos').removeClass('set-font-color');
            $(_this).find('.area-remark .one-pos.pos-m-3').addClass('set-font-color');
        }
        if (selected_pos_code == '') {
            // 默认选中 "多图文头条"
            selected_pos_code = 'pos_m_1';
            selected_pos_label = weixin_pos_config['pos_m_1'];
            retail_price = _order_available_pos_config.pos_m_1.retail_price;
            has_add_content = _order_available_pos_config.pos_m_1.has_add_content;
            pub_type = _order_available_pos_config.pos_m_1.pub_type;

            // 控制备注里的高亮显示
            $(_this).find('.area-remark .one-pos').removeClass('set-font-color');
            $(_this).find('.area-remark .one-pos.pos-m-1').addClass('set-font-color');
        }

        $(_this).find('.area-pos-select .selected-pos').text(selected_pos_label);
        $(_this).find('.area-retail-price .retail-price').text(retail_price);

        // "状态"和"操作"两列
        changeStatusAndOperate($(_this), has_add_content);

        // 加载投放位置下拉框
        var available_pos_list = {};
        if (_order_available_pos_config.pos_s.pub_type != 0) {
            available_pos_list['pos_s'] = weixin_pos_config['pos_s'];
        }
        if (_order_available_pos_config.pos_m_1.pub_type != 0) {
            available_pos_list['pos_m_1'] = weixin_pos_config['pos_m_1'];
        }
        if (_order_available_pos_config.pos_m_2.pub_type != 0) {
            available_pos_list['pos_m_2'] = weixin_pos_config['pos_m_2'];
        }
        if (_order_available_pos_config.pos_m_3.pub_type != 0) {
            available_pos_list['pos_m_3'] = weixin_pos_config['pos_m_3'];
        }
        var available_pos_select = $(_this).find('.available-pos-list');
        for (var _pos_code in available_pos_list) {
            var _pos_label = available_pos_list[_pos_code];
            var pos_cls = '';
            if (_pos_code == 'pos_s') {
                pos_cls = 'pos-s';
            } else if (_pos_code == 'pos_m_1') {
                pos_cls = 'pos-m-1';
            } else if (_pos_code == 'pos_m_2') {
                pos_cls = 'pos-m-2';
            } else if (_pos_code == 'pos_m_3') {
                pos_cls = 'pos-m-3';
            }
            available_pos_select.append('<li class=' + '"one-pos' + ' ' + pos_cls + '" data-code="' + _pos_code + '">' + _pos_label + '</li>');
        }

        // 各个位置的发布类型
        var pos_s_pub_type_label = pub_type_config[_order_available_pos_config.pos_s.pub_type];
        var pos_m_1_pub_type_label = pub_type_config[_order_available_pos_config.pos_m_1.pub_type];
        var pos_m_2_pub_type_label = pub_type_config[_order_available_pos_config.pos_m_2.pub_type];
        var pos_m_3_pub_type_label = pub_type_config[_order_available_pos_config.pos_m_3.pub_type];

        $(_this).find('.area-remark .one-pos.pos-m-1 .pub-type-label').text(pos_m_1_pub_type_label);
        $(_this).find('.area-remark .one-pos.pos-m-2 .pub-type-label').text(pos_m_2_pub_type_label);
        $(_this).find('.area-remark .one-pos.pos-m-3 .pub-type-label').text(pos_m_3_pub_type_label);
        $(_this).find('.area-remark .one-pos.pos-s .pub-type-label').text(pos_s_pub_type_label);

        $(_this).attr('data-order-uuid', _order_available_pos_config.order_uuid);
        $(_this).attr('data-retail-price', retail_price);
        $(_this).attr('data-pub-type', pub_type);
        $(_this).attr('data-pos-code', selected_pos_code);
        $(_this).attr('data-head-avg-read-num', _order_available_pos_config.head_avg_read_num);
        $(_this).attr('data-total-follower-num', _order_available_pos_config.total_follower_num);
        $(_this).attr('data-has-add-content', has_add_content);
    }

    countSomeStatistics();

    /**
     * 计算一些指标
     */
    function countSomeStatistics() {
        var total_retail_price = 0; // 总零售价
        var total_retail_price_online_pay = 0; // 总在线支付价
        var total_read_num = 0; // 阅读数
        var total_follower_num = 0; // 粉丝数
        var total_account = 0; // 账号数

        var pub_type = -1;
        var retail_price = 0;
        $('.source-choosed-table .one-account').each(function () {
            total_account++;
            pub_type = $(this).attr('data-pub-type');
            retail_price = Number($(this).attr('data-retail-price'));
            total_retail_price += retail_price;
            if (pub_type == 1) {
                // 只发布
                total_retail_price_online_pay += retail_price;
            }
            total_read_num += Number($(this).attr('data-head-avg-read-num'));
            total_follower_num += Number($(this).attr('data-total-follower-num'));
        });

        $('.area-stat .stat-account-cnt').text(total_account);
        $('.area-stat .stat-total-read-num').text(total_read_num);
        $('.area-stat .stat-total-follower-num').text(total_follower_num);
        $('.area-stat .stat-total-retail-price').text(total_retail_price);
        $('.area-stat .stat-total-retail-price').attr('data-total-price', total_retail_price);
        $('.area-stat .stat-total-retail-price-to-pay').text(total_retail_price_online_pay);
        $('.area-stat .stat-total-retail-price-to-pay').attr('data-price-to-pay', total_retail_price_online_pay);
    }

    // 投放位置变化时的联动变化
    $('.source-choosed-table .area-pos-select').on('click', '.available-pos-list .one-pos', function () {
        var _this_account = $(this).parents('.one-account');
        var order_available_pos_config = JSON.parse(_this_account.find('.order-available-pos-config').val());

        var pos_code = $(this).attr('data-code');
        _this_account.find('.area-pos-select .selected-pos').text(weixin_pos_config[pos_code]);

        if (pos_code == 'pos_s') {
            retail_price = order_available_pos_config.pos_s.retail_price;
            var has_add_content = order_available_pos_config.pos_s.has_add_content;
            var retail_price = order_available_pos_config.pos_s.retail_price;
            var pub_type = order_available_pos_config.pos_s.pub_type;

            _this_account.find('.area-retail-price .retail-price').text(retail_price);
            _this_account.attr('data-retail-price', retail_price);
            _this_account.attr('data-pub-type', pub_type);
            _this_account.attr('data-pos-code', pos_code);
            _this_account.attr('data-has-add-content', has_add_content);

            changeStatusAndOperate(_this_account, has_add_content);
            changeRemark(_this_account, pos_code);
        } else if (pos_code == 'pos_m_1') {
            retail_price = order_available_pos_config.pos_m_1.retail_price;
            var has_add_content = order_available_pos_config.pos_m_1.has_add_content;
            var retail_price = order_available_pos_config.pos_m_1.retail_price;
            var pub_type = order_available_pos_config.pos_m_1.pub_type;

            _this_account.find('.area-retail-price .retail-price').text(retail_price);
            _this_account.attr('data-retail-price', retail_price);
            _this_account.attr('data-pub-type', pub_type);
            _this_account.attr('data-pos-code', pos_code);
            _this_account.attr('data-has-add-content', has_add_content);

            changeStatusAndOperate(_this_account, has_add_content);
            changeRemark(_this_account, pos_code);
        } else if (pos_code == 'pos_m_2') {
            retail_price = order_available_pos_config.pos_m_2.retail_price;
            var has_add_content = order_available_pos_config.pos_m_2.has_add_content;
            var retail_price = order_available_pos_config.pos_m_2.retail_price;
            var pub_type = order_available_pos_config.pos_m_2.pub_type;

            _this_account.find('.area-retail-price .retail-price').text(retail_price);
            _this_account.attr('data-retail-price', retail_price);
            _this_account.attr('data-pub-type', pub_type);
            _this_account.attr('data-pos-code', pos_code);
            _this_account.attr('data-has-add-content', has_add_content);

            changeStatusAndOperate(_this_account, has_add_content);
            changeRemark(_this_account, pos_code);
        } else if (pos_code == 'pos_m_3') {
            retail_price = order_available_pos_config.pos_m_3.retail_price;
            var has_add_content = order_available_pos_config.pos_m_3.has_add_content;
            var retail_price = order_available_pos_config.pos_m_3.retail_price;
            var pub_type = order_available_pos_config.pos_m_3.pub_type;

            _this_account.find('.area-retail-price .retail-price').text(retail_price);
            _this_account.attr('data-retail-price', retail_price);
            _this_account.attr('data-pub-type', pub_type);
            _this_account.attr('data-pos-code', pos_code);
            _this_account.attr('data-has-add-content', has_add_content);

            changeStatusAndOperate(_this_account, has_add_content);
            changeRemark(_this_account, pos_code);
        }

        countSomeStatistics();

        // 记录投放位置的改变
        var order_uuid = _this_account.attr('data-order-uuid');
        var retail_price = _this_account.attr('data-retail-price');
        var pos_code = _this_account.attr('data-pos-code');
        var pub_type = _this_account.attr('data-pub-type');
        var change_order_pos_url = $('#id-change-order-pos-url').val().replace('_order_uuid_', order_uuid);
        $.ajax({
            url: change_order_pos_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {order_uuid: order_uuid, pos_code: pos_code, pub_type: pub_type, retail_price: retail_price},
            success: function (resp) {
                if (resp.err_code == 0) {
                    // TODO

                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统异常!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统异常!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });
    //改变需求添加状态和操作
    function changeStatusAndOperate(_this, _has_add_content) {
        if (_has_add_content == 1) {
            // 已经添加需求
            // 状态
            _this.find('.area-req-status .status-not-add-content').hide();
            _this.find('.area-req-status .status-has-add-content').show();
            // 操作
            _this.find('.area-operate .add-content').hide();
            _this.find('.area-operate .update-content').show();
        } else if (_has_add_content == 0) {
            // 未添加需求
            // 状态
            _this.find('.area-req-status .status-has-add-content').hide();
            _this.find('.area-req-status .status-not-add-content').show();
            // 操作
            _this.find('.area-operate .update-content').hide();
            _this.find('.area-operate .add-content').show();
        }
    }

    // 遍历备注的发布类型高亮显示
    function changeRemark(_this, _pos_code) {
        _this.find('.area-remark .one-pos').removeClass('set-font-color');
        if (_pos_code == 'pos_s') {
            _this.find('.area-remark .one-pos.pos-s').addClass('set-font-color');
        } else if (_pos_code == 'pos_m_1') {
            _this.find('.area-remark .one-pos.pos-m-1').addClass('set-font-color');
        } else if (_pos_code == 'pos_m_2') {
            _this.find('.area-remark .one-pos.pos-m-2').addClass('set-font-color');
        } else if (_pos_code == 'pos_m_3') {
            _this.find('.area-remark .one-pos.pos-m-3').addClass('set-font-color');
        }
    }

    // 继续添加账号
    $('.btn-add-more-media').on('click', function () {
        var weixin_media_list_url = $('#id-weixin-media-list-url').val();
        window.location.href = weixin_media_list_url;
    });

    // 删除账号
    $('.source-choosed-table').on('click', '.area-operate .delete-order', function () {
        var _this_account = $(this).parents('.one-account');
        var order_uuid = _this_account.attr('data-order-uuid');

        var delete_media_url = $('#id-weixin-media-delete-url').val().replace('_order_uuid_', order_uuid);
        // 往后台发送请求
        wom_alert.confirm({
            content: '确定移除该账号吗?'
        },function(){
            $.ajax({
                url: delete_media_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {order_uuid: order_uuid},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        // 提示是否删除
                        _this_account.remove();
                        wom_alert.msg({
                            icon: "finish",
                            content: "删除成功!",
                            delay_time: 1500
                        });

                        countSomeStatistics();
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统异常!",
                        delay_time: 1500
                    });
                    return false;
                }
            });
        });
    });

    // 添加需求
    $('.source-choosed-table').on('click', '.area-operate .add-content', function () {
        var _this_account = $(this).parents('.one-account');
        var order_uuid = _this_account.attr('data-order-uuid');
        var pub_type = _this_account.attr('data-pub-type');
        var pos_code = _this_account.attr('data-pos-code');
        editAdContent(pub_type, order_uuid, pos_code);
    });

    // 修改需求
    $('.source-choosed-table').on('click', '.area-operate .update-content', function () {
        var _this_account = $(this).parents('.one-account');
        var order_uuid = _this_account.attr('data-order-uuid');
        var pub_type = _this_account.attr('data-pub-type');
        var pos_code = _this_account.attr('data-pos-code');
        editAdContent(pub_type, order_uuid, pos_code);
    });

    function editAdContent(_pub_type, _order_uuid, _pos_code){
        if (_pub_type == 1) {
            // 直接投放
            var edit_direct_content_url = $('#id-edit-direct-content-url').val().replace('_order_uuid_', _order_uuid).replace('_pos_code_', _pos_code);
            window.location.href = edit_direct_content_url;
        } else if (_pub_type == 2) {
            // 原创约稿
            var edit_arrange_content_url = $('#id-edit-arrange-content-url').val().replace('_order_uuid_', _order_uuid).replace('_pos_code_', _pos_code);
            window.location.href = edit_arrange_content_url;
        }
    }

    // 提交并支付
    $('.btn-to-pay').on('click', function(){
        // 判断是否所有账号都添加了需求
        var has_not_add_content_cnt = 0;
        $('.source-choosed-table .one-account').each(function(){
            var has_add_content = $(this).attr('data-has-add-content');
            if(has_add_content == 0){
                has_not_add_content_cnt++;
            }
        });
        if(has_not_add_content_cnt > 0){
            wom_alert.msg({
                icon: "error",
                content: "存在账号未添加需求!",
                delay_time: 2000
            });
            return false;
        }
        var total_price_to_pay = $('.area-stat .stat-total-retail-price-to-pay').attr('data-price-to-pay');
        var total_retail_price = $('.area-stat .stat-total-retail-price').attr('data-total-price');
        var plan_uuid = $('#id-plan-uuid').val();
        if(total_price_to_pay == 0){
            var weixin_plan_submit_arrange_order = $('input#id-weixin-plan-submit-arrange-order').val();
            $.ajax({
                url: weixin_plan_submit_arrange_order,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {plan_uuid: plan_uuid},
                success: function (resp) {
                    if (resp.err_code == 0) {
                        // 清除微信媒体资源选中的cookie
                        Cookies.remove('weixin-media-selected-to-put-in');
                        window.location.href = $('#id-admin-weixin-plan-list-url').val();
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统错误!",
                            delay_time: 1500
                        });
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误!",
                        delay_time: 1500
                    });
                }
            });
        } else {
            var pay_confirm_url = $('#id-pay-confirm-url').val();
            var confirm_msg = '需支付金额: '+ total_price_to_pay+ '元 <br>确认提交吗?';
            wom_alert.confirm({
                content: confirm_msg
            },function(){
                $.ajax({
                    url: pay_confirm_url,
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {plan_uuid: plan_uuid, total_retail_price: total_retail_price, total_price_to_pay: total_price_to_pay},
                    success: function (resp) {
                        if (resp.err_code == 0) {
                            // 清除微信媒体资源选中的cookie
                            Cookies.remove('weixin-media-selected-to-put-in');
                            window.location.href = pay_confirm_url;
                        } else {
                            wom_alert.msg({
                                icon: "error",
                                content: "系统错误!",
                                delay_time: 1500
                            });
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统错误!",
                            delay_time: 1500
                        });
                    }
                });
            });
        }
    });
})
//滚动定位
$(function () {
    $('.data-show').addClass('data-show-position');
    var _foot_t = $('.footer-wrap').offset().top;

    $(window).scroll(function () {
        var _scroll_t = $(window).scrollTop();
        if (_scroll_t >= _foot_t - $(window).height()) {
            $('.data-show').removeClass('data-show-position');
        } else {
            $('.data-show').addClass('data-show-position');
        }
    })
})
//~~~~~侧边栏联系方式~~~~~
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