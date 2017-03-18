<?php
/**
 * 微信资源详情页
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:23 PM
 */
use yii\widgets\Pjax;
use wom\assets\AppAsset;
use yii\helpers\Html;
use common\helpers\MediaHelper;
use yii\helpers\Url;

$this->title = '微信资源详情页';

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/weixin/weixin-resource-detial.css');

AppAsset::addScript($this, '@web/dep/js/echarts.min.js');
AppAsset::addScript($this, '@web/dep/js/circles.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/weixin/weixin-resource-detail.js');

?>
<input type="hidden" id="id-media-uuid" value="<?=$mediaDetail['media_uuid']?>">
<input type="hidden" id="id-get-wmi-url" value="<?=Url::to(['/weixin/media/get-wmi']) ?>">
<input type="hidden" id="id-get-chart-data-url" value="<?=Url::to(['/weixin/media/get-chart-data']) ?>">
<input type="hidden" id="id-get-article-data-url" value="<?=Url::to(['/weixin/media/get-article-data']) ?>">
<!-- 账号基础信息-->
<div class="base-info w-1200 shadow">
    <div class="account-info clearfix">
        <div class="account-con fl clearfix">
            <div class="account-pic fl">
                <img src="http://open.weixin.qq.com/qr/code/?username=<?= $mediaDetail['public_id'] ?>" alt="">
                <i style="<?=($mediaDetail['account_cert'] == 1)?"display:block":"display:none";?>"></i>
            </div>
            <div class="account-con-info fl">
                <div class="account-name"><span><?=$mediaDetail['public_name']?></span><span class="fans-num">粉丝数：</span><i><?= round(intval($mediaDetail['follower_num'] / 10000),2) ?>万</i></div>
                <div class="wx_num"><span>微信号：<i><?=$mediaDetail['public_id']?></i></span></div>
                <div class="wx_approve"><span>微信认证：</span><span><?=empty($mediaDetail['account_cert_info'])?"／":$mediaDetail['account_cert_info']; ?></span></div>
                <div class="account-intro"><span>简介：</span><p class="plain-text-length-limit" data-limit="30" data-title="<?=$mediaDetail['desc']?>"><?=$mediaDetail['desc']?></p></div>
            </div>
            <div class="ewm clearfix">
                <img src="http://open.weixin.qq.com/qr/code/?username=<?= $mediaDetail['public_id'] ?>" width="111px" height="111px" alt="">
                <span class="fr"></span>
            </div>
        </div>
        <div class="account-property fl clearfix">
            <div class="account-property-l fl">
                <div class="account-classify resource-s" >
                    <span>资源标签</span>
                    <ul class="clearfix">
                        <?php
                        $mediaCate = MediaHelper::parseMediaCate($mediaDetail['media_cate']);
                        $mediaCate = json_decode($mediaCate);
                        if(!empty($mediaCate)){
                            foreach($mediaCate as $cate){?>
                                <li><?= $cate ?></li>
                        <?php }}?>
                    </ul>
                </div>
                <div class="account-sign resource-s" style="display: none">
                    <span>沃米分类</span>
                    <ul class="clearfix">
                       <li>段子手</li>
                       <li>娱乐新闻</li>
                       <li>美妆</li>
                    </ul>
                </div>
            </div>
            <div class="account-relate-num resource-s fr" style="display: none">
                <span>关联账号</span>
                <a href="javascript:;"><i></i>人民网</a>
                <a href="javascript:;"><i></i>人民网</a>
            </div>
        </div>
    </div>
    <div class="account-analyze">
        <h5>账号分析：</h5>
        <p>
            <i></i>
            最近发布文章时间是<b><?=$last_post_time;?></b>
        </p>
        <p>
            <i></i>
            最近30天内，总发布文章<b><?=dealReadNum($operation_status_array,'month_post_articles_count')?>篇</b>，
            共发送<b><?=dealReadNum($operation_status_array,'month_post_count')?>次</b>，
            每次平均发布<b><?=dealReadNum($operation_status_array,'month_article_per_post')?>篇</b>,
            全部文章中超过<b>10万+</b>的文章有<b><?= ($mediaDetail['m_10w_article_total_cnt'] !=-1)?$mediaDetail['m_10w_article_total_cnt']:"未知";?>篇</b>
        </p>
        <p>
            <i></i>
            最近30天内，平均每篇头条阅读数约为<b><?= ($mediaDetail['m_head_avg_view_cnt'] !=-1)?$mediaDetail['m_head_avg_view_cnt']:"未知";?></b>，
            头条点赞数约为<b><?= ($mediaDetail['m_head_avg_like_cnt'] !=-1)?$mediaDetail['m_head_avg_like_cnt']:"未知";?></b>，
            头条单次阅读成本<b><?= ($mediaDetail['m_avg_price_pv'] !=-1)?$mediaDetail['m_avg_price_pv']:"未知";?>元</b>

        </p>
    </div>
