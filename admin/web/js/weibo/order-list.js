

//微博订单列表页
function orderList(){

    //分页处理样式
    $(".pagination li a").each(function(){
        $(this).removeAttr("href");
        $(this).attr("style","cursor:pointer;");
    });
    //分页处理
    $(".pagination li a").click(function(){
        $(".main-stage .weibo-order-form input.page").attr("value", $(this).attr("data-page"));
        $(".main-stage .weibo-order-form").submit();
    });
    //时间插件
    $('.plan-time').daterangepicker({
        'singleDatePicker': true,
        'format': 'YYYY-MM-DD',
        'autoApply': false,
        'opens': 'center',
        //'drops': 'down',
        'timePicker': false,
        'timePicker24Hour': false,
        'startDate' : new Date()
    });
    //表头一直悬浮在列表头部
    $(function() {
        $(window).scroll(function() {
            var headFix = $("#header-fixed");
            var _head = $("#fixed-header-data-table");
            var headFixTh = headFix.find("thead tr th");
            var _headTh = _head.find("thead tr th");
            headFix.width(_head.width());
            for(var i=1;i<=_headTh.length;i++){
                headFix.find("thead tr th:nth-child("+i+")").width(_head.find("thead tr th:nth-child("+i+")").width());
            }
            var difference = _head.offset().top - $(this).scrollTop();
            (difference < 54) ? headFix.show() : headFix.hide();
        })
    });

    // 控制左侧导航选中
    if(!$('#weibo .weibo-order-manage .weibo-order-list').hasClass('active')){
        $('.menu-level-1').each(function(){
            $(this).removeClass('active');
        });
        $('.menu-level-2').each(function(){
            $(this).removeClass('active');
        });
        $('.menu-level-3').each(function(){
            $(this).removeClass('active');
        });
        $('#weibo.menu-level-1').addClass('active');
        $('#weibo.menu-level-1 .menu-level-2.weibo-order-manage').addClass('active');
        $('#weibo.menu-level-1 .menu-level-2.weibo-order-manage .menu-level-3.weibo-order-list').addClass('active');
    }



    //时间插件
    $('.plan-time').daterangepicker({
        'singleDatePicker': true,
        'format': 'YYYY-MM-DD',
        'autoApply': false,
        'opens': 'center',
        //'drops': 'down',
        'timePicker': false,
        'timePicker24Hour': false,
        'startDate' : new Date()
    });

    //搜索
    $(".weibo-form .btnSearch").click(function(){
        $(".weibo-order-form").submit();
    });

    //查看详情
    $(".btn-detail").click(function(){
        var url = $(this).data('url');
        var order_uuid = $(this).data('uuid');
        window.open(url+"&uuid="+order_uuid);
    });

}

//微博订单详情页js
function orderDetail(){
    //修改
    $(".btn-save").click(function(){
        var order_uuid = $(this).data('uuid');
        var url = $(this).data('url');
        var status = $("select[name=status]").children("option:selected").val();
        var execute_uuid = $("select[name=execute_uuid]").children("option:selected").val();
        var execute_remark = $("textarea[name=execute_remark]").val();
        var execute_price = $("input[name=execute_price]").val();
        swal({
                title: "确定提交修改?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                closeOnConfirm: false
            },
            function(){
                $.ajax({
                    url: url,
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {
                        order_uuid:order_uuid,
                        status:status,
                        execute_uuid:execute_uuid,
                        execute_remark:execute_remark,
                        execute_price:execute_price
                    },
                    success: function (resp) {
                        if(resp.err_code == 1){
                            swal({title: "修改失败！", text: "请联系系统管理员", type: "error"});
                            return false;
                        }else{
                            swal({title: "修改成功！", text: "", type: "success"});
                            window.location.reload();
                        }
                    },
                    error: function (XMLHttpRequest, msg, errorThrown) {
                        swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                        return false;
                    }
                });
            });

    });

    //取消
    $(".btn-cancel").click(function(){
        var url = $(this).data('url');
        window.location.href = url;
    });

}