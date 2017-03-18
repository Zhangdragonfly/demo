<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 1/5/17 8:38 PM
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\grid\GridView;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/report-forms-manage/report-forms-detail.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/echarts.min.js');
AppAsset::addScript($this, '@web/dep/echarts-wordcloud.min.js');
AppAsset::addScript($this, '@web/dep/circles.js');
AppAsset::addScript($this, '@web/dep/circles.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/report-forms-detail.js');

$this->title = '微信报表';

?>

<!-- 主要内容部分 -->
<div class="content-wrap">
    <!--基本信息-->
    <div class="base-info shadow">
        <div class="info-top clearfix">
            <div class="account-pic fl">
                <img src="../../src/images/demo.jpg" alt="">
            </div>
            <div class="account-con-info fl">
                <span class="name">同道大叔</span>
                <span class="id plain-text-length-limit" data-limit="10">people-rmw</span>
                <span class="check-link">检测链接 : <a href="http://www.51wom.com">http://www.51wom.com/</a></span>
            </div>
            <button class="create-report btn btn-danger bg-main fr">生成报告</button>
        </div>
        <div class="data-sum-show info-bottom clearfix">
            <div class="data-show-l fl">
                <ol class="clearfix">
                    <li>
                        <div class="sum-data-type">5600</div>
                        <span class="sum-type">阅读数</span>
                    </li>
                    <li>
                        <span class="sum-data-type">100</span>
                        <span class="sum-type">点赞数</span>
                    </li>
                    <li>
                        <span class="sum-data-type">24次</span>
                        <span class="sum-type">检测次数</span>
                    </li>
                </ol>
            </div>
            <div class="time-show-r fl">
                <ul class="clearfix">
                    <li>
                        <span class="time-pic"></span>
                        <span class="time-typ">文章发布时间 :</span>
                        <span class="time-num">2016.12.24 10:00</span>
                    </li>
                    <li>
                        <span class="time-pic"></span>
                        <span class="time-typ">开始检测时间 :</span>
                        <span class="time-num">2016.12.24 10:00</span>
                    </li>
                    <li>
                        <span class="time-pic"></span>
                        <span class="time-typ">检测时间周期 :</span>
                        <span class="time-num">48</span> 小时
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--数据走势-->
    <div class="data-trend shadow">
        <div class="con-header clearfix">
            <h3 class="headline fl"><i></i>数据走势</h3>
        </div>
        <div class="view-chart">阅读数累计视图</div>
        <div class="read-num-chart" id="read-num-chart"></div>
        <div class="view-chart agree-view-chart">点赞数累计视图</div>
        <div class="agree-num-chart" id="agree-num-chart"></div>
    </div>
    <!--文章内容-->
    <div class="article-con shadow clearfix">
        <div class="con-header clearfix">
            <h3 class="headline fl"><i></i>文章内容</h3>
        </div>
        <div class="key-words-con fl">
            <span>文章关键词</span>
            <div class="key-words" >
                <div id="key-words"></div>
            </div>
        </div>
        <div class="key-words-rank-con fl">
            <span>关键词排名前五</span>
            <div class="key-words-rank" id="key-words-rank"></div>
        </div>

    </div>
    <!--数据详情-->
    <div class="data-detail shadow">
        <div class="con-header clearfix">
            <h3 class="headline fl"><i></i>数据详情</h3>
        </div>
        <table class="table-data-detail">
            <thead>
            <tr>
                <th>时间</th>
                <th>当前阅读数</th>
                <th>新增阅读数</th>
                <th>当前点赞数</th>
                <th>新增点赞数</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            <tr>
                <td>2016.12.11 1200</td>
                <td>1200</td>
                <td>1200</td>
                <td>300</td>
                <td>300</td>
            </tr>
            </tbody>
        </table>
        <!--分页-->
        <div class="table-footer clearfix">
            <div class="page-wb fl system_page">
                <a href="javascript:;" now_num="1" big_num="250" all_num="5000" class="click_prev"> &lt; </a>
                <a href="javascript:;" now_num="1" big_num="250" all_num="5000" class="click_this on"> 1 </a>
                <a href="javascript:;" now_num="1" big_num="250" all_num="5000" class="click_this num"> 2 </a>
                <a href="javascript:;" now_num="1" big_num="250" all_num="5000" class="click_this num"> 3 </a>
                <a href="javascript:;" now_num="1" big_num="250" all_num="5000" class="click_this num"> 4 </a>
                <a href="javascript:;" now_num="1" big_num="250" all_num="5000" class="click_this num"> 5 </a>
                <a href="javascript:;" now_num="1" big_num="250" all_num="5000" class="click_this num"> 6 </a>
                <em> ... </em>
                <a href="javascript:;" now_num="1" big_num="250" all_num="5000" class="click_next num"> &gt; </a>
                <div class="count fl">
                    <i class="fl">前往</i>
                    <input type="text" name="tiaozhuan" class="text fl now_pages">
                    <i class="fl">页</i>
                    <span now_num="1" big_num="250" all_num="5000" class="aok fl start_select">前往</span>
                </div>
            </div>
        </div>
    </div>
</div>