</div>
<!-- 账号价格-->
<div class="account-price w-1200 shadow">
    <div class="con-header clearfix">
        <span class="headline fl"><i></i>账号价格</span>
        <div class="help-explain fl">
            <i class="help-icon"></i>
            <div class="help-explain-con">
                <em></em>
                <em></em>
                <div class="explain-content">
                    <h3>账号价格</h3>
                    <p>1、投放位置展示四个位置，发布形式按照后台数据展示，当该位置不接单或者是参考价为0时，均用“—”表示。</p>
                    <p>2、平均阅读数分别展示的是近30天每个位置总阅读数与发布文章数的比值。</p>
                    <p>3、接单备注默认展示16个字内【初步定，后续根据实际页面设计效果可做调整】，多余用省略号展示，鼠标放上去展示全部。</p>
                </div>
            </div>
        </div>
    </div>
    <div class="account-price-bd clearfix">
        <div class="pos-price fl">
        <?php   $mediaRetailPriceArray = MediaHelper::parseMediaWeixinRetailPrice($mediaDetail['pub_config']); ?>
        <?php
            function dealReadNum($data_array,$key){
                if(array_key_exists($key,$data_array)){
                    if(!empty($data_array[$key])){
                        if($data_array[$key]<=100000){
                            return $data_array[$key];
                        }else{
                            return "10W+";
                        }
                    }else{
                        return "／";
                    }
                }else{
                    return "／";
                }
            }

        ?>
            <table>
                <thead>
                <tr>
                    <th>投放位置</th>
                    <th>发布形式</th>
                    <th>参考价/元</th>
                    <th>平均阅读数</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>单图文</td>
                    <td><?= $mediaRetailPriceArray['s']['pub_type_label'] ?></td>
                    <td><?= (!empty($mediaRetailPriceArray['s']['retail_price_min']))?MediaHelper::formatMoney($mediaRetailPriceArray['s']['retail_price_min']):"／";?></td>
                    <td><?=dealReadNum($operation_status_array,'month_single_avg_read_num')?></td>
                </tr>
                <tr>
                    <td>多图文第一条</td>
                    <td><?= $mediaRetailPriceArray['m_1']['pub_type_label'] ?></td>
                    <td><?= (!empty($mediaRetailPriceArray['m_1']['retail_price_min']))?MediaHelper::formatMoney($mediaRetailPriceArray['m_1']['retail_price_min']):"／";?></td>
                    <td><?=dealReadNum($operation_status_array,'month_multi_pos_1_avg_read_num')?></td>
                </tr>
                <tr>
                    <td>多图文第二条</td>
                    <td><?= $mediaRetailPriceArray['m_2']['pub_type_label'] ?></td>
                    <td><?= (!empty($mediaRetailPriceArray['m_2']['retail_price_min']))?MediaHelper::formatMoney($mediaRetailPriceArray['m_2']['retail_price_min']):"／";?></td>
                    <td><?=dealReadNum($operation_status_array,'month_multi_pos_2_avg_read_num')?></td>
                </tr>
                <tr>
                    <td>多图文第3~N条</td>
                    <td><?= $mediaRetailPriceArray['m_3']['pub_type_label'] ?></td>
                    <td><?= (!empty($mediaRetailPriceArray['m_3']['retail_price_min']))?MediaHelper::formatMoney($mediaRetailPriceArray['m_3']['retail_price_min']):"／";?></td>
                    <td><?=dealReadNum($operation_status_array,'month_multi_pos_3_avg_read_num')?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="other-info">
            <div class="price-indate resource-s">
                <span>价格有效期</span>
                <p><?=(!empty($mediaDetail['active_end_time']))?date('Y.m.d',$mediaDetail['active_end_time']):"／";?></p>
            </div>
            <div class="order-remarks resource-s">
                <span>接单备注</span>
                <p><?=$mediaDetail['comment']?></p>
            </div>
        </div>
    </div>
