
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
AppAsset::addCss($this, '@web/dep/ueditor/ueditor.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/material-manage/material-create.css');

AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/dep/ueditor/ueditor.config.js');
AppAsset::addScript($this, '@web/dep/ueditor/ueditor.all.min.js');
AppAsset::addScript($this, '@web/dep/plupload/plupload.full.min.js');
AppAsset::addScript($this, '@web/dep/js/wom-uploader.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/material-manage.js');
$this->title = '新建素材';
?>
<!--面包屑-->
<div class="bread">
    <ol class="breadcrumb font-500">
        当前位置：
        <li><a href="#">首页</a></li>
        <li><a href="#">个人中心</a></li>
        <li><a href="#">素材管理</a></li>
        <li class="active color-main">新建素材</li>
    </ol>
</div>

<!-- 主要内容部分 -->
<input type="hidden" id="id-material-lib-list" value="<?=Url::to(['/ad-owner/admin-weixin-material-lib/list'])?>">
<input type="hidden" id="id-material-uuid" value="<?=yii::$app->request->get('material_uuid');?>">
<!-- 删除图片 -->
<input id="id-delete-file-url" type="hidden"
       value="<?= Url::to(['/site/file-uploader/delete-file', 'cate_code' => 'order']) ?>">
<!-- csrf -->
<input type="hidden" id="csrf" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken(); ?>"/>
<!-- 上传图片 -->
<input type="hidden" id="id-upload-file-url" value="<?= Url::to(['/site/file-uploader/upload', 'cate_code' => 'order']) ?>">

<h2 class="create-material">新建素材</h2>
<div class="content-wrap shadow">
    <div class="content file-content weixin-file-content">
        <div class="file-name">
            <span class="title file-content-title"><i>*</i>标题：</span>
            <input class="title-input" name="title" type="text" maxlength="50" value="<?=(!empty($material->title))?$material->title:"";?>" placeholder="请勿超过50个字,勿包含转发、分享等文字,以免被微信屏蔽造成损失">
            <span class="font-12 tips">您还可以输入<em class="color-main">50</em>字</span>
        </div>
        <div class="author-name">
            <span class="file-content-title">作者：</span>
            <input class="author-input" name="author" type="text" maxlength="8" value="<?=(!empty($material->author))?$material->author:"";?>">
            <span class="font-12 tips">您还可以输入<em class="color-main">8</em>字</span>
        </div>
        <div class="cover-pic">
            <div id="upload-single-img-container" class="plupload-container">
                <span class="file-content-title"><i>*</i>封面图片：</span>
                <button id="id-upload-001-btn"  class="cover-pic-btn btn btn-danger bg-main" for="id-upload-001-preview-area">上传图片</button>
                <span class="tips"> 请选择1张封面图片,不要超过2M</span>
                <div id="id-upload-001-preview-area" class="upload-preview-area"></div>
            </div>
        </div>
        <div class="article-content clearfix">
            <span class="file-content-title fl"><i>*</i>正文内容：</span>
            <form action="server.php" method="post">
                <!-- 加载编辑器的容器 -->
                <script id="container" name="content" type="text/plain"><?=(!empty($material->article_content))?$material->article_content:"";?></script>
            </form>
        </div>
        <div class="article-link">
            <div class="file-content-title">原文链接：</div>
            <input type="text" name="url"  placeholder="微信文章阅读原文的链接地址" value="<?=(!empty($material->original_mp_url))?$material->original_mp_url:"";?>">
        </div>
        <div class="summary">
            <span class="file-content-title fl">摘要：</span>
            <textarea  name="desc" id="abstract" cols="30" rows="10" maxlength="120" placeholder="选填,如果不填写会默认抓取正文前54个字"><?=(!empty($material->article_desc))?$material->article_desc:"";?></textarea>
            <p class="tips message">您还可以输入<em>120</em>字</p>
        </div>
        <button class="save btn btn-danger bg-main btn-save-material" data-url="<?=Url::to(['/ad-owner/admin-weixin-material-lib/create'])?>">保存</button>
    </div>
</div>