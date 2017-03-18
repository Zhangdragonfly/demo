<?php
/**
 * 投放基本信息
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:37 PM
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/plan_media_common.css');
AppAsset::addCss($this, '@web/src/css/weixin/plan_create.css');

AppAsset::addScript($this, '@web/dep/js/js.cookie.js');
AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/src/js/site-stage-layout-common.js');

$this->title = '创建活动';
$js = <<<JS

            //剩余输入字数判断
            $('.active-content .plan-name').keyup(function(){
                countNum(this, 50);
            });
            $('.active-content .plan-desc').keyup(function(){
                countNum(this, 1000);
            });
            function countNum(_this,num){
                var _value_len = $(_this).val().length;
                var _count = Number(_value_len);
                var _all_num_last = Number(num-_count);

                $(_this).next().find('em').text(_all_num_last);
            }
            // 点击"下一步"
            $('.active-content .btn-submit').click(function(){
                var plan_name = $.trim($('.active-content .plan-name').val());
                var plan_desc = $.trim($('.active-content .plan-desc').val());

                if(plan_name == ''){
                    wom_alert.msg({
                        icon: "error",
                        content: "请填写活动名称!",
                        delay_time: 1500
                    });
                    return false;
                };
                if(plan_desc == ''){
                    wom_alert.msg({
                        icon: "error",
                        content: "请填写需求概述!",
                        delay_time: 1500
                    });
                    return false;
                }

                var weixin_plan_create_url = $('#id-weixin-plan-create-url').val();
                var request_route = $('#id-request-route').val();
                if(request_route == 1){
                    // 直接点击"新建活动", 跳转到微信资源搜索列表页
                    $.ajax({
                        url: weixin_plan_create_url,
                        type: 'post',
                        cache: false,
                        dataType: 'json',
                        data: {plan_name: plan_name, plan_desc: plan_desc},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                var plan_uuid = resp.plan_uuid; // 生成的plan uuid
                                var weixinMediaListUrl = $('#id-weixin-media-list-url').val();
                                window.location.href = weixinMediaListUrl.replace('_plan_uuid_', plan_uuid);
                            } else if (resp.err_code == 1){
                                wom_alert.msg({
                                    icon: "error",
                                    content: resp.err_msg,
                                    delay_time: 1500
                                });
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            wom_alert.msg({
                                icon: "error",
                                content: "系统异常!",
                                delay_time: 1500
                            });
                        }
                    });
                } else if(request_route == 2) {
                    // 跳转到第3步 填写投放内容
                    // 来源1 : 在微信搜索列表页里选择资源, 在购物车里点击"立即投放"
                    // 来源2 : 在微信媒体库里选择资源,点击"投放"

                    // 从cookie获取购物车中的微信资源
                    var weixin_media_selected_to_put_in = Cookies.get('weixin-media-selected-to-put-in');
                    // console.log(weixin_media_selected_to_put_in);

                    $.ajax({
                        url: weixin_plan_create_url,
                        type: 'post',
                        cache: false,
                        dataType: 'json',
                        data: {plan_name: plan_name, plan_desc: plan_desc, weixin_media_selected_to_put_in: weixin_media_selected_to_put_in},
                        success: function (resp) {
                            if(resp.err_code == 0){
                                var plan_uuid = resp.plan_uuid; // 生成的plan uuid
                                var weixin_plan_confirm_url = $('#id-weixin-plan-step-3-url').val();
                                weixin_plan_confirm_url = weixin_plan_confirm_url.replace('_plan_uuid_', plan_uuid);

                                // 清除微信媒体资源选中的cookie
                                Cookies.remove('weixin-media-selected-to-put-in');

                                window.location.href = weixin_plan_confirm_url;
                            } else if (resp.err_code == 1){
                                wom_alert.msg({
                                    icon: "error",
                                    content: resp.err_msg,
                                    delay_time: 1500
                                });
                            }
                        },
                        error: function (XMLHttpRequest, msg, errorThrown) {
                            wom_alert.msg({
                                icon: "error",
                                content: "系统异常!",
                                delay_time: 1500
                            });
                        }
                    });
                }
            });
JS;
$this->registerJs($js);
?>

<!-- hidden input value -->
<input id="id-weixin-media-list-url" type="hidden" value="<?= Url::to(['/weixin/media/list', 'plan_uuid' => '_plan_uuid_']) ?>">
<input id="id-weixin-plan-step-3-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/confirm', 'plan_uuid' => '_plan_uuid_']) ?>">
<input id="id-weixin-plan-create-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/create']) ?>">
<input id="id-request-route" type="hidden" value="<?= $route ?>">

<!-- 内容部分-->
<div class="create-step">
    <ul class="clearfix">
        <li class="current-step">1.创建活动<i></i></li>
        <li>2.选择投放账号</li>
        <li>3.填写投放内容</li>
        <li class="last-step">4.提交并付款<i></i></li>
    </ul>
</div>

<div class="active-content">
    <div class="active-name">
        <span><i>*&nbsp;</i>活动名称：</span>
        <input type="text" class="form-control plan-name" placeholder="请输入活动名称" maxlength="50">
        <span class="count-num">您还可以输入<em>50</em>字</span>
    </div>
    <div class="content-sum">
        <span><i>*&nbsp;</i>需求概述：</span>
        <textarea name="" class="form-control plan-desc" placeholder="请介绍下您本次推广需求，让媒体主了解本次活动大概的效果" cols="" rows="" maxlength="1000"></textarea>
        <span class="count-num">您还可以输入<em>1000</em>字</span>
    </div>
    <button class="btn btn-danger btn-submit">下一步</button>
</div>
