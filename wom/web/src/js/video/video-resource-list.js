$(function(){

    //搜索名称
    $(".input-search-name").attr('value',$('#id-search-name').val());

    //控制导航栏选中
    $(".video-list").addClass('active-nav');
    $(".video-search").click();

    //选择下拉单
    $('.dropdown .dropdown-menu').on('click','li',selectedOption);
    //条件选择开始
    $('.filter-item-li ul.clearfix').on('click','li',selectCondition);
    //选择不限
    $('.filter-unlimit').click(function(){
        if($(this).hasClass('filter-active')){
            return false;
        } else {
            $(this).addClass('filter-active');
            $(this).siblings('ul').find('li i').removeClass('filter-active');
            var _classify_text = $(this).prev('h3').text();
            switch (_classify_text){
                case '分类：':
                    noLimitChangeCss(_classify_text);
                    $("#form-video-search .input-media-cate").attr('value','');
                    break;
                case '地域：':
                    noLimitChangeCss(_classify_text);
                    $("#form-video-search .input-follower-area").attr('value','');
                    break;
                case '平台：':
                    noLimitChangeCss(_classify_text);
                    $("#form-video-search .input-platform-type").attr('value','');
                    break;
                case '性别：':
                    noLimitChangeCss(_classify_text);
                    $("#form-video-search .input-sex").attr('value','');
                    break;
                case '参考报价：':
                    noLimitChangeCss(_classify_text);
                    $("#form-video-search .input-price").attr('value','');
                    break;
                case '粉丝数：':
                    noLimitChangeCss(_classify_text);
                    $("#form-video-search .input-follower-num").attr('value','');
                    break;
                break;
            }
        }
        selectedLiLen();
        doSearch();
    })
    //已选条件全部删除
    $('.selected-last-li').click(function(){
        $(this).css('display','none');
        $(this).siblings('li').remove();
        $('.filter-item li i').removeClass('filter-active');
        $('.filter-unlimit').addClass('filter-active');
        //表单重置为空
        for (var i = 0; i < $('#form-video-search .form-control').length; i++) {
            $('#form-video-search .form-control').attr('value', '');
        }
        doSearch();
    })
    ////购物车事件绑定
    //$('.media-stage tbody .select-account').on('click','input', selectedResource);
    //$('.card-head').on('click','.delete-all',emptyShoppingCar);
    //单个删除购物车内的资源
    $(document).on('click','.shopping-resource-del',function(){
        var _data = $(this).parent().siblings('.account').find(' a').attr('data-value');
        $('.media-stage tbody .account').each(function(){
            var _this_data = $(this).children('a').attr('data-value');
            if(_this_data == _data){
                $(this).siblings('.select-account').find('input').prop({checked:false});
            }
        })
        $(this).parents('tr').remove();
        var _len = $('.card-body tbody tr').length;
        if(_len == 0){
            $('#check-all').prop({checked:false});
        }
        if(_len <= 1){
            $('.card-head .btn-danger').css('display','none');
        };
        $('.card-head span:eq(0) i,.contact-shopping-cart em').text(_len);
        changeShoppingPrice();
        changeShoppingFans();
        //删除cookie的视频资源信息
        var _media_uuid = $(this).parents('tr').data('uuid');
        deleteMediaInCookie(_media_uuid);

    });
    // //购物车全选开始
    // $('.checked-all-resource-input').click(function(){
    //     var _select_option = $(this).parents('.media-stage').find('.table tbody .select-account input');
    //     if($(this).is(':checked')){
    //         _select_option.each(function(){
    //             if(!$(this).is(':checked')){
    //                 $(this).click();
    //             };
    //         });
    //     }else{
    //         _select_option.each(function(){
    //             if($(this).is(':checked')){
    //                 $(this).click();
    //             };
    //         });
    //     }
    //     changeShoppingPrice();
    //     changeShoppingFans();
    // })
    //价格及粉丝区域的验证
    $('.filter-price-button span').click(function(){
        var _classify_text = $(this).parents('.filter-item').children('h3').text();
        customConditionSearch(this,'.filter-value',_classify_text);
    })
    $('.filter-fans-btn span').click(function(){
        var _classify_text = $(this).parents('.filter-item').children('h3').text();
        customConditionSearch(this,'.filter-fans',_classify_text);
    })
    //排序
    $('.sort-icon').on('click','i',AccountSort);
    //购物车加入媒体库
    $('.add-shopping-selected').click(function(){
        judgeTrLen(this);
    });
    $('.put-in-resource').click(function(){
        judgeTrLen(this);
    })
    //表格头部导航
    $('.table-title .title-select li').click(function(){
        $(this).addClass('table-active').siblings().removeClass('table-active');
    })
    //modal层的切换
    $('#modal-select-media-lib .selected-option li').click(function(){
        var _index = $(this).index();
        var _element = $(this).parents('.first-dropdown').siblings('.media-tab');
        if(_index == 0){
            _element.find('.selected-media').css({display:'block'});
            _element.find('.add-new-media-name').css({display:'none'});
            $('.in-selected-media span:eq(0)').text('请选择已有的媒体库');
        } else {
            _element.find('.selected-media').css({display:'none'});
            _element.find('.add-new-media-name').css({display:'block'});
        }
    });
    //模糊查询的选择事件
    $('.media-name li').click(function(){
        var _txt = $(this).text();
        $(this).parents('.media-tab').find('.in-selected-media span:eq(0)').text(_txt);
    })

})
    //条件选择开始
    function selectCondition(){
        $(this).parent().prev().removeClass('filter-active');
        var _h3_text = $(this).parent().siblings('h3').text();
        var _this_i = $(this).children('i').text();
        var _data_value = $(this).parent().siblings('.dropdown').find('a').attr('data-value');

        switch (_h3_text){
            case '分类：':
                selectedMore(this,'filter-ID-sign','input-media-cate',_h3_text,_this_i);
            break;
            case '地域：':
                selectedMore(this,'filter-area','input-follower-area',_h3_text,_this_i);
                break;
            case '平台：':
                selectedMore(this,'filter-platform','input-platform-type',_h3_text,_this_i);
                break;
            case '性别：':
                selectedMore(this,'filter-sex','input-sex',_h3_text,_this_i);
                break;
            case '参考报价：':
                selectedOne(this,'参考报价','input-price',_h3_text,_this_i);
                break;
            case '粉丝数：':
                selectedOne(this,'粉丝数','input-follower-num',_h3_text,_this_i);
                break;
        }
        selectedLiLen();
        doSearch();
    }
    //多选条件选择
    function selectedMore(this_tag,filter_classify,input_type,_h3_text,_this_i){
        if($(this_tag).children('i').hasClass('filter-active')){
            $('.condition-selected li').each(function(){
                var _this_text = $(this).children('em').text();
                if(_this_text == _this_i){
                    $(this).remove();
                }
            });
            $(this_tag).children('i').removeClass('filter-active');
            var _len = $('.'+filter_classify+' ul .filter-active').length;
            if(_len == 0){
                $(this_tag).parents('ul').siblings('.filter-unlimit').addClass('filter-active');
            };
        }else{
            var _len = $('.'+filter_classify+' ul .filter-active').length;
            if(filter_classify == 'filter-ID-sign'){
                limitMaxLen(this_tag,_len,5,_h3_text,_this_i)
            };
            if(filter_classify == 'filter-area'){
                limitMaxLen(this_tag,_len,2,_h3_text,_this_i)
            };
            if(filter_classify == 'filter-platform'){
                limitMaxLen(this_tag,_len,_len,_h3_text,_this_i)
            };
            if(filter_classify == 'filter-sex'){
                limitMaxLen(this_tag,_len,_len,_h3_text,_this_i)
            };
        }
        //搜索传值
        var belong_tags = "";
        $('.' + filter_classify + ' ul .filter-active').each(function () {
            belong_tags += $(this).attr("data-code") + ',';
        });
        belong_tags = belong_tags.substr(0, belong_tags.length - 1);
        $("#form-video-search " + '.' + input_type).attr('value', belong_tags);
    }
    //多选限制选择3、6个
    function limitMaxLen(tag,_len,num,_h3_text,_this_i){
        if(_len > num){
            return;
        } else {
            $(tag).children('i').addClass('filter-active');
            $('.condition-selected .selected-last-li').before('<li><span>'+_h3_text+'</span><em>'+_this_i+'</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
        }
    }
    //单选条件选择
    function selectedOne(_this,condition,input_type,span_text,_this_i){
        if($(_this).children('i').hasClass('filter-active')){
            $('.condition-selected li').each(function(){
                var _this_span = $(this).children('span').text();
                if(_this_span.indexOf(condition) != -1){
                    $(this).remove();
                }
            });
            $(_this).children('i').removeClass('filter-active');
            $(_this).parents('ul').siblings('.filter-unlimit').addClass('filter-active');
            $("#form-video-search " + '.' + input_type).attr('value', '');
        }else{
            $(_this).siblings('li').children('i').removeClass('filter-active');
            $(_this).children('i').addClass('filter-active');
            $('.condition-selected li').each(function(){
                var _this_span = $(this).children('span').text();
                if(_this_span.indexOf(condition) != -1){
                    $(this).remove();
                }
            });
            $('.condition-selected .selected-last-li').before('<li><span>'+span_text+'</span><em>'+_this_i+'</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
            //搜索传值
            var data_min = $(_this).parents('ul').find('.filter-active').attr('data-min');
            var data_max = $(_this).parents('ul').find('.filter-active').attr('data-max');
            $("#form-video-search " + '.' + input_type).attr('value', data_min + ',' + data_max);
        }

    }
    //已选条件长度判断
    function selectedLiLen(){
        if($('.condition-selected li').length>2){
            $('.condition-selected .selected-last-li').css({display:'block'});
        }else{
            $('.condition-selected .selected-last-li').css({display:'none'});
        }
    }
    // 条件自定义搜索
    function customConditionSearch(_this_tag,_filter_class,_classify_text){
        var _data_value_min = $(_this_tag).parents('.filter-price').find('input:nth-of-type(1)').val();
        var _data_value_max = $(_this_tag).parents('.filter-price').find('input:nth-of-type(2)').val();
        var _span_limit = $(_this_tag).parents('.filter-item').children('.filter-unlimit');

        $(_filter_class).find('ul li').each(function(){
            var _child_i = $(this).children('i');
            _child_i.removeClass('filter-active');
            var _data_min = _child_i.attr('data-min');
            var _data_max = _child_i.attr('data-max');
            if(_data_min == _data_value_min&&_data_max == _data_value_max){
                _child_i.addClass('filter-active');
            }
        })
        var _reg = /^[1-9][0-9]*$/;
        if(_data_value_min == ''||_data_value_max == ''){
            layer.msg('请输入完整的区间',{
                icon:0,
                time: 1000
            });
            return false;
        } else if(!_reg.test(_data_value_min) || ! _reg.test(_data_value_max)){
            layer.msg('请输入正数',{
                icon:0,
                time: 1000
            });
            return false;
        }else if(parseInt(_data_value_min) >= parseInt(_data_value_max)){
            layer.msg('请输入正确的区间',{
                icon:0,
                time: 1000
            });
            return false;
        } else{
            _span_limit.removeClass('filter-active');
        }
        $('.condition-selected ul li').each(function(){
            eachUlLi(this,_classify_text,_data_value_min,_data_value_max);
        })
        if(_filter_class == '.filter-fans'){
            // 粉丝
            $('input.input-follower-num').val(_data_value_min+ ','+ _data_value_max);
            $('.condition-selected .selected-last-li').before('<li><span>'+_classify_text+'</span><em>'+_data_value_min+'-'+_data_value_max+'</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
        }else{
            // 零售价
            $('input.input-price').val(_data_value_min+ ','+ _data_value_max);
            $('.condition-selected .selected-last-li').before('<li><span>'+_classify_text+'</span><em>'+_data_value_min+'-'+_data_value_max+'元'+'</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
        }
        selectedLiLen();
        doSearch();
    }
    //单个删除条件
    function delCondition(tag){
        var _em_text = $(tag).prev().text();
        var _min_value = _em_text.split('-')[0];
        var _max_value = _em_text.split('-')[1];
        var _this_span_text = $(tag).siblings('span').text();

        switch (_this_span_text){
            case '分类：':
                eachDelMore('filter-ID-sign','input-media-cate',_em_text,_min_value,_max_value)
            break;
            case '地域：':
                eachDelMore('filter-area','input-follower-area',_em_text,_min_value,_max_value)
            break;
            case '平台：':
                eachDelMore('filter-platform','input-platform-type',_em_text,_min_value,_max_value)
                break;
            case '性别：':
                eachDelMore('filter-sex','input-sex',_em_text,_min_value,_max_value)
                break;
            case '参考报价：':
                eachDelSingle('filter-value','input-price',_em_text,_min_value,_max_value)
                break;
            case '粉丝数：':
                eachDelSingle('filter-fans','input-follower-num',_em_text,_min_value,_max_value)
            break;

        }
        $(tag).parent('li').remove();
        if($('.condition-selected li').length < 3){
            $('.condition-selected .selected-last-li').css({display:'none'});
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
        $('#form-video-search ' + '.' + input_type).attr('value', '');
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

                $('#form-video-search ' + '.' + input_type).attr('value', newStr);
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
    function eachUlLi(_this_li,classify,value1,value2){
        var _this_child_span = $(_this_li).children('span').text();
        if(_this_child_span == classify){
            $(_this_li).remove();
            return false;
        }
    }
    // 不限清空样式
    function noLimitChangeCss(_classify_text){
        $('.condition-selected ul li').each(function(){
            var _text_child_text = $(this).children('span').text();
            if(_text_child_text == _classify_text){
                $(this).remove();
            }
        });
    }

    //购物车效果开始
    function selectedResource(event){
        var _this_checked = $(this).parents('tr').find('.select-account input').is(':checked');
        if(_this_checked){
            //购物车飞入动画
            var _img = $(this).parents('tr').find('dl dt img').attr('src');
            var flyer = $('<a style="display:inline-block;width: 60px;height: 60px;border-radius: 50%;overflow: hidden;">' +
                '<img class="u-flyer" src="'+_img+'" style="position: relative;top: -142px;left: -142px;width: 350px;">'+'</a>');
            flyer.fly({
                start: {
                    left: event.clientX+80,
                    top: event.clientY
                },
                end: {
                    left: document.body.clientWidth-240,
                    top: 560,
                    width: 0,
                    height: 0
                }
            });

            var _dt_html = $(this).parents('tr').find('dt').html();
            var _dd_a_attr = $(this).parents('tr').find('dd a').attr('data-title');
            var _dd_a_txt = $(this).parents('tr').find('dd a').text();
            var _data_value = $(this).parents('tr').find('dd a').attr('data-value');
            //获取json数据
            var _info_text = $(this).parents('tr').find('.info-json').text();
            var info_obj_new = JSON.parse(_info_text);
            //var arr = [];
            //通过json获取所需要的值
            var _media_uuid = info_obj_new.media_uuid;
            var _fans_num = parseFloat(info_obj_new.follower_num/10000).toFixed(1) ;
            var _platform = info_obj_new.platform;
            var _org_price = info_obj_new.orig_video_price;
            var _offline_price = info_obj_new.offline_price;
            var _sex_num = info_obj_new.sex_type;

            //判断展示形式是线下活动还是原创视频和其价格
            var _price = '';
            var _show_type = '';
            if(_platform == 5){
                _show_type = '原创视频';
                _price = _org_price;
            } else {
                _show_type = '线下活动';
                _price = _offline_price;
            }
            var _shopping_car = $('.contact').siblings('.right-box');
            if(_shopping_car.css('right') == '-420px'){
                _shopping_car.stop().animate({right:'0'},500);
                $('.contact').stop().animate({right:'360px'},500);
            }
            $('.shopping-car tbody').prepend('<tr data-uuid="'+_media_uuid+'">' +
                '<td class="clearfix">'+
                    '<div class="pic-img fl">'+
                         _dt_html+
                        '<i class="car-platform-icon '+judgePlatform(_platform)+'">'+'</i>'+
                    '</div>'+
                    '<ul class="fl ID-info">'+
                        '<li>'+
                            '<a class="synopsis" data-str="6" data-value="'+_data_value+'" data-title="'+_dd_a_attr+'">'+
                                _dd_a_txt+
                            '</a>'+
                            '<i class="sex-icon '+judgeSex(_sex_num)+'">'+'</i>'+

                        '</li>'+
                        '<li>'+
                            '<span>'+'粉丝数：'+'</span>'+
                            '<i class="fans-num">'+_fans_num+'</i>'+'万'+
                        '</li>'+
                    '</ul>'+
                '</td>'+
                '<td>'+
                    '<span class="show-type">'+_show_type+'</span>'+'<br />'+'￥'+
                    '<i class="single-count">'+_price+'</i>'+
                '</td>'+
                '<td>'+
                    '<span class="shopping-resource-del"></span>'+
                '</td>'+
                '</tr>');
            var _shopping_tr_length = $('.card-body tbody tr').length;
            if(_shopping_tr_length >= 2){
                $('.card-head .btn-danger').css('display','inline-block');
            }
            $('.card-head span:eq(0) i,.contact-shopping-cart em').text(_shopping_tr_length);
            // 更新cookie,从cookie中添加一个资源
            addMediaIntoCookie(_media_uuid);
        } else {
            var _this_data_value = $(this).parents('tr').attr('data-uuid');
            $('.card-body .table tbody tr').each(function(){
                if($(this).attr('data-uuid') == _this_data_value){
                    $(this).remove();
                }
            });
            var _shopping_tr_length = $('.card-body tbody tr').length;
            var _shopping_car = $('.contact').siblings('.right-box');
            if(_shopping_car.css('right') == '-420px'){
                _shopping_car.stop().animate({right:'-420px'},500);
                $('.contact').stop().animate({right:'0px'},500);
            }
            if(_shopping_tr_length <= 1){
                $('.card-head .btn-danger').css('display','none');
            }
            $('.card-head span:eq(0) i,.contact-shopping-cart em').text(_shopping_tr_length);
            // 更新cookie,从cookie中移除一个资源
            deleteMediaInCookie(_media_uuid);
        }
        changeShoppingPrice();
        changeShoppingFans();
    }

    //遍历购物车统计价格
    function changeShoppingPrice(){
        var price_count = 0;
        $(".card-body .single-count").each(function(){
            price_count += parseInt($(this).text());
        });
        $('.count-sum').text(price_count);
    }
    //遍历购物车统计粉丝数
    function changeShoppingFans(){
        var price_count = 0;
        $(".card-body .fans-num").each(function(){
            price_count += parseInt($(this).text()*10000);
        });
        $('.fans-sum i').text(price_count);
    }
    //一键清空购物车
    function emptyShoppingCar(){
        $(this).css({display:'none'});
        $(this).parents('.card-head').siblings('.card-body').find('table tbody').children('tr').remove();
        $('.media-stage .table tbody .select-account input').prop({checked:false});
        $('.card-head span:eq(0) i,.contact-shopping-cart em').text(0);
        $('.checked-all-resource-input').prop({checked:false});
        changeShoppingPrice();
        changeShoppingFans();
        Cookies.set('video_shopping_car', '');
    }
    // 下拉单选择某一个
    function selectedOption(){
        var _txet = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_txet);
    }
    //排序
    function AccountSort(){
        var type = $(this).parent().parent().find('span').text();
        var input_class = '';
        switch (type) {
            case '粉丝数':
                input_class = 'input-sort-by-follower-num';
                break;
            case '参考报价':
                input_class = 'input-sort-by-price';
                break;
            case '平均观看人数':
                input_class = 'input-sort-by-avg-watch-num';
                break;
        }
        if ($(this).hasClass('up')) {
            if ($(this).hasClass('up-red')) {
                $(this).removeClass('up-red');
                $('.' + input_class).val(null);// 取消排序
            } else {
                $(this).addClass('up-red');
                $(this).parents('th').siblings('th').find('.up-red').removeClass('up-red');
                $(this).parents('th').siblings('th').find('.down-red').removeClass('down-red');
                $(this).siblings('i').removeClass('down-red');
                $('.' + input_class).val('sort_asc');// 升序
                if(input_class == "input-sort-by-follower-num"){
                    $(".input-sort-by-price").val("");
                    $(".input-sort-by-avg-watch-num").val("");
                }
                if(input_class == "input-sort-by-price"){
                    $(".input-sort-by-follower-num").val("");
                    $(".input-sort-by-avg-watch-num").val("");
                }
                if(input_class == "input-sort-by-avg-watch-num"){
                    $(".input-sort-by-price").val("");
                    $(".input-sort-by-follower-num").val("");
                }

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
                $('.' + input_class).val('sort_desc');// 倒序
                if(input_class == "input-sort-by-follower-num"){
                    $(".input-sort-by-price").val("");
                    $(".input-sort-by-avg-watch-num").val("");
                }
                if(input_class == "input-sort-by-price"){
                    $(".input-sort-by-follower-num").val("");
                    $(".input-sort-by-avg-watch-num").val("");
                }
                if(input_class == "input-sort-by-avg-watch-num"){
                    $(".input-sort-by-price").val("");
                    $(".input-sort-by-follower-num").val("");
                }
            }
        }
        doSearch();
    }
    //购物车媒体库和投放判断
    function judgeTrLen(current){
        var _len = $(current).parents('.card-footer').prev().find('tbody tr').length;
        if(_len == 0){
            layer.msg('您还未选择任何的账号到购物车！请添加',{
                icon:0,
                time: 1500
            });
            return false;
        }
    }

    // 将选中资源的media uuid加入cookie
    function addMediaIntoCookie(media_uuid) {
        var video_shopping_car = Cookies.get('video_shopping_car');
        if(typeof(video_shopping_car) == 'undefined' || video_shopping_car == ''){
            Cookies.set('video_shopping_car', media_uuid + ',');
        } else if(video_shopping_car.indexOf(media_uuid) < 0){
            Cookies.set('video_shopping_car', video_shopping_car + media_uuid + ',');
        }
    }
    // 根据视频 media uuid从cookie里移除给定的资源
    function deleteMediaInCookie(media_uuid) {
        var video_shopping_car = Cookies.get('video_shopping_car');
        if(video_shopping_car != ''){
            video_shopping_car = video_shopping_car.replace(media_uuid + ',', '');
            Cookies.set('video_shopping_car', video_shopping_car, {expires: 7});
        }
    }
    //列表页查询操作
    function doSearch(){
        searchLoading();
        $(".form-video-search").submit();
    }

    function searchLoading(){
        //loading层
        var index = layer.load(1, {
            shade: [0.1,"rgba(0,0,0,.5)"],//0.1透明度的白色背景
        });
        return index;
    }

    // 立即预约
    $('.right-box .btn-put-in-resource').click(function () {
        var selected_media_list = $(this).parents('.card-footer').prev().find('tbody tr');
        if (selected_media_list.length == 0) {
            wom_alert.msg({
                icon:"warning",
                content:"请选择视频账号加入购物车!",
                delay_time:1500
            });
            return false;
        }
        var selected_media_uuid_list = new Array();
        selected_media_list.each(function () {
            var media_uuid = $(this).data('uuid');
            selected_media_uuid_list.push(media_uuid);
        });
        var plan_uuid = $('#id-plan-uuid').val();
        var create_plan_url = $('#id-create-plan-order-url').val();
        // 将选中的资源post提交
        var url = $('#id-add-media-plan-order-url').val();
        //console.log(url);
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: { media_uuid_list: selected_media_uuid_list,plan_uuid:plan_uuid},
            beforeSend: function () {
                //让提交按钮失效，以实现防止按钮重复点击
            },
            complete: function () {
                //按钮重新有效
                //$('xxx').removeAttr('disabled');
            },
            success: function (resp) {
                if(resp.err_code == 0){
                    //Cookies.remove('video_shopping_car');
                    window.location.href = create_plan_url+"&plan_uuid="+resp.plan_uuid;
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
    });


    //购物车中的加入媒体库
    $('.right-box .btn-add-media-lib').click(function (){
        var group_uuid = $("#id-group-uuid").val();
        var selected_media_list = $(this).parents('.card-footer').prev().find('tbody tr');
        if (selected_media_list.length == 0) {
            wom_alert.msg({
                icon:"warning",
                content:"请选择视频账号加入购物车!",
                delay_time:1500
            });
            return false;
        }
        var selected_media_uuid_list = Cookies.get('video_shopping_car');
        if(group_uuid ==""){
            openMediaLibraryModal(selected_media_uuid_list);
        }else{
            addMediaIntoExistLib(group_uuid,selected_media_uuid_list);
        }
    });

/**
     * 打开媒体库的弹框
     * @param selected_media_uuid_list 选择的微信资源的uuid拼接成的字符串,以逗号隔开
     */
    function openMediaLibraryModal(selected_media_uuid_list) {
        var get_video_media_lib_url = $('#id-get-video-library-list-url').val();
        $.ajax({
            url: get_video_media_lib_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            success: function (resp) {
                if (resp.err_code == 0) {
                    var video_media_lib_list = resp.video_media_lib_list;
                    if (video_media_lib_list.length == 0) {
                        // 新增媒体库
                        $("#modal-new-media-lib .selected-media-uuid-list").val(selected_media_uuid_list);
                        $('#modal-new-media-lib').modal('show');
                    } else {
                        // 选择媒体库
                        $("#modal-select-media-lib .selected-media-uuid-list").val(selected_media_uuid_list);
                        $("#modal-select-media-lib .selected-media .media-name ul li").remove(); // 清空
                        for (var i = 0; i < video_media_lib_list.length; i++) {
                            $("#modal-select-media-lib .media-name ul").append('<li data-uuid="' + video_media_lib_list[i].uuid + '">' + video_media_lib_list[i].group_name + '</li>')
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
        var get_video_media_lib_url = $('#id-get-video-library-list-url').val();
        $.ajax({
            url: get_video_media_lib_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {media_lib_name: _media_lib_name},
            success: function (resp) {
                if (resp.err_code == 0) {
                    var video_media_lib_list = resp.video_media_lib_list;
                    // 选择媒体库
                    $("#modal-select-media-lib .selected-media .media-name ul li").remove(); // 清空
                    for (var i = 0; i < video_media_lib_list.length; i++) {
                        $("#modal-select-media-lib .media-name ul").append('<li data-uuid="' + video_media_lib_list[i].uuid + '">' + video_media_lib_list[i].group_name + '</li>')
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
    $("#modal-select-media-lib .btn-save").click(function () {
        var type = $('#modal-select-media-lib .selected-option').val(); // 获取类型(新建或添加到已有)
        if(type ==""){ //默认选择已有媒体库
            type ='select-one';
        }
        var selected_media_uuid_list = $('#modal-select-media-lib .selected-media-uuid-list').val();
        if (type == 'select-one') {
            var media_lib_uuid = $("#modal-select-media-lib .in-selected-media span.fl").attr("data-uuid"); // 媒体库uuid
            var media_lib_name = $("#modal-select-media-lib .in-selected-media span.fl").text();
            if (typeof(media_lib_uuid)== 'undefined') {
                wom_alert.msg({
                    icon: "error",
                    content: "请选择媒体库!",
                    delay_time: 2000
                });
                return false;
            }
        } else if (type == 'new-one') {
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

    // 将视频资源添加到媒体库
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
                        Cookies.remove('video_shopping_car');
                        // 跳转到个人中心>视频媒体库
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


    //将视频资源添加到已存在媒体库
    function   addMediaIntoExistLib(group_uuid,selected_media_uuid_list){
        var add_media_url = $('#id-add-media-into-exist-lib-url').val();
        $.ajax({
            url: add_media_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                media_lib_uuid: group_uuid,
                selected_media_uuid_list: selected_media_uuid_list
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    Cookies.remove('video_shopping_car');
                    // 跳转到个人中心>视频媒体库
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
    }


//pjax刷新js
function pjaxRefVideo(){
    //资源总数
    $(".total-resource").children("i").text($(".totalCount").val());
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
        if(custom_page != ""){
            $("input.page").attr("value", $('#id-custom-page').val() -1);
            doSearch();
        }
    });
    //购物车事件绑定
    $('.media-stage tbody .select-account').on('click','input', selectedResource);
    $('.card-head').on('click','.delete-all',emptyShoppingCar);

    //通过cookies选中已选资源
    var video_shopping_car = Cookies.get('video_shopping_car');
    var get_shopping_car_cookie_json_url = $("#id-get-list-cookie-json-url").val()
    if(typeof(video_shopping_car) != "undefined"){
        var platform_cookies = video_shopping_car;
        console.log(platform_cookies);
        $.ajax({
            url: get_shopping_car_cookie_json_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                media_cookies: platform_cookies,
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    //清空购物车
                    //console.log(resp.json_array[0]);

                    $('.card-body tbody').html("");
                    for(var i=0 ; i< resp.json_array.length; i++){
                        var _video_uuid = resp.json_array[i].platform_uuid ;
                        var _platform = resp.json_array[i].platform_type;
                        var _account_name = resp.json_array[i].account_name;
                        var _fans_num = parseFloat(resp.json_array[i].follower_num/10000).toFixed(1);
                        var _price = resp.json_array[i].price_orig_two;
                        var _video_url = resp.json_array[i].url;
                        var _sex_num = resp.json_array[i].sex;

                        //判断展示形式是线下活动还是原创视频和其价格
                        var _show_type = '';
                        if(_platform == 5){
                            _show_type = '原创视频';
                        } else {
                            _show_type = '线下活动';
                        }
                        //console.log(resp.json_array[i].cate_array);
                        $(".table-video-list").find("tr").each(function(){
                            var table_media_uuid = $(this).attr("data-uuid");
                            if(table_media_uuid == _video_uuid){
                                $(this).find(".select-account input").prop("checked",true);
                            }
                        });
                        $('.card-body tbody').prepend('<tr data-uuid="'+_video_uuid+'">' +
                            '<td class="clearfix">'+
                            '<div class="pic-img fl">'+
                            '<a target="_blank" href="'+_video_url+'">'+
                            '<img src="'+_video_uuid+'.jpg" alt="">'+
                            '</a>'+
                            '<i class="car-platform-icon '+judgePlatform(_platform)+'">'+'</i>'+
                            '</div>'+
                            '<ul class="fl ID-info">'+
                            '<li>'+
                            '<a class="" data-value="'+_video_uuid+'" data-title="'+_account_name+'">'+
                            cutString(_account_name,12)+
                            '</a>'+
                            '<i class="sex-icon '+judgeSex(_sex_num)+'">'+'</i>'+

                            '</li>'+
                            '<li>'+
                            '<span>'+'粉丝数：'+'</span>'+
                            '<i class="fans-num">'+_fans_num+'</i>'+'万'+
                            '</li>'+
                            '</ul>'+
                            '</td>'+
                            '<td>'+
                            '<span class="show-type">'+_show_type+'</span>'+'<br />'+'￥'+
                            '<i class="single-count">'+_price+'</i>'+
                            '</td>'+
                            '<td>'+
                            '<span class="shopping-resource-del"></span>'+
                            '</td>'+
                            '</tr>');

                        var tr_len = $('.card-body tbody tr').length;
                        if (tr_len >= 2) {
                            $('.card-head .btn-danger').css('display', 'inline-block');
                        }
                        $('.card-head span:eq(0) i,.contact-shopping-cart em').text(tr_len);
                        addMediaIntoCookie(_video_uuid);
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
        })

    }
//字符串截取函数
    function cutString(str,len) {
        if(str == null){
            return;
        }
        var strlen = 0;
        var s = "";
        for (var i = 0; i < str.length; i++) {
            if (str.charCodeAt(i) > 128) {
                strlen += 2;
            } else {
                strlen++;
            }
            if (strlen > len) {
                return s+"...";
            }
            s += str.charAt(i);
        }
        return s;
    }
    //资源列表页里的"加入媒体库"
    $(".btn-add-media-lib-in-media-list span").click(function () {
        var group_uuid = $("#id-group-uuid").val();
        var selected_media_uuid_list = [];
        var media_uuid = $(this).closest('tr').data('uuid');
        selected_media_uuid_list.push(media_uuid);
        if(group_uuid ==""){
            openMediaLibraryModal(selected_media_uuid_list);
        }else{
            addMediaIntoExistLib(group_uuid,selected_media_uuid_list);
        }
    });


    //全部账号
    $(".media-stage .table-title .all-account").click(function(){
        $("input[name=main_push]").val(-1);
        doSearch();
    });
    //主推账号
    $(".media-stage .table-title .main-push").click(function(){
        $("input[name=main_push]").val(1);
        doSearch();
    });



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
                    var video_media_list_arr = resp.video_media_list;
                    console.log(video_media_list_arr[0]['video_media_uuid']);
                    $('.table-video-list tbody tr').each(function(){
                        var _data_uuid = $(this).attr('data-uuid');
                        for(var i = 0; i < video_media_list_arr.length; i++){
                            if(_data_uuid == video_media_list_arr[i]['video_media_uuid']){
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
    $("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($(".media-stage")).css({ top: offset.top + a.outerHeight() + 6, left: offset.left + a.outerWidth() - 10 }).fadeIn(function () {
                });
            },
            function(){
                $(".show-all-info").remove();
            }
        );
    });

    //购物车全选开始
    $('.checked-all-resource-input').click(function(){
        var _select_option = $(this).parents('.media-stage').find('.table tbody .select-account input');
        if($(this).is(':checked')){
            _select_option.each(function(){
                if(!$(this).is(':checked')){
                    $(this).click();
                };
            });
        }else{
            _select_option.each(function(){
                if($(this).is(':checked')){
                    $(this).click();
                };
            });
        }
        changeShoppingPrice();
        changeShoppingFans();
    })

    //是否存在资源
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        console.log(resourceLength);
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
//平台的判断函数封装
function judgePlatform(_platform){
    var _platform_name = '';
    switch (_platform){
        case '1':
            _platform_name = 'car-platform-icon-hj';
            break;
        case '2':
            _platform_name = 'car-platform-icon-xm';
            break;
        case '3':
            _platform_name = 'car-platform-icon-hn';
            break;
        case '4':
            _platform_name = 'platform-icon-meip';
            break;
        case '5':
            _platform_name = 'car-platform-icon-mp';
            break;
        case '6':
            _platform_name = 'car-platform-icon-dy';
            break;
        case '7':
            _platform_name = 'car-platform-icon-yk';
            break;
        case '8':
            _platform_name = 'car-platform-icon-tb';
            break;
        case '9':
            _platform_name = 'car-platform-icon-yzb';
            break;

    }
    return _platform_name;
}

//判断性别
function judgeSex(_sex_num){
    var _sex_type = '';
    if(_sex_num == 1){
        _sex_type = 'sex-icon-b';
    } else if(_sex_num == 2){
        _sex_type = 'sex-icon-g';
    } else if(_sex_num == 0){
        _sex_type = '';
    }
    return _sex_type;
}


