<?php
/**
 * 案例中心
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM  BY Manson
 */
use wom\assets\AppAsset;

AppAsset::register($this);

AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/media-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/case-center.css');

AppAsset::addScript($this, '@web/src/js/case-center.js');

$case_js = <<<JS
    $('.nav .case').addClass('active-nav');
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
<!-- banner展示 -->
<div class="ad-banner">
    <ul>
        <li class="current"><a href="#"><img src="src/images/case-center/banner_1.jpg" alt=""></a></li>
        <li style="display: none"><a href="#"><img src="src/images/case-center/banner_1.jpg" alt=""></a></li>
        <li style="display: none"><a href="#"><img src="src/images/case-center/banner_1.jpg" alt=""></a></li>
    </ul>
    <ol class="clearfix slider-nav" style="display: none">
        <li class="active"></li>
        <li></li>
        <li></li>
    </ol>
</div>
<!-- 案例中心 -->
<div class="case-center">
    <div class="case-header module-header">
        <h3>案例中心</h3>
        <span>CASE CENTER</span>
        <span class="bg-icon"></span>
        <a class="more" href="#">更多</a>
    </div>
    <div class="case-con">
        <div class="case-con-header">
            <ul class="clearfix">
                <li class="current-nav"><span>网红直播</span><i></i></li>
                <li><span>汽车科技</span><i></i></li>
                <li><span>母婴用品</span><i></i></li>
                <li><span>旅游度假</span><i></i></li>
                <li><span>快消餐饮</span><i></i></li>
                <li><span>金融理财</span><i></i></li>
            </ul>
        </div>
        <div class="part-case clearfix">
            <div class="case-info live-case-info current-case-info clearfix">
                <div class="part-case-info-con clearfix">
                    <div class="case-info-con clearfix">
                        <div class="pic-l fl"></div>
                        <div class="case-info-r fl">
                            <div class="case-intro case-resource">
                                <h4>吉利熊猫</h4>
                                <p>6月20 日，主打“好看、好玩、好开”三大买点的吉利熊猫智动档IMT正式上市、为新车上市的预热造势，吉利熊猫结合当下最火爆的网红直播，通过官微、朋友圈、花椒平台直播发出“熊猫女郎爱慕播”活动，成为2016年汽车营销的创新案例。</p>
                            </div>
                            <div class="case-light case-resource">
                                <h4>案例亮点</h4>
                                <p>通过官微、朋友圈、花椒平台直播发出“熊猫女郎爱慕播”活动，成为2016年汽车营销的创新案例。</p>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-bg read-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>观看数</span>
                                            <span class="data-num">18万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg like-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>累计点赞数</span>
                                            <span class="data-num">2万</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="case-media-partners case-resource">
                                <h4>合作媒体</h4>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-pic fl"><img src="src/images/case-center/live/portrait-one.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>王欣欣</span>
                                            <span>ID : 29777040</span>
                                            <span>点赞数 : <i>128万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic read-bg fl"><img src="src/images/case-center/live/portrait-two.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>袁嘉宁</span>
                                            <span>ID : 30029726</span>
                                            <span>点赞数 : <i>47万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic like-bg fl"><img src="src/images/case-center/live/portrait-three.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>薛爱萍</span>
                                            <span>ID : 25238823</span>
                                            <span>点赞数 : <i>115万</i></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-info car-case-info clearfix">
                <div class="part-case-info-con clearfix">
                    <div class="case-info-con clearfix">
                        <div class="pic-l fl"></div>
                        <div class="case-info-r fl">
                            <div class="case-intro case-resource">
                                <h4>东风雪铁龙</h4>
                                <p>通过与微信意见领袖的合作，传播东风雪铁龙C3-XR是年轻最好的伙伴，是年轻人追求自由最好的表现，而且还可以定制，表达了年轻人对生活的态度：追求自由。</p>
                            </div>
                            <div class="case-light case-resource">
                                <h4>案例亮点</h4>
                                <p>通过与微信意见领袖的合作，传播东风雪铁龙代表年轻，勇敢追求自由。</p>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>覆盖用户</span>
                                            <span class="data-num">910万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg read-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总阅读数</span>
                                            <span class="data-num">21万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg like-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总点赞数</span>
                                            <span class="data-num">3万</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="case-media-partners case-resource">
                                <h4>合作媒体</h4>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-pic fl"><img src="src/images/case-center/car/portrait-one.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>新设计</span>
                                            <span>ID : new4life</span>
                                            <span>关注数 : <i>80万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic read-bg fl"><img src="src/images/case-center/car/portrait-two.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>创意果子</span>
                                            <span>ID : cygz999</span>
                                            <span>关注数 : <i>48万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic like-bg fl"><img src="src/images/case-center/car/portrait-three.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>汽车微杂志</span>
                                            <span>ID : vipauto</span>
                                            <span>关注数 : <i>32万</i></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-info baby-case-info clearfix">
                <div class="part-case-info-con clearfix">
                    <div class="case-info-con clearfix">
                        <div class="pic-l fl"></div>
                        <div class="case-info-r fl">
                            <div class="case-intro case-resource">
                                <h4>联合利华</h4>
                                <p>以社交媒体推广为主要推广模式，精准定位于母婴群体和家装群体人群，传播品牌“追求极致安全”的理念。同事，与消费者进行有效的产品功能性及产品EC活动沟通，从而进一步刺激销售。</p>
                            </div>
                            <div class="case-light case-resource">
                                <h4>案例亮点</h4>
                                <p>以社交媒体推广为主要推广模式，精准定位于母婴群体和家装群体人群，传播品牌“追求极致安全”的理念。</p>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>覆盖用户</span>
                                            <span class="data-num">4000万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg read-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总阅读数</span>
                                            <span class="data-num">137万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg like-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总点赞数</span>
                                            <span class="data-num">16万</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="case-media-partners case-resource">
                                <h4>合作媒体</h4>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-pic fl"><img src="src/images/case-center/baby/portrait-one.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>辣妈宝典</span>
                                            <span>ID : lamabd</span>
                                            <span>关注数 : <i>21万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic read-bg fl"><img src="src/images/case-center/baby/portrait-two.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>孕妇驾到</span>
                                            <span>ID : huaiyun01</span>
                                            <span>关注数 : <i>40万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic like-bg fl"><img src="src/images/case-center/baby/portrait-three.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>我是昕昕妈</span>
                                            <span>ID : xinxinma2009</span>
                                            <span>关注数：<i>19万</i></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-info tour-case-info clearfix">
                <div class="part-case-info-con clearfix">
                    <div class="case-info-con clearfix">
                        <div class="pic-l fl"></div>
                        <div class="case-info-r fl">
                            <div class="case-intro case-resource">
                                <h4>携程</h4>
                                <p>携程旅游攻略社区本身就是旅游行业的大号，本次通过几千个微信群的营销（红包、内容分享、H5）,为携程旅游攻略社区带来了精准的品牌曝光、内容互动、粉丝数也有效真是的增加。</p>
                            </div>
                            <div class="case-light case-resource">
                                <h4>案例亮点</h4>
                                <p>通过红包、内容分享及H5,为携程旅游攻略社区带来了精准的品牌曝光、内容互动、粉丝数也有效真是的增加。</p>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>覆盖用户</span>
                                            <span class="data-num">4500万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg read-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总阅读数</span>
                                            <span class="data-num">120万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg like-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总点赞数</span>
                                            <span class="data-num">10万</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="case-media-partners case-resource">
                                <h4>合作媒体</h4>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-pic fl"><img src="src/images/case-center/tour/portrait-one.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>时尚旅游圈</span>
                                            <span>ID : shishanglvyouquan</span>
                                            <span>关注数 : <i>80万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic read-bg fl"><img src="src/images/case-center/tour/portrait-two.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>旅游圈</span>
                                            <span>ID : dotours</span>
                                            <span>关注数 : <i>48万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic like-bg fl"><img src="src/images/case-center/tour/portrait-three.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>在线旅讯</span>
                                            <span>ID : otadaily</span>
                                            <span>关注数 : <i>32万</i></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-info fast-consume-case-info clearfix">
                <div class="part-case-info-con clearfix">
                    <div class="case-info-con clearfix">
                        <div class="pic-l fl"></div>
                        <div class="case-info-r fl">
                            <div class="case-intro case-resource">
                                <h4>雅闻倍优</h4>
                                <p>为提高雅闻倍优产品大陆知名度，并配合其天猫国际旗舰店电商销售活动而进行整体品牌、产品及销售引流活动推广。提高雅闻倍优品牌在大陆的整体知名度，也进一步提升了品牌的口碑。</p>
                        </div>
                            <div class="case-light case-resource">
                                <h4>案例亮点</h4>
                                <p>通过社交媒体营销活动，通过互动活动（萧敬腾的影响力）和天猫官方活动（新风尚、年中促、店内活动）运营，实现AISAS模型的整合营销。</p>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>覆盖用户</span>
                                            <span class="data-num">8000万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg read-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总阅读数</span>
                                            <span class="data-num">423万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg like-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总点赞数</span>
                                            <span class="data-num">2万</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="case-media-partners case-resource">
                                <h4>合作媒体</h4>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-pic fl"><img src="src/images/case-center/fast-consume/portrait-one.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>最浪潮</span>
                                            <span>ID : best-chaoliu</span>
                                            <span>关注数 : <i>56万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic read-bg fl"><img src="src/images/case-center/fast-consume/portrait-two.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>微女郎周刊</span>
                                            <span>ID : dhklsa</span>
                                            <span>关注数 : <i>40万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic like-bg fl"><img src="src/images/case-center/fast-consume/portrait-three.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>时尚吧</span>
                                            <span>ID : www438comcn</span>
                                            <span>关注数 : <i>31万</i></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-info finc-case-info clearfix">
                <div class="part-case-info-con clearfix">
                    <div class="case-info-con clearfix">
                        <div class="pic-l fl"></div>
                        <div class="case-info-r fl">
                            <div class="case-intro case-resource">
                                <h4>华夏基金</h4>
                                <p>为推广华夏基金赞助北京马拉松及北马开炮当日华夏基金“为奉献汇报”的品牌理念和“坚持运动坚持美好”的北马宣言，以拉新伟整体传播目的而进行的活动推广。</p>
                            </div>
                            <div class="case-light case-resource">
                                <h4>案例亮点</h4>
                                <p>通过有趣的线上互动游戏，吸引追求品质生活生活人群，如都市精英等人的关注和参与，并且结合热点事件和话题炒作，提升更多潜在用户的关注度，增强华夏基金品牌的好感度和知名度。</p>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>覆盖用户</span>
                                            <span class="data-num">297万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg read-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总阅读数</span>
                                            <span class="data-num">20万</span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-bg like-bg fl"></div>
                                        <div class="data-show fl">
                                            <span>总点赞数</span>
                                            <span class="data-num">2万</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="case-media-partners case-resource">
                                <h4>合作媒体</h4>
                                <ul class="clearfix">
                                    <li class="clearfix">
                                        <div class="data-pic fl"><img src="src/images/case-center/finc/portrait-one.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>全球热门排行榜</span>
                                            <span>ID : qqremphb</span>
                                            <span>关注数 : <i>80万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic read-bg fl"><img src="src/images/case-center/finc/portrait-two.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>热门头条新闻</span>
                                            <span>ID : RemenTT-Xw</span>
                                            <span>关注数 : <i>48万</i></span>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="data-pic like-bg fl"><img src="src/images/case-center/finc/portrait-three.png" alt="账户头像"></div>
                                        <div class="data-show fl">
                                            <span>全球热门荟萃</span>
                                            <span>ID : qq-rmhc</span>
                                            <span>关注数 : <i>32万</i></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 品牌客户 -->
