
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
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/video-media-lib-resource-detail.css');

AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/video-media-lib-resource-detail.js');
$this->title = '视频媒体库管理';
?>
<?php $this->beginBlock('level-1-nav'); ?>媒体库管理<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>视频<?php $this->endBlock(); ?>


<!--右侧内容-->
<div class="content fr">

    <!--页面参数-->
    <input id="id-del-media-from-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-video-media-lib/remove-media']) ?>">
    <input id="id-del-media-from-lib-batch-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-video-media-lib/remove-media-batch']) ?>">
    <input id="id-get-all-video-media-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-video-media-lib/get-all-lib']) ?>">
    <input id="id-re-group-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-video-media-lib/re-group']) ?>">
    <input id="id-new-video-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-video-media-lib/add-video-lib']) ?>">
    <input id="id-create-plan-media-from-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-video-media-lib/create-video-plan']) ?>">
    <input id="id-create-plan-order-url" type="hidden" value="<?= Url::to(['/ad-owner/video-plan/create-plan-order']) ?>">
    <input id="id-video-list-url" type="hidden" value="<?= Url::to(['/video/media/list']) ?>">
    <input id="id-video-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-video-media-lib/list']) ?>">
    <input id="id-media-export-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-video-media-lib/export-media']) ?>">

    <!--pjax开始-->
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?php
    $js = <<<JS
    pjaxRefVideoLibDetail();
JS;
    $this->registerJS($js);
    ?>

    <!--form表单信息-->
    <?= Html::beginForm(Url::to(['/ad-owner/admin-video-media-lib/detail', 'group_uuid' => $group_uuid]), 'post', ['data-pjax' => '', 'class' => 'form-inline form-detail-search', 'style' => 'display:none', 'id' => 'form-detail-search', 'autocomplete' => "off"]); ?>
    <input class="page" type="hidden" name="page" value="<?php echo Yii::$app->request->post('page', 0); ?>">
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
        <table class="video-resource-list-table">
            <thead>
            <thead>
            <tr>
                <th>选择</th>
                <th>平台账号</th>
                <th>平台</th>
                <th class="thead-title">
                    粉丝数（万）
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>粉丝数</h3>
                            <p>网红在该平台上的粉丝数量。</p>
                        </div>
                    </div>
                </th>
                <th class="thead-title">
                    参考报价
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>参考报价</h3>
                            <p>网红在该平台上的活动参考价。</p>
                        </div>
                    </div>
                </th>
                <th class="thead-title">
                    平均观看人数
                    <div class="explain-title">
                        <em></em>
                        <em></em>
                        <div class="explain-content">
                            <h3>平均观看人数</h3>
                            <p>网红在该平台上发布视频的平均观看人数。</p>
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
                            <p>网红在该平台上的参考价的有效期。</p>
                        </div>
                    </div>
                </th>
                <th>上下架</th>
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
                <?php
                switch($item['sex']){//性别
                    case 1:$sex = "男";$sex_icon = "sex-icon-b";break;
                    case 2:$sex = "女";$sex_icon = "sex-icon-g";break;
                    case 0:$sex = "未知";$sex_icon = "";break;
                    default:$sex = "未知";$sex_icon = "";
                }
                $area_array =array_filter(explode('#',$item['address']));//资源地域
                if(!empty($area_array)){
                    $mediaCityList = MediaHelper::getCityList();
                    foreach ($mediaCityList as $code => $cate) {
                        foreach($area_array as $k=>$v){
                            if($v==$code) $address = $cate;
                        }
                    }
                }else{//默认全国
                    $address = "全国";
                }
                ?>
                <td class="account clearfix">
                    <dl class="clearfix">
                        <dt class="fl">
                            <a href="<?=$item['url']?>" target="_blank">
                                <?php if(empty($item['avatar'])){ ?>
                                    <img src="<?=MediaHelper::getMediaVideoDefaultAvatar();?>" alt="">
                                <?php } else { ?>
                                    <img src="<?=$item['avatar']?>" alt="">
                                <?php } ?>
                            </a>
                        </dt>
                        <dd class="fl">
                            <a class="ID-name synopsis" target="_blank" href="<?=$item['url']?>" data-str="6" data-title="<?=$item['account_name']?>" data-value = '<?=$item['account_name']?>'><?=$item['account_name']?></a>
                            <div class="sex-address">
                                <i class="sex-icon-g <?=$sex_icon?>"></i>
                                <span><?=$sex?></span>
                                <i class="address-icon"></i>
                                <span><?=$address?></span>
                            </div>
                        </dd>
                    </dl>
                </td>
                <?php
                switch($item['platform_type']){//平台logo
                    case 1:$platform_icon = "platform-icon-hj";break;
                    case 2:$platform_icon = "platform-icon-xm";break;
                    case 3:$platform_icon = "platform-icon-hn";break;
                    case 4:$platform_icon = "platform-icon";break;
                    case 5:$platform_icon = "platform-icon-mp";break;
                    case 6:$platform_icon = "platform-icon-dy";break;
                    case 7:$platform_icon = "platform-icon-yk";break;
                    case 8:$platform_icon = "platform-icon-tb";break;
                    case 9:$platform_icon = "platform-icon-yzb";break;
                    default:$platform_icon = "";//默认美拍
                }
                ?>
                <td><i class="platform-icon <?=$platform_icon?>"></i></td>
                <td><?=round($item['follower_num']/10000,1)?></td>
                <?php
                //视频价格类型
                if($item['platform_type'] == 5){
                    $price_one = "原创视频";
                    $price_two = "视频转发";
                }else{
                    $price_one = "线上直播";
                    $price_two = "线下活动";
                }
                ?>
                <td class="ruan-ad-price">
                    <div><?=$price_one?>：<?=$item['price_orig_one']?></div>
                    <div><?=$price_two?>：<?=$item['price_orig_two']?></div>
                </td>
                <td><?=$item['avg_watch_num']?></td>
                <td><?=date('Y-m-d',$item['active_end_time'])?></td>
                <?php
                switch($item['is_put']){
                    case 0:$is_put = "下架";break;
                    case 1:$is_put = "上架";break;
                    default:$is_put = "下架";break;
                }
                ?>
                <td><?=$is_put?></td>
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
    <div class="table-footer clearfix fl">
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

    <!--批量管理-->
    <div class="batch-manage fr">
        <button class="add btn btn-danger bg-main" data-uuid="<?=$group_uuid?>">添 加</button>
        <button class="throw btn btn-danger bg-main">投 放</button>
        <button class="export btn btn-danger bg-main">导 出</button>
        <button class="remove btn btn-danger bg-main">移 除</button>
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


