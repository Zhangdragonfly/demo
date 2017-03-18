<?php
/**
 * 微信资源搜索列表页
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:23 PM
 */
use yii\widgets\Pjax;
use wom\assets\AppAsset;
use yii\helpers\Html;
use common\helpers\MediaHelper;
use yii\helpers\Url;

$this->title = '微信资源搜索列表页';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/media-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/media-resource-list-common.css');
AppAsset::addCss($this, '@web/src/css/weixin/resource-list.css');

AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/dep/js/jquery.fly.min.js');
AppAsset::addScript($this, '@web/src/js/media-resource-list-common.js');
AppAsset::addScript($this, '@web/src/js/weixin/resource-list.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');

$js = <<<JS
    pjaxRef();
JS;
?>



<!-- 获取cookie列表资源 -->
<input id="id-get-list-cookie-json-url" type="hidden" value="<?= Url::to(['/weixin/media/get-shopping-car-cookie']) ?>">
<!-- 将资源加入媒体库 -->
<input id="id-add-media-into-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-media-lib/add-media']) ?>">
<input id="id-weixin-media-lib-uuid" type="hidden" value="<?= Yii::$app->request->get('lib_uuid', '') ?>">
<!-- 获取微信媒体库列表 -->
<input id="id-get-media-library-list-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-media-lib/get-all']) ?>">
<!-- 创建投放活动 -->
<input id="id-create-plan-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/create', 'route' => 2]) ?>">
<!-- 填写投放内容 -->
<input id="id-confirm-plan-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/confirm', 'plan_uuid' => '_plan_uuid_']) ?>">
<!-- 更新微信plan -->
<input id="id-update-plan-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-plan/update', 'plan_uuid' => '_plan_uuid_']) ?>">
<!-- 把媒体加入到plan里 -->
<input id="id-add-media-into-plan-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/add-media-into-plan']) ?>">
<!-- plan uuid -->
<input id="id-plan-uuid" type="hidden" value="<?= Yii::$app->request->get('plan_uuid', '') ?>">
<!-- plan create type -->
<input id="id-plan-action-type" type="hidden" value="<?= Yii::$app->request->get('plan_action') ?>">
<input id="id-weixin-media-lib-uuid" type="hidden" value="<?= Yii::$app->request->get('lib_uuid', '') ?>">
<input id="id-group-uuid" type="hidden" value="<?=yii::$app->request->get('lib_uuid')?>">                 <!-- 媒体库group_uuid -->
<input id="id-select-item-url" type="hidden" value="<?=Url::to(['/ad-owner/admin-weixin-media-lib/get-group-item'])?>">                 <!-- 获取媒体库资源 -->
<input id="id-search-name" type="hidden" value="<?= Yii::$app->request->get('search_name', '') ?>">                 <!-- 搜索条件 -->


