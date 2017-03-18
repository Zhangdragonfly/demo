$(function(){
    /*/!**
     ** 限制文本长度,超出部分以省略号的形式展示.
     * 使用方法: 给存放文本的标签添加class类名 class = "plain-text-length-limit" ,设置属性 data-limit = "num" , num为想要限制的字数,根据自己需要设置.
     **!/
    (function plainContentLengthLimit(){
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
    })();*/
    //基础信息二维码显示
    $('.ewm').hover(function(){
        $(this).children('img').stop().show(500);
    },function(){
        $(this).children('img').stop().hide(500);
    })
    //问号帮助
    $('.help-explain').hover(function(){
        $(this).children('.help-explain-con').css('display','block');
    },function(){
        $(this).children('.help-explain-con').css('display','none');
    })
    //基础信息鼠标放上去显示完整信息
    $(".account-intro p").hover(function(){
        var title = $(this).attr('data-title');
        var offset = $(this).offset();
        if (title == undefined || title == "") return;

        $("<div class='show-all-info'>"+title+"</div>").appendTo($(".account-intro")).css({ top:160, left: 164 }).fadeIn(function () {
        });
    },function(){
        $(".show-all-info").remove();
    });
})


//详情页加载数据
$(function(){

    var media_uuid = $("#id-media-uuid").val();
    var get_wmi_url = $("#id-get-wmi-url").val();
    var get_chart_data_url = $("#id-get-chart-data-url").val();
    var get_article_data_url = $("#id-get-article-data-url").val();
    //沃米指数加载
    $.ajax({
        url: get_wmi_url,
        type: 'POST',
        cache: false,
        dataType: 'json',
        data: { media_uuid: media_uuid},
        success: function (resp) {
            if(resp.err_code == 0){
                //echarts和沃米指数圆环插件引入
                var circles = [];
                var child = document.getElementById('sum-data-type'),
                    percentage = resp.y_wmi;
                circles.push(Circles.create({
                    id:         child.id,
                    value:      percentage,
                    radius:     39,
                    width:      2,
                    maxValue:   10000,
                    text:       function(value){return "<em style='line-height:77px;font-weight:normal;color: #313131;font-size: 18px'>"+value+"</em>"+'<br/>';},
                    colors:     ['#C1C1C1', '#C81622']
                }));
                return false;
            } else {
                return false;
            }
        },
        error: function (XMLHttpRequest, msg, errorThrown) {
            return false;
        }
    });

    //图表数据加载
    $.ajax({
        url: get_chart_data_url,
        type: 'POST',
        cache: false,
        dataType: 'json',
        data: { media_uuid: media_uuid},
        success: function (resp) {
            if(resp.err_code == 0){
                //发稿量趋势
                var month_post_trend = resp.month_post_trend;
                var trend_data_horizontal = [];//横坐标数据
                var trend_data_vertical = [];//纵坐标数据
                for(i in month_post_trend){
                    trend_data_horizontal.push(i);
                    trend_data_vertical.push(month_post_trend[i]);
                }
                console.log(trend_data_horizontal);
                console.log(trend_data_vertical);
                var trend_max_data = Math.max.apply(null, trend_data_vertical)//发稿量的最大值
                trendDataMap(trend_data_horizontal,trend_data_vertical,trend_max_data,0);

                //发布时间日分布
                var month_post_time_hour_distribute = resp.month_post_time_hour_distribute;
                var month_hour_data_horizontal = [];
                var month_hour_data_vertical = [];
                for(i in month_post_time_hour_distribute){
                    month_hour_data_horizontal.push(i);
                    month_hour_data_vertical.push(month_post_time_hour_distribute[i]);
                }
                var month_hour_max_data = Math.max.apply(null, month_hour_data_vertical)//日发布的最大值
                pubDateMap(month_hour_data_horizontal,month_hour_data_vertical,month_hour_max_data,0);

                //发布时间周分布
                var month_post_time_week_distribute = resp.month_post_time_week_distribute;
                var month_week_data_horizontal = [];
                var month_week_data_vertical = [];
                for(i in month_post_time_week_distribute){
                    month_week_data_horizontal.push(i);
                    month_week_data_vertical.push(month_post_time_week_distribute[i]);
                }
                var month_week_max_data = Math.max.apply(null, month_week_data_vertical)//周发布的最大值
                pubDateWeekMap(month_week_data_horizontal,month_week_data_vertical,month_week_max_data,0);

                //头条阅读数分布
                var month_head_article_trend_view = resp.month_head_article_trend_view;
                var head_view_data_horizontal = [];
                var head_view_data_vertical = [];
                for(i in month_head_article_trend_view){
                    head_view_data_horizontal.push(i);
                    head_view_data_vertical.push(month_head_article_trend_view[i]);
                }
                var head_view_max_data = Math.max.apply(null, head_view_data_vertical)//阅读数的最大值
                readNumMap(head_view_data_horizontal,head_view_data_vertical,head_view_max_data,0);

                //头点赞数分布
                var month_head_article_trend_like = resp.month_head_article_trend_like;
                var head_like_data_horizontal = [];
                var head_like_data_vertical = [];
                for(i in month_head_article_trend_like){
                    head_like_data_horizontal.push(i);
                    head_like_data_vertical.push(month_head_article_trend_like[i]);
                }
                var head_like_max_data = Math.max.apply(null, head_like_data_vertical)//点赞数的最大值
                likeNumMap(head_like_data_horizontal,head_like_data_vertical,head_like_max_data,0);

                return false;
            } else {
                return false;
            }
        },
        error: function (XMLHttpRequest, msg, errorThrown) {
            return false;
        }
    });

    //文章数据加载
    $.ajax({
        url: get_article_data_url,
        type: 'POST',
        cache: false,
        dataType: 'json',
        data: { media_uuid: media_uuid},
        success: function (resp) {
            if(resp.err_code == 0){
                var mediaTopArticle = resp.mediaTopArticle;
                var mediaLastArticle = resp.mediaLastArticle;
                var top_article = "";
                for(i in mediaTopArticle){
                    top_article += '<div class="art-info">'+
                                        '<div class="art-head clearfix">'+
                                        '<a target="_blank" href="'+mediaTopArticle[i].article_url+'" class="title fl">'+cutString(mediaTopArticle[i].title,48)+'</a>'+
                                            '<span class="date-time fr">'+mediaTopArticle[i].post_time+'</span>'+
                                        '</div>'+
                                        '<p><a target="_blank" href="'+mediaTopArticle[i].article_url+'" title="'+mediaTopArticle[i].short_desc+'">'+cutString(mediaTopArticle[i].short_desc,180)+'</a></p>'+
                                        '<ul class="clearfix">'+
                                            '<li class="fl">'+mediaTopArticle[i].article_type+mediaTopArticle[i].article_pos+'</li>'+
                                            '<li class="fr">点赞数：<span>'+mediaTopArticle[i].like_num+'</span></li>'+
                                            '<li class="fr">阅读数：<span>'+mediaTopArticle[i].read_num+'</span></li>'+
                                        '</ul>'+
                                    '</div>';
                }
                $(".total-hot-art-top").append(top_article);
                var last_article = "";
                for(i in mediaLastArticle){
                    last_article += '<div class="art-info">'+
                                        '<div class="art-head clearfix">'+
                                        '<a target="_blank" href="'+mediaLastArticle[i].article_url+'" class="title fl">'+cutString(mediaLastArticle[i].title,48)+'</a>'+
                                            '<span class="date-time fr">'+mediaLastArticle[i].post_time+'</span>'+
                                        '</div>'+
                                        '<p><a target="_blank" href="'+mediaLastArticle[i].article_url+'" title="'+mediaLastArticle[i].short_desc+'">'+cutString(mediaLastArticle[i].short_desc,180)+'</a></p>'+
                                        '<ul class="clearfix">'+
                                            '<li class="fl">'+mediaLastArticle[i].article_type+mediaLastArticle[i].article_pos+'</li>'+
                                            '<li class="fr">点赞数：<span>'+mediaLastArticle[i].like_num+'</span></li>'+
                                            '<li class="fr">阅读数：<span>'+mediaLastArticle[i].read_num+'</span></li>'+
                                        '</ul>'+
                                    '</div>';
                }
                $(".total-hot-art-last").append(last_article);
                return false;
            } else {
                return false;
            }
        },
        error: function (XMLHttpRequest, msg, errorThrown) {
            return false;
        }
    });
    /*截取指定位置的字符串,多余的以...来显示*/
    function cutString(str,len) {
        if(str == null){
            return;
        }
        var strlen = 0;
        var s = "";
        for (var i = 0; i < str.length; i++) {
            if (str.charCodeAt(i) > 128) {
                strlen += 2;
            } else {
                strlen++;
            }
            if (strlen > len) {
                return s+"...";
            }
            s += str.charAt(i);
        }
        return s;
    }

    //图标插件
    //阅读数
    function readNumMap(horizontal_data,fill_data,max_data,min_data){
        var myChart = echarts.init(document.getElementById('read-num-map'));
        var option = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['阅读数']
            },
            toolbox: {
                show: true,
                feature: {
                    dataZoom: {
                        yAxisIndex: 'none'
                    },
                    dataView: {readOnly: false},
                    magicType: {type: ['line', 'bar']},
                    restore: {},
                    saveAsImage: {}
                }
            },
            xAxis:  {
                type: 'category',
                boundaryGap: false,
                data: horizontal_data
            },
            yAxis: {
                type: 'value',
                max:max_data,
                min:min_data,
                //interval:4,
                axisLabel: {
                    formatter: '{value}'
                }
            },
            series: [
                {
                    name:'阅读数',
                    type:'line',
                    data:fill_data,
                    markPoint: {
                        // data: [
                        //     {type: 'max', name: '最大值'},
                        //     {type: 'min', name: '最小值'}
                        // ]
                    },
                    markLine: {
                        // data: [
                        //     {type: 'average', name: '平均值'}
                        // ]
                    }
                }
            ]
        };
        myChart.setOption(option);
    };

    //发布时间日分布
    function pubDateMap(horizontal_data,fill_data,max_data,min_data){
        var myChart = echarts.init(document.getElementById('pub-date-map'));
        //app.title = '坐标轴刻度与标签对齐';
        var option = {
            color: ['#3398DB'],
            tooltip : {
                trigger: 'axis',
                axisPointer : {
                    type : 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    data : horizontal_data,
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    //data:[0,3,6,9],
                    max:max_data,
                    min:min_data,
                    //interval:3,
                    //splitNumber: 4,
                }
            ],
            series : [
                {
                    name:'分布',
                    type:'bar',
                    barWidth: '60%',
                    data:fill_data,
                    itemStyle:{
                        normal:{color:'#C81622'}
                    },
                    barCategoryGap:'30px'
                }
            ]
        };
        myChart.setOption(option);
    }
    //发稿量趋势
    function trendDataMap(horizontal_data,fill_data,max_data,min_data){
        var myChart = echarts.init(document.getElementById('trend-data-map'));
        var option = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['发稿量']
            },
            toolbox: {
                show: true,
                feature: {
                    dataZoom: {
                        yAxisIndex: 'none'
                    },
                    dataView: {readOnly: false},
                    magicType: {type: ['line', 'bar']},
                    restore: {},
                    saveAsImage: {}
                }
            },
            xAxis:  {
                type: 'category',
                boundaryGap: false,
                data:horizontal_data
            },
            yAxis: {
                type: 'value',
                //data:[0,3,6,9],
                max:max_data,
                min:min_data,
                //interval:1,
                //splitNumber: 1,
                axisLabel: {
                    formatter: '{value}'
                }
            },
            series: [
                {
                    name:'发稿量',
                    type:'line',
                    data:fill_data,
                    markPoint: {
                        // data: [
                        //     {type: 'max', name: '最大值'},
                        //     {type: 'min', name: '最小值'}
                        // ]
                    },
                    markLine: {
                        // data: [
                        //     {type: 'average', name: '平均值'}
                        // ]
                    }
                }
            ]
        };
        myChart.setOption(option);
    };

    //点赞数
    function likeNumMap(horizontal_data,fill_data,max_data,min_data){
        var myChart = echarts.init(document.getElementById('like-num-map'));
        var option = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['点赞数']
            },
            toolbox: {
                show: true,
                feature: {
                    dataZoom: {
                        yAxisIndex: 'none'
                    },
                    dataView: {readOnly: false},
                    magicType: {type: ['line', 'bar']},
                    restore: {},
                    saveAsImage: {}
                }
            },
            xAxis:  {
                type: 'category',
                boundaryGap: false,
                data: horizontal_data
            },
            yAxis: {
                type: 'value',
                //data:[0,3,6,9],
                max:max_data,
                min:min_data,
                //interval:3,
                //splitNumber: 4,
                axisLabel: {
                    formatter: '{value}'
                }
            },
            series: [
                {
                    name:'点赞数',
                    type:'line',
                    data:fill_data,
                    markPoint: {
                        // data: [
                        //     {type: 'max', name: '最大值'},
                        //     {type: 'min', name: '最小值'}
                        // ]
                    },
                    markLine: {
                        // data: [
                        //     {type: 'average', name: '平均值',data:10}
                        // ]
                    }
                }
            ]
        };
        myChart.setOption(option);
    };
    //发布时间周发布
    function pubDateWeekMap(horizontal_data,fill_data,max_data,min_data){
        var myChart = echarts.init(document.getElementById('pub-date-week-map'));
        //app.title = '坐标轴刻度与标签对齐';

        var option = {
            color: ['#3398DB'],
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    //data : ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
                    data : horizontal_data,
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    //data:[0,300,600,900],
                    max:max_data,
                    min:min_data,
                    //interval:300,
                    //splitNumber: 4,
                }
            ],
            series : [
                {
                    name:'分布',
                    type:'bar',
                    barWidth: '60%',
                    data:fill_data,
                    itemStyle:{
                        normal:{color:'#C81622'}
                    },
                    barCategoryGap:'30px'
                }
            ]
        };
        myChart.setOption(option);
    }
})





