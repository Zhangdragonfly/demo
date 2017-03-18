$(function(){
	//鼠标放上去显示完整信息
	$("a[data-title]").each(function() {
		var a = $(this);
		var title = a.attr('data-title');
		if (title == undefined || title == "") return;
		a.data('data-title', title).hover(function () {
				var offset = a.offset();
				$("<div class='show-all-info'>"+title+"</div>").appendTo($(".table-wrap")).css({ top: offset.top + a.outerHeight()-10, left: offset.left + a.outerWidth() + 1 }).fadeIn(function () {
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

	//页面渲染时判断显示的发布类型
	$('.source-choosed-table tr .data-json').each(function(){
		var _data_text = $(this).text();
		var _json_obj= JSON.parse(_data_text);

		if(_json_obj.pos_s.pub_type == 0){
			judgePubShow(this,'单图文');
		}
		if(_json_obj.pos_m_1.pub_type == 0){
			judgePubShow(this,'多图文第一条');
		}
		if(_json_obj.pos_m_2.pub_type == 0){
			judgePubShow(this,'多图文第二条');
		}
		if(_json_obj.pos_m_3.pub_type == 0){
			judgePubShow(this,'多图文第3-N条');
		}
		var _li_text = $(this).parents('tr').find('.dropdown-menu li:eq(0)').text();
		$(this).parents('tr').find('.dropdown div span:eq(0)').text(_li_text);

	})
	function judgePubShow(_this,text_con){
		$(_this).parents('tr').find('.dropdown-menu li').each(function(){
			var _text = $(this).text();
			if(_text == ''+text_con+''){
				$(this).remove();
			}

		})
	}
	//选择投放位置时的数据联动
	$('.source-choosed-table .dropdown-menu li').click(function(){
		var _data_text = $(this).parents('td').prev().children('.data-json').text();
		var json_obj= JSON.parse(_data_text);
		var pos_text = $(this).text();

		var pub_type = '';
		if(pos_text == '单图文'){
			pub_type = json_obj.pos_s.retail_price;
			var _pos_s_value = json_obj.pos_s.has_add_content;
			changeText(this,_pos_s_value);
			eachPubType(this,'单图文');

		} else if(pos_text == '多图文第一条'){
			pub_type = json_obj.pos_m_1.retail_price;
			var _pos_m_1_value = json_obj.pos_m_1.has_add_content;
			changeText(this,_pos_m_1_value);
			eachPubType(this,'多图文第一条');
		} else if(pos_text == '多图文第二条'){
			pub_type = json_obj.pos_m_2.retail_price;
			var _pos_m_2_value = json_obj.pos_m_2.has_add_content;
			changeText(this,_pos_m_2_value);
			eachPubType(this,'多图文第二条');
		} else if(pos_text == '多图文第3-N条'){
			pub_type = json_obj.pos_m_3.retail_price;
			var _pos_m_3_value = json_obj.pos_m_3.has_add_content;
			changeText(this,_pos_m_3_value);
			eachPubType(this,'多图文3-N条');
		}
		$(this).parents('td').next().text(pub_type);
	})
	function changeText(_this,pos_value){
		if(pos_value == 1){
			$(_this).parents('tr').find('.req-status span').addClass('set-font-green').text('已添加需求');
			$(_this).parents('tr').find('.operate a:eq(0)').text('修改需求');
		} else if(pos_value == 0){
			$(_this).parents('tr').find('.req-status span').removeClass('set-font-green').text('未添加需求');
			$(_this).parents('tr').find('.operate a:eq(0)').text('添加需求');
		}
	}
	//遍历备注的发布类型
	function eachPubType(_this,_pub_type){
		$(_this).parents('tr').find('.remark li').each(function(){
			var _text = $(this).children('span:eq(0)').text();
			if(_text == ''+_pub_type+"："+''){
				$(this).addClass('set-font-color').siblings().removeClass('set-font-color');
			}
		});
	}

})
$(function(){
	$('.data-show').addClass('data-show-position');
	/*var _foot_h = $('.footer-wrap').height();
	var _data_show_h = $('.data-show').height();
	var _sub_h = _foot_h - _data_show_h;*/
	$(window).scroll(function(){
		var _scroll_t = $(window).scrollTop();
		if(_scroll_t >= 20){
			$('.data-show').removeClass('data-show-position');
		} else{
			$('.data-show').addClass('data-show-position');
		}
	})
})
