$(function(){
    //搜索
    $(".btn-search-order").click(function(){
        $(".form-order-search").submit();
    });

})

//pjax调用js
function pjaxRefOrder(){
    //鼠标放上去显示完整信息ID篇
    $("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($(".weibo-table")).css({ top: offset.top + a.outerHeight() - 4, left: offset.left + a.outerWidth() + 1 }).fadeIn();
            },
            function(){ $(".show-all-info").remove();
            }
        );
    });

    //选择下拉订单状态
    $('.dropdown .dropdown-order-status').on('click','li',selectedOption);
    // 下拉单选择某一个
    function selectedOption(){
        var _text = $(this).text();
        var status = $(this).data('status');
        $("input[name=order_status]").val(status);
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }

    $('.dropdown .dropdown-order-status li').each(function(){
        var order_status = $("input[name=order_status]").val();
        if($(this).data('status') == order_status){
            $(this).parent().prev().find('span:eq(0)').text($(this).text());
        }
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

    //~~~~~~判断有无资源~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 1){
            $(".no-order").css("display","block");
        }else{
            $(".no-order").css("display","none");
        }
    }
    isResource();

    //为每个查看需求添加href属性,调用modal
    $(".check-order-require").on("click",function(){
        var plan_desc = $(this).data("desc");
        $(this).attr('data-target','#check-order-require');
        $('#check-order-require .modal-body p').text(plan_desc);
    })

    //~~~~~删除已选择的资源~~~~~
    $(".weibo-order-table").on("click",".delete-order",function(){
        var element_delete = $(this).parents("tr");
        var order_uuid = $(this).data('uuid');
        var url = $(this).data('url');
        layer.confirm('您确定要删除该账号吗？', {
            btn: ['确定', '取消']
        }, function () {
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: { order_uuid: order_uuid},
                beforeSend: function () {
                    //让提交按钮失效，以实现防止按钮重复点击
                },
                complete: function () {
                    //按钮重新有效
                    //$('xxx').removeAttr('disabled');
                },
                success: function (resp) {
                    if(resp.err_code == 0){
                        layer.msg('删除成功 !', {
                            icon: 1,
                            time: 1000
                        });
                        element_delete.remove();
                        isResource();
                    } else {
                        layer.msg('系统出错', {
                            icon: 2,
                            time: 1500
                        });
                        return false;
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    layer.msg('系统出错', {
                        icon: 2,
                        time: 1500
                    });
                    return false;
                }
            });
        })
    });


    //查看预约详情
    $(".check-order-require").click(function(){
        var order_uuid = $(this).data('uuid');
        var url = $("#id-check-plan-desc-url").val();
        window.open(url+"&order_uuid="+order_uuid);
    });


}