<?php

/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/8/16 9:07 AM
 */

namespace wom\modules\adOwner\controllers;

use common\models\AdOwner;
use common\models\AdOwnerFundChangeRecord;
use common\models\AdOwnerAllTradeRecord;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use Yii;

/**
 * Class AdminFinManageController
 * @package wom\modules\adOwner\controllers
 */
class AdminFinManageController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 全部流水
     */
    public function actionWeixinTradeList(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        $query = (new Query())
        ->select([
            'trade.uuid',
            'trade.type',
            'trade.fin_type',
            'trade.owner_uuid',
            'trade.create_time',
            'trade.amount',
            'trade.comment',
        ])
        ->from(['trade' => AdOwnerAllTradeRecord::tableName()])
        ->andWhere(['=','trade.owner_uuid',$ad_owner_uuid])
        ->orderBy(['trade.create_time' => SORT_DESC]);
        if ($request->isGet) {
            $type = $request->get('type');
            if($type==2){//消费记录（扣款）
                $query->andWhere(['trade.type'=>3]);
            }
        }
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $fin_type = $request->post('fin_type');
            $trade_type = $request->post('trade_type');
            $trade_start_time = strtotime($request->post('trade_start_time'));
            $trade_end_time = strtotime($request->post('trade_end_time'));
            if ($fin_type != -1){
                $query->andWhere(['trade.fin_type'=>$fin_type]);
            }
            if ($trade_type != -1){
                $query->andWhere(['trade.type'=>$trade_type]);
            }
            if (!empty($trade_start_time) && !empty($trade_end_time)){
                $query->andWhere(['between','trade.create_time',$trade_start_time,$trade_end_time]);
            }elseif(!empty($trade_start_time)){
                $query->andWhere(['>=','trade.create_time',$trade_start_time]);
            }elseif(!empty($trade_end_time)){
                $query->andWhere(['<=','trade.create_time',$trade_end_time]);
            }
        }
        $pager = new Pagination(['totalCount' => $query->count()]);
        $pager->pageSize = 10;
        $pager->page = $page;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' =>  $pager->pageSize,
                'page' =>  $pager->page
            ]
        ]);
        $ad_owner = AdOwner::findOne(['uuid' => $ad_owner_uuid]);
        return $this->render('weixin-trade-list', [
            'dataProvider' =>  $dataProvider->getModels(),
            'pager' => $pager,
            'ad_owner' => $ad_owner
        ]);
    }


    /**
     * @return string
     * 充值记录表
     */
    public function actionFundIncomeList(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        $query = (new Query())
        ->select([
            'fund.uuid',
            'fund.complete_time',
            'fund.type',
            'fund.amount',
            'fund.status',
            'fund.comment',
        ])
        ->from(['fund' => AdOwnerFundChangeRecord::tableName()])
        ->andWhere(['=','fund.owner_uuid',$ad_owner_uuid])
        ->orderBy(['fund.create_time' => SORT_DESC]);
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $income_type = $request->post('income_type');
            $income_start_time = strtotime($request->post('income_start_time'));
            $income_end_time = strtotime($request->post('income_end_time'));
            if ($income_type != -1){
                $query->andWhere(['fund.type'=>$income_type]);
            }
            if (!empty($income_start_time) && !empty($income_end_time)){
                $query->andWhere(['between','fund.complete_time',$income_start_time,$income_end_time]);
            }elseif(!empty($income_start_time)){
                $query->andWhere(['>=','fund.complete_time',$income_start_time]);
            }elseif(!empty($income_end_time)){
                $query->andWhere(['<=','fund.complete_time',$income_end_time]);
            }
        }
        $pager = new Pagination(['totalCount' => $query->count()]);
        $pager->pageSize = 10;
        $pager->page = $page;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' =>  $pager->pageSize,
                'page' =>  $pager->page
            ]
        ]);
        $ad_owner = AdOwner::findOne(['uuid' => $ad_owner_uuid]);
        return $this->render('fund-income-list', [
            'dataProvider' =>  $dataProvider->getModels(),
            'pager' => $pager,
            'ad_owner' => $ad_owner
        ]);
    }

    /**
     * 充值页面
     */
    public function actionTopUp()
    {
        $this->layout = '//site-stage';
        return $this->render('top-up');
    }

    /**
     * 付款成功
     */
    public function actionPaySuccess()
    {
        $this->layout = '//site-stage';
        return $this->render('pay-success');
    }

    /**
     * 线下支付提示页面
     */
    public function actionPayOffline()
    {
        $this->layout = '//site-stage';
        return $this->render('pay-offline');
    }

}
