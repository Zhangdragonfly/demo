<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/28/16 4:22 PM
 */
use wom\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::addCss($this, '@web/src/css/reset.css');
AppAsset::addCss($this, '@web/src/css/home-page/index.css');

AppAsset::addScript($this, '@web/dep/js/echarts.min.js');
AppAsset::addScript($this, '@web/src/js/home-page/index.js');
$this->title = '首页';
?>
<!--视频网红资源-->
<div class="resource-wrap">
    <div class="video-resource resource">
        <!--资源头部-->
        <div class="resource-title clearfix">
            <span class="resource-icon fl"></span>
            <span class="resource-name fl">视频网红资源</span>
            <a class="fr color-main" href="<?= Url::to(['/video/media/list'])?>" target="_blank">更多</a>
        </div>
        <!--资源导航-->
        <div class="video-nav resource-nav">
            <ul class="clearfix">
                <li class="active">
                    <div class="platform">斗鱼</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">一直播</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">美拍</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">秒拍</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">花椒</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li class="no-border">
                    <div class="platform">映客</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <ul class="bottom-line clearfix">
                <li class="line-active"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div class="video-data" style="display: none"><?= $videoJson ?></div>
        <!--斗鱼TV-->
        <div class="douyuTv resource-con show">
            <ul class="douyu-ul clearfix">
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#"></a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#">小苍MM卡死阿萨德你那,是你那,你</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#">小苍MM卡死阿萨德你那,是你那,你</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li class="no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#"
                                   data-title="小苍MM卡死阿萨德你那,是你那,你">小苍MM卡死阿萨德你那,是你那,你</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--一直播-->
        <div class="yizhibo resource-con">
            <ul class="yzb-ul clearfix">
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#">小苍MM卡死阿萨德你那,是你那,你</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.氨基酸和交换机和看书的卡还款爱德华</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li class="no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--美拍-->
        <div class="meipai resource-con">
            <ul class="meipai-ul clearfix">
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#">小苍MM卡死阿萨德你那,是你那,你</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.氨基酸和交换机和看书的卡还款爱德华</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li class="no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--秒拍-->
        <div class="miaopai resource-con">
            <ul class="miaopai-ul clearfix">
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#">小苍MM卡死阿萨德你那,是你那,你</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.氨基酸和交换机和看书的卡还款爱德华</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li class="no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--花椒-->
        <div class="huajiao resource-con">
            <ul class="huajiao-ul clearfix">
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#">小苍MM卡死阿萨德你那,是你那,你</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.氨基酸和交换机和看书的卡还款爱德华</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li class="no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--映客-->
        <div class="yingke resource-con">
            <ul class="yingke-ul clearfix">
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name plain-text-length-limit" data-limit="5" href="#">小苍MM卡死阿萨德你那,是你那,你</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播,卡加上点卡按客户卡号的卡号卡死的</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.氨基酸和交换机和看书的卡还款爱德华</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
                <li class="no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <div class="name-id">
                                <a class="name" href="#">小苍MM</a>
                                <i></i>
                            </div>
                            <div class="intro">国内知名斗鱼游戏女主播.</div>
                        </div>
                    </div>
                    <a class="video-info" href="#"><img src="../src/images/banner-one.jpg" alt=""></a>
                    <div class="follower-audience">
                        <div class="fl">
                            <span>粉丝数 :</span>
                            <span class="follower-account">3.4万</span>
                        </div>
                        <div class="audience fl">
                            <span>平均观看人数 :</span>
                            <span class="audience-account">134111</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!--微信自媒体资源-->