</div>
<!-- 数据概要-->
<div class="data-summary w-1200 shadow">
    <div class="con-header clearfix">
        <span class="headline fl"><i></i>数据概要</span>
        <div class="help-explain fl">
            <i class="help-icon"></i>
            <div class="help-explain-con">
                <em></em>
                <em></em>
                <div class="explain-content">
                    <h3>数据概要</h3>
                    <p>数据概要：整体的反应该账号文章发布的质量及性价比。此数据计算按照从今天开始往前推3天的近30天的数据开始计算。</p>
                    <p>1、发布总篇数：历史文章总数  从可检测到的最早文章开始 计算。</p>
                    <p>2、头条平均阅读数：最近30天头条阅读总数与最近30天头条发布文章数的比值。</p>
                    <p>3、平均阅读数：最近30天总阅读数与发布文章数的比值。</p>
                    <p>4、平均点赞数：最近30天总点赞数/发布文章数的比值。</p>
                    <p>5、沃米指数：沃米指数基于微信公众号的粉丝数、文章数据、 近期价格，推出的指数系列，用于衡量微信的传播力、 活跃度和性价比详情见帮助中心沃米指数说明 。</p>
                    <p>6、10w+发布：近30天阅读数超过10w+的文章总数。</p>
                    <p>7、单词阅读成本：头条价格/头条平均阅读数 近30天，如头条 价格不接单或者头条平均阅读数大于10万+， 则单次阅读成本按照多图文二条价格/多图文二条的平均 阅读数，以此类推。
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="data-sum-show clearfix">
        <div class="data-show-l fl">
            <ul class="clearfix">
                <li>
                    <span class="data-pic"></span>
                    <span class="data-typ">头条平均阅读数</span>
                    <span class="data-num"><?= ($mediaDetail['m_head_avg_view_cnt'] !=-1)?$mediaDetail['m_head_avg_view_cnt']:"/";?></span>
                </li>
                <li>
                    <span class="data-pic"></span>
                    <span class="data-typ">发布总篇数</span>
                    <span class="data-num"><?= ($mediaDetail['total_article_cnt'] !=-1)?$mediaDetail['total_article_cnt']:"/";?></span>
                </li>
                <li>
                    <span class="data-pic"></span>
                    <span class="data-typ">平均阅读数</span>
                    <span class="data-num"><?= ($mediaDetail['m_avg_view_cnt'] !=-1)?$mediaDetail['m_avg_view_cnt']:"/";?></span>
                </li>
                <li>
                    <span class="data-pic"></span>
                    <span class="data-typ">平均点赞数</span>
                    <span class="data-num"><?= ($mediaDetail['m_avg_like_cnt'] !=-1)?$mediaDetail['m_avg_like_cnt']:"/";?></span>
                </li>
            </ul>
        </div>
        <div class="data-show-r fl">
            <ol class="clearfix">
                <li class="figure">
                    <div class="" id="sum-data-type"></div>
                    <span class="sum-type">沃米指数</span>
                </li>
                <li>
                    <span class="sum-data-type"><?= ($mediaDetail['m_10w_article_total_cnt'] !=-1)?$mediaDetail['m_10w_article_total_cnt']:"／";?>篇</span>
                    <span class="sum-type">10万+发布</span>
                </li>
                <li>
                    <span class="sum-data-type"><?=$mediaDetail['m_avg_price_pv']?>元</span>
                    <span class="sum-type">单次阅读成本</span>
                </li>
            </ol>
        </div>
    </div>
