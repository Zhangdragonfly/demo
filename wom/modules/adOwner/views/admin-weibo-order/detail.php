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
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/order-manage/weibo-order-detail.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/weibo-order-detail.js');
$this->title = '微博订单详情';
?>
<!-- 主要内容部分 -->
<div class="content-wrap clearfix">
    <h2 class="font-20">微博订单详情</h2>
    <h3>已选择资源</h3>
    <div class="table-wrap shadow">
        <table class="table-normal">
            <tr>
                <th>微博账号</th>
                <th>粉丝数 (万)</th>
                <th>预约位置</th>
                <th>位置价格</th>
                <th>接单备注</th>
            </tr>
            <?php
            switch($weiboOrder->sub_type){
                case 1:$sub_type="软广直发";break;
                case 2:$sub_type="软广转发";break;
                case 3:$sub_type="微任务直发";break;
                case 4:$sub_type="微任务转发";break;
                default:$sub_type="未知";break;
            }
            ?>
            <tr class="border-none" order-uuid="<?=$weiboOrder->uuid;?>">
                <td>
                    <a class="account-number plain-text-length-limit" href="javascript:;" data-limit="7"><?=$weiboOrder->weibo_name;?></a>
                </td>
                <td class="flower-number"><?=round($weiboOrder->follower_num/10000,1)?></td>
                <td class="order-pos"><?=$sub_type;?></td>
                <td class="price-of-pos"><?=$weiboOrder->price;?></td>
                <td class="order-remark"><?=$weiboOrder->accept_remark;?></td>
            </tr>
        </table>
    </div>
    <h3>预约需求</h3>
    <div class="order-demand shadow">
        <div class="column">
            <span class="info-title">预约名称 :</span>
            <span class="order-name"><?=$weiboPlan->plan_name?></span>
        </div>
        <div class="column">
            <span class="info-title">预约执行时间 :</span>
            <span class="order-execute-time">
                <?=date('Y-m-d',$weiboPlan->execute_start_time)?> ~ <?=date('Y-m-d',$weiboPlan->execute_end_time)?>
            </span>
        </div>
        <div class="column">
            <span class="info-title">联系人 :</span>
            <span class="contact"><?=$weiboPlan->contacts?></span>
        </div>
        <div class="column">
            <span class="info-title">手机号码 :</span>
            <span class="phone-number"><?=$weiboPlan->phone?></span>
        </div>
        <div class="column">
            <span class="info-title fl">预约需求:</span>
            <textarea class="textarea-order-demand" name="" id="" cols="30" rows="10" disabled><?=$weiboPlan->plan_desc?></textarea>
        </div>
        <div class="column clearfix">
            <div class="fl left-con">
                <span class="info-title">需求反馈时间 :</span>
                <span class="feedback-time"><?=date('Y-m-d H:i:s',$weiboPlan->feedback_time)?></span>
            </div>
            <div class="fl right-con">
                <span class="time-order-creat info-title">订单创建时间 :</span>
                <span class="order-creat-time"><?=date('Y-m-d H:i:s',$weiboPlan->create_time)?></span>
            </div>
        </div>
        <div class="column">
            <span class="info-title">订单状态 :</span>
            <?php
            switch($weiboOrder->status){
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
                <?php (!empty($weiboOrder->execute_time))? $execute_time=date('Y-m-d H:i:s',$weiboOrder->execute_time):$execute_time="暂无";?>
                <span class="order-detail-execute-time"><?=$execute_time?></span>
            </div>
            <div class="fl right-con">
                <span class="execute-money info-title">订单执行金额 :</span>
                <?php ($weiboOrder->status==2)? $execute_price = $weiboOrder->execute_price:$execute_price="暂无";?>
                <span><?=$execute_price;?></span>
            </div>
        </div>
    </div>
</div>
