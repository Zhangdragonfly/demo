$(function(){
    //~~~~~~判断有无资源~~~~~~
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 10){
            $(".table-footer").css("display","none");
            if(resourceLength < 1){
                $(".no-order").css("display","block");
            }else{
                $(".no-order").css("display","none");
            }
        }else{
            $(".table-footer").css("display","block");
        }
    }
    isResource();

    //字数限制函数
    function fontNumberLimit(element,location,num){
        var fontNumber = element.val().length;
        if (fontNumber <= num) {
            location.text(num - fontNumber);
        }else{
            var now_con = element.val();
            var max_con = now_con.substr(0,num - 1);
            $(element).val(max_con);
        }
    }

    $(".refuse-reason-textarea").on("input",function(){
        fontNumberLimit($(this),$(".refuse-reason-section em"),30);
    });

    //直投订单详情图片的显示
    $(".btn-view-cover-pic").on("click",function(){
        $(".view-cover-pic").slideToggle(200);
    })
    //选择下拉单
    $('.dropdown .dropdown-menu').on('click','li',selectedOption);
    // 下拉单选择某一个
    function selectedOption(){
        var _text = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }


    // *** 上传图片/文件
    // ************************************************
    // ========= 上传按钮与上传结果展示区域的对应 =========
    var wom_uploader_setting = {
        'id-upload-001-btn': 'id-upload-001-preview-area',
        'id-upload-002-btn': 'id-upload-002-preview-area',
        'id-upload-003-btn': 'id-upload-003-preview-area'
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

    // 上传执行链接图片
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

    // 上传效果截图
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

    // 上传反馈图片
    wom_uploader.init('id-upload-003-btn',{
        file_added : function(files){
            uploader_file_added('id-upload-003-btn', files);
        },
        upload_progress: function(file) {
            uploader_upload_progress('id-upload-003-btn', file);
        },
        file_uploaded: function(file, resp){
            uploader_file_uploaded('id-upload-003-btn', file, resp);
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

// pjax刷新事件
function pjaxRef(){

    //表格二维码图片显示
    $('.ewm').hover(function(){
        $(this).siblings('img').css({display:'block'});
    },function(){
        $(this).siblings('img').css({display:'none'});
    })

    //鼠标放上去显示完整信息ID篇
    $("a[data-value]").each(function() {
        var a = $(this);
        var title = a.attr('data-value');
        if (title == undefined || title == "") return;
        a.data('data-value', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($("table")).css({ top: offset.top + a.outerHeight() + 6, left: offset.left + a.outerWidth() + 1 }).fadeIn(function () {
                });
            },
            function(){ $(".show-all-info").remove();
            }
        );
    });

    // 打开执行反馈弹框
    $('.content').on('click','.btn-execute-feedback',function(){
        var order_uuid = $(this).data("order-uuid");
        // 获取广告主的反馈
        var url = $('input#get-order-feedback-url').val();
        $.post(url, {order_uuid: order_uuid},function(data, status){
            if(status == 'success'){
                $('#modal-resubmit-execute-link .feedback-con textarea').html(data.order_track.content);
                $('#modal-resubmit-execute-link').modal('show');
            }else{
                wom_alert.msg({
                    icon:"warning",
                    content:"系统异常!",
                    delay_time:1000
                });
            }
        });

    });
    // 重新提交执行链接
    $('#modal-resubmit-execute-link').on('click','.execute-link-submit-btn',function(){
        var url = $('input#submit-execute-link-url').val();
        var order_uuid = $('.btn-execute-feedback').data('order-uuid');
        var execute_link = $('#modal-resubmit-execute-link .link-address-input').val();
        var screenshot_name = '';
        $(".upload-preview-area ul.file-list").children("li").find("a").each(function(){
            screenshot_name += $(this).data("img-name")+",";
        });

        if(execute_link == ''){
            $('#modal-resubmit-execute-link .tips').text('请填写执行链接');
            return false;
        }

        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid,
                execute_link: execute_link,
                screenshot_name:screenshot_name
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    $('#modal-resubmit-execute-link').modal('hide');
                    location.reload();
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });


    //详情
    $('.content').on('click','.btn-direct-order-detail',function(){
        var external_file_url = $("#id-external-file-url").val();//图片存储路径
        // 获取详情
        var order_uuid = $(this).parents("tr").data("uuid");
        var url = $('input#get-direct-order-detail-url').val();
        $.post(url,
            {
                order_uuid: order_uuid
            },function(data, status){
                if(status == 'success'){
                    var con_del_tag = data.detail.article_content.replace(/<\/?.+?>/g,"");
                    var plain_con = con_del_tag.replace(/&nbsp;/g,"");
                    $('#modal-direct-order-detail .order-name .active-name-show').text(data.detail.plan_name);
                    $('#modal-direct-order-detail .carry-out-time .execute-time').text(data.detail.execute_time);
                    $('#modal-direct-order-detail .article-to-lead a.fl').text(data.detail.original_mp_url);
                    $('#modal-direct-order-detail .title .title-name').text(data.detail.title);
                    $('#modal-direct-order-detail .author .author-name').text(data.detail.author);
                    $('#modal-direct-order-detail .text-content .requirements-con').text(plain_con);
                    $('#modal-direct-order-detail .org-text a.fl').text(data.detail.link_url);
                    $('#modal-direct-order-detail .abstract p.fl').text(data.detail.article_short_desc);
                    $('#modal-direct-order-detail .prove-quality li').text(data.detail.cert_img_urls);
                    $('#modal-direct-order-detail .curry-on-remarks p.fl').text(data.detail.comment);
                    $('#modal-direct-order-detail .view-cover-pic').attr("src",external_file_url+data.detail.cover_img);
                    $('#modal-direct-order-detail').modal('show');
                }else{
                    wom_alert.msg({
                        icon:"error",
                        content:"系统异常",
                        delay_time:2000
                    })
                }
            });
    });

    //接单
    $('.content').on('click', '.order-table .btn-accept-order', function(){
        var order_uuid = $(this).attr('data-order-uuid');
        $('#modal-accept-order').find('.order-uuid').val(order_uuid);
        $('#modal-accept-order').modal('show');
    });
    $('#modal-accept-order').on('click', '.btn-accept',function(){
        var order_uuid = $('#modal-accept-order').find('.order-uuid').val();
        var accept_order_url = $('input#accept-order-url').val();
        $.ajax({
            url: accept_order_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    window.location.reload();
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });


    //拒单确认
    $('.content').on('click', '.order-table .btn-refuse-order',function(){
        var order_uuid = $(this).attr('data-order-uuid');
        $('#modal-refuse-order').find('.order-uuid').val(order_uuid);
        $('#modal-refuse-order').modal('show');
    });
    // 拒单
    $('#modal-refuse-order').on('click', '.btn-refuse',function(){
        var order_uuid = $('#modal-refuse-order').find('.order-uuid').val();
        var refuse_content = $.trim($('#modal-refuse-order .refuse-reason-textarea').val());
        if(refuse_content == ''){
            wom_alert.msg({
                icon: "warning",
                content: "请填写拒单原因!",
                delay_time: 1500
            });
            return false;
        }
        var refuse_order_url = $('input#refuse-order-url').val();
        $.ajax({
            url: refuse_order_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid,
                content: refuse_content
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    wom_alert.msg({
                        icon: "finish",
                        content: "拒单成功!",
                        delay_time: 1500
                    });
                    $('#modal-refuse-order').modal('hide');
                    location.reload();
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });


    //提交执行链接
    $('.content').on('click', '.order-table .btn-submit-execute-link', function(){
        var order_uuid = $(this).attr('data-order-uuid');
        $('#modal-submit-execute-link').find('.order-uuid').val(order_uuid);
        $('#modal-submit-execute-link').modal('show');
    });
    $('#modal-submit-execute-link').on('click','.btn-submit-execute-link', function(){
        var submit_execute_link_url = $('input#submit-execute-link-url').val();
        var this_modal = $('#modal-submit-execute-link');
        var order_uuid = this_modal.find('.order-uuid').val();
        var execute_link = $.trim(this_modal.find('.input-execute-link').val());
        var screenshot_name = '';
        $(".upload-preview-area ul.file-list").children("li").find("a").each(function(){
            screenshot_name += $(this).data("img-name")+",";
        });
        if(execute_link == ''){
            wom_alert.msg({
                icon: "error",
                content: "请输入执行链接!",
                delay_time: 1500
            });
            return false;
        }
        $.ajax({
            url: submit_execute_link_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid,
                execute_link: execute_link,
                screenshot_name: screenshot_name
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    location.reload();
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });


    //提交效果截图
    $('.content').on('click', '.order-table .btn-submit-effect-shots',function(){
        $('#modal-submit-effect-shots').modal('show');
    });
    $('#modal-submit-effect-shots').on('click', '.btn-submit-effect-shots',function(){
        var order_uuid = $(".btn-submit-effect-shots").data('order-uuid');
        var submit_effect_shots_url = $('input#submit-effect-shots-url').val();
        var screenshot_name = '';
        $(".upload-preview-area ul.file-list").children("li").find("a").each(function(){
            screenshot_name += $(this).data("img-name")+",";
        });
        if(screenshot_name == ''){
            wom_alert.msg({
                icon: "error",
                content: "请上传效果截图!",
                delay_time: 1500
            });
            return false;
        }

        $.ajax({
            url: submit_effect_shots_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid,
                screenshot_name:screenshot_name
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    location.reload();
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });
    });

    //流单原因
    $('.content').on('click', '.order-table .btn-flow-order-reason',function(){
        var order_uuid = $(this).attr('data-order-uuid');
        var get_order_reason_url = $("input#get-order-reason-url").val();
        $.ajax({
            url: get_order_reason_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid,
                type: "flow",
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    $('#modal-invalid-order-info .reason-con .reason-area').text(resp.refuse_content);
                    $('#modal-invalid-order-info').modal('show');
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });

    });

    //拒单原因
    $('.content').on('click', '.order-table .btn-refuse-order-reason',function(){
        var order_uuid = $(this).attr('data-order-uuid');
        var get_order_reason_url = $("input#get-order-reason-url").val();
        $.ajax({
            url: get_order_reason_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: order_uuid,
                type: "refuse",
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    $('#modal-invalid-order-info .reason-con .reason-area').text(resp.refuse_content);
                    $('#modal-invalid-order-info').modal('show');
                } else {
                    wom_alert.msg({
                        icon: "error",
                        content: "系统出错!",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content: "系统出错!",
                    delay_time: 1500
                });
                return false;
            }
        });

    });



    //查看报告
    $('.content').on('click', '.order-table .btn-view-report',function(){
        var order_uuid = $(this).attr('data-order-uuid');
        var show_report_url = $('input#show-report-url').val().replace('_order_uuid_', order_uuid);
        window.open(show_report_url);
    });

}
