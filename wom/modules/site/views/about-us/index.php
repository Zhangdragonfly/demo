<?php
/**
 * 关于我们
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM  BY Manson
 */
use wom\assets\AppAsset;

AppAsset::register($this);

AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/media-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/about-us.css');

AppAsset::addScript($this, '@web/src/js/case-center.js');

$case_js = <<<JS
    $('.nav .about-us').addClass('active-nav');
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
JS;

$this->registerJs($case_js);
?>

<div class="content">
    <div class="company">
        <div class="title">
            <p>沃米优选</p>
            <div class="title-footer"></div>
        </div>
        <div class="company-introduce clearfix">
            <p>致力打造业界领先的 “数据+平台+内容+服务” 的新媒体营销生态系统</p>
            <p>沃米优选是以大数据驱动的新媒体整合、分析、交易、监控的共享平台，实现海量新媒体主和广告主的自主化、程序化、精准化、0差价交易。</p>
            <p>并提供数据分析、效果监控、信用担保、评价体系、交易撮合等多项基础服务，帮助广告主提升媒介管理效率，提高投放性价比和效果，帮助媒体主快速变现、实现价值最大化。</p>
            <div class="icon glxl fl">
                <span></span>
                <p>高效提升媒介管理效率</p>
            </div>
            <div class="icon tfxjb fr">
                <span></span>
                <p>精准提高投放性价比和效果</p>
            </div>
        </div>
    </div>
    <div class="team">
        <div class="title">
            <p>专业团队 品质保证</p>
            <div class="title-footer"></div>
        </div>
        <img src="../src/images/about-us/about-team.png" alt="沃米优选团队照片">
        <div class="team-introduce">
            <div class="team-content team-manage clearfix">
                <div class="tc-left fl"></div>
                <div class="tc-right fr">
                    <h3>全名订单管理</h3>
                    <p>每一个订单均可跟单，涵盖自媒体策略、约稿、监控、数据报告全程服务</p>
                </div>
            </div>
            <div class="team-content team-service clearfix">
                <div class="tc-left fl"></div>
                <div class="tc-right fl">
                    <h3>专业服务团队</h3>
                    <p>专业团队分行业持续研究独有的自媒体策略模型</p>
                </div>
            </div>
        </div>
    </div>
    <div class="resource clearfix">
        <div class="title">
            <p>海量资源任您选</p>
            <div class="title-footer"></div>
        </div>
        <p>覆盖超过<span>80%</span>的具备传播价值的微信和微博大号</p>
        <div class="resource-icon wx fl">
            <span></span>
            <p>合作微信大号<span>5W+</span></p>
        </div>
        <div class="resource-icon fans fl">
            <span></span>
            <p>覆盖<span>3亿</span>粉丝</p>
        </div>
        <div class="resource-icon wb fl">
            <span></span>
            <p>合作微博大号<span>2W+</span></p>
        </div>
    </div>
    <div class="analyze">
        <div class="title">
            <p>多维度分析</p>
            <div class="title-footer"></div>
        </div>
        <p>超过<span>42项</span>细化指标全面分析账号传播价值</p>
        <div class="an-button first-button">账号评价数据</div>
        <div class="an-button">传播数据</div>
        <div class="an-button">账号基本数据</div>
        <div class="an-button">历史投放数据</div>
        <div class="analyze-text">
            <p>数据交叉分别</p>
            <p>构建自媒体的传播价值分析模型</p>
            <p>进行传播价值分析</p>
        </div>
        <div class="analyze-bg"></div>
    </div>
    <div class="brand clearfix">
        <div class="title">
            <p>合作品牌</p>
            <div class="title-footer"></div>
        </div>
        <div class="brand-style pepsi fl"></div>
        <div class="brand-style philips fl"></div>
        <div class="brand-style bankOfSh fl"></div>
        <div class="brand-style ford fl"></div>
        <div class="brand-style Bri fl"></div>
        <div class="brand-style chinaMobile fl"></div>
        <div class="brand-style clear fl"></div>
        <div class="brand-style li-ning fl"></div>
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