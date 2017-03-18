$(function(){
    //选择下拉单
    $('.dropdown .dropdown-menu').on('click','li',selectedOption);
    //下拉单选择某一个
    function selectedOption(){
        var _text = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }

    // ~~~~~~~~~banenr部分(勿删除,图片待添加)~~~~~~~~~
    	var ad_pic = $('.ad-pic');
    	var ad_picLi = ad_pic.children('li');
    	var ad_dot = $('.ad-dot');
    	var ad_dotLi = ad_dot.children('li');
    	var x = 0;
    	function move(){
    		for (var i = 0; i < ad_picLi.length; i++) {
    			ad_picLi[i].style.opacity = 0;
    			ad_dotLi[i].className ="";
    		}
    		ad_picLi[x].style.opacity = 1;
    		ad_dotLi[x].className = "active";
    	}
    	function moveL(){
    		x++;
    		if (x >= ad_picLi.length) {
    			x = 0;
    		}
    		move();
    	}
    	var timer1 = setInterval(moveL,5000);

    	for (var i = 0; i < ad_dotLi.length; i++) {
    		ad_dotLi[i].index = i;
    		ad_dotLi[i].onclick = function(){
    			x = this.index;
    			move();
    		}
    	}

    // ~~~~~~~~~视频微信微博选项卡~~~~~~
	function resourceNavTab(_thisLi,_thisResource){
		_thisLi.addClass("active").siblings().removeClass("active");
		_thisLi.find(".caret").removeClass("color-fff").parents("li").siblings().find("i").addClass("color-fff");
		_thisResource.find(".bottom-line li").removeClass("line-active").eq(_thisLi.index()).addClass("line-active");
		_thisResource.find(".resource-con").removeClass("show").eq(_thisLi.index()).addClass("show");
	}
	$(".video-nav li").on("click",function(){
		var _thisLi = $(this);
		var _thisResource = $(".video-resource");
		resourceNavTab(_thisLi,_thisResource);
	});
	$(".weixin-nav li").on("click",function(){
		var _thisLi = $(this);
		var _thisResource = $(".weixin-resource");
		resourceNavTab(_thisLi,_thisResource);
	});
	$(".weibo-nav li").on("click",function(){
		var _thisLi = $(this);
		var _thisResource = $(".weibo-resource");
		resourceNavTab(_thisLi,_thisResource);
	});



	//阅读数
	//左侧展示表
	readNumOneMap();
	function readNumOneMap(){
		var myChart = echarts.init(document.getElementById('read-num-chart-one'));
		var time = ['08-01','08-02','08-03','08-04','08-05','08-06','08-07'];
		var value_y = [5000,3000,2000,5000,1000,4000,8000];
		var option = {
			tooltip: {
				trigger: "axis",
				show: true
			},
			toolbox: {
				show: false,
				feature: {
					dataView: {
						readOnly: true
					},
					magicType: {
						type: ["line", "bar", "stack", "tiled"],
						show: false
					}
				}
			},
			calculable: true,
			xAxis: [{
				type: "category",
				data: time,
				splitLine: {
					show: true
				},
				nameTextStyle: {
					color: "rgb(50, 52, 55)",
					fontSize: 12,
					fontStyle: "normal"
				},
				scale: true,
				boundaryGap: false,
				axisLabel: {
					textStyle: {
						color: "rgb(50, 52, 55)",
						fontSize: 12,
						fontStyle: "normal"
					}
				},
				axisTick: {
					show: false
				},

			}],
			yAxis: [{
				type: "value",
				splitLine: {
					lineStyle: {
						color: "rgb(204, 204, 204)",
						width: 1
					}
				},
				axisLine: {
					lineStyle: {
						width: 1,
						color: "rgb(76, 76, 76)"
					},
					show: false
				},
				axisTick: {
					show: false
				}
			}],
			series: [{
				type: "line",
				itemStyle: {
					normal: {
						areaStyle: {
							type: "default",
							color: "#fae7e9"
						},
						color: "#c71523",
						lineStyle: {
							width: 2
						},
						borderWidth: 0
					}
				},
				name: "阅读数",
				data: value_y,
				symbolSize: 1
			}],
			grid: {
				x:50,
				y:10,
				x2:80,
				y2:20,
				borderColor: "rgb(255, 255, 255)"
			}
		};
		myChart.setOption(option);
	};

	//右侧展示表
	readNumTwoMap();
	function readNumTwoMap(){
		var myChart = echarts.init(document.getElementById('read-num-chart-two'));
		var time = ['08-01','08-02','08-03','08-04','08-05','08-06','08-07'];
		var value_y = [2000,5000,1000,5000,3000,4000,8000];
		var option = {
			tooltip: {
				trigger: "axis",
				show: true
			},
			toolbox: {
				show: false,
				feature: {
					dataView: {
						readOnly: true
					},
					magicType: {
						type: ["line", "bar", "stack", "tiled"],
						show: false
					}
				}
			},
			calculable: true,
			xAxis: [{
				type: "category",
				data: time,
				splitLine: {
					show: true
				},
				nameTextStyle: {
					color: "rgb(50, 52, 55)",
					fontSize: 12,
					fontStyle: "normal"
				},
				scale: true,
				boundaryGap: false,
				axisLabel: {
					textStyle: {
						color: "rgb(50, 52, 55)",
						fontSize: 12,
						fontStyle: "normal"
					}
				},
				axisTick: {
					show: false
				},

			}],
			yAxis: [{
				type: "value",
				splitLine: {
					lineStyle: {
						color: "rgb(204, 204, 204)",
						width: 1
					}
				},
				axisLine: {
					lineStyle: {
						width: 1,
						color: "rgb(76, 76, 76)"
					},
					show: false
				},
				axisTick: {
					show: false
				}
			}],
			series: [{
				type: "line",
				itemStyle: {
					normal: {
						areaStyle: {
							type: "default",
							color: "#fae7e9"
						},
						color: "#c71523",
						lineStyle: {
							width: 2
						},
						borderWidth: 0
					}
				},
				name: "阅读数",
				data: value_y,
				symbolSize: 1
			}],
			grid: {
				x:50,
				y:10,
				x2:80,
				y2:20,
				borderColor: "rgb(255, 255, 255)"
			}
		};
		myChart.setOption(option);
	};

	//评论转发点赞粉丝数雷达图
	//左侧展示部分
	varietyDataOneMap();
	function varietyDataOneMap(){
		var myChart = echarts.init(document.getElementById('variety-data-one'));
		var option = {
			tooltip: {
				trigger: 'axis'
			},

			radar: [
				{
					indicator: [
						{text: '评论数', max: 100},
						{text: '转发数', max: 100},
						{text: '粉丝数', max: 100},
						{text: '点赞数', max: 100}
					],
					center: ['50%','50%'],
					radius: 80
				},
			],
			series: [
				{
					type: 'radar',
					tooltip: {
						trigger: 'item'
					},
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data: [
						{
							value: [60,73,85,40]
						}
					]
				}
			]
		};
		myChart.setOption(option);
	};

	//右侧展示部分
	varietyDataTwoMap();
	function varietyDataTwoMap(){
		var myChart = echarts.init(document.getElementById('variety-data-two'));
		var option = {
			tooltip: {
				trigger: 'axis'
			},

			radar: [
				{
					indicator: [
						{text: '评论数', max: 100},
						{text: '转发数', max: 100},
						{text: '粉丝数', max: 100},
						{text: '点赞数', max: 100}
					],
					center: ['50%','50%'],
					radius: 80
				},
			],
			series: [
				{
					type: 'radar',
					tooltip: {
						trigger: 'item'
					},
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data: [
						{
							value: [34,45,22,30]
						}
					]
				}
			]
		};
		myChart.setOption(option);
	};

	// ~~~~~~经典案例轮播图~~~~~~
	var tab = document.getElementById("tab");
	var tabLi = tab.getElementsByTagName("span");
	var banner2 = document.getElementById("banner2");
	var wrap = document.getElementById("wrap");
	var pic=document.getElementById("pic");
	var picLi=pic.getElementsByTagName("li");
	var dot=document.getElementById("dot");
	var dotLi=dot.getElementsByTagName("li");
	var prev=document.getElementById("prev");
	var next=document.getElementById("next");
	pic.innerHTML+=pic.innerHTML;
	pic.style.width=picLi.length*wrap.offsetWidth+"px";
	var index = 0;
	var timer=null;
	function nextFn(){
		index++;
		if(index>dotLi.length-1){
			index=0;
		}
		if(pic.offsetLeft<=(-picLi.length*wrap.offsetWidth)/2){
			pic.style.left="0px";
			pic.style.transitionDuration="0s";
		}
		pic.style.left=pic.offsetLeft-wrap.offsetWidth+"px";
		pic.style.transition="all .4s ease";

		for (var i=0;i<dotLi.length;i++) {
			tabLi[i].className="";
			dotLi[i].className="";
		}
		tabLi[index].className="active1";
		dotLi[index].className="active2";
	}
	function prevFn(){
		index--;
		if(index<0){
			index=dotLi.length-1;
		}
		if(pic.offsetLeft>=(-wrap.offsetWidth)){
			pic.style.left=(-picLi.length*wrap.offsetWidth)/2+"px";
			pic.style.transitionDuration="0s";
		}
		pic.style.left=pic.offsetLeft+wrap.offsetWidth+"px";
		pic.style.transition="all .4s ease";
		for (var i=0;i<dotLi.length;i++) {
			tabLi[i].className="";
			dotLi[i].className="";
		}
		tabLi[index].className="active1";
		dotLi[index].className="active2";
	}
	prev.onclick=function(){
		prevFn();
	}
	next.onclick=function(){
		nextFn();
	}
	run();
	function run(){
		clearInterval(timer);
		timer=setInterval(function(){
			nextFn();
		},3000);
	}
	for (var i=0;i<dotLi.length;i++) {
		dotLi[i].index=i; //消除点击记忆，防止跳播
		dotLi[i].onclick=function(){
			index=this.index;
			for (var i = 0; i < dotLi.length; i++){
				dotLi[i].className="";
				tabLi[i].className="";
			}
			tabLi[index].className="active1";
			this.className="active2";
			run();
			pic.style.left= -this.index*wrap.offsetWidth+"px";

		}
	}
	for (var i=0;i<tabLi.length;i++) {
		tabLi[i].index=i;
		tabLi[i].onclick=function(){
			index=this.index; //消除点击记忆，防止跳播
			for (var i = 0; i < tabLi.length; i++){
				dotLi[i].className="";
				tabLi[i].className="";
			}
			dotLi[index].className="active2";
			this.className="active1";
			run();
			pic.style.left= -this.index*wrap.offsetWidth+"px";
		}
	}
	banner2.onmouseenter=function(){
		clearInterval(timer);
	}
	banner2.onmouseleave=function(){
		run();
	}
	tab.onmouseenter=function(){
		clearInterval(timer);
	}
	tab.onmouseleave=function(){
		run();
	}
	//限制字符串长度
	function plainContentLengthLimit(){
		$('.plain-text-length-limit').each(function(){
			var content = $(this).text().trim();
			var length_limit = $(this).attr('data-limit');
			var content_length = content.length;
			if(length_limit == undefined){
				length_limit = 5;
			}
			if(content_length > length_limit){
				$(this).text(content.substr(0, length_limit) + '...');
			}
			$(this).attr('data-value', content);
		})
	};
	plainContentLengthLimit();

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
})
