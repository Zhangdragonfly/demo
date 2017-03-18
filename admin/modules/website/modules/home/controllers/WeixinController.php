<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 8/24/16 10:19 AM
 */
namespace admin\modules\website\modules\home\controllers;

use admin\controllers\BaseAppController;
use admin\helpers\AdminHelper;
use common\models\MediaWeixin;
use common\models\WomHomePageMediaWeixin;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\web\Response;

/**
 * 首页管理 - 微信管理
 * Class WeixinController
 * @package admin\modules\website\modules\home\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class WeixinController extends BaseAppController
{
    /**
     * 列表
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        $query = (new Query())
            ->select(['home_media_weixin.uuid', 'home_media_weixin.media_name',
                'home_media_weixin.media_cate', 'home_media_weixin.weixin_id',
                'home_media_weixin.short_desc', 'home_media_weixin.follower_num',
                'home_media_weixin.m_head_avg_view_num', 'home_media_weixin.show_latest_7_head_view_num',
                'home_media_weixin.status'])
            ->from(['home_media_weixin' => WomHomePageMediaWeixin::tableName()]);

        if ($request->isPost) {
            $mediaName = $request->post('media-name');
            $query->andWhere(['like', 'home_media_weixin.media_name', $mediaName]);
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

    public function actionSearch()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mediaName = $request->post('media_name');

        $query = (new Query())
            ->select(['media_weixin.public_name', 'media_weixin.public_id', 'media_weixin.media_cate', 'media_weixin.follower_num'])
            ->from(['media_weixin' => MediaWeixin::tableName()])
            ->where(['or', ['like', 'media_weixin.public_name', $mediaName], ['like', 'media_weixin.public_id', $mediaName]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);

        return ['err_code' => 0, 'media_list' => $dataProvider->getModels()];
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