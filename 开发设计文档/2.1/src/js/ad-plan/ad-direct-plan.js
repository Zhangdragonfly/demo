$(function(){
	//**************时间戳或者日期的互相转化*****************
	$.extend({
		myTime: {
			/**
			 * 当前时间戳
			 * @return <int>        unix时间戳(秒)
			 */
			CurTime: function () {
				return Date.parse(new Date()) / 1000;
			},
			/**
			 * 日期 转换为 Unix时间戳
			 * @param <string> 2014-01-01 20:20:20  日期格式
			 * @return <int>        unix时间戳(秒)
			 */
			DateToUnix: function (string) {
				var f = string.split(' ', 2);
				var d = (f[0] ? f[0] : '').split('-', 3);
				var t = (f[1] ? f[1] : '').split(':', 3);
				return (new Date(
						parseInt(d[0], 10) || null,
						(parseInt(d[1], 10) || 1) - 1,
						parseInt(d[2], 10) || null,
						parseInt(t[0], 10) || null,
						parseInt(t[1], 10) || null,
						parseInt(t[2], 10) || null
					)).getTime() / 1000;
			},
			/**
			 * 时间戳转换日期
			 * @param <int> unixTime    待时间戳(秒)
			 * @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)
			 * @param <int>  timeZone   时区
			 */
			UnixToDate: function (unixTime, isFull, timeZone) {
				if (unixTime === '') {
					return '';
				} else {
					if (typeof (timeZone) == 'number') {
						unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
					}
					var time = new Date(unixTime * 1000);
					var ymdhis = "";
					ymdhis += time.getUTCFullYear() + "-";
					ymdhis += (time.getUTCMonth() + 1) + "-";
					ymdhis += time.getUTCDate();
					if (isFull === true) {
						ymdhis += " " + time.getUTCHours() + ":";
						ymdhis += time.getUTCMinutes() + ":";
						ymdhis += time.getUTCSeconds();
					}
					return ymdhis;
				}
			}
		}
	});
//*******************************

	// ~~~~~~~~~~~侧边栏联系方式~~~~~~~~
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

	//~~~~~~~~~判断必填项是否已填~~~~~~~~~
	$(".next-step").on("click",function(){
		var publish_start_time = $('.plan-time').find('#publish-start-time').val();
		var publish_end_time = $('.plan-time').find('#publish-end-time').val();
		var plan_budget_min = $(".plan-budget-min").val();
		var plan_budget_max = $(".plan-budget-max").val();
		if($(".plan-name").children("input").val() == ""){
			layer.msg("投放计划名称不能为空!",{
				icon:0,
				time: 1500
			});
			return false;
		}
		if(publish_start_time != '' && publish_end_time != ''){
			var start = $.myTime.DateToUnix(publish_start_time);
			var end = $.myTime.DateToUnix(publish_end_time);
			if(start >= end){
				layer.msg("请输入正确的投放计划时间",{
					icon:0,
					time: 1500
				});
				return false;
			}
		}else{
			layer.msg("请选择投放计划时间",{
				icon:0,
				time: 1500
			});
			return false;
		}
		if(plan_budget_min!= '' && plan_budget_max != ''){
			if(plan_budget_min >= plan_budget_max){
				layer.msg("请输入正确的投放计划预算",{
					icon:0,
					time: 1500
				});
				return false;
			}
			if(isNaN(plan_budget_min) || isNaN(plan_budget_max)){
				layer.msg("请填入正确的预算数字",{
					icon:0,
					time: 1500
				});
				return false;
			}
		}else{
			layer.msg("请输入投放计划预算",{
				icon:0,
				time: 1500
			});
			return false;
		}
		location.href="add-media.html";
	})
})

 