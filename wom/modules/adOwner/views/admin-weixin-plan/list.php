<?php
/**
 * 活动列表
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/30/16 15:09
 */

use common\models\AdWeixinPlan;
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\MediaHelper;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/plan-manage.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');

$this->title = '微信计划管理';

$weixinPlanJs = <<<JS

// 新建微信活动
$('.weixin-plan-manage-stage').on('click', '.form-plan-search .btn-create', function(){
    var weixin_plan_create_url = $('#id-weixin-plan-create-url').val();
    window.location.href = weixin_plan_create_url;
});

// 进入活动详情页
$('.weixin-plan-manage-stage').on('click', '.weixin-plan-table .plan-detail', function(){
    var plan_detail_url = $(this).attr('data-url');
    window.location.href = plan_detail_url;
});
// 进入活动详情页
$('.weixin-plan-manage-stage').on('click', '.weixin-plan-table .plan-name', function(){
    $(this).closest('tr').find('.plan-detail').trigger('click');
});

JS;
$this->registerJs($weixinPlanJs);

?>
<?php $this->beginBlock('level-1-nav'); ?>
活动管理
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
微信活动
<?php $this->endBlock(); ?>

<input id="id-weixin-plan-create-url" type="hidden"
       value="<?= Url::to(['/ad-owner/weixin-plan/create', 'route' => 1]) ?>">

<!--右侧内容-->
<div class="content fr weixin-plan-manage-stage">

    <!--pjax开始-->
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?php
    $pjax_js = <<<JS
            // 选择下拉活动状态
            $('.plan-status-select-area .plan-status-select').on('click', 'li', selectedOption);
            // 下拉单选择某一个
            function selectedOption(){
                var _text = $(this).text();
                var _status = $(this).data('status');
                $(".form-plan-search input[name=plan-status]").val(_status);
                $(this).parent().prev().find('span:eq(0)').text(_text);
            }
            $('.plan-status-select-area .plan-status-select li').each(function(){
                var _status = $(".form-plan-search input[name=plan-status]").val();
                if($(this).data('status') == _status){
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
                $(".form-plan-search").submit();
            });
            //~~~~~~判断有无资源~~~~~~
            function isResource(){
                var resourceLength =  $(".table tbody").children("tr").length;
                if(resourceLength < 1){
                    $(".no-plan").css("display","block");
                }else{
                    $(".no-plan").css("display","none");
                }
            }
            isResource();
JS;
    $this->registerJS($pjax_js);
    ?>
    <!--搜需条件-->
    <div class="con-top shadow clearfix">
        <?= Html::beginForm(Url::to(['/ad-owner/admin-weixin-plan/list']), 'post', ['data-pjax' => '', 'class' => 'form-plan-search']); ?>
        <input class="plan-status" type="hidden" name="plan-status" value="<?php echo Yii::$app->request->post('plan-status', -1); ?>">
        <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">

        <span class="fl active-status">活动状态 :</span>
        <div class="dropdown fl plan-status-select-area">
            <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="show-default fl">全部活动</span>
                <span class="caret fr"></span>
            </div>
            <ul class="dropdown-menu plan-status-select" role="menu">
                <li data-status="-1">不限</li>
                <li data-status="3">已完成</li>
                <li data-status="2">执行中</li>
                <li data-status="1">待支付</li>
                <li data-status="0">待提交</li>
            </ul>
        </div>

        <input type="hidden" class="selected-plan-status" value="<?php echo Yii::$app->request->post('selected-plan-status', '-1'); ?>">
        <div class="condition-area fl font-14 font-500">
            活动名称 : <input name="plan-name" type="text" value="<?php echo Yii::$app->request->post('plan-name', ''); ?>" placeholder="输入活动名称">
        </div>

        <div class="condition-area fl font-14 font-500">公众号名称 :
            <input name="media-name" type="text" value="<?php echo Yii::$app->request->post('media-name', ''); ?>" placeholder="输入公众号名称/ID">
        </div>

        <button type="submit" class="btn btn-danger bg-main btn-search"><i></i>搜索</button>
        <button class="btn btn-danger bg-main btn-create"><i></i>创建活动</button>
        <?= Html::endForm() ?>
    </div>

    <!-- 搜索列表页 -->
    <div class="weixin-plan-table table shadow">
        <table>
            <thead>
            <tr>
                <th>活动名称</th>
                <th>总粉丝量 (万)</th>
                <th>预计投放总金额</th>
                <th>创建时间</th>
                <th>支付状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($dataResult)) {
                foreach ($dataResult as $key => $row) {
                    ?>
                    <?php $payStatusCount = MediaHelper::getPlanPayStatus($row['plan_uuid']) ?>
                    <tr data-uuid="<?= $row['plan_uuid'] ?>">
                        <td width="240">
                            <a class="plan-name" href="javascript:void(0)">
                                <?= $row['plan_name'] ?>
                            </a>
                        </td>
                        <td><?= ceil($row['total_follower_num'] / 10000) ?></td>
                        <td><?= $row['total_price_amount_max'] ?></td>
                        <td><?= date('Y-m-d H:i', $row['create_time']) ?></td>
                        <td><?= $payStatusCount['orderCount']."/".$payStatusCount['payCount']?></td>
                        <td>
                            <a class="manage plan-detail" href="javascript:void(0)" data-url="<?= Url::to(['/ad-owner/admin-weixin-order/list', 'plan_uuid' => $row['plan_uuid']]) ?>">详情</a>
                            <?php if ($row['status'] == AdWeixinPlan::STATUS_TO_PUBLISH) { ?>
                                <a class="manage" href="<?= Url::to(['/ad-owner/admin-weixin-plan/update', 'plan_uuid' => $row['plan_uuid']]) ?>">修改</a>
                            <?php } ?>
                            <?php if ($row['status'] == AdWeixinPlan::STATUS_TO_PAY) { ?>
                                <a class="manage" href="<?= Url::to(['/ad-owner/weixin-plan/pay-confirm', 'plan_uuid' => $row['plan_uuid']]) ?>">支付</a>
                            <?php } ?>
                            <?php if ($row['status'] == AdWeixinPlan::STATUS_IN_PROGRESS || $row['status'] == AdWeixinPlan::STATUS_FINISH) { ?>
                                <a class="manage" href="<?= Url::to(['/ad-owner/admin-weixin-report/list', 'plan_uuid' => $row['plan_uuid']]) ?>">查看报告</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php }
            } else { ?>
            <?php } ?>
            </tbody>
        </table>
        <div class="no-plan">暂无活动</div>
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

