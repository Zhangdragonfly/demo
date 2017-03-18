<?php
/**
 * 解决方案
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM  BY Manson
 */
use wom\assets\AppAsset;

AppAsset::register($this);

AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/media-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/solution.css');

AppAsset::addScript($this, '@web/src/js/case-center.js');

$case_js = <<<JS
    $('.nav .solution').addClass('active-nav');


    //~~~~~~~~~~~~~~~~~~~侧边栏联系方式~~~~~~~~~~~~~~~~~~~~~~
   $(".side-bar li").hover(function(){
		$(this).css("background","#c81624");
		$(this).children("div").stop().animate({
			left:'-155px'
		},500);
	},function(){
		$(this).css("background","#1e1e1e");
		$(this).children("div").stop().animate({
			left:'46px'
		},500);
	})
	$(".side-bar .top").on("click",function(){
		$('html,body').stop().animate({
			scrollTop:'0'
		},500)
	})
	//顶部搜索（微信、视频、微博）的下拉列表
	$('.dropdown .dropdown-search-type').on('click','li',selectedTypeOption);
	function selectedTypeOption(){
		var weixin_list_url = $("#id-weixin-media-list-url").val();
		var weibo_list_url = $("#id-weibo-media-list-url").val();
		var video_list_url = $("#id-video-media-list-url").val();
		var type = $(this).data('type');
		var _txet = $(this).text();
		$(this).parent().prev().find('span:eq(0)').text(_txet);
		if(type=="weixin"){
			$("input[name=search-media]").attr("data-url",weixin_list_url);
			$("input[name=search-media]").attr("placeholder","输入账号名称/ID");
		}
		if(type=="weibo"){
			$("input[name=search-media]").attr("data-url",weibo_list_url);
			$("input[name=search-media]").attr("placeholder","输入账号名称");
		}
		if(type=="video"){
			$("input[name=search-media]").attr("data-url",video_list_url);
			$("input[name=search-media]").attr("placeholder","输入平台名称/ID");
		}
	}
	//顶部搜索
	$(".search-media").click(function(){
		var search_url = $("input[name=search-media]").data('url');
		var search_name = $("input[name=search-media]").val();
		if(search_name == ""){
			return false;
		}
		window.location.href = search_url+"&search_name="+search_name;
	});
	//回车搜索
    $('input[name = search-media]').bind('keypress',function(event){
        if(event.keyCode == "13"){
            resourceSearch();
        }
    });
    //资源搜索
    function resourceSearch(){
        var search_url = $("input[name=search-media]").data('url');
        var search_name = $("input[name=search-media]").val();
        if(search_name == ""){
            window.location.href = search_url;
            return false;
        }
        window.location.href = search_url+"&search_name="+search_name;
    }
JS;

$this->registerJs($case_js);
?>
<!--直播营销面临的问题-->
<div class="section">
    <div class="content content-1 clearfix">
        <div class="title">
            <p>直播营销面临的问题</p>
            <div class="title-bottom"></div>
        </div>
        <div class="question-box question-box-1 fl">
            <div class="img-box"></div>
            <p>如何选择合适的网红主播</p>
        </div>
        <div class="question-box question-box-2 fl">
            <div class="img-box"></div>
            <p>如何玩转网红直播</p>
        </div>
        <div class="question-box question-box-3 fl">
            <div class="img-box"></div>
            <p>如何评估直播效果</p>
        </div>
    </div>
</div>
<!--如何选择合适的网红主播-->
<div class="section bg-gray">
    <div class="content">
        <div class="img-title">
            <div class="img-title-bg img-title-bg-1"></div>
            <p>如何选择合适的网红主播</p>
        </div>
        <div class="tag-box clearfix">
            <div class="tag fl">
                <p class="small-title">消费属性</p>
                <p>关注品牌，关注产品，消费水平，消费心态</p>
            </div>
            <div class="tag fl">
                <p class="small-title">媒体属性</p>
                <p>浏览媒介，浏览内容，兴趣关注点，当前需求</p>
            </div>
            <div class="tag fl">
                <p class="small-title">粉丝属性</p>
                <p>年龄分布，性别占比，兴趣喜好，消费能力，品牌偏好</p>
            </div>
            <div class="tag fl">
                <p class="small-title">基础属性</p>
                <p>性别，年龄，身高，语言，形象类别，特长，活动经历</p>
            </div>
            <div class="tag fl">
                <p class="small-title">行为属性</p>
                <p>广告交互，搜素行为，效率跟踪</p>
            </div>
            <div class="tag fl">
                <p class="small-title">品牌标签</p>
                <p>品牌定位，形象，气质。性别，消费者认知</p>
            </div>
            <div class="tag fl">
                <p class="small-title">产品标签</p>
                <p>产品功能，特性，利益点，情怀，区别，口碑</p>
            </div>
            <div class="tag fl">
                <p class="small-title">兴趣标签</p>
                <p>小幅这个洞察和感知归类后的总结分类</p>
            </div>
        </div>
    </div>
