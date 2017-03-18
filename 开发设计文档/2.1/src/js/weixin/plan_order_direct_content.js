$(function(){
    // 手机页面展示
    $(".startime").on("blur",function(){
        $(".execute-time").text($(this).val());
    })
    $(".file-name .title-input").on("input",function(){
        $(".file-title").text($(this).val());
    })
    $(".author-name .author-input").on("input",function(){
        $(".author-clone").text($(this).val());
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
    $(".title-input").on("input",function(){
        fontNumberLimit($(this),$(".file-name em"),50);
    });
    //作者字数限制
    $(".author-input").on("input",function(){
        fontNumberLimit($(this),$(".author-name em"),8);
    });
    //摘要字数限制
    $("#abstract").on("input",function(){
        fontNumberLimit($(this),$(".summary em"),120);
    });
    //投放备注字数限制
    $(".remark-textarea").on("input",function(){
        fontNumberLimit($(this),$(".plan-remark em"),120);
    });


    // ~~~~~~添加正文内容文本编辑器~~~~~~
    // 实例化编辑器
    var ue = UE.getEditor('container',{
        //字数统计
        wordCount:true,
        maximumWords:10000
    });

    var domUtils = UE.dom.domUtils;
    //UEditor添加事件
    ue.addListener('ready',function(){
        domUtils.on(ue.body,"keyup blur",article_content_clone);
    });

    function article_content_clone(){
        //对编辑器的操作最好在编辑器ready之后再做
        ue.ready(function() {
            //获取html内容
            var ue_html = ue.getContent();
            $(".article-content-clone").html(ue_html);
        });
    }

    //从素材库选择
    //固定modal框
    //$("#select-from-material-lib").modal({backdrop: "static", keyboard: false});

    $(".material-list").children("tr").on("click",function(){
        if($(this).hasClass('choosed')){
            $(this).removeClass("choosed");
            $(this).find(".material-choosed").removeClass("show");
            return false;
        }
        $(this).addClass("choosed").siblings().removeClass("choosed");
        $(this).find(".material-choosed").addClass("show").parents("tr").siblings().find(".material-choosed").removeClass("show");
    })

    //判断必填项是否为空
    $(".save").on("click",function(){
        var execute_time_val = $(".startime").val(),
            title_val = $(".title-input").val(),
            editor_con = ue.getContentTxt(),
            cover_pic_list_length = $("#upload-single-img-file-list").children("li").length;
        if(execute_time_val == ""){
            layer.msg('请选择执行时间!', {
                icon: 7,
                time:1000
            });
            return false;
        }
        if(title_val == ""){
            layer.msg('请填写标题!', {
                icon: 7,
                time:1000
            });
            return false;
        }
        if(cover_pic_list_length < 1){
            layer.msg('请上传封面图片!', {
                icon: 7,
                time:1000
            });
            return false;
        }
        if(editor_con == ""){
            layer.msg('请填写正文内容!', {
                icon: 7,
                time:1000
            });
            return false;
        }
        else{
            layer.msg('保存成功!', {
                icon: 1,
                time:1000
            });
        }

    })
})