<!-- 搜索条件选择-->
<div class="condition">
    <div class="condition-filter">
        <div class="condition-classify">
            <div class="filter-item filter-item-li filter-ID-sign clearfix">
                <h3>分类：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <?php
                    $mediaTagList = MediaHelper::getMediaCateList();
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
                        1 => '北京', 9 => '上海', 289 => '广州', 291 => '深圳', 162 => '南京', 175 => '杭州', 2 => '天津', 385 => '成都', 22 => '重庆', 3 => '河北', 4 => '山西', 5 => '内蒙古', 6 => '辽宁', 7 => '吉林', 8 => '黑龙江', 10 => '江苏', 11 => '浙江', 12 => '安徽', 13 => '福建', 14 => '江西', 15 => '山东', 16 => '河南', 17 => '湖北', 18 => '湖南', 19 => '广东', 20 => '广西', 21 => '海南', 23 => '四川', 24 => '贵州', 25 => '云南', 26 => '西藏', 27 => '陕西', 28 => '甘肃', 29 => '青海', 30 => '宁夏', 31 => '新疆', 33 => '香港', 34 => '澳门', 32 => '台湾', 73 => '石家庄', 84 => '太原', 107 => '沈阳', 108 => '大连', 121 => '长春', 130 => '哈尔滨', 166 => '苏州', 163 => '无锡', 176 => '宁波', 186 => '合肥', 203 => '福州', 204 => '厦门', 212 => '南昌', 223 => '济南', 224 => '青岛', 240 => '郑州', 258 => '武汉', 275 => '长沙', 310 => '南宁', 324 => '海口', 406 => '贵阳', 415 => '昆明', 438 => '西安', 448 => '兰州', 462 => '西宁', 35 => '海外',];//MediaHelper::getFollowerAreaList();
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
                <span class="select-more">更多</span>
                <span class="limit-three">支持多选（最多选择3个）</span>
            </div>
            <div class="filter-item filter-item-li filter-ID-type clearfix">
                <h3>类型：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <li><i data-code="1">媒体</i></li>
                    <li><i data-code="2">机构</i></li>
                    <li><i data-code="3">个人</i></li>
                    <li><i data-code="-1">其他</i></li>
                </ul>
            </div>
            <div class="filter-item filter-item-li filter-headline-read clearfix">
                <h3>头条阅读数：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <li><i data-min="1" data-max="5000" data-label="5000以下">5000以下</i></li>
                    <li><i data-min="5000" data-max="10000" data-label="5000-10000">5000-1万</i></li>
                    <li><i data-min="10000" data-max="50000" data-label="10000-50000">1万-5万</i></li>
                    <li><i data-min="50000" data-max="100000" data-label="50000-100000">5万-10万</i></li>
                    <li><i data-min="100000" data-max="n" data-label="100000+">10万+</i></li>
                </ul>
            </div>
            <div class="filter-item filter-item-li filter-value clearfix">
                <div class="dropdown fl">
                    <a type="button" data-value="零售价：" data-type="wx" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        <span>多图文头条价格</span>
                        <span class="caret fr"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-weixin" role="menu">
                        <li>多图文头条价格</li>
                        <li>多图文2条价格</li>
                        <li>多图文3~N条价格</li>
                        <li>单图文价格</li>
                    </ul>
                </div>
                <em class="fl" style="margin-top: 15px">：</em>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <li><i data-min="1" data-max="500" data-label="500元以下">500元以下</i></li>
                    <li><i data-min="500" data-max="1000" data-label="500-1000">500-1000元</i></li>
                    <li><i data-min="1000" data-max="2000" data-label="1000-2000">1000-2000元</i></li>
                    <li><i data-min="2000" data-max="5000" data-label="30000-50000">2000-5000元</i></li>
                    <li><i data-min="5000" data-max="10000" data-label="5000-10000">5000-1万元</i></li>
                    <li><i data-min="10000" data-max="n" data-label="10000元以上">1万元以上</i></li>
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
                    <li><i data-min="1" data-max="10000" data-label="10000元以下">1万以下</i></li>
                    <li><i data-min="10000" data-max="50000" data-label="10000-50000">1-5万</i></li>
                    <li><i data-min="50000" data-max="100000" data-label="50000-100000">5-10万</i></li>
                    <li><i data-min="100000" data-max="200000" data-label="100000-200000">10-20万</i></li>
                    <li><i data-min="200000" data-max="400000" data-label="200000-400000">20-40万</i></li>
                    <li><i data-min="400000" data-max="800000" data-label="500元以下">40-80万</i></li>
                    <li><i data-min="800000" data-max="n" data-label="800000以上">80万以上</i></li>
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
                <!--<li>
                    <span>分类：</span><em>美容美妆</em>
                    <i class="clear-icon"></i>
                </li>-->
                <li class="selected-last-li">
                    <span>全部删除</span>
                </li>

            </ul>
        </div>
    </div>
</div>