<div class="resource-wrap">
    <input type="hidden" id="weixin-list-url" value="<?= Url::to(['weixin-list']) ?>">
    <div class="weixin-resource resource">
        <!--资源头部-->
        <div class="resource-title clearfix">
            <span class="resource-icon fl"></span>
            <span class="resource-name fl">微信自媒体资源</span>
            <a class="fr color-main" href="<?= Url::to(['/weixin/media/list'])?>" target="_blank">更多</a>
        </div>
        <!--资源导航-->
        <div class="weixin-nav resource-nav">
            <ul class="clearfix">
                <li class="active">
                    <div class="platform">汽车</div>
                    <div>
                        <span>1600+账号资源</span>
                        <i class="caret"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">母婴/育儿</div>
                    <div>
                        <span>1400+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">IT/互联网</div>
                    <div>
                        <span>1400+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">时尚</div>
                    <div>
                        <span>2000+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">健康养生</div>
                    <div>
                        <span>1000+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li class="no-border">
                    <div class="platform">生活</div>
                    <div>
                        <span>7000+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <ul class="bottom-line clearfix">
                <li class="line-active"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <!--汽车-->
        <div class="car-weixin resource-con show">
            <div class="account-detail clearfix">
                <div class="account fl">
                    <div class="basic-info clearfix">
                        <a class="portrait fl"
                           href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>">
                            <img src="" alt="">
                        </a>
                        <div class="name-id-intro fl">
                            <a class="name"
                               href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-one"></div>
                </div>
                <div class="account fl no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl"
                           href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"><img src=""
                                                                                                                alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name"
                               href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-two"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="clearfix">
                    <li class="clearfix">
                        <a class="portrait fl"
                           href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"><img src=""
                                                                                                                alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5"
                               href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix no-border">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--母婴/育儿-->
        <div class="baby-weixin resource-con">
            <div class="account-detail clearfix">
                <div class="account fl">
                    <div class="basic-info clearfix">
                        <a class="portrait fl"
                           href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"><img
                                src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name"
                               href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"></a>
                            <div class="id"></div>
                            <div class="intro">.</div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-three"></div>
                </div>
                <div class="account fl no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl"
                           href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"><img
                                src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name"
                               href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-four"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="clearfix">
                    <li class="clearfix">
                        <a class="portrait fl"
                           href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"><img
                                src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5"
                               href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl"
                           href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"><img
                                src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5"
                               href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl"
                           href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_']) ?>"><img
                                src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="#"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix no-border">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--IT/互联网-->
        <div class="it-weixin resource-con">
            <div class="account-detail clearfix">
                <div class="account fl">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-five"></div>
                </div>
                <div class="account fl no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-six"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="clearfix">
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix no-border">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--时尚-->
        <div class="fashion-weixin resource-con">
            <div class="account-detail clearfix">
                <div class="account fl">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-seven"></div>
                </div>
                <div class="account fl no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-eight"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="clearfix">
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix no-border">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--健康养生-->
        <div class="health-weixin resource-con">
            <div class="account-detail clearfix">
                <div class="account fl">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>">小苍MM</a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-nine"></div>
                </div>
                <div class="account fl no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-ten"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="clearfix">
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix no-border">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--生活-->
        <div class="life-weixin resource-con">
            <div class="account-detail clearfix">
                <div class="account fl">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-eleven"></div>
                </div>
                <div class="account fl no-border">
                    <div class="basic-info clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span><i class="follower-num"></i></li>
                            <li><span>头条平均阅读数 : </span><i class="avg-view-num"></i></li>
                            <li><span>最近发布时间 : </span><i class="publish-time"></i></li>
                        </ul>
                    </div>
                    <p><i></i>近7天头条阅读数</p>
                    <div class="reading-account-average" id="read-num-chart-twelve"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="clearfix">
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="clearfix no-border">
                        <a class="portrait fl" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"><img src="../src/images/demo.jpg" alt=""></a>
                        <div class="name-id-intro fl">
                            <a class="name plain-text-length-limit" data-limit="5" href="<?= Url::to(['/weixin/media/detail', 'media_uuid' => '_media_uuid_'])?>"></a>
                            <div class="id"></div>
                            <div class="intro"></div>
                            <i></i>
                        </div>
                        <ul class="detail-account clearfix fl">
                            <li>
                                <span>粉丝数</span>
                                <span class="follower-num"></span>
                            </li>
                            <li>
                                <span>头条平均阅读数</span>
                                <span class="avg-view-num"></span>
                            </li>
                            <li>
                                <span>沃米指数</span>
                                <span class="wmi-num"></span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--微博自媒体资源-->
