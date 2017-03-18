<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 1/5/17 8:38 PM
 */

use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::register($this);
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/ad-owner-user-admin/report-forms-manage/report-forms-list.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');
AppAsset::addScript($this, '@web/src/js/ad-owner-user-admin/report-forms-list.js');

$this->title = '微信报表';

?>

<?php $this->beginBlock('level-1-nav'); ?>
报表管理
<?php $this->endBlock(); ?>
<?php $this->beginBlock('level-2-nav'); ?>
微信报表
<?php $this->endBlock(); ?>

<!--右侧内容-->
<div class="content fr">
    <div class="con-top shadow clearfix">
        <div class="account-name fl font-14 font-500">
            账号名称/ID : <input type="text">
        </div>
        <div class="publish-con-title fl font-14 font-500">
            发布内容标题 : <input type="text">
        </div>
        <button class="btn-search btn btn-danger bg-main fr"><i></i>搜索</button>
    </div>
    <div class="table shadow">
        <table>
            <thead>
            <tr>
                <th>选择</th>
                <th>订单号</th>
                <th>投放账号</th>
                <th>投放位置</th>
                <th>阅读量</th>
                <th>点赞数</th>
                <th>发布内容标题</th>
                <th>发布链接</th>
                <th>发布时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td class="order-number">20161224</td>
                <td class="account" width="150">
                    <a href="javascript:;" class="plain-text-length-limit" data-limit="7" data-title="玩车教授erp雷雨晴雷教授">玩车教授雷雨晴雷教授</a>
                    <span class="plain-text-length-limit" data-limit="16">wanchejiaoshou阿萨德啊adadas</span>
                    <span class="follower-account">粉丝数 : 12万</span>
                </td>
                <td class="msg-status">
                    <span>多图文一条</span>
                    <span>(只原创)</span>
                </td>
                <td class="reading-account">25000</td>
                <td class="agree-account">200</td>
                <td class="title-publish-con">双十二推广</td>
                <td class="publish-link" width="140"><span class="publish-link-ellipsis plain-text-length-limit" data-limit="20" data-title="http://www.51wom.local">http://www.51wom.local</span></td>
                <td class="publish-time">2016.12.10</td>
                <td class="operate"><a href="Javascript:;">查看报表</a></td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td class="order-number">20161224</td>
                <td class="account" width="150">
                    <a href="javascript:;" class="plain-text-length-limit" data-limit="7" data-title="玩车教授erp雷雨晴雷教授">玩车教授雷雨晴雷教授</a>
                    <span class="plain-text-length-limit" data-limit="16">wanchejiaoshou阿萨德啊adadas</span>
                    <span class="follower-account">粉丝数 : 12万</span>
                </td>
                <td class="msg-status">
                    <span>多图文一条</span>
                    <span>(只原创)</span>
                </td>
                <td class="reading-account">25000</td>
                <td class="agree-account">200</td>
                <td class="title-publish-con">双十二推广</td>
                <td class="publish-link" width="140"><span class="publish-link-ellipsis plain-text-length-limit" data-limit="20" data-title="http://www.51wom.local">http://www.51wom.local</span></td>
                <td class="publish-time">2016.12.10</td>
                <td class="operate"><a href="Javascript:;">查看报表</a></td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td class="order-number">20161224</td>
                <td class="account" width="150">
                    <a href="javascript:;" class="plain-text-length-limit" data-limit="7" data-title="玩车教授erp雷雨晴雷教授">玩车教授雷雨晴雷教授</a>
                    <span class="plain-text-length-limit" data-limit="16">wanchejiaoshou阿萨德啊adadas</span>
                    <span class="follower-account">粉丝数 : 12万</span>
                </td>
                <td class="msg-status">
                    <span>多图文一条</span>
                    <span>(只原创)</span>
                </td>
                <td class="reading-account">25000</td>
                <td class="agree-account">200</td>
                <td class="title-publish-con">双十二推广</td>
                <td class="publish-link" width="140"><span class="publish-link-ellipsis plain-text-length-limit" data-limit="20" data-title="http://www.51wom.local">http://www.51wom.local</span></td>
                <td class="publish-time">2016.12.10</td>
                <td class="operate"><a href="Javascript:;">查看报表</a></td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td class="order-number">20161224</td>
                <td class="account" width="150">
                    <a href="javascript:;" class="plain-text-length-limit" data-limit="7" data-title="玩车教授erp雷雨晴雷教授">玩车教授雷雨晴雷教授</a>
                    <span class="plain-text-length-limit" data-limit="16">wanchejiaoshou阿萨德啊adadas</span>
                    <span class="follower-account">粉丝数 : 12万</span>
                </td>
                <td class="msg-status">
                    <span>多图文一条</span>
                    <span>(只原创)</span>
                </td>
                <td class="reading-account">25000</td>
                <td class="agree-account">200</td>
                <td class="title-publish-con">双十二推广</td>
                <td class="publish-link" width="140"><span class="publish-link-ellipsis plain-text-length-limit" data-limit="20" data-title="http://www.51wom.local">http://www.51wom.local</span></td>
                <td class="publish-time">2016.12.10</td>
                <td class="operate"><a href="Javascript:;">查看报表</a></td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td class="order-number">20161224</td>
                <td class="account" width="150">
                    <a href="javascript:;" class="plain-text-length-limit" data-limit="7" data-title="玩车教授erp雷雨晴雷教授">玩车教授雷雨晴雷教授</a>
                    <span class="plain-text-length-limit" data-limit="16">wanchejiaoshou阿萨德啊adadas</span>
                    <span class="follower-account">粉丝数 : 12万</span>
                </td>
                <td class="msg-status">
                    <span>多图文一条</span>
                    <span>(只原创)</span>
                </td>
                <td class="reading-account">25000</td>
                <td class="agree-account">200</td>
                <td class="title-publish-con">双十二推广</td>
                <td class="publish-link" width="140"><span class="publish-link-ellipsis plain-text-length-limit" data-limit="20" data-title="http://www.51wom.local">http://www.51wom.local</span></td>
                <td class="publish-time">2016.12.10</td>
                <td class="operate"><a href="Javascript:;">查看报表</a></td>
            </tr>
            </tbody>
        </table>
        <div class="no-order">暂无订单</div>
    </div>
    <!--分页-->
    <div class="table-footer clearfix">
        <label class="check-all"><input type="checkbox">全选</label>
        <!--批量操作-->
        <div class="batch-operate fr">
            <button class="export-report-forms btn btn-danger bg-main">导出报表</button>
        </div>
        <div class="page-wb system_page">
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
