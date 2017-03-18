
<?php
/**
 * 视频资源搜索列表页
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/5/16 11:23 AM
 */
use yii\widgets\Pjax;
use wom\assets\AppAsset;
use yii\helpers\Html;
use common\helpers\MediaHelper;
use yii\helpers\Url;
use common\helpers\ExternalFileHelper;

$this->title = '视频资源搜索列表页';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/media-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/media-resource-list-common.css');
AppAsset::addCss($this, '@web/src/css/video/video-resource-list.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/dep/js/jquery.fly.min.js');
AppAsset::addScript($this, '@web/src/js/media-resource-list-common.js');
AppAsset::addScript($this, '@web/src/js/video/video-resource-list.js');


?>
<!-- 获取cookie列表资源 -->
<input id="id-get-list-cookie-json-url" type="hidden" value="<?= Url::to(['/video/media/get-shopping-car-cookie']) ?>">
<input id="id-plan-uuid" type="hidden" value="<?=yii::$app->request->get('plan_uuid')?>">     <!--视频预约plan_uuid-->
<input id="id-add-media-plan-order-url" type="hidden" value="<?= Url::to(['/ad-owner/video-plan/add-media-plan-order']) ?>"><!--添加视频预约需求url-->
<input id="id-create-plan-order-url" type="hidden" value="<?= Url::to(['/ad-owner/video-plan/create-plan-order']) ?>">  <!--视频预约需求订单url-->
<input id="id-get-video-library-list-url" type="hidden" value="<?= Url::to(['/ad-owner/video-media-lib/get-media-lib-list']) ?>"> <!-- 获取视频媒体库列表 -->
<input id="id-add-media-into-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/video-media-lib/add-media-to-lib']) ?>">  <!-- 将视频资源加入媒体库 -->
<input id="id-add-media-into-exist-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/video-media-lib/add-media-to-exist-lib']) ?>">  <!-- 将视频资源加入已存在媒体库 -->
<input id="id-group-uuid" type="hidden" value="<?=yii::$app->request->get('group_uuid')?>">                 <!-- 媒体库group_uuid -->
<input id="id-select-item-url" type="hidden" value="<?=Url::to(['/ad-owner/admin-video-media-lib/get-group-item'])?>">                 <!-- 获取媒体库资源 -->
<input id="id-search-name" type="hidden" value="<?= Yii::$app->request->get('search_name', '') ?>">                 <!-- 搜索条件 -->

