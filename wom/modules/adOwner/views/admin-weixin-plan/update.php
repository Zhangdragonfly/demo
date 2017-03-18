<?php
/**
 * 活动内容
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/30/16/ 16:05
 */
use wom\assets\AppAsset;
use common\helpers\MediaHelper;
use yii\helpers\Url;

AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/plan-manage/weixin-plan-update.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/plan-manage/weixin-plan-update.js');

$this->title = '微信计划内容';
?>

<!--活动内容-->
<input id="id-change-order-pos-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/change-order-pos', 'order_uuid' => '_order_uuid_']) ?>">
<input id="id-edit-arrange-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/edit-arrange-content', 'plan_action' => 'update', 'plan_uuid' => $weixinPlan->uuid, 'order_uuid' => '_order_uuid_', 'pos_code' => '_pos_code_']) ?>">
<input id="id-edit-direct-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/edit-direct-content', 'plan_action' => 'update', 'plan_uuid' => $weixinPlan->uuid, 'order_uuid' => '_order_uuid_', 'pos_code' => '_pos_code_']) ?>">
<input id="id-weixin-media-delete-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/delete-media', 'order_uuid' => '_order_uuid_']) ?>">
<input id="id-weixin-media-list-url" type="hidden" value="<?= Url::to(['/weixin/media/list', 'plan_action' => 'update', 'plan_uuid' => $weixinPlan->uuid]) ?>">
<input id="id-pay-confirm-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/pay-confirm', 'plan_uuid' => $weixinPlan->uuid]) ?>">
<input id="id-plan-uuid" type="hidden" value="<?= $weixinPlan->uuid ?>">

<div class="active-content-wrap">
    <h2 class="font-20">活动内容</h2>
    <div class="active-content">
        <div class="active-platform clearfix">
            <span class="fl">活动名称：</span>
            <div class="active-name fl">
                <span><?= $weixinPlan->name ?></span>
                <span class="modify-active-name red">我要修改</span>
            </div>
            <div class="edit-active-name">
                <input type="text" class="form-control">
                <button class="btn btn-danger save-edit">保存</button>
                <button class="btn btn-danger cancle-edit">取消</button>
            </div>
        </div>
        <div class="active-status">
            <span>活动状态：</span>
            <i><?= MediaHelper::getPlanStatusLabel($weixinPlan->status) ?></i>
        </div>
        <div class="requirements-sum clearfix">
            <span class="fl">需求描述：</span>
                <textarea name="" class="fl">
                    <?= $weixinPlan->plan_desc ?>
                </textarea>
                <textarea name="" class="form-control fl" style="display: none">
                </textarea>
        </div>
        <div class="edit-requirements-sum">
            <span class="red">我要修改</span>
            <button class="btn btn-danger save-edit">保存</button>
            <button class="btn btn-danger cancle-edit">取消</button>
        </div>
    </div>
</div>

