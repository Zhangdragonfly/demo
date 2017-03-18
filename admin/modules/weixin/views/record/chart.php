<?php
use admin\assets\AppAsset;

$getChartInfo = Yii::$app->urlManager->createUrl(array('weixin/record/chart-info'));
$weixinToVerifyJs = <<<JS
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('price-trend'));
    var time_x = [];
    var orig_price_s = [];
    var retail_price_s = [];
    
    //数据导入
    $.ajax({
        url: '$getChartInfo',
        type: 'POST',
        cache: false,
        dataType: 'json',
      
        success: function (resp) {
            if(resp.err_code == 1){
                return false;
            }else{
                console.log(resp);
                for(var name in resp.time_x){
                    time_x.push(resp.time_x[name]);
                    orig_price_s.push(resp.orig_price_s[name]);
                    retail_price_s.push(resp.retail_price_s[name]);
                }
                // 使用刚指定的配置项和数据显示图表。
                myChart.setOption(option);
            }
        },
        error: function (XMLHttpRequest, msg, errorThrown) {
            alert('error');
            return false;
        }
    });
    
    // 指定图表的配置项和数据
    var option = {
    title: {
        text: '增长折线图(最近7天)'
    },
    tooltip: {
        trigger: 'axis'
    },
    legend: {
        data:['平台合作价 单图文','零售价 单图文']
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    toolbox: {
        feature: {
            saveAsImage: {}
        }
    },
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: time_x
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name:'平台合作价 单图文',
            type:'line',
            stack: '总量',
            data:orig_price_s
        },
        {
            name:'零售价 单图文',
            type:'line',
            stack: '总量',
            data:retail_price_s
        }
    ]
};

JS;

$this->registerJs($weixinToVerifyJs);

AppAsset::addScript($this, '@web/js/echarts.common.min.js');
?>


<div id="content" class="content">

    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">投放管理</a></li>
        <li class="active">计划列表</li>
    </ol>
    <h1 class="page-header">价格趋势</h1>
    <h1 class="page-header"><?php $sql?></h1>
    <div class="row">
        <div class="col-md-12 main-stage">
            <div class="panel panel-inverse" data-sortable-id="index-1">
                <div class="panel-body">
                    <div id="price-trend" style="min-height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
