<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 8/24/16 10:19 AM
 */
namespace admin\modules\home\controllers;

use admin\controllers\BaseAppController;
use admin\helpers\AdminHelper;
use common\helpers\MediaHelper;
use common\helpers\PlatformHelper;
use common\models\WomTask;
use common\models\WomTaskGrabMedia;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Response;

/**
 * 首页网红管理
 * Class VideoController
 * @package admin\modules\home\controllers
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class TaskController extends BaseAppController{

    /**
     * 列表
     */
    public function actionCreate(){

        return $this->render('create');
    }





    //添加后台视频资源抓取
    public function actionCreateGrab(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $task = new WomTask();
            $task->uuid = PlatformHelper::getUUID();
            $task->task_type = 1;
            $task->status = 0;
            $task->task_come_from = 2;
            $task->create_time = time();
            $task->update_time = time();
            $task->save();

            $grab = new WomTaskGrabMedia();
            $grab->uuid = PlatformHelper::getUUID();
            $grab->task_uuid = $task->uuid;
            $grab->media_type = trim($request->post("media_type"));
            $grab->media_url = trim($request->post("media_url"));
            $grab->media_id = $request->post("media_id");
            $grab->create_time = time();
            $grab->save();
            return json_encode(array('err_code'=>0));
        }else{
            return json_encode(array('err_code'=>1));
        }
    }

    /**
     * 基础信息列表
     */
    public function actionGrabList(){
        $request = Yii::$app->request;
        $page = 0;//默认第一页
        $query = (new Query())
            ->select([
                'task.uuid',
                'task.platform_type',
                'task.grab_id',
                'task.grab_url',
                'task.status',
                'task.platform_type',
                'task.create_time',
                'task.update_time',
            ])
            ->from(['task' => VideoGrabTask::tableName()]);
        if($request->isPost){
            $page = $request->post('page');
            $status = $request->post('status',-1);
            $grab_id = trim($request->post('grab_id'));
            $grab_url = trim($request->post('grab_url'));
            if (!empty($grab_id)){//艺人名称/账号ID
                $query->andWhere(['like', 'task.grab_id', $grab_id]);
            }
            if (!empty($grab_url)){//自媒体主
                $query->andWhere(['like', 'task.grab_url', $grab_url]);
            }
            if ($status != -1){ //状态
                $query->andWhere(['task.status'=>$status]);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => AdminHelper::getPageSize(),
                'page' => $page
            ]
        ]);
        return $this->render('grab-list', [
            'dataProvider' => $dataProvider,
        ]);
    }


}