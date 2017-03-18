<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/24/16 1:35 PM
 */

namespace wom\controllers;

use common\models\MediaVideo;
use common\models\MediaWeibo;
use common\models\MediaWeixin;
use common\models\MediaWeixinStatistic;
use common\models\VideoPlatformCommonInfo;
use common\models\WomHomePageMediaVideo;
use common\models\WomHomePageMediaWeibo;
use common\models\WomHomePageMediaWeixin;
use common\helpers\ExternalFileHelper;
use wom\helpers\StatHelper;
use Yii;
use yii\db\Query;
use yii\web\Response;

/**
 * Class WomController
 * @package wom\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class WomController extends BaseAppController
{
    /**
     * @return string
     */
    public function actionError()
    {
        $this->layout = 'site-stage';
        return $this->render('500');
    }

    /**
     * 沃米首页
     */
    public function actionIndex()
    {
        $this->layout = 'site-index';

        // 视频
        $video = (new Query())
            ->select([
                'home_video.uuid',
                'home_video.media_uuid',
                'home_video.media_name',
                'media_video.nickname',
                'home_video.avatar_img',
                'home_video.platform_code',
                'home_video.short_desc',
                'video_platform_common_info.person_sign',
                'home_video.follower_num',
                'video_platform_common_info.follower_num as follower_number',
                'home_video.avg_view_num',
                'video_platform_common_info.avg_watch_num',
                'home_video.source_video_url',
                'home_video.video_cover_img'
            ])
            ->from(['home_video' => WomHomePageMediaVideo::tableName()])
            ->Where(['home_video.status' => WomHomePageMediaVideo::STATUS_IN_HOME])
            ->leftJoin(['media_video' => MediaVideo::tableName()],'media_video.uuid = home_video.media_uuid')
            ->leftJoin(['video_platform_common_info' => VideoPlatformCommonInfo::tableName()], 'home_video.media_uuid = video_platform_common_info.video_uuid and home_video.platform_code = video_platform_common_info.platform_type')
            ->limit(24)
            ->all();

        return $this->render('index',[
            'videoJson' => $this->getVideoJson($video)
        ]);
    }

    /**
     * 微信
     */
    public function actionWeixinList()
    {
        $weixin = (new Query())
            ->select([
                'home_weixin.uuid',
                'home_weixin.media_uuid',
                'home_weixin.media_name',
                'media_weixin.public_name as weixin_name',
                'home_weixin.media_cate',
                'home_weixin.weixin_id',
                'home_weixin.short_desc',
                'media_weixin.account_short_desc',
                'home_weixin.follower_num',
                'media_weixin.follower_num as follower_number',
                'home_weixin.m_head_avg_view_num',
                'media_weixin.m_head_avg_view_cnt as head_avg_view_num',
                'home_weixin.show_latest_7_head_view_num',
                'media_weixin.latest_article_post_date',
                'media_weixin.m_wmi',
                'weixin_statistic.article_effect',
            ])
            ->from(['home_weixin' => WomHomePageMediaWeixin::tableName()])
            ->Where(['home_weixin.status' => WomHomePageMediaWeixin::STATUS_IN_HOME])
            ->leftJoin(['media_weixin' => MediaWeixin::tableName()],'home_weixin.media_uuid = media_weixin.uuid')
            ->leftJoin(['weixin_statistic' => MediaWeixinStatistic::tableName()],'home_weixin.weixin_id = weixin_statistic.public_id')
            ->limit(36)
            ->all();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->getWeixinJson($weixin);
    }

    /**
     * 微博
     * @return array
     */
    public function actionWeiboList()
    {
        $weibo = (new Query())
            ->select([
                'home_weibo.uuid',
                'home_weibo.media_uuid',
                'home_weibo.media_name',
                'media_weibo.weibo_name',
                'home_weibo.media_cate',
                'home_weibo.avatar_img',
                'home_weibo.short_desc',
                'media_weibo.intro',
                'home_weibo.follower_num',
                'media_weibo.follower_num as follower_number',
                'home_weibo.total_comment_num',
                'home_weibo.total_forward_num',
                'home_weibo.total_like_num',
                'home_weibo.show_chart',
                'home_weibo.source_weibo_url'
            ])
            ->from(['home_weibo' => WomHomePageMediaWeibo::tableName()])
            ->Where(['home_weibo.status' => WomHomePageMediaWeibo::STATUS_IN_HOME])
            ->leftJoin(['media_weibo' => MediaWeibo::tableName()],'home_weibo.media_uuid = media_weibo.uuid')
            ->limit(36)
            ->all();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->getWeiboJson($weibo);
    }

    /**
     * 视频json
     * @param $video 视频列表
     * @return string json
     */
    public function getVideoJson($video)
    {
        $homePageVideoImgDir = Yii::$app->request->hostInfo . '/' . ExternalFileHelper::getHomePageRelativeDirectory() . 'video/';
        $videoList = [];
        foreach($video as $key => $value){
            $videoList[$key]['uuid'] = $value['uuid'];
            $videoList[$key]['media_uuid'] = $value['media_uuid'];
            $videoList[$key]['video_cover_img'] = $homePageVideoImgDir. $value['video_cover_img'];
            $videoList[$key]['media_name'] = $value['media_name'];
            $videoList[$key]['avatar_img'] = $homePageVideoImgDir. $value['avatar_img'];
            $videoList[$key]['short_desc'] = $value['short_desc'];
            $videoList[$key]['follower_num'] = $value['follower_num'];
            $videoList[$key]['avg_view_num'] = $value['avg_view_num'];
            $videoList[$key]['platform_code'] = $value['platform_code'];
            $videoList[$key]['source_video_url'] = $value['source_video_url'];
            if(empty($value['media_name'])){
                $videoList[$key]['media_name'] = $value['nickname'];
            }
            if(empty($value['short_desc'])){
                $videoList[$key]['short_desc'] = $value['person_sign'];
            }
            if(empty($value['follower_num'])){
                $videoList[$key]['follower_num'] = $value['follower_number'];
            }
            if(empty($value['avg_view_num'])){
                $videoList[$key]['avg_view_num'] = $value['avg_watch_num'];
            }
        }
        return json_encode($videoList);
    }

    /**
     * 微信json
     * @param $weixin 微信列表
     * @return array
     */
    public function getWeixinJson($weixin)
    {
        $weixinxList = [];
        foreach($weixin as $key => $value){
            $weixinxList[$key]['uuid'] = $value['uuid'];
            $weixinxList[$key]['media_uuid'] = $value['media_uuid'];
            $weixinxList[$key]['media_name'] = $value['media_name'];
            if(empty($value['media_name'])){
                $weixinxList[$key]['media_name'] = $value['weixin_name'];
            }
            $weixinxList[$key]['media_cate'] = $value['media_cate'];
            $weixinxList[$key]['weixin_id'] = $value['weixin_id'];
            $weixinxList[$key]['short_desc'] = $value['short_desc'];
            if(empty($value['short_desc'])){
                $weixinxList[$key]['short_desc'] = $value['account_short_desc'];
            }
            $weixinxList[$key]['follower_num'] = $value['follower_num'];
            if(empty($value['follower_num'])){
                $weixinxList[$key]['follower_num'] = $value['follower_number'];
            }
            $weixinxList[$key]['m_head_avg_view_num'] = $value['m_head_avg_view_num'];
            if(empty($value['m_head_avg_view_num'])){
                $weixinxList[$key]['m_head_avg_view_num'] = $value['head_avg_view_num'];
            }
            $weixinxList[$key]['show_latest_7_head_view_num'] = $value['show_latest_7_head_view_num'];
            $weixinxList[$key]['latest_article_post_date'] = date('Y-m-d', $value['latest_article_post_date']);
            $weixinxList[$key]['wmi'] = $value['m_wmi'];

            $weixinxList[$key]['avg_read_num_date'] = "";
            $weixinxList[$key]['avg_read_num_value'] = "";
            if(empty($value['article_effect'])){
                $trend = array();
            }else{
                $articleEffectArray = json_decode($value['article_effect'], true);
                // 趋势
                $trend = $articleEffectArray['month_article_effect']['month_head_article_trend'];
            }
            // 排序并返回最近7天的数据
            $res = StatHelper::dealWeixinStatisticDate($trend,7,'two');
            ksort($res);
            foreach($res as $date => $readNum){
                $weixinxList[$key]['avg_read_num_date'] .= date('m-d', $date) . ',';
                $weixinxList[$key]['avg_read_num_value'] .= $readNum['read_num']. ',';
            }
            $weixinxList[$key]['avg_read_num_date'] = substr($weixinxList[$key]['avg_read_num_date'],0,-1);
            $weixinxList[$key]['avg_read_num_value'] = substr($weixinxList[$key]['avg_read_num_value'],0,-1);
        }
        return $weixinxList;
    }

    /**
     * 微博json
     */
    public function getWeiboJson($weibo)
    {
        $homePageWeiboImgDir = Yii::$app->request->hostInfo . '/' . ExternalFileHelper::getHomePageRelativeDirectory() . 'weibo/';
        $weiboList = [];
        foreach($weibo as $key => $value){
            $weiboList[$key]['uuid'] = $value['uuid'];
            $weiboList[$key]['media_uuid'] = $value['media_uuid'];
            $weiboList[$key]['media_name'] = $value['media_name'];
            $weiboList[$key]['follower_num'] = $value['follower_num'];
            $weiboList[$key]['short_desc'] = $value['short_desc'];
            $weiboList[$key]['source_weibo_url'] = $value['source_weibo_url'];
            if(empty($value['media_name'])){
                $weiboList[$key]['media_name'] = $value['weibo_name'];
            }
            $weiboList[$key]['media_cate'] = $value['media_cate'];
            $weiboList[$key]['avatar_img'] = $homePageWeiboImgDir. $value['avatar_img'];
            if(empty($value['follower_num'])){
                $weiboList[$key]['follower_num'] = $value['follower_number'];
            }
            if(empty($value['short_desc'])){
                $weiboList[$key]['short_desc'] = $value['intro'];
            }
            $weiboList[$key]['total_comment_num'] = $value['total_comment_num'];
            $weiboList[$key]['total_forward_num'] = $value['total_forward_num'];
            $weiboList[$key]['total_like_num'] = $value['total_like_num'];
            $weiboList[$key]['show_chart'] = $value['show_chart'];
        }
        return $weiboList;
    }

    /**
     * 获取微信分类资源
     * @param $media
     * @return string
     */
    public function getWxCateMedia($media){
        if($media['media_cate'] == '#4#'){
            // 1.汽车
            $cateMedia['car'][] = $media;
            $cate = "car";
        }else if($media['media_cate'] == '#10#'){
            // 2.母婴/育儿
            $cateMedia['baby'][] = $media;
            $cate = "baby";
        }else if($media['media_cate'] == '#7#'){
            // 3.IT/互联网
            $cateMedia['it'][] = $media;
            $cate = "it";
        }else if($media['media_cate'] == '#5#'){
            // 4.时尚
            $cateMedia['fashion'][] = $media;
            $cate = "fashion";
        }else if($media['media_cate'] == '#14#'){
            // 5.美食
            $cateMedia['food'][] = $media;
            $cate = "food";
        }else if($media['media_cate'] == '#2#'){
            // 6.生活
            $cateMedia['life'][] = $media;
            $cate = "life";
        }else if($media['media_cate'] == '#19#'){
            // 7.金融财经
            $cateMedia['financial'][] = $media;
            $cate = "financial";
        }else if($media['media_cate'] == '#13#'){
            // 8.家居房产
            $cateMedia['house'][] = $media;
            $cate = "house";
        }

        return $cate;
    }

    /**
     * 获取视频分类资源
     * @param $media
     * @return string
     */
    public function getVideoCateMedia($media){
        if($media['media_cate'] == '#1#'){
            // 1.花椒
            $cateMedia['hj'][] = $media;
            $cate = "hj";
        }else if($media['media_cate'] == '#4#'){
            // 2.美拍
            $cateMedia['mp'][] = $media;
            $cate = "mp";
        }else if($media['media_cate'] == '#5#'){
            // 3.秒拍
            $cateMedia['mip'][] = $media;
            $cate = "mip";
        }else if($media['media_cate'] == '#6#'){
            // 4.斗鱼
            $cateMedia['dy'][] = $media;
            $cate = "dy";
        }else if($media['media_cate'] == '#7#'){
            // 5.映客
            $cateMedia['yk'][] = $media;
            $cate = "yk";
        }else if($media['media_cate'] == '#2#'){
            // 6.熊猫
            $cateMedia['panda'][] = $media;
            $cate = "panda";
        }else if($media['media_cate'] == '#9#'){
            // 9.一直播
            $cateMedia['yzb'][] = $media;
            $cate = "yzb";
        }else{
            // 其他
            $cateMedia['other'][] = $media;
            $cate = "other";
        }

        return $cate;
    }

}
