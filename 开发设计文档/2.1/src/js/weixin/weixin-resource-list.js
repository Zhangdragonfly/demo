$(function(){
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
            var _data_value = $(this).siblings('.dropdown').find('a').attr('data-value');
            if(_data_value == '零售价：'){
                $('.condition-selected ul li').each(function(){
                    var _text_child_text = $(this).children('span').text();
                    if(_text_child_text == _data_value){
                        $(this).remove();
                    }
                });
            }
            switch (_classify_text){
                case '分类：':case '地域：':case '类型：':case '头条阅读数：':case '粉丝数：':
                    noLimitChangeCss(_classify_text);
                break;
            }
        }
        selectedLiLen()
    })
    //已选条件全部删除
    $('.selected-last-li').click(function(){
        $(this).css('display','none');
        $(this).siblings('li').remove();
        $('.filter-item li i').removeClass('filter-active');
        $('.filter-unlimit').addClass('filter-active');
    });
    //购物车事件绑定
    $('.table-item tbody .select-account').on('click','input', selectedResource);
    $('.resource-head').on('click','.delete-all',emptyShoppingCar);
    //单个删除购物车内的资源
    $(document).on('click','.shopping-resource-del',function(){
        var _data = $(this).parent().siblings('td').find('.ID-name').attr('data-value');
        $('.table-item tbody .ID-name').each(function(){
            var _this_data = $(this).attr('data-value');
            if(_this_data == _data){
                $(this).parents('.account').siblings('.select-account').find('input').prop({checked:false});
            }
        })
        $(this).parents('tr').remove();
        var _len = $('.resource-table tbody tr').length;
        if(_len == 0){
            $('#checked-all').prop({checked:false});
        }
        if(_len <= 1){
            $('.resource-head .btn-danger').css('display','none');
        };
        $('.resource-head span:eq(0) i,.contact-shopping-cart em').text(_len);
        changeShoppingPrice();
        changeShoppingFans();
    });

    //购物车全选开始
    $('.checked-all-resource-input').click(function(){
        var _select_option = $(this).parents('.table-item').find('.table tbody .select-account input');
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
     });
    //价格及粉丝区域的验证
    $('.filter-price-button span').click(function(){
        var _classify_text = $(this).parents('.filter-item').find('.dropdown a').attr('data-value');
        customConditionSearch(this,'.filter-value',_classify_text);
    });
    $('.filter-fans-btn span').click(function(){
        var _classify_text = $(this).parents('.filter-item').children('h3').text();
        customConditionSearch(this,'.filter-fans',_classify_text);
    });
    //排序
    $('.sort-icon').on('click','i',AccountSort);
    //购物车加入媒体库
    $('.add-shopping-selected').click(function(){
        judgeTrLen(this);
    });
    $('.put-in-resource').click(function(){
        judgeTrLen(this);
    });
    //表格头部导航
    $('.table-title .title-select li').click(function(){
        $(this).addClass('table-active').siblings().removeClass('table-active');
    });
    //modal层的切换
    $('#addMedia .selected-option li').click(function(){
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
    //鼠标放上去显示完整信息
    $("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($(".table-item")).css({ top: offset.top + a.outerHeight() + 6, left: offset.left + a.outerWidth() + 1 }).fadeIn(function () {
                });
            },
            function(){ $(".show-all-info").remove();
            }
        );
    });
})
    //条件选择开始
    function selectCondition(){
        $(this).parent().prev().removeClass('filter-active');
        var _h3_text = $(this).parent().siblings('h3').text();
        var _this_i = $(this).children('i').text();
        var _data_value = $(this).parent().siblings('.dropdown').find('a').attr('data-value');

        if(_data_value == '零售价：'){
            selectedOne(this,'零售价',_data_value,_this_i)
        }
        switch (_h3_text){
            case '分类：':
                selectedMore(this,'filter-ID-sign',_h3_text,_this_i);
                break;
            case '地域：':
                selectedMore(this,'filter-area',_h3_text,_this_i);
                break;
            case '类型：':
                selectedMore(this,'filter-ID-type',_h3_text,_this_i);
                break;
            case '头条阅读数：':
                selectedOne(this,'头条阅读数',_h3_text,_this_i)
                break;
            case '粉丝数：':
                selectedOne(this,'粉丝数',_h3_text,_this_i)
                break;
        }
        selectedLiLen()
    }
    //多选条件选择
    function selectedMore(this_tag,filter_classify,_h3_text,_this_i){
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
            if(filter_classify == 'filter-ID-type'){
                limitMaxLen(this_tag,_len,_len,_h3_text,_this_i)
            };
        }
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
    function selectedOne(_this,condition,span_text,_this_i){
        if($(_this).children('i').hasClass('filter-active')){
            $('.condition-selected li').each(function(){
                var _this_span = $(this).children('span').text();
                if(_this_span.indexOf(condition) != -1){
                    $(this).remove();
                }
            });
            $(_this).children('i').removeClass('filter-active');
            $(_this).parents('ul').siblings('.filter-unlimit').addClass('filter-active');
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
                time: 1500
            });
            return false;
        } else if(!_reg.test(_data_value_min) || ! _reg.test(_data_value_max)){
            layer.msg('请输入正数',{
                icon:0,
                time: 1500
            });
            return false;
        }else if(parseInt(_data_value_min) >= parseInt(_data_value_max)){
            layer.msg('请输入正确的区间',{
                icon:0,
                time: 1500
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
            $('input.input-retail-price').val(_data_value_min+ ','+ _data_value_max);
            $('.condition-selected .selected-last-li').before('<li><span>'+_classify_text+'</span><em>'+_data_value_min+'-'+_data_value_max+'元'+'</em><i onclick="delCondition(this)" class="clear-icon"></i></li>');
        }
        selectedLiLen();
    }
    //单个删除条件
    function delCondition(tag){
        var _em_text = $(tag).prev().text();
        var _min_value = _em_text.split('-')[0];
        var _max_value = _em_text.split('-')[1];
        var _this_span_text = $(tag).siblings('span').text();

        switch (_this_span_text){
            case '分类：':
                eachDel('filter-ID-sign',_em_text,_min_value,_max_value)
            break;
            case '地域：':
                eachDel('filter-area',_em_text,_min_value,_max_value)
            break;
            case '类型：':
                eachDel('filter-ID-type',_em_text,_min_value,_max_value)
            break;
            case '头条阅读数：':
                eachDel('filter-headline-read',_em_text,_min_value,_max_value)
            break;
            case '零售价：':
                eachDel('filter-value',_em_text,_min_value,_max_value)
            break;
            case '粉丝数：':
                eachDel('filter-fans',_em_text,_min_value,_max_value)
            break;
        }
        $(tag).parent('li').remove();
        if($('.condition-selected li').length < 3){
            $('.condition-selected .selected-last-li').css({display:'none'});
        }
    }
    //遍历删除
    function eachDel(filter_classify,_em_text,value1,value2){
        $('.'+filter_classify+' ul li').each(function(){
            var _this_i_text = $(this).children('i').text();
            var _active_i = $(this).parents('ul').find('li .filter-active');
            var _data_min = $(this).children('i').attr('data-min');
            var _data_max = $(this).children('i').attr('data-max');

            if(_data_min == value1 && _data_max == value2){
                $(this).children('i').removeClass('filter-active');
            };
            if(_this_i_text == _em_text){
                $(this).children('i').removeClass('filter-active');
            }
            if(_active_i.length == 0){
                $(this).parents('ul').siblings('.filter-unlimit').addClass('filter-active');
            }
        })
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
            var _img = $(this).parents('tr').find('dl dt img').attr('src');
            var flyer = $('<a style="display:inline-block;width: 60px;height: 60px;border-radius: 50%;overflow: hidden;">' +
                '<img class="u-flyer" src="'+_img+'" style="position: relative;top: -142px;left: -142px;width: 350px;">');
            flyer.fly({
                start: {
                    left: event.clientX+80,
                    top: event.clientY
                },
                end: {
                    left: document.body.clientWidth-240,
                    top: 500,
                    width: 0,
                    height: 0
                }
            });
            var _account_html = $(this).parents('tr').find('.account').html();
            var _price_text = $(this).parents('tr').find('.refer-price .price-json').text();
            var price_obj_new = JSON.parse(_price_text);
            var arr = [];

            for(var name in price_obj_new){
                arr.push(name +  ":" + price_obj_new[name]);
            }
            var _fans_num = arr[1].split(':')[1];
            var _retail_price = arr[2].split(':')[1];

            var _shopping_car = $('.contact').siblings('.shopping');
            if(_shopping_car.css('right') == '-420px'){
                _shopping_car.stop().animate({right:'0'},500);
                $('.contact').stop().animate({right:'360px'},500);
            }
            $('.shopping-resource tbody').prepend('<tr>' +
                '<td class="account">'+_account_html+'</td>'+
                '<td  class="ad-type">'+
                    '<span>'+'多图文第一条'+'</span>'+'￥'+
                    '<i class="single-count">'+_retail_price+'</i>'+
                    '<em class="fans-num" style="display: none">'+_fans_num+'</em>'+
                '</td>'+
                '<td>'+
                    '<span class="shopping-resource-del"></span>'+
                '</td>'+
                '</tr>');
            var _shopping_tr_length = $('.resource-table tbody tr').length;
            if(_shopping_tr_length >= 2){
                $('.resource-head .btn-danger').css('display','inline-block');
            }
            $('.resource-head span:eq(0) i,.contact-shopping-cart em').text(_shopping_tr_length);

        } else {
            var _this_data_value = $(this).parents('tr').find('dl dd a').attr('data-value');
            $('.resource-table .table tbody tr').each(function(){
                if($(this).find('dd a').attr('data-value') == _this_data_value){
                    $(this).remove();
                }
            });
            var _shopping_tr_length = $('.resource-table tbody tr').length;
            var _shopping_car = $('.contact').siblings('.shopping');
            if(_shopping_car.css('right') == '-420px'){
                _shopping_car.stop().animate({right:'-420px'});
                $('.contact').stop().animate({right:'0px'});
            }
            if(_shopping_tr_length <= 1){
                $('.resource-head .btn-danger').css('display','none');
            }
            $('.resource-head span:eq(0) i,.contact-shopping-cart em').text(_shopping_tr_length);
        }
        changeShoppingPrice();
        changeShoppingFans();
    }
    //遍历购物车统计价格
    function changeShoppingPrice(){
        var price_count = 0;
        $(".resource-table .single-count").each(function(){
            price_count += parseInt($(this).text());
        });
        $('.count-sum').text(price_count);
    }
    //遍历购物车统计价格
    function changeShoppingFans(){
        var price_count = 0;
        $(".resource-table .fans-num").each(function(){
            price_count += parseInt($(this).text());
        });
        $('.fans-sum i').text(price_count);
    }
    //一键清空购物车
    function emptyShoppingCar(){
        $(this).css({display:'none'});
        $(this).parents('.resource-head').siblings('.resource-table').find('table tbody').children('tr').remove();
        $('.table-item .table tbody .select-account input').prop({checked:false});
        $('.resource-head span:eq(0) i,.contact-shopping-cart em').text(0);
        $('.checked-all-resource input').prop({checked:false});
        changeShoppingPrice();
        changeShoppingFans();
    }
    // 下拉单选择某一个
    function selectedOption(){
        var _txet = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_txet);
    }
    //排序
    function AccountSort(){
        if($(this).hasClass('up')){
            if($(this).hasClass('up-red')){
                $(this).removeClass('up-red');
            }else{
                $(this).addClass('up-red');
                $(this).parents('th').siblings('th').find('.up-red').removeClass('up-red');
                $(this).siblings('i').removeClass('down-red');
            }
            return;
        }else{
            if($(this).hasClass('down-red')){
                $(this).removeClass('down-red');
            }else{
                $(this).addClass('down-red');
                $(this).parents('th').siblings('th').find('.down-red').removeClass('down-red');
                $(this).siblings('i').removeClass('up-red');
            }
        }
    }
    //购物车媒体库和投放判断
    function judgeTrLen(current){
        var _len = $(current).parents('.shopping-selected').prev().find('tbody tr').length;
        if(_len == 0){
            layer.msg('您还未选择任何的账号到购物车！请添加',{
                icon:0,
                time: 1500
            });
            return false;
        }
    }