<div class="source-wrap">
    <h2>微信公众号</h2>
    <div class="source-edit shadow">
        <table class="thead-title">
            <thead>
            <tr>
                <th width="200px">账号名称</th>
                <th width="200px">投放位置</th>
                <th width="100px">参考价（元）</th>
                <th width="200px">需求添加状态/执行状态</th>
                <th width="215px">操作</th>
                <th width="220px">接单备注</th>
            </tr>
            </thead>
        </table>
        <div class="table-wrap">
            <table class="table source-choosed-table">
                <tbody>
                <!--
                    后台渲染页面的时候,每个账号都有一个基本信息(json格式)
                    示例如下:
                    {
                                    "pos_s":{
                                        "retail_price": 111,
                                        "has_add_content": 1,
                                        "pub_type": 1,
                                        "is_selected": 1
                                    },
                                    "pos_m_1":{
                                        "retail_price": 112,
                                        "has_add_content": 1,
                                        "pub_type": 0,
                                        "is_selected": 0
                                    },
                                    "pos_m_2":{
                                        "retail_price": 113,
                                        "has_add_content": 0,
                                        "pub_type": 1,
                                        "is_selected": 0
                                    },
                                    "pos_m_3":{
                                        "retail_price": 114,
                                        "has_add_content": 0,
                                        "pub_type": 1,
                                        "is_selected": 0
                                    }
                                }
                 其中:   pos_s 表示单图文,  pos_m_1 表示多图文头条,  pos_m_2 表示多图文2条,  pos_m_3 表示多图文3-N条
                         retail_price 表示零售价(参考价,单位元)
                         has_add_content表示是否已经添加需求(1 已经添加需求, 0 未添加)
                         pub_type发布类型(-1 未设置, 0 不接单,1 只发布, 2 只原创)
                         is_selected 当前位置是否select选中(1 已经选中, 0 未选中)



                         {
                                "is_paid": 1,
                                "head_avg_read_num": 2355,
                                "total_follower_num": 1000,
                                "pos_selected": "pos_m_1",
                                "order_status": 5,
                                "order_status_label": "已完成",
                                "retail_price": 4365,
                                "operate_action": [show_execute_link,show_effect_shots,show_report],
                                "pos_s": {
                                    "pub_type": 0
                                },
                                "pos_m_1": {
                                    "pub_type": 2
                                },
                                "pos_m_2": {
                                    "pub_type": 1
                                },
                                "pos_m_3": {
                                    "pub_type": 1
                                }
                          }

                          {
                                "is_paid": 0,
                                "head_avg_read_num": 2355,
                                "total_follower_num": 1000,
                                "pos_s": {
                                    "retail_price": 111,
                                    "has_add_content": 1,
                                    "pub_type": 1,
                                    "is_selected": 1
                                },
                                "pos_m_1": {
                                    "retail_price": 112,
                                    "has_add_content": 1,
                                    "pub_type": 1,
                                    "is_selected": 0
                                },
                                "pos_m_2": {
                                    "retail_price": 113,
                                    "has_add_content": 0,
                                    "pub_type": 1,
                                    "is_selected": 0
                                },
                                "pos_m_3": {
                                    "retail_price": 114,
                                    "has_add_content": 0,
                                    "pub_type": 1,
                                    "is_selected": 0
                                }
                          }
                -->

                <?php foreach($weixinOrderList as $order){ ?>

                    <tr class="one-account" data-is-paid="0" data-pub-type="" data-retail-price="" data-head-avg-read-num="" data-total-follower-num="">
                        <td width="200px" class="area-account-info clearfix">

                            <div class="order-config" style="display: none">
                                <?= $order['order_config'] ?>
                            </div>

                            <dl class="clearfix">
                                <dt class="fl">
                                    <a href="#">
                                        <img src="http://open.weixin.qq.com/qr/code/?username=<?= $order['public_id'] ?>" alt="">
                                    </a>
                                    <i></i>
                                </dt>
                                <dd class="fl">
                                    <a class="account-name plain-text-length-limit" href="#" data-limit="7" data-title="<?= $order['public_name']; ?>"><?= $order['public_name']; ?></a>
                                    <div class="account-id-area">
                                        <i class="ewm"></i>
                                        <em class="plain-text-length-limit" data-limit="7"><?= $order['public_id']; ?></em>
                                        <img src="http://open.weixin.qq.com/qr/code/?username=<?= $order['public_id'] ?>" alt="" height="80px" width="80px">
                                    </div>
                                </dd>
                            </dl>
                        </td>

                        <td width="200px" class="area-pos-select">
                            <span class="pub-type-read-only"></span>
                            <div class="dropdown">
                                <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fl selected-pos"></span>
                                    <span class="caret fr"></span>
                                </div>
                                <ul class="dropdown-menu available-pos-list" role="menu">

                                </ul>
                            </div>
                        </td>

                        <td width="100px" class="area-retail-price">
                            <span class="retail-price"></span>
                        </td>

                        <td width="200px" class="area-status area-req-status">
                            <span class="status-has-add-content set-font-green">已添加需求</span>
                            <span class="status-not-add-content set-font-color">未添加需求</span>
                        </td>

                        <td width="215px" class="area-operate">
                            <ul>
                                <li class="btn-operate btn-update-demand" data-btn="btn-update-demand" style="display: none">修改需求</li>
                                <li class="btn-operate btn-add-demand" data-btn="btn-add-demand" style="display: none">添加需求</li>
                                <li class="btn-operate btn-del-order" data-btn="btn-del-order" style="display: none">删除</li>
                                <li class="btn-operate btn-show-report" data-btn="btn-show-report" style="display: none">查看报告</li>
                                <li class="btn-operate btn-invalid-order-info" data-btn="btn-invalid-order-info" data-toggle="modal" data-target="#modal-invalid-order-info" style="display: none">原因</li>
                                <li class="btn-operate btn-arrange-order-detail" data-btn="btn-arrange-order-detail" data-toggle="modal" data-target="#modal-arrange-order-detail" style="display: none">详情</li>
                                <li class="btn-operate btn-direct-order-detail" data-btn="btn-direct-order-detail" data-toggle="modal" data-target="#modal-arrange-order-more-detail" style="display: none">详情</li>
                                <li class="btn-operate btn-arrange-order-more-detail" data-btn="btn-arrange-order-more-detail" data-toggle="modal" data-target="#modal-direct-order-detail" style="display: none">详情</li>
                                <li class="btn-operate btn-to-verify-execute-link" data-btn="btn-to-verify-execute-link" data-toggle="modal" data-target="#modal-to-verify-execute-link" style="display: none">确认链接</li>
                                <li class="btn-operate btn-show-execute-link" data-btn="btn-show-execute-link" data-toggle="modal" data-target="#modal-show-execute-link" style="display: none">执行链接</li>
                                <li class="btn-operate btn-show-effect-shots" data-btn="btn-show-effect-shots" data-toggle="modal" data-target="#modal-show-effect-shots" style="display: none">效果截图</li>
                            </ul>
                        </td>

                        <td width="220px" class="area-remark">
                            <ul>
                                <li class="one-pos pos-m-1" data-pos = "pos_m_1"><span>多图文头条：</span><span class="pub-type-label"></span></li>
                                <li class="one-pos pos-m-2" data-pos = "pos_m_2"><span>多图文2条：</span><span class="pub-type-label"></span></li>
                                <li class="one-pos pos-m-3" data-pos = "pos_m_3"><span>多图文3-N条：</span><span class="pub-type-label"></span></li>
                                <li class="one-pos pos-s" data-pos="pos_s"><span>单图文：</span><span class="pub-type-label"></span></li>
                            </ul>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
            <div class="nothing-resource">暂无资源</div>
        </div>
    </div>
</div>

<div class="data-show area-stat">
    <div class="in-data-show clearfix">
        <div class="data-l fl">
            <ul class="clearfix">
                <li><span>账号数：</span><i class="set-font-color stat-account-cnt">0</i>个　<em>｜</em></li>
                <li><span>预计总阅读量：</span><i class="stat-total-read-num">0</i>　<em>｜</em></li>
                <li><span>总粉丝数：</span><i class="stat-total-follower-num">0</i>　<em>｜</em></li>
                <li><span>本次活动预计总投放金额：</span><i class="stat-total-retail-price">0</i>元</li>
            </ul>
            <p class="set-font-color">注：本次只需支付直接投放账号金额，预约原创需求，我们将尽快核实并与您沟通</p>
        </div>
        <div class="data-r fr">
            <div class="pay-sum">
                <span>需支付金额：</span>
                <i class="set-font-color stat-total-retail-price-to-pay">0</i>
                <em>元</em>
            </div>
            <div class="has-pay-sum">
                <span>已支付金额：</span>
                <i class="set-font-color stat-total-retail-price-has-pay">0</i>
                <em>元</em>
            </div>
            <div class="data-btn">
                <a href="javascript:void(0)" class="btn btn-danger btn-add-more-media">继续添加账号</a>
                <a href="javascript:void(0)" class="btn btn-danger btn-to-pay">提交并支付</a>
            </div>
        </div>
    </div>
</div>

<!-- 效果截图modal-->
<div id="modal-show-effect-shots" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">效果截图</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="effect-shots-pic clearfix">
                    <span class="fl">截图：</span>
                    <div class="pic-show fl">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 执行链接modal-->
<div id="modal-show-execute-link" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">执行链接</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="link-address clearfix">
                    <span class="fl">链接：</span>
                    <a class="fl" href="#">www.baidu.com</a>
                </div>
                <div class="effect-shots-pic clearfix">
                    <span class="fl">截图：</span>
                    <div class="pic-show fl">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 确认执行链接modal-->
<div id="modal-to-verify-execute-link" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">执行链接</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="link-address">
                    <span>链接：</span>
                    <a href="#">www.baidu.com</a>
                </div>
                <div class="effect-shots-pic clearfix">
                    <span class="fl">截图：</span>
                    <div class="pic-show fl">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                        <img src="../../src/images/demo.jpg" alt="" height="94px" width="74px">
                    </div>
                </div>
                <ul class="radio-select clearfix">
                    <li><i class="selected"></i><span>确认</span></li>
                    <li><i></i><span>反馈</span></li>
                </ul>
                <div class="feedback">
                    <span>反馈：</span>
                    <textarea name="" class="form-control"></textarea>
                    <ul class="clearfix">
                        <li class="fl">如有问题请联系客服</li>
                        <li class="fr">您还可以输入<i class="count-num red">100</i>字</li>
                    </ul>
                </div>
                <button class="btn btn-danger submit-btn">提交</button>
            </div>
        </div>
    </div>
</div>
<!-- 原创约稿执行前的详情modal-->
<div id="modal-arrange-order-detail" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">详情</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="active-name clearfix">
                    <span class="fl">活动名称：</span>
                    <span class="active-name-show fl">双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划双十一计划</span>
                </div>
                <div class="carry-out-time">
                    <span>预计执行时间：</span>
                    <span>2018.11.09&nbsp;11:20</span>
                </div>
                <div class="requirements">
                    <span>预约需求：</span>
                    <div class="requirements-con"></div>
                </div>
                <div class="attach-file clearfix">
                    <span class="fl">附件：</span>
                    <ul class="clearfix fl">
                        <li>文件名称</li>
                        <li>文件名称</li>
                        <li>文件名称文件名称</li>
                    </ul>
                </div>
                <div class="feedback-time">
                    <span>预计反馈时间：</span>
                    <span>2018.11.09&nbsp;11:20</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 原创约稿执行中的详情modal-->
<div id="modal-arrange-order-more-detail" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">详情</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="activ-name">
                    <span class="fl">活动名称：</span>
                    <span class="active-name-show fl">双十一计划</span>
                </div>
                <div class="carry-out-time">
                    <span>执行时间：</span>
                    <span>2018.11.09&nbsp;11:20</span>
                </div>
                <div class="carry-out-sum">
                    <span>执行金额：</span>
                    <span>436537</span>
                </div>
                <div class="requirements">
                    <span>预约需求：</span>
                    <div class="requirements-con"></div>
                </div>
                <div class="attach-file clearfix">
                    <span class="fl">附件：</span>
                    <ul class="clearfix fl">
                        <li>文件名称</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 直投订单详情modal-->
<div id="modal-direct-order-detail" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">详情</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="activ-name clearfix">
                    <span class="fl">活动名称：</span>
                    <span class="active-name-show fl">双十一计划</span>
                </div>
                <div class="carry-out-time">
                    <span>执行时间：</span>
                    <span>2018.11.09&nbsp;11:20</span>
                </div>
                <div class="article-to-lead clearfix">
                    <span class="fl">文章导入：</span>
                    <a class="fl" href="#">www.baidu.com</a>
                </div>
                <div class="title clearfix">
                    <span class="fl">标题：</span>
                    <span class="title-name fl">无</span>
                </div>
                <div class="author">
                    <span>作者：</span>
                    <span>美婷婷</span>
                </div>
                <div class="cover-pic">
                    <span>封面图片：</span>
                    <button class="btn btn-danger">查看</button>
                    <p>封面图片显示在正文中</p>
                </div>
                <div class="text-content requirements">
                    <span>正文内容：</span>
                    <div class="text-content requirements-con"></div>
                </div>
                <div class="org-text clearfix">
                    <span class="fl">原文链接：</span>
                    <a class="fl" href="#">http://wom.51wom.local/index.php?r=ad-owner%2Fweixin-plan%2Fconfirm&plan_uuid=1480908421vXz971S</a>
                </div>
                <div class="abstract clearfix">
                    <span class="fl">摘要：</span>
                    <p class="fl">无</p>
                </div>
                <div class="prove-quality attach-file clearfix">
                    <span class="fl">证明品质：</span>
                    <ul class="clearfix fl">
                        <li>文件名称</li>
                    </ul>
                </div>
                <div class="curry-on-remarks clearfix">
                    <span class="fl">投放备注：</span>
                    <p class="fl">无</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 原因展示modal-->
<div id="modal-invalid-order-info" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">原因</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body">
                <div class="reason-con clearfix">
                    <span class="modal-body-title reason fl">原 因：</span>
                    <div class="reason-show fl"></div>
                </div>
            </div>
        </div>
    </div>
</div>

