<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace admin\modules\ad\controllers;

use common\helpers\PlatformHelper;
use common\models\AdVideoOrder;
use common\models\AdVideoOrderContent;
use common\models\AdVideoPlan;
use common\models\AdWeixinOrder;
use common\models\AdWeixinOrderArrangeContent;
use common\models\AdWeixinOrderArrangeOutline;
use common\models\AdWeixinOrderDirectContent;
use common\models\AdWeixinOrderPublishResult;
use common\models\AdWeixinOrderTrack;
use common\models\AdWeixinPlan;
use common\models\AdOwnerFundChangeRecord;
use Yii;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use admin\controllers\BaseAppController;
use admin\helpers\AdminHelper;
use common\models\AdOwner;
use common\models\WomAccount;
use yii\web\Response;

/**
 * Class OwnerController
 * @package admin\modules\ad\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class OwnerController extends BaseAppController
{
    /**
     * 广告主列表
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $compName = $request->post('comp-name', ''); // 广告主名称
            $loginAccount = $request->post('login-account', '');
            $status = $request->post('status', 1);
            $contact = $request->post('contact', '');// 联系方式
            $page = $request->post('page', 0);

            $query = (new Query())
                ->select(['ad_owner.uuid AS ad_owner_uuid', 'ad_owner.comp_name', 'ad_owner.uuid', 'ad_owner.contact_name', 'ad_owner.contact_1',
                    'ad_owner.contact_2', 'ad_owner.cust_credit_fund', 'account.login_account', 'account.create_time','ad_owner.last_publish_plan_time',
                    'ad_owner.total_available_topup', 'ad_owner.total_available_credit'])
                ->from(['ad_owner' => AdOwner::tableName()])
                ->leftJoin(['account' => WomAccount::tableName()], 'account.uuid = ad_owner.account_uuid')
                ->orderBy(['account.create_time' => SORT_DESC]);

            if (!empty($compName)) {
                $query->andWhere(['or', ['like', 'ad_owner.comp_name', $compName], ['like', 'ad_owner.contact_name', $compName]]);
            }
            if (!empty($loginAccount)) {
                $query->andWhere(['like', 'account.login_account', $loginAccount]);
            }
            if (isset($status)) {
                $query->andWhere(['=', 'account.status', $status]);
            }
            if (!empty($contact)) {
                $query->andWhere(['or', ['like', 'ad_owner.contact_1', $contact], ['like', 'ad_owner.contact_2', $contact]]);
            }

            //\Yii::trace($query->createCommand()->getRawSql(), 'dev\#' . __METHOD__);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);

            return $this->render('list', ['dataProvider' => $dataProvider]);
        } else {
            $query = (new Query())
                ->select(['ad_owner.uuid AS ad_owner_uuid', 'ad_owner.comp_name', 'ad_owner.contact_name', 'ad_owner.contact_1',
                    'ad_owner.contact_2', 'ad_owner.cust_credit_fund', 'account.login_account','account.create_time', 'ad_owner.last_publish_plan_time',
                    'ad_owner.total_available_topup', 'ad_owner.total_available_credit', 'account.status AS account_status'])
                ->from(['ad_owner' => AdOwner::tableName()])
                ->leftJoin(['account' => WomAccount::tableName()], 'account.uuid = ad_owner.account_uuid')
                ->orderBy(['account.create_time' => SORT_DESC]);

            //\Yii::trace($query->createCommand()->getRawSql(), 'dev\#' . __METHOD__);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);

            return $this->render('list', [
                'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $uuid = $request->post('uuid');
        // 微信plan
        $weixinPlanUUIDList = [];
        $weixinOrderUUIDList = [];
        $planList = AdWeixinPlan::findAll(['ad_owner_uuid' => $uuid]);
        foreach($planList as $plan){
            $weixinPlanUUIDList[] = $plan->uuid;
        }
        $orderList = AdWeixinOrder::findAll(['plan_uuid' => $weixinPlanUUIDList]);
        foreach($orderList as $order){
            $weixinOrderUUIDList[] = $order->uuid;
        }
        AdWeixinOrderDirectContent::deleteAll(['order_uuid' => $weixinOrderUUIDList]);
        AdWeixinOrderArrangeContent::deleteAll(['order_uuid' => $weixinOrderUUIDList]);
        AdWeixinOrderArrangeOutline::deleteAll(['order_uuid' => $weixinOrderUUIDList]);
        AdWeixinOrderTrack::deleteAll(['order_uuid' => $weixinOrderUUIDList]);
        AdWeixinOrderPublishResult::deleteAll(['order_uuid' => $weixinOrderUUIDList]);
        AdWeixinOrder::deleteAll(['order_uuid' => $weixinOrderUUIDList]);
        AdWeixinPlan::deleteAll(['uuid' => $weixinPlanUUIDList]);

        // 视频plan
        $videoPlanUUIDList = [];
        $videoOrderUUIDList = [];
        $planList = AdVideoPlan::findAll(['ad_owner_uuid' => $uuid]);
        foreach($planList as $plan){
            $videoPlanUUIDList[] = $plan->uuid;
        }
        $orderList = AdVideoOrder::findAll(['plan_uuid' => $videoPlanUUIDList]);
        foreach($orderList as $order){
            $videoOrderUUIDList[] = $order->uuid;
        }
        AdVideoOrderContent::deleteAll(['order_uuid' => $videoOrderUUIDList]);
        AdVideoOrder::findAll(['plan_uuid' => $videoPlanUUIDList]);
        AdVideoPlan::deleteAll(['uuid' => $videoPlanUUIDList]);

        AdOwner::deleteAll(['uuid' => $uuid]);

        return ['err_code' => 0, 'err_msg' => '删除成功'];
    }

    /**
     * 广告主详情
     */
    public function actionDetail()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;

        $adOwnerUUID = $request->get('ad-owner-uuid');
        $data = (new Query())
            ->select('ad_owner.comp_name, wom_account.login_account, wom_account.status AS account_status, ad_owner.contact_name, ad_owner.contact_1, ad_owner.contact_2, ad_owner.weixin, ad_owner.comp_address, ,ad_owner.comp_desc ,ad_owner.comp_website, ad_owner.total_available_topup, ad_owner.total_available_credit, ad_owner.total_frozen_topup, ad_owner.total_frozen_credit')
            ->from(['ad_owner' => AdOwner::tableName()])
            ->leftJoin(['wom_account' => WomAccount::tableName()], 'wom_account.uuid = ad_owner.account_uuid')
            ->where(['ad_owner.uuid' => $adOwnerUUID])
            ->one();

        return ['err_code' => 0, 'err_msg' => '获取成功', 'ad_owner' => $data];
    }

    /**
     * @return array
     */
    public function actionSetFundAndPayPass()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isPost){
            $uuid = $request->post('ad_owner_uuid'); // 广告主的uuid
            $topup = $request->post('topup_amount'); // 充值金额
            $credit = $request->post('credit_amount'); // 授信金额
            $password = $request->post('reset_password');// 重置支付密码

            $loginUser = self::getCurrentLoginUser();
            $accountUUID = $loginUser['account_uuid'];
            $loginAccount = $loginUser['login_account'];
            $userName = $loginUser['user_name'];

            $adOwner = AdOwner::findOne(['uuid' => $uuid]);
            if($adOwner === null){
                throw new ErrorException('ad owner not exists!');
            }

            // 充值
            if(!empty($topup)){
                $record = new AdOwnerFundChangeRecord();
                $record->uuid = PlatformHelper::getUUID();
                $record->owner_uuid = $uuid;
                $record->type = AdOwnerFundChangeRecord::TYPE_TOP_UP_OFFLINE;
                $record->amount =  $topup;
                $record->operator_uuid =  $accountUUID;
                $record->operator_name =  $userName;
                $record->comment =  'Admin后台充值';
                $record->status = AdOwnerFundChangeRecord::STATUS_SUCCESS;
                $record->complete_time = time();
                $record->save();

                $adOwner->total_available_topup += $topup;
                $adOwner->last_update_time = time();
                $adOwner->save();
            }

            // 授信
            if(!empty($credit)){
                $record = new AdOwnerFundChangeRecord();
                $record->uuid = PlatformHelper::getUUID();
                $record->owner_uuid = $uuid;
                $record->type = AdOwnerFundChangeRecord::TYPE_CREDIT;
                $record->amount =  $credit;
                $record->operator_uuid =  $accountUUID;
                $record->operator_name =  $userName;
                $record->comment =  'Admin后台直接授信';
                $record->status = AdOwnerFundChangeRecord::STATUS_SUCCESS;
                $record->complete_time = time();
                $record->save();

                $adOwner->total_available_credit += $credit;
                $adOwner->last_update_time = time();
                $adOwner->save();
            }

            // 充值支付密码
            if(!empty($password)){
                $adOwner->setPayPassword($password);
                $adOwner->last_update_time = time();
                $adOwner->save();
            }

            return ['err_code' => 0, 'err_msg' => '成功保存'];
        }
    }
}