<?php

/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:34 PM
 */

namespace wom\modules\adOwner\controllers;


use common\helpers\PlatformHelper;
use common\models\AdWeixinOrder;
use common\models\AdWeixinOrderArrangeContent;
use common\models\AdWeixinOrderDirectContent;
use common\models\AdWeixinPlan;
use common\helpers\ExternalFileHelper;
use common\models\WomDirectOrderTimeLineCtl;
use yii;
use yii\web\Response;


/**
 * 微信订单
 * Class WeixinOrderController
 * @package wom\modules\adOwner\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class WeixinOrderController extends AdOwnerBaseAppController
{
    public $layout = '//site-stage';

    /**
     * 添加原创约稿需求
     */
    public function actionEditArrangeContent()
    {
        $request = Yii::$app->request;
        // 保存内容
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $orderSubmit = $request->post('order_submit', 0);
            $posCode = $request->post('pos_code');
            $publishStartTime = strtotime($request->post('publish_start_time'));
            $publishEndTime = strtotime($request->post('publish_end_time'));
            $requirement = $request->post('requirement');

            if ($request->post('feedback_datetime', '') == '') {
                $feedbackTime = -1;
            } else {
                $feedbackTime = strtotime($request->post('feedback_datetime'));
            }

            $arrangeContent = AdWeixinOrderArrangeContent::find()
                ->where(['order_uuid' => $orderUUID, 'position_code' => $posCode])
                ->one();
            if ($arrangeContent === null) {
                $arrangeContent = new AdWeixinOrderArrangeContent();
                $arrangeContent->order_uuid = $orderUUID;
                $arrangeContent->position_code = $posCode;
                $arrangeContent->publish_start_time = $publishStartTime;
                $arrangeContent->publish_end_time = $publishEndTime;
                $arrangeContent->requirement = $requirement;
                $arrangeContent->feedback_datetime = $feedbackTime;
                $arrangeContent->save();
            } else {
                $arrangeContent->publish_start_time = $publishStartTime;
                $arrangeContent->publish_end_time = $publishEndTime;
                $arrangeContent->requirement = $requirement;
                $arrangeContent->feedback_datetime = $feedbackTime;
                $arrangeContent->save();
            }

            $order = AdWeixinOrder::find()
                ->where(['uuid' => $orderUUID])
                ->one();
            $position_content_conf = json_decode($order->position_content_conf, true);
            $position_content_conf[$posCode] = 1;
            $order->position_content_conf = json_encode($position_content_conf);
            $order->publish_start_time = $publishStartTime;
            $order->publish_end_time = $publishEndTime;
            $order->save();

            return ['err_code' => 0, 'err_msg' => '保存成功'];
        }
        // 加载页面
        if ($request->isGet) {
            $planUUID = $request->get('plan_uuid');
            $orderUUID = $request->get('order_uuid');
            $posCode = $request->get('pos_code');

            $weixinPlan = AdWeixinPlan::find()
                ->where(['uuid' => $planUUID])
                ->one();
            $arrangeContent = AdWeixinOrderArrangeContent::find()
                ->where(['order_uuid' => $orderUUID, 'position_code' => $posCode])
                ->one();
            if ($arrangeContent === null) {
                $arrangeContent = new AdWeixinOrderArrangeContent();
                $arrangeContent->order_uuid = $orderUUID;
                $arrangeContent->position_code = $posCode;
                $arrangeContent->publish_start_time = '';
                $arrangeContent->publish_end_time = '';
                $arrangeContent->feedback_datetime = '';
            } else {
                $arrangeContent->publish_start_time = date('Y-m-d H:i', $arrangeContent->publish_start_time);
                $arrangeContent->publish_end_time = date('Y-m-d H:i', $arrangeContent->publish_end_time);
                $arrangeContent->feedback_datetime = date('Y-m-d H:i', $arrangeContent->feedback_datetime);
            }

            return $this->render('edit-arrange-content', [
                'weixinPlan' => $weixinPlan,
                'arrangeContent' => $arrangeContent
            ]);
        }
    }

    /**
     * 添加直接投放内容
     */
    public function actionEditDirectContent()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $planUUID = $request->get('plan_uuid');
            $orderUUID = $request->get('order_uuid');
            $posCode = $request->get('pos_code');
            $weixinPlan = AdWeixinPlan::findOne(['uuid' => $planUUID]);
            $directContent = AdWeixinOrderDirectContent::findOne(['order_uuid' => $orderUUID, 'position_code' => $posCode]);
            if ($directContent === null) {
                $directContent = new AdWeixinOrderDirectContent();
                $directContent->order_uuid = $orderUUID;
                $directContent->position_code = $posCode;
                $directContent->publish_start_time = '';
                $directContent->publish_end_time = '';
            } else {
                $directContent->publish_start_time = date('Y-m-d H:i', $directContent->publish_start_time);
                $directContent->publish_end_time = date('Y-m-d H:i', $directContent->publish_end_time);
            }
            return $this->render('edit-direct-content', [
                'weixinPlan' => $weixinPlan,
                'directContent' => $directContent
            ]);
        }
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $orderUUID = $request->post('order_uuid');
            $posCode = $request->post('pos_code');
            $publishTime = strtotime($request->post('publish_time'));
            $articleUrl = $request->post('article_url');
            $title = $request->post('title');
            $author = $request->post('author');
            $link_url = $request->post('link_url');
            $articleShortDesc = $request->post('article_short_desc');
            $comment = $request->post('comment');
            $cover_in_body = $request->post('cover_in_body');
            $editor_con = $request->post('editor_con');
            $cover_pic = $request->post('cover_pic');
            $multiple_pic = substr($request->post('multiple_pic'),0,-1);
            $orderPay = $request->post('order_pay', 0);
            $directContent = AdWeixinOrderDirectContent::findOne(['order_uuid' => $orderUUID, 'position_code' => $posCode]);
            if ($directContent === null) {  //新建投放需求
                $directContent = new AdWeixinOrderDirectContent();
                $directContent->order_uuid = $orderUUID;
                $directContent->position_code = $posCode;
                $directContent->publish_start_time = $publishTime;
                $directContent->publish_end_time = $publishTime;
                $directContent->original_mp_url = trim($articleUrl);
                $directContent->title = trim($title);
                $directContent->author = trim($author);
                $directContent->link_url = trim($link_url);
                $directContent->article_short_desc = trim($articleShortDesc);
                $directContent->comment = trim($comment);
                $directContent->cover_in_body = $cover_in_body;
                $directContent->article_content = trim($editor_con);
                $directContent->cover_img = $cover_pic;
                $directContent->cert_img_urls = $multiple_pic;
                $directContent->save();
            } else {  //修改投放需求
                $directContent->publish_start_time = $publishTime;
                $directContent->publish_end_time = $publishTime;
                $directContent->original_mp_url = $articleUrl;
                $directContent->title = $title;
                $directContent->author = $author;
                $directContent->link_url = $link_url;
                $directContent->article_short_desc = $articleShortDesc;
                $directContent->comment = $comment;
                $directContent->cover_in_body = $cover_in_body;
                $directContent->article_content = trim($editor_con);
                $directContent->cover_img = $cover_pic;
                $directContent->cert_img_urls = $multiple_pic;
                $directContent->save();
            }
            $order = AdWeixinOrder::findOne(['uuid' => $orderUUID]);
            $position_content_conf = json_decode($order->position_content_conf, true);
            $position_content_conf[$posCode] = 1;
            $order->position_content_conf = json_encode($position_content_conf);
            $order->publish_start_time = $publishTime; // 投放开始时间
            $order->publish_end_time = $publishTime; // 投放结束时间
            $order->execute_time = $publishTime; // 执行时间
            if($orderPay == 1 && $order->status == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT){//提交待支付
                $order->status = AdWeixinOrder::ORDER_STATUS_TO_PAY;

                //任务时间控制
                $orderTimeLine = WomDirectOrderTimeLineCtl::findOne(['order_uuid'=>$orderUUID]);
                if($orderTimeLine == null){
                    $orderTime = time();
                    $time_diff_hours = ($publishTime - $orderTime)/3600;
                    //新建任务时间控制
                    $orderTimeLine = new WomDirectOrderTimeLineCtl();
                    $orderTimeLine->uuid = PlatformHelper::getUUID();
                    $orderTimeLine->order_uuid = $orderUUID;
                    $orderTimeLine->order_time = $orderTime;
                    $orderTimeLine->execute_time = $publishTime;
                }else{
                    $orderTime = $orderTimeLine->order_time;
                    $time_diff_hours = ($publishTime - $orderTime)/3600;
                    $orderTimeLine->execute_time = $publishTime;
                }
                if($time_diff_hours>4){
                    $orderTimeLine->not_pay_flow_time = $orderTime+(2*3600);
                }else{
                    $orderTimeLine->not_pay_flow_time = $orderTime+(1*3600);
                }
                $orderTimeLine->save();

            }
            $order->save();
            return ['err_code' => 0, 'err_msg' => '保存成功'];
        }
    }

    /**
     * 文章导入
     */
    public function actionImportContent(){
        $request = Yii::$app->request;
        $weixin_article_url =$request->post('article_url');
        $title = "";
        $author = "";
        $content = "";
        $innerHtml = "";
        try {
            $html =  file_get_html($weixin_article_url);
            $title = trim($html->find("#activity-name", 0)->plaintext);
            $author = trim($html->find("#post-user", 0)->plaintext);
            $content = trim($html->find("#js_content", 0)->plaintext);
            $innerHtml = trim($html->find("#js_content", 0)->innertext);

            return json_encode([
                'err_code'=>0,
                'err_msg'=>'获取成功',
                'title'=>$title,
                'author'=>$author,
                'content'=>$content,
                'innerHtml'=>$innerHtml,
            ]);
        } catch (Exception $e) {
            return json_encode(['err_code'=>1, 'err_msg'=>'获取失败',]);
        }
    }

}