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

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/datetimepicker/jquery.datetimepicker.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/order-manage/weibo-order-list.css');

AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/pjax-common.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/weibo-order-list.js');
$this->title = '微博订单管理';
?>
<!--面包屑-->
<?php $this->beginBlock('level-1-nav'); ?>订单管理<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>微博<?php $this->endBlock(); ?>

<div class="content fr">

    <!--页面参数--->
    <input id="id-check-plan-desc-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-order/detail']) ?>">

    <!--pjax开始-->
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?php
    $js = <<<JS
    pjaxRefOrder();
    // 分页样式
    $(".pagination li a").each(function () {
        $(this).removeAttr("href");
        $(this).attr("style", "cursor: pointer;");
    });
    $(".pagination li.disabled").each(function () {
        var label_text = $(this).text();
        $(this).find('span').after('<a>' + label_text + '</a>');
        $(this).find('span').remove();
    });
    //分页处理
    $(".pagination li a").click(function () {
        $("input.page").attr("value", $(this).attr("data-page"));
        $(".form-order-search").submit();
    });
    plainContentLengthLimit();
JS;
    $this->registerJS($js);
    ?>

    <!--搜索表单-->
    <?= Html::beginForm([''], 'post', ['data-pjax' => '', 'class' => 'form-inline form-order-search', 'id' => 'form-order-search', 'autocomplete' => "off"]); ?>
    <div class="con-top shadow clearfix">

        <input class="order_status" type="hidden" name="order_status" value="<?php echo Yii::$app->request->post('order_status',-1); ?>">
        <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">

        <div class="dropdown fl">
            <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="show-default fl">订单状态</span>
                <span class="caret fr"></span>
            </div>
            <ul class="dropdown-menu dropdown-order-status" role="menu">
                <li data-status="0">未提交</li>
                <li data-status="1">预约中</li>
                <li data-status="2">已完成</li>
            </ul>
        </div>
        <div class="condition-area fl font-14 font-500">
            预约账号 : <input type="text" name="weibo_name" value="<?php echo Yii::$app->request->post('weibo_name', ''); ?>"placeholder="请输入预约微博号">
        </div>
        <div class="condition-area order-time fl font-14 font-500">
            预约投放时间 :
            <input type="text" id="order-start-time" name="execute_start_time" value="<?php echo Yii::$app->request->post('execute_start_time', ''); ?>"class="input-section text-input datetimepicker" readonly="readonly" placeholder="请选择开始时间"/>
            <span class="line"></span>
            <input type="text" id="order-end-time" name="execute_end_time" value="<?php echo Yii::$app->request->post('execute_end_time', ''); ?>"class="input-section text-input datetimepicker" readonly="readonly" placeholder="请选择结束时间"/>
        </div>
        <button type="submit" class="btn btn-danger bg-main btn-search"><i></i>搜索</button>
        <a href="<?=yii::$app->urlManager->createUrl(array("/ad-owner/weibo-plan/create-plan-order"))?>" class="btn-create btn btn-danger bg-main"><i></i>创建预约单</a>
    </div>
    <?= Html::endForm() ?>

    <!--搜索列表页 -->
    <div class="weibo-table table shadow">
        <table class="weibo-order-table">
            <thead>
                <tr>
                    <th width="130">订单ID</th>
                    <th width="140">预约名称</th>
                    <th width="160">预约账号</th>
                    <th class="thead-title">
                        价格位置
                        <div class="explain-title">
                            <em></em>
                            <em></em>
                            <div class="explain-content">
                                <h3>价格位置</h3>
                                <p>价格位置（软广/微任务）的（直发/转发）
                                </p>
                            </div>
                        </div>
                    </th>
                    <th class="thead-title">
                        参考价 ( 元 )
                        <div class="explain-title">
                            <em></em>
                            <em></em>
                            <div class="explain-content">
                                <h3>参考报价</h3>
                                <p>（微任务或者软广）的直发和转发参考价
                                </p>
                            </div>
                        </div>
                    </th>
                    <th>预计投放时间</th>
                    <th>预约订单状态</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody>
            <?php
            if(!empty($dataProvider)){
                foreach($dataProvider as $key => $order){?>
                <tr data-uuid="<?=$order['order_uuid']?>">
                    <td><a href="javascript:;"><?=$order['order_uuid']?></a></td>
                    <td><a href="javascript:;"><?=$order['plan_name']?></a></td>
                    <td><a href="javascript:;" class="plain-text-length-limit" data-limit="7" data-title="<?=$order['weibo_name']?>"><?=$order['weibo_name']?></a></td>
                    <?php
                    switch($order['sub_type']){
                        case 1:$sub_type = '软广直发';break;
                        case 2:$sub_type = '软广转发';break;
                        case 3:$sub_type = '微任务直发';break;
                        case 4:$sub_type = '微任务转发';break;
                        default:$sub_type = '未知';break;
                    }
                    ?>
                    <td class="ruan-ad-price">
                        <div><?=$sub_type?></div>
                    </td>
                    <td class="tiny-task-price">
                        <div><?=$order['price']?></div>
                    </td>
                    <td><?=date('Y.m.d',$order['execute_start_time'])?>
                        ~
                        <?=date('Y.m.d',$order['execute_end_time'])?>
                    </td>
                    <?php
                    switch($order['status']){
                        case 0:$status = '未提交';break;
                        case 1:$status = '预约中';break;
                        case 2:$status = '已完成';break;
                        default:$status = '未提交';break;
                    }
                    ?>
                    <td><?=$status?></td>
                    <td>
                    <?php
                    if($order['status']!=0){
                        echo '<span class="check-order-require" data-uuid="'.$order['order_uuid'].'">查看预约需求</span>';
                    }else{
                        echo '<a class="edit" href="'.Url::to(["/ad-owner/weibo-plan/create-plan-order",'plan_uuid'=>$order['plan_uuid']]).'">编辑</a>
                              <span class="delete-order" data-url="'.yii::$app->urlManager->createUrl(array("/ad-owner/weibo-plan/delete-weibo-order")).'" data-uuid="'.$order['order_uuid'].'">删除</span>';
                    }
                    ?>
                    </td>
                </tr>
            <?php  }}?>
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
