<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:33 PM
 */
use admin\assets\AppAsset;
use common\helpers\MediaHelper;
use yii\helpers\Html;

AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addScript($this, '@web/js/home/task-create.js');

$videoJS = <<<JS
    taskCreate();
JS;
$this->registerJs($videoJS);
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">网站管理</a></li>
        <li><a href="javascript:;">任务管理</a></li>
        <li class="active">创建任务</li>
    </ol>
    <h1 class="page-header">创建任务 <small>为admin创建不同的任务</small></h1>

    <div class="row">
        <!-- 资源抓取-->
        <div class="col-md-6">
            <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                    <h4 class="panel-title">爬取资源</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label">资源类型</label>
                            <div class="col-md-9">
                                <label class="radio-inline">
                                    <input type="radio" name="media_type" value="1" checked />
                                    微信
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="media_type" value="2" />
                                    微博
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="media_type" value="3" />
                                    视频
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">资源ID</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control media_id" placeholder="资源唯一ID" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">资源URL</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control media_url" placeholder="资源唯一链接"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">确定</label>
                            <div class="col-md-9">
                                <button type="button" class="btn btn-sm btn-success btn-submit-grab" data-url="<?=Yii::$app->urlManager->createUrl(array('home/task/create-grab'))?>">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 价格系数调整 -->
        <div class="col-md-6">
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-stuff-2">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                    <h4 class="panel-title">价格系数调整</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <div class="col-md-8">
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="start" placeholder="价格" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" name="end" placeholder="价格" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-4 control-label">价格系数</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="start" placeholder="价格" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" name="end" placeholder="价格" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-4 control-label">价格系数</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="start" placeholder="价格" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" name="end" placeholder="价格" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-4 control-label">价格系数</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="start" placeholder="价格" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" name="end" placeholder="价格" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-4 control-label">价格系数</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9">
                                <button type="button" class="btn btn-sm btn-success">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
    </div>

</div>



























