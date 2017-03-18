$(function(){
    //~~~~~~判断有无消息~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 5){
            $(".table-footer").hide();
            if(resourceLength < 1){
                $(".no-order").show();
                $(".batch-operate").hide();
            }else{
                $(".no-order").hide();
                $(".batch-operate").show();
            }
        }else{
            $(".table-footer").show();
        }
    }
    isResource();

    //鼠标移入显示账号全称
    $("a[data-title]").each(function () {
        var _this = $(this);
        show_whole_info(_this);
    });
    //鼠标移入显示发布链接全称
    $('.publish-link-ellipsis').each(function () {
        var _this = $(this);
        show_whole_info(_this);
    });

    function show_whole_info(_this){
        var _data_title = _this.attr('data-title');
        if (_data_title == undefined || _data_title == '') return;
        _this.data('data-title', _data_title).hover(function () {
            var offset = _this.offset();
            $("<div class='show-all-info'>" + _data_title + "</div>").appendTo($(".table")).css({
                top: offset.top + _this.outerHeight(),
                left: offset.left + _this.outerWidth() - 20
            }).fadeIn(function () {
            });
        }, function () {
            $(".show-all-info").remove();
        })
    }
    //全选
    var check_all = $(".table-footer .check-all input");
    var check_single = $(".table :checkbox");

    check_all.on("click",function(){
        if(this.checked){
            check_single.prop("checked",true);
        }else{
            check_single.prop("checked",false);
        }
    })
    //判断全选复选框的选中与否
    check_single.click(function(){
        allchk();
    });
    function allchk(){
        var chknum = check_single.size();//选项总个数
        var chk = 0; //已选中的个数
        check_single.each(function () {
            if($(this).prop("checked") == true){
                chk++;
            }
        });
        if(chknum == chk){ //全选时
            check_all.prop("checked",true);
        }else{  //不全选时
            check_all.prop("checked",false);
        }
    }

    //批量导出报表
    $(".batch-operate .export-report-forms").on("click",function(){
        $(".table input").each(function(){
            var _this = $(this),
                checkbox_checked = $("tbody").find("input:checked");
            if(checkbox_checked.length < 1){
                wom_alert.msg({
                    icon:"warning",
                    content:"请选择订单!",
                    delay_time:1500
                })
                return false;
            }
            if(_this.is(":checked")){
                wom_alert.msg({
                    icon:"finish",
                    content:"导出成功!",
                    delay_time:1500
                })
            }
        })
    })


})