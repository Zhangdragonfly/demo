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
$this->title = '视频预约';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/dep/datetimepicker/jquery.datetimepicker.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/video/order-creat.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
AppAsset::addScript($this, '@web/src/js/video/order-creat.js');

?>
    <input type="hidden" name="input-delete-video-order-url" value='<?=yii::$app->urlManager->createUrl(array('/ad-owner/video-plan/delete-video-order'))?>'>
    <input type="hidden" name="input-submit-plan-order-url" value='<?=yii::$app->urlManager->createUrl(array('/ad-owner/video-plan/submit-plan'))?>'>
    <input type="hidden" name="input-admin-order-list-url" value='<?=yii::$app->urlManager->createUrl(array('/ad-owner/admin-video-order/list'))?>'>
    <!--填写预约需求内容-->
    <div class="order-creat-content">
        <h2 class="font-20">填写预约需求</h2>
        <!--已选择资源-->
        <div class="top-title clearfix">
            <span class="fl">已选择资源: <i class="source-choosed-count color-main"></i> 个</span>
            <a class="continue-add btn btn-danger bg-main fr" href="<?=yii::$app->urlManager->createUrl(array('/video/media/list'))."&plan_uuid=".$plan_uuid;?>">继续添加资源</a>
        </div>
        <div class="source-choosed shadow">
            <table class="thead-title">
                <thead>
                <tr>
                    <th width="200px">平台账号</th>
                    <th width="95px">粉丝数</th>
                    <th width="172px">预约形式</th>
                    <th width="200px">参考价（元）</th>
                    <th width="215px">价格有效期</th>
                    <th width="220px">接单备注</th>
                    <th>操作</th>
                </tr>
                </thead>
            </table>
            <div class="table-wrap">
                <table class="source-choosed-table table">
                    <tbody>
                    <?php
                    if(!empty($videoOrderList)){
                    foreach($videoOrderList as $key => $order){?>
                    <?php
                    switch($order['sex']){//性别
                        case 1:$sex = "男";$sex_icon = "sex-icon-b";break;
                        case 2:$sex = "女";$sex_icon = "sex-icon-g";break;
                        case 0:$sex = "未知";$sex_icon = "";break;
                        default:$sex = "未知";$sex_icon = "";
                    }
                    switch($order['platform_type']){//平台logo
                        case 1:$platform_icon = "platform-icon-hj";break;
                        case 2:$platform_icon = "platform-icon-xm";break;
                        case 3:$platform_icon = "platform-icon-hn";break;
                        case 4:$platform_icon = "platform-icon";break;
                        case 5:$platform_icon = "platform-icon-mp";break;
                        case 6:$platform_icon = "platform-icon-dy";break;
                        case 7:$platform_icon = "platform-icon-yk";break;
                        case 8:$platform_icon = "platform-icon-tb";break;
                        case 9:$platform_icon = "platform-icon-yzb";break;
                        default:$platform_icon = "";//默认美拍
                    }

                    ?>
                    <tr order-uuid="<?=$order['uuid']?>">
                        <td width="200px" class="account-platform clearfix">
                            <dl class="clearfix">
                                <dt class="fl">
                                    <a href="#">
                                        <img src="../src/images/demo.jpg" alt="">
                                    </a>
                                </dt>
                                <dd class="fl">
                                    <a class="ID-name synopsis" href="#" data-str="6" data-title="<?=$order['account_name']?>" data-value = '<?=$order['account_name']?>'><?=$order['account_name']?></a>
                                    <div class="">
                                        <i class="sex-icon-g <?=$sex_icon?>"></i>
                                        <em><?=$sex?></em>
                                        <i class="platform-icon <?=$platform_icon?>"></i>
                                    </div>
                                </dd>
                            </dl>
                        </td>
                        <td width="95px"><?=round($order['follower_num']/10000,1)?>万</td>
                        <?php
                        if($order['platform_type']==5){
                            $online_name = "原创视频";
                            $offline_name = "视频转发";
                        }else{
                            $online_name = "线上直播";
                            $offline_name = "线下活动";
                        }
                        ?>
                        <td width="172px">
                            <div class="dropdown">
                                <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fl sub_type"><?=$online_name?></span>
                                    <span class="caret fr"></span>
                                </div>
                                <ul class="dropdown-menu" role="menu">
                                    <li><?=$online_name?></li>
                                    <li><?=$offline_name?></li>
                                </ul>
                            </div>
                        </td>
                        <td width="200px" class="price">
                            <span class="online-price"><?=$order['price_one']?></span>
                            <span class="offline-price" style="display:none;"><?=$order['price_two']?></span>
                        </td>
                        <td width="215px"><?=date('Y-m-d',$order['active_end_time'])?></td>
                        <td width="220px" class="remark">
                            <span class="brief-remark synopsis" data-str="10"><?=$order['accept_remark']?></span>
                            <div class="whole-remark">
                                <em></em>
                                <em></em>
                                <div class="remark-content" >
                                    <?=$order['accept_remark']?>
                                </div>
                            </div>
                        </td>
                        <td><span class="delete color-main" order-uuid="<?=$order['uuid']?>">删除</span></td>
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
                    <span class="platform font-500">视频网红</span>
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