<div class="brand-customer">
    <div class="in-brand-customer">
        <div class="brand-header module-header">
            <h3>品牌客户</h3>
            <span>BRAND CUSTOMER</span>
            <span class="bg-icon"></span>
        </div>
        <ul class="brand-logo clearfix">
            <li class="pepsi"></li>
            <li class="philips"></li>
            <li class="sh-brank"></li>
            <li class="ford"></li>
            <li class="bridgestone"></li>
            <li class="china-mobile"></li>
            <li class="clear-logo"></li>
            <li class="lining"></li>
        </ul>
    </div>
</div>
<!-- 平台优势 -->
<div class="platform-advantage">
    <div class="platform-header module-header">
        <h3>平台优势</h3>
        <span>PLATFORM ADVANTAGE</span>
        <span class="bg-icon"></span>
    </div>
    <div class="advantage-type clearfix">
        <div class="advantage-per advantage-more">
            <span></span>
            <p>10万+高配合度优质自媒体资源</p>
            <p>多维度账号传播价值分析</p>
        </div>
        <div class="advantage-per advantage-fast">
            <span></span>
            <p>创建专属资源库管理，方便资源管理</p>
            <p>和推荐智能投放高效完成推广计划</p>
        </div>
        <div class="advantage-per advantage-good">
            <span></span>
            <p>超过5年服务团队全程服务</p>
            <p>专业媒介团队分行业研究同步共享</p>
        </div>
        <div class="advantage-per advantage-save">
            <span></span>
            <p>集中采买 拒绝差价</p>
            <p>快速选择省心省力</p>
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