$(function(){
    //~~~~~~判断有无资源~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 10){
            $(".table-footer").css("display","none");
            if(resourceLength < 1){
                $(".no-order").css("display","block");
            }else{
                $(".no-order").css("display","none");
            }
        }else{
            $(".table-footer").css("display","block");
        }
    }
    isResource();

    //表格二维码图片显示
    $('.ewm').hover(function(){
        $(this).siblings('img').css({display:'block'});
    },function(){
        $(this).siblings('img').css({display:'none'});
    })

    //鼠标放上去显示完整信息ID篇
    $("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($("table")).css({ top: offset.top + a.outerHeight() + 6, left: offset.left + a.outerWidth() + 1 }).fadeIn(function () {
                });
            },
            function(){ $(".show-all-info").remove();
            }
        );
    });

    //为每个查看需求添加href属性,调用modal
    $(".check-order-require").on("click",function(){
        $(this).attr('data-target','#check-order-require');
    })

    //字数统计
    //将文本进行转换，得到总的字符数。
    function getLength(str){
        // 匹配中文字符的正则表达式： [\u4e00-\u9fa5]
        return String(str).replace(/[\u4e00-\u9fa5]/g,'aa').length;
    }
    //字数限制函数
    function fontNumberLimit(element,location,num){
        var fontNumber = Math.ceil(getLength(element.val())/2);
        if (fontNumber <= num) {
            location.text(num - fontNumber);
        }else{
            var now_con = element.val();
            var max_con = now_con.substr(0,num - 1);
            $(element).val(max_con);
        }
    }
    //字数限制
    //标题字数限制
    $(".refuse-reason-textarea").on("input",function(){
        fontNumberLimit($(this),$(".refuse-reason-section em"),30);
    });

    //直投订单详情图片的显示
    $(".btn-view-cover-pic").on("click",function(){
        $(".view-cover-pic").slideToggle(200);
    })
    //鼠标移入显示活动名称全称
    $('.active-full-name').each(function(){
        var _data_title = $(this).attr('data-title');
        if(_data_title == undefined || _data_title == '') return;
        $(this).data('data-title',_data_title).hover(function(){
            var offset = $(this).offset();
            $("<div class='show-all-info'>"+_data_title+"</div>").appendTo($(".table")).css({ top: offset.top + $(this).outerHeight()+10, left: offset.left + $(this).outerWidth() - 10 }).fadeIn(function () {
            });
        },function(){
            $(".show-all-info").remove();
        })
    })
    //~~~~~~~固定modal~~~~~~
    //$("#modal-resubmit-execute-link").modal({backdrop: "static", keyboard: false});

    //按钮json
    var buttons_config = {
        "accept-order":"btn-accept-order",//接单
        "refuse-order":"btn-refuse-order",//拒单
        "submit-execute-link":"btn-submit-execute-link",//提交执行链接
        "show-execute-link":"btn-show-execute-link",//执行链接
        "resubmit-execute-link":"btn-resubmit-execute-link",//执行反馈
        "submit-effect-shots":"btn-submit-effect-shots",//提交效果
        "show-effect-shots":"btn-show-effect-shots",//效果截图
        "direct-order-detail":"btn-direct-order-detail",//直投订单详情
        "invalid-order-info":"btn-invalid-order-info",//原因
        "arrange-order-detail":"btn-arrange-order-detail",//原创约稿详情(待审核)
        "arrange-order-more-detail": "btn-arrange-order-more-detail",//原创约稿详情(执行中)
        "show-empty":"btn-show-empty",
        "view-report":"btn-view-report" //查看报告
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

        //操作结果显示
        resultAndOperateJudge(_this,'operate',order_info_config.operate_action);

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
        $(_this).find('.'+_result_operate+' li').each(function(){
            var _attr_data_btn = $(this).attr('data-btn');
            for(var i = 0; i <btn_arr.length ;i++){
                if(_attr_data_btn == btn_arr[i]){
                    $(this).css('display','block');
                }
            }
        })
    }
})