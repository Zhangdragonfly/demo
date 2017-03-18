<?php
/**
 * 创建原创约稿内容
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/28/16/ 20:41
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);

AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/weixin/plan_order_arrange_content.css');
AppAsset::addCss($this, '@web/dep/datetimepicker/jquery.datetimepicker.css');
AppAsset::addCss($this, '@web/src/css/plan_media_common.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
AppAsset::addScript($this, '@web/dep/plupload/plupload.full.min.js');
AppAsset::addScript($this, '@web/dep/js/wom-uploader.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');

$this->title = '填写原创需求';
$js = <<<JS
//输入剩余字数判断
$('.content-sum textarea').keyup(function(){
    countNum(this,4000);
});
function countNum(_this,num){
    var _value_len = $(_this).val().length;
    var _count = Number(_value_len);
    var _all_num_last = Number(num-_count);

    $(_this).next().find('em').text(_all_num_last);
}

//点击保存判断
$('.save-data').click(function(){
    var _order_uuid = $('#id-order-uuid').val();
    var order_submit = $(this).attr('data-submit');
    var _pos_code = $('#id-pos-code').val();
    var _start_time = $.trim($('.plan-time input:eq(0)').val());
    var _end_time = $.trim($('.plan-time input:eq(1)').val());
    var _desc = $.trim($('.content-sum .desc').val());
    var _feedback_time = $.trim($('.cut-time .feedback-datetime').val());

    if(_start_time == ''|| _end_time == ''){
        layer.msg('请填写执行时间!',{
            icon:0,
            time: 1500
        });
        return false;
    }

    if(_desc == ''){
        layer.msg('请填写需求概述!',{
            icon:0,
            time: 1500
        });
        return false;
    }

    var edit_arrange_content_url = $('#id-edit-arrange-content-url').val();
    var weixin_plan_confirm_url = $('#id-weixin-plan-confirm-url').val();
    var weixin_order_list_url = $('#id-weixin_order_list_url').val();
    $.ajax({
            url: edit_arrange_content_url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                order_uuid: _order_uuid,
                order_submit: order_submit,
                pos_code: _pos_code,
                publish_start_time: _start_time,
                publish_end_time: _end_time,
                requirement: _desc,
                feedback_datetime: _feedback_time
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    if(order_submit == 1){
                        window.location.href = weixin_order_list_url;
                    }else{
                        window.location.href = weixin_plan_confirm_url;
                    }

                } else {
                    layer.msg('系统异常', {
                        icon: 0,
                        time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                layer.msg('系统异常', {
                    icon: 0,
                    time: 1500
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
        'id-upload-001-btn': 'id-upload-001-preview-area'
    };

    // =========  上传中自定义函数 =========
    function uploader_file_added(uploader_btn_id, files){
        var display_area = $('#' + wom_uploader_setting[uploader_btn_id] + '');
        var display_content = '';
        for(var i = 0, len = files.length; i < len; i++){
            var file_name = files[i].name;
            var file_id = files[i].id;
            var img_item = '<div class="progress"><span class="bar"></span><span class="percent">上传中 0%</span></div><p>' +file_name+ '<i class="delete-pic"></i></p>';
            display_content += '<li id="' + file_id +'">' + img_item + '</li>';
        }
        var file_list = display_area.find('.file-list');
        if(file_list.length == 0){
            display_content = '<ul class="file-list clearfix">' + display_content + '</ul>';
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

    // 附件
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
            max_file_size: '20mb', //限制上传图片的大小,
            file_ext_accept: '*',
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
JS;
$this->registerJs($js);
?>

<!-- 内容部分-->
<input id="id-edit-arrange-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/edit-arrange-content']) ?>">
<input id="id-weixin-plan-confirm-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/confirm', 'plan_uuid' => $weixinPlan->uuid]) ?>">
<input id="id-weixin_order_list_url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-order/list']) ?>">
<input id="id-order-uuid" type="hidden" value="<?= $arrangeContent->order_uuid ?>">
<input id="id-pos-code" type="hidden" value="<?= $arrangeContent->position_code ?>">

<!-- 删除图片 -->
<input id="id-delete-file-url" type="hidden"
       value="<?= Url::to(['/site/file-uploader/delete-file', 'cate_code' => 'order']) ?>">
<!-- csrf -->
<input type="hidden" id="csrf" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken(); ?>"/>
<!-- 上传图片 -->
<input type="hidden" id="id-upload-file-url"
       value="<?= Url::to(['/site/file-uploader/upload', 'cate_code' => 'order']) ?>">

<div class="content">
    <div class="in-content">
        <div class="active-name">
            <div class="title-wrap"><span class="title">活动名称：</span></div>
            <span><?= $weixinPlan->name; ?></span>
        </div>
        <div class="plan-time">
            <div class="title-wrap"><span class="title"><i></i>预计执行时间：</span></div>
            <input type="text" class="form-control datetimepicker" value="<?= $arrangeContent->publish_start_time ?>" placeholder="请选择开始时间"/>
            <em></em><em></em>
            <span class="line"></span>
            <input type="text" class="form-control datetimepicker" value="<?= $arrangeContent->publish_end_time ?>" placeholder="请选择结束时间"/>
            <span class="plan-time-alert">选择合理的预计投放时间，便于媒体主预留时间</span>
        </div>
        <div class="content-sum">
            <div class="title-wrap"><span class="title"><i></i>需求概述：</span></div>
            <textarea name="" class="form-control desc" placeholder="让媒体主了解您需要干什么，越具体越好。请勿超过4000字" cols="" rows="" maxlength="4000"><?= $arrangeContent->requirement ?></textarea>
            <span class="count-num">您还可以输入<em>4000</em>字</span>
        </div>
        <div class="attach-file">
            <div class="title-wrap"><span class="title">附件：</span></div>
            <button id="id-upload-001-btn" class="btn btn-danger" for="id-upload-001-preview-area">上传</button>
            <p>您可以通过这里上传需求及其他信息，单文件不能大于20M，最多可上传10个。</p>
            <div id="id-upload-001-preview-area" class="upload-preview-area">

            </div>
        </div>
        <div class="cut-time">
            <div class="title-wrap"><span class="title">预计反馈时间：</span></div>
            <input type="text" class="form-control datetimepicker feedback-datetime" value="<?= $arrangeContent->feedback_datetime ?>" placeholder="请选择开始时间"/>
            <span>请选择预约结果反馈截止时间</span>
        </div>

        <?php if(empty(Yii::$app->request->get('submit'))){ ?>
            <button class="btn btn-danger save-data" data-submit="0">保存</button>
        <?php }else{ ?>
            <button class="btn btn-danger save-data" data-submit="1">提交</button>
        <?php } ?>
    </div>
</div>
