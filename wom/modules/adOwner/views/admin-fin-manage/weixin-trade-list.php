
<?php
/**
 * 全部流水
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
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/fin-total-list.css');

AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/fin-total-list.js');
$type = yii::$app->request->get('type');
$this->title = '全部流水';
?>

    <!--右侧内容-->
    <div class="content fr">
        <div class="content-head shadow">
            <ul class="clearfix">
                <li><span>用户名：</span><span><?= $ad_owner['nickname'] ?></span></li>
                <li><span>授信总金额：</span><em><?= $ad_owner['total_frozen_credit'] + $ad_owner['total_available_credit']?></em>元</li>
                <li><span>账户余额：</span><em><?= $ad_owner['total_balance'] ?></em>元</li>
                <li><span>可用余额：</span><em><?= $ad_owner['total_available_balance'] ?></em>元</li>
                <li><span>冻结金额：</span><em><?= $ad_owner['total_frozen_amount'] ?></em>元</li>
                <li class="btn-pay"><a href="<?= Url::to(['top-up']) ?>">立即充值</a></li>
            </ul>
        </div>

        <!--pjax开始-->
        <?php Pjax::begin(['linkSelector' => false]); ?>
<?php
$js = <<<JS
    finTotalList();
JS;
$this->registerJS($js);
?>
        <?php $this->beginBlock('level-1-nav'); ?>
        财务管理
        <?php $this->endBlock(); ?>
        <?php $this->beginBlock('level-2-nav');
        if($type == 1){echo '全部流水';}else{echo '消费记录';}
        $this->endBlock(); ?>
        <?= Html::beginForm([''], 'post', ['data-pjax' => '', 'class' => 'form-inline form-total-search', 'id' => 'form-total-search', 'autocomplete' => "off"]); ?>
        <div class="content-head-search shadow clearfix">
            <div class="condition-area fin-type clearfix fl" <?php if($type==2){echo "style='display:none;'";}?>>
                <span class="fl">财务类型：</span>
                <div class="dropdown fl">
                    <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="show-default fl">不限</span>
                        <span class="caret fr"></span>
                    </div>
                    <ul class="dropdown-menu dropdown-fin-type" role="menu">
                        <li data-type="-1">不限</li>
                        <li data-type="1">收入</li>
                        <li data-type="2">支出</li>
                    </ul>
                </div>
            </div>
            <div class="condition-area trade-way fin-type clearfix fl" <?php if($type==2){echo "style='display:none;'";}?>>
                <span class="fl">交易方式：</span>
                <div class="dropdown fl">
                    <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="show-default fl">不限</span>
                        <span class="caret fr"></span>
                    </div>
                    <ul class="dropdown-menu dropdown-trade-type" role="menu">
                        <li data-type="-1">不限</li>
                        <li data-type="1">冻结</li>
                        <li data-type="2">解冻</li>
                        <li data-type="3">扣款</li>
                        <li data-type="4">线上充值</li>
                        <li data-type="5">线下充值</li>
                        <li data-type="6">授信</li>
                    </ul>
                </div>
            </div>
            <div class="condition-area trade-time fl" <?php if($type==2){echo "style='margin-left:20px;'";}?>>
                <span class="">交易时间：</span>
                <input type="text" name="trade_start_time" value="<?php echo Yii::$app->request->post('trade_start_time', ''); ?>" class="datetimepicker form-control">
                ——
                <input type="text"  name="trade_end_time" value="<?php echo Yii::$app->request->post('trade_end_time', ''); ?>" class="datetimepicker form-control">
            </div>
            <button class="btn-search btn btn-danger bg-main fr btn-search-total "><i></i>搜索</button>
        </div>
        <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">
        <input class="fin_type" type="hidden" name="fin_type" value="<?php echo Yii::$app->request->post('fin_type',-1); ?>">
        <input class="trade_type" type="hidden" name="trade_type" value="<?php echo Yii::$app->request->post('trade_type',-1); ?>">
        <?= Html::endForm() ?>

        <div class="table-info shadow">
            <table class="table">
                <thead>
                <tr>
                    <th>流水号</th>
                    <th>财务类型</th>
                    <th>交易方式</th>
                    <th>交易时间</th>
                    <th>交易金额</th>
                    <th>操作/备注</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($dataProvider)){
                foreach($dataProvider as $key => $trade){?>
                    <tr data-uuid="<?=$trade['uuid']?>">
                        <td><?=$trade['uuid']?></td>
                        <?php
                        switch($trade['fin_type']){
                            case -1:$fin_type = "未知";break;
                            case 1:$fin_type = "收入";break;
                            case 2:$fin_type = "支出";break;
                            default:$fin_type = "未知";
                        }
                        switch($trade['type']){
                            case -1:$trade_type = "未知";break;
                            case 1:$trade_type = "冻结";break;
                            case 2:$trade_type = "解冻";break;
                            case 3:$trade_type = "扣款";break;
                            case 4:$trade_type = "线上充值";break;
                            case 5:$trade_type = "线下充值";break;
                            case 6:$trade_type = "授信";break;
                            default:$trade_type = "未知";
                        }
                        ?>
                        <td><?=$fin_type?></td>
                        <td><?=$trade_type?></td>
                        <td><?=(!empty($trade['create_time']))?date('Y.m.d',$trade['create_time']):"暂无";?></td>
                        <td><?=$trade['amount']?></td>
                        <td><span class="red">详情/备注（未做）</span></td>
                    </tr>
<!--                    <tr>-->
<!--                        <td>111111111111111</td>-->
<!--                        <td>收入</td>-->
<!--                        <td>自主充值</td>-->
<!--                        <td>2015.04.09</td>-->
<!--                        <td>2566</td>-->
<!--                        <td><span>支付宝支付</span></td>-->
<!--                    </tr>-->
                <?php }} ?>
                </tbody>
            </table>
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



