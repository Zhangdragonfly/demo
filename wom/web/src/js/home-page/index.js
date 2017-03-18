$(function(){
	//控制导航栏选中
	$(".wom-index").addClass('active-nav');

    //选择下拉单
    $('.dropdown .dropdown-menu').on('click','li',selectedOption);
    //下拉单选择某一个
    function selectedOption(){
        var _text = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }

    // ~~~~~~~~~顶部banenr部分(勿删除,图片待添加)~~~~~~~~~

    //	var ad_pic = $('.ad-pic');
    //	var ad_picLi = ad_pic.children('li');
    //	var ad_dot = $('.ad-dot');
    //	var ad_dotLi = ad_dot.children('li');
    //	var x = 0;
    //	function move(){
    //		for (var i = 0; i < ad_picLi.length; i++) {
    //			ad_picLi[i].style.opacity = 0;
    //			ad_dotLi[i].className ="";
    //		}
    //		ad_picLi[x].style.opacity = 1;
    //		ad_dotLi[x].className ~= "active";
    //	}
    //	function moveL(){
    //		x++;
    //		if (x >= ad_picLi.length) {
    //			x = 0;
    //		}
    //		move();
    //	}
    //	var timer1 = setInterval(moveL,5000);
    //
    //	for (var i = 0; i < ad_dotLi.length; i++) {
    //		ad_dotLi[i].index = i;
    //		ad_dotLi[i].onclick = function(){
    //			x = this.index;
    //			move();
    //		}
    //	}

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
			$("input[name=search-media]").attr("placeholder","请输入微信账号/ID");
		}
		if(type=="weibo"){
			$("input[name=search-media]").attr("data-url",weibo_list_url);
			$("input[name=search-media]").attr("placeholder","请输入微博名称");
		}
		if(type=="video"){
			$("input[name=search-media]").attr("data-url",video_list_url);
			$("input[name=search-media]").attr("placeholder","请输入平台昵称/ID");
		}

	}
	//顶部搜索
	$(".search-media").click(function(){
		resourceSearch();
	});
	//回车搜索
	$('input[name = search-media]').bind('keypress',function(event){
		if(event.keyCode == "13"){
			resourceSearch();
		}
	});
	//搜索资源
	function resourceSearch(){
		var search_url = $("input[name=search-media]").data('url');
		var search_name = $("input[name=search-media]").val();
		if(search_name == ""){
			return false;
		}
		window.location.href = search_url+"&search_name="+search_name;
	}

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~视频部分数据展示~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	var video_json_data = JSON.parse($(".video-data").text());
	var douyu_data_array = [];
	var yzb_data_array = [];
	var meipai_data_array = [];
	var miaopai_data_array = [];
	var huajiao_data_array = [];
	var yingke_data_array = [];
	for(i in video_json_data){
		if(video_json_data[i].platform_code == 6){
			douyu_data_array.push(video_json_data[i]);
		}
		if(video_json_data[i].platform_code == 9){
			yzb_data_array.push(video_json_data[i]);
		}
		if(video_json_data[i].platform_code == 4){
			meipai_data_array.push(video_json_data[i]);
		}
		if(video_json_data[i].platform_code == 5){
			miaopai_data_array.push(video_json_data[i]);
		}
		if(video_json_data[i].platform_code == 1){
			huajiao_data_array.push(video_json_data[i]);
		}
		if(video_json_data[i].platform_code == 7){
			yingke_data_array.push(video_json_data[i]);
		}
	}
	videoInitInfoShow(douyu_data_array,$(".douyu-ul"));
	videoInitInfoShow(yzb_data_array,$(".yzb-ul"));
	videoInitInfoShow(meipai_data_array,$(".meipai-ul"));
	videoInitInfoShow(miaopai_data_array,$(".miaopai-ul"));
	videoInitInfoShow(huajiao_data_array,$(".huajiao-ul"));
	videoInitInfoShow(yingke_data_array,$(".yingke-ul"));

	function videoInitInfoShow(platform_data_array,platform_ul){
		for(i in platform_data_array) {
			platform_ul.find(".portrait").eq(i).attr("href", platform_data_array[i].source_video_url);
			platform_ul.find(".portrait").eq(i).attr("target", '_blank');
			platform_ul.find(".portrait img").eq(i).attr("src", platform_data_array[i].avatar_img);
			platform_ul.find(".name").eq(i).text(platform_data_array[i].media_name);
			platform_ul.find(".name").eq(i).attr("href",platform_data_array[i].source_video_url);
			platform_ul.find(".name").eq(i).attr("target","_blank");
			platform_ul.find(".intro").eq(i).text(platform_data_array[i].short_desc);
			platform_ul.find(".video-info").eq(i).attr("href", platform_data_array[i].source_video_url);
			platform_ul.find(".video-info").eq(i).attr("target", '_blank');
			platform_ul.find(".video-info img").eq(i).attr("src", platform_data_array[i].video_cover_img);
			platform_ul.find(".follower-account").eq(i).text(platform_data_array[i].follower_num);
			platform_ul.find(".audience-account").eq(i).text(platform_data_array[i].avg_view_num);
		}
	}


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~微信部分数据展示~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	var weixin_list_url = $('input#weixin-list-url').val();

	$.get(weixin_list_url,function(data,status){
		//定义分类数组和是否显示图表数组
		var car_data_array = [];
		var car_data_array_show_chart = [];
		var car_data_array_hide_chart = [];

		var baby_data_array = [];
		var baby_data_array_show_chart = [];
		var baby_data_array_hide_chart = [];

		var it_data_array = [];
		var it_data_array_show_chart = [];
		var it_data_array_hide_chart = [];

		var fashion_data_array = [];
		var fashion_data_array_show_chart = [];
		var fashion_data_array_hide_chart = [];

		var health_data_array = [];
		var health_data_array_show_chart = [];
		var health_data_array_hide_chart = [];

		var life_data_array = [];
		var life_data_array_show_chart = [];
		var life_data_array_hide_chart = [];

		//判断分类
		for(i in data){
			if(data[i].media_cate == 4){
				car_data_array.push(data[i]);
			}
			if(data[i].media_cate == 10){
				baby_data_array.push(data[i]);
			}
			if(data[i].media_cate == 7){
				it_data_array.push(data[i]);
			}
			if(data[i].media_cate == 5){
				fashion_data_array.push(data[i]);
			}
			if(data[i].media_cate == 12){
				health_data_array.push(data[i]);
			}
			if(data[i].media_cate == 2){
				life_data_array.push(data[i]);
			}
		}
		showOrHideChart(car_data_array,car_data_array_show_chart,car_data_array_hide_chart);

		showOrHideChart(baby_data_array,baby_data_array_show_chart,baby_data_array_hide_chart);

		showOrHideChart(it_data_array,it_data_array_show_chart,it_data_array_hide_chart);

		showOrHideChart(fashion_data_array,fashion_data_array_show_chart,fashion_data_array_hide_chart);

		showOrHideChart(health_data_array,health_data_array_show_chart,health_data_array_hide_chart);

		showOrHideChart(life_data_array,life_data_array_show_chart,life_data_array_hide_chart);
		//判断是否显示图表
		function showOrHideChart(parent_data_array,data_array_show_chart,data_array_hide_chart ){
			for(i in parent_data_array){
				if(parent_data_array[i].show_latest_7_head_view_num == 1){
					data_array_show_chart.push(parent_data_array[i]);
				}else {
					data_array_hide_chart.push(parent_data_array[i]);
				}
			}
		}

		weixinInitInfoShow(car_data_array_show_chart,$(".car-weixin .account-detail"));
		weixinInitInfoShow(car_data_array_hide_chart,$(".car-weixin .account-list"));

		weixinInitInfoShow(baby_data_array_show_chart,$(".baby-weixin .account-detail"));
		weixinInitInfoShow(baby_data_array_hide_chart,$(".baby-weixin .account-list"));

		weixinInitInfoShow(it_data_array_show_chart,$(".it-weixin .account-detail"));
		weixinInitInfoShow(it_data_array_hide_chart,$(".it-weixin .account-list"));

		weixinInitInfoShow(fashion_data_array_show_chart,$(".fashion-weixin .account-detail"));
		weixinInitInfoShow(fashion_data_array_hide_chart,$(".fashion-weixin .account-list"));

		weixinInitInfoShow(health_data_array_show_chart,$(".health-weixin .account-detail"));
		weixinInitInfoShow(health_data_array_hide_chart,$(".health-weixin .account-list"));

		weixinInitInfoShow(life_data_array_show_chart,$(".life-weixin .account-detail"));
		weixinInitInfoShow(life_data_array_hide_chart,$(".life-weixin .account-list"));

		function weixinInitInfoShow(media_cate_data_array,media_cate){
			var _detail_url = $(".weixin-resource .portrait").attr("href").substring(0,48);
			for(i in media_cate_data_array) {
				media_cate.find(".portrait").eq(i).attr("href",_detail_url + media_cate_data_array[i].media_uuid);
				media_cate.find(".portrait").eq(i).attr("target",'_blank');
				media_cate.find(".portrait img").eq(i).attr("src", 'http://open.weixin.qq.com/qr/code/?username='+ media_cate_data_array[i].weixin_id);
				media_cate.find(".name").eq(i).text(media_cate_data_array[i].media_name);
				media_cate.find(".name").eq(i).attr("href",_detail_url + media_cate_data_array[i].media_uuid);
				media_cate.find(".name").eq(i).attr("target",'_blank');
				media_cate.find(".id").eq(i).text(media_cate_data_array[i].weixin_id);
				media_cate.find(".intro").eq(i).text(media_cate_data_array[i].short_desc);
				media_cate.find(".follower-num").eq(i).text(media_cate_data_array[i].follower_num);
				media_cate.find(".avg-view-num").eq(i).text(media_cate_data_array[i].m_head_avg_view_num);
				media_cate.find(".publish-time").eq(i).text(media_cate_data_array[i].latest_article_post_date);
				media_cate.find(".wmi-num").eq(i).text(media_cate_data_array[i].wmi);
			}
		}

		//近7天头条平均阅读数图表展示
		// var value_x = car_data_array_show_chart[0].avg_read_num_date;
		// var time = value_x.split(",");


		var value_x_one = car_data_array_show_chart[0].avg_read_num_date.split(",");
		var value_y_one = car_data_array_show_chart[0].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-one",value_x_one,value_y_one);

		var value_x_two = car_data_array_show_chart[1].avg_read_num_date.split(",");
		var value_y_two = car_data_array_show_chart[1].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-two",value_x_two,value_y_two);

		var value_x_three = baby_data_array_show_chart[0].avg_read_num_date.split(",");
		var value_y_three = baby_data_array_show_chart[0].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-three",value_x_three,value_y_three);

		var value_x_four = baby_data_array_show_chart[1].avg_read_num_date.split(",");
		var value_y_four = baby_data_array_show_chart[1].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-four",value_x_four,value_y_four);

		var value_x_five = it_data_array_show_chart[0].avg_read_num_date.split(",");
		var value_y_five = it_data_array_show_chart[0].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-five",value_x_five,value_y_five);

		var value_x_six = it_data_array_show_chart[1].avg_read_num_date.split(",");
		var value_y_six = it_data_array_show_chart[1].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-six",value_x_six,value_y_six);

		var value_x_seven = fashion_data_array_show_chart[0].avg_read_num_date.split(",");
		var value_y_seven = fashion_data_array_show_chart[0].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-seven",value_x_seven,value_y_seven);

		var value_x_eight = fashion_data_array_show_chart[1].avg_read_num_date.split(",");
		var value_y_eight = fashion_data_array_show_chart[1].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-eight",value_x_eight,value_y_eight);

		var value_x_nine = health_data_array_show_chart[0].avg_read_num_date.split(",");
		var value_y_nine = health_data_array_show_chart[0].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-nine",value_x_nine,value_y_nine);

		var value_x_ten = health_data_array_show_chart[1].avg_read_num_date.split(",");
		var value_y_ten = health_data_array_show_chart[1].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-ten",value_x_ten,value_y_ten);

		var value_x_eleven = life_data_array_show_chart[0].avg_read_num_date.split(",");
		var value_y_eleven = life_data_array_show_chart[0].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-eleven",value_x_eleven,value_y_eleven);

		var value_x_twelve = life_data_array_show_chart[1].avg_read_num_date.split(",");
		var value_y_twelve = life_data_array_show_chart[1].avg_read_num_value.split(",");
		avgViewNumChartShow("read-num-chart-twelve",value_x_twelve,value_y_twelve);

		function avgViewNumChartShow(_id,time,_value_y){
			var myChart = echarts.init(document.getElementById(_id));
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
					data: _value_y,
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
	});


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~微博部分数据展示~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	var weibo_list_url = $('input#weibo-list-url').val();

	$.get(weibo_list_url,function(data,status){

		//定义分类数组和是否显示图表数组
		var car_data_array = [];
		var car_data_array_show_chart = [];
		var car_data_array_hide_chart = [];

		var baby_data_array = [];
		var baby_data_array_show_chart = [];
		var baby_data_array_hide_chart = [];

		var it_data_array = [];
		var it_data_array_show_chart = [];
		var it_data_array_hide_chart = [];

		var fashion_data_array = [];
		var fashion_data_array_show_chart = [];
		var fashion_data_array_hide_chart = [];

		var health_data_array = [];
		var health_data_array_show_chart = [];
		var health_data_array_hide_chart = [];

		var life_data_array = [];
		var life_data_array_show_chart = [];
		var life_data_array_hide_chart = [];

		//判断分类
		for(i in data){
			if(data[i].media_cate == 4){
				car_data_array.push(data[i]);
			}
			if(data[i].media_cate == 10){
				baby_data_array.push(data[i]);
			}
			if(data[i].media_cate == 7){
				it_data_array.push(data[i]);
			}
			if(data[i].media_cate == 5){
				fashion_data_array.push(data[i]);
			}
			if(data[i].media_cate == 12){
				health_data_array.push(data[i]);
			}
			if(data[i].media_cate == 2){
				life_data_array.push(data[i]);
			}
		}
		showOrHideChart(car_data_array,car_data_array_show_chart,car_data_array_hide_chart);

		showOrHideChart(baby_data_array,baby_data_array_show_chart,baby_data_array_hide_chart);

		showOrHideChart(it_data_array,it_data_array_show_chart,it_data_array_hide_chart);

		showOrHideChart(fashion_data_array,fashion_data_array_show_chart,fashion_data_array_hide_chart);

		showOrHideChart(health_data_array,health_data_array_show_chart,health_data_array_hide_chart);

		showOrHideChart(life_data_array,life_data_array_show_chart,life_data_array_hide_chart);
		//判断是否显示图表
		function showOrHideChart(parent_data_array,data_array_show_chart,data_array_hide_chart ){
			for(i in parent_data_array){
				if(parent_data_array[i].show_chart == 1){
					data_array_show_chart.push(parent_data_array[i]);
				}else {
					data_array_hide_chart.push(parent_data_array[i]);
				}
			}
		}

		//获取四个坐标最大值
		function getChartMaxNum(data_array,num_type){
			var data_max_comment_array = [];
			if(num_type == "comment_num"){
				for (i in data_array){
					data_max_comment_array.push(data_array[i].total_comment_num);
				}
			}
			if(num_type == "like_num"){
				for (i in data_array){
					data_max_comment_array.push(data_array[i].total_like_num);
				}
			}
			if(num_type == "follower_num"){
				for (i in data_array){
					data_max_comment_array.push(data_array[i].follower_num);
				}
			}
			if(num_type == "forward_num"){
				for (i in data_array){
					data_max_comment_array.push(data_array[i].total_forward_num);
				}
			}
			return Math.max.apply(null, data_max_comment_array);

		}

		weiboInitInfoShow(car_data_array_show_chart,$(".car-weibo .account-detail"));
		weiboInitInfoShow(car_data_array_hide_chart,$(".car-weibo .account-list"));

		weiboInitInfoShow(baby_data_array_show_chart,$(".baby-weibo .account-detail"));
		weiboInitInfoShow(baby_data_array_hide_chart,$(".baby-weibo .account-list"));

		weiboInitInfoShow(it_data_array_show_chart,$(".it-weibo .account-detail"));
		weiboInitInfoShow(it_data_array_hide_chart,$(".it-weibo .account-list"));

		weiboInitInfoShow(fashion_data_array_show_chart,$(".fashion-weibo .account-detail"));
		weiboInitInfoShow(fashion_data_array_hide_chart,$(".fashion-weibo .account-list"));

		weiboInitInfoShow(health_data_array_show_chart,$(".health-weibo .account-detail"));
		weiboInitInfoShow(health_data_array_hide_chart,$(".health-weibo .account-list"));

		weiboInitInfoShow(life_data_array_show_chart,$(".life-weibo .account-detail"));
		weiboInitInfoShow(life_data_array_hide_chart,$(".life-weibo .account-list"));

		function weiboInitInfoShow(media_cate_data_array,media_cate){
			for(i in media_cate_data_array) {
				media_cate.find(".portrait").eq(i).attr("href",media_cate_data_array[i].source_weibo_url);
				media_cate.find(".portrait").eq(i).attr("target",'_blank');
				media_cate.find(".portrait img").eq(i).attr("src",media_cate_data_array[i].avatar_img);
				media_cate.find(".name").eq(i).text(media_cate_data_array[i].media_name);
				media_cate.find(".name").eq(i).attr("href",media_cate_data_array[i].source_weibo_url);
				media_cate.find(".name").eq(i).attr("target",'_blank');
				media_cate.find(".id").eq(i).text(media_cate_data_array[i].weixin_id);
				media_cate.find(".intro").eq(i).text(media_cate_data_array[i].short_desc);
				media_cate.find(".follower-num").eq(i).text(media_cate_data_array[i].follower_num);
				media_cate.find(".comment-num").eq(i).text(media_cate_data_array[i].total_comment_num);
				media_cate.find(".transmit-num").eq(i).text(media_cate_data_array[i].total_forward_num);
				media_cate.find(".agree-num").eq(i).text(media_cate_data_array[i].total_like_num);
			}
		}


		//评论转发点赞粉丝数图表展示例
		var value_one = [car_data_array_show_chart[0].total_comment_num,car_data_array_show_chart[0].total_forward_num,car_data_array_show_chart[0].follower_num,car_data_array_show_chart[0].total_like_num];
		varietyDataChartShow("variety-data-one",value_one,car_data_array);

		var value_two = [car_data_array_show_chart[1].total_comment_num,car_data_array_show_chart[1].total_forward_num,car_data_array_show_chart[1].follower_num,car_data_array_show_chart[1].total_like_num];
		varietyDataChartShow("variety-data-two",value_two,car_data_array);

		var value_three = [baby_data_array_show_chart[0].total_comment_num,baby_data_array_show_chart[0].total_forward_num,baby_data_array_show_chart[0].follower_num,baby_data_array_show_chart[0].total_like_num];
		varietyDataChartShow("variety-data-three",value_three,baby_data_array);

		var value_four = [baby_data_array_show_chart[1].total_comment_num,baby_data_array_show_chart[1].total_forward_num,baby_data_array_show_chart[1].follower_num,baby_data_array_show_chart[1].total_like_num];
		varietyDataChartShow("variety-data-four",value_four,baby_data_array);

		var value_five = [it_data_array_show_chart[0].total_comment_num,it_data_array_show_chart[0].total_forward_num,it_data_array_show_chart[0].follower_num,it_data_array_show_chart[0].total_like_num];
		varietyDataChartShow("variety-data-five",value_five,it_data_array);

		var value_six = [it_data_array_show_chart[1].total_comment_num,it_data_array_show_chart[1].total_forward_num,it_data_array_show_chart[1].follower_num,it_data_array_show_chart[1].total_like_num];
		varietyDataChartShow("variety-data-six",value_six,it_data_array);

		var value_seven = [fashion_data_array_show_chart[0].total_comment_num,fashion_data_array_show_chart[0].total_forward_num,fashion_data_array_show_chart[0].follower_num,fashion_data_array_show_chart[0].total_like_num];
		varietyDataChartShow("variety-data-seven",value_seven,fashion_data_array);

		var value_eight = [fashion_data_array_show_chart[1].total_comment_num,fashion_data_array_show_chart[1].total_forward_num,fashion_data_array_show_chart[1].follower_num,fashion_data_array_show_chart[1].total_like_num];
		varietyDataChartShow("variety-data-eight",value_eight,fashion_data_array);

		var value_nine = [health_data_array_show_chart[0].total_comment_num,health_data_array_show_chart[0].total_forward_num,health_data_array_show_chart[0].follower_num,health_data_array_show_chart[0].total_like_num];
		varietyDataChartShow("variety-data-nine",value_nine,health_data_array);

		var value_ten = [health_data_array_show_chart[1].total_comment_num,health_data_array_show_chart[1].total_forward_num,health_data_array_show_chart[1].follower_num,health_data_array_show_chart[1].total_like_num];
		varietyDataChartShow("variety-data-ten",value_ten,health_data_array);

		var value_eleven = [life_data_array_show_chart[0].total_comment_num,life_data_array_show_chart[0].total_forward_num,life_data_array_show_chart[0].follower_num,life_data_array_show_chart[0].total_like_num];
		varietyDataChartShow("variety-data-eleven",value_eleven,life_data_array);

		var value_twelve = [life_data_array_show_chart[1].total_comment_num,life_data_array_show_chart[1].total_forward_num,life_data_array_show_chart[1].follower_num,life_data_array_show_chart[1].total_like_num];
		varietyDataChartShow("variety-data-twelve",value_twelve,life_data_array);





		function varietyDataChartShow(_id,_value,_data_array){
			var myChart = echarts.init(document.getElementById(_id));
			var option = {
				tooltip: {
					trigger: 'axis'
				},

				radar: [
					{
						indicator: [
							{text: '评论数', max: getChartMaxNum(_data_array,'comment_num')},
							{text: '转发数', max:  getChartMaxNum(_data_array,'forward_num')},
							{text: '粉丝数', max:  getChartMaxNum(_data_array,'follower_num')},
							{text: '点赞数', max:  getChartMaxNum(_data_array,'like_num')}
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
								value: _value
							}
						]
					}
				]
			};
			myChart.setOption(option);
		};
	});
})