</div>
<!--如何玩转网红直播-->
<div class="section">
    <div class="content">
        <div class="img-title">
            <div class="img-title-bg img-title-bg-2"></div>
            <p>如何玩转网红主播</p>
        </div>
        <div class="icon-box clearfix">
            <div class="icon fl">
                <div class="icon-img icon-img-1"></div>
                <p>策略/策划</p>
            </div>
            <div class="icon fl">
                <div class="icon-img icon-img-2"></div>
                <p>创意内容</p>
            </div>
            <div class="icon fl">
                <div class="icon-img icon-img-3"></div>
                <p>网红主播</p>
            </div>
            <div class="icon fl">
                <div class="icon-img icon-img-4"></div>
                <p>媒介资源</p>
            </div>
            <div class="icon fl">
                <div class="icon-img icon-img-5"></div>
                <p>执行/转播</p>
            </div>
        </div>
        <div class="method-box">
            <div class="method-content-box method-content-box-1">
                <p>行业分析</p>
                <p>品牌/产品定位</p>
                <p>目标人群分析</p>
                <p>创意分享</p>
                <p>策略定制</p>
            </div>
            <div class="method-content-box method-content-box-2">
                <p>互动机制</p>
                <p>新鲜玩法</p>
                <p>直播脚本</p>
                <p>粉丝互动</p>
                <p>传播/导流</p>
            </div>
            <div class="method-content-box method-content-box-3">
                <p>主播类型</p>
                <p>直播风格</p>
                <p>特长/优势</p>
                <p>人气</p>
                <p>影响力</p>
                <p>粉丝分析</p>
            </div>
            <div class="method-content-box method-content-box-4">
                <p>沃米优选</p>
                <p>直播平台</p>
                <p>直播视频二次传播</p>
                <p>BBS</p>
                <p>ERP</p>
            </div>
            <div class="method-content-box method-content-box-5">
                <p>预热</p>
                <p>执行期间</p>
                <p>长尾传播</p>
                <p>UGC</p>
            </div>
        </div>
    </div>
</div>
<!--如何评估直播效应-->
<div class="section section-4 bg-gray">
    <div class="content">
        <div class="img-title">
            <div class="img-title-bg img-title-bg-3"></div>
            <p>如何评估直播效果</p>
        </div>
        <div class="living-point-img"></div>
    </div>
</div>
<!--谦玛促进直播的良性循环-->
<div class="section section-5">
    <div class="content">
        <div class="title">
            <p>谦玛促进直播的良性循环</p>
            <div class="title-bottom"></div>
        </div>
        <div class="living-feature-img"></div>
    </div>
</div>
<!--视频网红直播服务产品-->
<div class="section bg-gray">
    <div class="content clearfix">
        <div class="title">
            <p>视频网红直播服务产品</p>
            <div class="title-bottom"></div>
        </div>
        <div class="living-product-box fl">
            <p>明星名人直播</p>
            <div>
                <div class="top-left-border"></div>
                <div class="bottom-right-border"></div>
                <p>指依据品牌、活动匹配的明星名人的专业直播，要求有专业直播设备(一般是多机位)和定制脚本和互动的直播活动</p>
            </div>
        </div>
        <div class="living-product-box living-product-box-2 fl">
            <p>分众直播</p>
            <div>
                <div class="top-left-border"></div>
                <div class="bottom-right-border"></div>
                <p>指邀请多名网红，同一时间段内进行口播、平牌植入、品牌赞助、线上直播的多平台多屏直播营销</p>
            </div>
        </div>
        <div class="living-product-box fl">
            <p>整合直播</p>
            <div>
                <div class="top-left-border"></div>
                <div class="bottom-right-border"></div>
                <p>提供基于直播的整体解决方案，包括网红直播、平台资源，同时通过媒体进行转播，包括但不限于微信、微博、ERP和视频广告等</p>
            </div>
        </div>
    </div>
</div>
<!--优势直播营销资源-->
<div class="section section-7">
    <div class="content clearfix">
        <div class="title">
            <p>优势直播营销资源</p>
            <div class="title-bottom"></div>
        </div>
        <div class="resource-point resource-point-1 fl">
            <span></span>
            <p><span>独家代理</span>300位网红</p>
        </div>
        <div class="resource-point resource-point-2 fl">
            <span></span>
            <p>全平台和做<span>5000+网红</span>与各个的top网红具有良好的合作关系</p>
        </div>
        <div class="resource-point resource-point-3 fl">
            <span></span>
            <p><span>全面覆盖</span>各个直播平台、视频平台的广告业务与主流平台保持良好的广告才买业务的合作关系</p>
        </div>
    </div>
</div>
<!--侧边栏联系方式-->
<div class="side-bar">
    <ul>
        <li class="tel">
            <div class="tel-con detail">
                <span></span>
                <p>客服电话</p>
                <p>400-878-9551</p>
            </div>
            <i></i>
        </li>
        <li class="qq">
            <div class="qq-con detail">
                <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=2&uin=800187006" target="_blank">
                    <span></span>
                    <p>客服QQ</p>
                    <p>800187006</p>
                    <p>点击直接与客服沟通</p>
                </a>
            </div>
            <i></i>
        </li>
        <li class="weixin">
            <div class="weixin-con detail">
                <p>微信扫一扫</p>
                <span></span>
            </div>
            <i></i>
        </li>
        <li class="top">
            <i></i>
        </li>
    </ul>
</div>


