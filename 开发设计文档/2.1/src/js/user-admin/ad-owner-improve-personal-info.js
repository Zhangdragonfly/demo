//~~~~限制字符长度函数~~~~
function constraintLength(){
  for(var i=0;i<$('.synopsis').length;i++){
    var strlen = $('.synopsis').eq(i).data('str');
    if($('.synopsis').eq(i).text().length>strlen){
      $('.synopsis').eq(i).text($('.synopsis').eq(i).text().trim().substr(0,strlen));
      $('.synopsis').eq(i).html($('.synopsis').eq(i).html()+"...");
    }
  }
}
// ~~~~~用户微信登录的二维码显示~~~~~~
  $(".small-code").on("mouseenter",function(){
    $(".code").fadeIn();
  })
  $(".small-code").on("mouseleave",function(){
    $(".code").fadeOut();
  })

  //~~~~~~~自定义地域选择引入~~~~~~~~
  $('.follower-area-province').change(function() {
      var provinceCode = $(this).val();
      var followerAreaCitySelect = $('.follower-area-city');
      var cityList = getCityConfigWithProvinceCode(provinceCode);
      followerAreaCitySelect.find('option').remove();
      followerAreaCitySelect.append($('<option value="-1" selected>请选择</option>'));
      for(var i = 0; i < cityList.length; i++){
          var code = cityList[i]['code'];
          var label = cityList[i]['label'];
          followerAreaCitySelect.append($('<option value="' + code + '">' + label + '</option>'));
      }
  });

  // ~~~~~~~~所填信息判断~~~~~~~~~~
  $(".contact-way-con .must").each(function(){
      $(this).on("blur",function(){
          var title = $(this).parent().siblings().find(".title").text();
          if ($(this).val() == "") {
             layer.msg(title+"不能为空", {
              time: 1500, 
            });
             $(this).css("background","rgba(200, 22, 36, 0.2)");
          } 
      })
      $(this).on("focus",function(){
          $(this).css("background","#fff");
      })
  })


