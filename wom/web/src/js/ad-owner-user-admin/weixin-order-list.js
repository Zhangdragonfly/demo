$(function () {
    //执行链接确认执行还是反馈
    $("#modal-to-verify-execute-link").find(".radio-select li").on("click", "i", function () {
        $(this).addClass("selected").parent().siblings().children("i").removeClass("selected");
        var feedback_mark = $(".feedback-operate").children("i");
        if (feedback_mark.hasClass("selected")) {
            $(".feedback").show();
        } else {
            $(".feedback").hide();
        }
    });
    //字数统计
    function getLength(str) {
        // 匹配中文字符的正则表达式： [\u4e00-\u9fa5]
        return String(str).replace(/[\u4e00-\u9fa5]/g, 'aa').length;
    }
    //字数限制函数
    function fontNumberLimit(element, location, num) {
        var fontNumber = Math.ceil(getLength(element.val()) / 2);
        if (fontNumber <= num) {
            location.text(num - fontNumber);
        } else {
            var now_con = element.val();
            var max_con = now_con.substr(0, num - 1);
            $(element).val(max_con);
        }
    }
    //反馈字数限制
    $(".feedback-textarea").on("input", function () {
        fontNumberLimit($(this), $(".count-num"), 100);
    });

    // 操作及操作按钮的配置
    // operation code => operation btn
    // var operation_button_config = {
    //     "update_order": "btn-update-order",//修改
    //     "cancel_order": "btn-cancel-order",//取消
    //     "pay_order": "btn-pay-order",//支付
    //     "direct_order_detail": "btn-direct-order-detail",//直投详情
    //     "to_verify_execute_link": "btn-to-verify-execute-link",//确认执行
    //     "show_report": "btn-show-report",//查看报告
    //     "invalid_order_info": "btn-invalid-order-info",//原因
    //     "arrange_order_detail": "btn-arrange-order-detail",//执行前详情
    //     "arrange_order_more_detail": "btn-arrange-order-more-detail",//执行中详情
    //     "show_execute_link": "btn-show-execute-link",//执行链接
    //     "show_effect_shots": "btn-show-effect-shots",//执行效果截图
    //     "show-empty": "btn-show-empty"//执行结果为空
    // };
    // var weixin_pos_config = {
    //     'pos_s': '单图文',
    //     'pos_m_1': '多图文头条',
    //     'pos_m_2': '多图文2条',
    //     'pos_m_3': '多图文3-N条'
    // };
    // var pub_type_config = {
    //     '-1': '未设置',
    //     '0': '不接单',
    //     '1': '只发布',
    //     '2': '只原创'
    // };

    //初始化表格信息展示
    // $('.content .order-table tbody tr').each(function () {
    //     var order_config_str = $(this).find('.order-config').text();
    //     var order_config = JSON.parse(order_config_str);
    //     init_one_order_record(this, order_config);
    // });

    /**
     * 页面加载时,渲染每个账号
     * @param _this
     * @param _order_config
     */
    // function init_one_order_record(_this, _order_config) {
    //     var _order_account_uuid = _order_config.order_uuid;
    //     var _pub_type = _order_config.pub_type;
    //     var _pos_code = _order_config.pos_code;
    //     var _pos_label = weixin_pos_config[_pos_code];
    //     var _order_status = _order_config.order_status;
    //     var _operate_action = _order_config.operate_action;
    //
    //     displayOperationBtn(_this, _order_config);
    //     displayExecuteResult(_this, _order_config);
    // }

    /**
     * 显示操作按钮
     * @param _order_config
     */
    // function displayOperationBtn(_this, _order_config) {
    //     var operate_action_list = _order_config.operate_action;
    //     for (var i = 0; i < operate_action_list.length; i++) {
    //         var operation_action = operate_action_list[i];
    //         if (operation_action in operation_button_config) {
    //             var class_btn_operation = operation_button_config[operation_action];
    //             $(_this).find('.' + class_btn_operation).show();
    //             $(_this).find('.' + class_btn_operation).next().show();
    //         } else {
    //             $(_this).find('.' + class_btn_operation).hide();
    //             $(_this).find('.' + class_btn_operation).next().hide();
    //         }
    //     }
    // }
    //
    // function displayExecuteResult(_order_config) {
    //     var operation_result_list = _order_config.execute_result
    // }




    //直投订单详情图片的显示
    $(".btn-view-cover-pic").on("click",function(){
        $(".view-cover-pic").slideToggle(200);
    })
})
// pjax 刷新事件
function pjaxRef() {
    // 订单修改
    $('.order-table').on('click', '.btn-update-order', function () {
        var order_config_arr = $(this).parents('tr').find('.order-config').text();
        var order_config = JSON.parse(order_config_arr);
        var order_uuid = order_config.order_uuid;
        var plan_uuid = order_config.plan_uuid;
        var pub_type = order_config.pub_type;
        var pos_code = order_config.pos_code;
        editAdContent(pub_type, order_uuid, pos_code, plan_uuid);
    });

    // 订单支付
    $('.order-table').on('click', '.btn-pay-order', function () {
        var order_config_arr = $(this).parents('tr').find('.order-config').text();
        var order_config = JSON.parse(order_config_arr);
        var order_uuid = order_config.order_uuid;
        var plan_uuid = order_config.plan_uuid;
        var pub_type = order_config.pub_type;
        var pos_code = order_config.pos_code;
        editAdContent(pub_type, order_uuid, pos_code, plan_uuid);
    });

    // 订单取消
    $('.order-table').on('click', '.btn-cancel-order', function(){
        var order_config_arr = $(this).parents('tr').find('.order-config').text();
        var order_config = JSON.parse(order_config_arr);
        var order_uuid = order_config.order_uuid;
        var url = $('#cancel-order-url').val();
        wom_alert.confirm({
            content:"确定取消订单吗？"
        },function(){
            $.ajax({
                url: url,
                type: 'GET',
                cache: false,
                dataType: 'json',
                data: {
                    order_uuid: order_uuid
                },
                success: function (resp) {
                    if (resp.err_code == 0) {
                        location.reload();
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统出错!",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            });
        })
    });

    // 打开(直接投放)详情弹框
    $('.content').on('click', '.order-table .btn-direct-order-detail', function () {
        var order_uuid = $(this).attr('data-order-uuid');
        var get_direct_order_detail_url = $('input#get-direct-order-detail-url').val();
        var external_file_url = $("#id-external-file-url").val();//图片存储路径
        $.ajax({
            url: get_direct_order_detail_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    var order_detail = resp.order_detail;
                    var con_del_tag = order_detail.article_content.replace(/<\/?.+?>/g,"");
                    var plain_con = con_del_tag.replace(/&nbsp;/g,"");
                    $('#modal-direct-order-detail .activ-name .active-name-show').text(order_detail.plan_name);
                    $('#modal-direct-order-detail .carry-out-time .execute-time').text(order_detail.execute_time);
                    $('#modal-direct-order-detail .article-to-lead a.fl').text(order_detail.original_mp_url);
                    $('#modal-direct-order-detail .title .title-name').text(order_detail.title);
                    $('#modal-direct-order-detail .author .author-name').text(order_detail.author);
                    $('#modal-direct-order-detail .text-content .requirements-con').text(plain_con);
                    $('#modal-direct-order-detail .org-text a.fl').text(order_detail.link_url);
                    $('#modal-direct-order-detail .abstract p.fl').text(order_detail.article_short_desc);
                    $('#modal-direct-order-detail .view-cover-pic').attr("src",external_file_url+order_detail.cover_img);
                    var prove_quality_file_name = order_detail.cert_img_urls.split(",");
                    $('#modal-direct-order-detail .curry-on-remarks p.fl').text(order_detail.comment);
                    $('#modal-direct-order-detail').modal('show');
                    var file_con = '';
                    var quality_li_length = $('#modal-direct-order-detail .prove-quality ul li').length;
                    if(quality_li_length > 0){
                        return false;
                    }
                    for(i in prove_quality_file_name){
                        file_con += '<li>'+prove_quality_file_name[i]+'</li>';
                    }
                    $('#modal-direct-order-detail .prove-quality ul').append(file_con);
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });

    // 确认执行链接提交
    $('#modal-to-verify-execute-link').on('click', '.btn-submit', function () {
        var order_uuid = $('#modal-to-verify-execute-link').find('.order-uuid').val();
        // 确认(默认)
        var confirm = $('#modal-to-verify-execute-link .radio-select .confirm-operate i').hasClass('selected');
        if (confirm) {
            var to_verify_execute_link_url = $('input#to-verify-execute-link-url').val();
            $.ajax({
                url: to_verify_execute_link_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    order_uuid: order_uuid
                },
                success: function (resp) {
                    if (resp.err_code == 0) {
                        $('#modal-to-verify-execute-link').modal('hide');
                        location.reload();
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统出错!",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            });

        } else {//反馈
            var feedback_execute_link_url = $('input#feedback-execute-link-url').val();
            var order_feedback = $.trim($('#modal-to-verify-execute-link .feedback-textarea').val());
            if (order_feedback == '') {
                wom_alert.msg({
                    icon: "warning",
                    content: '请填写反馈内容',
                    delay_time: 1000
                });
                return false;
            }

            $.ajax({
                url: feedback_execute_link_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    order_uuid: order_uuid,
                    order_feedback: order_feedback
                },
                success: function (resp) {
                    if (resp.err_code == 0) {
                        $('#modal-to-verify-execute-link').modal('hide');
                        location.reload();
                    } else {
                        wom_alert.msg({
                            icon: "error",
                            content: "系统出错!",
                            delay_time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            });
        }
    });


    //执行链接弹框
    $('.execute-result').on('click', '.btn-show-execute-link', function () {
        // 获取执行链接和截图
        var order_uuid = $(this).parent().parent().parent().find('.order-account span').text();
        var url = $("input#get-execute-link-url").val();
        $.post(url,
            {'order_uuid': order_uuid},
            function (data, status) {
                if (status == 'success') {
                    // 设置链接和截图
                    $('#modal-show-execute-link .link-address a').text(data.info.publish_url);
                    $('#modal-show-execute-link .link-address a').attr('href', data.info.publish_url);
                    $('#modal-show-execute-link .link-address a').attr('target', '_blank');
                    var publish_screenshot_arr = data.info.publish_screenshot.split(',');
                    var pic = "";
                    $.each(publish_screenshot_arr, function (index, image) {
                        pic += '<img src="' + image + '" height="94px" width="74px">';
                    });
                    $('#modal-show-execute-link .pic-show').html(pic);
                    $('#modal-show-execute-link').modal('show');
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统异常",
                        delay_time: 2000
                    })
                }
            });
    });

    //流单原因
    $('.content').on('click', '.order-table .btn-flow-order-reason', function () {
        var order_uuid = $(this).attr('data-order-uuid');
        var get_reason_url = $("input#get-order-reason-url").val();
        $.ajax({
            url: get_reason_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {order_uuid: order_uuid,type:"flow"},
            success: function (resp) {
                if (resp.err_code == 0) {
                    $('#modal-invalid-order-info .reason-con .reason-show').text(resp.refuse_content);
                    $('#modal-invalid-order-info').modal('show');
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });

    //拒单原因
    $('.content').on('click', '.order-table .btn-refuse-order-reason', function () {
        var order_uuid = $(this).attr('data-order-uuid');
        var get_reason_url = $("input#get-order-reason-url").val();
        $.ajax({
            url: get_reason_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {order_uuid: order_uuid,type:"refuse"},
            success: function (resp) {
                if (resp.err_code == 0) {
                    $('#modal-invalid-order-info .reason-con .reason-show').text(resp.refuse_content);
                    $('#modal-invalid-order-info').modal('show');
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });


    //确认执行链接弹框
    $('.content').on('click', '.order-table .btn-to-verify-execute-link', function () {
        // 获取执行链接和截图
        var order_uuid = $(this).attr('data-order-uuid');
        var get_execute_link_url = $("input#get-execute-link-url").val();
        //获取本地图片路径
        var external_file_url = $("#id-external-file-url").val();

        $.ajax({
            url: get_execute_link_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {order_uuid: order_uuid},
            success: function (resp) {
                if (resp.err_code == 0) {
                    $('#modal-to-verify-execute-link').find('.order-uuid').val(order_uuid);
                    $('#modal-to-verify-execute-link .link-address a').text(resp.publish_result['publish_url']);
                    $('#modal-to-verify-execute-link .link-address a').attr('href', resp.publish_result['publish_url']);
                    $('#modal-to-verify-execute-link .link-address a').attr('target', '_blank');
                    if (resp.order_track) {
                        // 已反馈,隐藏反馈选项
                        $('#modal-to-verify-execute-link .feedback-operate').hide();
                    } else {
                        // 未反馈,显示反馈选项
                        $('#modal-to-verify-execute-link .feedback-operate').show();
                    }

                    var publish_screenshot_arr = [];
                    if(resp.publish_result.publish_screenshot != '' && resp.publish_result.publish_screenshot != null){
                        publish_screenshot_arr = resp.publish_result.publish_screenshot.split(',');
                    }
                    var pic = "";
                    $.each(publish_screenshot_arr, function (index, image) {
                        pic += '<img src="' + external_file_url+image + '" height="94px" width="74px">';
                    });
                    $('#modal-to-verify-execute-link .pic-show').html(pic);
                    $('#modal-to-verify-execute-link').modal('show');
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });


    // 查看报告
    $('.order-table').on('click', '.btn-show-report', function(){
        var order_uuid = $(this).attr('data-order-uuid');
        var show_report_url = $('input#show-report-url').val().replace('_order_uuid_', order_uuid);
        window.open(show_report_url);
    });

}



function editAdContent(_pub_type, _order_uuid, _pos_code, _plan_uuid){
    if (_pub_type == 1) {
        // 直接投放
        var edit_direct_content_url = $('#id-edit-direct-content-url').val().replace('_order_uuid_', _order_uuid).replace('_pos_code_', _pos_code).replace('_plan_uuid_', _plan_uuid);
        window.location.href = edit_direct_content_url+ '&pay=1';
    } else if (_pub_type == 2) {
        // 原创约稿
        var edit_arrange_content_url = $('#id-edit-arrange-content-url').val().replace('_order_uuid_', _order_uuid).replace('_pos_code_', _pos_code).replace('_plan_uuid_', _plan_uuid)+ '&submit=1';
        window.location.href = edit_arrange_content_url;
    }
}
