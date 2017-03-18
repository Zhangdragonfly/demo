$(function(){
    //~~~~~~判断有无素材~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 10){
            $(".table-footer").css("display","none");
            if(resourceLength < 1){
                $(".no-lib").css("display","block");
            }else{
                $(".no-lib").css("display","none");
            }
        }else{
            $(".table-footer").css("display","block");
        }
        $(".material-total").children("span").text(resourceLength);
    }
    isResource();

    //删除资源
    $(".table").on("click",".remove",function(){
        var element_delete =  $(this).parents("tr");
        layer.confirm('您确定要删除该素材吗？', {
                btn: ['确定','取消']
            }, function(){
                layer.msg('删除成功 !', {
                    icon: 1,
                    time:1000
                });
                element_delete.remove();
                isResource();
            }
        )

    });

 //~~~~~~~~创建素材~~~~~~~~
    //~~~~字数统计~~~~
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
    $(".title-input").on("keyup",function(){
        fontNumberLimit($(this),$(".file-name em"),50);
    });
    //作者字数限制
    $(".author-input").on("keyup",function(){
        fontNumberLimit($(this),$(".author-name em"),8);
    });
    //摘要字数限制
    $("#abstract").on("keyup",function(){
        fontNumberLimit($(this),$(".summary em"),120);
    });

    // ~~~~~实例化编辑器~~~
    var ue = UE.getEditor('container',{
        //字数统计
        wordCount:true,
        maximumWords:10000
    });

    //判断必填项是否为空
    $(".save").on("click",function(){
        var title_val = $(".title-input").val();
        var editor_con = ue.getContentTxt();
        var cover_pic_list_length = $(".cover-pic-list").children("li").length;
        if(title_val == ""){
            layer.msg('请填写标题 !', {
                icon: 7,
                time:1000
            });
            return false;
        }
        if(cover_pic_list_length < 1){
            layer.msg('请上传封面图片 !', {
                icon: 7,
                time:1000
            });
            return false;
        }
        if(editor_con == ""){
            layer.msg('请填写正文内容 !', {
                icon: 7,
                time:1000
            });
            return false;
        }
        else{
            layer.msg('保存成功 !', {
                icon: 1,
                time:1000
            });
        }

    })

})