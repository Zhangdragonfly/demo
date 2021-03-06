<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:33 PM
 */
use admin\assets\AppAsset;
use common\helpers\MediaHelper;
use yii\helpers\Html;

AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');

$videoJS = <<<JS
    //taskCreate();
JS;
$this->registerJs($videoJS);
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">订单管理</a></li>
        <li class="active">订单列表</li>
    </ol>
    <h1 class="page-header">订单详情／操作 <small>处理订单</small></h1>


    <div class="row">

        <div class="col-md-10">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#default-tab-1" data-toggle="tab">基础信息</a></li>
                <li class=""><a href="#default-tab-2" data-toggle="tab">订单操作</a></li>
                <li class=""><a href="#default-tab-3" data-toggle="tab">操作记录</a></li>
            </ul>

            <div class="tab-content">
                <!--预约基础信息-->
                <div class="tab-pane fade active in" id="default-tab-1">
                    <div class="panel-group" id="accordion">

                        <!--基础信息-->
                        <div class="panel panel-inverse overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <i class="fa fa-plus-circle pull-right"></i>
                                       订单基础信息
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <form class="form-horizontal form-bordered">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">广告主</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="viwen@51wom.com" disabled/>
                                            </div>

                                            <label class="col-md-2 control-label">自媒体主</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="manson@qmhchina.com" disabled/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">活动名称</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="双十一推广活动" disabled/>
                                            </div>

                                            <label class="col-md-2 control-label">下单时间</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="2017.11.17 12:00" disabled/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">活动状态</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="带提交" disabled/>
                                            </div>

                                            <label class="col-md-2 control-label">订单状态</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="待审核" disabled/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">预约账号</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="同道大叔（tongdaodashu）" disabled/>
                                            </div>

                                            <label class="col-md-2 control-label">发布位置</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="多图文第一条（只原创）" disabled/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">执行时间</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="2017.12.30 - 2018.01.09" disabled/>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!--订单投放内容-->
                        <div class="panel panel-inverse overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                        <i class="fa fa-plus-circle pull-right"></i>
                                        订单投放内容
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <form class="form-horizontal form-bordered">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">文章导入链接</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="viwen@51wom.com" disabled/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">原文链接</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control media_id" placeholder="viwen@51wom.com" disabled/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">封面图片</label>
                                            <div class="col-md-3">
                                                <div class="radio">
                                                    <label>
                                                        <input type="checkbox" name="optionsRadios" value="option1">
                                                        封面图片显示在正文
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">摘要</label>
                                            <div class="col-md-3">
                                                <textarea class="form-control" placeholder="Textarea" rows="5" disabled></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">正文内容</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" placeholder="Textarea" rows="16" disabled></textarea>
                                            </div>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--订单操作-->
                <div class="tab-pane fade" id="default-tab-2">

                    <!--待接单-->
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                    <i class="fa fa-plus-circle pull-right"></i>
                                   待接单
                                </a>
                            </h3>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单状态</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="待接单" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">操作人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">跟单媒介</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" />
                                        </div>

                                        <label class="col-md-2 control-label">操作时间</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="2017.11.17 12:00" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">项目负责人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">备注</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单操作</label>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="checkbox" name="optionsRadios" value="option1">
                                                    取消订单
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">取消原因</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">广告主解冻金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">自媒体主打款金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <a href="javascript:;" class="btn btn-white m-r-5">取消</a>
                                    <a href="javascript:;" class="btn btn-primary">确定</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--待执行链接-->
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseExecuteUrl">
                                    <i class="fa fa-plus-circle pull-right"></i>
                                    待执行链接
                                </a>
                            </h3>
                        </div>
                        <div id="collapseExecuteUrl" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单状态</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="待接单" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">操作人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">跟单媒介</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" />
                                        </div>

                                        <label class="col-md-2 control-label">操作时间</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="2017.11.17 12:00" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">项目负责人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">提交执行链接</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">提交执行截图</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">备注</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单操作</label>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="checkbox" name="optionsRadios" value="option1">
                                                    取消订单
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">取消原因</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">广告主解冻金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">自媒体主打款金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <a href="javascript:;" class="btn btn-white m-r-5">取消</a>
                                    <a href="javascript:;" class="btn btn-primary">确定</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--待效果截图-->
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseImg">
                                    <i class="fa fa-plus-circle pull-right"></i>
                                    待效果截图
                                </a>
                            </h3>
                        </div>
                        <div id="collapseImg" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单状态</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="待接单" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">操作人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">跟单媒介</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" />
                                        </div>

                                        <label class="col-md-2 control-label">操作时间</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="2017.11.17 12:00" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">项目负责人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">执行记录表</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">提交执行截图</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">备注</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>

                                    <legend>执行反馈</legend>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">执行反馈</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="审核未通过" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">延长反馈处理时间</label>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="checkbox" name="optionsRadios" value="option1">
                                                    48小时
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">提交执行链接</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="审核未通过" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">提交效果截图</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">备注</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>

                                </form>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <a href="javascript:;" class="btn btn-white m-r-5">取消</a>
                                    <a href="javascript:;" class="btn btn-primary">确定</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--已拒绝-->
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseRefuse">
                                    <i class="fa fa-plus-circle pull-right"></i>
                                    已拒单
                                </a>
                            </h3>
                        </div>
                        <div id="collapseRefuse" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单状态</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="待接单" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">操作人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">跟单媒介</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" />
                                        </div>

                                        <label class="col-md-2 control-label">操作时间</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="2017.11.17 12:00" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">项目负责人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">拒单原因</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <a href="javascript:;" class="btn btn-white m-r-5">取消</a>
                                    <a href="javascript:;" class="btn btn-primary">确定</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--已取消-->
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseCancel">
                                    <i class="fa fa-plus-circle pull-right"></i>
                                    已取消
                                </a>
                            </h3>
                        </div>
                        <div id="collapseCancel" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单状态</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="待接单" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">操作人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">跟单媒介</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" />
                                        </div>

                                        <label class="col-md-2 control-label">操作时间</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="2017.11.17 12:00" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">项目负责人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">取消原因</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">广告主解冻金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">自媒体主打款金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">备注</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <a href="javascript:;" class="btn btn-white m-r-5">取消</a>
                                    <a href="javascript:;" class="btn btn-primary">确定</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--已完成-->
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseFinsh">
                                    <i class="fa fa-plus-circle pull-right"></i>
                                    已完成
                                </a>
                            </h3>
                        </div>
                        <div id="collapseFinsh" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">广告主订单状态</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="待接单" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">自媒体主订单状态</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">跟单媒介</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" />
                                        </div>

                                        <label class="col-md-2 control-label">操作时间</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="2017.11.17 12:00" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">操作人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                        <label class="col-md-2 control-label">项目负责人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">广告主已扣款</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="800" disabled/>
                                        </div>
                                        <label class="col-md-2 control-label">自媒体主已收款</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="800" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">提交执行记录表</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">备注</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>

                                    <legend>执行反馈</legend>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">执行反馈</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="审核未通过" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">延长反馈处理时间</label>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="checkbox" name="optionsRadios" value="option1">
                                                    48小时
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">提交执行链接</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="审核未通过" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">提交效果截图</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <a href="javascript:;" class="btn btn-white m-r-5">取消</a>
                                    <a href="javascript:;" class="btn btn-primary">确定</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--已流单-->
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseFlow">
                                    <i class="fa fa-plus-circle pull-right"></i>
                                    已流单
                                </a>
                            </h3>
                        </div>
                        <div id="collapseFlow" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单状态</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="待接单" disabled/>
                                        </div>

                                        <label class="col-md-2 control-label">操作人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">跟单媒介</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="manson" />
                                        </div>

                                        <label class="col-md-2 control-label">操作时间</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="2017.11.17 12:00" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">项目负责人</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">执行记录表</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">取消原因</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" placeholder="Textarea" rows="8" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">订单金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">广告主解冻金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">自媒体主打款金额</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control media_id" placeholder="阿梦辰" />
                                        </div>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <a href="javascript:;" class="btn btn-white m-r-5">取消</a>
                                    <a href="javascript:;" class="btn btn-primary">确定</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <!--操作记录-->
                <div class="tab-pane fade" id="default-tab-3">
                    <!--订单投放内容-->
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                                    <i class="fa fa-plus-circle pull-right"></i>
                                    操作记录
                                </a>
                            </h3>
                        </div>
                        <div id="collapseSix" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="panel panel-inverse" data-sortable-id="table-basic-5">
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>操作者</th>
                                                <th>操作事项</th>
                                                <th>操作时间</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>masnon</td>
                                                <td>Nicky Almera</td>
                                                <td>nicky@hotmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>ymc</td>
                                                <td>Edmund Wong</td>
                                                <td>edmund@yahoo.com</td>
                                            </tr>
                                            <tr>
                                                <td>test</td>
                                                <td>Harvinder Singh</td>
                                                <td>harvinder@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>saony</td>
                                                <td>Terry Khoo</td>
                                                <td>terry@gmail.com</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>


</div>




























