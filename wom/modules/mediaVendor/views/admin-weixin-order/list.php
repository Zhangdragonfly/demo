<?php
/**
 * 订单列表
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/9/16 11:17
 */

use wom\assets\AppAsset;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\AdWeixinOrder;
use common\helpers\MediaHelper;
use common\models\UserAccount;
use common\helpers\ExternalFileHelper;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/datetimepicker/jquery.datetimepicker.css');
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/media-vendor-user-admin/order-manage/weixin-order-list.css');

AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/plupload/plupload.full.min.js');
AppAsset::addScript($this, '@web/dep/js/wom-uploader.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/media-vendor-user-admin/order-manage/weixin-order-list.js');
$this->title = '微信订单管理';
$js = <<<JS
    pjaxRef();
JS;
$this->registerJs($js);
?>
<?php $this->beginBlock('level-1-nav'); ?>订单管理<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>微信<?php $this->endBlock(); ?>


<!-- 拒单 -->
<input type="hidden" id="refuse-order-url" value="<?= Url::to(['refuse-order']) ?>">
<!-- 接单 -->
<input type="hidden" id="accept-order-url" value="<?= Url::to(['/media-vendor/admin-weixin-order/accept-order']) ?>">
<!-- 执行链接 -->
<input type="hidden" id="submit-execute-link-url" value="<?= Url::to(['/media-vendor/admin-weixin-order/submit-execute-link']) ?>">
<!-- 获取执行链接的反馈 -->
<input type="hidden" id="get-order-feedback-url" value="<?= Url::to(['get-order-feedback']) ?>">
<!-- 效果截图 -->
<input type="hidden" id="submit-effect-shots-url" value="<?= Url::to(['/media-vendor/admin-weixin-order/submit-effect-shots']) ?>">
<!-- 获取(直接投放)详情 -->
<input type="hidden" id="get-direct-order-detail-url" value="<?= Url::to(['get-direct-order-detail']) ?>">
<!-- 获取原因 -->
<input type="hidden" id="get-order-reason-url" value="<?= Url::to(['/media-vendor/admin-weixin-order/get-order-reason']) ?>">
<!-- 查看报告 -->
<input type="hidden" id="show-report-url" value="<?= Url::to(['/ad-owner/admin-weixin-report/detail', 'order_uuid' => '_order_uuid_']) ?>">

<!-- 获取本地图片路径 -->
<input type="hidden" id="id-external-file-url" value="<?= ExternalFileHelper::getPlanOrderRelativeDirectory(); ?>">


<!-- csrf -->
<input type="hidden" id="csrf" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken(); ?>"/>
<!-- 删除图片 -->
<input type="hidden" id="id-delete-file-url" value="<?= Url::to(['/site/file-uploader/delete-file', 'cate_code' => 'order']) ?>">
<!-- 上传图片 -->
<input type="hidden" id="id-upload-file-url" value="<?= Url::to(['/site/file-uploader/upload', 'cate_code' => 'order']) ?>">