<!-- 条件选择-->
<div class="condition">
    <div class="condition-filter">
        <div class="condition-classify">
            <div class="filter-item filter-item-li filter-ID-sign clearfix">
                <h3>分类：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <?php
                    $mediaTagList = MediaHelper::getMediaVideoCateList();
                    $pos = 0;
                    foreach ($mediaTagList as $code => $tag) { ?>
                        <li class="pos-<?= $pos ?>" data-pos="<?= $pos ?>">
                            <i data-code="<?= $code ?>" data-label="<?= $tag ?>"><?= $tag ?></i>
                        </li>
                        <?php $pos++;
                    } ?>
                </ul>
                <span class="limit-six">支持多选（最多选择6个）</span>
            </div>
            <div class="filter-item filter-item-li filter-area clearfix">
                <h3>地域：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <?php
                    $followerAreaList = [
                        //0 => '全国',
                        1 => '北京', 9 => '上海', 289 => '广州', 291 => '深圳', 162 => '南京', 175 => '杭州', 385 => '成都',36 => '其他'];//MediaHelper::getFollowerAreaList();
                    $pos = 0;
                    foreach ($followerAreaList as $code => $area) {
                        ?>
                        <li class="pos-<?= $pos ?>" data-pos="<?= $pos ?>">
                            <i data-code="<?= $code ?>" data-label="<?= $area ?>">
                                <?= $area ?>
                            </i>
                        </li>
                        <?php
                        $pos++;
                    } ?>
                </ul>
                <span class="limit-three">支持多选（最多选择3个）</span>
            </div>
            <div class="filter-item filter-item-li filter-platform clearfix">
                <h3>平台：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <?php
                    $mediaTagList = MediaHelper::getMediaVideoPlatformList();
                    $pos = 0;
                    foreach ($mediaTagList as $code => $tag) { ?>
                        <li class="pos-<?= $pos ?>" data-pos="<?= $pos ?>">
                            <i data-code="<?= $code ?>" data-label="<?= $tag ?>"><?= $tag ?></i>
                        </li>
                        <?php $pos++;
                    } ?>
                </ul>
            </div>
            <div class="filter-item filter-item-li filter-sex clearfix">
                <h3>性别：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <li><i data-code="1">男</i></li>
                    <li><i data-code="2">女</i></li>
                </ul>
            </div>
            <div class="filter-item filter-item-li filter-value clearfix">
                <h3>参考报价：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <li><i data-min="1" data-max="2000" data-label="2000元以下">2000元以下</i></li>
                    <li><i data-min="2000" data-max="5000" data-label="2000-5000">2000-5000元</i></li>
                    <li><i data-min="5000" data-max="10000" data-label="5000-10000">5000-1万元</i></li>
                    <li><i data-min="10000" data-max="50000" data-label="10000-50000">1万-5万元</i></li>
                    <li><i data-min="50000" data-max="100000" data-label="50000-100000">5万-10万元</i></li>
                    <li><i data-min="100000" data-max="n" data-label="100000元以上">10万元以上</i></li>
                </ul>
                <div class="filter-price filter-price-button">
                    <input type="text" placeholder="￥">
                    <i>-</i>
                    <input type="text" placeholder="￥">
                    <span>确定</span>
                </div>
            </div>
            <div class="filter-item filter-item-li filter-fans clearfix">
                <h3>粉丝数：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <li><i data-min="1" data-max="5000" data-label="5000以下">5000以下</i></li>
                    <li><i data-min="5000" data-max="10000" data-label="5000-10000">5000-1万</i></li>
                    <li><i data-min="10000" data-max="30000" data-label="10000-30000">1-3万</i></li>
                    <li><i data-min="30000" data-max="50000" data-label="30000-50000">3-5万</i></li>
                    <li><i data-min="50000" data-max="100000" data-label="50000-100000">5-10万</i></li>
                    <li><i data-min="100000" data-max="n" data-label="100000以上">10万以上</i></li>
                </ul>
                <div class="filter-price filter-fans-btn">
                    <input type="text">
                    <i>-</i>
                    <input type="text">
                    <span>确定</span>
                </div>
            </div>
        </div>
        <div class="condition-selected clearfix">
            <h3 class="fl">已选择：</h3>
            <ul class="clearfix fl">
                <li class="selected-last-li">
                    <span>全部删除</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- 表格开始-->
<div class="media-stage">
    <div class="table-title clearfix">
        <ul class="clearfix title-select fl ">
            <li class="all-account table-active">全部账号</li>
            <li class="main-push">主推账号</li>
        </ul>
        <div class="total-resource fl"><span>共计</span><i><!--资源总数--></i><span>个资源</span></div>
    </div>
    <table class="table-head">
        <thead style="height: 76px;">
        <tr id="scrollTh">
            <th>选择</th>
            <th>平台账号</th>
            <th>平台</th>
            <th>平台ID</th>
            <th class="thead-title">
                <span>粉丝数</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
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
                <span>参考报价</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
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
                <span>平均观看人数</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
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
                <span>价格有效期</span>
                <div class="explain-title">
                    <em></em>
                    <em></em>
                    <div class="explain-content">
                        <h3>价格有效期</h3>
                        <p>网红在该平台上的参考价的有效期。</p>
                    </div>
                </div>
            </th>
            <th>
                <span>操作</span>
            </th>
        </tr>
        </thead>
    </table>
    <!--pjax开始-->
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?php
    if(!empty(yii::$app->request->get('group_uuid'))){
        $group_uuid = "&group_uuid=".yii::$app->request->get('group_uuid');
    }else{
        $group_uuid = "";
    }
    ?>