</div>
<!-- 运维情况-->
<div class="data-show-map w-1200 shadow">
    <div class="con-header clearfix">
        <span class="headline fl"><i></i>运维情况</span>
        <div class="help-explain fl">
            <i class="help-icon"></i>
            <div class="help-explain-con">
                <em></em>
                <em></em>
                <div class="explain-content">
                    <h3>运维情况</h3>
                    <p>运维情况：根据近一个月该账号发布文章的数量、发布的次数来突显本月该账号的活跃度。</p>
                    <p>1、30天发布总篇数：近30天发布历史文章总数。</p>
                    <p>2、30天发布总次数：近30天发布文章的次数。</p>
                    <p>3、每次发布文章数：近30天每次发布文章数（近30天发布总篇数/30天发布总次数）。</p>
                    <p>4、发稿量趋势：横坐标展示近一个月，每天发布的稿件数量。</p>
                    <p>5、发布时间周分布：展示近30天的周一到周日，每天平均发稿数。</p>
                    <p>6、发布时间日发布：近30天0-24点每个时间段的发稿总数。0~24点 分8段。5点半到6点半之间发布认为是6时这个时段的，其他以此类推。</p>
                </div>
            </div>
        </div>
    </div>
    <div class="total-data-map clearfix">
        <div class="data-map-l fl">
            <div class="art-data">
                <ul class="clearfix">
                    <li>
                        <span class="art-num"><?=dealReadNum($operation_status_array,'month_post_articles_count')?>篇</span>
                        <span class="art-type">30天发布总篇数</span>
                    </li>
                    <li>
                        <span class="art-num"><?=dealReadNum($operation_status_array,'month_post_count')?>次</span>
                        <span class="art-type">30天发布总次数</span>
                    </li>
                    <li>
                        <span class="art-num"><?=dealReadNum($operation_status_array,'month_article_per_post')?>篇</span>
                        <span class="art-type">每次发布文章数</span>
                    </li>
                </ul>
            </div>
            <div class="read-num map-property">
                <div class="con-header clearfix">
                    <span class="headline fl"><i></i>阅读数</span>
                </div>
                <div class="read-num-map" id="read-num-map"></div>
            </div>
            <div class="pub-date map-property">
                <div class="con-header clearfix">
                    <span class="headline fl"><i></i>发布时间日分布</span>
                </div>
                <div class="pub-date-map" id="pub-date-map"></div>
            </div>
        </div>
        <div class="data-map-r fr">
            <div class="trend-data map-property">
                <div class="con-header clearfix">
                    <span class="headline fl"><i></i>发稿量趋势</span>
                </div>
                <div class="trend-data-map" id="trend-data-map">

                </div>
            </div>
            <div class="like-num map-property">
                <div class="con-header clearfix">
                    <span class="headline fl"><i></i>点赞数</span>
                </div>
                <div class="like-num-map" id="like-num-map"></div>
            </div>
            <div class="pub-date-week map-property">
                <div class="con-header clearfix">
                    <span class="headline fl"><i></i>发布时间周分布</span>
                </div>
                <div class="pub-date-week-map" id="pub-date-week-map"></div>
            </div>
        </div>
    </div>
</div>
<!-- 文章-->
<div class="total-art clearfix">
    <div class="hot-art w-592 shadow fl">
        <div class="con-header clearfix">
            <span class="headline fl"><i></i>30天最热文章</span>
        </div>
        <div class="total-hot-art total-hot-art-top">
        <!--ajax加载文章数据-->
        </div>
    </div>
    <div class="lately-art w-592 shadow fr">
        <div class="con-header clearfix">
            <span class="headline fl"><i></i>最近发布</span>
        </div>
        <div class="total-hot-art total-hot-art-last">
            <!--ajax加载文章数据-->
        </div>
    </div>
</div>
<!-- 更多-->
<!--<div class="more-art">-->
<!--    <a href="#">查看更多>></a>-->
<!--</div>-->




