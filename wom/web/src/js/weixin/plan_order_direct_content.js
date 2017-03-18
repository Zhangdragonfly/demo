$(function () {
    // 手机页面展示
    $(".startime").on("blur", function () {
        $(".execute-time").text($(this).val());
    });
    $(".file-name .input-title").on("input", function () {
        $(".file-title").text($(this).val());
    });
    $(".author-name .input-author").on("input", function () {
        $(".author-clone").text($(this).val());
    });

    //字数限制函数
    function fontNumberLimit(element, location, num) {
        var fontNumber = element.val().length;
        if (fontNumber <= num) {
            location.text(num - fontNumber);
        }
    }
    //标题字数限制
    $(".input-title").on("input", function () {
        fontNumberLimit($(this), $(".file-name em"), 50);
    });
    //作者字数限制
    $(".input-author").on("input", function () {
        fontNumberLimit($(this), $(".author-name em"), 8);
    });
    //摘要字数限制
    $("#abstract").on("input", function () {
        fontNumberLimit($(this), $(".summary em"), 120);
    });
    //投放备注字数限制
    $(".input-comment").on("input", function () {
        fontNumberLimit($(this), $(".plan-comment em"), 120);
    });


    // ~~~~~~添加正文内容文本编辑器~~~~~~
    // 实例化编辑器
    var ue = UE.getEditor('container', {
        //字数统计
        wordCount: true,
        maximumWords: 10000
    });

    var domUtils = UE.dom.domUtils;
    //UEditor添加事件
    ue.addListener('ready', function () {
        domUtils.on(ue.body, "keyup blur", article_content_clone);
    });

    function article_content_clone() {
        //对编辑器的操作最好在编辑器ready之后再做
        ue.ready(function () {
            //获取html内容
            var ue_html = ue.getContent();
            $(".article-content-clone").html(ue_html);
        });
    }

    //从素材库选择
    //固定modal框
    //$("#select-from-material-lib").modal({backdrop: "static", keyboard: false});

    $(".material-list").children("tr").on("click", function () {
        if ($(this).hasClass('choosed')) {
            $(this).removeClass("choosed");
            $(this).find(".material-choosed").removeClass("show");
            return false;
        }
        $(this).addClass("choosed").siblings().removeClass("choosed");
        $(this).find(".material-choosed").addClass("show").parents("tr").siblings().find(".material-choosed").removeClass("show");
    });

    //填写需求的保存
    $(".save").on("click", function () {
        var order_uuid = $('#id-order-uuid').val();
        var pos_code = $('#id-pos-code').val();
        var publish_time = $.trim($('.weixin-file-content .input-publish-time').val()); // 发布时间
        var article_url = $.trim($('.weixin-file-content .input-article-url').val()); // 文章url
        var title = $.trim($('.weixin-file-content .input-title').val()); // 标题
        var author = $.trim($('.weixin-file-content .input-author').val()); // 作者
        var cover_pic_list_length = $("#id-upload-001-preview-area .file-list").children("li").length;//封面图片数量
        var link_url = $.trim($('.weixin-file-content .input-link-url').val()); // 原文链接
        var article_short_desc = $.trim($('.weixin-file-content .input-article-short-desc').val()); // 摘要
        var comment = $.trim($('.weixin-file-content .input-comment').val()); // 投放备注
        var editor_con = ue.getContent();//正文内容
        var cover_pic = $("#id-upload-001-preview-area .file-list").children("li").find("a").data("img-name");
        var multiple_pic = "";
        var order_pay = $(this).attr('data-pay');
        $(".quality-proof ul.file-list").children("li").find("a").each(function(){
            multiple_pic += $(this).data("img-name")+",";
        });

        //封面是否正文中间
        if($('.weixin-file-content .cover-pic-show').find("input[name=input-cover-in-body]").is(":checked")){
            var cover_in_body = 1;
        }else{
            var cover_in_body = 0;
        }

        if (publish_time == '') {
            wom_alert.msg({
                icon: "warning",
                content:"请选择执行时间",
                delay_time: 1500
            });
            return false;
        }
        if(title == ""){
            wom_alert.msg({
                icon: "warning",
                content:"请填写标题",
                delay_time: 1500
            });
            return false;
        }
        if(cover_pic_list_length < 1){
            wom_alert.msg({
                icon: "warning",
                content:"请上传封面图片",
                delay_time: 1500
            });
            return false;
        }
        if(editor_con == ""){
            wom_alert.msg({
                icon: "warning",
                content:"请填写正文内容",
                delay_time: 1500
            });
            return false;
        }

        var edit_direct_content_url = $('#id-edit-direct-content-url').val();
        var weixin_plan_confirm_url = $('#id-weixin-plan-confirm-url').val();
        var weixin_plan_update_url = $('#id-weixin-plan-update-url').val();
        var weixin_plan_pay_url = $('#id-weixin-plan-pay-url').val().replace('_order_uuid_',order_uuid);
        var weixin_plan_action_type = $('#id-weixin-plan-action-type').val();

        $.ajax({
            url: edit_direct_content_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid,
                pos_code: pos_code,
                publish_time: publish_time,
                article_url: article_url,
                title: title,
                author: author,
                link_url: link_url,
                article_short_desc: article_short_desc,
                comment: comment,
                cover_in_body:cover_in_body,
                editor_con:editor_con,
                cover_pic:cover_pic,
                multiple_pic:multiple_pic,
                order_pay:order_pay,
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    if(weixin_plan_action_type == 'create'){
                        window.location.href = weixin_plan_confirm_url;
                    } else if(weixin_plan_action_type == 'update'){
                        window.location.href = weixin_plan_update_url;
                    } else if(weixin_plan_action_type == 'pay'){
                        window.location.href = weixin_plan_pay_url;
                    }
                } else {
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

        //删除文件夹封面图片
        deleteUploadImg(need_delete_single_img);

    });
    // 导入文章链接
    $(".import").on("click", function () {
        var article_url = $.trim($('.weixin-file-content .input-article-url').val()); // 文章url
        var import_url = $('#id-import-content-url').val();
        if (article_url == '') {
            wom_alert.msg({
                icon: "warning",
                content:"请输入文章URL",
                delay_time: 1500
            });
            return false;
        }
        $.ajax({
           url: import_url,
           type: 'POST',
           cache: false,
           dataType: 'json',
           data: {
               article_url: article_url,
           },
           success: function (resp) {
               if (resp.err_code == 0) {
                   $('.weixin-file-content .input-title').val(resp.title);
                   $('.weixin-file-content .input-author').val(resp.author);
                   ue.setContent(resp.innerHtml);
                   $(".file-title").text(resp.title);
                   $(".author-clone").text(resp.author);
                   $(".article-content-clone").html( ue.getContent());
                   return false;
               } else {
                   wom_alert.msg({
                       icon: "error",
                       content:"无效链接",
                       delay_time: 1500
                   });
                   return false;
               }
           },
           error: function (XMLHttpRequest, msg, errorThrown) {
               wom_alert.msg({
                   icon: "error",
                   content:"无效链接",
                   delay_time: 1500
               });
               return false;
           }
        });
    });

    // ************************************************
    // ***  wom uploader
    // *** 上传图片/文件
    // ************************************************
    // ========= 上传按钮与上传结果展示区域的对应 =========
    var wom_uploader_setting = {
        'id-upload-001-btn': 'id-upload-001-preview-area',
        'id-upload-002-btn': 'id-upload-002-preview-area'
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

    // 封面图片
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

    // 正品证明
    wom_uploader.init('id-upload-002-btn',{
        file_added : function(files){
            uploader_file_added('id-upload-002-btn', files);
        },
        upload_progress: function(file) {
            uploader_upload_progress('id-upload-002-btn', file);
        },
        file_uploaded: function(file, resp){
            uploader_file_uploaded('id-upload-002-btn', file, resp);
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

    //页面初始化时手机获取所填内容
    function initPhoneCon() {
        $(".execute-time").text($(".startime").val());
        $(".file-title").text($(".file-name .input-title").val());
        $(".author-clone").text($(".author-name .input-author").val());
        article_content_clone();
    }
    initPhoneCon();

    //~~~~~侧边栏联系方式~~~~~
    $(".side-bar li").hover(function(){
        $(this).css("background","#c81624");
        $(this).children("div").stop().animate({
            left:'-155px'
        },500);
    },function(){
        $(this).css("background","#1e1e1e");
        $(this).children("div").stop().animate({
            left:'46px'
        },500);
    })
    $(".side-bar .top").on("click",function(){
        $('html,body').stop().animate({
            scrollTop:'0'
        },500)
    })

});
