<?php
/**
 * 视频资源搜索列表页
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/5/16 11:23 AM
 */
use yii\widgets\Pjax;
use wom\assets\AppAsset;
use yii\helpers\Html;
use common\helpers\MediaHelper;
use yii\helpers\Url;
$this->title = '微博预约';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/dep/datetimepicker/jquery.datetimepicker.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/weibo/order-creat.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/weibo/order-creat.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
$js = <<<JS
    //pjaxRefweibo();
JS;
$this->registerJS($js);
?>
    <input type="hidden" name="input-delete-weibo-order-url" value='<?=yii::$app->urlManager->createUrl(array('/ad-owner/weibo-plan/delete-weibo-order'))?>'>
    <input type="hidden" name="input-submit-plan-order-url" value='<?=yii::$app->urlManager->createUrl(array('/ad-owner/weibo-plan/submit-weibo-plan'))?>'>
    <input type="hidden" name="input-admin-order-list-url" value='<?=yii::$app->urlManager->createUrl(array('/ad-owner/admin-weibo-order/list'))?>'>
    <!--填写预约需求内容-->
    <div class="order-creat-content">
        <h2 class="font-20">填写预约需求</h2>
        <!--已选择资源-->
        <div class="top-title clearfix">
            <span class="fl">已选择资源: <i class="source-choosed-count color-main"></i> 个</span>
            <a class="continue-add btn btn-danger bg-main fr" href="<?=yii::$app->urlManager->createUrl(array('/weibo/media/list'))."&plan_uuid=".$plan_uuid;?>">继续添加资源</a>
        </div>
        <div class="source-choosed shadow">
            <table class="thead-title">
                <thead>
                    <tr>
                        <th style="width:218px">微博账号</th>
                        <th style="width:160px">粉丝数 (万)</th>
                        <th style="width:400px">价格位置</th>
                        <th style="width:109px">价格 (元)</th>
                        <th style="width:200px">接单备注</th>
                        <th style="width:100px">操作</th>
                    </tr>
                </thead>
            </table>
            <div class="table-wrap">
                <table class="source-choosed-table table">
                    <tbody>
                    <?php
                    if(!empty($weiboOrderList)){
                    foreach($weiboOrderList as $key => $order){?>
                        <tr order-uuid="<?=$order['uuid']?>">
                            <td class="user-ID" style="width: 218px">
                                <a class="short-ID plain-text-length-limit" href="javascript:;" data-limit="7" data-title="<?=$order['weibo_name']?>"><?=$order['weibo_name']?></a>
                            </td>
                            <td style="width: 160px"><?=round($order['follower_num']/10000,1)?></td>
                            <td style="width: 400px">

                                <div class="dropdown">
                                    <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="fl sub_type">软广直发</span>
                                        <span class="caret fr"></span>
                                    </div>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>软广直发</li>
                                        <li>软广转发</li>
                                        <li>硬广直发</li>
                                        <li>硬广转发</li>
                                    </ul>
                                </div>
                            </td>
                            <td class="price" style="width: 109px">
                                <span class="sd_price" style="display:block"><?=$order['sd_price']?></span>
                                <span class="st_price" style="display:none"><?=$order['st_price']?></span>
                                <span class="md_price" style="display:none"><?=$order['md_price']?></span>
                                <span class="mt_price" style="display:none"><?=$order['mt_price']?></span>
                            </td>
                            <td class="remark" width="200px">
                                <span class="brief-remark plain-text-length-limit" data-limit="10"><?=$order['accept_remark']?></span>
                                <div class="whole-remark">
                                    <em></em>
                                    <em></em>
                                    <div class="remark-content" >
                                        <?=$order['accept_remark']?>
                                    </div>
                                </div>
                            </td>
                            <td style="width:100px"><span class="delete color-main" order-uuid="<?=$order['uuid']?>">删除</span></td>
                        </tr>
                    <?php }} ?>
                    </tbody>
                </table>
                <div class="no-resource">暂未选择资源</div>

            </div>
        </div>
        <!--填写预约需求-->
        <h3 class="fill-order-title">填写预约需求</h3>
        <div class="fill-order-con shadow">
            <form action="post">
                <div class="input-group">
                    <span class="title-wrap">预约平台:</span>
                    <span class="platform font-500">新浪微博</span>
                </div>
                <div class="input-group">
                    <div class="title-wrap"><span class="title"><i></i>预约名称:</span></div>
                    <input class="booking-name" type="text" placeholder="请输入预约名称">
                    <div class="tips"><i>!</i>请输入预约名称</div>
                </div>
                <div class="plan-time input-group">
                    <div class="title-wrap"><span class="title"><i></i>预约执行时间:</span></div>
                    <input type="text" id="publish-start-time" class="input-section text-input datetimepicker" readonly="readonly" placeholder="请选择开始时间"/>
                    <span class="line"></span>
                    <input type="text" id="publish-end-time" class="input-section text-input datetimepicker" readonly="readonly" placeholder="请选择结束时间"/>
                    <div class="tips"><i>!</i>请输入执行时间</div>
                </div>
                <div class="input-group">
                    <div class="title-wrap"><span class="title"><i></i>联系人:</span></div>
                    <input class="contact" type="text" placeholder="请输入联系人">
                    <div class="tips"><i>!</i>请输入联系人</div>
                </div>
                <div class="input-group">
                    <div class="title-wrap"><span class="title"><i></i>手机号码:</span></div>
                    <input class="phone-number" type="text" placeholder="请输入手机号码">
                    <div class="tips"><i>!</i>请填写手机号码</div>
                </div>
                <div class="booking-require-wrap input-group">
                    <div class="clearfix">
                        <div class="title-wrap fl"><span class="title"><i></i>预约需求:</span></div>
                        <textarea class="booking-require fl" name="" id="" placeholder="请填写完整的预约需求内容" ></textarea>
                    </div>
                    <div class="sweet-tips">不要超过 <em> 2000 </em> 个字 </div>
                    <div class="tips"><i>!</i>请填写预约需求</div>
                </div>
                <div class="feedback input-group">
                    <div class="title-wrap"><span class="title"><i></i>需求反馈时间:</span></div>
                    <input type="text" id="feedback-time" class="input-section text-input datetimepicker" readonly="readonly" placeholder="反馈时间要大于当前时间"/>
                    <div class="tips"><i>!</i>请输入需求反馈时间</div>
                </div>
            </form>
        </div>
        <div class="submit btn bg-danger bg-main btn-submit-plan-order" data-uuid="<?=!empty($plan_uuid)?$plan_uuid:"";?>">提交</div>
    </div>

    <!--侧边栏联系方式-->
    <div class="side-bar">
        <ul>
            <li class="tel">
                <div class="tel-con detail">
                    <span></span>
                    <p>客服电话</p>
                    <p>400-878-9551</p>
                </div>
                <i></i>
            </li>
            <li class="qq">
                <div class="qq-con detail">
                    <span></span>
                    <p>客服QQ</p>
                    <p>2544745674</p>
                    <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=2&uin=800187006" target="_blank">点击直接与客服沟通</a>
                </div>
                <i></i>
            </li>
            <li class="weixin">
                <div class="weixin-con detail">
                    <p>微信扫一扫</p>
                    <span></span>
                </div>
                <i></i>
            </li>
            <li class="top">
                <i></i>
            </li>
        </ul>
    </div>

