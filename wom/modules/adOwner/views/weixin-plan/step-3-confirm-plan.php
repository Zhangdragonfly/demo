<?php
/**
 * 填写投放内容
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:38 PM
 */
use wom\assets\AppAsset;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/plan_media_common.css');
AppAsset::addCss($this, '@web/src/css/weixin/plan_media_select.css');

AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/weixin/plan_confirm.js');

$this->title = '确定投放位置及内容';
?>
<!-- 内容部分-->
<input id="id-plan-uuid" type="hidden" value="<?= $planUUID ?>">
<input id="id-weixin-media-list-url" type="hidden" value="<?= Url::to(['/weixin/media/list', 'plan_action' => 'create', 'plan_uuid' => $planUUID]) ?>">
<input id="id-weixin-media-delete-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/delete-media', 'order_uuid' => '_order_uuid_']) ?>">
<input id="id-change-order-pos-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/change-order-pos', 'order_uuid' => '_order_uuid_']) ?>">
<input id="id-edit-arrange-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/edit-arrange-content', 'plan_action' => 'create', 'plan_uuid' => $planUUID, 'order_uuid' => '_order_uuid_', 'pos_code' => '_pos_code_']) ?>">
<input id="id-edit-direct-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/edit-direct-content', 'plan_action' => 'create', 'plan_uuid' => $planUUID, 'order_uuid' => '_order_uuid_', 'pos_code' => '_pos_code_']) ?>">
<input id="id-pay-confirm-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/pay-confirm', 'plan_uuid' => $planUUID]) ?>">
<input id="id-weixin-plan-submit-arrange-order" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/submit-arrange-order']) ?>">
<input id="id-admin-weixin-plan-list-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-plan/list']) ?>">

<div class="create-step">
    <ul class="clearfix">
        <li class="current-step">1.创建活动</li>
        <li class="current-step">2.选择投放账号</li>
        <li class="current-step">3.填写投放内容<i></i></li>
        <li class="last-step">4.提交并付款<i></i></li>
    </ul>
</div>

<div class="source-choosed-wrap">
    <h4>*系统会自动保存填写的内容</h4>
    <div class="source-choosed shadow">
        <table class="thead-title">
            <thead>
            <tr>
                <th width="200px">账号名称</th>
                <th width="200px">投放位置</th>
                <th width="100px">参考价（元）</th>
                <th width="200px">需求添加状态</th>
                <th width="215px">操作</th>
                <th width="220px">接单备注</th>
            </tr>
            </thead>
        </table>
        <div class="table-wrap">
            <table class="source-choosed-table table">
                <tbody>
                <!--
                    后台渲染页面的时候,每个账号都有一个基本信息(json格式)
                    示例如下:
                    {
                                    "head_avg_read_num": 1111,
                                    "total_follower_num": 2222,
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
                 其中:   head_avg_read_num 头条平均阅读数
                         total_follower_num 总粉丝数
                         pos_s 表示单图文,  pos_m_1 表示多图文头条,  pos_m_2 表示多图文2条,  pos_m_3 表示多图文3-N条
                         retail_price 表示零售价(参考价,单位元)
                         has_add_content表示是否已经添加需求(1 已经添加需求, 0 未添加)
                         pub_type发布类型(-1 未设置, 0 不接单,1 只发布, 2 只原创)
                         is_selected 当前位置是否select选中(1 已经选中, 0 未选中)
                -->

                <?php foreach($weixinOrderList as $weixinOrder){ ?>
                    <tr class="one-account">
                        <input class="order-available-pos-config" type="hidden" value='<?= $weixinOrder['order_available_pos_config']; ?>'>
                        <td width="200px" class="area-account-info clearfix">
                            <dl class="clearfix">
                                <dt class="fl">
                                    <a href="#">
                                        <img src="http://open.weixin.qq.com/qr/code/?username=<?= $weixinOrder['real_public_id'] ?>" alt="">
                                    </a>
                                    <i></i>
                                </dt>
                                <dd class="fl">
                                    <a class="account-name plain-text-length-limit" data-limit="5" href="#"><?= $weixinOrder['public_name']; ?></a>
                                    <div class="account-id-area">
                                        <i class="ewm"></i>
                                        <em class="plain-text-length-limit" data-limit="5"><?= $weixinOrder['real_public_id']; ?></em>
                                        <img src="http://open.weixin.qq.com/qr/code/?username=<?= $weixinOrder['real_public_id'] ?>" alt="" height="80px" width="80px">
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

                        <td class="area-retail-price" width="100px">
                            <span class="retail-price"></span>
                        </td>
                        <td width="200px" class="area-req-status">
                            <span class="status-has-add-content set-font-green" style="display: none">已添加需求</span>
                            <span class="status-not-add-content" style="display: none">未添加需求</span>
                        </td>
                        <td width="215px" class="area-operate">
                            <a class="add-content" href="javascript:void(0)" style="display: none">添加需求</a>
                            <a class="update-content" href="javascript:void(0)" style="display: none">修改需求</a>
                            <a class="delete-order" href="javascript:void(0)">删除</a>
                        </td>
                        <td width="220px" class="area-remark">
                            <ul>
                                <li class="one-pos pos-m-1"><span>多图文头条：</span><span class="pub-type-label"></span></li>
                                <li class="one-pos pos-m-2"><span>多图文2条：</span><span class="pub-type-label"></span></li>
                                <li class="one-pos pos-m-3"><span>多图文3-N条：</span><span class="pub-type-label"></span></li>
                                <li class="one-pos pos-s"><span>单图文：</span><span class="pub-type-label"></span></li>
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
                <li><span>账号数：</span><i class="set-font-color stat-account-cnt"></i> 个　<em>｜</em></li>
                <li><span>预计总阅读量：</span><i class="stat-total-read-num"></i>　<em>｜</em></li>
                <li><span>总粉丝数：</span><i class="stat-total-follower-num"></i>　<em>｜</em></li>
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
            <div class="data-btn">
                <a href="javascript:void(0)" class="btn btn-danger btn-add-more-media">继续添加账号</a>
                <a href="javascript:void(0)" class="btn btn-danger btn-to-pay">提交并支付</a>
            </div>
        </div>
    </div>
</div>
