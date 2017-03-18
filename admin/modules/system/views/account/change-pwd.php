<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 9/18/16 11:03 AM
 */
use admin\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::addCss($this, "@web/plugins/sweetalert/css/sweetalert.css");
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
$changePwd = Url::to(['/system/account/change-pwd']);
$changeSuccess = Url::to(['/system/account/change-success']);
?>
<div id="content" class="content">

     <ol class="breadcrumb pull-right">
         <li><a href="javascript:;">系统管理</a></li>
         <li class="active">重置密码</li>
     </ol>

     <h1 class="page-header">重置密码</h1>

     <div class="tab-content">
         <div class="tab-pane fade active in">
             <div class="panel panel-inverse">
                 <div class="panel-body">
                   <div class="form-horizontal form-bordered" data-parsley-validate="true" >
                         <div class="form-group">
                             <label class="col-md-3 control-label">重置密码*: </label>
                             <div class="col-md-5">
                                <input class="form-control" type="password" id="new_pwd" name="new_pwd" data-parsley-type="new_pwd" placeholder="请输入新密码" data-parsley-required="true"  maxlength="12"/>
                                <div class="error_report new_pwd_error" style="color:red;display:none;">请输入新密码</div>
                             </div>
                         </div>
                         <div class="form-group">
                             <label class="col-md-3 control-label">确认密码*: </label>
                             <div class="col-md-5">
                                <input class="form-control" type="password" id="comfirm_pwd" name="comfirm_pwd" data-parsley-type="comfirm_pwd" placeholder="确认输入新密码"  maxlength="12"/>
                                <div class="error_report comfirm_pwd_error" style="color:red;display:none;">确认输入新密码</div>
                             </div>
                         </div>

                         <div class="form-group">
                             <div class="col-lg-offset-3 col-lg-9">
                               <div class="col-md-6 col-sm-6">
                                 <button type="submit" class="btn btn-success btn-commit" disabled="disabled">确定</button>
                               </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
           </div>
        </div>

<!-- JS -->
<?php
$Js = <<<JS
      //密码输入验证
      $(".form-control").blur(function(){
          var id = $(this).attr('id');
          if( $(this).val() ==''){
              $("."+id+"_error").css("display","block");
          }else{
              $("."+id+"_error").css("display","none");
          }
          $(".form-control").each(function(){
              if($(this).val() ==''){
                  $(".col-md-6").find("button").attr("disabled","disabled");
                  return false;
              }else{
                  $(".col-md-6").find("button").removeAttr("disabled");
              }
          });
      });
      //确认更新密码
      $("input[name=comfirm_pwd]").blur(function(){
          var id = $(this).attr('id');
          var new_pwd = $("input[name=new_pwd]").val();
          var comfirm_pwd = $("input[name=comfirm_pwd]").val();
          if(new_pwd == comfirm_pwd){
              $("."+id+"_error").css("display","none");
              $(".col-md-6").find("button").removeAttr("disabled");
          }else{
              $("."+id+"_error").css("display","block");
              $("."+id+"_error").html("两次输入密码不一致！");
              $(".col-md-6").find("button").attr("disabled","disabled");
          }
      });

        //提交
      $(".btn-commit").click(function(){
            var new_pwd = $("input[name=new_pwd]").val();
        $.ajax({
            url: '$changePwd',
            method: 'POST',
            cache: false,
            dataType: 'json',
            data:{new_pwd:new_pwd},
            success: function (resp) {
                if(resp.err_code == 0){
                    window.location.href = '$changeSuccess';
                }else{
                    swal({title: "修改失败！", text: "", type: "error"});
                    return false;
                }
            },
            error: function (XMLHttpRequest,msg,errorThrown) {
                swal({title: "", text: "系统出错！", type: "error"});
            }
        });
      });



JS;
$this->registerJs($Js);
 ?>
