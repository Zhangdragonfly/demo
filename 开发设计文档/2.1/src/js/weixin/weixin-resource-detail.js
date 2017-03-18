$(function(){
    /**
     ** 限制文本长度,超出部分以省略号的形式展示.
     * 使用方法: 给存放文本的标签添加class类名 class = "plain-text-length-limit" ,设置属性 data-limit = "num" , num为想要限制的字数,根据自己需要设置.
     **/
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
    })();
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
//echarts和沃米指数圆环插件引入
$(function(){
    //沃米指数
    function shuffle(o){ //v1.0
        for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
        return o;
    }
    var circles = [];
    var child = document.getElementById('sum-data-type'),
        percentage = 0.86;
    circles.push(Circles.create({
        id:         child.id,
        value:      percentage,
        radius:     39,
        width:      2,
        maxValue:   1,
        text:       function(value){return "<em style='line-height:77px;font-weight:normal;color: #313131;font-size: 18px'>"+value+"</em>"+'<br/>';},
        colors:     ['#C1C1C1', '#C81622']
    }));
    //阅读数
    readNumMap();
    function readNumMap(){
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
                data: ['10-12','10-16','10-20','10-24','10-28','11-1','11-5','11-9','11-13','11-17']
            },
            yAxis: {
                type: 'value',
                data:[0,3,6,9],
                max:9,
                min:0,
                interval:3,
                splitNumber: 4,
                axisLabel: {
                    formatter: '{value}'
                }
            },
            series: [
                {
                    name:'阅读数',
                    type:'line',
                    data:[4, 7, 5, 6, 4, 3, 3, 4, 7, 7],
                    markPoint: {
                        data: [
                            {type: 'max', name: '最大值'},
                            {type: 'min', name: '最小值'}
                        ]
                    },
                    markLine: {
                        data: [
                            {type: 'average', name: '平均值'}
                        ]
                    }
                }
            ]
        };
        myChart.setOption(option);
    };
    //发布时间日发布
    pubDateMap();
    function pubDateMap(){
        var myChart = echarts.init(document.getElementById('pub-date-map'));
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
                    data : ['0时', '3时', '6时', '9时', '12时', '15时', '18时', '21时', '24时'],
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    data:[0,3,6,9],
                    max:9,
                    min:0,
                    interval:3,
                    splitNumber: 4,
                }
            ],
            series : [
                {
                    name:'分布',
                    type:'bar',
                    barWidth: '60%',
                    data:[3, 2, 4, 3, 5, 6, 7,6,8],
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
    trendDataMap();
    function trendDataMap(){
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
                data: ['10-12','10-16','10-20','10-24','10-28','11-1','11-5','11-9','11-13','11-17']
            },
            yAxis: {
                type: 'value',
                data:[0,3,6,9],
                max:9,
                min:0,
                interval:3,
                splitNumber: 4,
                axisLabel: {
                    formatter: '{value}'
                }
            },
            series: [
                {
                    name:'发稿量',
                    type:'line',
                    data:[4, 7, 5, 6, 4, 3, 3, 4, 7, 7],
                    markPoint: {
                        data: [
                            {type: 'max', name: '最大值'},
                            {type: 'min', name: '最小值'}
                        ]
                    },
                    markLine: {
                        data: [
                            {type: 'average', name: '平均值'}
                        ]
                    }
                }
            ]
        };
        myChart.setOption(option);
    };
    //点赞数
    likeNumMap();
    function likeNumMap(){
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
                data: ['10-12','10-16','10-20','10-24','10-28','11-1','11-5','11-9','11-13','11-17']
            },
            yAxis: {
                type: 'value',
                data:[0,3,6,9],
                max:9,
                min:0,
                interval:3,
                splitNumber: 4,
                axisLabel: {
                    formatter: '{value}'
                }
            },
            series: [
                {
                    name:'点赞数',
                    type:'line',
                    data:[4, 7, 5, 6, 4, 3, 3, 4, 7, 7],
                    markPoint: {
                        data: [
                            {type: 'max', name: '最大值'},
                            {type: 'min', name: '最小值'}
                        ]
                    },
                    markLine: {
                        data: [
                            {type: 'average', name: '平均值'}
                        ]
                    }
                }
            ]
        };
        myChart.setOption(option);
    };
    //发布时间周发布
    pubDateWeekMap();
    function pubDateWeekMap(){
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
                    data : ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    data:[0,300,600,900],
                    max:900,
                    min:0,
                    interval:300,
                    splitNumber: 4,
                }
            ],
            series : [
                {
                    name:'分布',
                    type:'bar',
                    barWidth: '60%',
                    data:[10, 52, 200, 334, 390, 330, 220],
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