<!--右侧内容-->
<div class="content fr">

    <?php Pjax::begin(); ?>

    <div class="con-top shadow clearfix search-area">
        <span class="order-status fl">订单状态：</span>
        <div class="dropdown fl">
            <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="show-default fl">全部</span>
                <span class="caret fr"></span>
            </div>
            <ul class="dropdown-menu" role="menu">
                <li>全部</li>
                <li>待接单</li>
                <li>已拒单</li>
                <li>已流单</li>
                <li>已取消</li>
                <li>已完成</li>
                <li>待执行链接</li>
                <li>待效果截图</li>
                <li>待审核</li>
                <li>审核失败</li>
                <li>执行中</li>
            </ul>
        </div>

        <div class="search-quick clearfix fl">
            <div class="dropdown fl">
                <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="show-default fl">投放账号/ID:</span>
                    <span class="caret fr"></span>
                </div>
                <ul class="dropdown-menu" role="menu">
                    <li>投放账号/ID:</li>
                    <li>活动名称:</li>
                </ul>
            </div>
            <input class="form-control fl" type="text">
        </div>

        <div class="order-time fl font-14 font-500">
            投放时间 :
            <input type="text" id="order-start-time" class="input-section text-input datetimepicker" readonly="readonly"/>
            <span class="line"></span>
            <input type="text" id="order-end-time" class="input-section text-input datetimepicker" readonly="readonly"/>
        </div>

        <button type="submit" class="btn btn-danger bg-main btn-search"><i></i>搜索</button>
    </div>

    <!-- 搜索列表页 -->
    <div class="order-table table shadow">
        <table>
            <thead>
            <tr>
                <th>订单ID</th>
                <th>投放账号</th>
                <th>投放位置</th>
                <th>投放金额（元）</th>
                <th>投放时间</th>
                <th>订单状态</th>
                <th>订单名称</th>
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
                            <a class="order-uuid" href="#"><?= $row['order_uuid'] ?></a>
                            <div class="order-config" style="display: none">
                                <?php
                                echo json_encode([
                                    'order_uuid' => $row['order_uuid'],
                                    'pub_type' => $row['pub_type'],
                                    'pos_code' => $row['pos_code'],
                                    'order_status' => $row['order_status']
                                ]);
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
                                    <a class="account-name plain-text-length-limit" href="#"
                                       data-limit="7"><?= $row['weixin_name'] ?></a>
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
                            <?= MediaHelper::getWeixinOrderStatusLabel(UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR, $row['order_status'], $row['pub_type']); ?>
                        </td>
                        <td>
                            <?= $row['plan_name'] ?>
                        </td>
                        <td>
                            <!--待接单操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT) { ?>
                                <a href="javascript:void(0)" class="btn-accept-order" data-order-uuid="<?= $row['order_uuid'] ?>">接单</a><br>
                                <a href="javascript:void(0)" class="btn-refuse-order" data-order-uuid="<?= $row['order_uuid'] ?>">拒单</a><br>
                            <?php } ?>

                            <!--待执行链接操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_LINK) { ?>
                                <a href="javascript:void(0)" class="btn-submit-execute-link" data-order-uuid="<?= $row['order_uuid'] ?>">提交</a><br>
                            <?php } ?>

                            <!--待效果截图操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_RESULT) { ?>
<!--                                <a href="javascript:void(0)" class="btn-submit-effect-shots">提交</a><br>-->
                            <?php } ?>

                            <!--确认执行反馈操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_TO_CONFTRM_FEEDBACK) { ?>
                                <a href="javascript:void(0)" class="btn-execute-feedback" data-order-uuid="<?= $row['order_uuid'] ?>">执行反馈</a><br>
<!--                                <a href="javascript:void(0)" class="btn-submit-effect-shots">提交</a><br>-->
                            <?php } ?>

                            <!--确认执行操作-->
                            <?php if ($row['order_status'] == AdWeixinOrder::ORDER_STATUS_CONFIRM_LINK) { ?>
                                <a href="javascript:void(0)" class="btn-submit-effect-shots" data-order-uuid="<?= $row['order_uuid'] ?>">提交</a><br>
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
                                <a href="javascript:void(0)" class="btn-view-report" data-order-uuid="<?= $row['order_uuid'] ?>">查看报告</a><br>
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

<!-- 接单modal -->
<div id="modal-accept-order" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" class="order-uuid" value="">
            <div class="modal-header clearfix">
                <span class="modal-title red fl">接单</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
            <div class="modal-body clearfix">
                <h4>您确定接受执行该订单?</h4>
                <div class="btn-opetate-order">
                    <button class="btn btn-danger bg-main btn-accept" data-dismiss="modal">确认</button>
                    <button class="btn btn-danger bg-main btn-close" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 拒单modal -->
<div id="modal-refuse-order" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" class="order-uuid" value="">
            <div class="modal-header clearfix">
                <span class="modal-title red fl">拒单</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
            <div class="modal-body clearfix">
                <div class="refuse-reason-section">
                    <span class="refuse-reason fl">拒单原因:</span>
                    <textarea class="refuse-reason-textarea" name="refuse-reason" id="" cols="30" rows="10" maxlength="30"></textarea>
                    <p class="tips message">您还可以输入 <em>30</em> 字</p>
                </div>
                <div class="btn-opetate-order">
                    <button class="btn btn-danger bg-main btn-refuse">确认</button>
                    <button class="btn btn-danger bg-main btn-close" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 提交执行链接 -->
<div id="modal-submit-execute-link" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" class="order-uuid" value="">
            <div class="modal-header clearfix">
                <span class="modal-title red fl">提交执行链接</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
            <div class="modal-body clearfix">
                <div class="area-execute-link">
                    <span>链接：</span>
                    <input class="input-execute-link" type="text" placeholder="请输入执行链接">
                </div>
                <p class="tips"></p>
                <div class="screenshot clearfix">
                    <span class="fl">截图：</span>
                    <div id="upload-single-img-container" class="plupload-container fl">
                        <button id="id-upload-001-btn" class="choose-screen-shot fl" for="id-upload-001-preview-area"></button>
                        <div class="fl">
                            <span class="img-size-tips">请上传2张截图,大小不能超过2M</span>
                            <a class="view-example color-main" href="#modal-view-example" data-toggle="modal">查看示例</a>
                        </div>
                    </div>
                </div>
                <div id="id-upload-001-preview-area" class="upload-preview-area"></div>
            </div>
            <button class="btn btn-danger bg-main btn-submit-execute-link">提交</button>
        </div>
    </div>
</div>

<!-- 执行链接modal-->
<div id="modal-show-execute-link" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="modal-title red fl">执行链接</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="link-address">
                    <span>链接：</span>
                    <a href="#">www.baidu.com</a>
                </div>
                <div class="all-pic-show clearfix">
                    <span class="fl">截图：</span>
                    <div class="pic-show fl">
                        <img src="../../src/images/wzq.jpg" alt="" height="94px" width="74px">
                        <img src="../../src/images/wzq.jpg" alt="" height="94px" width="74px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 执行反馈modal-->
<div id="modal-resubmit-execute-link" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="modal-title red fl">执行反馈</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="feedback-con">
                    <span class="modal-body-title fl">反馈内容：</span>
                    <textarea readonly name="" cols="30" rows="10"></textarea>
                </div>
                <div class="link-address">
                    <span class="modal-body-title">重新提交链接：</span><input class="link-address-input" type="text">
                </div>
                <p class="tips"></p>
                <div class="screenshot clearfix">
                    <span class="modal-body-title fl">重新提交截图：</span>
                    <button id="id-upload-003-btn" class="choose-screen-shot fl" for="id-upload-003-preview-area"></button>
                    <div class="fl">
                        <a class="view-example color-main" href="#modal-view-example" data-toggle="modal">查看示例</a>
                    </div>
                </div>
                <div id="id-upload-003-preview-area" class="upload-preview-area"></div>
            </div>
            <button class="execute-link-submit-btn btn btn-danger bg-main">提交</button>
        </div>
    </div>
</div>

<!-- 提交效果modal -->
<div id="modal-submit-effect-shots" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" class="order-uuid" value="">
            <div class="modal-header clearfix"><span class="modal-title red fl">提交效果截图</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="screenshot clearfix">
                    <span class="fl">截图：</span>
                    <button id="id-upload-002-btn" class="upload-multiple-img-btn choose-screen-shot fl" for="id-upload-002-preview-area"></button>
                    <div class="fl">
                        <span class="img-size-tips">图片大小不能超过2M</span>
                        <a class="view-example color-main" href="#modal-view-example" data-toggle="modal">查看示例</a>
                    </div>
                </div>
                <div id="id-upload-002-preview-area" class="upload-preview-area"></div>

            </div>
            <button class="btn btn-danger bg-main btn-submit-effect-shots">提交</button>
        </div>
    </div>
</div>

<!-- 效果截图modal-->
<div id="modal-show-effect-shots" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="modal-title red fl">效果截图</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="all-pic-show clearfix">
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

<!-- 详情modal-->
<div id="modal-direct-order-detail" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="modal-title red fl">详情</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="order-name clearfix">
                    <span class="fl">订单名称：</span>
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
                    <span class="title-name fl"></span>
                </div>
                <div class="author">
                    <span>作者：</span>
                    <span class="author-name">无</span>
                </div>
                <div class="cover-pic">
                    <span>封面图片：</span>
                    <button class="btn-view-cover-pic btn btn-danger">查看</button>
                    <img class="view-cover-pic" src="" alt="">
                    <p>封面图片显示在正文中</p>
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
                    <span class="fl">正品证明：</span>
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

<!-- 原因modal-->
<div id="modal-invalid-order-info" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <span class="modal-title red fl">原因</span><i class="close fr" data-dismiss="modal">X</i>
            </div>
            <div class="modal-body">
                <div class="reason-con clearfix">
                    <span class="modal-body-title reason fl">原 因：</span>
                    <div class="reason-area fl"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 原创约稿详情modal(待审核)-->
<div id="modal-arrange-order-detail" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="modal-title red fl">详情</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="order-name">
                    <span>订单名称：</span><span>双十一计划</span>
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

<!-- 原创约稿详情modal(执行中)-->
<div id="modal-arrange-order-more-detail" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="modal-title red fl">详情</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <div class="order-name">
                    <span>订单名称：</span><span>双十一计划</span>
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

<!-- 查看示例图片modal-->
<div id="modal-view-example" class="modal modal-message fade in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="modal-title red fl">查看示例图片</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body clearfix">
                <img src="../../src/images/view-example.jpg" alt="查看截图示例图片">
            </div>
        </div>
    </div>
</div>