<div class="resource-wrap">
    <input type="hidden" id="weibo-list-url" value="<?= Url::to(['weibo-list']) ?>">
    <div class="weibo-resource resource">
        <!--资源头部-->
        <div class="resource-title clearfix">
            <span class="resource-icon fl"></span>
            <span class="resource-name fl">微博自媒体资源</span>
            <a class="fr color-main" href="<?= Url::to(['/weibo/media/list'])?>" target="_blank">更多</a>
        </div>
        <!--资源导航-->
        <div class="weibo-nav resource-nav">
            <ul class="clearfix">
                <li class="active">
                    <div class="platform">汽车</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">母婴/育儿</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">IT/互联网</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">时尚</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li>
                    <div class="platform">健康养生</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
                <li class="no-border">
                    <div class="platform">生活</div>
                    <div>
                        <span>500+账号资源</span>
                        <i class="caret color-fff"></i>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <ul class="bottom-line clearfix">
                <li class="line-active"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <!--汽车-->
        <div class="car-weibo resource-con show">
            <div class="account-detail clearfix">
                <div class="account clearfix fl">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#"></a>
                                <div class="intro"></div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num"></i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num"></i></li>
                            <li><span>转发数 :</span> <i class="transmit-num"></i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num"></i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-one"></div>
                </div>
                <div class="account clearfix fl no-border">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#"></a>
                                <div class="intro"></div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num"></i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num"></i></li>
                            <li><span>转发数 :</span> <i class="transmit-num"></i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num"></i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-two"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="account-list-ul clearfix">
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name plain-text-length-limit" data-limit="4" href="#"></a>
                                <div class="intro"></div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="fl">
                            <li>粉丝数 : <span class="follower-num"></span></li>
                            <li>转发数 : <span class="transmit-num"></span></li>
                            <li>评论数 : <span class="comment-num"></span></li>
                            <li>点赞数 : <span class="agree-num"></span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name plain-text-length-limit" data-limit="4" href="#"></a>
                                <div class="intro"></div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num"></span></li>
                            <li>转发数 : <span class="transmit-num"></span></li>
                            <li>评论数 : <span class="comment-num"></span></li>
                            <li>点赞数 : <span class="agree-num"></span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name plain-text-length-limit" data-limit="4" href="#"></a>
                                <div class="intro"></div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num"></span></li>
                            <li>转发数 : <span class="transmit-num"></span></li>
                            <li>评论数 : <span class="comment-num"></span></li>
                            <li>点赞数 : <span class="agree-num"></span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix no-border">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name plain-text-length-limit" data-limit="4" href="#"></a>
                                <div class="intro"></div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num"></span></li>
                            <li>转发数 : <span class="transmit-num"></span></li>
                            <li>评论数 : <span class="comment-num"></span></li>
                            <li>点赞数 : <span class="agree-num"></span></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--母婴-->
        <div class="baby-weibo resource-con">
            <div class="account-detail clearfix">
                <div class="account clearfix fl">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-three"></div>
                </div>
                <div class="account clearfix fl no-border">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-four"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="account-list-ul clearfix">
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix no-border">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--IT科技-->
        <div class="it-weibo resource-con">
            <div class="account-detail clearfix">
                <div class="account clearfix fl">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-five"></div>
                </div>
                <div class="account clearfix fl no-border">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-six"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="account-list-ul clearfix">
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix no-border">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--时尚美容-->
        <div class="fashion-weibo resource-con">
            <div class="account-detail clearfix">
                <div class="account clearfix fl">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-seven"></div>
                </div>
                <div class="account clearfix fl no-border">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-eight"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="account-list-ul clearfix">
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix no-border">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--健康美食-->
        <div class="health-weibo resource-con">
            <div class="account-detail clearfix">
                <div class="account clearfix fl">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-nine"></div>
                </div>
                <div class="account clearfix fl no-border">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-ten"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="account-list-ul clearfix">
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix no-border">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--本地生活-->
        <div class="life-weibo resource-con">
            <div class="account-detail clearfix">
                <div class="account clearfix fl">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-eleven"></div>
                </div>
                <div class="account clearfix fl no-border">
                    <div class="basic-info fl">
                        <div class="clearfix">
                            <a class="portrait fl" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro fl">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class="clearfix fl">
                            <li><span>粉丝数 :</span> <i class="follower-num">143900</i></li>
                            <li class="comment-account"><span>评论数 : </span> <i class="comment-num">143900</i></li>
                            <li><span>转发数 :</span> <i class="transmit-num">143900</i></li>
                            <li class="agree-account"><span>点赞数 :</span> <i class="agree-num">143900</i></li>
                        </ul>
                    </div>
                    <div class="data-charts fl" id="variety-data-twelve"></div>
                </div>
            </div>
            <div class="account-list">
                <ul class="account-list-ul clearfix">
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                    <li class="account-list-detail clearfix no-border">
                        <div class="basic-info fl">
                            <a class="portrait" href="#"><img src="../src/images/demo.jpg" alt=""></a>
                            <div class="name-id-intro">
                                <a class="name" href="#">小苍MM</a>
                                <div class="intro">国内知名斗鱼游戏女主播.</div>
                                <i></i>
                            </div>
                        </div>
                        <ul class=" fl">
                            <li>粉丝数 : <span class="follower-num">143900</span></li>
                            <li>转发数 : <span class="transmit-num">143900</span></li>
                            <li>评论数 : <span class="comment-num">143900</span></li>
                            <li>点赞数 : <span class="agree-num">143900</span></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--经典案例-->
