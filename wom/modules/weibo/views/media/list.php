<?php
/**
 * 微信资源搜索列表页
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 2016/11/11 11:21
 */
use yii\widgets\Pjax;
use wom\assets\AppAsset;
use yii\helpers\Html;
use common\helpers\MediaHelper;
use yii\helpers\Url;

$this->title = '微博资源搜索列表页';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/media-resource-list-common.css');
AppAsset::addCss($this, '@web/src/css/media-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/weibo/weibo-resource-list.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/dep/js/jquery.fly.min.js');
AppAsset::addScript($this, '@web/src/js/media-resource-list-common.js');
AppAsset::addScript($this, '@web/src/js/weibo/weibo-resource-list.js');

$js = <<<JS
   pjaxRefWeibo();
JS;
?>

<!-- 获取cookie列表资源 -->
<input id="id-get-list-cookie-json-url" type="hidden" value="<?= Url::to(['/weibo/media/get-shopping-car-cookie']) ?>">
<input id="id-plan-uuid" type="hidden" value="<?=yii::$app->request->get('plan_uuid')?>">                               <!--微博预约plan_uuid-->
<input id="id-add-media-plan-order-url" type="hidden" value="<?= Url::to(['/ad-owner/weibo-plan/add-media-plan-order']) ?>">  <!--微博增加预约需求订单url-->
<input id="id-create-plan-order-url" type="hidden" value="<?= Url::to(['/ad-owner/weibo-plan/create-plan-order']) ?>">  <!--微博预约需求订单url-->
<input id="id-get-weibo-library-list-url" type="hidden" value="<?= Url::to(['/ad-owner/weibo-media-lib/get-media-lib-list']) ?>"> <!-- 获取微博媒体库列表 -->
<input id="id-add-media-into-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/weibo-media-lib/add-media-to-lib']) ?>">  <!-- 将微博资源加入媒体库 -->
<input id="id-add-media-into-exist-lib-url" type="hidden" value="<?= Url::to(['/ad-owner/weibo-media-lib/add-media-to-exist-lib']) ?>">  <!-- 将微博资源加入已存在媒体库 -->
<input id="id-group-uuid" type="hidden" value="<?=yii::$app->request->get('group_uuid')?>">                 <!-- 媒体库group_uuid -->
<input id="id-select-item-url" type="hidden" value="<?=Url::to(['/ad-owner/admin-weibo-media-lib/get-group-item'])?>">                 <!-- 获取媒体库资源 -->
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
            <div class="filter-item filter-item-li filter-wb-certification clearfix">
                <h3>微博认证：</h3>
                <span class="filter-unlimit filter-active">不限</span>
                <ul class="clearfix">
                    <li><i data-code="1">蓝V</i></li>
                    <li><i data-code="2">黄V</i></li>
                    <li><i data-code="4">达人</i></li>
                    <li><i data-code="3">草根</i></li>
                </ul>
            </div>
            <div class="filter-item filter-item-li filter-value clearfix">
                <div id="dropdown-price-type" class="dropdown fl">
                    <a type="button" data-value="零售价：" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>硬广参考价</span>
                        <span class="caret fr"></span>
                    </a>
                    <ul class="dropdown-menu" data-type="search" role="menu">
                        <li data-type="micro">硬广参考价</li>
                        <li data-type="soft">软广参考价</li>
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
            <li class="table-active all-account">全部账号</li>
            <li class="main-push">主推账号</li>
        </ul>
        <div class="total-resource fl"><span>共计</span><i><!--资源总数--></i><span>个资源</span></div>
        <div class="sort-condition clearfix fr">
            <span class="fl">价格排序：</span>
            <div id="dropdown-price-sort" class="dropdown fl">
                <a type="button" data-value="零售价：" class="clearfix" data-type="wx" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fl">默认排序</span>
                    <span class="caret fr"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li data-type="">默认排序</li>
                    <li data-type="md-desc">硬广直发从高到低</li>
                    <li data-type="md-asc">硬广直发从低到高</li>
                    <li data-type="mt-desc">硬广转发从高到低</li>
                    <li data-type="mt-asc">硬广转发从低到高</li>
                    <li data-type="sd-desc">软广直发从高到低</li>
                    <li data-type="sd-asc">软广直发从低到高</li>
                    <li data-type="st-desc">软广转发从高到低</li>
                    <li data-type="st-asc">软广转发从低到高</li>
                </ul>
            </div>
        </div>
    </div>
    <table class="table-head">
        <thead style="height: 76px;">
        <tr id="scrollTh">
            <th>选择</th>
            <th>账号名称</th>
            <th>
                <span>粉丝数</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
            </th>
            <th class="thead-title">
                <span>软广参考价</span>
                <div class="explain-title">
                    <em></em>
                    <em></em>
                    <div class="explain-content">
                        <h3>软广参考价</h3>
                        <p>发布软广的直发和转发参考价</p>
                    </div>
                </div>
            </th>
            <th class="thead-title">
                <span>硬广参考价</span>
                <div class="explain-title">
                    <em></em>
                    <em></em>
                    <div class="explain-content">
                        <h3>硬广参考价</h3>
                        <p>发布微任务的直发和转发参考价</p>
                    </div>
                </div>
            </th>
            <th class="thead-title">
                <span>更新时间</span>
                <div class="sort-icon">
                    <i class="up"></i>
                    <i class="down"></i>
                </div>
                <div class="explain-title">
                    <em></em>
                    <em></em>
                    <div class="explain-content">
                        <h3>更新时间</h3>
                        <p>该账号在系统中被维护的最新时间</p>
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
            <th>接单备注</th>
            <th>
                <span>操作</span>
            </th>
        </tr>
        </thead>
    </table>
    <?php Pjax::begin(['linkSelector' => false]); ?>
