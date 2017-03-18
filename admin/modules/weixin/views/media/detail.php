<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:35 PM
 */
use common\helpers\DateTimeHelper;

?>

<div id="content" class="content">

    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">微信</a></li>
        <li><a href="javascript:;">资源管理</a></li>
        <li class="active">资源详情</li>
    </ol>

    <h1 class="page-header">资源详情</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body bg-grey-m-lighter">
                    <div class="row">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>字段</th>
                                <th>值</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>账号名称 :</td>
                                <td><?php echo $media['public_name']; ?></td>
                            </tr>
                            <tr>
                                <td>微信ID :</td>
                                <td><?php echo $media['public_id']; ?></td>
                            </tr>
                            <tr>
                                <td>头像 :</td>
                                <td><img style="max-width: 80px" src="/images/4b403faaa9c437f659ec6a73130b37086358dd18.jpg"
                                         alt=""></td>
                            </tr>
                            <tr>
                                <td>二维码 :</td>
                                <td><img style="max-width: 80px" src="/images/4b403faaa9c437f659ec6a73130b37086358dd18.jpg"
                                         alt=""></td>
                            </tr>
                            <tr>
                                <td>粉丝数 :</td>
                                <td><?php echo $media['follower_num']; ?></td>
                            </tr>
                            <tr>
                                <td>认证信息 :</td>
                                <td><?php echo $media->getCertInfo(); ?></td>
                            </tr>
                            <tr>
                                <td>激活 :</td>
                                <td><?php echo $media->getActivateInfo(); ?></td>
                            </tr>
                            <tr>
                                <td>上架状态 :</td>
                                <td><?php echo $media->getPutUpInfo(); ?></td>
                            </tr>
                            <tr>
                                <td>入驻时间 :</td>
                                <td><?php echo $media->getFormattedCreateTime(); ?></td>
                            </tr>
                            <tr>
                                <td>最后抓取 :</td>
                                <td><?php echo $media->getFormattedLastCrawlTime(); ?></td>
                            </tr>
                            <tr>
                                <td>行业圈子 :</td>
                                <td><?php echo ' - '; ?></td>
                            </tr>
                            <tr>
                                <td>资源标签 :</td>
                                <td><?php echo ' - '; ?></td>
                            </tr>
                            <tr>
                                <td>粉丝区域 :</td>
                                <td><?php echo ' - '; ?></td>
                            </tr>
                            <tr>
                                <td>资源审核 :</td>
                                <td><?php echo ' - '; ?></td>
                            </tr>
                            <tr>
                                <td>最近审核时间 :</td>
                                <td><?php echo DateTimeHelper::getFormattedDateTime($media['last_verify_time'], 'Y-m-d H:i:s'); ?></td>
                            </tr>
                            <tr>
                                <td>备注 :</td>
                                <td><?php echo $media['comment']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

