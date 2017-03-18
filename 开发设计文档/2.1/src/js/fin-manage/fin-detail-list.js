$(function(){
    //鼠标移入显示账号全称
    $("a[data-title]").each(function() {
        showFullName(this);
    });
    //鼠标移入显示活动名称全称
    $('.active-full-name').each(function(){
        showFullName(this);
    })
    //账号ID显示全称
    $('.ewm-ID em').each(function(){
        showFullName(this);
    })
    //~~~~~~~固定modal~~~~~~
    //$("#modal-to-verify-execute-link").modal({backdrop: "static", keyboard: false});
    //显示全称封装
    function showFullName(_this){
        var title = $(_this).attr('data-title');
        if (title == undefined || title == "") return;
        $(_this).data('data-title', title).hover(function () {
                var offset = $(_this).offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($(".table")).css({ top: offset.top + $(_this).outerHeight() + 10, left: offset.left + $(_this).outerWidth() - 6 }).fadeIn(function () {
                });
            },
            function(){ $(".show-all-info").remove();
            }
        );
    }
    //执行链接确认执行还是反馈
    $("#modal-to-verify-execute-link").find(".radio-select li").on("click","i",function(){
        $(this).addClass("selected").parent().siblings().children("i").removeClass("selected");
    })


    //按钮json
    var buttons_config = {
        "show_execute_link":"btn-show-execute-link",//执行链接
        "show_effect_shots": "btn-show-effect-shots",//执行效果截图
        "show-empty":"btn-show-empty"//执行结果为空
    };
    //初始化表格信息展示
    $('.table-info tbody tr').each(function(){
        var order_info_config = JSON.parse($(this).find('.order-info-config').text());
        initEachOrderInfo(this,order_info_config)
    })

    //页面加载初始化每个账号
    function initEachOrderInfo(_this,order_info_config){
        var _order_account_uuid = order_info_config.order_uuid;
        var _pos_label = order_info_config.pos;
        var _pub_type = order_info_config.pub_type;
        var _order_status = order_info_config.order_status;
        var _order_tatus_label = order_info_config.order_status_label;

        //订单ID标签创建及显示
        $(_this).children('.order-account').append('<span>'+_order_account_uuid+'</span>');
        //判断投放位置
        var _show_pos_label = '';
        if(_pos_label == '-1'){
            _show_pos_label = '未设置';
        } else if(_pos_label == 'm_1'){
            _show_pos_label = '多图文头条';
        } else if(_pos_label == 'm_2'){
            _show_pos_label = '多图文2条';
        } else if(_pos_label == 'm_3'){
            _show_pos_label = '多图文第3-N条';
        } else if(_pos_label == 's_1'){
            _show_pos_label = '单图文';
        }
        //判断只发布还是只原创
        var _show_pub_type = '';
        if(_pub_type == '1'){
            _show_pub_type = '只发布';
        } else if(_pub_type == '2'){
            _show_pub_type = '只原创';
        }
        //创建投放位置显示信息的标签，并显示
        $(_this).find('.pos').append('<span>'+_show_pos_label+'</span><br/><i>'+_show_pub_type+'</i>')

        //订单状态判断及显示
        //创建显示订单状态标签，并高亮显示订单状态
        $(_this).find('.order-status-show').append('<span>'+_order_tatus_label+'</span>');
        switch(_order_status){
            case 5:
                $(_this).find('.order-status-show span').attr('class','green');
                break;
            case -1:case 0:case 1:case 22:case 24:
                $(_this).find('.order-status-show span').attr('class','red');
                break;
            case 3:case 4:case 5:
                $(_this).find('.order-status-show span').attr('class','');
                break;
        }
        //执行结果显示
        resultAndOperateJudge(_this,'execute-result',order_info_config.execute_result);
    }
    //执行结果和操作显示判断
    function resultAndOperateJudge(_this,_result_operate,order_info_config){
        //从json中获取值
        var _json_value = order_info_config;
        //转化成数组
        var _json_value_arr = _json_value.substr(1,_json_value.length-2).split(',');
        //从buttons_config中得到想要的操作按钮，并放入数组中
        var btn_arr = [];
        for(var i = 0; i < _json_value_arr.length;i++){
            btn_arr.push(buttons_config[_json_value_arr[i]]);
        }
        //遍历HTML中operate的li，并获取与传入数值相同的btn；
        $(_this).find('.'+_result_operate+' a').each(function(){
            var _attr_data_btn = $(this).attr('data-btn');
            for(var i = 0; i <btn_arr.length ;i++){
                if(_attr_data_btn == btn_arr[i]){
                    $(this).css('display','block');
                }
            }
        })
    }
})
