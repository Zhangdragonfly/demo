<?php
/**
 * 创建原创约稿内容
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 11/28/16/ 20:46
 */
use wom\assets\AppAsset;
use yii\helpers\Url;
use common\helpers\ExternalFileHelper;

AppAsset::register($this);

//css文件引用顺序, 依次是：bootstarp 、第三方插件、reset.css、页面的css
AppAsset::addCss($this, '@web/dep/layer/skin/layer.css');
AppAsset::addCss($this, '@web/dep/datetimepicker/jquery.datetimepicker.css');
AppAsset::addCss($this, '@web/dep/ueditor/ueditor.css');
AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/site-stage-layout-common.css');
AppAsset::addCss($this, '@web/src/css/weixin/plan_order_direct_content.css');
AppAsset::addCss($this, '@web/src/css/plan_media_common.css');

AppAsset::addScript($this, '@web/dep/layer/layer.js');
AppAsset::addScript($this, '@web/dep/js/wom-tool.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/jquery.datetimepicker.js');
AppAsset::addScript($this, '@web/dep/datetimepicker/datetime.js');
AppAsset::addScript($this, '@web/dep/ueditor/ueditor.config.js');
AppAsset::addScript($this, '@web/dep/ueditor/ueditor.all.min.js');
AppAsset::addScript($this, '@web/dep/plupload/plupload.full.min.js');
AppAsset::addScript($this, '@web/dep/js/wom-uploader.js');
AppAsset::addScript($this, '@web/src/js/weixin/plan_order_direct_content.js');
$this->title = '直接投放添加内容';
?>

<!--投放内容-->
<input id="id-edit-direct-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/edit-direct-content']) ?>">
<input id="id-weixin-plan-confirm-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/confirm', 'plan_uuid' => $weixinPlan->uuid]) ?>">
<input id="id-weixin-plan-update-url" type="hidden" value="<?= Url::to(['/ad-owner/admin-weixin-plan/update', 'plan_uuid' => $weixinPlan->uuid]) ?>">
<input id="id-weixin-plan-pay-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-plan/pay-confirm', 'plan_uuid' => $weixinPlan->uuid, 'order_uuid' => '_order_uuid_']) ?>">
<input id="id-order-uuid" type="hidden" value="<?= $directContent->order_uuid ?>">
<input id="id-pos-code" type="hidden" value="<?= $directContent->position_code ?>">
<input id="id-weixin-plan-action-type" type="hidden" value="<?= Yii::$app->request->get('plan_action') ?>">
<input id="id-import-content-url" type="hidden" value="<?= Url::to(['/ad-owner/weixin-order/import-content']) ?>">

<!-- csrf -->
<input type="hidden" id="csrf" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken(); ?>"/>
<!-- 删除图片 -->
<input type="hidden" id="id-delete-file-url" value="<?= Url::to(['/site/file-uploader/delete-file', 'cate_code' => 'order']) ?>">
<!-- 上传图片 -->
<input type="hidden" id="id-upload-file-url" value="<?= Url::to(['/site/file-uploader/upload', 'cate_code' => 'order']) ?>">


