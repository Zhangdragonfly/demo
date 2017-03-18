$(function(){
	/*  ~~~~~限制字符串长度~~~~~
		使用场合:微信,微博,视频网红的名称,个人说明过长;
		使用方法:给相应标签添加class类 .plain-text-length-limit, 再添加属性data-limit="number(希望限制的长度)";
	*/

	(function plainContentLengthLimit(){
		var lengthOfContent = $('.plain-text-length-limit').length;
		for(var i = 0; i < lengthOfContent; i++){
			var limit = $(this).data('limit');
			var content = $(this).text().trim();
			if(limit <= 0 || NaN(limit)){
				return false;
			}
			if(content.length > limit){
				var sub_content = content.substr(0, limit);
				$(this).html(sub_content + '...');
			}
		}
	})();


	/*~~~~~自定义地域选择引入(选择省份和城市)~~~~~
		使用场合:资源列表页以及个人信息完善部分用到.
		使用方法:
		 <select class="form-control province">
			 <option value="-2">请选择</option>
			 <option value="10">江苏</option>
			 <option value="11">浙江</option>
			 <option value="12">安徽</option>
			 <option value="13">福建</option>
			 <option value="14">江西</option>
			 <option value="15">山东</option>
			 <option value="16">河南</option>
			 <option value="17">湖北</option>
			 <option value="18">湖南</option>
			 <option value="19">广东</option>
			 <option value="30">宁夏</option>
			 <option value="31">新疆</option>
			 <option value="4">山西</option>
			 <option value="3">河北</option>
			 <option value="5">内蒙古</option>
			 <option value="6">辽宁</option>
			 <option value="7">吉林</option>
			 <option value="8">黑龙江</option>
			 <option value="20">广西</option>
			 <option value="21">海南</option>
			 <option value="23">四川</option>
			 <option value="24">贵州</option>
			 <option value="25">云南</option>
			 <option value="26">西藏</option>
			 <option value="27">陕西</option>
			 <option value="28">甘肃</option>
			 <option value="29">青海</option>
			 <option value="32">台湾</option>
			 <option value="33">香港</option>
			 <option value="34">澳门</option>
			 <option value="34">其他</option>
		 </select>
		 <select class="form-control city">
		 	<option value="-1" selected>请选择</option>
		 </select>
	*/
	$('.choose-area').change(function() {
		var provinceCode = $(this).val();
		var areaCitySelect = $('.city');
		var cityList = getCityConfigWithProvinceCode(provinceCode);
		areaCitySelect.find('option').remove();
		areaCitySelect.append($('<option value="-1" selected>请选择</option>'));
		for(var i = 0; i < cityList.length; i++){
			var code = cityList[i]['code'];
			var label = cityList[i]['label'];
			areaCitySelect.append($('<option value="' + code + '">' + label + '</option>'));
		}
	});


	/*  ~~~~~时间插件~~~~~
		使用场合:订单/计划投放时间的选择
		使用方法:引入相应的插件文件(jquery.datetimepicker.css和jquery.datetimepicker.js)后,只需要给相应的input标签添加class类 .datetimepicker即可.
	            为了体验更好,建议给input标签添加属性: readonly="readonly".
	*/
	$(function () {
		$(".datetimepicker").datetimepicker({
			lang:"ch",           //语言选择中文
			format:"Y-m-d H:i",      //格式化日期
			i18n:{
				// 以中文显示月份
				de:{
					months:["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月",],
					// 以中文显示每周（必须按此顺序，否则日期出错）
					dayOfWeek:["日","一","二","三","四","五","六"]
				}
			}
			// 显示成年月日，时间--
		});
		$('.search').click(function () {
			var star = $('input[name="iStarTime"]').val();
			var over = $('input[name="iOverTime"]').val();
			var iss = $('.iSource').val();
			var iss_a = $('.iSource_a').val();
			//  四个值
			location.href = "/acenter/recharge/iss/"+iss+"/star/" + star + "/over/" + over + ".html";
		})
	})
})
