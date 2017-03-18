
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
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/material-manage/material-manage.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/material-manage.js');
$this->title = '素材管理';
?>
<?php $this->beginBlock('level-1-nav'); ?>素材管理<?php $this->endBlock(); ?>


<div class="content fr">

    <!--pjax开始-->
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?php
    $js = <<<JS
    //分页处理
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
    
    //查询
    $(".search").click(function(){     
        $(".form-lib-search").submit();
    });
    //检查有无资源
    function isResource(){
        var resourceLength =  $(".table tbody").children("tr").length;
        if(resourceLength < 10){
            $(".table-footer").css("display","none");
            if(resourceLength < 1){
                $(".no-lib").css("display","block");
            }else{
                $(".no-lib").css("display","none");
            }
        }else{
            $(".table-footer").css("display","block");
        }
        $(".material-total").children("span").text(resourceLength);
    }
    isResource();
JS;
    $this->registerJS($js);
    ?>
    <!--搜需条件-->
    <div class="con-top shadow clearfix">
        <?= Html::beginForm([''], 'post', ['data-pjax' => '', 'class' => 'form-inline form-lib-search', 'id' => 'form-lib-search', 'autocomplete' => "off"]); ?>
        <div class="material-total fl">素材总数 : <span class="color-main"></span> </div>
        <div class="input-wrap fl font-16 font-500">
            <input type="text" class="search-input" name="search_name" value="<?php echo Yii::$app->request->post('search_name', ''); ?>" placeholder="标题/作者">
        </div>
        <button type="button" class="search btn btn-danger bg-main"><i></i>搜索</button>
        <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">
        <a class="create-material btn btn-danger bg-main" href="<?=Url::to(['/ad-owner/admin-weixin-material-lib/create'])?>"><i></i>新建素材</a>
        <?= Html::endForm() ?>
    </div>

    <!-- 搜索列表页 -->
    <div class="weibo-table table shadow">
        <table>
            <thead>
            <tr>
                <th width="90">标题</th>
                <th width="500"></th>
                <th width="100">创建时间</th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody class="material-list">
            <?php
            if(!empty($dataProvider)){
            foreach($dataProvider as $key => $material){?>
                <tr data-uuid="<?=$material['uuid']?>">
                    <td width="90">
                        <div><img src="../../src/images/demo.jpg" alt=""></div>
                    </td>
                    <td class="material-name"><?=$material['title']?></td>
                    <td width="100"><?=(!empty($material['create_time']))?date("Y.m.d",$material['create_time']):"/";?></td>
                    <td width="150">
                        <span><a class="edit color-main" href="<?=Url::to(['/ad-owner/admin-weixin-material-lib/create','material_uuid'=>$material['uuid']])?>">编辑</a></span>
                        <span class="copy" style="display: none;">复制</span>
                        <span class="remove" data-uuid="<?=$material['uuid']?>" data-url="<?=Url::to(['/ad-owner/admin-weixin-material-lib/delete-material'])?>">删除</span>
                    </td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
        <div class="no-lib">暂无素材</div>
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




