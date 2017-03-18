<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 9/23/16 10:32 AM
 */
namespace wom\modules\weixin\controllers;

use wom\helpers\StatHelper;
use common\helpers\MediaHelper;
use common\models\AdOwner;
use common\models\MediaWeixin;
use common\models\MediaWeixinStatistic;
use common\models\WeixinArticle;
use wom\controllers\BaseAppController;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Response;
use yii;

/**
 * Class MediaController
 * @package wom\modules\weixin\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaController extends BaseAppController
{
    public $layout = '//media-stage';

    /**
     * 微信账号搜索列表页
     * @return string
     */
    public function actionList()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $page = $request->post('page', 0);
            // 搜索过滤条件
            $query = (new Query())
                ->select('weixin.uuid AS media_uuid,
                          weixin.pub_config,
                          weixin.follower_num,
                          weixin.public_name,
                          weixin.public_id,
                          weixin.last_update_time,
                          weixin.active_end_time,
                          weixin.account_cert,
                          weixin.account_short_desc as desc,
                          weixin.y_head_avg_view_cnt as head_avg_view_cnt,
                          weixin.y_avg_price_pv as avg_price_pv,
                          weixin.y_wmi as wmi,
                          has_origin_pub,
                          last_update_time,
                          (CASE
  WHEN weixin.active_end_time IS NULL THEN 0
  WHEN weixin.active_end_time < UNIX_TIMESTAMP(NOW())  THEN 1
  WHEN weixin.active_end_time > UNIX_TIMESTAMP(NOW())  THEN 2
  else 0 END ) as active')
                ->from(['weixin' => MediaWeixin::tableName()]);
            $search_name = $request->post('search_name', '');
            $mediaCate = $request->post('media_cate', -1); // 资源标签
            $belongTag = $request->post('belong_tag'); // 类型(媒体、个人等)
            $headAvgViewCnt = $request->post('read_num', -1); // 头条平均阅读数
            $retailPriceType = $request->post('retail_price_type', -1); // 零售价格类型
            $retailPrice = $request->post('retail_price', -1); // 零售价格
            $followerNum = $request->post('follower_num', -1); // 粉丝数量
            $followerArea = $request->post('follower_area', -1); // 粉丝地域
            $pubType = $request->post('pub_type', -1); // 发布形式
            $isPush= $request->post('is_push', -1); // 是否主推
            $sortByFollowerNum = $request->post('sort_by_follower_num', -1); // 按粉丝数量排序
            $sortByRetailPrice = $request->post('sort_by_retail_price', -1); // 按零售价排序
            $sortByAvgReadNum = $request->post('sort_by_m_1_avg_read_num', -1); // 按头条平均阅读数排序
            $sortByWomNum = $request->post('sort_by_wom_num', -1); // 按沃米指数排序
            $sortByUpdateTime = $request->post('sort_by_last_update_time', -1); // 按最近更新时间排序
            $sortByActiveEndTime = $request->post('sort_by_active_end_time', -1); // 按价格有效日期排序

            // 上下架
            $query->andWhere(['=', 'weixin.put_up', 1]);
            // 审核通过
            $query->andWhere(['=', 'status', 1]);
            // 不接单
            $query->andWhere(['or', 'weixin.s_pub_type!=0', 'weixin.m_1_pub_type!=0', 'weixin.m_2_pub_type!=0', 'weixin.m_3_pub_type!=0']);
            // 账号名称
            if (!empty($search_name)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $search_name], ['like', 'weixin.public_id', $search_name]]);
            }
            // 资源标签
            if (!empty($mediaCate) && $mediaCate != -1) {
                $tagWhereClause = ['or'];
                $mediaCateArray = explode(',', $mediaCate);
                foreach ($mediaCateArray as $tag) {
                    $tagWhereClause[] = ['like', 'weixin.media_cate', '#' . $tag . '#'];
                }
                $query->andWhere($tagWhereClause);
            }
            // 分类(媒体、个人等)
            if (!empty($belongTag)) {
                // $query->andWhere(['media_belong_type' => $belongTag]);
                $belongTagArray = explode(',', $belongTag);
                $query->andWhere(['in', 'weixin.media_belong_type', $belongTagArray]);
            }
            // 平均阅读数
            if (!empty($headAvgViewCnt) && $headAvgViewCnt != -1) {
                $headAvgViewCntArray = explode(',', $headAvgViewCnt);
                if($headAvgViewCntArray[1] == 'n'){
                    $query->andWhere(['>', 'weixin.y_head_avg_view_cnt', $headAvgViewCntArray[0]]);
                }else{
                    $query->andWhere(['between', 'weixin.y_head_avg_view_cnt', $headAvgViewCntArray[0], $headAvgViewCntArray[1]]);
                }
            }
            // 零售价格
            if (!empty($retailPrice) && $retailPrice != -1) {
                $retailPriceArray = explode(',', $retailPrice);
                switch($retailPriceType){
                    case 's' :
                        $retailPriceType = 'retail_price_s_min';
                        break;
                    case 'm-1' :
                        $retailPriceType = 'retail_price_m_1_min';
                        break;
                    case 'm-2' :
                        $retailPriceType = 'retail_price_m_2_min';
                        break;
                    case 'm-3' :
                        $retailPriceType = 'retail_price_m_3_min';
                        break;
                    default :
                        $retailPriceType = 'retail_price_m_1_min';
                        break;
                }
                if($retailPriceArray[1] == 'n'){
                    $query->andWhere(['>', 'weixin.'.$retailPriceType, $retailPriceArray[0]]);
                }else{
                    $query->andWhere(['between', 'weixin.'.$retailPriceType, $retailPriceArray[0], $retailPriceArray[1]]);
                }
            }
            // 粉丝数
            if (!empty($followerNum) && $followerNum != -1) {
                $followerNumArray = explode(',', $followerNum);
                if($followerNumArray[1] == 'n'){
                    $query->andWhere(['>', 'weixin.follower_num', $followerNumArray[0]]);
                }else{
                    $query->andWhere(['between', 'weixin.follower_num', $followerNumArray[0], $followerNumArray[1]]);
                }
            }
            // 粉丝地域
            if (!empty($followerArea) && $followerArea != -1) {
                $followerAreaWhereClause = ['or'];
                $followerAreaArray = explode(',', $followerArea);
                foreach ($followerAreaArray as $area) {
                    $followerAreaWhereClause[] = ['like', 'weixin.follower_area', '#' . $area . '#'];
                }

                $query->andWhere($followerAreaWhereClause);

            }
            // 发布形式
            if (!empty($pubType) && $pubType != -1) {
                if ($pubType == 1) {
                    // 直接投放
                    $query->andWhere(['has_direct_pub' => 1]);
                } else if ($pubType == 2) {
                    // 原创约稿
                    $query->andWhere(['has_origin_pub' => 1]);
                }
            }
            // 是否主推
            if(!empty($isPush) && $isPush != -1){
                if($isPush == 1){
                    // 主推
                    $query->andWhere(['is_push' => 1]);
                }
            }

            // 按粉丝数排序
            if (!empty($sortByFollowerNum) && $sortByFollowerNum != -1) {
                if ($sortByFollowerNum == 's-desc') {
                    $query->addOrderBy(['follower_num' => SORT_DESC]);
                } else if ($sortByFollowerNum == 's-asc') {
                    $query->addOrderBy(['follower_num' => SORT_ASC]);
                }
            }
            // 按零售价(多图文)排序
            if (!empty($sortByRetailPrice) && $sortByRetailPrice != -1) {
                $queryFilterArray['sort_by_retail_price'] = $sortByRetailPrice;
                switch ($sortByRetailPrice) {
                    case 's-desc':
                        $query->andWhere(['>', 'retail_price_s_min', 0]);
                        $pubType == '-1'?'':$query->andWhere(['=', 's_pub_type', $pubType]);
                        $query->addOrderBy(['retail_price_s_min' => SORT_DESC]);
                        break;
                    case 's-asc':
                        $query->andWhere(['>', 'retail_price_s_min', 0]);
                        $pubType == '-1'?'':$query->andWhere(['=', 's_pub_type', $pubType]);
                        $query->addOrderBy(['retail_price_s_min' => SORT_ASC]);
                        break;
                    case 'm-1-desc':
                        $query->andWhere(['>', 'retail_price_m_1_min', 0]);
                        $pubType == '-1'?'':$query->andWhere(['=', 'm_1_pub_type', $pubType]);
                        $query->addOrderBy(['retail_price_m_1_min' => SORT_DESC]);
                        break;
                    case 'm-1-asc':
                        $query->andWhere(['>', 'retail_price_m_1_min', 0]);
                        $pubType == '-1'?'':$query->andWhere(['=', 'm_1_pub_type', $pubType]);
                        $query->addOrderBy(['retail_price_m_1_min' => SORT_ASC]);
                        break;
                    case 'm-2-desc':
                        $query->andWhere(['>', 'retail_price_m_2_min', 0]);
                        $pubType == '-1'?'':$query->andWhere(['=', 'm_2_pub_type', $pubType]);
                        $query->addOrderBy(['retail_price_m_2_min' => SORT_DESC]);
                        break;
                    case 'm-2-asc':
                        $query->andWhere(['>', 'retail_price_m_2_min', 0]);
                        $pubType == '-1'?'':$query->andWhere(['=', 'm_2_pub_type', $pubType]);
                        $query->addOrderBy(['retail_price_m_2_min' => SORT_ASC]);
                        break;
                    case 'm-3-desc':
                        $query->andWhere(['>', 'retail_price_m_3_min', 0]);
                        $pubType == '-1'?'':$query->andWhere(['=', 'm_3_pub_type', $pubType]);
                        $query->addOrderBy(['retail_price_m_3_min' => SORT_DESC]);
                        break;
                    case 'm-3-asc':
                        $query->andWhere(['>', 'retail_price_m_3_min', 0]);
                        $pubType == '-1'?'':$query->andWhere(['=', 'm_3_pub_type', $pubType]);
                        $query->addOrderBy(['retail_price_m_3_min' => SORT_ASC]);
                        break;
                }
            }
            // 统计
            $totalCount = $query->count();
            // 默认排序
            if(empty($sortByAvgReadNum) && empty($sortByActiveEndTime) && empty($sortByWomNum) && empty($sortByFollowerNum) && empty($sortByRetailPrice)){
                $query->addOrderBy(['active' => SORT_DESC]);
                $query->addOrderBy(['y_wmi' => SORT_DESC]);
            }
            // 按头条平均阅读数排序
            if (!empty($sortByAvgReadNum) && $sortByAvgReadNum != -1) {
                if ($sortByAvgReadNum == 's-desc') {
                    $query->addOrderBy(['head_avg_view_cnt' => SORT_DESC]);
                } else if ($sortByAvgReadNum == 's-asc') {
                    $query->addOrderBy(['head_avg_view_cnt' => SORT_ASC]);
                }
            }
            // 按价格有效日期排序
            if (!empty($sortByActiveEndTime) && $sortByActiveEndTime != -1) {
                if ($sortByActiveEndTime == 's-desc') {
                    $query->addOrderBy(['active_end_time' => SORT_DESC]);
                } else if ($sortByActiveEndTime == 's-asc') {
                    $query->addOrderBy(['active_end_time' => SORT_ASC]);
                }
            } else {
                //$query->addOrderBy(['active' => SORT_DESC]);
            }
            // 按沃米指数排序
            if (!empty($sortByWomNum) && $sortByWomNum != -1) {
                if ($sortByWomNum == 's-desc') {
                    $query->addOrderBy(['y_wmi' => SORT_DESC]);
                } else if ($sortByWomNum == 's-asc') {
                    $query->addOrderBy(['y_wmi' => SORT_ASC]);
                }
            } else {
                // 默认排序
                //$query->addOrderBy(['y_wmi' => SORT_DESC]);
            }
            // 按最近更新时间排序
            if (!empty($sortByUpdateTime) && $sortByUpdateTime != -1) {
                if ($sortByUpdateTime == 's-desc') {
                    $query->addOrderBy(['last_update_time' => SORT_DESC]);
                } else if ($sortByUpdateTime == 's-asc') {
                    $query->addOrderBy(['last_update_time' => SORT_ASC]);
                }
            }
            // 分页
            $pager = new Pagination(['totalCount' => $totalCount]);
            $pager->pageSize = 10;
            $pager->page = $page;
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $pager->pageSize,
                    'page' => $pager->page
                ]
            ]);
            $queryResult = $dataProvider->getModels();
            return $this->render('list',
                [
                    'queryResult' => $queryResult,
                    'pager' => $pager
                ]
            );
        }
        if ($request->isGet) {
            $planUUID = $request->get('plan_uuid', '');
            $search_name = $request->get('search_name', '');
            // 加载页面
            $query = (new Query())
                ->select('weixin.uuid AS media_uuid,
                          weixin.pub_config,
                          weixin.follower_num,
                          weixin.public_name,
                          weixin.public_id,
                          weixin.last_update_time,
                          weixin.active_end_time,
                          weixin.account_cert,
                          weixin.account_short_desc as desc,
                          weixin.y_head_avg_view_cnt as head_avg_view_cnt,
                          weixin.y_avg_price_pv as avg_price_pv,
                          weixin.y_wmi as wmi,
                          has_origin_pub,
                          (CASE
  WHEN weixin.active_end_time IS NULL THEN 0
  WHEN weixin.active_end_time < UNIX_TIMESTAMP(NOW())  THEN 1
  WHEN weixin.active_end_time > UNIX_TIMESTAMP(NOW())  THEN 2
  else 0 END ) as active')
                ->from(['weixin' => MediaWeixin::tableName()]);

            // =======   过滤条件  =======
            // 审核通过
            $query->andWhere(['=', 'weixin.status', 1]);
            // 上下架
            $query->andWhere(['=', 'weixin.put_up', 1]);
            // 不接单
            $query->andWhere(['or', 'weixin.s_pub_type != 0', 'weixin.m_1_pub_type != 0', 'weixin.m_2_pub_type != 0', 'weixin.m_3_pub_type != 0']);
            // 账号名称
            if (!empty($search_name)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $search_name], ['like', 'weixin.public_id', $search_name]]);
            }
            // 搜索结果总数
            $totalCount = $query->count();
            $query->addOrderBy(['active' => SORT_DESC]);
            $query->addOrderBy(['y_wmi' => SORT_DESC]);
            // 分页
            $pager = new Pagination(['totalCount' => $totalCount]);
            $pager->pageSize = 10;
            $pager->page = 0;
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $pager->pageSize,
                    'page' => $pager->page
                ]
            ]);
            $queryResult = $dataProvider->getModels();

            return $this->render('list',
                [
                    'queryResult' => $queryResult,
                    'pager' => $pager,
                    'plan_uuid' => $planUUID
                ]
            );
        }
    }

    /**
     * 微信账号详情页
     * @return string
     */
    public function actionDetail(){
        $this->layout = '//site-stage';
        $request = \Yii::$app->request;
        if ($request->isGet) {
            $media_uuid = $request->get('media_uuid');
            $query = (new Query())
            ->select('
              weixin.uuid AS media_uuid,
              weixin.pub_config,
              weixin.follower_num,
              weixin.public_name,
              weixin.public_id,
              weixin.media_cate,
              weixin.account_cert,
              weixin.account_cert_info,
              weixin.comment,
              weixin.last_update_time,
              weixin.active_end_time,
              weixin.account_short_desc as desc,
              weixin.y_head_avg_view_cnt as head_avg_view_cnt,
              weixin.y_avg_price_pv as avg_price_pv,
              weixin.m_avg_view_cnt,
              weixin.m_avg_like_cnt,
              weixin.m_head_avg_view_cnt,
              weixin.m_head_avg_like_cnt,
              weixin.m_10w_article_total_cnt,
              weixin.total_article_cnt,
              weixin.m_avg_price_pv,
              weixin.y_wmi as wmi,
              has_origin_pub,
              weixin.active_end_time,
              weixin.latest_article_post_date,
            ')
            ->from(['weixin' => MediaWeixin::tableName()]);
            $query->andWhere(['=', 'weixin.uuid', $media_uuid]);
            $mediaDetail = $query->one();

            //30天最近发布文章日期
            $last_time = strtotime("-30 day");//30天前的时间
            $mediaLastArticle = WeixinArticle::find()->where(['weixin_id'=>$mediaDetail['public_id']])
            ->andWhere(['between','post_time',$last_time,time()])
            ->orderBy(['post_time' => SORT_DESC])
            ->limit(1)->one();
            if(!empty($mediaLastArticle->post_time)){
                $last_post_time = date('Y.m.d',$mediaLastArticle->post_time);
            }else{
                $last_post_time = "/";
            }
            //微信运维数据
            $weixinStatistic = MediaWeixinStatistic::find()->where(['public_id'=>$mediaDetail['public_id']])->asArray()->one();
            $operation_status_array = json_decode($weixinStatistic['operation_status'],true);

            if(empty($operation_status_array)){
                $operation_status_array = array();
                $operation_status_array['operational_status'] = array();
            };

            return $this->render('detail',[
                'mediaDetail' => $mediaDetail,
                'mediaUuid' => $media_uuid,
                'last_post_time' => $last_post_time,
                "operation_status_array" =>$operation_status_array['operational_status'],
            ]);
        }
    }

    /**
     * 详情页月wom指数
     * @return string
     */
    public function actionGetWmi(){
        $request = \Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isPost) {
            $mediaUuid = $request->post('media_uuid');
            $mediaWeixin = MediaWeixin::findOne(['uuid'=>$mediaUuid]);
            return ['err_code'=>0,'err_msg'=>'获取成功','y_wmi'=>$mediaWeixin->y_wmi];
        }else{
            return ['err_code'=>1,'err_msg'=>'获取失败'];
        }
    }

    /**
     * 详情页图表数据
     * @return string
     */
    public function actionGetChartData(){
        $request = \Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isPost) {
            $mediaUuid = $request->post('media_uuid');
            $mediaWeixin = MediaWeixin::findOne(['uuid'=>$mediaUuid]);
            $weixinStatistic = MediaWeixinStatistic::find()->where(['public_id'=>$mediaWeixin->public_id])->asArray()->one();
            if(!empty($weixinStatistic)){
                $operation_status_array = json_decode($weixinStatistic['operation_status'],true);
                $article_effect_array = json_decode($weixinStatistic['article_effect'],true);
                $month_post_trend_arr = $operation_status_array['operational_status']['month_post_trend'];
                $month_post_time_hour_distribute_arr = $operation_status_array['operational_status']['month_post_time_hour_distribute'];
                $month_post_time_week_distribute_arr = $operation_status_array['operational_status']['month_post_time_week_distribute'];
                $month_head_article_trend_arr = $article_effect_array['month_article_effect']['month_head_article_trend'];
            }else{
                $operation_status_array = array();
                $article_effect_array = array();
                $month_post_trend_arr = array();
                $month_post_time_hour_distribute_arr = array();
                $month_post_time_week_distribute_arr = array();
                $month_head_article_trend_arr = array();
            }


            //发稿量趋势
            $month_post_trend = StatHelper::dealWeixinStatisticDate($month_post_trend_arr,30,'one');
            ksort($month_post_trend);
            $trend_array = array();
            foreach($month_post_trend as $k=>$v){
                $trend_key = date('m-d',$k);
                $trend_array[$trend_key] = $v;
            }

            $month_head_article_trend = StatHelper::dealWeixinStatisticDate($month_head_article_trend_arr,30,'two');
            ksort($month_head_article_trend);

            //头条阅读数
            $month_head_article_trend_view = array();
            foreach($month_head_article_trend as $k=>$v){
                $head_view_key = date('m-d',$k);
                $month_head_article_trend_view[$head_view_key] = $v['read_num'];
            }
//
            //头条点赞数
            $month_head_article_trend_like = array();
            foreach($month_head_article_trend as $k=>$v){
                $head_like_key = date('m-d',$k);
                $month_head_article_trend_like[$head_like_key] = $v['like_num'];
            }

            //发布时间日分布
            for($i=0 ; $i<24 ; $i++){
                if(!array_key_exists($i,$month_post_time_hour_distribute_arr)){
                    $month_post_time_hour_distribute_arr[$i] = 0;
                }
            }
            ksort($month_post_time_hour_distribute_arr);
            $month_post_time_hour_distribute = array();
            foreach($month_post_time_hour_distribute_arr as $k=>$v){
                $hour_key = $k."时";
                $month_post_time_hour_distribute[$hour_key] = $v;
            }
            //发布时间周分布
            for($i=1 ; $i<=7 ; $i++){
                if(!array_key_exists($i,$month_post_time_week_distribute_arr)){
                    $month_post_time_week_distribute_arr[$i] = 0;
                }
            }
            ksort($month_post_time_week_distribute_arr);
            $month_post_time_week_distribute = array();
            foreach($month_post_time_week_distribute_arr as $k=>$v){
                $week_key = $k;
                $month_post_time_week_distribute[$week_key] = $v;
            }

            return [
                'err_code'=>0,
                'err_msg'=>'获取成功',
                "month_post_trend"=>$trend_array,
                "month_post_time_hour_distribute"=>$month_post_time_hour_distribute,
                "month_post_time_week_distribute"=>$month_post_time_week_distribute,
                "month_head_article_trend_view"=>$month_head_article_trend_view,
                "month_head_article_trend_like"=>$month_head_article_trend_like,
            ];
        }else{
            return ['err_code'=>1,'err_msg'=>'获取失败'];
        }
    }

    /**
     * 文章数据
     * @return string
     */
    public function actionGetArticleData(){
        $request = \Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isPost) {
            $mediaUuid = $request->post('media_uuid');
            $mediaWeixin = MediaWeixin::findOne(['uuid'=>$mediaUuid]);
            $last_time = strtotime("-30 day");//30天前的时间
            //30天内最热文章
            $mediaTopArticle = WeixinArticle::find()->where(['weixin_id'=>$mediaWeixin->public_id])
            ->andWhere(['between','post_time',$last_time,time()])
            ->orderBy(['read_num' => SORT_DESC])
            ->limit(10)->asArray()->all();
            //30天内最近文章
            $mediaLastArticle = WeixinArticle::find()->where(['weixin_id'=>$mediaWeixin->public_id])
            ->andWhere(['between','post_time',$last_time,time()])
            ->orderBy(['post_time' => SORT_DESC])
            ->limit(10)->asArray()->all();
            //数据处理
            $mediaTopArticle = $this->articleDeal($mediaTopArticle);
            $mediaLastArticle = $this->articleDeal($mediaLastArticle);
            return [
                'err_code'=>0,
                'err_msg'=>'获取成功',
                "mediaTopArticle"=>$mediaTopArticle,
                "mediaLastArticle"=>$mediaLastArticle,
            ];
        }else{
            return ['err_code'=>1,'err_msg'=>'获取失败'];
        }
    }

    //文章数据处理
    function articleDeal($article_arr=array()){
        foreach($article_arr as $k=>$v){
            $article_arr[$k]['post_time'] = (!empty($v['post_time']))?date('Y-m-d H:i',$v['post_time']):"/";
            $article_arr[$k]['read_num'] = ($v['read_num']<=100000)?$v['read_num']:"10W+";
            if($v['article_type']==0){
                $article_arr[$k]['article_type'] = "单图文";
            }
            if($v['article_type']==1){
                $article_arr[$k]['article_type'] = "多图文";
            }
            switch($v['article_pos']){
                case 1:$article_arr[$k]['article_pos'] = "第一条";break;
                case 2:$article_arr[$k]['article_pos'] = "第二条";break;
                case 3:$article_arr[$k]['article_pos'] = "第三条";break;
                case 4:$article_arr[$k]['article_pos'] = "第四条";break;
                case 5:$article_arr[$k]['article_pos'] = "第五条";break;
                case 6:$article_arr[$k]['article_pos'] = "第六条";break;
                default:$article_arr[$k]['article_pos'] = "第一条";
            }

        }
        return $article_arr;
    }


    //获取购物车cookie数据
    public function actionGetShoppingCarCookie(){
        $request = \Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mediaCookies = $request->get('media_cookies');
        if(!empty($mediaCookies)){
            $cookies_array = explode(',',$mediaCookies);
            $cookies_array = array_filter($cookies_array);
            $models = $query = (new Query())
                ->select('weixin.uuid AS media_uuid,
                      weixin.pub_config,
                      weixin.follower_num,
                      weixin.public_id,
                      weixin.public_name,
                ')
                ->from(['weixin' => MediaWeixin::tableName()])->where(['uuid'=>$cookies_array])->all();

            foreach ($models as $key=>$val){
                $mediaRetailPriceArray = MediaHelper::parseMediaWeixinRetailPrice($val['pub_config']);
                $models[$key]['pos_1_retail_price'] = $mediaRetailPriceArray['m_1']['retail_price_min'];
            }
            return ['err_code'=>0,'json_array'=>$models];
        }else{
            return ['err_code'=>0,'json_array'=>array()];
        }

    }

}