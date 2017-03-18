
<?php
/**
 * 订单详情列表
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/6/16 18:33
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/order-manage/video-order-detail.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/video-order-detail.js');
$this->title = '视频订单详情';
?>
<!-- 主要内容部分 -->
<div class="content-wrap clearfix">
    <h2 class="font-20">视频订单详情</h2>
    <h3>已选择资源</h3>
    <div class="table-wrap shadow">
        <table class="table-normal table-video">
            <tr>
                <th>平台名称/ID</th>
                <th>粉丝数 (万)</th>
                <th>预约活动形式</th>
                <th>参考价</th>
                <th>平均观看人数</th>
                <th>接单备注</th>
            </tr>
            <?php
            switch($videoOrder->sub_type){
                case 1:$sub_type="线上直播";break;
                case 2:$sub_type="线下活动";break;
                case 3:$sub_type="原创视频";break;
                case 4:$sub_type="视频转发";break;
                default:$sub_type="未知";break;
            }
            switch($videoOrder->platform_type){//平台logo
                case 1:$platform_icon = "car-platform-icon-hj";break;
                case 2:$platform_icon = "car-platform-icon-xm";break;
                case 3:$platform_icon = "car-platform-icon-hn";break;
                case 4:$platform_icon = "car-platform-icon";break;
                case 5:$platform_icon = "car-platform-icon-mp";break;
                case 6:$platform_icon = "car-platform-icon-dy";break;
                case 7:$platform_icon = "car-platform-icon-yk";break;
                case 8:$platform_icon = "car-platform-icon-tb";break;
                case 9:$platform_icon = "car-platform-icon-yzb";break;
                default:$platform_icon = "";//默认美拍
            }
            ?>
            <tr class="border-none" order-uuid="<?=$videoOrder->uuid;?>">
                <td class="platform">
                    <div style="position:relative;">
                        <a class="platform-name plain-text-length-limit" href="javascript:;" data-limit="7"><?=$videoOrder->account_name;?></a>
                        <span class="car-platform-icon <?=$platform_icon?>"></span>
                    </div>
                    <span class="platform-id"><?=$videoOrder->account_id;?></span>
                </td>
                <td class="flower-number"><?=round($videoOrder->follower_num/10000,1)?></td>
                <td class="order-activity-form"><?=$sub_type?></td>
                <td class="price"><?=$videoOrder->price;?></td>
                <td class="audience-number"><?=$videoOrder->account_id;?></td>
                <td class="order-remark"><?=$videoOrder->accept_remark;?></td>
            </tr>
        </table>
    </div>
    <h3>预约需求</h3>
    <div class="order-demand shadow">
        <div class="column">
            <span class="info-title">预约名称 :</span>
            <span class="order-name"><?=$videoPlan->plan_name?></span>
        </div>
        <div class="column">
            <span class="info-title">预约执行时间 :</span>
            <span class="order-execute-time">
                <?=date('Y-m-d',$videoPlan->execute_start_time)?> ~ <?=date('Y-m-d',$videoPlan->execute_end_time)?>
            </span>
        </div>
        <div class="column">
            <span class="info-title">联系人 :</span>
            <span class="contact"><?=$videoPlan->contacts?></span>
        </div>
        <div class="column">
            <span class="info-title">手机号码 :</span>
            <span class="phone-number"><?=$videoPlan->phone?></span>
        </div>
        <div class="column">
            <span class="info-title fl">预约需求:</span>
            <textarea class="textarea-order-demand" name="" id="" cols="30" rows="10" disabled><?=$videoPlan->plan_desc?></textarea>
        </div>
        <div class="column-feedback-time column clearfix">
            <div class="fl left-con">
                <span class="info-title">需求反馈时间 :</span>
                <span class="feedback-time"><?=date('Y-m-d H:i:s',$videoPlan->feedback_time)?></span>
            </div>
            <div class="fl right-con">
                <span class="time-order-creat info-title">订单创建时间 :</span>
                <span><?=date('Y-m-d H:i:s',$videoPlan->create_time)?></span>
            </div>
        </div>
        <div class="column">
            <span class="info-title">订单状态 :</span>
            <?php
            switch($videoOrder->status){
                case 0:$status="待提交";break;
                case 1:$status="预约中";break;
                case 2:$status="已完成";break;
                default:$status="待提交";break;
            }
            ?>
            <span class="order-status"><?=$status?></span>
        </div>
        <div class="column clearfix">
            <div class="fl left-con">
                <span class="info-title">订单执行时间 :</span>
                <?php (!empty($videoOrder->execute_time))? $execute_time=date('Y-m-d H:i:s',$videoOrder->execute_time):$execute_time="暂无";?>
                <span class="order-detail-execute-time"><?=$execute_time?></span>
            </div>
            <div class="fl right-con">
                <span class="execute-money info-title">订单执行金额 :</span>
                <?php ($videoOrder->status==2)? $execute_price = $videoOrder->execute_price:$execute_price="暂无";?>
                <span><?=$execute_price;?></span>
            </div>

        </div>
    </div>
</div>
