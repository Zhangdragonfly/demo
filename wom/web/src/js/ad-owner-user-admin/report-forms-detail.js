$(function(){
    //echarts实现图表功能

    //阅读数
    readNumMap();
    function readNumMap(){
        var myChart = echarts.init(document.getElementById('read-num-chart'));
        var time = ['08-11 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-12 22:09:55'];
        var value_y = [4, 7, 5, 6, 4, 3, 3, 4, 7, 9];
        var option = {
            tooltip: {
                trigger: "axis",
                show: true
            },
            legend: {
                data: ["阅读数"],
                selectedMode: "multiple",
                x: "center",
                y: "top"
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
            dataZoom: [{
                type: 'inside',
                start: 0,
                end: 10
            }, {
                start: 0,
                end: 10,
                handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
                handleSize: '80%',
                handleStyle: {
                    color: '#fff',
                    shadowBlur: 3,
                    shadowColor: 'rgba(0, 0, 0, 0.6)',
                    shadowOffsetX: 2,
                    shadowOffsetY: 2
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
                width: 1050,
                height: 280,
                borderColor: "rgb(255, 255, 255)"
            }
        };
        myChart.setOption(option);
    };

    //点赞数
    agreeNumMap();
    function agreeNumMap(){
        var myChart = echarts.init(document.getElementById('agree-num-chart'));
        var time = ['08-11 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-10 22:09:55','08-12 22:09:55'];
        var value_y = [4, 7, 5, 6, 4, 3, 3, 4, 7, 9];
        var option = {
            tooltip: {
                trigger: "axis",
                show: true
            },
            legend: {
                data: ["点赞数"],
                selectedMode: "multiple",
                x: "center",
                y: "top"
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
            dataZoom: [{
                type: 'inside',
                start: 0,
                end: 10
            }, {
                start: 0,
                end: 10,
                handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
                handleSize: '80%',
                handleStyle: {
                    color: '#fff',
                    shadowBlur: 3,
                    shadowColor: 'rgba(0, 0, 0, 0.6)',
                    shadowOffsetX: 2,
                    shadowOffsetY: 2
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
                name: "点赞数",
                data: value_y,
                symbolSize: 1
            }],
            grid: {
                width: 1050,
                height: 280,
                borderColor: "rgb(255, 255, 255)"
            }
        };
        myChart.setOption(option);
    };

    //文章关键词词云图
    keyWordsChart();
    function keyWordsChart(){
        var word_cloud = echarts.init(document.getElementById('key-words'));
        var data = [{
            name: '词云',
            value: 10000,
            textStyle: {
                normal: {
                    color: '#c81624'
                },
                emphasis: {
                    color: 'red'
                }
            }
        }, {
            name: 'Macys',
            value: 6181
        }, {
            name: 'Amy Schumer',
            value: 4386
        }, {
            name: 'Jurassic World',
            value: 4055
        }, {
            name: 'Charter Communications',
            value: 2467
        }, {
            name: 'Chick Fil A',
            value: 2244
        }, {
            name: 'Planet Fitness',
            value: 1898
        }, {
            name: 'Pitch Perfect',
            value: 1484
        }, {
            name: 'Express',
            value: 1112
        }, {
            name: 'Home',
            value: 965
        }, {
            name: 'Johnny Depp',
            value: 847
        }, {
            name: 'Lena Dunham',
            value: 582
        }, {
            name: 'Lewis Hamilton',
            value: 555
        }, {
            name: 'KXAN',
            value: 550
        }, {
            name: 'Mary Ellen Mark',
            value: 462
        }, {
            name: 'Farrah Abraham',
            value: 366
        }, {
            name: 'Rita Ora',
            value: 360
        }, {
            name: 'Serena Williams',
            value: 282
        }, {
            name: 'NCAA baseball tournament',
            value: 273
        }, {
            name: 'Point Break',
            value: 265
        }]
        var word_cloud_options = {
            series: [{
                type: 'wordCloud',
                gridSize: 20,
                sizeRange: [12, 50],
                rotationRange: [0, 0],
                shape: 'circle',
                textStyle: {
                    normal: {
                        color: function() {
                            return 'rgb(' + [
                                    Math.round(Math.random() * 160),
                                    Math.round(Math.random() * 160),
                                    Math.round(Math.random() * 160)
                                ].join(',') + ')';
                        }
                    },
                    emphasis: {
                        shadowBlur: 10,
                        shadowColor: '#333'
                    }
                },
                data: data
            }]
        }
        word_cloud.setOption(word_cloud_options);
    }


    //关键词排名
    keyWordsRankMap();
    function keyWordsRankMap(){
        var myChart = echarts.init(document.getElementById('key-words-rank'));
        var data=[17, 20, 45, 56, 60];
        var xMax=100;
        var option = {
            tooltip:{
                show:true,
                formatter:"{b} {c}"
            },
            grid:{
                left:'10%',
                top:'5%',
                bottom:'0',
                right:'0'
            },
            xAxis : [
                {
                    max:xMax,
                    type : 'value',
                    axisTick: {
                        show: false,
                    },
                    axisLine: {
                        show:false,
                    },
                    axisLabel: {
                        show:false
                    },
                    splitLine: {
                        show: false
                    }
                }
            ],
            yAxis : [
                {
                    type : 'category',
                    data : ['所有文章','全部','80个词','显示','词云','Kenzo'],
                    nameTextStyle:{
                        color:'#b7ce9e',
                        fontSize:'18px'
                    },
                    axisTick: {
                        show: false,
                    },
                    axisLine: {
                        show: false,
                    }
                }
            ],
            series : [
                {
                    name:' ',
                    type:'bar',
                    barWidth:30,
                    label: {
                        normal: {
                            show: true,
                            position: 'right',
                            formatter: '{c}%',
                        }
                    },
                    itemStyle: {
                        normal: {color: '#c81624'}
                    },
                    data:[
                        {
                            value:17,

                        },{
                            value:20,

                        },{
                            value:45,

                        },{
                            value:56,

                        },{
                            value:60,

                        }
                    ],
                }
            ]
        };
        myChart.setOption(option);
    };





})