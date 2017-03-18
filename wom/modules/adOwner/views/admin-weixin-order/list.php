<?php
/**
 * 订单列表
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/6/16 18:33
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\MediaHelper;
use common\models\AdWeixinOrder;
use common\models\UserAccount;
use common\helpers\ExternalFileHelper;


//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/datetimepicker/jquery.datetimepicker.css');
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/order-manage/weixin-order-list.css');

AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/pjax-common.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/weixin-order-list.js');
$this->title = '微信订单管理';
$js = <<<JS
    pjaxRef();
JS;
$this->registerJs($js);
?>
<?php $this->beginBlock('level-1-nav'); ?>订单管理<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>微信<?php $this->endBlock(); ?>


<!-- 获取本地图片路径 -->
<input type="hidden" id="id-external-file-url" value="<?= ExternalFileHelper::getPlanOrderRelativeDirectory(); ?>">
<!-- 获取执行链接 -->
<input type="hidden" id="get-execute-link-url" value="<?= Url::to(['/ad-owner/admin-weixin-order/get-execute-link']) ?>">
<!-- 确认执行链接 -->
<input type="hidden" id="to-verify-execute-link-url" value="<?= Url::to(['/ad-owner/admin-weixin-order/to-verify-execute-link']) ?>">
<!-- 反馈执行链接 -->
<input type="hidden" id="feedback-execute-link-url" value="<?= Url::to(['/ad-owner/admin-weixin-order/feedback-execute-link']) ?>">
<!-- 获取(直接投放)详情 -->
<input type="hidden" id="get-direct-order-detail-url" value="<?= Url::to(['/ad-owner/admin-weixin-order/get-direct-order-detail']) ?>">
<!-- 获取原因 -->
<input type="hidden" id="get-order-reason-url" value="<?= Url::to(['/ad-owner/admin-weixin-order/get-order-reason']) ?>">
<!-- 编辑直接投放内容 -->
<input id="id-edit-arrange-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/edit-arrange-content', 'plan_action' => 'create', 'plan_uuid' => '_plan_uuid_', 'order_uuid' => '_order_uuid_', 'pos_code' => '_pos_code_']) ?>">
<!-- 编辑原创约稿内容 -->
<input id="id-edit-direct-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/edit-direct-content', 'plan_action' => 'pay', 'plan_uuid' => '_plan_uuid_', 'order_uuid' => '_order_uuid_', 'pos_code' => '_pos_code_']) ?>">
<!-- 取消订单 -->
<input type="hidden" id="cancel-order-url" value="<?= Url::to(['/ad-owner/admin-weixin-order/cancel-order']) ?>">
<!-- 查看报告 -->
<input type="hidden" id="show-report-url" value="<?= Url::to(['/ad-owner/admin-weixin-report/detail', 'order_uuid' => '_order_uuid_']) ?>">

<!--右侧内容-->
<div class="content fr">
    <!--pjax开始-->
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?php
    $pjax_js = <<<JS
            // 选择下拉订单状态
            $('.order-status-select-area .order-status-select').on('click', 'li', function(){
                var _text = $(this).text();
                var _status = $(this).data('status');
                $(".form-order-search input[name=order-status]").val(_status);
                $(this).parent().prev().find('span:eq(0)').text(_text);
            });
            $('.order-status-select-area .order-status-select li').each(function(){
                var _status = $(".form-order-search input[name=order-status]").val();
                if($(this).data('status') == _status){
                    $(this).parent().prev().find('span:eq(0)').text($(this).text());
                }
            });
            // 二级选择
            $('.condition-area .secondary-select-option-area .secondary-select-option').on('click', 'li', function(){
                var _text = $(this).text();
                var _option = $(this).data('option');
                $(".form-order-search input[name=secondary-select-option]").val(_option);
                $(this).parent().prev().find('span:eq(0)').text(_text);
            });
            $('.condition-area .secondary-select-option-area .secondary-select-option li').each(function(){
                var _option = $(".form-order-search input[name=secondary-select-option]").val();
                if($(this).data('option') == _option){
                    $(this).parent().prev().find('span:eq(0)').text($(this).text());
                }
            });
            // 分页
            $(".pagination li a").each(function () {
                $(this).removeAttr("href");
                $(this).attr("style", "cursor: pointer;");
            });
            $(".pagination li.disabled").each(function () {
                var label_text = $(this).text();
                $(this).find('span').after('<a>' + label_text + '</a>');
                $(this).find('span').remove();
            });
            // 分页处理
            $(".pagination li a").click(function () {
                $("input.page").attr("value", $(this).attr("data-page"));
                $(".form-order-search").submit();
            });
            //限制字符串长度
            plainContentLengthLimit();
            //表格二维码图片显示
            $('.content').find('.table .ewm').hover(function () {
                $(this).siblings('img').css({display: 'block'});
            }, function () {
                $(this).siblings('img').css({display: 'none'});
            });
            //鼠标放上去显示完整信息ID篇
            $("a[data-title]").each(function() {
                var a = $(this);
                var title = a.attr('data-title');
                if (title == undefined || title == "") return;
                a.data('data-title', title).hover(function () {
                        var offset = a.offset();
                        $("<div class='show-all-info'>"+title+"</div>").appendTo($("table")).css({ top: offset.top + a.outerHeight() -278, left: a.outerWidth() + 240}).fadeIn();
                    },function(){ 
                        $(".show-all-info").remove();
                    }
                );
            });
             //~~~~~~判断有无资源~~~~~~
            function isResource(){
                var resourceLength =  $(".table tbody").children("tr").length;
                if(resourceLength < 1){
                    $(".no-order").css("display","block");
                }else{
                    $(".no-order").css("display","none");
                }
            }
            isResource();

JS;
    $this->registerJS($pjax_js);
    ?>
    <!-- 搜索条件 -->
    <div class="con-top shadow clearfix">

        <?= Html::beginForm(Url::to(['/ad-owner/admin-weixin-order/list']), 'post', ['data-pjax' => '', 'class' => 'form-order-search']); ?>

        <input class="order-status" type="hidden" name="order-status" value="<?php echo Yii::$app->request->post('order-status', -11); ?>">
        <input class="secondary-select-option" type="hidden" name="secondary-select-option" value="<?php echo Yii::$app->request->post('secondary-select-option', 'account'); ?>">
        <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">

        <div class="condition-area clearfix fl">
            <span class="order-status fl">订单状态：</span>
            <div class="dropdown fl order-status-select-area">
                <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="show-default fl">不限</span>
                    <span class="caret fr"></span>
                </div>
                <ul class="dropdown-menu order-status-select" role="menu">
                    <li data-status="-11">不限</li>
                    <li data-status="-1">待提交</li>
                    <li data-status="0">待支付</li>
                    <li data-status="1">待接单</li>
                    <li data-status="2">已拒单</li>
                    <li data-status="3">已流单</li>
                    <li data-status="4">已取消</li>
                    <li data-status="5">已完成</li>
                    <li data-status="22">待执行链接</li>
                    <li data-status="24">待效果截图</li>
                    <li data-status="27">待审核</li>
                    <li data-status="28">审核失败</li>
                    <!--                <li data-status="">执行中</li>-->
                </ul>
            </div>
        </div>

        <div class="condition-area search-quick clearfix fl">
            <div class="dropdown fl secondary-select-option-area">
                <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="show-default fl">投放账号/ID</span>
                    <span class="caret fr"></span>
                </div>
                <ul class="dropdown-menu secondary-select-option" role="menu">
                    <li data-option="account">投放账号/ID</li>
                    <li data-option="plan-name">活动名称</li>
                </ul>
            </div>
            <input type="text" class="form-control fl" name="secondary-select-value" value="<?php echo Yii::$app->request->post('secondary-select-value', ''); ?>">
        </div>

        <div class="condition-area order-time fl font-14 font-500">
            投放时间 :
            <input type="text" id="order-start-time" class="input-section text-input datetimepicker" readonly="readonly"/>
            <span class="line"></span>
            <input type="text" id="order-end-time" class="input-section text-input datetimepicker" readonly="readonly"/>
        </div>
        <button class="btn-search btn btn-danger bg-main"><i></i>搜索</button>
        <?= Html::endForm() ?>
    </div>

    <!-- 搜索列表页 -->
    <div class="order-table table shadow">
        <table>
            <thead>
            <tr>
                <th width="130">订单ID</th>
                <th width="180">投放账号</th>
                <th width="115">投放位置</th>
                <th width="115">投放金额（元）</th>
                <th width="115">投放时间</th>
                <th width="115">订单状态</th>
                <th width="150">活动名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($dataResult)) {
                foreach ($dataResult as $key => $row) {
                    ?>
                    <tr data-uuid="<?= $row['order_uuid'] ?>">
                        <td>
                            <a class="order-uuid" href="javascript:;">
                                <?= $row['order_uuid'] ?>
                            </a>
                            <div class="order-config" style="display: none">
                                <?php
                                    echo json_encode([
                                        'order_uuid' => $row['order_uuid'],
                                        'plan_uuid' => $row['plan_uuid'],
                                        'pub_type' => $row['pub_type'],
                                        'pos_code' => $row['pos_code'],
                                        'order_status' => $row['order_status']
                                    ]);
                                ?>
                                <?php
                                    //echo json_encode(['order_uuid' => $row['order_uuid'], 'plan_uuid' => $row['plan_uuid'],'pub_type' => $row['pub_type'], 'pos_code' => $row['pos_code'],
                                        //'operate_action' => MediaHelper::getWeixinOrderOperationList(UserAccount::ACCOUNT_TYPE_AD_OWNER, $row['order_status'], $row['pub_type']), 'order_status' => $row['order_status']]);
                                ?>
                            </div>
                        </td>
                        <td class="order-account clearfix">
                            <dl class="clearfix">
                                <dt class="fl">
                                    <a href="javascript:;">
                                        <img src="http://open.weixin.qq.com/qr/code/?username=<?= $row['weixin_id'] ?>" alt="">
                                    </a>
                                    <i></i>
                                </dt>
                                <dd class="fl">
                                    <a class="account-name plain-text-length-limit" href="javascript:;" data-limit="7" data-title="<?= $row['weixin_name'] ?>"><?= $row['weixin_name'] ?></a>
                                    <div class="ewm-ID">
                                        <i class="ewm"></i>
                                        <em class="plain-text-length-limit" data-limit="7"><?= $row['weixin_id'] ?></em>
                                        <img src="http://open.weixin.qq.com/qr/code/?username=<?= $row['weixin_id'] ?>" alt="" height="50px" width="50px">
                                    </div>
                                </dd>
                            </dl>
                        </td>
                        <td>
                            <?php if ($row['pos_code'] == 'pos_s') { ?>单图文
                            <?php } else if ($row['pos_code'] == 'pos_m_1') { ?>多图文头条
                            <?php } else if ($row['pos_code'] == 'pos_m_2') { ?>多图文2条
                            <?php } else if ($row['pos_code'] == 'pos_m_3') { ?>多图文3-N条
                            <?php } ?>
                        </td>
                        <td><?= $row['price_min'] ?></td>
                        <td>
                            <?php if ($row['pub_type'] == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) { ?>
                                <span class="start-time"><?= date('Y-m-d H:i', $row['publish_start_time']) ?></span>
                            <?php } else { ?>
                                <span class="start-time"><?= date('Y-m-d H:i', $row['publish_start_time']) ?></span>
                                <br/> ~ <br/>
                                <span class="end-time"><?= date('Y-m-d H:i', $row['publish_end_time']) ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <?= MediaHelper::getWeixinOrderStatusLabel(UserAccount::ACCOUNT_TYPE_AD_OWNER, $row['order_status'], $row['pub_type']); ?>
                        </td>
                        <td>
                            <a href="<?= Url::to(['/ad-owner/admin-weixin-plan/list', 'plan_uuid' => $row['plan_uuid']]) ?>"><?= $row['plan_name'] ?></a>
                        </td>
                        <td>
                            <!--待提交操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT) { ?>
                                <a href="javascript:void(0)" class="btn-update-order">修改</a><br>
                                <a href="javascript:void(0)" class="btn-cancel-order">取消</a>
                            <?php } ?>

                            <!--待支付操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_PAY) { ?>
                                <a href="javascript:void(0)" class="btn-pay-order">支付</a><br>
                                <a href="javascript:void(0)" class="btn-cancel-order">取消</a>
                            <?php } ?>

                            <!--待接单操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT) { ?>
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                            <!--待执行链接操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_LINK) { ?>
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                            <!--待效果截图操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_RESULT) { ?>
                                <a href="javascript:void(0)" class="btn-to-verify-execute-link" data-order-uuid="<?= $row['order_uuid'] ?>">确认执行</a><br>
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                            <!--待执行反馈操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_CONFTRM_FEEDBACK) { ?>
                                <a href="javascript:void(0)" class="btn-to-verify-execute-link" data-order-uuid="<?= $row['order_uuid'] ?>">确认执行</a><br>
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                           <!--确认执行操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_CONFIRM_LINK) { ?>
<!--                                <a href="javascript:void(0)" class="btn-show-effect-shots" data-val="1">效果截图</a><br>-->
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                            <!--已取消详情-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_CANCElED) { ?>
<!--                                <a href="javascript:void(0)" class="btn-invalid-order-info" data-order-uuid="--><?//= $row['order_uuid'] ?><!--">原因</a><br>-->
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                            <!--已拒单详情-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_REFUSE) { ?>
                                <a href="javascript:void(0)" class="btn-refuse-order-reason" data-order-uuid="<?= $row['order_uuid'] ?>">原因</a><br>
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                            <!--已流单详情-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_FlOW) { ?>
                                <a href="javascript:void(0)" class="btn-flow-order-reason" data-order-uuid="<?= $row['order_uuid'] ?>">原因</a><br>
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                            <!--已完成详情-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_FINISHED) { ?>
                                <a href="javascript:void(0)" class="btn-show-report" data-order-uuid="<?= $row['order_uuid'] ?>">查看报告</a><br>
                                <a href="javascript:void(0)" class="btn-direct-order-detail" data-order-uuid="<?= $row['order_uuid'] ?>">详情</a>
                            <?php } ?>

                        </td>
                    </tr>
                <?php }
            } else { ?>
            <?php } ?>
            </tbody>
        </table>
        <div class="no-order">暂无订单</div>
    </div>

    <!--分页-->
    <div class="table-footer clearfix">
        <div class="page-wb fl system_page" data-value="<?= $pager->totalCount ?>">
            <?= \yii\widgets\LinkPager::widget([
                'firstPageCssClass' => '',
                'nextPageCssClass' => '',
                'pagination' => $pager,
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                'firstPageLabel' => '首页',
                'lastPageLabel' => '尾页',
                'maxButtonCount' => 5
            ]) ?>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>

<!-- 效果截图modal-->
<div id="modal-show-effect-shots" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <span class="red fl">效果截图</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
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
            <input type="hidden" class="order-uuid" value="">
            <div class="modal-header clearfix">
                <span class="red fl">执行链接</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
            <div class="modal-body clearfix">
                <div class="link-address clearfix">
                    <span class="fl">链接：</span>
                    <a class="fl" href="#"></a>
                </div>
                <div class="effect-shots-pic clearfix">
                    <span class="fl">截图：</span>
                    <div class="pic-show fl">

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
            <input type="hidden" class="order-uuid" value="">
            <div class="modal-header clearfix">
                <span class="red fl">执行链接</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
            <div class="modal-body clearfix">
                <div class="link-address">
                    <span>链接：</span>
                    <a href="#"></a>
                </div>
                <div class="effect-shots-pic clearfix">
                    <span class="fl">截图：</span>
                    <div class="pic-show fl">
                    </div>
                </div>
                <ul class="radio-select clearfix">
                    <li class="confirm-operate"><i class="selected"></i><span>确认</span></li>
                    <li class="feedback-operate"><i></i><span>反馈</span></li>
                </ul>
                <div class="feedback">
                    <span>反馈：</span>
                    <textarea name="" class="form-control feedback-textarea" maxlength="100"></textarea>
                    <ul class="clearfix">
                        <li class="fl">如有问题请联系客服</li>
                        <li class="fr">您还可以输入<i class="count-num red">100</i>字</li>
                    </ul>
                </div>
                <button class="btn btn-danger btn-submit">提交</button>
            </div>
        </div>
    </div>
</div>

<!-- 原创约稿执行前的详情modal-->
<div id="modal-arrange-order-detail" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <span class="red fl">详情</span><i class="close fr" data-dismiss="modal">X</i></div>
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
            <div class="modal-header clearfix">
                <span class="red fl">详情</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
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
            <div class="modal-header clearfix">
                <span class="red fl">详情</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
            <div class="modal-body clearfix">
                <div class="activ-name clearfix">
                    <span class="fl">活动名称：</span>
                    <span class="active-name-show fl"></span>
                </div>
                <div class="carry-out-time">
                    <span>执行时间：</span>
                    <span class="execute-time"></span>
                </div>
                <div class="article-to-lead clearfix">
                    <span class="fl">文章导入：</span>
                    <a class="fl" href="#"></a>
                </div>
                <div class="title clearfix">
                    <span class="fl">标题：</span>
                    <span class="title-name fl">无</span>
                </div>
                <div class="author">
                    <span>作者：</span>
                    <span class="author-name"></span>
                </div>
                <div class="cover-pic">
                    <span>封面图片：</span>
                    <button class="btn-view-cover-pic btn btn-danger">查看</button>
                    <p>封面图片显示在正文中</p>
                    <img class="view-cover-pic" src="" alt="封面图片">
                </div>
                <div class="text-content requirements">
                    <span>正文内容：</span>
                    <div class="text-content requirements-con"></div>
                </div>
                <div class="org-text clearfix">
                    <span class="fl">原文链接：</span>
                    <a class="fl" href="#"></a>
                </div>
                <div class="abstract clearfix">
                    <span class="fl">摘要：</span>
                    <p class="fl">无</p>
                </div>
                <div class="prove-quality attach-file clearfix">
                    <span class="fl">证明品质：</span>
                    <ul class="clearfix fl">

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
            <div class="modal-header clearfix">
                <span class="red fl">原因</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
            <div class="modal-body">
                <div class="reason-con clearfix">
                    <span class="modal-body-title reason fl">原 因：</span>
                    <div class="reason-show fl"></div>
                </div>
            </div>
        </div>
    </div>
</div>
