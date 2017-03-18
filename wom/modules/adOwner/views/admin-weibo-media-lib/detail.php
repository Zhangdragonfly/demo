
<?php
/**
 *  微博媒体库资源详情管理列表
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/6/16 18:33
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\MediaHelper;
//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/weibo-media-lib-resource-detail.css');

AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/pjax-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/weibo-media-lib-resource-detail.js');
$this->title = '微博媒体库管理';
?>
<?php $this->beginBlock('level-1-nav'); ?>媒体库管理<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>微博<?php $this->endBlock(); ?>


<!--右侧内容-->
<div class="content fr">

    <!--页面参数-->
    <input id="id-del-media-from-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/remove-media']) ?>">
    <input id="id-del-media-from-lib-batch-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/remove-media-batch']) ?>">
    <input id="id-get-all-weibo-media-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/get-all-lib']) ?>">
    <input id="id-re-group-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/re-group']) ?>">
    <input id="id-new-weibo-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/add-weibo-lib']) ?>">
    <input id="id-create-plan-media-from-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/create-weibo-plan']) ?>">
    <input id="id-create-plan-order-url" type="hidden" value="<?= Url::to(['/ad-owner/weibo-plan/create-plan-order']) ?>">
    <input id="id-weibo-list-url" type="hidden" value="<?= Url::to(['/weibo/media/list']) ?>">
    <input id="id-weibo-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/list']) ?>">
    <input id="id-media-export-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/export-media']) ?>">

    <!--pjax开始-->
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?php
    $js = <<<JS
    pjaxRefWeiboLibDetail();
JS;
    $this->registerJS($js);
    ?>

    <!--表单信息-->
    <?= Html::beginForm(Url::to(['/ad-owner/admin-weibo-media-lib/detail', 'group_uuid' => $group_uuid]), 'post', ['data-pjax' => '', 'class' => 'form-inline form-detail-search', 'style' => 'display:none', 'id' => 'form-detail-search', 'autocomplete' => "off"]); ?>
    <?= Html::input('hidden', 'page', Yii::$app->request->post('page'), ['class' => 'form-control page']) ?>
    <?= Html::endForm() ?>
    <div class="resource-list-table-top clearfix">
        <div class="lib-detail fl font-500">
            <span><?=$group_name?></span>
            <span>( 资源数 : &nbsp;<i class="color-main"><?= $pager->totalCount ?></i> 个 )</span>
        </div>
        <button href="#create" class="create btn btn-danger bg-main fr font-16" data-toggle="modal"><i></i>新建媒体库</button>
    </div>


    <!--搜索列表页-->
    <div class="table shadow">
        <table class="weibo-resource-list-table">
            <thead>
            <tr>
                <th>选择</th>
                <th>微博名称</th>
                <th>粉丝数 ( 万 )</th>
                <th class="thead-title">
                    软广参考价 ( 元 )
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>软广参考价</h3>
                            <p>发布软广的直发和转发参考价
                            </p>
                        </div>
                    </div>
                </th>
                <th class="thead-title">
                    微任务参考价 ( 元 )
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>微任务参考价</h3>
                            <p>发布微任务的直发和转发参考价
                            </p>
                        </div>
                    </div>
                </th>
                <th class="thead-title">
                    价格有效期
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>价格有效期</h3>
                            <p>该账号的参考零售价的截止日期</p>
                        </div>
                    </div>
                </th>
                <th>上下架</th>
                <th>接单备注</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!empty($dataProvider)){
            foreach($dataProvider as $key => $item){?>
            <tr data-uuid="<?=$item['uuid']?>">
                <td>
                    <input name="media-selected" type="checkbox">
                </td>
                <td class="weibo-name clearfix" width="160px">
                    <div>
                        <a class="name plain-text-length-limit" target="_blank"  href="<?= $item['weibo_url'] ?>"  data-title="<?=$item['weibo_name']?>" data-limit="7"><?=$item['weibo_name']?></a>
                        <span <?php
                        switch($item['media_level']){
                            case 1:echo '';break;
                            case 2:echo 'class="weibo-icon yellow-on"';break;
                            case 4:echo 'class="weibo-icon red-on"';break;
                            default:echo 'style="display:none"';break;
                        }
                        ?>></span>
                    </div>
                    <div class="tag">
                        <?php
                        $mediaCate = MediaHelper::parseMediaCate($item['media_cate']);
                        $mediaCate = json_decode($mediaCate);
                        if(!empty($mediaCate)){
                            foreach($mediaCate as $cate){
                            ?>
                            <span><?= $cate ?></span>
                        <?php }}?>
                    </div>

                </td>
                <td width="90px"><?=intval($item['follower_num']/10000) ?></td>
                <td class="ruan-ad-price">
                    <div>直发:<?= ($item['sd_price']!="0.00")?$item['sd_price']:"--"; ?></div>
                    <div>转发:<?= ($item['st_price']!="0.00")?$item['st_price']:"--"; ?></div>
                </td>
                <td class="wei-task-price">
                    <div>直发:<?= ($item['md_price']!="0.00")?$item['md_price']:"--"; ?></div>
                    <div>转发:<?= ($item['mt_price']!="0.00")?$item['mt_price']:"--"; ?></div>
                </td>
                <td><?= empty($item['active_end_time'])?'/':date('Y-m-d', $item['active_end_time']) ?></td>
                <?php
                switch($item['is_put']){
                    case 0:$is_put = "下架";break;
                    case 1:$is_put = "上架";break;
                    default:$is_put = "下架";break;
                }
                ?>
                <td><?=$is_put?></td>
                <td><?=$item['accept_remark']?></td>
                <td class="operate" width="76px">
                    <a href="javascript:;" class="re-group" data-group-uuid="<?=$item['group_uuid']?>" data-item-uuid="<?=$item['uuid']?>">重新分组</a>
                    <a href="javascript:;" class="delete" data-item-uuid="<?=$item['uuid']?>">移除</a>
                </td>
            </tr>
            <?php }} ?>
            </tbody>
        </table>
        <div class="no-resource">暂无资源</div>
    </div>
    <label class="check-all"><input type="checkbox">全选</label>
    <!--分页-->
    <div class="table-footer clearfix">
        <div class="page-wb system_page" data-value="<?= $pager->totalCount ?>">
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

    <!--批量管理-->
    <div class="batch-manage fl">
        <button class="add btn btn-danger bg-main" data-uuid="<?=$group_uuid?>">添 加</button>
        <button class="throw btn btn-danger bg-main">投 放</button>
        <button class="export btn btn-danger bg-main">导 出</button>
        <button class="remove btn btn-danger bg-main">移 除</button>
    </div>
    <?php Pjax::end(); ?>
</div>


<!-- 新建媒体库modal框 -->
<div id="create" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="fl color-main">新建媒体库</h4>
                <span class="close fr" data-dismiss="modal">x</span>
            </div>
            <div class="modal-body">
                <div class="create-media-lib font-14 font-500">
                    媒体库名称 : <input class="new-media-lib" type="text" placeholder="请输入新的媒体库名称">
                </div>
                <button class="save btn btn-danger bg-main btn-new-lib-save">保存</button>
            </div>
            <div class="modal-footer">
                <h4>我的媒体库特权</h4>
                <p>1、创建专属个人资源管理库</p>
                <p>2、帮助您高效地管理自己的自媒体资源</p>
                <p>3、资源库中的资源随时共享</p>
            </div>
        </div>
    </div>
</div>

<!-- 重新分组modal框 -->
<div id="regrouping" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="fl color-main">重新分组</h4>
                <span class="close fr" data-dismiss="modal">x</span>
            </div>
            <div class="modal-body">
                <div class="bd-header">
                    <span>选择</span>　　
                    <span>资源分组名称</span>
                </div>
                <div class="group-name">
                    <ul>
                        <!--ajax媒体库列表-->
                    </ul>
                </div>
                <div class="bd-foot">
                    <input type="checkbox" id="checked-all">
                    <label for="checked-all">全选</label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-submit-regroup">确定</button>
            </div>
        </div>
    </div>
</div>

