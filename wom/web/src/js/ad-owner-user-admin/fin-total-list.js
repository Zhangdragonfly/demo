
function finTotalList(){

    //搜索
    $(".btn-search-total").click(function(){
        $(".form-total-search").submit();
    });

    //财务类型状态
    $('.dropdown .dropdown-fin-type').on('click','li',selectedOption);
    // 下拉单选择某一个
    function selectedOption(){
        var _text = $(this).text();
        var type = $(this).data('type');
        $("input[name=fin_type]").val(type);
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }

    $('.dropdown .dropdown-fin-type li').each(function(){
        var fin_type = $("input[name=fin_type]").val();
        if($(this).data('type') == fin_type){
            $(this).parent().prev().find('span:eq(0)').text($(this).text());
        }
    });

    //交易方式状态
    $('.dropdown .dropdown-trade-type').on('click','li',selectedTradeOption);
    // 下拉单选择某一个
    function selectedTradeOption(){
        var _text = $(this).text();
        var type = $(this).data('type');
        $("input[name=trade_type]").val(type);
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }

    $('.dropdown .dropdown-trade-type li').each(function(){
        var trade_type = $("input[name=trade_type]").val();
        if($(this).data('type') == trade_type){
            $(this).parent().prev().find('span:eq(0)').text($(this).text());
        }
    });

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
        $(".form-total-search").submit();
    });

    //选择时间插件
    $(".datetimepicker").datetimepicker({
        lang:"ch",           //语言选择中文
        format:"Y-m-d H:i",      //格式化日期
        i18n:{
            // 以中文显示月份
            de:{
                months:["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月",],
                // 以中文显示每周（必须按此顺序，否则日期出错）
                dayOfWeek:["日","一","二","三","四","五","六"]
            }
        }
        // 显示成年月日，时间--
    });
    $('.search').click(function () {
        var star = $('input[name="iStarTime"]').val();
        var over = $('input[name="iOverTime"]').val();
        var iss = $('.iSource').val();
        var iss_a = $('.iSource_a').val();
        location.href = "/acenter/recharge/iss/"+iss+"/star/" + star + "/over/" + over + ".html";
    })


}