<!-- 表格开始-->
<div class="table-item media-stage">

    <div class="table-title clearfix">
        <ul class="clearfix title-select fl ">
            <li class="table-active">全部账号</li>
            <li>主推账号</li>
        </ul>
        <div class="total-resource fl"><span>共计</span><i><?= $pager->totalCount ?></i><span>个资源</span></div>
        <div class="sort-condition clearfix fr">
            <div class="publish-type fl clearfix">
                <span class="fl">发布形式：</span>
                <div class="dropdown fl">
                    <a type="button" data-value="零售价：" class="clearfix" data-type="wx" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <span class="fl">不限</span>
                        <span class="caret fr"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-weixin" role="menu">
                        <li>不限</li>
                        <li>直接发布</li>
                        <li>原创</li>
                    </ul>
                </div>
            </div>
            <div class="price-sort fl clearfix">
                <span class="fl">价格排序：</span>
                <div class="dropdown fl">
                    <a type="button" data-value="零售价：" class="clearfix" data-type="wx" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <span class="fl">默认排序</span>
                        <span class="caret fr"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-weixin" role="menu">
                        <li>默认排序</li>
                        <li>单图文价格从高到低</li>
                        <li>单图文价格从低到高</li>
                        <li>多图文头条价格从高到低</li>
                        <li>多图文头条价格从低到高</li>
                        <li>多图文2条价格从高到低</li>
                        <li>多图文2条价格从低到高</li>
                        <li>多图文3~N条价格从高到低</li>
                        <li>多图文3~N条价格从低到高</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <table class="table-head">
        <thead style="height: 76px;">
        <tr id="scrollTh">
            <th>选择</th>
            <th>微信账号</th>
            <th>
                <span>粉丝数</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
            </th>
            <th>参考价（元）</th>
            <th class="thead-title">
                <span>头条平均阅读数</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
                <div class="explain-title">
                    <em></em>
                    <em></em>
                    <div class="explain-content">
                        <h3>头条平均阅读数</h3>
                        <p>最近30天头条阅读总数与最近30天头条发布文章数的比值
                        </p>
                    </div>
                </div>
            </th>
            <th class="thead-title">
                <span>单次阅读成本</span>
                <div class="explain-title">
                    <em></em>
                    <em></em>
                    <div class="explain-content">
                        <h3>单次阅读成本</h3>
                        <p>头条价格/头条平均阅读数 近30天，如头条价格不接单或者头条平均阅读数大于10万+，则单次阅读成本按照多图文二条价格/多图文二条的平均阅读数，以此类推。</p>
                    </div>
                </div>
            </th>
            <th class="thead-title">
                <span>沃米指数</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
                <div class="explain-title">
                    <em></em>
                    <em></em>
                    <div class="explain-content">
                        <h3>沃米指数</h3>
                        <p>沃米指数基于微信公众号的粉丝数、文章数据、近期价格，推出的指数系列，用于衡量微信的传播力、活跃度和性价比详情见帮助中心沃米指数说明。
                    </div>
                </div>
            </th>
            <th class="thead-title">
                <span>价格有效期</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
                <div class="explain-title">
                    <em></em>
                    <em></em>
                    <div class="explain-content">
                        <h3>价格有效期</h3>
                        <p>该账号的参考零售价的截止日期</p>
                    </div>
                </div>
            </th>
            <th>
                <span>操作</span>
            </th>
        </tr>
        </thead>
    </table>
    <?php
    if(!empty(yii::$app->request->get('lib_uuid'))){
        $lib_uuid = "&lib_uuid=".yii::$app->request->get('lib_uuid');
    }else{
        $lib_uuid = "";
    }
    ?>
    <?php Pjax::begin(['linkSelector' => false]); ?>
    <?= Html::beginForm(Yii::$app->urlManager->createUrl(array('weixin/media/list')).$lib_uuid, 'post', ['data-pjax' => '', 'class' => 'form-inline form-weixin-search', 'style' => 'display:none', 'id' => 'form-weixin-search', 'autocomplete' => "off"]); ?>
    <!-- 过滤条件 -->
    <!-- 微信账号 or ID-->
    <?= Html::input('text', 'search_name', Yii::$app->request->post('search_name'), ['class' => 'form-control input-search-name']) ?>
    <!-- 账号分类 -->
    <?= Html::input('text', 'media_cate', Yii::$app->request->post('media_cate'), ['class' => 'form-control input-media-cate']) ?>
    <?= Html::input('text', 'belong_tag', Yii::$app->request->post('belong_tag'), ['class' => 'form-control input-belong-tag']) ?>
    <!-- 零售价格 -->
    <?= Html::input('text', 'retail_price', Yii::$app->request->post('retail_price'), ['class' => 'form-control input-retail-price']) ?>
    <!-- 零售价格类型 -->
    <?= Html::input('text', 'retail_price_type', Yii::$app->request->post('retail_price_type'), ['class' => 'form-control input-retail-price-type']) ?>
    <!-- 发布类型 -->
    <?= Html::input('text', 'pub_type', Yii::$app->request->post('pub_type',-1), ['class' => 'form-control input-pub-type']) ?>
    <!-- 粉丝数量 -->
    <?= Html::input('text', 'follower_num', Yii::$app->request->post('follower_num'), ['class' => 'form-control input-follower-num']) ?>
    <!-- 地域 -->
    <?= Html::input('text', 'follower_area', Yii::$app->request->post('follower_area'), ['class' => 'form-control input-follower-area']) ?>
    <!-- 头条平均阅读数 -->
    <?= Html::input('text', 'read_num', Yii::$app->request->post('read_num'), ['class' => 'form-control input-read-num']) ?>
    <!-- 当前页数 -->
    <?= Html::input('text', 'page', Yii::$app->request->post('page'), ['class' => 'form-control page']) ?>
    <!-- 主推 -->
    <?= Html::input('text', 'is_push', Yii::$app->request->post('is_push'), ['class' => 'form-control is-push']) ?>

    <!-- 排序 -->
    <!-- 按粉丝数排序-->
    <?= Html::input('text', 'sort_by_follower_num', Yii::$app->request->post('sort_by_follower_num'), ['class' => 'form-control input-sort-by-follower-num']) ?>
    <!-- 按零售价排序-->
    <?= Html::input('text', 'sort_by_retail_price', Yii::$app->request->post('sort_by_retail_price'), ['class' => 'form-control input-sort-by-retail-price']) ?>
    <!-- 按多图文头条平均阅读数排序-->
    <?= Html::input('text', 'sort_by_m_1_avg_read_num', Yii::$app->request->post('sort_by_m_1_avg_read_num'), ['class' => 'form-control input-sort-by-m-1-avg-read-num']) ?>
    <!-- 按沃米指数排序-->
    <?= Html::input('text', 'sort_by_wom_num', Yii::$app->request->post('sort_by_wom_num'), ['class' => 'form-control input-sort-by-wom-num']) ?>
    <!-- 按最近更新时间排序-->
    <?= Html::input('text', 'sort_by_last_update_time', Yii::$app->request->post('sort_by_last_update_time'), ['class' => 'form-control input-sort-by-last-upate-time']) ?>
    <!-- 按价格有效期排序-->
    <?= Html::input('text', 'sort_by_active_end_time', Yii::$app->request->post('sort_by_active_end_time'), ['class' => 'form-control input-sort-by-active-end-time']) ?>

    <?= Html::endForm() ?>

    <!--搜索结果列表-->
    <table class="table media-table">
        <tbody>
        <?php
        if (!empty($queryResult)) {
            $pubType = Yii::$app->request->post('pub_type',-1);
            if($pubType == ''){
                $pubType = -1;
            }
            foreach ($queryResult as $key => $media) {
                $mediaRetailPriceArray = MediaHelper::parseMediaWeixinRetailPrice($media['pub_config']);
                ?>
                <tr <?= $key == 0 ? 'id="scrollTd"' : '' ?> class="one-media" data-media-uuid="<?= $media['media_uuid']; ?>">
                    <td width="62px" class="select-account">
                        <input type="checkbox" id="account-checkbox-<?= $key ?>">

                        <div class="media-info-json" style="display: none">
                            {
                            "media_uuid": "<?= $media['media_uuid'] ?>",
                            "follower_num": <?= $media['follower_num'] ?>,
                            "pos_1_retail_price": <?= $mediaRetailPriceArray['m_1']['retail_price_min'] ?>
                            }
                        </div>
                    </td>
                    <td width="150px" class="account clearfix">
                        <dl class="clearfix" style="width: 170px">
                            <dt class="fl">
                                <a target="_blank" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => $media['media_uuid']]); ?>">
                                    <img src="http://open.weixin.qq.com/qr/code/?username=<?= $media['public_id'] ?>" alt="">
                                </a>
                                <i style="<?=($media['account_cert'] == 1)?"display:block":"display:none";?>"></i>
                            </dt>
                            <dd class="fl" style="width: 100px">
                                <a class="account-name plain-text-length-limit" data-limit="5" target="_blank" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => $media['media_uuid']]); ?>"
                                   data-value='<?=$media['media_uuid']?>' data-title="<?= $media['public_name'] ?>"><?= $media['public_name'] ?></a>
                                <div class="account-id-area">
                                    <i></i>
                                    <span class="plain-text-length-limit" data-limit="8"><?= $media['public_id'] ?></span>
                                    <img src="http://open.weixin.qq.com/qr/code/?username=<?= $media['public_id'] ?>"
                                         height="80px" width="80px" alt="">
                                </div>
                            </dd>
                        </dl>
                    </td>
                    <td width="86px"><?= intval($media['follower_num'] / 10000) ?>万</td>
                    <td width="260px" class="refer-price">
                        <ul>
                            <!-- 单图文  -->
                            <?php
                                if($mediaRetailPriceArray['s']['retail_price_min']>0){
                                    if($pubType == -1 || ($pubType != -1 && $mediaRetailPriceArray['s']['pub_type'] == $pubType)){
                            ?>
                            <li>
                                <span>单图文：</span><i><?= MediaHelper::formatMoney($mediaRetailPriceArray['s']['price_label'])?></i><em><?= $mediaRetailPriceArray['s']['pub_type'] == 2?'（原创）':'' ?></em>
                            </li>
                            <?php }} ?>
                            <!-- 多图文头条  -->
                            <?php
                                if($mediaRetailPriceArray['m_1']['retail_price_min']>0){
                                    if($pubType == -1 || ($pubType != -1 && $mediaRetailPriceArray['m_1']['pub_type'] == $pubType)){
                            ?>
                            <li>
                                <span>多图文头条：</span><i><?= MediaHelper::formatMoney($mediaRetailPriceArray['m_1']['price_label']) ?></i><em><?= $mediaRetailPriceArray['m_1']['pub_type'] == 2?'（原创）':'' ?></em>
                            </li>
                            <?php } }?>
                            <!-- 多图文2条  -->
                            <?php
                                if($mediaRetailPriceArray['m_2']['retail_price_min']>0){
                                    if($pubType == -1 || ($pubType != -1 && $mediaRetailPriceArray['m_2']['pub_type'] == $pubType)){
                            ?>
                            <li>
                                <span>多图文2条：</span><i><?= MediaHelper::formatMoney($mediaRetailPriceArray['m_2']['price_label']) ?></i><em><?= $mediaRetailPriceArray['m_2']['pub_type'] == 2?'（原创）':'' ?></em>
                            </li>
                            <?php }}?>
                            <!-- 多图文3-N条  -->
                            <?php
                                if($mediaRetailPriceArray['m_3']['retail_price_min']>0){
                                    if($pubType == -1 || ($pubType != -1 && $mediaRetailPriceArray['m_3']['pub_type'] == $pubType)){
                            ?>
                            <li>
                                <span>多图文3-N条：</span><i><?= MediaHelper::formatMoney($mediaRetailPriceArray['m_3']['price_label']) ?></i><em><?= $mediaRetailPriceArray['m_3']['pub_type'] == 2?'（原创）':'' ?></em>
                            </li>
                            <?php }}?>
                        </ul>
                    </td>
                    <?php
                    if($media['head_avg_view_cnt'] > -1){
                        if($media['head_avg_view_cnt'] > 100000){
                            $h_avg_view_cnt = "100000+";
                        }else{
                            $h_avg_view_cnt = MediaHelper::formatMoney($media['head_avg_view_cnt']);
                        }
                    }else{
                        $h_avg_view_cnt = "/";
                    }

                    ?>
                    <td width="140px"><?= $h_avg_view_cnt?></td>
                    <td width="130px"><?= $media['avg_price_pv'] ?></td>
                    <td width="110px"><?= $media['wmi'] ?></td>
                    <td width="120px"><?= empty($media['active_end_time']) ? '/' : date('Y-m-d', $media['active_end_time']) ?></td>
                    <td width="140px" class="collect">
                        <a class="detail" target="_blank" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => $media['media_uuid']]); ?>">
                            <i></i>
                            <span>详情</span>
                        </a>
                        <a class="btn-add-media-lib-in-media-list" href="javascript:void(0)">
                            <i></i>
                            <span data-uuid="<?= $media['media_uuid'] ?>">加入媒体库</span>
                        </a>
                        <a href="#"><label for="account-checkbox-<?= $key ?>" class="add-car">
                                <i></i>
                                <span>投放</span>
                            </label></a>
                    </td>
                </tr>
                <?php
            }
        } ?>
        </tbody>
    </table>
    <div class="no-resource"><span>无结果</span></div>

    <!--分页-->
    <div class="table-footer clearfix">
        <form action="">
            <input class="checked-all-resource-input" type="checkbox" id="check-all">
            <label class="checked-all-resource" for="check-all">全选</label>
        </form>
        <div class="page-wb fl system_page" data-value="<?= $pager->totalCount ?>">
            <?= \yii\widgets\LinkPager::widget([
                //'options' => ['class' => ''],
                //'pageCssClass' => ['class' => ''],
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

    <?php
    $this->registerJS($js);
    Pjax::end();
    ?>
</div>

<!-- 购物车开始-->
<div class="right-box">
    <div class="shopping-column left-border fl">
        <span><i></i></span>
    </div>
    <div class="shopping-car fl">
        <div class="card-head">
            <span>已选账号：<i>0</i>个</span>
            <span class="btn btn-danger delete-all"><i></i>一键清空</span>
        </div>
        <div class="card-body">
            <table class="table selected-media">
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="card-footer">
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
            <button class="btn btn-danger btn-put-in-resource">立即投放</button>
        </div>
    </div>
</div>

<!-- 新建媒体库弹出层-->
<div id="modal-new-media-lib" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">新建媒体库</span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <input class="selected-media-uuid-list" type="hidden" value="">
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

<!-- 选择媒体库弹出层 -->
<div id="modal-select-media-lib" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <span class="fl">添加到媒体库</span><i class="close fr" data-dismiss="modal"></i>
            </div>
            <div class="modal-body">
                <input class="selected-media-uuid-list" type="hidden" value="">
                <input class="selected-option" type="hidden" value="select-one">
                <div class="dropdown first-dropdown">
                    <a type="button" class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        <span class="fl">选择媒体库</span>
                        <span class="caret fr"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lib selected-option" role="menu">
                        <li class="option" data-code="select-one">选择媒体库</li>
                        <li class="option" data-code="new-one">新建媒体库</li>
                    </ul>
                </div>
                <div class="media-tab">
                    <div class="selected-media">
                        <div type="button" class="clearfix in-selected-media" data-type="wx" data-toggle="dropdown"
                             aria-haspopup="true" aria-expanded="false">
                            <span class="fl">请选择已有的媒体库</span>
                            <span class="caret fr"></span>
                        </div>
                        <div class="dropdown-menu" role="menu">
                            <input type="text" class="form-control input-name" placeholder="搜索已有的媒体库" data-url="<?= Url::to(['/ad-owner/weixin-media-lib/search']) ?>">
                            <span class="search-icon"></span>
                            </input>
                            <div class="media-name">
                                <ul></ul>
                            </div>
                        </div>
                    </div>
                    <input type="text" style="display: none" class="form-control lib-name" placeholder="请输入新的媒体库名称">
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


<input type="hidden" class="csrf" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken(); ?>"/>
