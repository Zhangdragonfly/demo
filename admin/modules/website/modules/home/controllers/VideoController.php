<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 8/24/16 10:19 AM
 */

namespace admin\modules\website\modules\home\controllers;

use admin\controllers\BaseAppController;
use admin\helpers\AdminHelper;
use common\models\WomHomePageMediaVideo;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Response;

/**
 * 首页-网红管理
 * Class VideoController
 * @package admin\modules\website\modules\home\controllers
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class VideoController extends BaseAppController
{
    /**
     * 列表
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        $query = (new Query())
            ->select(['home_media_video.uuid', 'home_media_video.media_name',
                'home_media_video.platform_code', 'home_media_video.avatar_img',
                'home_media_video.short_desc', 'home_media_video.follower_num',
                'home_media_video.avg_view_num', 'home_media_video.video_cover_img',
                'home_media_video.status'])
            ->from(['home_media_video' => WomHomePageMediaVideo::tableName()]);

        if ($request->isPost) {
            $mediaName = $request->post('media-name');
            $query->andWhere(['like', 'home_media_video.media_name', $mediaName]);
            $page = $request->post("page", 0);
        } else {
            $page = 0;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => AdminHelper::getPageSize(),
                'page' => $page
            ]
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 获取视频的信息
     */
    public function actionGetMediaInfo()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $uuid = $request->get('uuid');

            $homeMediaVideo = WomHomePageMediaVideo::find()
                ->where(['uuid' => $uuid])
                ->asArray()
                ->one();

            return ['err_code' => 0, 'home_media_video' => $homeMediaVideo];
        }
    }

    /**
     * 更新视频的信息
     */
    public function actionUpdateMediaInfo()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $uuid = $request->post('uuid');
            $mediaName = $request->post('media_name');
            $followerNum = $request->post('follower_num');
            $avgViewNum = $request->post('avg_view_num');
            $shortDesc = $request->post('short_desc');

            WomHomePageMediaVideo::updateAll([
                'media_name' => $mediaName,
                'short_desc' => $shortDesc,
                'follower_num' => $followerNum,
                'avg_view_num' => $avgViewNum
            ], [
                'uuid' => $uuid
            ]);

            return ['err_code' => 0, 'err_msg' => '更新媒体资源成功'];
        }
    }
}