<?php
$js = <<<JS
    pjaxRefVideo();
JS;
$this->registerJS($js);
?>
    <?= Html::beginForm(Yii::$app->urlManager->createUrl(array('video/media/list')).$group_uuid, 'post', ['data-pjax' => '', 'class' => 'form-inline form-video-search', 'style' => 'display:none', 'id' => 'form-video-search', 'autocomplete' => "off"]); ?>
    <!-- 过滤条件 -->
    <?= Html::input('text', 'search_name', Yii::$app->request->post('search_name'), ['class' => 'form-control input-search-name']) ?>       <!-- 搜索名称/ID -->
    <?= Html::input('text', 'page', Yii::$app->request->post('page',0), ['class' => 'form-control page']) ?>                                <!-- 当前页数 -->
    <?= Html::input('text', 'media_cate', Yii::$app->request->post('media_cate'), ['class' => 'form-control input-media-cate']) ?>          <!-- 账号分类 -->
    <?= Html::input('text', 'follower_area', Yii::$app->request->post('follower_area'), ['class' => 'form-control input-follower-area']) ?> <!-- 地域 -->
    <?= Html::input('text', 'platform_type', Yii::$app->request->post('platform_type'), ['class' => 'form-control input-platform-type']) ?> <!-- 平台类型 -->
    <?= Html::input('text', 'sex', Yii::$app->request->post('sex'), ['class' => 'form-control input-sex']) ?>                               <!-- 性别 -->
    <?= Html::input('text', 'price', Yii::$app->request->post('price'), ['class' => 'form-control input-price']) ?>                           <!-- 参考报价 -->
    <?= Html::input('text', 'follower_num', Yii::$app->request->post('follower_num'), ['class' => 'form-control input-follower-num']) ?>    <!-- 粉丝数 -->
    <?= Html::input('text', 'main_push', Yii::$app->request->post('main_push'), ['class' => 'form-control input-main-push']) ?>             <!-- 主推账号 -->
    <!-- 排序 -->
    <?= Html::input('text', 'sort_by_follower_num', Yii::$app->request->post('sort_by_follower_num'), ['class' => 'form-control input-sort-by-follower-num']) ?>        <!-- 按粉丝数排序-->
    <?= Html::input('text', 'sort_by_price', Yii::$app->request->post('sort_by_price'), ['class' => 'form-control input-sort-by-price']) ?>                             <!-- 按参考报价排序-->
    <?= Html::input('text', 'sort_by_avg_watch_num', Yii::$app->request->post('sort_by_avg_watch_num'), ['class' => 'form-control input-sort-by-avg-watch-num']) ?>     <!-- 按平均观看人数排序-->
    <?= Html::endForm() ?>
    <input class="totalCount" type="hidden" value="<?= $pager->totalCount ?>">
    <table class="table table-video-list">
        <tbody>
        <?php
        if(!empty($queryResult)){
            foreach($queryResult as $key => $video){?>
            <tr <?= $key == 0?'id="scrollTd"':''?> data-uuid="<?= $video['platform_uuid'] ?>">
                <td width="62px" class="select-account">
                    <input type="checkbox" id="account-checkbox-<?=($key+1)?>">
                    <div class="info-json" style="display:none">
                        {
                            "media_uuid" :"<?= $video['platform_uuid'] ?>",
                            "platform" :"<?= $video['platform_type'] ?>",
                            "follower_num" :"<?= $video['follower_num'] ?>",
                            "orig_video_price" :"<?= $video['price_retail_one'] ?>",
                            "offline_price" :"<?= $video['price_retail_two'] ?>",
                            "sex_type" :"<?= $video['sex'] ?>"
                        }
                    </div>
                </td>
                <?php
                switch($video['sex']){//性别
                    case 1:$sex = "男";$sex_icon = "sex-icon-b";break;
                    case 2:$sex = "女";$sex_icon = "sex-icon-g";break;
                    case 0:$sex = "未知";$sex_icon = "";break;
                    default:$sex = "未知";$sex_icon = "";
                }
                $area_array =array_filter(explode('#',$video['address']));//资源地域
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
                <td width="210px" class="account clearfix">
                    <dl class="clearfix">
                        <dt class="fl">
                            <a target="_blank" href="<?=$video['url']?>">
                                <?php if(empty($video['avatar'])){ ?>
                                    <img src="<?=MediaHelper::getMediaVideoDefaultAvatar();?>" alt="">
                                <?php } else { ?>
                                    <img src="<?=$video['avatar']?>" alt="">
                                <?php } ?>

                            </a>
                        </dt>
                        <dd class="fl">
                            <a class="ID-name plain-text-length-limit" data-limit="5" target="_blank" href="<?=$video['url']?>"  data-title="<?=$video['account_name']?>" data-value = '<?=$video['account_id']?>'><?=$video['account_name']?></a>
                            <div class="sex-address">
                                <i class="sex-icon <?=$sex_icon?>"></i>
                                <span><?=$sex?></span>
                                <i class="address-icon"></i>
                                <span><?=$address?></span>
                            </div>
                        </dd>
                    </dl>
                </td>
                <?php
                switch($video['platform_type']){//平台logo
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
                <td width="96px"><i class="platform-icon <?=$platform_icon?>"></i></td>
                <td width="140px">
                    <span><?=($video['account_id'])?"ID:".$video['account_id']:"/";?></span>
                </td>
                <td width="140px"><span><?=round($video['follower_num']/10000,1)?></span>万</td>
                <?php
                //视频价格类型
                if($video['platform_type'] == 5){
                    $price_one = "原创视频";
                    $price_two = "视频转发";
                }else{
                    $price_one = "线上直播";
                    $price_two = "线下活动";
                }
                if($video['price_retail_one']=="0.00"){
                    $price_retail_one = "暂无报价";
                }else{
                    $price_retail_one = MediaHelper::formatMoney($video['price_retail_one']);
                }
                if($video['price_retail_two']=="0.00"){
                    $price_retail_two = "暂无报价";
                }else{
                    $price_retail_two = MediaHelper::formatMoney($video['price_retail_two']);
                }
                ?>
                <td width="180px" class="refer-price">
                    <ul>
                        <li>
                            <span><?=$price_one?>：</span>
                            <i><?=$price_retail_one?></i>
                        </li>
                        <li>
                            <span><?=$price_two?>：</span>
                            <i><?=$price_retail_two?></i>
                        </li>
                    </ul>
                </td>
                <td width="110px"><?=($video['avg_watch_num'])?$video['avg_watch_num']:"/";?></td>
                <td width="120px"><?=date('Y-m-d',$video['active_end_time'])?></td>
                <td width="140px" class="collect">
                    <a class="detail" target="_blank" href="<?=$video['url']?>">
                        <i></i>
                        <span>详情</span>
                    </a>
                    <a href="javascript:void(0)" class="btn-add-media-lib-in-media-list">
                        <i></i>
                        <span>加入媒体库</span>
                    </a>
                    <a href="#">
                        <label for="account-checkbox-<?=($key+1)?>" class="add-appointment">
                            <i></i>
                            <span>预约</span>
                        </label>
                    </a>
                </td>
            </tr>
        <?php }}?>
        </tbody>
    </table>
    <div class="no-resource">无结果</div>


    <div class="table-footer clearfix">
        <form action="">
            <input class="checked-all-resource-input" type="checkbox"
                   id="check-all">
            <label class="checked-all-resource" for="check-all">全选
            </label>
        </form>
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
            <div class="skip">
                <i>共<?= $pager->getPageCount() ?>页</i>,
                跳转<input class="form-control" id="id-custom-page">页,
                <span class="custom-page" style="cursor: pointer">跳转</span>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
<!-- 新建媒体库弹出层-->
<div id="modal-new-media-lib" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <input class="selected-media-uuid-list" type="hidden" value="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">新建媒体库</span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <div class="new-media-name">
                    <span>媒体库名称：</span>
                    <input type="text" class="form-control lib-name" placeholder="请输入新的媒体库名称">
                    <button class="btn btn-danger btn-save">保存</button>
                </div>
            </div>
            <div class="modal-footer">
                <ul>
                    <li>我的媒体库特权</li>
                    <li>1、创建专属个人资源管理库</li>
                    <li>2、帮助您高效地管理自己的自媒体资源</li>
                    <li>3、资源库中的资源随时共享</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- 添加媒体库弹出层-->
<div id="modal-select-media-lib" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <input class="selected-media-uuid-list" type="hidden" value="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">添加到我的媒体库</span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <div class="dropdown first-dropdown">
                    <a type="button" class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="fl">选择媒体库</span>
                        <span class="caret fr"></span>
                    </a>
                    <ul class="dropdown-menu selected-option" role="menu">
                        <li class="option" data-code="select-one">选择媒体库</li>
                        <li class="option" data-code="new-one">新建媒体库</li>
                    </ul>
                </div>
                <div class="media-tab">
                    <div class="selected-media">
                        <div type="button" class="clearfix in-selected-media" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fl">请选择已有的媒体库</span>
                            <span class="caret fr"></span>
                        </div>
                        <div class="dropdown-menu" role="menu">
                            <input type="text" class="form-control input-name" placeholder="搜索已有的媒体库名称">
                            <span class="search-icon"></span>
                            <div class="media-name">
                                <ul>
                                    <!--ajax媒体库列表-->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="form-control lib-name" placeholder="请输入新的媒体库名称">
                </div>
                <button class="btn btn-danger btn-save">保存</button>
            </div>
            <div class="modal-footer">
                <ul>
                    <li>我的媒体库特权</li>
                    <li>1、创建专属个人资源管理库</li>
                    <li>2、帮助您高效地管理自己的自媒体资源</li>
                    <li>3、资源库中的资源随时共享</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- 侧边栏联系方式 -->
<div class="contact">
    <ul>
        <li class="contact-shopping-cart">
            <i></i>
            <em>0</em>
        </li>
        <li>
            <div class="contact-bg">
                <i></i>
            </div>
            <span class="contact-way">联系电话：400-878-9551</span>
        </li>
        <li>
            <div class="contact-bg">
                <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=2&uin=800187006" target="_blank"><i></i></a>
            </div>
            <span class="contact-way">联系QQ：800187006</span>
        </li>
        <li>
            <div class="contact-bg">
                <i></i>
            </div>
            <span class="ewm-pic"><img src="../src/images/wechatcode.png" alt=""></span>

        </li>
    </ul>
</div>
<!-- 购物车开始-->
<div class="right-box">
    <div class="left-border fl">
        <span><i></i></span>
    </div>
    <div class="shopping-car fl">
        <div class="card-head">
            <span>已选账号：<i>0</i>个</span>
            <span class="btn btn-danger delete-all"><i></i>一键清空</span>
        </div>
        <div class="card-body">
            <table class="table">
                <tbody>
                <!--js购物车资源列表-->
                </tbody>
            </table>
        </div>
        <div class="shopping-selected card-footer">
            <ul class="clearfix">
                <li>
                    <span>预计投放金额：</span>
                    <i class="count-sum">0</i>
                    <em>元</em>
                </li>
                <li class="fans-sum">
                    <span>覆盖粉丝量：</span>
                    <i>0</i>
                    <em>个</em>
                </li>
            </ul>
            <button class="btn btn-danger btn-add-media-lib" style="margin-right: 107px">加入媒体库</button>
            <button class="btn btn-put-in-resource btn-danger ">立即预约</button>
        </div>
    </div>
</div>
