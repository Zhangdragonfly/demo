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
    //~~~~~~判断有无提现信息~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 10){
            $(".table-footer").hide();
            if(resourceLength < 1){
                $(".no-resource").show();
            }else{
                $(".no-resource").hide();
            }
        }else{
            $(".table-footer").show();
        }
    }
    isResource();

    //判断出现哪种弹框
    $(".operate a").on("click",function(){
        var collection_way =  $(this).parent().siblings(".collection-way").text();
        var withdraw_status = $(this).parent().siblings(".withdraw-status").text();
        var alipay_apply_status = $(".alipay-apply-status");
        var bank_card_apply_status = $(".bank-card-apply-status");
        if(collection_way == "支付宝"){
            if(withdraw_status == "已完成"){
                $(".alipay-apply-status-complete").show();
                alipay_apply_status.text("已完成");
            }else{
                $(".alipay-apply-status-complete").hide();
                alipay_apply_status.text("待处理");
            }
            $(this).attr("data-target","#view-detail-alipay");
        }
        if(collection_way == "银行卡"){
            if(withdraw_status == "已完成"){
                $(".bank-card-apply-status-complete").show();
                bank_card_apply_status.text("已完成");
            }else{
                $(".bank-card-apply-status-complete").hide();
                bank_card_apply_status.text("待处理");
            }
            $(this).attr("data-target","#view-detail-bank-card");
        }
    })

})

