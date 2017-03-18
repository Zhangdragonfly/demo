
<?php
/**
 *  微博媒体库管理列表
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/6/16 18:33
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/weibo-media-lib-manage.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/pjax-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/weibo-media-lib-manage.js');
$this->title = '微博媒体库管理';
?>
<?php $this->beginBlock('level-1-nav'); ?>
媒体库管理
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
微博
<?php $this->endBlock(); ?>


<div class="content fr">

    <!--页面参数-->
    <input id="id-new-weibo-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/add-weibo-lib']) ?>">
    <input id="id-delete-excel-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weibo-media-lib/delete-export-excel']) ?>">

    <!--pjax开始-->
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?php
    $js = <<<JS
    pjaxReflib();
    //分页
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
        $(".form-lib-search").submit();
    });

    //限制字符串长度
    plainContentLengthLimit();
    //鼠标放上去显示完整信息
    $("a[data-title]").each(function() {
        var a = $(this);
        var title = a.attr('data-title');
        if (title == undefined || title == "") return;
        a.data('data-title', title).hover(function () {
                var offset = a.offset();
                $("<div class='show-all-info'>"+title+"</div>").appendTo($(".table")).css({ top: offset.top + a.outerHeight(), left: offset.left + a.outerWidth()}).fadeIn(function () {
                });
            },
            function(){ $(".show-all-info").remove();
            }
        );
    });

JS;
    $this->registerJS($js);
    ?>

    <!--搜需条件-->
    <div class="con-top shadow clearfix">
        <?= Html::beginForm([''], 'post', ['data-pjax' => '', 'class' => 'form-inline form-lib-search', 'id' => 'form-lib-search', 'autocomplete' => "off"]); ?>
        <div class="condition-area media-lib-name fl font-16 font-500">媒体库名称 :
            <input type="text" name="group_name" value="<?php echo Yii::$app->request->post('group_name', ''); ?>" placeholder="请输入媒体库名称">
        </div>
        <div class="condition-area detail-name fl font-16 font-500">微博名称 :
            <input type="text" name="weibo_name" value="<?php echo Yii::$app->request->post('weibo_name', ''); ?>" placeholder="输入微博名称">
        </div>
        <button class="btn-search btn btn-danger fl bg-main btn-search-lib"><i></i>搜索</button>
        <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">
        <?= Html::endForm() ?>
        <button data-target="#create" class="btn-create btn btn-danger fl bg-main" data-toggle="modal"><i></i>新建媒体库</button>
    </div>

    <!-- 搜索列表页 -->
    <div class="weibo-table table shadow">
        <table>
            <thead>
                <tr>
                <th>媒体库名称</th>
                <th>媒体数量</th>
                <th class="thead-title">
                        覆盖粉丝数
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>覆盖粉丝数</h3>
                            <p>统计该资源库全部的资源的粉丝数之和。</p>
                        </div>
                    </div>
                </th>
                <th class="thead-title">
                        微任务转发总参考报价
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>微任务转发总参考报价</h3>
                            <p>统计该资源库全部的资源的微任务妆发参考报价总和。</p>
                        </div>
                    </div>
                </th>
                <th class="thead-title">
                        更新时间
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>更新时间</h3>
                            <p>该账号在系统中被维护的最新时间。</p>

                        </div>
                    </div>
                </th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!empty($dataProvider)){
            foreach($dataProvider as $key => $group){?>
                <tr data-uuid="<?=$group['group_uuid']?>">
                    <td>
                        <a href="<?=Url::to(['/ad-owner/admin-weibo-media-lib/detail','group_uuid'=>$group['group_uuid']]);?>" class="plain-text-length-limit" data-limit="7" data-title="<?=$group['group_name']?>">
                            <?=$group['group_name']?>
                        </a>
                    </td>
                    <td><?=$group['media_cnt']?></td>
                    <td><?=$group['total_fan_cnt']?></td>
                    <td><?= (!empty($group['extra_data']))? json_decode($group['extra_data'])->total_micro_transfer_retail_price:'-';?></td>
                    <td><?=date('Y-m-d',$group['last_update_time'])?></td>
                    <td>
                        <a class="manage" href="<?=Url::to(['/ad-owner/admin-weibo-media-lib/detail','group_uuid'=>$group['group_uuid']]);?>">管理</a>
                        <a class="export" href="javascript:;" data-url="<?=Yii::$app->urlManager->createUrl(array('/ad-owner/admin-weibo-media-lib/export-lib-media'))?>" data-uuid="<?=$group['group_uuid']?>">导出</a>
                        <a class="remove" href="javascript:;" data-url="<?=Yii::$app->urlManager->createUrl(array('/ad-owner/admin-weibo-media-lib/delete-group'))?>" data-uuid="<?=$group['group_uuid']?>">移除</a>
                    </td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
        <div class="no-lib">暂无媒体库</div>
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

<!-- 新建媒体库modal框 -->
<div id="create" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="fl color-main">新建媒体库</h4>
                <span class="close fr" data-dismiss="modal">x</span>
            </div>
            <div class="modal-body">
                <div class="create-media-lib font-14 font-500">媒体库名称 :
                    <input class="new-media-lib" type="text" placeholder="请输入新的媒体库名称">
                </div>
                <button class="btn btn-danger bg-main btn-new-lib-save">保存</button>
            </div>
            <div class="modal-footer">
                <h4>我的媒体库特权</h4>
                <p>1、创建专属个人资源管理库</p>
                <p>2、帮助您高效管理自己的自媒体资源</p>
                <p>3、资源库中的资源随时共享</p>
            </div>
        </div>
    </div>
</div>

