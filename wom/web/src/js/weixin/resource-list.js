$(function () {

    //搜索名称
    $(".input-search-name").attr('value',$('#id-search-name').val());

    //控制导航栏选中
    $(".weixin-list").addClass('active-nav');
    $(".weixin-search").click();

    //选择新建、已有媒体库
    $('.dropdown .dropdown-menu-lib').on('click','li',selectedLibOption);
    function selectedLibOption(){
        var _txet = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_txet);
    }
    //选择下拉单
    $('.dropdown .dropdown-menu-weixin').on('click', 'li', selectedOption);
    //条件选择开始
    $('.filter-item-li ul.clearfix').on('click', 'li', selectCondition);
    //选择不限
    $('.filter-unlimit').click(function () {
        if ($(this).hasClass('filter-active')) {
            return false;
        } else {
            $(this).addClass('filter-active');
            $(this).siblings('ul').find('li i').removeClass('filter-active');
            var _classify_text = $(this).prev('h3').text();
            var _data_value = $(this).siblings('.dropdown').find('a').attr('data-value');
            if (_data_value == '零售价：') {
                $('.condition-selected ul li').each(function () {
                    var _text_child_text = $(this).children('span').text();
                    if (_text_child_text == _data_value) {
                        $(this).remove();
                    }
                });
                $("#form-weixin-search .input-retail-price").attr('value', '');
                $("#form-weixin-search .input-retail-price-type").attr('value', '');
            }
            switch (_classify_text) {
                case '分类：':
                    noLimitChangeCss(_classify_text);
                    $("#form-weixin-search .input-media-cate").attr('value', '');
                    break;
                case '地域：':
                    noLimitChangeCss(_classify_text);
                    $("#form-weixin-search .input-follower-area").attr('value', '');
                    break;
                case '类型：':
                    noLimitChangeCss(_classify_text);
                    $("#form-weixin-search .input-belong-tag").attr('value', '');
                    break;
                case '头条阅读数：':
                    noLimitChangeCss(_classify_text);
                    $("#form-weixin-search .input-read-num").attr('value', '');
                    break;
                case '粉丝数：':
                    noLimitChangeCss(_classify_text);
                    $("#form-weixin-search .input-follower-num").attr('value', '');
                    break;
            }
        }
        selectedLiLen()
        doSearch();
    })
    //已选条件全部删除
    $('.selected-last-li').click(function () {
        $(this).css('display', 'none');
        $(this).siblings('li').remove();
        $('.filter-item li i').removeClass('filter-active');
        $('.filter-unlimit').addClass('filter-active');
        //表单重置为空
        for (var i = 0; i < $('#form-weixin-search .form-control').length; i++) {
            $('#form-weixin-search .form-control').attr('value', '');
        }
        doSearch();
    });
    //购物车事件绑定
    // 初始化购物车

    csrf = $("input.csrf").val();
    $('.card-head').on('click', '.delete-all', emptyShoppingCar);
    //单个删除购物车内的资源
    $(document).on('click', '.shopping-resource-del', function () {
        var data_value = $(this).parent().siblings('td').find('.account-name').attr('data-value');
        var media_uuid = $(this).closest('tr').attr('data-media-uuid');
        $('.table-item tbody .account-name').each(function () {
            var _this_data = $(this).attr('data-value');
            if (_this_data == data_value) {
                $(this).parents('.account').siblings('.select-account').find('input').prop({checked: false});
            }
        })
        $(this).parents('tr').remove();
        var _len = $('.card-body tbody tr').length;
        if (_len == 0) {
            $('#checked-all').prop({checked: false});
        }
        if (_len <= 1) {
            $('.card-head .btn-danger').css('display', 'none');
        }
        $('.card-head span:eq(0) i,.contact-shopping-cart em').text(_len);
        changeShoppingPrice();
        changeShoppingFans();
        deleteMediaInCookie(media_uuid);

    });

    // //购物车全选开始
    // $('.checked-all-resource-input').click(function () {
    //     var _select_option = $(this).parents('.table-item').find('.table tbody .select-account input');
    //     if ($(this).is(':checked')) {
    //         _select_option.each(function () {
    //             if (!$(this).is(':checked')) {
    //                 $(this).click();
    //             }
    //             ;
    //         });
    //     } else {
    //         _select_option.each(function () {
    //             if ($(this).is(':checked')) {
    //                 $(this).click();
    //             }
    //             ;
    //         });
    //     }
    //     changeShoppingPrice();
    //     changeShoppingFans();
    // });
    //价格及粉丝区域的验证
    $('.filter-price-button span').click(function () {
        var _classify_text = $(this).parents('.filter-item').find('.dropdown a').attr('data-value');
        customConditionSearch(this, '.filter-value', _classify_text);
    });
    $('.filter-fans-btn span').click(function () {
        var _classify_text = $(this).parents('.filter-item').children('h3').text();
        customConditionSearch(this, '.filter-fans', _classify_text);
    });
    //排序
    $('.sort-icon').on('click', 'i', AccountSort);

    // 将购物车里的资源加入媒体库
    $('.shopping-car .card-footer .btn-add-media-lib').click(function () {
        var selected_media_uuid_array = [];

        // TODO
        var selected_media_uuid_list = '';
        $('.shopping-car .card-body table tr').each(function () {
            var media_uuid = $(this).attr('data-media-uuid');
            selected_media_uuid_array.push(media_uuid);
            selected_media_uuid_list += media_uuid + ',';
        });
        if (selected_media_uuid_array.length == 0) {
            wom_alert.msg({
                icon: "error",
                content: "请选择微信账号!",
                delay_time: 1500
            });
            return false;
        }
        // 如果有url中有lib uuid,则直接跳转到该媒体库里
        var weixin_media_lib_uuid = $('#id-weixin-media-lib-uuid').val();
        if (weixin_media_lib_uuid == '') {
            // 区分是否从购物车打开弹框
            $('.btn.btn-danger.btn-save').attr('open-by-shop-car',true);
            openMediaLibraryModal(selected_media_uuid_array);
        } else {
            var add_media_into_lib_url = $('#id-add-media-into-lib-url').val();
            $.ajax({
                url: add_media_into_lib_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    media_lib_uuid: weixin_media_lib_uuid,
                    media_lib_name: '',
                    selected_media_uuid_list: selected_media_uuid_list
                },
                beforeSend: function () {
                    //让提交按钮失效，以实现防止按钮重复点击
                },
                complete: function () {
                    //按钮重新有效
                    //$('xxx').removeAttr('disabled');
                },
                success: function (resp) {
                    if (resp.err_code == 0) {
                        // 清空cookie
                        Cookies.remove('weixin-media-selected-to-put-in');
                        window.location.href = resp.redirect_url;
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

                }
            });
        }
    });

    // 立即投放
    $('.right-box .btn-put-in-resource').click(function () {
        //console.log(Cookies.get('weixin-media-selected-to-put-in'));
        var selected_media_list = $(this).parents('.card-footer').prev().find('tbody tr');
        if (selected_media_list.length == 0) {
            wom_alert.msg({
                icon: "error",
                content: "请选择微信账号加入购物车!",
                delay_time: 1500
            });
            return false;
        }
        var selected_media_uuid_list = [];
        selected_media_list.each(function () {
            var media_uuid = $(this).find('.account .account-name').attr('data-value');
            selected_media_uuid_list.push(media_uuid);
        });

        var plan_uuid = $('#id-plan-uuid').val();
        var create_plan_url = $('#id-create-plan-url').val();

        // TODO 检查有没有登录,且是否有权限进行投放操作

        // console.log(Cookies.get('weixin-media-selected-to-put-in'));

        if (plan_uuid == '') {
            // 第1步:创建活动
            window.location.href = create_plan_url;
        } else {
            // 有plan uuid, 直接进入 第3步:填写投放内容

            // 将选中的资源post提交
            var add_media_into_plan_url = $('#id-add-media-into-plan-url').val();
            var plan_action_type = $('#id-plan-action-type').val();
            $.ajax({
                url: add_media_into_plan_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    weixin_media_selected_to_put_in: Cookies.get('weixin-media-selected-to-put-in'),
                    plan_uuid: plan_uuid
                },
                beforeSend: function () {
                    //让提交按钮失效，以实现防止按钮重复点击
                },
                complete: function () {
                    //按钮重新有效
                    //$('xxx').removeAttr('disabled');
                },
                success: function (resp) {
                    if (resp.err_code == 0) {
                        // 清空cookie
                        Cookies.remove('weixin-media-selected-to-put-in');
                        if(plan_action_type == 'create'){
                            var confirm_plan_url = $('#id-confirm-plan-url').val().replace('_plan_uuid_', plan_uuid);
                            window.location.href = confirm_plan_url;
                        } else if(plan_action_type == 'update'){
                            var update_plan_url = $('#id-update-plan-url').val().replace('_plan_uuid_', plan_uuid);
                            window.location.href = update_plan_url;
                        }
                    } else {
                        layer.msg('系统出错', {
                            icon: 2,
                            time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {

                }
            });
        }
    });

    //表格头部分类
    $('.table-title .title-select li').click(function () {
        $(this).addClass('table-active').siblings().removeClass('table-active');
        if($(this).text() == '主推账号'){
            $('.is-push').val(1);
        }else{
            $('.is-push').val(0);
        }
        doSearch();
    });

    // =====  添加到媒体库  =====
    $('#modal-select-media-lib .selected-option .option').on('click', function () {
        var _code = $(this).attr('data-code');
        var _element = $(this).parents('.first-dropdown').siblings('.media-tab');
        if (_code == 'select-one') {
            _element.find('.selected-media').css({display: 'block'});
            _element.find('.lib-name').css({display: 'none'});
            $("#modal-select-media-lib .in-selected-media span.fl").removeAttr("data-uuid");
            $('.in-selected-media span:eq(0)').text('请选择已有的媒体库');
        } else if (_code == 'new-one') {
            _element.find('.selected-media').css({display: 'none'});
            _element.find('.lib-name').css({display: 'block'});
        }

        // 选择媒体库 or 新建媒体库
        $('#modal-select-media-lib .selected-option').val(_code);
    });
    // 选择已经存在的某个媒体库
    $('#modal-select-media-lib .selected-media .media-name').on('click', 'li', function () {
        var _media_lib_name = $(this).text();
        var _media_lib_uuid = $(this).attr('data-uuid');
        $(this).parents('.media-tab').find('.in-selected-media span:eq(0)').text(_media_lib_name);
        $(this).parents('.media-tab').find('.in-selected-media span:eq(0)').attr('data-uuid', _media_lib_uuid);
    });

    // 搜索已经存在媒体库(输入媒体库名称,然后回车)
    $('#modal-select-media-lib .selected-media .input-name').on('click', function () {
        var _media_lib_name = $.trim($(this).val());
        var get_weixin_media_lib_url = $('#id-get-media-library-list-url').val();
        $.ajax({
            url: get_weixin_media_lib_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {media_lib_name: _media_lib_name},
            success: function (resp) {
                if (resp.err_code == 0) {
                    var weixin_media_lib_list = resp.weixin_media_lib_list;
                    // 选择媒体库
                    $("#modal-select-media-lib .selected-media .media-name ul li").remove(); // 清空
                    for (var i = 0; i < weixin_media_lib_list.length; i++) {
                        $("#modal-select-media-lib .media-name ul").append('<li data-uuid="' + weixin_media_lib_list[i].uuid + '">' + weixin_media_lib_list[i].group_name + '</li>')
                    }
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 2000
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统错误，请联系管理员!",
                    delay_time: 2000
                });
                return false;
            }
        });
    });

    //enter键搜索
    $('#modal-select-media-lib .selected-media .input-name').keydown(function(event){
        if(event.keyCode==13){
            $('#modal-select-media-lib .selected-media .input-name').click();
            return false;
        }
    });

    // 新建媒体库(点击"保存")
    $("#modal-new-media-lib .btn-save").click(function () {
        var new_media_lib_modal = $('#modal-new-media-lib');
        var selected_media_uuid_list = new_media_lib_modal.find('.selected-media-uuid-list').val();
        var media_lib_name = new_media_lib_modal.find('.lib-name').val();
        var media_lib_uuid = -1;
        if (media_lib_name == '') {
            wom_alert.msg({
                icon: "error",
                content: "媒体库名称不能为空!",
                delay_time: 2000
            });
            return false;
        }
        addMediaIntoLib(media_lib_uuid, media_lib_name, selected_media_uuid_list);
    });

    // 添加资源到已有媒体库(点击"保存")
    //TODO 验证media_lib_uuid，option是否为空或undefined
    $("#modal-select-media-lib .btn-save").click(function () {
        // 获取类型(新建或添加到已有)
        var option = $('#modal-select-media-lib .selected-option').val();
        if (option == "") { //默认选择已有媒体库
            option = 'select-one';
        }
        // 已经选择的媒体资源的uuid
        var selected_media_uuid_list = $('#modal-select-media-lib .selected-media-uuid-list').val();
        if (option == 'select-one') {
            var media_lib_uuid = $("#modal-select-media-lib .in-selected-media span.fl").attr("data-uuid"); // 媒体库uuid
            var media_lib_name = $("#modal-select-media-lib .in-selected-media span.fl").text();
            if (typeof(media_lib_uuid) == 'undefined') {
                wom_alert.msg({
                    icon: "error",
                    content: "请选择媒体库!",
                    delay_time: 2000
                });
                return false;
            }
        } else if (option == 'new-one') {
            var media_lib_name = $.trim($("#modal-select-media-lib .lib-name").val());
            var media_lib_uuid = -1;
            if (media_lib_name == '') {
                wom_alert.msg({
                    icon: "error",
                    content: "媒体库名称不能为空!",
                    delay_time: 2000
                });
                return false;
            }
        }
        addMediaIntoLib(media_lib_uuid, media_lib_name, selected_media_uuid_list);
    });

});

//条件选择开始
function selectCondition() {
    $(this).parent().prev().removeClass('filter-active');
    var _h3_text = $(this).parent().siblings('h3').text();
    var _this_i = $(this).children('i').text();
    var _data_value = $(this).parent().siblings('.dropdown').find('a').attr('data-value');

    if (_data_value == '零售价：') {
        selectedOne(this, '零售价', 'input-retail-price', _data_value, _this_i)
    }
    switch (_h3_text) {
        case '分类：':
            selectedMore(this, 'filter-ID-sign', 'input-media-cate', _h3_text, _this_i);
            break;
        case '地域：':
            selectedMore(this, 'filter-area', 'input-follower-area', _h3_text, _this_i);
            break;
        case '类型：':
            selectedMore(this, 'filter-ID-type', 'input-belong-tag', _h3_text, _this_i);
            break;
        case '头条阅读数：':
            selectedOne(this, '头条阅读数', 'input-read-num', _h3_text, _this_i)
            break;
        case '粉丝数：':
            selectedOne(this, '粉丝数', 'input-follower-num', _h3_text, _this_i)
            break;
    }
    selectedLiLen();
    doSearch();
}

//多选条件选择
function selectedMore(this_tag, filter_classify, input_type, _h3_text, _this_i) {
    if ($(this_tag).children('i').hasClass('filter-active')) {
        $('.condition-selected li').each(function () {
            var _this_text = $(this).children('em').text();
            if (_this_text == _this_i) {
                $(this).remove();
            }
        });
        $(this_tag).children('i').removeClass('filter-active');
        var _len = $('.' + filter_classify + ' ul .filter-active').length;
        if (_len == 0) {
            $(this_tag).parents('ul').siblings('.filter-unlimit').addClass('filter-active');
        }
        ;
    } else {
        var _len = $('.' + filter_classify + ' ul .filter-active').length;
        if (filter_classify == 'filter-ID-sign') {
            limitMaxLen(this_tag, _len, 5, _h3_text, _this_i)
        }
        ;
        if (filter_classify == 'filter-area') {
            limitMaxLen(this_tag, _len, 2, _h3_text, _this_i)
        }
        ;
        if (filter_classify == 'filter-ID-type') {
            limitMaxLen(this_tag, _len, _len, _h3_text, _this_i)
        }
        ;
    }
    //搜索传值
    var belong_tags = "";
    $('.' + filter_classify + ' ul .filter-active').each(function () {
        belong_tags += $(this).attr("data-code") + ',';
    });
    belong_tags = belong_tags.substr(0, belong_tags.length - 1);
    $("#form-weixin-search " + '.' + input_type).attr('value', belong_tags);
}

//多选限制选择3、6个
function limitMaxLen(tag, _len, num, _h3_text, _this_i) {
    if (_len > num) {
        return;
    } else {
        $(tag).children('i').addClass('filter-active');
        $('.condition-selected .selected-last-li').before('<li><span>' + _h3_text + '</span><em>' + _this_i + '</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
    }
}

//单选条件选择
function selectedOne(_this, condition, input_type, span_text, _this_i) {
    if ($(_this).children('i').hasClass('filter-active')) {
        $('.condition-selected li').each(function () {
            var _this_span = $(this).children('span').text();
            if (_this_span.indexOf(condition) != -1) {
                $(this).remove();
            }
        });
        $(_this).children('i').removeClass('filter-active');
        $(_this).parents('ul').siblings('.filter-unlimit').addClass('filter-active');
        $("#form-weixin-search " + '.' + input_type).attr('value', '');
    } else {
        $(_this).siblings('li').children('i').removeClass('filter-active');
        $(_this).children('i').addClass('filter-active');
        $('.condition-selected li').each(function () {
            var _this_span = $(this).children('span').text();
            if (_this_span.indexOf(condition) != -1) {
                $(this).remove();
            }
        });
        $('.condition-selected .selected-last-li').before('<li><span>' + span_text + '</span><em>' + _this_i + '</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
        //搜索传值
        var data_min = $(_this).parents('ul').find('.filter-active').attr('data-min');
        var data_max = $(_this).parents('ul').find('.filter-active').attr('data-max');
        $("#form-weixin-search " + '.' + input_type).attr('value', data_min + ',' + data_max);
    }

}

//已选条件长度判断
function selectedLiLen() {
    if ($('.condition-selected li').length > 2) {
        $('.condition-selected .selected-last-li').css({display: 'block'});
    } else {
        $('.condition-selected .selected-last-li').css({display: 'none'});
    }
}

// 条件自定义搜索
function customConditionSearch(_this_tag, _filter_class, _classify_text) {
    var _data_value_min = $(_this_tag).parents('.filter-price').find('input:nth-of-type(1)').val();
    var _data_value_max = $(_this_tag).parents('.filter-price').find('input:nth-of-type(2)').val();

    var _span_limit = $(_this_tag).parents('.filter-item').children('.filter-unlimit');

    $(_filter_class).find('ul li').each(function () {
        var _child_i = $(this).children('i');
        _child_i.removeClass('filter-active');
        var _data_min = _child_i.attr('data-min');
        var _data_max = _child_i.attr('data-max');
        if (_data_min == _data_value_min && _data_max == _data_value_max) {
            _child_i.addClass('filter-active');
        }
    })
    var _reg = /^[1-9][0-9]*$/;
    if (_data_value_min == '' || _data_value_max == '') {
        layer.msg('请输入完整的区间', {
            icon: 0,
            time: 1500
        });
        return false;
    } else if (!_reg.test(_data_value_min) || !_reg.test(_data_value_max)) {
        layer.msg('请输入正数', {
            icon: 0,
            time: 1500
        });
        return false;
    } else if (parseInt(_data_value_min) >= parseInt(_data_value_max)) {
        layer.msg('请输入正确的区间', {
            icon: 0,
            time: 1500
        });
        return false;
    } else {
        _span_limit.removeClass('filter-active');
    }
    $('.condition-selected ul li').each(function () {
        eachUlLi(this, _classify_text, _data_value_min, _data_value_max);
    })
    if (_filter_class == '.filter-fans') {
        // 粉丝
        $('input.input-follower-num').val(_data_value_min + ',' + _data_value_max);
        $('.condition-selected .selected-last-li').before('<li><span>' + _classify_text + '</span><em>' + _data_value_min + '-' + _data_value_max + '</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
    } else {
        // 零售价
        $('input.input-retail-price').val(_data_value_min + ',' + _data_value_max);
        $('.condition-selected .selected-last-li').before('<li><span>' + _classify_text + '</span><em>' + _data_value_min + '-' + _data_value_max + '元' + '</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
    }
    selectedLiLen();
    doSearch();
}

//单个删除条件
function delCondition(tag) {
    var _em_text = $(tag).prev().text();
    var _min_value = _em_text.split('-')[0];
    var _max_value = _em_text.split('-')[1];
    var _this_span_text = $(tag).siblings('span').text();

    switch (_this_span_text) {
        case '分类：':
            eachDelMore('filter-ID-sign', 'input-media-cate', _em_text);
            break;
        case '地域：':
            eachDelMore('filter-area', 'input-follower-area', _em_text);
            break;
        case '类型：':
            eachDelMore('filter-ID-type', 'input-belong-tag', _em_text);
            break;
        case '头条阅读数：':
            eachDelSingle('filter-headline-read', 'input-read-num', _em_text, _min_value, _max_value);
            break;
        case '零售价：':
            eachDelSingle('filter-value', 'input-retail-price', _em_text, _min_value, _max_value);
            break;
        case '粉丝数：':
            eachDelSingle('filter-fans', 'input-follower-num', _em_text, _min_value, _max_value);
            break;
    }
    $(tag).parent('li').remove();
    if ($('.condition-selected li').length < 3) {
        $('.condition-selected .selected-last-li').css({display: 'none'});
    }
}

//遍历删除
//单个选择条件的删除
function eachDelSingle(filter_classify, input_type, _em_text, value1, value2) {
    $('.' + filter_classify + ' ul li').each(function () {
        var _active_i = $(this).parents('ul').find('li .filter-active');
        var _this_i_text = $(this).children('i').text();
        var _active_i = $(this).parents('ul').find('li .filter-active');
        var _data_min = $(this).children('i').attr('data-min');
        var _data_max = $(this).children('i').attr('data-max');

        if (_data_min == value1 && _data_max == value2) {
            $(this).children('i').removeClass('filter-active');
        }
        ;
        if (_this_i_text == _em_text) {
            $(this).children('i').removeClass('filter-active');
        }
        if (_active_i.length == 0) {
            $(this).parents('ul').siblings('.filter-unlimit').addClass('filter-active');
        }
    })
    $('#form-weixin-search ' + '.' + input_type).attr('value', '');
    doSearch();
}

//多个选择条件的删除
function eachDelMore(filter_classify, input_type, _em_text) {
    $('.' + filter_classify + ' ul li').each(function () {
        var _this_i_text = $(this).children('i').text();
        var _this_i_code = $(this).children('i').attr('data-code');
        var _active_i = $(this).parents('ul').find('li .filter-active');

        if (_this_i_text == _em_text) {
            $(this).children('i').removeClass('filter-active');

            var _str_value = $('.' + input_type).val();
            var newStr = getResultByDelete(_str_value, _this_i_code);

            $('#form-weixin-search ' + '.' + input_type).attr('value', newStr);
        }
        if (_active_i.length == 0) {
            $(this).parents('ul').siblings('.filter-unlimit').addClass('filter-active');
        }
    });
    doSearch();
}

//多选条件（数组字符串转换，删除数组中一个元素）
function getResultByDelete(fullStr, toDelStr) {
    var arr = fullStr.split(',');
    var pos = $.inArray(toDelStr, arr);
    arr.splice(pos, 1);
    var newStr = arr.toString();
    return newStr;
}

//条件范围控制开始
function eachUlLi(_this_li, classify, value1, value2) {
    var _this_child_span = $(_this_li).children('span').text();
    if (_this_child_span == classify) {
        $(_this_li).remove();
        return false;
    }
}

// 不限清空样式
function noLimitChangeCss(_classify_text) {
    $('.condition-selected ul li').each(function () {
        var _text_child_text = $(this).children('span').text();
        if (_text_child_text == _classify_text) {
            $(this).remove();
        }
    });
}

//购物车效果开始
function selectedResource(event) {
    var _this_checked = $(this).parents('tr').find('.select-account input').is(':checked');
    var media_info_json = $(this).parents('tr').find('.media-info-json').text();
    var price_obj_new = JSON.parse(media_info_json);
    var media_uuid = price_obj_new.media_uuid;
    if (_this_checked) {
        // 选中
        var _img = $(this).parents('tr').find('dl dt img').attr('src');
        var flyer = $('<a style="display:inline-block;width: 60px;height: 60px;border-radius: 50%;overflow: hidden;">' +
            '<img class="u-flyer" src="' + _img + '" style="position: relative;top: -142px;left: -142px;width: 350px;">' + '</a>');
        flyer.fly({
            start: {
                left: event.clientX + 80,
                top: event.clientY
            },
            end: {
                left: document.body.clientWidth - 240,
                top: 500,
                width: 0,
                height: 0
            }
        });
        var _account_html = $(this).parents('tr').find('.account').html();
        var _fans_num = price_obj_new.follower_num;
        var _retail_price = price_obj_new.pos_1_retail_price;
        var _shopping_car = $('.contact').siblings('.right-box');


        if (_shopping_car.css('right') == '-420px') {
            _shopping_car.stop().animate({right: '0'}, 500);
            $('.contact').stop().animate({right: '360px'}, 500);
        }
        $('.shopping-car tbody').prepend('<tr class="one-media" data-media-uuid = "' + media_uuid + '">' +
            '<td class="account">' + _account_html + '</td>' +
            '<td  class="ad-type">' +
            '<span>' + '多图文头条' + '</span>' + '￥' +
            '<i class="single-count">' + _retail_price + '</i>' +
            '<em class="fans-num" style="display: none">' + _fans_num + '</em>' +
            '</td>' +
            '<td>' +
            '<span class="shopping-resource-del"></span>' +
            '</td>' +
            '</tr>');
        var _shopping_tr_length = $('.card-body tbody tr').length;
        if (_shopping_tr_length >= 2) {
            $('.card-head .btn-danger').css('display', 'inline-block');
        }
        $('.card-head span:eq(0) i,.contact-shopping-cart em').text(_shopping_tr_length);

        addMediaIntoCookie(media_uuid);
    } else {
        // 去除"选中"

        var _this_data_value = $(this).parents('tr').attr('data-media-uuid');
        $('.card-body .table tbody tr').each(function () {
            if ($(this).attr('data-media-uuid') == _this_data_value) {
                $(this).remove();
            }
        });
        var _shopping_tr_length = $('.card-body tbody tr').length;
        var _shopping_car = $('.contact').siblings('.right-box');
        if (_shopping_car.css('right') == '-420px') {
            _shopping_car.stop().animate({right: '-420px'});
            $('.contact').stop().animate({right: '0px'});
        }
        if (_shopping_tr_length <= 1) {
            $('.card-head .btn-danger').css('display', 'none');
        }
        $('.card-head span:eq(0) i,.contact-shopping-cart em').text(_shopping_tr_length);
        // 更新cookie,从cookie中移除一个资源
        deleteMediaInCookie(media_uuid);
    }
    changeShoppingPrice();
    changeShoppingFans();

    // console.log(Cookies.get('weixin-media-selected-to-put-in'));
}


// 根据weixin media uuid从cookie里移除给定的资源
function deleteMediaInCookie(media_uuid) {
    var weixin_media_selected_to_put_in = Cookies.get('weixin-media-selected-to-put-in');
    if (weixin_media_selected_to_put_in != '') {
        weixin_media_selected_to_put_in = weixin_media_selected_to_put_in.replace(media_uuid + ',', '');
        Cookies.set('weixin-media-selected-to-put-in', weixin_media_selected_to_put_in, {expires: 7});
    }
}

// 将选中资源的media uuid加入cookie
function addMediaIntoCookie(media_uuid) {
    var weixin_media_selected_to_put_in = Cookies.get('weixin-media-selected-to-put-in');
    if (weixin_media_selected_to_put_in == undefined || weixin_media_selected_to_put_in == '') {
        Cookies.set('weixin-media-selected-to-put-in', media_uuid + ',');
    } else if (weixin_media_selected_to_put_in.indexOf(media_uuid) < 0) {
        Cookies.set('weixin-media-selected-to-put-in', weixin_media_selected_to_put_in + media_uuid + ',');
    }
}

// 将媒体添加到媒体库
function addMediaIntoLib(media_lib_uuid, media_lib_name, selected_media_uuid_list) {
    var add_media_url = $('#id-add-media-into-lib-url').val();
    wom_alert.confirm({
        content: '确认保存吗?'
    }, function () {
        $.ajax({
            url: add_media_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                media_lib_uuid: media_lib_uuid,
                media_lib_name: media_lib_name,
                selected_media_uuid_list: selected_media_uuid_list
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    if($('.btn.btn-danger.btn-save').attr('open-by-shop-car') == true){
                        Cookies.remove('weixin-media-selected-to-put-in');
                    }
                    //Cookies.remove('weixin-media-selected-to-put-in');
                    // 跳转到个人中心 > 微信媒体库
                    window.location.href = resp.redirect_url;
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 2000
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统错误，请联系管理员!",
                    delay_time: 2000
                });
                return false;
            }
        });
    });
}

//遍历购物车统计价格
function changeShoppingPrice() {
    var price_count = 0;
    $(".card-body .single-count").each(function () {
        price_count += parseInt($(this).text());
    });
    $('.count-sum').text(price_count);
}

//遍历购物车统计价格
function changeShoppingFans() {
    var price_count = 0;
    $(".card-body .fans-num").each(function () {
        price_count += parseInt($(this).text());
    });
    $('.fans-sum i').text(price_count);
}

//一键清空购物车
function emptyShoppingCar() {
    $(this).css({display: 'none'});
    $(this).parents('.card-head').siblings('.card-body').find('table tbody').children('tr').remove();
    $('.table-item .table tbody .select-account input').prop({checked: false});
    $('.card-head span:eq(0) i,.contact-shopping-cart em').text(0);
    $('.checked-all-resource-input').prop({checked: false});
    changeShoppingPrice();
    changeShoppingFans();
    Cookies.set('weixin-media-selected-to-put-in', '');
}

// 下拉单选择某一个
function selectedOption() {
    var _txet = $(this).text();
    var input_value = "";
    var is_order = false;
    var is_pub_type = false;
    switch (_txet) {
        case '默认排序':
            input_value = null;
            is_order = true;
            break;
        case '单图文价格从高到低':
            input_value = "s-desc";
            is_order = true;
            break;
        case '单图文价格从低到高':
            input_value = "s-asc";
            is_order = true;
            break;
        case '多图文头条价格从高到低':
            input_value = "m-1-desc";
            is_order = true;
            break;
        case '多图文头条价格从低到高':
            input_value = "m-1-asc";
            is_order = true;
            break;
        case '多图文2条价格从高到低':
            input_value = "m-2-desc";
            is_order = true;
            break;
        case '多图文2条价格从低到高':
            input_value = "m-2-asc";
            is_order = true;
            break;
        case '多图文3~N条价格从高到低':
            input_value = "m-3-desc";
            is_order = true;
            break;
        case '多图文3~N条价格从低到高':
            input_value = "m-3-asc";
            is_order = true;
            break;
        case '多图文头条价格':
            input_value = "m-1";
            break;
        case '多图文2条价格':
            input_value = "m-2";
            break;
        case '多图文3~N条价格':
            input_value = "m-3";
            break;
        case '单图文价格':
            input_value = "s";
            break;
        case '不限':
            input_value = "-1";
            is_pub_type = true;
            break;
        case '直接发布':
            input_value = "1";
            is_pub_type = true;
            break;
        case '原创':
            input_value = "2";
            is_pub_type = true;
            break;
        default:
            input_value = "-1";
            break;
    }
    if (is_order) {
        clearSort();
        $('th').siblings('th').find('.up-red').removeClass('up-red');
        $('th').siblings('th').find('.down-red').removeClass('down-red');
        $(".input-sort-by-retail-price").val(input_value);
        doSearch();
    }else if(is_pub_type){
        $(".input-pub-type").val(input_value);
        doSearch();
    }else{
        $(".input-retail-price-type").val(input_value);
    }
    // doSearch();
    $(this).parent().prev().find('span:eq(0)').text(_txet);
}

//排序
function AccountSort() {
    var type = $(this).parent().parent().find('span').text();
    var input_class = '';
    switch (type) {
        case '粉丝数':
            input_class = 'input-sort-by-follower-num';
            break;
        case '头条平均阅读数':
            input_class = 'input-sort-by-m-1-avg-read-num';
            break;
        case '更新时间':
            input_class = 'input-sort-by-update-time';
            break;
        case '沃米指数':
            input_class = 'input-sort-by-wom-num';
            break;
        case '价格有效期':
            input_class = 'input-sort-by-active-end-time';
            break;
    }
    clearSort();
    if ($(this).hasClass('up')) {
        if ($(this).hasClass('up-red')) {
            $(this).removeClass('up-red');
            $('.' + input_class).val(null);// 取消排序
        } else {
            $(this).addClass('up-red');
            $(this).parents('th').siblings('th').find('.up-red').removeClass('up-red');
            $(this).parents('th').siblings('th').find('.down-red').removeClass('down-red');
            $(this).siblings('i').removeClass('down-red');
            $('.' + input_class).val('s-asc');// 升序
        }
    } else {
        if ($(this).hasClass('down-red')) {
            $(this).removeClass('down-red');
            $('.' + input_class).val(null);// 取消排序
        } else {
            $(this).addClass('down-red');
            $(this).parents('th').siblings('th').find('.down-red').removeClass('down-red');
            $(this).parents('th').siblings('th').find('.up-red').removeClass('up-red');
            $(this).siblings('i').removeClass('up-red');
            $('.' + input_class).val('s-desc');// 倒序
        }
    }
    $('.price-sort .dropdown.fl span.fl').text('默认排序');
    doSearch();
}
// 排序重置清空
function clearSort(){
    $('.input-sort-by-m-1-avg-read-num').val(null);// 取消多图文头条平均阅读数排序
    $('.input-sort-by-follower-num').val(null);// 取消粉丝数排序
    $('.input-sort-by-retail-price').val(null);// 取消零售价排序
    $('.input-sort-by-wom-num').val(null);// 取消沃米指数排序
    $('.input-sort-by-active-end-time').val(null);// 取消价格有效期排序
}

// 搜索
function doSearch() {
    searchLoading();
    $(".form-weixin-search").submit();
}

function searchLoading(){
    //loading层
    var index = layer.load(1, {
        shade: [0.1,"rgba(0,0,0,.5)"],//0.1透明度的白色背景
    });
    return index;
}
/**
 * 打开媒体库的弹框
 * @param selected_media_uuid_list 选择的微信资源的uuid拼接成的字符串,以逗号隔开
 */
function openMediaLibraryModal(selected_media_uuid_list) {
    var get_weixin_media_lib_url = $('#id-get-media-library-list-url').val();
    $.ajax({
        url: get_weixin_media_lib_url,
        type: 'GET',
        cache: false,
        dataType: 'json',
        data: {},
        success: function (resp) {
            if (resp.err_code == 0) {
                var weixin_media_lib_list = resp.weixin_media_lib_list;
                if (weixin_media_lib_list.length == 0) {
                    // 新增媒体库
                    $("#modal-new-media-lib .selected-media-uuid-list").val(selected_media_uuid_list);
                    $('#modal-new-media-lib').modal('show');
                } else {
                    // 选择媒体库
                    $("#modal-select-media-lib .selected-media-uuid-list").val(selected_media_uuid_list);
                    $("#modal-select-media-lib .selected-media .media-name ul li").remove(); // 清空
                    for (var i = 0; i < weixin_media_lib_list.length; i++) {
                        $("#modal-select-media-lib .media-name ul").append('<li data-uuid="' + weixin_media_lib_list[i].uuid + '">' + weixin_media_lib_list[i].group_name + '</li>')
                    }
                    $('#modal-select-media-lib').modal('show');
                }
            } else if (resp.err_code == 2) {
                // 非广告主账号登录
                wom_alert.msg({
                    icon: "error",
                    content: "请使用广告主账号登录!",
                    delay_time: 1500
                });
                return false;
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
            if (XMLHttpRequest.status == 200) {
                layer.msg(XMLHttpRequest.responseText, {
                    icon: 0,
                    time: 2000
                });
                return false;
            }
            if (XMLHttpRequest.status != 302) {
                layer.msg('系统异常', {
                    icon: 0,
                    time: 1500
                });
                return false;
            }
        }
    });
}

function plainContentLengthLimit(){
    $('.plain-text-length-limit').each(function(){
        var content = $(this).text().trim();
        var length_limit = $(this).attr('data-limit');
        var content_length = content.length;

        if(length_limit == undefined){
            length_limit = 5;
        }

        if(content_length > length_limit){
            $(this).text(content.substr(0, length_limit) + '...');
        }
        $(this).attr('data-value', content);
    })

};

// pjax 刷新事件
function pjaxRef() {
    // 资源列表页里的"加入媒体库"
    $(".btn-add-media-lib-in-media-list span").click(function () {
        var selected_media_uuid_list = [];
        var media_uuid = $(this).attr('data-uuid');
        selected_media_uuid_list.push(media_uuid);

        var weixin_media_lib_uuid = $('#id-weixin-media-lib-uuid').val();
        if (weixin_media_lib_uuid == '') {
            // 区分是否从购物车打开弹框
            $('.btn.btn-danger.btn-save').attr('open-by-shop-car',false);
            openMediaLibraryModal(selected_media_uuid_list);
        } else {
            var add_media_into_lib_url = $('#id-add-media-into-lib-url').val();
            $.ajax({
                url: add_media_into_lib_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    media_lib_uuid: weixin_media_lib_uuid,
                    media_lib_name: '',
                    selected_media_uuid_list: media_uuid
                },
                beforeSend: function () {
                    //让提交按钮失效，以实现防止按钮重复点击
                },
                complete: function () {
                    //按钮重新有效
                    //$('xxx').removeAttr('disabled');
                },
                success: function (resp) {
                    if (resp.err_code == 0) {
                        window.location.href = resp.redirect_url;
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

                }
            });
        }
    });

    //购物车事件绑定
    $('.table-item tbody .select-account').on('click', 'input', selectedResource);

    //通过cookies选中已选资源
    var get_shopping_car_cookie_json_url = $("#id-get-list-cookie-json-url").val();
    //console.log(get_shopping_car_cookie_json_url);
    var weixin_shopping_car = Cookies.get('weixin-media-selected-to-put-in');
    if(typeof(weixin_shopping_car) != "undefined"){
        var media_cookies = weixin_shopping_car;
        $.ajax({
            url: get_shopping_car_cookie_json_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {
                media_cookies: media_cookies
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    //清空购物车
                    $('.card-body tbody').html("");
                     for(var i=0 ; i< resp.json_array.length; i++){
                         var media_uuid = resp.json_array[i].media_uuid;
                         var _fans_num = resp.json_array[i].follower_num;
                         var _retail_price = resp.json_array[i].pos_1_retail_price;
                         var _pub_id = resp.json_array[i].public_id;
                         var _pub_name = resp.json_array[i].public_name;
                         var _account_html = '<dl class="clearfix">'+
                             '<dt class="fl">'+
                                 '<a href="#">'+
                                    '<img src="http://open.weixin.qq.com/qr/code/?username='+_pub_id+'" alt="">'+
                                 '</a>'+
                                 '<i></i>'+
                             '</dt>'+
                             '<dd class="fl">'+
                                 '<a class="ID-name synopsis" href="#" data-str="6" data-title="'+_pub_name+'" data-value = "'+media_uuid+'">'+_pub_name+'</a>'+
                                 '<div class="ewm-ID">'+
                                     '<i></i>'+
                                     '<span>'+_pub_id+'</span>'+
                                 '</div>'+
                             '</dd>'+
                             '</dl>';
                         //遍历表格，根据购物车的数据给相应的复选框选中效果
                         $(".media-table").find("tr").each(function(){
                             var table_media_uuid = $(this).data("media-uuid");
                             if(table_media_uuid == media_uuid){
                                 $(this).find(".select-account input").prop("checked",true);
                             }
                         });
                         $('.card-body tbody').prepend('<tr class="one-media" data-media-uuid = "' + media_uuid + '">' +
                             '<td class="account">' + _account_html + '</td>' +
                             '<td  class="ad-type">' +
                             '<span>' + '多图文头条' + '</span>' + '￥' +
                             '<i class="single-count">' + _retail_price + '</i>' +
                             '<em class="fans-num" style="display: none">' + _fans_num + '</em>' +
                             '</td>' +
                             '<td>' +
                             '<span class="shopping-resource-del"></span>' +
                             '</td>' +
                             '</tr>');
                         var tr_len = $('.card-body tbody tr').length;
                         if (tr_len >= 2) {
                             $('.card-head .btn-danger').css('display', 'inline-block');
                         }
                         $('.card-head span:eq(0) i,.contact-shopping-cart em').text(tr_len);
                         addMediaIntoCookie(media_uuid);
                         changeShoppingPrice();
                         changeShoppingFans();
                     }
                    //window.location.href = resp.redirect_url;
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

            }
        });
    }
    plainContentLengthLimit();
    // 分页样式
    $(".pagination li a").each(function () {
        $(this).removeAttr("href");
        $(this).attr("style", "cursor: pointer;");
    });

    $(".pagination li.disabled").each(function () {
        var label_text = $(this).text();
        $(this).find('span').after('<a>' + label_text + '</a>');
        $(this).find('span').remove();
    });

    //分页处理
    $(".pagination li a").click(function () {
        $("input.page").attr("value", $(this).attr("data-page"));
        doSearch();
    });
    // 分页跳转
    $(".custom-page").click(function(){
        var custom_page = $('#id-custom-page').val();
        if(custom_page !=""){
            $("input.page").attr("value", $('#id-custom-page').val() -1);
            doSearch();
        }
    });
    //更新总记录数
    var totalCount = $(".system_page").attr("data-value");
    $(".table-title .fl i").text(totalCount);

    //判断已选中媒体库资源
    var group_uuid = $("#id-group-uuid").val();
    var select_item_url = $("#id-select-item-url").val();
    if(group_uuid !=""){
        $.ajax({
            url: select_item_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {group_uuid:group_uuid},
            success: function (resp) {
                if (resp.err_code == 0) {
                    var weixin_media_list_arr = resp.weixin_media_list;
                    $('.media-table tbody tr').each(function(){
                        var _data_uuid = $(this).attr('data-media-uuid');
                        for(var i = 0; i < weixin_media_list_arr.length; i++){
                            if(_data_uuid == weixin_media_list_arr[i]['weixin_media_uuid']){
                                $(this).css({color:'#ACACA4',background:'#FFFFF7'});
                                $(this).find('span').css({color:'#ACACA4'});
                                $(this).find('.select-account input').prop({disabled:true});
                                $(this).find('.collect a').css({display:'none'});
                                $(this).find('.collect').append("<img src='/src/images/media-selected.png'/>");
                                $(this).find('.account li').css({border:"1px solid #FEC0C1"})
                            }
                        }
                    })

                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统错误，请联系管理员!",
                        delay_time: 2000
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统错误，请联系管理员!",
                    delay_time: 2000
                });
                return false;
            }
        });
    };


    function searchLoading(){
        //loading层
        var index = layer.load(1, {
            shade: [0.1,"rgba(0,0,0,.5)"],//0.1透明度的白色背景
        });
        return index;
    }
    //关闭loading
    layer.close(searchLoading());


    //鼠标放上去显示完整信息
    $("a[data-title]").each(function () {
        var a = $(this);
        var title = a.attr('data-title');
        //console.log(title);
        if (title == undefined || title == '') return;
        a.data('data-title', title).hover(
            function () {
                var offset = a.offset();
                $("<div class='show-all-info'>" + title + "</div>").appendTo($(".table-item")).css({
                    top: offset.top + a.outerHeight() + 6,
                    left: offset.left + a.outerWidth() - 10
                }).fadeIn(function () {
                });
                //console.log($(".show-all-info").text());
            },
            function () {
                $(".show-all-info").remove();
            }
        );
    });

    //购物车全选开始
    $('.checked-all-resource-input').click(function () {
        var _select_option = $(this).parents('.table-item').find('.table tbody .select-account input');
        if ($(this).is(':checked')) {
            _select_option.each(function () {
                if (!$(this).is(':checked')) {
                    $(this).click();
                }
                ;
            });
        } else {
            _select_option.each(function () {
                if ($(this).is(':checked')) {
                    $(this).click();
                }
                ;
            });
        }
        changeShoppingPrice();
        changeShoppingFans();
    });

    //是否存在资源
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 1){
            $(".no-resource").css("display","block");
            $(".table").css("margin-bottom","0");
        }else{
            $(".no-resource").css("display","none");
            $(".table").css("margin-bottom","20px");
        }
    }
    isResource();


}






