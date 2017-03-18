<?php
/**
 * 微信媒体库管理首页
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 2016/11/25 10:20
 */
use wom\assets\AppAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/media-lib-resource-detail.css');

AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/media-lib-resource-detail.js');

$this->title = '微信媒体库资源列表';
?>
<?php $this->beginBlock('level-1-nav'); ?>
媒体库管理
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
微信
<?php $this->endBlock(); ?>
<div class="content fr">

    <input id="id-weixin-media-select-url" type="hidden" value="<?= Url::to(['/weixin/media/list', 'lib_uuid' => $weixinMediaLib->uuid]) ?>">
    <input id="id-plan-create-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/create', 'route' => 2]) ?>">
    <input id="id-get-all-weixin-media-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-media-lib/get-all']) ?>">
    <input id="id-re-group-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-media-lib/re-group']) ?>">
    <input id="id-media-export-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-media-lib/export-media']) ?>">
    <input id="id-del-media-from-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-media-lib/delete-media']) ?>">
    <input id="id-weixin-media-lib-create-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-media-lib/create']) ?>">
    <input id="id-media-uuid-list-in-this-lib" type="hidden" value="<?= $mediaUUIDList ?>">
    <input id="id-media-lib-uuid" type="hidden" value="<?= $weixinMediaLib->uuid ?>">

    <div class="resource-list-table-top clearfix">
        <div class="lib-detail fl font-500">
            <span><?= $weixinMediaLib->group_name; ?></span>
            <span>( 资源数 : &nbsp;<i class="color-main"><?= $weixinMediaLib->media_cnt; ?></i> 个 )</span>
        </div>
        <button class="btn btn-danger bg-main fr font-16 create"><i></i>新建媒体库
        </button>
    </div>

    <div class="table shadow">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'weixin-resource-list-table', 'data-page-size' => 10],
            'pager' => [
                //'options'=>['class'=>'hidden'],//关闭自带分页
                'firstPageLabel' => "首页",
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'lastPageLabel' => '尾页',
            ],
            'summary' => false,
            'columns' => [
                [
                    'header' => '选择',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<input type="checkbox" name="media-select" data-item-uuid=" '.$model['item_uuid'] .'"  data-media-uuid="' . $model['media_uuid'] . '">';
                    }
                ],
                [
                    'header' => '微信账号',
                    'format' => 'html',
                    'contentOptions' => ['class' => 'user-main-info clearfix'],
                    'value' => function ($model) {
                        return '<div class="portrait fl">
                                <a href="' . Url::to(['/weixin/media/detail', 'media_uuid' => $model['media_uuid']]) . '">
                                    <img src="http://open.weixin.qq.com/qr/code/?username=' . $model['public_id'] . '" alt="">
                                </a>
                            </div>
                            <span class="v-logo"></span>
                            <div class="info fl">
                                <div class="weixinID"><a class="plain-text-length-limit" data-limit="5" href="' . Url::to(['/weixin/media/detail', 'media_uuid' => $model['media_uuid']]) . '">' . $model['public_name'] . '</a></div>
                                <div class="name clearfix">
                                    <i class="little-code fl"></i>
                                    <span class="en-name fl plain-text-length-limit" data-limit="10">' . $model['public_id'] . '</span>
                                </div>
                                <img class="weixin-code" src="http://open.weixin.qq.com/qr/code/?username=' . $model['public_id'] . '" alt="">
                            </div>';
                    }
                ],
                [
                    'header' => '粉丝数',
                    'format' => 'html',
                    'contentOptions' => ['width' => '90px'],
                    'value' => function ($model) {
                        if(empty($model['follower_num'])){
                            return 0;
                        } else {
                            return round($model['follower_num'] / 10000, 1) . '万';
                        }
                    }
                ],
                [
                    'header' => '参考价 ( 元 )',
                    'format' => 'html',
                    'contentOptions' => ['class' => 'refer-price', 'width' => '280px'],
                    'value' => function ($model) {
                        $priceArray = \common\helpers\MediaHelper::parseMediaWeixinRetailPrice($model['pub_config']);
                        $priceHtml = "";
                        if($priceArray['s']['pub_type'] == 1){
                            $priceHtml .= "<li><span>单图文：</span><i>". $priceArray['s']['price_label'] ."</i><em style='width:50px'></em></li>";
                        }else if($priceArray['s']['pub_type'] == 2){
                            $priceHtml .= "<li><span>单图文：</span><i>". $priceArray['s']['price_label'] ."</i><em style='width:50px'>(原创)</em></li>";
                        }
                        if($priceArray['m_1']['pub_type'] == 1){
                            $priceHtml .= "<li><span>多图文头条：</span><i>". $priceArray['m_1']['price_label'] ."</i><em style='width:50px'></em></li>";
                        }else if($priceArray['m_1']['pub_type'] == 2){
                            $priceHtml .= "<li><span>多图文头条：</span><i>". $priceArray['m_1']['price_label'] ."</i><em style='width:50px'>(原创)</em></li>";
                        }
                        if($priceArray['m_2']['pub_type'] == 1){
                            $priceHtml .= "<li><span>多图文2条：</span><i>". $priceArray['m_2']['price_label'] ."</i><em style='width:50px'></em></li>";
                        }else if($priceArray['m_2']['pub_type'] == 2){
                            $priceHtml .= "<li><span>多图文2条：</span><i>". $priceArray['m_2']['price_label'] ."</i><em style='width:50px'>(原创)</em></li>";
                        }
                        if($priceArray['m_3']['pub_type'] == 1){
                            $priceHtml .= "<li><span>多图文3-N条：</span><i>". $priceArray['m_3']['price_label'] ."</i><em style='width:50px'></em></li>";
                        }else if($priceArray['m_3']['pub_type'] == 2){
                            $priceHtml .= "<li><span>多图文3-N条：</span><i>". $priceArray['m_3']['price_label'] ."</i><em style='width:50px'>(原创)</em></li>";
                        }
                        return "<ul>".$priceHtml."</ul>";
                    }
                ],
                [
                    'header' => '头条平均阅读数' . '<div class="explain-title">
                                <em></em>
                                <em></em>
                                <div class="explain-content">
                                    <h3>头条平均阅读数</h3>
                                    <p>最近30天头条阅读总数与最近30天头条发布文章数的比值。</p>
                                </div>
                            </div>',
                    'headerOptions' => ['class' => 'thead-title'],
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model['head_avg_view_cnt'];
                    }
                ],
                [
                    'header' => '沃米指数' . '<div class="explain-title">
                                <em></em>
                                <em></em>
                                <div class="explain-content">
                                    <h3>沃米指数</h3>
                                    <p>沃米指数基于微信公众号的粉丝数、文章数据、近期价格，推出的指数系列，用于衡量微信的传播力、活跃度和性价比详情见帮助中心沃米指数说明。</p>

                                </div>
                            </div>',
                    'headerOptions' => ['class' => 'thead-title'],
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model['wmi'];
                    }
                ],
                [
                    'header' => '上下架',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model['put_up'] == 1 ? '上架' : '下架';
                    }
                ],
                [
                    'header' => '价格有效期' . '<div class="explain-title">
                                <em></em>
                                <em></em>
                                <div class="explain-content">
                                    <h3>价格有效期</h3>
                                    <p>该账号的参考零售价的截止日期。</p>
                                </div>
                            </div>',
                    'headerOptions' => ['class' => 'thead-title'],
                    'format' => 'html',
                    'value' => function ($model) {
                        return date('Y-m-d', $model['active_end_time']);
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{re-group}<br>{remove}',
                    'buttons' => [
                        're-group' => function ($url, $model) {
                            return '<a class="re-group" data-item-uuid="' . $model['item_uuid'] . '" data-lib-uuid="' . $model['lib_uuid'] . '">重新分组</a>';
                        },
                        'remove' => function ($url, $model) {
                            return '<a class="delete" data-media-uuid="'.$model['media_uuid'].'" data-item-uuid="' . $model['item_uuid'] . '">移除</a>';
                        },
                    ]
                ],
            ],
        ]) ?>
        <label class="check-all"><input type="checkbox">全选</label>
    </div>
    <!--批量管理-->
    <div class="batch-manage fl">
        <button class="btn btn-danger bg-main btn-add-more">添 加</button>
        <button class="btn btn-danger bg-main btn-put-in">投 放</button>
        <button class="btn btn-danger bg-main btn-export">导 出</button>
        <button class="btn btn-danger bg-main btn-delete">移 除</button>
    </div>
</div>

<!-- 新建微信媒体库 -->
<div id="modal-create-weixin-media-lib" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="fl color-main">新建媒体库</h4>
                <span class="close fr" data-dismiss="modal">x</span>
            </div>
            <div class="modal-body">
                <div class="area-lib-name font-14 font-500">
                    媒体库名称 : <input class="input-lib-name form-control" type="text" placeholder="请输入新的媒体库名称">
                </div>
                <button class="btn btn-danger bg-main btn-save">保存</button>
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

<!-- 重新分组modal框 -->
<div id="modal-re-group" class="modal modal-message fade in" aria-hidden="true"
     data-backdrop="static">
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

                    </ul>
                </div>
                <div class="bd-foot">
                    <input type="checkbox" id="checked-all">
                    <label for="checked-all">全选</label>
                </div>
            </div>
            <button class="btn btn-danger btn-commit btn-submit-regroup"  data-url="<?= Url::to(['regroup']) ?>">确定</button>
        </div>
    </div>
</div>
