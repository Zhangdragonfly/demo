$(function(){
	//点击编辑活动名称
	$('.modify-active-name').click(function(){
		$(this).parents('.active-name').css('display','none');
		$(this).parents('.active-name').next().find('input').val($(this).prev().text());
		$(this).parents('.active-name').next().css('display','block');
	})
	//活动名称点击保存
	$('.save-edit').click(function(){
		$(this).parents('.edit-active-name').prev().find('span:eq(0)').text($(this).prev().val());
		$(this).parents('.edit-active-name').css('display','none');
		$(this).parents('.edit-active-name').prev().css('display','block');
	})
	//活动名称点击取消
	$('.cancle-edit').click(function(){
		$(this).parents('.edit-active-name').css('display','none');
		$(this).parents('.edit-active-name').prev().css('display','block');
	})
	//页面渲染的时候需求概述不能编辑
	$('.requirements-sum textarea:eq(0)').prop({disabled:true}).css({background:'#ffffff'});
	//需求概述点击修改

	$('.edit-requirements-sum span').click(function(){
		var _val = $('.edit-requirements-sum').prev().find('textarea:eq(0)').val();
		$(this).css('display','none');
		$(this).siblings('.btn').css('display','inline-block');
		$(this).parents('.edit-requirements-sum').prev().find('textarea:eq(0)').css('display','none');
		$(this).parents('.edit-requirements-sum').prev().find('textarea:eq(1)').val(_val).css('display','block');
	})

	//点击保存
	$('.edit-requirements-sum .save-edit').click(function(){
		var _val = $('.edit-requirements-sum').prev().find('textarea:eq(1)').val();
		editContent(this,_val);
	})
	//点击取消
	$('.edit-requirements-sum .cancle-edit').click(function(){
		var _val = $('.edit-requirements-sum').prev().find('textarea:eq(0)').val();
		editContent(this,_val);
	})
	//保存取消时需求概述内容编辑
	function editContent(_this,value){
		$(_this).parents('.edit-requirements-sum').find('.btn').css('display','none');
		$(_this).siblings('span').css('display','block');
		$(_this).parents('.edit-requirements-sum').prev().find('textarea:eq(0)').val(value).css('display','block');
		$(_this).parents('.edit-requirements-sum').prev().find('textarea:eq(1)').css('display','none');
	}
	//鼠标放上去显示完整信息
	$("a[data-title]").each(function() {
		var a = $(this);
		var title = a.attr('data-title');
		if (title == undefined || title == "") return;
		a.data('data-title', title).hover(function () {
				var offset = a.offset();
				$("<div class='show-all-info'>"+title+"</div>").appendTo($(".table-wrap")).css({ top: offset.top + a.outerHeight()+0, left: offset.left + a.outerWidth() - 10 }).fadeIn(function () {
				});
			},
			function(){ $(".show-all-info").remove();
			}
		);
	});
	//二维码显示
	$('.ewm').hover(function(){
		$(this).siblings('img').css({display:'block'});
	},function(){
		$(this).siblings('img').css({display:'none'});
	})


	//操作json
	var buttons_config = {
		"update_demand":"btn-update-demand",//修改需求
		"add_demand":"btn-add-demand",//添加需求
		"del_order":"btn-del-order",//取消
		"direct_order_detail":"btn-direct-order-detail",//直投详情
		"to_verify_execute_link":"btn-to-verify-execute-link",//确认执行
		"show_report":"btn-show-report",//查看报告
		"invalid_order_info":"btn-invalid-order-info",//原因
		"arrange_order_detail":"btn-arrange-order-detail",//执行前详情
		"arrange_order_more_detail":"btn-arrange-order-more-detail",//执行中详情
		"show_execute_link":"btn-show-execute-link",//执行链接
		"show_effect_shots": "btn-show-effect-shots",//执行效果截图
	};
	var active_pos_config = {
		'pos_s': '单图文',
		'pos_m_1': '多图文头条',
		'pos_m_2': '多图文2条',
		'pos_m_3': '多图文3-N条'
	};
	var remark_pub_type_config = {
		'-1': '未设置',
		'0': '不接单',
		'1': '只发布',
		'2': '只原创'
	}
	//表格初始化页面渲染时数据展示
	$('.order-table tr').each(function(){
		var paid_status = $(this).attr('data-is-paid');
		var data_json_config = '';
		//判断是否已支付，并根据是否支付初始化data_json_config的值，便于后期传值；
		if( paid_status == 1 ){
			data_json_config = JSON.parse($(this).find('.has-paid-data-config').text());
			$(this).find('.pub-type-read').css('display','block');
			initHasPaidRecord(this,data_json_config);
			return;
		} else if(paid_status == 0) {
			data_json_config = JSON.parse($(this).find('.has-not-paid-data-config').text());
			$(this).find('.dropdown').css('display','block');
			initHasNotPaidRecord(this,data_json_config);
			return;
		}
	})
	//已支付状态下的初始化每条记录
	function initHasPaidRecord(_this,data_json_config){
		var _pos_location = data_json_config.pos,
			_pub_type = data_json_config.pub_type,
		    _retail_price = data_json_config.retail_price,
			_order_status = data_json_config.order_status,
			_order_status_label = data_json_config.order_status_label,
			_head_avg_read_num = data_json_config.head_avg_read_num,
			_total_follower_num = data_json_config.total_follower_num;

		//判断投放位置，并显示
		var _show_pos_location = '';
		switch (_pos_location){
			case 'pos_m_1':
				var data_pub_type = data_json_config.pos_m_1.pub_type;
				_show_pos_location = '多图文头条';
				$(_this).find('.pub-type-read-only').attr('data-pos','pos_m_1');
				$(_this).attr('data-pub-type',data_pub_type);
				break;
			case 'pos_m_2':
				var data_pub_type = data_json_config.pos_m_2.pub_type;
				_show_pos_location = '多图文2条';
				$(_this).find('.pub-type-read-only').attr('data-pos','pos_m_2');
				$(_this).attr('data-pub-type',data_pub_type);
				break;
			case 'pos_m_3':
				var data_pub_type = data_json_config.pos_m_3.pub_type;
				_show_pos_location = '多图文第3-N条';
				$(_this).find('.pub-type-read-only').attr('data-pos','pos_m_3');
				$(_this).attr('data-pub-type',data_pub_type);
				break;
			case 'pos_s_1':
				var data_pub_type = data_json_config.pos_s.pub_type;
				_show_pos_location = '单图文';
				$(_this).find('.pub-type-read-only').attr('data-pos','pos_s');
				$(_this).attr('data-pub-type',data_pub_type);
				break;
		}
		$(_this).find('.pub-type-read-only').text(_show_pos_location);
		//参考报价显示
		$(_this).find('.area-retail-price').append('<span class="retail-price">'+_retail_price+'</span>');
		//需求状态/执行状态显示及颜色判断
		$(_this).find('.order-status').append('<span class="has-paid-status">'+_order_status_label+'</span>');
		switch(_order_status){
			case 5:
				$(_this).find('.has-paid-status').attr('class','set-font-green');
				break;
			case -1:case 0:case 1:case 22:case 24:
				$(_this).find('.has-paid-status').attr('class','red');
				break;
			case 3:case 4:case 5:
				$(_this).find('.has-paid-status').attr('class','');
				break;
		}
		//操作判断并显示
		operateJudge(_this,'operate',data_json_config.operate_action);


		//备注发布类型判断显示，并高亮显示；
		pubType(_this,data_json_config);
		//得到投放位置的data-pos属性值，遍历备注并高亮显示；
		var pub_pos_data_value = $(_this).find('.pub-type-read-only').attr('data-pos');
		$(_this).find('.remark li').each(function(){
			var _this_data_pos = $(this).attr('data-pos');
			if(_this_data_pos == pub_pos_data_value){
				$(this).addClass('red');
			}
		});

		//初始化值显示
		$(_this).attr('data-retail-price',_retail_price);
		$(_this).attr('data-head-avg-read-num',_head_avg_read_num);
		$(_this).attr('data-total-follower-num',_total_follower_num);
	}

	//操作显示判断
	function operateJudge(_this,_result_operate,data_json_config){
		//从json中获取值
		var _json_value = data_json_config;
		//转化成数组
		var _json_value_arr = _json_value.substr(1,_json_value.length-2).split(',');
		//从buttons_config中得到想要的操作按钮，并放入数组中
		var btn_arr = [];
		for(var i = 0; i < _json_value_arr.length;i++){
			btn_arr.push(buttons_config[_json_value_arr[i]]);
		}
		//遍历HTML中operate的li，并获取与传入数值相同的btn；
		$(_this).find('.'+_result_operate+' li').each(function(){
			var _attr_data_btn = $(this).attr('data-btn');
			for(var i = 0; i <btn_arr.length ;i++){
				if(_attr_data_btn == btn_arr[i]){
					$(this).css('display','block');
				}
			}
		});
	}
	//备注发布类型判断并显示
	function pubType(_this,data_json_config){
		var pos_s_pub_type_label = remark_pub_type_config[data_json_config.pos_s.pub_type];
		var pos_m_1_pub_type_label = remark_pub_type_config[data_json_config.pos_m_1.pub_type];
		var pos_m_2_pub_type_label = remark_pub_type_config[data_json_config.pos_m_2.pub_type];
		var pos_m_3_pub_type_label = remark_pub_type_config[data_json_config.pos_m_3.pub_type];

		$(_this).find('.pos-m-1 .pub-type-label').text(pos_m_1_pub_type_label);
		$(_this).find('.pos-m-2 .pub-type-label').text(pos_m_2_pub_type_label);
		$(_this).find('.pos-m-3 .pub-type-label').text(pos_m_3_pub_type_label);
		$(_this).find('.pos-s .pub-type-label').text(pos_s_pub_type_label);
	}
	//未支付状态下初始化每条记录
	function initHasNotPaidRecord(_this,data_json_config){
		//投放位置下拉单加载
		var pub_pos_list = {};
		if (data_json_config.pos_s.pub_type != 0) {
			pub_pos_list['pos_s'] = active_pos_config['pos_s'];
		}
		if (data_json_config.pos_m_1.pub_type != 0) {
			pub_pos_list['pos_m_1'] = active_pos_config['pos_m_1'];
		}
		if (data_json_config.pos_m_2.pub_type != 0) {
			pub_pos_list['pos_m_2'] = active_pos_config['pos_m_2'];
		}
		if (data_json_config.pos_m_3.pub_type != 0) {
			pub_pos_list['pos_m_3'] = active_pos_config['pos_m_3'];
		}
		var pub_pos_select = $(_this).find('.dropdown-menu');
		for (var _pos_code in pub_pos_list) {
			var _pos_label = pub_pos_list[_pos_code];
			var pos_cls = '';
			if (_pos_code == 'pos_s') {
				pos_cls = 'pos-s';
			} else if (_pos_code == 'pos_m_1') {
				pos_cls = 'pos-m-1';
			} else if (_pos_code == 'pos_m_2') {
				pos_cls = 'pos-m-2';
			} else if (_pos_code == 'pos_m_3') {
				pos_cls = 'pos-m-3';
			}
			pub_pos_select.append('<li class=' + '"one-pos' + ' ' + pos_cls + '" data-pos="' + _pos_code + '">' + _pos_label + '</li>');
		}
		//备注发布类型判断
		pubType(_this,data_json_config);
		//默认判断和备注高亮显示
		var selected_pos_label = '';
		var has_add_content = 0;
		var retail_price = 0;
		var pub_type = -1;
		var	_head_avg_read_num = data_json_config.head_avg_read_num;
		var	_total_follower_num = data_json_config.total_follower_num;
		if( data_json_config.pos_s.is_selected == 1 ){
			selected_pos_label = active_pos_config['pos_s'];
			retail_price = data_json_config.pos_s.retail_price;
			pub_type = data_json_config.pos_s.pub_type;
			has_add_content = data_json_config.pos_s.has_add_content;
			// 控制备注里的高亮显示
			$(_this).find('.remark .one-pos').removeClass('set-font-color');
			$(_this).find('.remark .one-pos.pos-s').addClass('set-font-color');
		}
		if( data_json_config.pos_m_1.is_selected == 1 ){
			selected_pos_label = active_pos_config['pos_m_1'];
			retail_price = data_json_config.pos_m_1.retail_price;
			pub_type = data_json_config.pos_m_1.pub_type;
			has_add_content = data_json_config.pos_m_1.has_add_content;
			// 控制备注里的高亮显示
			$(_this).find('.remark .one-pos').removeClass('set-font-color');
			$(_this).find('.remark .one-pos.pos-m-1').addClass('set-font-color');
		}
		if( data_json_config.pos_m_2.is_selected == 1 ){
			selected_pos_label = active_pos_config['pos_m_2'];
			retail_price = data_json_config.pos_m_2.retail_price;
			pub_type = data_json_config.pos_m_2.pub_type;
			has_add_content = data_json_config.pos_m_2.has_add_content;
			// 控制备注里的高亮显示
			$(_this).find('.remark .one-pos').removeClass('set-font-color');
			$(_this).find('.remark .one-pos.pos-m-2').addClass('set-font-color');
		}
		if( data_json_config.pos_m_3.is_selected == 1 ){
			selected_pos_label = active_pos_config['pos_m_3'];
			retail_price = data_json_config.pos_m_3.retail_price;
			pub_type = data_json_config.pos_m_3.pub_type;
			has_add_content = data_json_config.pos_m_3.has_add_content;
			// 控制备注里的高亮显示
			$(_this).find('.remark .one-pos').removeClass('set-font-color');
			$(_this).find('.remark .one-pos.pos-m-1').addClass('set-font-color');
		}
		$(_this).find('.selected-pos').text(selected_pos_label);
		$(_this).find('.area-retail-price').append('<span class="retail-price">'+retail_price+'</span>');

		//未付款订单改变需求添加状态和操作
		changeStatusAndOperate($(_this),has_add_content);

		//初始化值显示
		$(_this).attr('data-pub-type',pub_type);
		$(_this).attr('data-retail-price',retail_price);
		$(_this).attr('data-head-avg-read-num',_head_avg_read_num);
		$(_this).attr('data-total-follower-num',_total_follower_num);
	}
	//点击下拉选择时数据联动
	$('.pub-pos .one-pos').click(function(){
		var _this_tr = $(this).parents('tr');
		var has_not_paid_config = JSON.parse(_this_tr.find('.has-not-paid-data-config').text());
		//console.log(has_not_paid_config)
		var _data_code = $(this).attr('data-pos');
		_this_tr.find('.selected-pos').text(active_pos_config[_data_code]);

		if(_data_code == 'pos_s'){
			var retail_price = has_not_paid_config.pos_s.retail_price;
			var has_add_content = has_not_paid_config.pos_s.has_add_content;

			changeStatusAndOperate(_this_tr,has_add_content);
			changeRemark(_this_tr, _data_code)

			_this_tr.find('.retail-price').text(retail_price);
			_this_tr.attr('data-retail-price',retail_price);
		}
		if(_data_code == 'pos_m_1'){
			var retail_price = has_not_paid_config.pos_m_1.retail_price;
			var has_add_content = has_not_paid_config.pos_m_1.has_add_content;

			changeStatusAndOperate(_this_tr,has_add_content);
			changeRemark(_this_tr, _data_code)

			_this_tr.find('.retail-price').text(retail_price);
			_this_tr.attr('data-retail-price',retail_price);
		}
		if(_data_code == 'pos_m_2'){
			var retail_price = has_not_paid_config.pos_m_2.retail_price;
			var has_add_content = has_not_paid_config.pos_m_2.has_add_content;

			changeStatusAndOperate(_this_tr,has_add_content);
			changeRemark(_this_tr, _data_code)

			_this_tr.find('.retail-price').text(retail_price);
			_this_tr.attr('data-retail-price',retail_price);
		}
		if(_data_code == 'pos_m_3'){
			var retail_price = has_not_paid_config.pos_m_3.retail_price;
			var has_add_content = has_not_paid_config.pos_m_3.has_add_content;

			changeStatusAndOperate(_this_tr,has_add_content);
			changeRemark(_this_tr, _data_code);
			_this_tr.find('.retail-price').text(retail_price);
			_this_tr.attr('data-retail-price',retail_price);

		}
		countSomeStatistics();

	})
	//未付款订单改变需求添加状态和操作
	function changeStatusAndOperate(_this,has_add_content){
		if (has_add_content == 1) {
			// 已经添加需求
			// 状态
			_this.find('.order-status .status-not-add-content').css('display','none');
			_this.find('.order-status .status-has-add-content').css('display','block');
			//console.log()
			// 操作
			_this.find('.operate .btn-add-demand').css('display','none');
			_this.find('.operate .btn-update-demand').css('display','block');
			_this.find('.operate .btn-del-order').css('display','block');
		} else if (has_add_content == 0) {
			// 未添加需求
			// 状态
			_this.find('.order-status .status-has-add-content').css('display','none');
			_this.find('.order-status .status-not-add-content').css('display','block');
			// 操作
			_this.find('.operate .btn-update-demand').css('display','none');
			_this.find('.operate .btn-add-demand').css('display','block');
			_this.find('.operate .btn-del-order').css('display','block')
		}
	}

	//遍历备注高亮显示
	function changeRemark(_this, _data_code) {
		_this.find('.remark .one-pos').removeClass('set-font-color');
		if (_data_code == 'pos_s') {
			_this.find('.remark .pos-s').addClass('set-font-color');
		} else if (_data_code == 'pos_m_1') {
			_this.find('.remark .pos-m-1').addClass('set-font-color');
		} else if (_data_code == 'pos_m_2') {
			_this.find('.remark .pos-m-2').addClass('set-font-color');
		} else if (_data_code == 'pos_m_3') {
			_this.find('.remark .pos-m-3').addClass('set-font-color');
		}
	}
	/**
	 * 计算一些指标
	 */
	countSomeStatistics();
	function countSomeStatistics() {
		var total_retail_price = 0; // 总零售价
		var total_retail_price_online_pay = 0; // 总在线支付价
		var total_read_num = 0; // 阅读数
		var total_follower_num = 0; // 粉丝数
		var total_account = 0; // 账号数
		var pub_type = -1;
		var retail_price = 0;


		$('.order-table tr').each(function () {
			var data_paid = $(this).attr('data-is-paid');

			total_account++;
			if(data_paid == 0){
				pub_type = $(this).attr('data-pub-type');
				retail_price = Number($(this).attr('data-retail-price'));
				total_retail_price += retail_price;
				if (pub_type == 1) {
					// 只发布
					total_retail_price_online_pay += retail_price;
				}
			}

			total_read_num += Number($(this).attr('data-head-avg-read-num'));
			total_follower_num += Number($(this).attr('data-total-follower-num'));
		});

		$('.total-account').text(total_account);
		$('.total-read-num').text(total_read_num);
		$('.total-follower-num').text(total_follower_num);
		$('.stat-total-retail-price').text(total_retail_price);
		$('.stat-total-retail-price-to-pay').text(total_retail_price_online_pay);
	}
})

//滚动定位
$(function () {
	$('.data-show').addClass('data-show-position');
	var _foot_t = $('.footer-wrap').offset().top;

	$(window).scroll(function () {
		var _scroll_t = $(window).scrollTop();
		if (_scroll_t >= _foot_t - $(window).height()) {
			$('.data-show').removeClass('data-show-position');
		} else {
			$('.data-show').addClass('data-show-position');
		}
	})
})
