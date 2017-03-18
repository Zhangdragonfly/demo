// ~~~~~~~~~~添加微信文案~~~~~~~~~~~~
// tab切换
$(function(){
	$(".tab span").on("click",function(){
		$(this).addClass("on").siblings().removeClass("on");
		$(".weixin-file-content").removeClass("show").eq($(this).index()).addClass("show");

	})
})
// ~~~~~~~~~~~侧边栏联系方式~~~~~~~~
$(function(){
	$(".side-bar li").hover(function(){
		$(this).css("background","#c81624");
		$(this).children("div").stop().animate({
			left:'-155px'
		},500);
	},function(){
		$(this).css("background","#4c4c4c");
		$(this).children("div").stop().animate({
			left:'46px'
		},500);
	})
	$(".side-bar .top").on("click",function(){
		$('html,body').stop().animate({
			scrollTop:'0'
		},500)
	})
})
// 手机页面展示
$(".file-name input").on("blur",function(){
	$(".file-title").text($(this).val());
})
$(".startime").on("blur",function(){
	$(".execute-time").text($(this).val());
})

// ~~~~~~添加正文内容文本编辑器~~~~~~
	// 实例化编辑器
   var ue = UE.getEditor('container');

//~~~~~~~~侧边栏信息展示~~~~~~~~~~
$(function(){
	$(".sidebar li").on("mouseenter",function(){
		$(this).addClass("active");
		$(".sidebar div").eq($(this).index()).stop().animate({right:"30px"},300);
	})
	$(".sidebar li").on("mouseleave",function(){
		$(this).removeClass("active");
		$(".sidebar div").eq($(this).index()).stop().animate({right:"-150px"},300);
	})
})

//tab位置
$(function(){
	//获取要定位元素距离浏览器顶部的距离
	var navH_tab = $(".tab").offset().top;
	//滚动条事件
	$(window).scroll(function(){
		//获取滚动条的滑动距离
		var scroH_tab = $(this).scrollTop();
		//滚动条的滑动距离大于等于定位元素距离浏览器顶部的距离，就固定，反之就不固定
		if(scroH_tab >= navH_tab){
			$(".tab").css({"position":"fixed","top":0,"left":"50%","margin-left":"-180px","z-index": "999"});
		}else if(scroH_tab < navH_tab){
			$(".tab").css({"position":"absolute","top":"-40px","left":"-1px","margin-left":"0"});
		}
	})
})
//确认投放的位置变化
$(function(){
	//var navH = $(".written-done").offset().top;
	$(window).scroll(function(){
		var scroH = $(this).scrollTop();
		//console.log(scroH);
		var conHeight = $(".content-right").height();
		//console.log(conHeight);

		if(scroH >= conHeight-380){
			$(".written-done").css({"position":"static","margin":"30px 0 30px 420px","transition":"all ease .1s"});
		}else if(scroH < conHeight){
			$(".written-done").css({"position":"fixed","bottom":0,"margin":"0 0 0 420px","transition":"all ease .1s"});
		}
	})
})
//标题和摘要的字数统计
//聚焦、失焦事件
$(function(){
	var title_bool = true;
	var bool = true;
	$(".file-title-input").on("focus",function(){
		if (bool) {
			$(".file-content-title span").html("还可以输入<em>64</em>字");
			bool = false;
		}
	})
	$(".file-title-input").on("blur",function(){
		if ($("#abstract").val() == "") {
			$(".file-content-title span").html("最多输入<em>64</em>字");
			bool = true;
		}
	})
	$("#abstract").on("focus",function(){
		if (bool) {
			$(".summary").find(".message").html("还可以输入<span>120</span>字");
			bool = false;
		}
	})
	$("#abstract").on("blur",function(){
		if ($("#abstract").val() == "") {
			$(".summary").find(".message").html("最多输入<span>120</span>字");
			bool = true;
		}
	})
})

//将文本进行转换，得到总的字符数。
function getLength(str){
	// 匹配中文字符的正则表达式： [\u4e00-\u9fa5]
	return String(str).replace(/[\u4e00-\u9fa5]/g,'aa').length;
}
// replace() 方法用于在字符串中用一些字符替换另一些字符，或替换一个与正则表达式匹配的子串。
$(".file-title-input").on("input",function(){
	var titleNumber = Math.ceil(getLength($(".file-title-input").val())/2);
	if (titleNumber <= 64) {
		$(".file-content-title em").html(64 - titleNumber);
	}else{
		$(".file-content-title span").html("已超出<em></em>字");
		$(".file-content-title em").html (titleNumber - 64);
	}
})
$("#abstract").on("input",function(){
	var fontNumber = Math.ceil(getLength($("#abstract").val())/2);
	if (fontNumber <= 120) {
		$(".message span").html(120 - fontNumber);
	}else{
		$(".summary").find(".message").html("已超出<span></span>字");
		$(".message span").html (fontNumber - 120);
	}
})