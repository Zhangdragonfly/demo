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
        var delete_url = $(this).data("url");
        var material_uuid  = $(this).data("uuid");
        var element_delete =  $(this).parents("tr");
        layer.confirm("确定删除吗？", {
            btn: ['确定','取消'],
        }, function () {
            $.ajax({
                url: delete_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    material_uuid: material_uuid,
                },
                success: function (resp) {
                    if(resp.err_code == 0){
                        window.location.reload();
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

    //新建素材的保存
    $(".btn-save-material").click(function(){
        var material_uuid = $("#id-material-uuid").val();
        var material_lib_list = $("#id-material-lib-list").val();
        var save_material_url = $(this).data("url");
        var title = $("input[name=title]").val();
        var author = $("input[name=author]").val();
        var orig_url = $("input[name=url]").val();
        var desc = $("textarea[name=desc]").val();
        var content = ue.getContent();
        var cover_pic_list_length = $(".cover-pic-list").children("li").length;
        if(title == ""){
            layer.msg('请填写标题 !', {
                icon: 7,
                time:1000
            });
            return false;
        }
        // if(cover_pic_list_length < 1){
        //     layer.msg('请上传封面图片 !', {
        //         icon: 7,
        //         time:1000
        //     });
        //     return false;
        // }
        if(content == ""){
            layer.msg('请填写正文内容 !', {
                icon: 7,
                time:1000
            });
            return false;
        }
        wom_alert.confirm({
            content: '确认保存吗?'
        }, function () {
            $.ajax({
                url: save_material_url,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    material_uuid: material_uuid,
                    title: title,
                    author: author,
                    orig_url: orig_url,
                    desc: desc,
                    content: content,
                },
                beforeSend: function () {
                    //让提交按钮失效，以实现防止按钮重复点击
                    $(".btn-save-material").attr('disabled',"disabled");
                },
                complete: function () {
                    //按钮重新有效
                    $(".btn-save-material").removeAttr('disabled');
                },
                success: function (resp) {
                    if(resp.err_code == 0){
                        window.location.href = material_lib_list;
                        return false;
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

        });

    });

    // ************************************************
    // ***  wom uploader
    // *** 上传图片/文件
    // ************************************************
    // ========= 上传按钮与上传结果展示区域的对应 =========
    var wom_uploader_setting = {
        'id-upload-001-btn': 'id-upload-001-preview-area',
    };

    // =========  上传中自定义函数 =========
    function uploader_file_added(uploader_btn_id, files){
        var display_area = $('#' + wom_uploader_setting[uploader_btn_id] + '');
        var display_content = '';
        for(var i = 0, len = files.length; i < len; i++){
            var file_name = files[i].name;
            var file_id = files[i].id;
            // 上传的图片
            var img_item = '<div class="progress"><span class="bar"></span><span class="percent"></span></div><a data-img-name="" class="delete-pic" href="javascript:;"><i></i></a>';
            display_content += '<li id="' + file_id +'">' + img_item + '</li>';

            !function(i){
                previewImage(files[i], function(img_url){
                    $('#' + files[i].id).append('<img src="'+ img_url +'" />');
                })
            }(i);
        }
        var file_list = display_area.find('.file-list');
        if(file_list.length == 0){
            display_content = '<ul class="file-list">' + display_content + '</ul>';
            display_area.append(display_content);
        } else {
            display_area.find('.file-list').append(display_content);
        }
    }
    function uploader_upload_progress(uploader_btn_id, file){
        var file_id = file.id;
        var percent = file.percent;
        $('#' + file_id).find('.bar').css({'width': percent + '%'});
        $('#' + file_id).find('.percent').text('已上传' + percent + '%');
    }
    function uploader_file_uploaded(uploader_btn_id, file, resp){
        // 设置删除图片按钮的属性(data-img-name)
        var file_id = file.id;
        var display_area = $('#' + wom_uploader_setting[uploader_btn_id] + '');
        var resp = $.parseJSON(resp.response);
        if(resp.err_code == 0){
            var file_name = resp.file_name;
            $('#' + file_id).find(".delete-pic").attr("data-img-name", file_name);
            display_area.find('.progress').hide();
        } else {
            // 出错
        }
    }

    // 上传图片
    wom_uploader.init('id-upload-001-btn',{
        file_added : function(files){
            uploader_file_added('id-upload-001-btn', files);
        },
        upload_progress: function(file) {
            uploader_upload_progress('id-upload-001-btn', file);
        },
        file_uploaded: function(file, resp){
            uploader_file_uploaded('id-upload-001-btn', file, resp);
        },
        max_file_size: '2mb', //限制上传图片的大小,
        file_ext_accept: 'jpeg,jpg,gif,png',
        upload_url: $('input#id-upload-file-url').val(),
        csrf: $('input#csrf').val()
    });

    // 删除图片
    $(".upload-preview-area").on('click', '.file-list .delete-pic', function(){
        var this_img = $(this).closest('li');
        var img_name = $(this).data('img-name');
        var delete_file_url = $("input#id-delete-file-url").val();
        if(img_name == ''){
            return false;
        }
        $.ajax({
            url: delete_file_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {
                img_name: img_name
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    this_img.remove();
                } else if(resp.err_code == 1){
                    wom_alert.msg({
                        icon: "error",
                        content:"系统异常",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content:"系统异常",
                    delay_time: 1500
                });
                return false;
            }
        });
    });
})