<div class="case-wrap">
    <div class="case">
        <div class="title clearfix">
            <span class="title-icon fl"></span>
            <span class="title-con fl">经典案例</span>
        </div>
        <div id="tab">
            <ul class="clearfix">
                <li><span class="active1">直播案例</span></li>
                <li><span>快消案例</span></li>
                <li><span>O2O案例</span></li>
                <li><span>母婴案例</span></li>
                <li><span>汽车案例</span></li>
                <li><span>旅游案例</span></li>
                <li id="finance"><span>金融财经案例</span></li>
            </ul>
        </div>
        <div id="banner2">
            <div id="wrap">
                <ul id="pic" class="clearfix">
                    <li class="zhibo"></li>
                    <li class="kuaixiao"></li>
                    <li class="o2o"></li>
                    <li class="muying"></li>
                    <li class="car"></li>
                    <li class="tour"></li>
                    <li class="finance"></li>
                </ul>
            </div>
            <ul id="dot">
                <li class="active2"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
            <div id="prev"></div>
            <div id="next"></div>
        </div>
    </div>
</div>
<!--我们的服务-->
<div class="our-serve-wrap">
    <div class="our-serve clearfix">
        <div class="title clearfix">
            <span class="title-icon fl"></span>
            <span class="title-con fl">我们的服务</span>
        </div>
        <div class="clearfix">
            <div class="computer-img fl"></div>
            <ul class="serve-con clearfix fl">
                <li class="more">
                    <i></i>
                    <em>多</em>
                    <span>20万 + 高配合度优质自媒体资源</span>
                    <span>多维度账号传播价值分析</span>
                </li>
                <li class="fast">
                    <i></i>
                    <em>快</em>
                    <span>创建专属资源库管理</span>
                    <span>和推荐智能投放高效完成推广计划</span>
                </li>
                <li class="great">
                    <i></i>
                    <em>好</em>
                    <span>超过5年服务团队全程服务</span>
                    <span>专业媒介团队分行业研究同步共享</span>
                </li>
                <li class="save">
                    <i></i>
                    <em>省</em>
                    <span>集中采买 拒绝差价</span>
                    <span>快速选择 省心省力</span>
                </li>
            </ul>
        </div>
    </div>
</div>

