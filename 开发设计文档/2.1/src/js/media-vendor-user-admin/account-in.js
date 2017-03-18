$(function(){
    //表格发布类型选择
    $('.select-btn').click(function(){
        //当发布类型为不接单时，默认价格为0
        if($(this).hasClass('pos-s')){
            $(this).parents("tr").find('.price input').val(0);
        } else{
            $(this).parents("tr").find('.price input').val("");
        }
        $(this).addClass('current-pub-type').parents('li').siblings('li').children('.select-btn').removeClass('current-pub-type');
    });

    //点击提交事件
    $('.btn-submit').click(function(){
        var _len = $("#upload-single-img-file-list li").length;
        //报价是否已填写
        fillInPrice();
        //发布类型是否已经选择
        pubTypeSelect();
        //粉丝截图是否上传
        fansPic(_len);
        //遍历上半部分的input是否为空
        eachInputValue();

    })
    //遍历上半部分的input是否为空
    function eachInputValue(){
        $('.con-area').each(function(){
            if($(this).val() == ''){
                wom_alert.msg({
                    icon:"warning",
                    content:"尚有内容未填写，请填写完整",
                    delay_time: "1500"
                });
            }
        });
        return false;
    }
    //粉丝截图是否上传
    function fansPic(_len){
        if(_len == 0){
            wom_alert.msg({
                icon:"warning",
                content:"请上传粉丝截图",
                delay_time: "1500"
            })
        }
        return false;
    }
    //发布类型是否已经选择
    function pubTypeSelect(){
        $('.pos-table tbody tr').each(function(){
            var _selected_len = $(this).find('.current-pub-type').length;

            if(_selected_len == 0){
                wom_alert.msg({
                    icon:"warning",
                    content:"尚有发布类型未选择，请选择",
                    delay_time: "1500"
                })
            }
        })
        return false;
    }
    //报价是否已填写
    function fillInPrice(){
        $('.pos-table tbody tr .price-value').each(function(){
            if($(this).val() == ''){
                wom_alert.msg({
                    icon:"warning",
                    content:"尚有报价未填写，请填报价",
                    delay_time: "1500"
                });
            }
        })
        return false;
    }
})