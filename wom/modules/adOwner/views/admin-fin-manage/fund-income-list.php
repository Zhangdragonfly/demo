
<?php
/**
 * 充值记录
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
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/pay-record-list.css');

AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/fund-income-list.js');
$this->title = '充值记录';
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
    incomeList();
JS;
$this->registerJS($js);
?>
        <?php $this->beginBlock('level-1-nav'); ?>
        财务管理
        <?php $this->endBlock(); ?>
        <?php $this->beginBlock('level-2-nav'); ?>
        充值记录
        <?php $this->endBlock(); ?>
        <?= Html::beginForm([''], 'post', ['data-pjax' => '', 'class' => 'form-inline form-income-search', 'id' => 'form-income-search', 'autocomplete' => "off"]); ?>
        <div class="content-head-search shadow clearfix">
            <div class="pay-type clearfix fl">
                <span class="fl">充值类别：</span>
                <div class="dropdown fl">
                    <div class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="show-default fl">不限</span>
                        <span class="caret fr"></span>
                    </div>
                    <ul class="dropdown-menu dropdown-income-type" role="menu">
                        <li data-type="-1">不限</li>
                        <li data-type="1">授信</li>
                        <li data-type="2">线上充值</li>
                        <li data-type="3">线下充值</li>
                    </ul>
                </div>
            </div>
            <div class="condition-area trade-time fl">
                <span class="">交易时间：</span>
                <input type="text" name="income_start_time" value="<?php echo Yii::$app->request->post('income_start_time', ''); ?>" class="form-control datetimepicker">
                ——
                <input type="text" name="income_end_time" value="<?php echo Yii::$app->request->post('income_end_time', ''); ?>" class="form-control datetimepicker">
            </div>
            <button class="btn-search btn btn-danger bg-main fr btn-search-income"><i></i>搜索</button>
        </div>
        <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">
        <input class="income_type" type="hidden" name="income_type" value="<?php echo Yii::$app->request->post('income_type',-1); ?>">
        <?= Html::endForm() ?>

        <div class="table-info shadow">
            <table class="table">
                <thead>
                    <tr>
                        <th>流水号</th>
                        <th>充值时间</th>
                        <th>充值类别</th>
                        <th>交易金额</th>
                        <th>充值状态</th>
                        <th>操作/备注</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($dataProvider)){
                foreach($dataProvider as $key => $income){?>
                    <tr data-uuid="<?=$income['uuid']?>">
                        <td><?=$income['uuid']?></td>
                        <td><?=(!empty($income['complete_time']))?date('Y.m.d',$income['complete_time']):"暂无";?></td>
                        <?php
                        switch($income['type']){
                            case -1:$income_type = "未知";break;
                            case 1:$income_type = "授信";break;
                            case 2:$income_type = "线上充值";break;
                            case 3:$income_type = "线下充值";break;
                            default:$income_type = "未知";
                        }
                        ?>
                        <td><?=$income_type?></td>
                        <td><?=$income['amount']?></td>
                        <?php
                        if($income['status']==1){
                            echo "<td>已支付</td><td>".$income['comment']."</td>";
                        }else{
                            echo  "<td>未支付</td><td class='red'>支付</td>";
                        }
                        ?>
                    </tr>
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

