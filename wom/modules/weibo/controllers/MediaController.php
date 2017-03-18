<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 2016/11/11 11:11
 */
namespace wom\modules\weibo\controllers;

use common\models\MediaVendor;
use common\helpers\MediaHelper;
use common\models\MediaWeibo;
use common\models\WeiboVendorBind;
use wom\controllers\BaseAppController;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Response;
use yii;

/**
 * 微博
 * Class MediaController
 * @package wom\modules\weibo\controllers
 * @author Tom Tan <tom@51wom.com>
 * @since 1.0
 */
class MediaController extends BaseAppController
{
    public $layout = '//media-stage';

    /**
     * 微博账号搜索列表页
     */
    public function actionList(){
        $request = \Yii::$app->request;
        $page = $request->post('page', 0);
        $query = (new Query())
        ->select('
             weibo.uuid,
             weibo.weibo_url,
             weibo.weibo_name,
             weibo.media_level,
             weibo.media_cate,
             weibo.follower_num,
             bind.soft_direct_price_retail as sd_price,
             bind.soft_transfer_price_retail as st_price,
             bind.micro_direct_price_retail as md_price,
             bind.micro_transfer_price_retail as mt_price,
             bind.active_end_time,
             weibo.accept_remark,
             weibo.intro,
             weibo.update_time,
        ')
        ->from(['weibo' => MediaWeibo::tableName()])
        ->leftJoin(['bind'=> WeiboVendorBind::tableName()], 'bind.weibo_uuid  =  weibo.uuid  and  bind.is_pref_vendor = 1')
        ->leftJoin(['vendor'=>MediaVendor::tableName()], 'bind.vendor_uuid  =  vendor.uuid ')
        ->andWhere(['=', 'weibo.is_put', 1])
        ->andWhere(['=', 'weibo.status', 1]);
//        ->orderBy(['weibo.is_top' => SORT_DESC,'weibo.update_time' => SORT_DESC]);
        if($request->isGet){
            $query->orderBy(['weibo.is_top' => SORT_DESC,'bind.active_end_time' => SORT_DESC,'weibo.update_time' => SORT_DESC]);
            $search_name = $request->get('search_name');
            if (!empty($search_name)){   //微博/自媒体搜索
                $query->andWhere(['or', ['like', 'weibo.weibo_name', $search_name]]);
            }
        }
        if ($request->isPost) {
            // 搜索过滤条件
            $search_name = $request->post('search_name');// 按名称搜索
            $mediaCate = $request->post("media_cate"); // 按分类搜索
            $mediaLevel = $request->post("media_level"); // 按等级搜索
            $priceType = $request->post("price_type");    //参考报价类型
            $retailPrice = $request->post("retail_price"); // 按零售价搜索
            $followerNum = $request->post("follower_num"); // 按粉丝数搜索
            $followerArea = $request->post("follower_area"); // 按粉丝区域搜索
            $sortByPrice = $request->post('sort_by_price'); // 按价格排序
            $sortByFollowerNum = $request->post('sort_by_follower_num'); // 按粉丝数量排序
            $sortByActiveEndTime = $request->post('sort_by_active_end_time'); // 按价格有效日期排序
            $sortByUpdateTime = $request->post('sort_by_update_time'); // 按更新时间排序
            $main_push = $request->post('main_push');//主推账号
            if (!empty($search_name)){   //微博/自媒体搜索
                $query->andWhere(['or', ['like', 'weibo.weibo_name', $search_name]]);
            }
            // 分类
            if (!empty($mediaCate)) {
                $cateWhereClause = ['or'];
                $mediaCateArr = explode(',', $mediaCate);
                foreach ($mediaCateArr as $cate) {
                    $cateWhereClause[] = ['like', 'media_cate', '#' . $cate . '#'];
                }
                $query->andWhere($cateWhereClause);
            }
            // 等级
            if (!empty($mediaLevel)) {
                $mediaLevelArr = explode(',', $mediaLevel);
                $query->andWhere(['in', 'media_level', $mediaLevelArr]);
            }
            //主推账号
            if (!empty($main_push) && $main_push != -1) {
                $query->andWhere(['=', 'is_push', 1]);
            }
            if ($main_push == -1) {
                $query->orderBy(['weibo.is_top' => SORT_DESC,'bind.active_end_time' => SORT_DESC,'weibo.update_time' => SORT_DESC]);
            }
            // 零售价
            if (!empty($retailPrice)) {
                $retailPriceArr = explode(',', $retailPrice);
                switch ($priceType) {
                    case 'micro':
                        $pType[0] = 'micro_direct_price_retail';
                        $pType[1] = 'micro_transfer_price_retail';
                        break;
                    case 'soft':
                        $pType[0] = 'soft_direct_price_retail';
                        $pType[1] = 'soft_transfer_price_retail';
                        break;
                    default:
                        $pType[0] = 'micro_direct_price_retail';
                        $pType[1] = 'micro_transfer_price_retail';
                        break;
                }
                if ($retailPriceArr[1] == 'n') {
                    $query->andWhere(['or', ['>=', $pType[0], $retailPriceArr[0]], ['>=', $pType[1], $retailPriceArr[0]]]);
                } else {
                    $query->andWhere(['or', ['between', $pType[0], $retailPriceArr[0], $retailPriceArr[1]], ['between', $pType[1], $retailPriceArr[0], $retailPriceArr[1]]]);
                }
            }
            // 粉丝数
            if (!empty($followerNum)) {
                $followerNumArr = explode(',', $followerNum);
                if ($followerNumArr[1] == 'n') {
                    $query->andWhere(['>=', 'follower_num', $followerNumArr[0]]);
                } else {
                    $query->andWhere(['between', 'follower_num', $followerNumArr[0], $followerNumArr[1]]);
                }
            }
            // 粉丝区域
            if (!empty($followerArea)) {
                $areaWhereClause = ['or'];
                $followerAreaArr = explode(',', $followerArea);
                foreach ($followerAreaArr as $area) {
                    $areaWhereClause[] = ['like', 'follower_area', '#' . $area . '#'];
                }
                $query->andWhere($areaWhereClause);
            }
            // 按零售价排序
            if (!empty($sortByPrice)) {
                if ($sortByPrice == 'md-desc') {
                    $query->OrderBy(['bind.micro_direct_price_retail' => SORT_DESC]);
                } else if ($sortByPrice == 'md-asc') {
                    $query->OrderBy(['bind.micro_direct_price_retail' => SORT_ASC]);
                } else if ($sortByPrice == 'mt-desc') {
                    $query->OrderBy(['bind.micro_transfer_price_retail' => SORT_DESC]);
                } else if ($sortByPrice == 'mt-asc') {
                    $query->OrderBy(['bind.micro_transfer_price_retail' => SORT_ASC]);
                } else if ($sortByPrice == 'sd-desc') {
                    $query->OrderBy(['bind.soft_direct_price_retail' => SORT_DESC]);
                } else if ($sortByPrice == 'sd-asc') {
                    $query->OrderBy(['bind.soft_direct_price_retail' => SORT_ASC]);
                } else if ($sortByPrice == 'st-desc') {
                    $query->OrderBy(['bind.soft_transfer_price_retail' => SORT_DESC]);
                } else if ($sortByPrice == 'st-asc') {
                    $query->OrderBy(['bind.soft_transfer_price_retail' => SORT_ASC]);
                }
            }
            // 按粉丝数排序
            if (!empty($sortByFollowerNum)) {
                if ($sortByFollowerNum == 'sort-desc') {
                    $query->OrderBy(['weibo.follower_num' => SORT_DESC]);
                } else if ($sortByFollowerNum == 'sort-asc') {
                    $query->OrderBy(['weibo.follower_num' => SORT_ASC]);
                }
            }
            // 按价格有效日期排序
            if (!empty($sortByActiveEndTime)) {
                if ($sortByActiveEndTime == 'sort-desc') {
                    $query->OrderBy(['bind.active_end_time' => SORT_DESC]);
                } else if ($sortByActiveEndTime == 'sort-asc') {
                    $query->OrderBy(['bind.active_end_time' => SORT_ASC]);
                }
            }
            // 按更新时间排序
            if (!empty($sortByUpdateTime)) {
                if ($sortByUpdateTime == 'sort-desc') {
                    $query->OrderBy(['weibo.update_time' => SORT_DESC]);
                } else if ($sortByUpdateTime == 'sort-asc') {
                    $query->OrderBy(['weibo.update_time' => SORT_ASC]);
                }
            }
        }
        // 分页
        $pager = new Pagination(['totalCount' => $query->count()]);
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


    //获取购物车cookie数据
    public function actionGetShoppingCarCookie(){
        $request = \Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mediaCookies = $request->post('media_cookies');
        if(!empty($mediaCookies)){
            $cookies_array = explode(',',$mediaCookies);
            $cookies_array = array_filter($cookies_array);
            $models =  $query = (new Query())
                ->select('
                 weibo.uuid,
                 weibo.weibo_url,
                 weibo.weibo_name,
                 weibo.media_level,
                 weibo.media_cate,
                 weibo.follower_num,
                 bind.micro_transfer_price_retail as mt_price,
                 bind.active_end_time,
                 weibo.accept_remark,
                 weibo.intro,
                 weibo.update_time,
                ')
                ->from(['weibo' => MediaWeibo::tableName()])
                ->leftJoin(['bind'=> WeiboVendorBind::tableName()], 'bind.weibo_uuid  =  weibo.uuid  and  bind.is_pref_vendor = 1')
                ->leftJoin(['vendor'=>MediaVendor::tableName()], 'bind.vendor_uuid  =  vendor.uuid ')
                ->andWhere(['=', 'weibo.is_put', 1])
                ->andWhere(['=', 'weibo.status', 1])
                ->andWhere(['weibo.uuid'=>$cookies_array])->all();
            //数据处理
            foreach ($models as $key=>$val){
                $mediaCate = MediaHelper::parseMediaCate($val['media_cate']);
                $mediaCate = json_decode($mediaCate,true);
                $models[$key]['cate_array'] = $mediaCate;
            }
            return ['err_code'=>0,'json_array'=>$models];
        }else{
            return ['err_code'=>0,'json_array'=>array()];
        }

    }

    /**
     * 微博详情页
     */
    public function actionDetail()
    {
        return $this->render('detail');
    }
}