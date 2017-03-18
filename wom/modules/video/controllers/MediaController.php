<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 9/23/16 10:32 AM
 */
namespace wom\modules\video\controllers;

use wom\controllers\BaseAppController;
use common\models\VideoPlatformCommonInfo;
use common\models\VendorVideoBind;
use common\models\VideoVendorPrice;
use common\models\MediaVideo;
use common\models\MediaVendor;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii;
use yii\web\Response;


/**
 * Class MediaController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaController extends BaseAppController
{
    public $layout = '//media-stage';

    /**
     * 视频账号搜索列表页
     * @return string
     */
    public function actionList(){
        $request = Yii::$app->request;
        $page = $request->post('page',0);//默认首页
        $query = (new Query())
        ->select([
            'video.uuid as video_uuid',
            'video.nickname',
            'video.media_cate',
            'video.address',
            'video.sex',
            'video.main_platform',
            'platform.uuid as platform_uuid',
            'platform.platform_type',
            'platform.account_name',
            'platform.account_id',
            'platform.follower_num',
            'platform.avg_watch_num',
            'platform.url',
            'platform.status',
            'platform.is_put',
            'platform.is_top',
            'platform.is_push',
            'platform.create_time',
            'platform.update_time',
            'platform.avatar',
            'bind.active_end_time',
            'price.price_config',
            'price.price_retail_one',
            'price.price_retail_two',
            'vendor.name',
        ])
        ->from(['video' => MediaVideo::tableName()])
        ->leftJoin(['platform' => VideoPlatformCommonInfo::tableName()],'video.uuid = platform.video_uuid ')
        ->leftJoin(['bind' => VendorVideoBind::tableName()],'video.uuid = bind.video_uuid and bind.is_pref_vendor = 1')
        ->leftJoin(['vendor' => MediaVendor::tableName()],'vendor.uuid = bind.vendor_uuid')
        ->leftJoin(['price' => VideoVendorPrice::tableName()],'bind.uuid = price.vendor_bind_uuid and platform.uuid = price.platform_uuid')
        ->andWhere(['=', 'platform.is_put', 1])//已上架
        ->andWhere(['=', 'platform.status', 1]);//已审核
        //查询条件
        if($request->isGet){
            $query->orderBy(['platform.is_top' => SORT_DESC,'platform.avg_watch_num'=>SORT_DESC,'platform.follower_num'=>SORT_DESC]);
            $search_name = $request->get('search_name');
            if (!empty($search_name)){   //微博/自媒体搜索
                $query->andWhere(['or', ['like', 'video.nickname', $search_name], ['like', 'platform.account_name', $search_name], ['like', 'platform.account_id', $search_name]]);
            }
        }
        if($request->isPost){
            $search_name = $request->post('search_name');
            $media_cate = $request->post('media_cate');
            $follower_area = $request->post('follower_area');
            $platform_type = $request->post('platform_type');
            $sex = $request->post('sex');
            $price = $request->post('price');
            $follower_num = $request->post('follower_num');
            $main_push = $request->post('main_push');
            $sort_by_follower_num = $request->post('sort_by_follower_num');
            $sort_by_price = $request->post('sort_by_price');
            $sort_by_avg_watch_num = $request->post('sort_by_avg_watch_num');
            if (!empty($search_name)){   //微博/自媒体搜索
                $query->andWhere(['or', ['like', 'video.nickname', $search_name], ['like', 'platform.account_name', $search_name], ['like', 'platform.account_id', $search_name]]);
            }
            // 资源标签
            if (!empty($media_cate) && $media_cate != -1) {
                $tagWhereClause = ['or'];
                $mediaCateArray = explode(',', $media_cate);
                foreach ($mediaCateArray as $tag) {
                    $tagWhereClause[] = ['like', 'video.media_cate', '#' . $tag . '#'];
                }
                $query->andWhere($tagWhereClause);
            }
            // 粉丝地域
            if (!empty($follower_area) && $follower_area != -1) {
                $followerAreaWhereClause = ['or'];
                $followerAreaArray = explode(',', $follower_area);
                foreach ($followerAreaArray as $area) {
                    $followerAreaWhereClause[] = ['like', 'video.address', '#' . $area . '#'];
                }
                $query->andWhere($followerAreaWhereClause);
            }
            //平台类型
            if (!empty($platform_type) && $platform_type != -1) {
                $platformWhereClause = ['or'];
                $platformArray = explode(',', $platform_type);
                foreach ($platformArray as $type) {
                    $platformWhereClause[] = ['=', 'platform.platform_type',$type];
                }
                $query->andWhere($platformWhereClause);
            }
            //性别
            if (!empty($sex) && $sex != -1) {
                $sexWhereClause = ['or'];
                $sexArray = explode(',', $sex);
                foreach ($sexArray as $type) {
                    $sexWhereClause[] = ['=', 'video.sex',$type];
                }
                $query->andWhere($sexWhereClause);
            }
            //参考报价
            if (!empty($price) && $price != -1) {
                $priceArray = explode(',', $price);
                if($priceArray[1] =='n'){//参考报价10万加
                    $query->andWhere(['or',['>=','price.price_retail_one', $priceArray[0]],['>=','price.price_retail_two', $priceArray[0]]]);
                }else{
                    $query->andWhere(['or',['between', 'price.price_retail_one', $priceArray[0], $priceArray[1]],['between', 'price.price_retail_two', $priceArray[0], $priceArray[1]]]);
                }
            }
            //粉丝数
            if (!empty($follower_num) && $follower_num != -1) {
                $followerNumArray = explode(',', $follower_num);
                if($followerNumArray[1] =='n'){//粉丝数10万加
                    $query->andWhere(['>=', 'platform.follower_num', $followerNumArray[0]]);
                }else{
                    $query->andWhere(['between', 'platform.follower_num', $followerNumArray[0], $followerNumArray[1]]);
                }
            }
            //主推账号
            if (!empty($main_push) && $main_push != -1) {
                $query->andWhere(['=', 'platform.is_push', 1]);
            }
            if ($main_push == -1) {
                $query->orderBy(['platform.is_top' => SORT_DESC,'platform.avg_watch_num'=>SORT_DESC,'platform.follower_num'=>SORT_DESC]);
            }
            //按粉丝数排序
            if (!empty($sort_by_follower_num) && $sort_by_follower_num != -1) {
                if ($sort_by_follower_num == 'sort_desc') {
                    $query->addOrderBy(['platform.follower_num' => SORT_DESC]);
                } else if ($sort_by_follower_num == 'sort_asc') {
                    $query->addOrderBy(['platform.follower_num' => SORT_ASC]);
                }
            }
            //按参考报价排序
            if (!empty($sort_by_price) && $sort_by_price != -1) {
                if ($sort_by_price == 'sort_desc') {
                    $query->addOrderBy(['price.price_retail_two' => SORT_DESC]);
                } else if ($sort_by_price == 'sort_asc') {
                    $query->addOrderBy(['price.price_retail_two' => SORT_ASC]);
                }
            }
            //按平均观看人数排序
            if (!empty($sort_by_avg_watch_num) && $sort_by_avg_watch_num != -1) {
                if ($sort_by_avg_watch_num == 'sort_desc') {
                    $query->addOrderBy(['platform.avg_watch_num' => SORT_DESC]);
                } else if ($sort_by_avg_watch_num == 'sort_asc') {
                    $query->addOrderBy(['platform.avg_watch_num' => SORT_ASC]);
                }
            }
        }

        $totalCount = $query->count();
        //分页
        $pager = new Pagination(['totalCount' => $totalCount]);
        $pager->pageSize = 10;
        $pager->page = $page;

        //查询数据结果
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pager->pageSize,
                'page' => $pager->page
            ]
        ]);

        return $this->render('list', [
            'queryResult' => $dataProvider->getModels(),
            'pager' => $pager,
        ]);
    }

    //获取购物车cookie数据
    public function actionGetShoppingCarCookie(){
        $request = \Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mediaCookies = $request->post('media_cookies');
        if(!empty($mediaCookies)){
            $cookies_array = explode(',',$mediaCookies);
            $cookies_array = array_filter($cookies_array);
            $models = $query = (new Query())
            ->select([
                'video.uuid as video_uuid',
                'video.nickname',
                'video.media_cate',
                'video.address',
                'video.sex',
                'video.main_platform',
                'platform.uuid as platform_uuid',
                'platform.platform_type',
                'platform.account_name',
                'platform.account_id',
                'platform.follower_num',
                'platform.avg_watch_num',
                'platform.url',
                'platform.status',
                'platform.is_put',
                'platform.is_top',
                'platform.is_push',
                'platform.create_time',
                'platform.update_time',
                'platform.avatar',
                'bind.active_end_time',
                'price.price_config',
                'price.price_retail_one',
                'price.price_retail_two',
                'vendor.name',
            ])
            ->from(['video' => MediaVideo::tableName()])
            ->leftJoin(['platform' => VideoPlatformCommonInfo::tableName()],'video.uuid = platform.video_uuid ')
            ->leftJoin(['bind' => VendorVideoBind::tableName()],'video.uuid = bind.video_uuid and bind.is_pref_vendor = 1')
            ->leftJoin(['vendor' => MediaVendor::tableName()],'vendor.uuid = bind.vendor_uuid')
            ->leftJoin(['price' => VideoVendorPrice::tableName()],'bind.uuid = price.vendor_bind_uuid and platform.uuid = price.platform_uuid')
            ->andWhere(['=', 'platform.is_put', 1])//已上架
            ->andWhere(['=', 'platform.status', 1])//已审核
            ->andWhere(['platform.uuid'=>$cookies_array])->all();

            return ['err_code'=>0,'json_array'=>$models];
        }else{
            return ['err_code'=>0,'json_array'=>array()];
        }

    }

}