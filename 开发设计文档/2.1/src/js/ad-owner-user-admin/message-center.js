$(function(){
    //~~~~~~判断有无消息~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 3){
            $(".table-footer").hide();
            if(resourceLength < 1){
                $(".no-msg").show();
                $(".batch-operate").hide();
            }else{
                $(".no-msg").hide();
                $(".batch-operate").show();
            }
        }else{
            $(".table-footer").show();
        }
    }
    isResource();

    //删除消息
    $(".table").on("click",".delete-msg",function(){
        var element_delete =  $(this).parents("tr");
        wom_alert.confirm({
            content:"确定删除该消息吗？",
        }, function(){
            wom_alert.msg({
                icon:"finish",
                content:"删除成功!",
                delay_time:1500
            })
            element_delete.remove();
            isResource();
        });
    });

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

    //批量标记为已读
    $(".batch-operate .has-read-remark").on("click",function(){
        $(".table input").each(function(){
            var _this = $(this),
                checkbox_checked = $("tbody").find("input:checked");
            if(checkbox_checked.length < 1){
                wom_alert.msg({
                    icon:"warning",
                    content:"请选择消息!",
                    delay_time:1500
                })
                return false;
            }
            if(_this.is(":checked")){
               _this.parents("tr").children(".msg-status").text("已读");
            }
        })
    })
    //批量删除
    $(".batch-operate .delete").on("click",function(){
        $(".table input").each(function(){
            var _this = $(this),
                element_delete = _this.parents("tr"),
                checkbox_checked = $("tbody").find("input:checked");
            if(checkbox_checked.length < 1){
                wom_alert.msg({
                    icon:"warning",
                    content:"请选择消息!",
                    delay_time:1500
                })
                return false;
            }
            if(_this.is(":checked")){
                element_delete.remove();
                isResource();
            }
        })
    })


})