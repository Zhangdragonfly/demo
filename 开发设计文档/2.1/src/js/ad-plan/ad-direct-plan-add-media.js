$(function(){
	//~~~~~~显示微信二维码~~~~~
	$(".account .icon").on("mouseenter",function(){
		$(this).siblings(".wechat-code").fadeIn();
	});
	$(".account .icon").on("mouseout",function(){
		$(this).siblings(".wechat-code").fadeOut();
	})

	//~~~~~~判断有无资源~~~~~~
	function isResource(){
		var resourceLength =  $(".weixin-table tbody").children("tr").length;
		//console.log(resourceLength);
		if(resourceLength < 1){
			$(".no-resource").css("display","block");
		}
		if(resourceLength < 5){
			$(".insure-throw").css({"position":"static","margin":"70px auto 40px","transition":"all ease .2s"});
		}
	}
	isResource();

	//~~~~~~删除所选资源~~~~~~
	$(".weixin-table .delete").click(function(){
		var element_delete =  $(this).parents("tr");
		layer.confirm('您确定要删除账号吗？', {
			btn: ['确定','取消']
		}, function(){
			layer.msg('删除成功 !', {
				icon: 1,
				time:1000
			});
			element_delete.remove();
			isResource();

			},function(){
				layer.msg('已取消删除 !', {
					icon: 6,
					time:1000
				});
			}
		)
	});

	//~~~~~~确认投放的位置变化~~~~~~~
	$(document).scroll(function(){
		var scroH = $(this).scrollTop();
		var conHeight = $(".weixin-table").height();
		//console.log(scroH);
		//console.log(conHeight);
		if(scroH >= conHeight - 100){
			$(".insure-throw").css({"position":"static","margin":"70px auto 40px","transition":"all ease .2s"});
		}else if(scroH < conHeight){
			$(".insure-throw").css({"position":"fixed","bottom":0,"margin":"0 0 0 90px","transition":"all ease .2s"});

		}
	})

	// ~~~~~~侧边栏联系方式~~~~~~
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
})
