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
// ~~~~~~~~执行中订单选项卡~~~~~~~~~~
$(function(){
	$(".tab li").on("click",function(){
		$(this).addClass("active").siblings().removeClass("active");
		$(this).find("span").addClass("active");
		$(this).siblings().find("span").removeClass("active");
		var tab_con = $(this).parents().siblings(".tab-con");
		tab_con.eq($(this).index()).addClass("show").siblings().removeClass("show");
	})
})
// ~~~~~~~~显示微信二维码~~~~~~~~~~
$(function(){
	$(".resource-detail .icon").on("mouseenter",function(){
		$(this).siblings(".wechat-code").fadeIn();
	});
	$(".resource-detail .icon").on("mouseout",function(){
		$(this).siblings(".wechat-code").fadeOut();
	})
})
// ~~~~~~~~~~遮罩上浮~~~~~~~~~~
$(".recommend-case ul li").hover(function(){
    $(this).find(".mask").stop().animate({"top":"0"},500);
	$(this).find(".mask-con").stop().animate({"top":"0"},500);
},function(){
    $(this).find(".mask").stop().animate({"top":"84px"},500);
	$(this).find(".mask-con").stop().animate({"top":"84px"},500);
})

