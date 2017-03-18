<?php
use admin\assets\AppAsset;
use common\helpers\MediaHelper;
use common\models\WomAdminAccount;

AppAsset::addScript($this, '@web/plugins/sweetalert/js/sweetalert.min.js');
AppAsset::addCss($this, '@web/plugins/sweetalert/css/sweetalert.css');
AppAsset::addScript($this, '@web/js/weibo/order-list.js');

?>

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12"><!--基础信息-->
            <div class="tab-pane fade active in" id="default-tab-1">
                <h4>预约账号信息</h4>
                <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                    <div class="panel-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>账号</th>
                                        <th>粉丝数</th>
                                        <th>价格位置</th>
                                        <th>价格</th>
                                        <th>接单备注</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    switch($data['sub_type']){
                                        case 1:$sub_type = "软广直发";break;
                                        case 2:$sub_type = "软广转发";break;
                                        case 3:$sub_type = "微任务直发";break;
                                        case 4:$sub_type = "微任务转发";break;
                                        default:$sub_type = "未知";
                                    }
                                    ?>
                                    <tr>
                                        <td><?=!empty($data['weibo_name'])?$data['weibo_name']:"";?></td>
                                        <td><?=!empty($data['follower_num'])? round($data['follower_num']/10000,1)."万":"";?></td>
                                        <td><?=$sub_type?></td>
                                        <td><?=!empty($data['price'])?$data['price']:"";?></td>
                                        <td><?=!empty($data['accept_remark'])?$data['accept_remark']:"";?></td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>

                <h4>预约需求内容</h4>
                <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">预约名称</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control"  value="<?=!empty($data['plan_name'])?$data['plan_name']:"";?>"disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">预约执行时间</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control"  value="<?=!empty($data['execute_start_time'])?date('Y-m-d',$data['execute_start_time']):"";?>"style="display:inline-block;float: left"disabled/>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control"  value="<?=!empty($data['execute_end_time'])?date('Y-m-d',$data['execute_end_time']):"";?>"style="display:inline-block;float: right"disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">联系人</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control"  value="<?=!empty($data['contacts'])?$data['contacts']:"";?>" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">手机号码</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control"  value="<?=!empty($data['phone'])?$data['phone']:"";?>"  disabled/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">预约需求</label>
                                <div class="col-md-6">
                                    <textarea class="form-control"  placeholder="最多输入140个字符!"rows="5" disabled><?=!empty($data['plan_desc'])?$data['plan_desc']:"";?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">需求反馈时间</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control"  value="<?=!empty($data['feedback_time'])?date('Y-m-d',$data['feedback_time']):"";?>" disabled/>
                                </div>
                                <label class="col-md-3 control-label">订单创建时间: <span class="createTime"><?=!empty($data['create_time'])?date('Y-m-d',$data['create_time']):"";?></span></label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">订单状态</label>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="0"<?=($data['status']==0)?"selected":""; ?>>未提交</option>
                                        <option value="1"<?=($data['status']==1)?"selected":""; ?>>已提交</option>
                                        <option value="2"<?=($data['status']==2)?"selected":""; ?>>已完成</option>
                                    </select>
                                </div>
                                <label class="col-md-2 control-label">执行媒介</label>
                                <div class="col-md-2">
                                    <select name="execute_uuid"class="form-control">
                                        <option value="-1" <?=($data['execute_person_uuid']==-1)?"selected":""; ?>>未知</option>
                                        <?php $account_array = WomAdminAccount::find()->asArray()->all();
                                        foreach($account_array as $k=>$v){?>
                                            <option value="<?=$v['uuid']; ?>" <?=($data['execute_person_uuid']==$v['uuid'])?"selected":""; ?>><?=$v['user_name']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">订单执行金额</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="execute_price" value="<?=!empty($data['execute_price'])?$data['execute_price']:"";?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">订单执行备注</label>
                                <div class="col-md-6">
                                    <textarea name="execute_remark" class="form-control" placeholder="最多输入140个字符!" rows="5" ><?=!empty($data['execute_remark'])?$data['execute_remark']:"";?></textarea>
                                </div>
                            </div>
                        </form>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-9">
                                    <button type="text" class="btn btn-success btn-save" data-uuid="<?=!empty($data['uuid'])?$data['uuid']:"";?>" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/order/update-order'))?>">保存</button>
                                    <button type="text" class="btn btn-primary btn-cancel" data-url="<?=Yii::$app->urlManager->createUrl(array('weibo/order/list'))?>">返回</button>
                                </div>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$Js = <<<JS
    orderDetail();
JS;
$this->registerJs($Js);
?>
