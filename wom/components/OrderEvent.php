<?php
/**
 * Date: 2016/12/23
 * Time: 14:35
 */
namespace wom\components;

use common\helpers\PlatformHelper;
use common\models\AdWeixinOrder;
use common\models\AdWeixinOrderDirectContent;
use common\models\MediaWeixinNeedGet;
use common\models\WomDirectOrderTimeLineCtl;
use yii\base\Component;
use yii;

/**
 * 订单流程计时事件
 * Class OrderEvent
 * @package wom\components
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class OrderEvent extends Component{
    /**
     * 初始化直投订单时间点事件
     */
    const EVENT_INIT_WEIXIN_DIRECT_ORDER_TIMELINE_CTL = "initWeixinDirectOrderTimelineCtl";
    /**
     * 媒体主提交订单执行链接事件
     */
    const EVENT_SUBMIT_ORDER_EXECUTE_LINK = "submitOrderExecuteLink";
    /**
     * 触发 afterPayOrder事件
     */
    public function afterPayOrder()
    {
        $this->trigger(self::EVENT_INIT_WEIXIN_DIRECT_ORDER_TIMELINE_CTL);
    }
    /**
     * 触发 afterSubmitOrderExecuteLink事件
     */
    public function afterSubmitOrderExecuteLink()
    {
        $this->trigger(self::EVENT_SUBMIT_ORDER_EXECUTE_LINK);
    }

    /**
     *  微信直投订单支付后 事件处理器
     * 目前主要作用：
     * 添加到 wom_direct_order_timeline_ctl 表
     * @param $event 参数包含：
     * 1.name：事件名
     * 2.sender：调用 trigger() 方法的对象
     * 3.data：附加事件处理器时传入的数据，默认为空
     */
    public function initWeixinDirectOrderTimelineCtl($event)
    {
        $planUUID = $event->data['plan_uuid'];
        $orderUUID = $event->data['order_uuid'];
        if($orderUUID == 0){
            $orderList = AdWeixinOrder::findAll(['plan_uuid' => $planUUID]);
        }else{
            $orderList = AdWeixinOrder::findAll(['uuid' => $orderUUID]);
        }
        foreach($orderList as $order){
            $directContent = AdWeixinOrderDirectContent::findOne(['order_uuid' => $order['uuid']]);
            $timeLineCtl = new WomDirectOrderTimeLineCtl();
            $timeLineCtl->uuid = PlatformHelper::getUUID();
            $timeLineCtl->order_uuid = $order['uuid'];
            $timeLineCtl->order_pay_time = time();
            $timeLineCtl->sys_auto_abort_order_time = $timeLineCtl->order_pay_time + 3600*2;
            $timeLineCtl->execute_time = $directContent['publish_start_time'];
            $timeLineCtl->execute_buffer_time = $directContent['publish_start_time'] + 3600*2;
            if($timeLineCtl->execute_time - $timeLineCtl->order_pay_time > 4*3600){
                $timeLineCtl->execute_remind_time = $directContent['publish_start_time'] - 3600*2;
            }else{
                $timeLineCtl->execute_remind_time = $directContent['publish_start_time'] - 3600;
            }
            $timeLineCtl->save();
        }
    }

    /**
     *  媒体主提交订单执行链接后 事件处理器
     * 目前主要作用：
     * 更新 wom_direct_order_timeline_ctl 表
     * @param $event 参数包含：
     * 1.name：事件名
     * 2.sender：调用 trigger() 方法的对象
     * 3.data：附加事件处理器时传入的数据，默认为空
     */
    public function submitOrderExecuteLink($event)
    {
        $orderUUID = $event->data;
        $timeLineCtl = WomDirectOrderTimeLineCtl::findOne(['order_uuid' => $orderUUID]);
        $timeLineCtl->real_execute_time = time();
        $timeLineCtl->sys_auto_confirm_execute_time = strtotime(date('Y-m-d'). " 23:59:59");
        $timeLineCtl->screenshot_commit_btn_open_time = strtotime(date('Y-m-d'). " 00:00:00") + 4*24*3600;
        $timeLineCtl->screenshot_commit_end_time = $timeLineCtl->screenshot_commit_btn_open_time + 2*24*3600;
        $timeLineCtl->save();
    }
}