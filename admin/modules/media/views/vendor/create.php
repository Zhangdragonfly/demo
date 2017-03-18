<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:33 PM
 */
use admin\assets\AppAsset;
use common\helpers\MediaHelper;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\grid\GridView;

$vendorCreateUrl = Yii::$app->urlManager->createUrl(array('media/vendor/create'));
$vendorListUrl = Yii::$app->urlManager->createUrl(array('media/vendor/list'));

$weixinToCreateJs = <<<JS
    // 控制左侧导航选中
    if(!$('#media-vendor .media-vendor-create').hasClass('active')){
        $('.menu-level-1').each(function(){
             $(this).removeClass('active');
        });
        $('.menu-level-2').each(function(){
             $(this).removeClass('active');
        });

        $('#media-vendor.menu-level-1').addClass('active');
        $('#media-vendor.menu-level-1 .menu-level-2.media-vendor-create').addClass('active');
    }

    // 有效日期
    $(".form-vendor .active-end-time").datetimepicker({
                language: "zh-CN",
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                minView: 2,
                pickerPosition:'top-right'
    });
JS;

$this->registerJs($weixinToCreateJs);

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');

AppAsset::addScript($this, '@web/plugins/moment/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">媒体主管理</a></li>
        <li class="active">添加媒体主</li>
    </ol>

    <h1 class="page-header">添加媒体主</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form class="form-horizontal form-vendor">
                        <div class="form-group">
                            <label class="col-md-3 control-label">媒体主名称 *: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control name" placeholder="请输入公司名称或者媒体负责人名称(必填)" data-parsley-required="true"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">联系人 *: </label>
                            <div class="col-md-8">
                                <table class="table table-contact">
                                    <thead>
                                        <tr>
                                            <th>姓名</th>
                                            <th>电话</th>
                                            <th>微信</th>
                                            <th>QQ</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>

                                    <tbody class="add-contact-tbody">
                                        <tr>
                                            <td style="padding: 0px;"> <input name=contact type="text"></td>
                                            <td style="padding: 0px;"> <input name=phone type="text"></td>
                                            <td style="padding: 0px;"> <input name=weixin type="text"></td>
                                            <td style="padding: 0px;"> <input name=qq type="text"></td>
                                            <td style="padding: 0px 15px;"><span class="delete-add-span" style="color:red;cursor: pointer;">删除</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p>
                                    <a href="javascript:;" class="btn btn-primary btn-xs m-r-5 add-one-contact">添加联系人</a>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">报价有效期: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control active-end-time"
                                       placeholder="请填写报价有效期" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">备注: </label>
                            <div class="col-md-6">
                                <textarea class="form-control comment" placeholder="请输入备注信息" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">支付名称: </label>
                            <div class="col-md-3">
                                <input type="text" name="pay_user" class="form-control pay_user" placeholder="公司或者个人" value="">
                            </div>
                            <label class="col-md-2 control-label">开户行: </label>
                            <div class="col-md-3">
                                <input type="text" name="bank_name" class="form-control bank_name" placeholder="银行名字及分行" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">银行账号: </label>
                            <div class="col-md-3">
                                <input type="text" name="bank_account" class="form-control bank_account" placeholder="银行账号" value="">
                            </div>
                            <label class="col-md-2 control-label">支付类型: </label>
                            <div class="col-md-3">
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" value="1" />银行卡
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" value="2" />支付宝
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-9">
                                <button class="btn btn-success btn-commit" type="button">保&nbsp;&nbsp;&nbsp;存
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$weixinToCreateJs = <<<JS
    // 控制左侧导航选中
    if(!$('#media-vendor .media-vendor-create').hasClass('active')){
        $('.menu-level-1').each(function(){
             $(this).removeClass('active');
        });
        $('.menu-level-2').each(function(){
             $(this).removeClass('active');
        });
        $('#media-vendor.menu-level-1').addClass('active');
        $('#media-vendor.menu-level-1 .menu-level-2.media-vendor-create').addClass('active');
    }

    // 添加联系人
    $('.form-vendor .add-one-contact').on('click', function(){
       var contacts_num = $(".add-contact-tbody tr").length;
       if(contacts_num<3){
           var contacts_input = "<tr>" +
            "<td style='padding: 0px;'><input name=contact type='text'></td>" +
            "<td style='padding: 0px;'><input name=phone type='text'></td>" +
            "<td style='padding: 0px;'><input name=weixin type='text'></td>" +
            "<td style='padding: 0px;'><input name=qq type='text'></td>" +
            "<td style='padding: 0px 15px;'><span class='delete-add-span' style='color:red;cursor:pointer;'>删除</span></td>" +
            "</tr>";
          $(".add-contact-tbody").append(contacts_input);
       }else{
           swal('', '最多添加三个联系人!', 'error');
           return false;
       }
    });

    //删除添加的联系人
    $('body').on('click','.delete-add-span',function(){
        $(this).parents('tr').remove();
    });

     //保存账号
     var contact_arr =[];
     var contact_list = new Object();
     $('.form-vendor .btn-commit').on('click', function(){
        var vendor_form = $('.form-vendor');
        var vendor_name = $.trim(vendor_form.find('.name').val());
        var active_end_time = $.trim(vendor_form.find('.active-end-time').val());
        var comment = $.trim(vendor_form.find('.comment').val());
        var pay_user = $("input[name=pay_user]").val();
        var bank_name = $("input[name=bank_name]").val();
        var bank_account = $("input[name=bank_account]").val();
        var pay_type = $("input[name=pay_type]:checked").val();
        var contacts_num = $(".add-contact-tbody tr").length;
        contact_arr =[];
        for(i=0;i<contacts_num;i++){
            contact_list = new Object();
            contact_list["contact_person"] = $(".add-contact-tbody tr").eq(i).children('td').children("input[name=contact]").val();
            contact_list["contact_phone"] = $(".add-contact-tbody tr").eq(i).children('td').children("input[name=phone]").val();
            contact_list["weixin"] = $(".add-contact-tbody tr").eq(i).children('td').children("input[name=weixin]").val();
            contact_list["qq"] = $(".add-contact-tbody tr").eq(i).children('td').children("input[name=qq]").val();
            contact_list["add_time"] = Date.parse(new Date()).toString().substr(0,10);
            contact_arr.push(contact_list);

            if(contact_list["contact_person"]==""){
                swal('', '联系人不能为空!', 'error');
                return false;
            }
            if(contact_list["contact_phone"]=="" && contact_list["weixin"]=="" &&contact_list["qq"]==""){
                swal('', '联系电话/微信/QQ, 请至少填写一个!', 'error');
                return false;
            }
            //手机号码或者固话验证
            if(contact_list["contact_phone"]!=""){
                var cell_phone_reg= /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                var phone_reg= /^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/;
                if(!cell_phone_reg.test(contact_list["contact_phone"])){
                    if(!phone_reg.test(contact_list["contact_phone"])){
                        swal('', '手机号码格式不正确!', 'error');
                        return false;
                    }
                }
            }
            //qq号码格式验证
            var number_reg = /^[0-9]*$/;
            if(!number_reg.test(contact_list["qq"])){
                swal('', 'qq号码格式不正确!', 'error');
                return false;
            }

        }
        if(vendor_name == ''){
            swal('', '媒体主名称不能为空', 'error');
            return false;
        }
        if(contacts_num == 0){
            swal('', '请至少添加一个联系人', 'error');
            return false;
        }
        swal({
            title: '确认保存么？',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            closeOnConfirm: true
        },function (){
            $.ajax({
                url: '$vendorCreateUrl',
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    contact_array: JSON.stringify(contact_arr),
                    comment:comment,
                    vendor_name:vendor_name,
                    active_end_time: active_end_time,
                    pay_user: pay_user,
                    bank_name: bank_name,
                    bank_account: bank_account,
                    pay_type: pay_type,
                },
                success: function (resp) {
                    if(resp.err_code == 1){
                        swal({title: "新建失败！", text: "请联系系统管理员", type: "error"});
                        return false;
                    }else{
                        swal('', '添加媒体主成功', 'success');
                        window.location.href = '$vendorListUrl';
                    }
                },
                error: function (XMLHttpRequest, msg, errorThrown) {
                    swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                    return false;
                }
            });

        });

     });


    //检查媒体主是否存在
    $(".form-vendor .name").blur(function(){
        var vendor_name = $(this).val();
        if(vendor_name==""){
            return false;
        };
        $.ajax({
            url: '/index.php?r=media/vendor/check-vendor-name',
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {vendor_name: vendor_name},
            success: function (resp) {
                if(resp.err_code == 1){
                    swal({title: "该媒体主可能已经存在，请勿重复录入!", text: "", type: "error"});
                    return false;
                } else {
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                swal({title: "系统出错!", text: "请联系系统管理员", type: "error"});
                return false;
            }
        });
    });

JS;
$this->registerJs($weixinToCreateJs);
?>