<!--内容-->
<div class="edit-weixin-ad-content">
    <div class="content clearfix">
        <div class="content-left">
            <div class="phone-clone">
                <h2 class="file-title">标题</h2>
                <div class="time-and-author">
                    <span class="execute-time">执行时间</span>
                    <span class="author-clone">作者</span>
                </div>
                <div class="article-content-clone"></div>
            </div>
        </div>
        <button class="material-select btn btn-danger bg-main fr" data-target="#select-from-material-lib"
                data-toggle="modal"><i></i>从素材库选择
        </button>

        <div class="content-right">
            <div class="file-content weixin-file-content">
                <div class="plan-name">
                    <span class="file-content-title">活动名称：</span>
                    <span class="text-plan-name"><?= $weixinPlan['name'] ?></span>
                </div>
                <div class="throw-time">
                    <span class="file-content-title"><i>*</i>执行时间：</span>
                    <input type="text" class="startime input-section text-input datetimepicker input-publish-time"
                           name="iStarTime" value="<?= $directContent->publish_start_time ?>" readonly="readonly"/> <em
                        class="tips">选择当前时间2小时后,7天内</em>
                    <p class="throw-time-tips color-main">建议您至少提前一天推广，否则流单/拒单率高！</p>
                    <i class="calendar-icon"></i>
                </div>
                <div class="article-address">
                    <div>
                        <span class="file-content-title">文章导入：</span>
                        <input type="text" class="input-article-url" placeholder="可输入微信文章链接或微信后台预览链接"
                               value="<?= $directContent->original_mp_url ?>">
                        <button class="import btn btn-danger bg-main">导入</button>
                    </div>
                </div>
                <div class="file-name">
                    <span class="title file-content-title"><i>*</i>标题：</span>
                    <input class="input-title" maxlength="50" type="text" placeholder="请勿超过50个字,勿包含转发、分享等文字,以免被微信屏蔽造成损失"
                           value="<?= $directContent->title ?>">
                    <span class="font-12 tips">您还可以输入<em class="color-main">50</em>字</span>
                </div>
                <div class="author-name">
                    <span class="file-content-title">作者：</span>
                    <input class="input-author" maxlength="8" type="text" placeholder="请输入作者(可选)"
                           value="<?= $directContent->author ?>">
                    <span class="font-12 tips">您还可以输入<em class="color-main">8</em>字</span>
                </div>
                <div class="cover-pic">
                    <span class="file-content-title"><i>*</i>封面图片：</span>
                    <button id="id-upload-001-btn" class="btn btn-danger bg-main" for="id-upload-001-preview-area">
                        上传图片
                    </button>
                    <span class="tips"> 请选择1张封面图片,不要超过2M</span>
                    <div id="id-upload-001-preview-area" class="upload-preview-area">
                        <?php if (!empty($directContent->cover_img)) { ?>
                            <ul class="file-list">
                                <li id="delete">
                                    <div class="progress" style="display: none;">
                                        <span class="bar" style="width: 100%;"></span>
                                        <span class="percent">上传中100%</span>
                                    </div>
                                    <a data-img-name="<?= $directContent->cover_img ?>" class="delete-pic" href="javascript:;"><i></i></a>
                                    <img src="<?= ExternalFileHelper::getPlanOrderRelativeDirectory() . $directContent->cover_img; ?>">
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
                <div class="cover-pic-show font-600">
                    <label>
                        <?php if ($directContent->cover_in_body == 1) {
                            $cover_in_body = 1;
                            $checked = "checked";
                        } else {
                            $cover_in_body = 0;
                            $checked = "";

                        } ?>
                        <input class="input-cover-in-body" name="input-cover-in-body" value="<?= $cover_in_body ?>"
                               type="checkbox" <?= $checked ?>/>封面图片显示在正文中
                    </label>
                </div>
                <div class="article-content clearfix">
                    <span class="file-content-title fl"><i>*</i>正文内容：</span>
                    <form action="#" method="post">
                        <!-- 加载编辑器的容器 -->
                        <script id="container" name="content"
                                type="text/plain"><?= $directContent->article_content ?></script>
                    </form>
                </div>
                <div class="article-link">
                    <div class="file-content-title">原文链接：</div>
                    <input type="text" class="input-link-url" placeholder="微信文章阅读原文的链接地址"
                           value="<?= $directContent->link_url ?>">
                </div>
                <div class="summary">
                    <span class="file-content-title fl">摘要：</span>
                    <textarea name="" id="abstract" maxlength="120" class="input-article-short-desc" cols="30" rows="10"
                              placeholder="选填, 如果不填写会默认抓取正文前54个字"><?= $directContent->article_short_desc ?></textarea>
                    <p class="tips message">您还可以输入<em>120</em>字</p>
                </div>
                <div class="quality-proof">
                    <span class="file-content-title">正品证明：</span>

                    <button id="id-upload-002-btn" class="btn btn-danger bg-main" href="javascript:;"
                            for="id-upload-002-preview-area">上传图片
                    </button>
                    <span class="tips font-12">仅支持jpg、gif、png图片文件，文件小于2M,允许上传5张</span>

                    <div id="id-upload-002-preview-area" class="upload-preview-area">
                        <?php if (!empty($directContent->cert_img_urls)) { ?>
                            <ul class="file-list">
                                <?php
                                $urls = explode(",", $directContent->cert_img_urls);
                                foreach (array_filter($urls) as $key => $img) {
                                    ?>

                                    <li id="delete">
                                        <div class="progress" style="display: none;">
                                            <span class="bar" style="width: 100%;"></span>
                                            <span class="percent">已上传100%</span>
                                        </div>
                                        <a data-img-name="<?= $img; ?>" class="delete-pic" href="javascript:;">
                                            <i></i>
                                        </a>
                                        <img src="<?= ExternalFileHelper::getPlanOrderRelativeDirectory() . $img; ?>">
                                    </li>

                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </div>
                    <p class="tips">若您推广的单品属于知名品牌,请务必上传该商品的正品证明(如产品许可证、营业执照截图等)</p>
                </div>
                <div class="plan-comment">
                    <span class="file-content-title fl">投放备注：</span>
                    <textarea class="input-comment" maxlength="120" name="" id="" cols="30" rows="10"
                              placeholder="选填,如果不填写会默认抓取正文前54个字"><?= $directContent->comment ?></textarea>
                    <p class="tips message">您还可以输入<em>120</em>字</p>
                </div>
                <div class="agree font-12 clearfix">
                    <input type="checkbox" value="1" name="is_ht_ok" class="check is_ht" checked="checked"
                           disabled="disabled">
                    <strong>我同意<a target="_blank" href="#" data-toggle="modal" data-target="#agreement-ad-server-rule">《广告主投放须知》</a></strong>
                </div>

                <?php if (empty(Yii::$app->request->get('pay'))) { ?>
                    <button class="btn btn-danger bg-main save" data-pay="0">保存</button>
                <?php } else { ?>
                    <button class="btn btn-danger bg-main save" data-pay="1">提交并支付</button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- 从素材库选择modal框 -->
<div id="select-from-material-lib" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>选择内容素材</h4>
                <div class="search-and-creat">
                    <input type="text" placeholder="标题/作者">
                    <button class="search-btn btn btn-danger bg-main font-16"><i></i>搜索</button>
                    <button class="create-material btn btn-danger bg-main font-16" data-toggle="modal"><i></i>新建素材
                    </button>
                </div>
                <!--<span class="close fr" data-dismiss="modal">x</span>-->
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                    <tr>
                        <th>标题</th>
                        <th></th>
                        <th></th>
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody class="material-list">
                    <tr>
                        <td width="90px">
                            <div class="material-pic"><img src="../src/images/demo.jpg" alt=""></div>
                        </td>
                        <td width="224px">
                            联合利华大结局爱丽丝阿里斯顿
                        </td>
                        <td class="material-choosed-td">
                            <div class="material-choosed">
                                <i class="fl"></i> 已选择
                            </div>
                        </td>
                        <td width="120px">2016.5.13</td>
                    </tr>
                    <tr>
                        <td width="90px">
                            <div class="material-pic"><img src="../src/images/demo.jpg" alt=""></div>
                        </td>
                        <td width="224px">
                            联合利华大结局爱丽丝阿里斯顿
                        </td>
                        <td class="material-choosed-td">
                            <div class="material-choosed">
                                <i class="fl"></i> 已选择
                            </div>
                        </td>
                        <td width="120px">2016.5.13</td>
                    </tr>
                    <tr>
                        <td width="90px">
                            <div class="material-pic"><img src="../src/images/demo.jpg" alt=""></div>
                        </td>
                        <td width="224px">
                            联合利华大结局爱丽丝阿里斯顿
                        </td>
                        <td class="material-choosed-td">
                            <div class="material-choosed">
                                <i class="fl"></i> 已选择
                            </div>
                        </td>
                        <td width="120px">2016.5.13</td>
                    </tr>
                    <tr>
                        <td width="90px">
                            <div class="material-pic"><img src="../src/images/demo.jpg" alt=""></div>
                        </td>
                        <td width="224px">
                            联合利华大结局爱丽丝阿里斯顿
                        </td>
                        <td class="material-choosed-td">
                            <div class="material-choosed">
                                <i class="fl"></i> 已选择
                            </div>
                        </td>
                        <td width="120px">2016.5.13</td>
                    </tr>
                    <tr>
                        <td width="90px">
                            <div class="material-pic"><img src="../src/images/demo.jpg" alt=""></div>
                        </td>
                        <td width="224px">
                            联合利华大结局爱丽丝阿里斯顿
                        </td>
                        <td class="material-choosed-td">
                            <div class="material-choosed">
                                <i class="fl"></i> 已选择
                            </div>
                        </td>
                        <td width="120px">2016.5.13</td>
                    </tr>
                    <tr>
                        <td width="90px">
                            <div class="material-pic"><img src="../src/images/demo.jpg" alt=""></div>
                        </td>
                        <td width="224px">
                            联合利华大结局爱丽丝阿里斯顿
                        </td>
                        <td class="material-choosed-td">
                            <div class="material-choosed">
                                <i class="fl"></i> 已选择
                            </div>
                        </td>
                        <td width="120px">2016.5.13</td>
                    </tr>
                    <tr>
                        <td width="90px">
                            <div class="material-pic"><img src="../src/images/demo.jpg" alt=""></div>
                        </td>
                        <td width="224px">
                            联合利华大结局爱丽丝阿里斯顿
                        </td>
                        <td class="material-choosed-td">
                            <div class="material-choosed">
                                <i class="fl"></i> 已选择
                            </div>
                        </td>
                        <td width="120px">2016.5.13</td>
                    </tr>
                    <tr>
                        <td width="90px">
                            <div class="material-pic"><img src="../src/images/demo.jpg" alt=""></div>
                        </td>
                        <td width="224px">
                            联合利华大结局爱丽丝阿里斯顿
                        </td>
                        <td class="material-choosed-td">
                            <div class="material-choosed">
                                <i class="fl"></i> 已选择
                            </div>
                        </td>
                        <td width="120px">2016.5.13</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger bg-main font-16" data-dismiss="modal">确定</button>
                <button class="btn btn-danger bg-main font-16" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!--广告主 modal层-->
<div id="agreement-ad-server-rule" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">广告主投放须知</span><i class="close fr" data-dismiss="modal">X</i></div>
            <div class="modal-body">
                <br>
                在51wom投放之前，请您认真阅读如下规则，您的后续行为将视为您已知晓并遵守下述规则：<br><br>
                1、请确保您的文案符合《广告法》和平台相关规定，不会对他人形成侵权，由此产生的所有责任由您负担。51wom在进行订单审核时，会将违反国家法律法规规定及平台规定的订单驳回，您可以修改后重新提交。<br><br>
                2、请尽量提供明确完整的投放文案素材，并承诺尽量不在后期沟通时要求更改。反复的修改可能会造成自媒体主不配合执行，由此产生的任何后果，51wom不承担任何责任。<br><br>
                3、您下发订单后，是否接单将取决于自媒体资深对于内容的判断和排期，完全由自媒体自主决定，我们会有媒介跟进联系，促成媒体主接单，51wom不做任何肯定接单的承诺，您的询价和下单的行为均视作要约，请慎重执行，如果无故违约，将有可能受到降低评级、关闭账号等处罚。<br><br>
                4、请及时查看并作出选择，并及时进行订单确认、反馈等处理，如因处理不及时导致流单，如果媒体主已经发布，则此订单有效，51wom不承担任何责任。<br><br>
                5、由于51wom自媒体主可以随时修改价格和基本信息，所以请密切关注价格，如因自媒体主价格调整造成您的订单无法执行或利益损失，51wom不承担任何责任。<br><br>
                6、51wom不对推广效果做出任何承诺，或任何提示，请您在投放前请做出正确预估，如因效果KPI等原因造成的任何纠纷，51wom不承担任何责任，亦不退费。<br><br>
                7、51wom将为您的投放提供正规服务费发票，税费由您承担，是您与媒体主约定投放净价的8%。<br><br>
                8、如果您有垫付款项、策划执行、整案服务等超出自助交易以外的需求，请直接联系我们的客服热线：400-878-9551。
            </div>
            <div class="modal-footer clearfix">
                <button class="btn-agree" data-dismiss="modal">同意并关闭</button>
            </div>
        </div>
    </div>
</div>

