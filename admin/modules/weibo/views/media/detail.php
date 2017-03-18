<?php
use admin\assets\AppAsset;
use common\helpers\MediaHelper;
use common\models\WomAdminAccount;

AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/moment.min.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker.js');
AppAsset::addScript($this, '@web/plugins/bootstrap-daterangepicker/lang/zh-cn.js');
AppAsset::addCss($this, '@web/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/js/weibo/media-list.js');
?>
<div id="content" class="content">
    <h1 class="page-header">审核资源/媒体主</h1>
    <input type="hidden" name="media_uuid" value="<?= Yii::$app->request->get('uuid')?>">
    <input type="hidden" name="delete_vendor" value="<?= Yii::$app->urlManager->createUrl(array('weibo/media/remove-vendor'))?>">
    <input type="hidden" name="get_vendor_info" value="<?= Yii::$app->urlManager->createUrl(array('weibo/media/get-vendor-info'))?>">
    <input type="hidden" name="get_vendor_active" value="<?= Yii::$app->urlManager->createUrl(array('weibo/media/get-vendor-active-time'))?>">
<div class="row">
    <!-- begin col-6 -->
    <div class="col-md-22">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#default-tab-1" data-toggle="tab" class="audit-media">审核资源</a></li>
            <li class=""><a href="#default-tab-2" data-toggle="tab" class="audit-vendor" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/get-list-of-vendor'))?>">审核自媒体主</a></li>
            <li class=""><a href="#default-tab-3" data-toggle="tab" class="add-vendor" data-url="<?=Yii::$app->urlManager->createUrl(array('media/vendor/search'))?>">添加媒体主</a></li>
        </ul>
        <div class="tab-content">
            <!--审核资源-->
            <div class="tab-pane fade active in" id="default-tab-1">
                <!-- begin col-6 -->
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                        <div class="panel-body">
                            <!--基础信息-->
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">*账号名称
                                        <br>
                                        <small class="text-danger">(必填)</small>
                                    </label>
                                    <div class="col-md-3">
                                        <input name="weibo_name" type="text" class="form-control" value="<?=!empty($data['weibo_name'])?$data['weibo_name']:"";?>" placeholder="账号名称" />
                                    </div>

                                    <label class="col-md-1 control-label">*粉丝数
                                        <br>
                                        <small class="text-danger">(必填)</small>
                                    </label>
                                    <div class="col-md-2">
                                        <input name="follower_num" type="text" class="form-control"  value="<?=!empty($data['follower_num'])? $data['follower_num']:"";?>"placeholder="粉丝数" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">*链接
                                        <br>
                                        <small class="text-danger">(必填)</small>
                                    </label>
                                    <div class="col-md-3">
                                        <input name="weibo_url" type="text" class="form-control"  value="<?=!empty($data['weibo_url'])?$data['weibo_url']:"";?>" placeholder="链接" />
                                    </div>

                                    <label class="col-md-1 control-label">认证</label>
                                    <div class="col-md-2">
                                        <select name="media_level" class="form-control">
                                            <option  value="-1" >不限</option>
                                            <option  value="1" <?=($data['media_level']==1)?"selected":""; ?>>蓝V</option>
                                            <option  value="2" <?=($data['media_level']==2)?"selected":""; ?>>黄V</option>
                                            <option  value="3" <?=($data['media_level']==3)?"selected":""; ?>>草根</option>
                                            <option  value="4" <?=($data['media_level']==4)?"selected":""; ?>>达人</option>
                                        </select>
                                    </div>

                                    <label class="col-md-1 control-label">入驻时间</label>
                                    <div class="col-md-2">
                                        <input name="enter_time" type="text" class="form-control"  value="<?=!empty($data['enter_time'])? date('Y-m-d',$data['enter_time']):"";?>"placeholder="" disabled/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">简介</label>
                                    <div class="col-md-3">
                                        <textarea  name="intro" class="form-control"  rows="5"><?=!empty($data['intro'])?$data['intro']:"";?></textarea>
                                    </div>

                                    <label class="col-md-1 control-label">接单备注</label>
                                    <div class="col-md-5">
                                        <textarea name="accept_remark" class="form-control comment" rows="5"><?=!empty($data['accept_remark'])?$data['accept_remark']:"";?></textarea>
                                    </div>
                                </div>
                                <!-- 属性信息-->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">资源分类 *:
                                        <br>
                                        <small class="text-danger">(多选)</small>
                                    </label>
                                    <div class="col-md-8 media-cate">
                                        <?php
                                        $cate_array =array_filter(explode('#',$data['media_cate']));//选中资源
                                        $mediaCateList = MediaHelper::getMediaCateList();//全部资源
                                        foreach ($mediaCateList as $code => $cate) { ?>
                                            <label class="checkbox-inline ">
                                                <input type="checkbox" name="media_cate" value="<?php echo $code; ?>" class="<?php echo 'one-cate cate-' . $code; ?>"  <?php foreach($cate_array as $k=>$v){if($v==$code)echo "checked";} ?>><?php echo $cate; ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">账号地域属性 *:
                                        <br>
                                        <small class="text-danger">(必填)</small>
                                    </label>
                                    <div class="col-md-8 follower-area">
                                        <?php
                                        $area_array =array_filter(explode('#',$data['follower_area']));//选中资源
                                        $mediaCityList = MediaHelper::getCityList();
                                        foreach ($mediaCityList as $code => $cate) { ?>
                                            <label class="checkbox-inline ">
                                                <input type="checkbox" name="follower_area" value="<?php echo $code; ?>" class="<?php echo 'one-area area-'.$code; ?>" <?php foreach($area_array as $k=>$v){if($v==$code)echo "checked";} ?> ><?php echo $cate; ?>
                                            </label>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">1.0接单备注</label>
                                    <div class="col-md-5">
                                        <textarea name="accept_remark" class="form-control comment" rows="5" disabled><?=!empty($data['accept_remark_one'])?$data['accept_remark_one']:"";?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">审核状态</label>
                                    <div class="col-md-2">
                                        <select name="audit_status"class="form-control">
                                            <option value="-1"<?=($data['status']==-1)?"selected":""; ?>>不限</option>
                                            <option value="0"<?=($data['status']==0)?"selected":""; ?>>待审核</option>
                                            <option value="1"<?=($data['status']==1)?"selected":""; ?>>已审核</option>
                                            <option value="2"<?=($data['status']==2)?"selected":""; ?>>未通过</option>
                                        </select>
                                    </div>

                                    <label class="col-md-3 control-label">审核时间</label>
                                    <div class="col-md-2">
                                        <input name="audit_time" type="text" class="form-control"  value="<?=!empty($data['audit_time'])?date('Y-m-d', $data['audit_time']):"";?>"placeholder="" disabled/>
                                    </div>
                                </div>

                            </form>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-success submit btn-update-base" data-uuid="<?=!empty($data['uuid'])?$data['uuid']:"";?>" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/update-base'))?>">保存</button>
                                        <button type="submit" class="btn btn-primary btn-cancel" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/list'))?>">返回</button>
                                    </div>
                                </div>
                        </div>
                    </div>
            </div>

            <!--审核自媒体主-->
            <div class="tab-pane fade" id="default-tab-2">
                <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                    <div class="panel-body">
                        <legend class="vendor-list" style="color: #00acac;">媒体主列表
                            <div style="float: right;">
                                <button class="btn btn-warning btn-sm btn-to-add-vendor" type="button">添加媒体主</button>
                            </div>
                        </legend>
                        <table class="table table-vendor">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>媒体主名称</th>
                                    <th>自媒体来源</th>
                                    <th>联系人</th>
                                    <th>联系方式</th>
                                    <th>首选媒体主</th>
                                    <th>资源状态</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--媒体主列表-->
                            </tbody>
                        </table>
                    </div>

                    <!--自媒体主信息-->
                    <div class="panel-body panel-body-vendor-info" style="display: none;">
                        <legend class="vendor-name" style="color: #00acac;">自媒体主:<span style="color: #348fe2;"></span></legend>
                        <form class="form-horizontal">
                            <table class="table table-bordered table-price-set">
                                <thead>
                                <tr>
                                    <th style="min-width: 150px">位置</th>
                                    <th style="min-width: 150px">平台合作价(元)</th>
                                    <th style="min-width: 150px">零售价(元)</th>
                                    <th style="min-width: 150px">执行价(元)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="one-pos pos-s">
                                    <td>软广直发</td>
                                    <td class="orig-price">
                                        <div class="input-group">
                                            <input name="soft_d_orig" type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                                    <td class="retail-price">
                                        <div class="input-group">
                                            <input name="soft_d_retail" type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                                    <td class="execute-price">
                                        <div class="input-group">
                                            <input name="soft_d_execute" type="text" class="form-control col-md-6" value="" placeholder="" />
                                        </div>
                                    </td>
                                </tr>
                                <tr class="one-pos pos-m-1">
                                    <td>软广转发</td>
                                    <td class="orig-price">
                                        <div class="input-group">
                                            <input name="soft_t_orig"type="text" class="form-control col-md-6" value="" placeholder="" />
                                        </div>
                                    </td>
                                    <td class="retail-price">
                                        <div class="input-group">
                                            <input name="soft_t_retail"type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                                    <td class="execute-price">
                                        <div class="input-group">
                                            <input name="soft_t_execute"type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                                </tr>
                                <tr class="one-pos pos-m-2">
                                    <td>微任务直发</td>
                                    <td class="orig-price">
                                        <div class="input-group">
                                            <input name="mic_d_orig"type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                                    <td class="retail-price">
                                        <div class="input-group">
                                            <input name="mic_d_retail"type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                                    <td class="execute-price">
                                        <div class="input-group">
                                            <input name="mic_d_execute"type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                                </tr>
                                <tr class="one-pos pos-m-3">
                                    <td>微任务转发</td>
                                    <td class="orig-price">
                                        <div class="input-group">
                                            <input name="mic_t_orig" type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                                    <td class="retail-price">
                                        <div class="input-group">
                                            <input name="mic_t_retail"type="text" class="form-control col-md-6" value="" placeholder="" />
                                        </div>
                                    </td>
                                    <td class="execute-price">
                                        <div class="input-group">
                                            <input name="mic_t_execute"type="text" class="form-control col-md-6" value=""placeholder="" />
                                        </div>
                                    </td>
                            </table>

                            <div class="form-group">
                                <label class="col-md-2 control-label">资源所属关系</label>
                                <div class="col-md-5">
                                    <label class="radio-inline">
                                        <input type="radio" name="belong_type" value="1" />自营
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="belong_type" value="2" />独家
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="belong_type" value="3" />代理
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="belong_type" value="0" />其他
                                    </label>
                                </div>

                                <label class="col-md-2 control-label">价格有效期</label>
                                <div class="col-md-2">
                                    <input type="text" name="active_end_time"class="form-control active_end_time" value=""/>
                                    <a href="javascript:;" class="btn btn-primary btn-xs m-r-5 sync-latest-active-end-time">同步最新报价有效期</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">账期</label>
                                <div class="col-md-5">
                                    <label class="radio-inline">
                                        <input type="radio" name="account_period" value="1"  />季度
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="account_period" value="2" />半年
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="account_period" value="3" />一个月
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="account_period" value="0" />无
                                    </label>
                                </div>

                                <label class="col-md-2 control-label">审核状态</label>
                                <div class="col-md-2">
                                    <select name="status"class="form-control">
                                        <option value="-1">不限</option>
                                        <option value="0">待审核</option>
                                        <option value="1">已审核</option>
                                        <option value="2">未通过</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">配合度</label>
                                <div class="col-md-5">
                                    <label class="radio-inline">
                                        <input type="radio" name="cooperate_level" value="1" />高
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="cooperate_level" value="2" />中
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="cooperate_level" value="3" />低
                                    </label>
                                </div>

                                <label class="col-md-2 control-label">设置为首选供应商</label>
                                <div class="col-md-2">
                                    <label class="radio-inline">
                                        <input type="radio" name="is_pref_vendor" value="1"  />是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_pref_vendor" value="0"  />否
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">运营媒介</label>
                                <div class="col-md-2">
                                    <input name="name" type="text" class="form-control" value="" disabled/>
                                </div>

                                <label class="col-md-5 control-label label-is-put">是否上架</label>
                                <div class="col-md-2 is_put">
                                    <label class="radio-inline">
                                        <input type="radio" name="is_put" value="1" />是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_put" value="0" />否
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-9 control-label label-is-top">是否置顶</label>
                                <div class="col-md-2 is_top">
                                    <label class="radio-inline">
                                        <input type="radio" name="is_top" value="1"  />是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_top" value="0" />否
                                    </label>
                                </div>
                            </div>
                        </form>

                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-success btn-update-audit" data-uuid="<?=!empty($data['uuid'])?$data['uuid']:"";?>" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/update-audit'))?>">保存</button>
                                    <button type="submit" class="btn btn-primary btn-cancel" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/list'))?>">返回</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <!--添加媒体主-->
            <div class="tab-pane fade" id="default-tab-3">
                <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                    <div class="panel-body body-add-media-vendor">
                        <div class="alert alert-danger fade in m-b-15">
                            <strong>注意:  </strong>
                            1. 可从系统中搜索已经存在的媒体主  2. 如果在系统不存在,可以"新建媒体主"
                            <span class="close" data-dismiss="alert">×</span>
                        </div>

                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">搜索已有媒体主*: </label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control input-name" name="search_vendor_name" placeholder="媒体主名称\联系方式\QQ\微信"/>
                                    <span class="error-msg" style="color:red;display:none;font-size:16px;">媒体主不存在，请去 "新建媒体主"</span>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-success btn-vendor-search" type="button" data-url="<?=Yii::$app->urlManager->createUrl(array('media/vendor/search'))?>">搜&nbsp;&nbsp;&nbsp;索</button>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-primary btn-create-vendor" type="button" data-url="<?=Yii::$app->urlManager->createUrl(array('media/vendor/create'))?>">新建媒体主</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive table-search-vendor-list">
                            <h1 class="page-header" style="font-size:16px;color:#00acac;">搜索媒体主列表</h1>
                            <table id="user" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>媒体主</th>
                                        <th>注册渠道</th>
                                        <th>联系人</th>
                                        <th>备注</th>
                                        <th>选择</th>
                                    </tr>
                                </thead>
                                <tbody class="vendor-search-result">
                                <!-- ajax获取媒体主列表-->
                                </tbody>
                            </table>
                        </div>
                        <div style="text-align: center">
                            <button type="button" class="btn btn-success btn-add-vendor" style="display: none" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/add-vendor'))?>">添&nbsp;&nbsp;&nbsp;&nbsp;加</button>
                            <button type="submit" class="btn btn-primary btn-cancel" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/media/list'))?>">返回</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
   </div>
</div>


<?php
$Js = <<<JS
    mediaDetail();
JS;
$this->registerJs($Js);
?>

