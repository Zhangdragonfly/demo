<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 7/14/16 10:21 PM
 */

namespace admin\modules\media\controllers;
use admin\controllers\BaseAppController;
use common\models\MediaExecutor;
use common\models\MediaExecutorAssign;
use Yii;
use yii\db\Query;
use yii\web\Response;

/**
 * Class ExecutorController
 * @package admin\modules\media\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class ExecutorController extends BaseAppController
{
    /**
     * 媒介运营列表
     */
    public function actionFetchList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $executorList = (new Query())
            ->select('uuid, name, account_uuid')
            ->from(['media_executor' => MediaExecutor::tableName()])
            ->orderBy(['media_executor.uuid' => SORT_ASC])
            ->all();

        return ['err_code' => 0, 'err_msg' => '获取成功', 'executor_list' => $executorList];
    }

    /**
     * 分配媒介运营
     */
    public function actionAssignOne()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            Yii::$app->response->format = Response::FORMAT_JSON;

            $mediaType = $request->post('media_type'); // 媒体类型
            $executorUUID = $request->post('executor_uuid'); // 媒介运营uuid
            $mediaUUID = $request->post('media_uuid'); // 媒体uuid
            $assign = MediaExecutorAssign::findOne(['media_type' => $mediaType, 'media_uuid' => $mediaUUID]);
            if($assign === null){
                $assign = new MediaExecutorAssign();
                $assign->uuid = ''; // TODO
                $assign->media_type = $mediaType;
                $assign->executor_uuid = $executorUUID;
                $assign->media_uuid = $mediaUUID;
                $assign->assign_time = time();
                $assign->save();
            } else if($assign->executor_uuid != $executorUUID){
                $assign->executor_uuid = $executorUUID;
                $assign->save();
            }
            return ['err_code' => 0, 'err_msg' => '分配成功!'];
        }
    }
}