<?php
$js = <<<JS
    pjaxRefWeibo();
JS;
$this->registerJS($js);
?>
    <?php
    if(!empty(yii::$app->request->get('group_uuid'))){
        $group_uuid = "&group_uuid=".yii::$app->request->get('group_uuid');
    }else{
        $group_uuid = "";
    }
    ?>
    <?= Html::beginForm(Yii::$app->urlManager->createUrl(array('weibo/media/list')).$group_uuid, 'post', ['data-pjax' => '', 'class' => 'form-inline form-weibo-search', 'style' => 'display:none', 'id' => 'form-weibo-search', 'autocomplete' => "off"]); ?>
    <?= Html::input('text', 'search_name', Yii::$app->request->post('search_name'), ['class' => 'form-control input-search-name']) ?>
    <?= Html::input('text', 'price_type', Yii::$app->request->post('price_type','micro'), ['class' => 'form-control input-price-type']) ?>
    <?= Html::input('text', 'retail_price', Yii::$app->request->post('retail_price'), ['class' => 'form-control input-retail-price']) ?>
    <?= Html::input('text', 'follower_num', Yii::$app->request->post('follower_num'), ['class' => 'form-control input-follower-num']) ?>
    <?= Html::input('text', 'follower_area', Yii::$app->request->post('follower_area'), ['class' => 'form-control input-follower-area']) ?>
    <?= Html::input('text', 'media_cate', Yii::$app->request->post('media_cate'), ['class' => 'form-control input-media-cate']) ?>
    <?= Html::input('text', 'media_level', Yii::$app->request->post('media_level'), ['class' => 'form-control input-media-level']) ?>
    <?= Html::input('text', 'page', Yii::$app->request->post('page'), ['class' => 'form-control page']) ?>
    <?= Html::input('text', 'main_push', Yii::$app->request->post('main_push'), ['class' => 'form-control input-main-push']) ?>             <!-- 主推账号 -->
    <!-- 排序 -->
    <!-- 按价格排序-->
    <?= Html::input('text', 'sort_by_price', Yii::$app->request->post('sort_by_price'), ['class' => 'form-control input-sort-by-price']) ?>
    <!-- 按价格有效期排序-->
    <?= Html::input('text', 'sort_by_active_end_time', Yii::$app->request->post('sort_by_active_end_time'), ['class' => 'form-control input-sort-by-active-end-time']) ?>
    <!-- 按粉丝数排序-->
    <?= Html::input('text', 'sort_by_follower_num', Yii::$app->request->post('sort_by_follower_num'), ['class' => 'form-control input-sort-by-follower-num']) ?>
    <!-- 按更新时间排序-->
    <?= Html::input('text', 'sort_by_update_time', Yii::$app->request->post('sort_by_update_time'), ['class' => 'form-control input-sort-by-update-time']) ?>
    <?= Html::endForm() ?>
    <input class="totalCount" type="hidden" value="<?= $pager->totalCount ?>">
    <table class="table table-weibo-list">
        <tbody>
        <?php
        if(!empty($queryResult)){
        foreach($queryResult as $key => $media){
        ?>
        <tr <?= $key == 0?'id="scrollTd"':''?> data-uuid="<?= $media['uuid'] ?>">
            <td width="62px" class="select-account"><input type="checkbox" id="account-checkbox-<?=($key+1)?>"></td>
            <td width="210px" class="account clearfix">
                <a href="<?= $media['weibo_url'] ?>" target="_blank" data-value = '<?= $media['uuid'] ?>'><span class="synopsis" data-str = "6" data-title="<?= $media['weibo_name'] ?>"><?= $media['weibo_name'] ?></span>
                    <i <?php
                    switch($media['media_level']){
                        case 1:echo '';break;
                        case 2:echo 'class="yellow-on"';break;
                        case 4:echo 'class="red-on"';break;
                        default:echo 'style="display:none"';break;
                    }
                    ?>></i></a>
                <ul class="clearfix">
                    <?php
                    $mediaCate = MediaHelper::parseMediaCate($media['media_cate']);
                    $mediaCate = json_decode($mediaCate);
                    if(!empty($mediaCate)){
                        foreach($mediaCate as $cate){
                            ?>
                            <li><?= $cate ?></li>
                    <?php }}?>
                </ul>
            </td>
            <td width="86px"><?= intval($media['follower_num']/10000) ?>万</td>
            <td width="180px" class="refer-price">
                <ul>
                    <li><span>直发：</span><i><?= ($media['sd_price']!="0.00")? MediaHelper::formatMoney($media['sd_price']):"暂无报价"; ?></i></li>
                    <li><span>转发：</span><i><?= ($media['st_price']!="0.00")? MediaHelper::formatMoney($media['st_price']):"暂无报价"; ?></i></li>
                </ul>
            </td>
            <td width="140px" class="refer-price micro-price">
                <div class="price-json" style="display: none">
                    {
                    "media_uuid": "<?= $media['uuid'] ?>",
                    "follower_num": "<?= $media['follower_num'] ?>",
                    "pos_1_retail_price": "<?= $media['mt_price'] ?>"
                    }
                </div>
                <ul>
                    <li><span>直发：</span><i><?= ($media['md_price']!="0.00")? MediaHelper::formatMoney($media['md_price']):"暂无报价"; ?></i></li>
                    <li><span>转发：</span><i><?= ($media['mt_price']!="0.00")? MediaHelper::formatMoney($media['mt_price']):"暂无报价";?></i></li>
                </ul>
            </td>
            <td width="110px"><?= date('Y-m-d', $media['update_time']) ?></td>
            <td width="110px"><?= empty($media['active_end_time'])?'/':date('Y-m-d', $media['active_end_time']) ?></td>
            <td width="160px" class="remark thead-title">
                <span class="synopsis" data-str = "10" data-title="<?= $media['accept_remark'] ?>"><?= $media['accept_remark'] ?></span>
            </td>
            <td width="140px" class="collect">
                <a class="detail" href="<?= $media['weibo_url'] ?>" target="_blank" >
                    <i></i>
                    <span>详情</span>
                </a>
                <a  href="javascript:void(0)" class="btn-add-media-lib-in-media-list" >
                    <i></i>
                    <span>加入媒体库</span>
                </a>
                <a href="#"><label for="account-checkbox-<?=($key+1)?>" class="add-order">
                        <i></i>
                        <span>预约</span>
                    </label></a>
            </td>
        </tr>
        <?php }} ?>
        </tbody>
    </table>
    <div class="no-resource">无结果</div>



    <div class="table-footer clearfix">
        <form action="">
            <input class="checked-all-resource-input" type="checkbox" id="check-all">
            <label class="checked-all-resource" for="check-all">全选</label>
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
            <button class="btn btn-put-in-resource btn-danger">立即预约</button>
        </div>
    </div>
</div>