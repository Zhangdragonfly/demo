<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace admin\modules\ad\controllers;

use admin\helpers\AdminHelper;
use common\models\AdOwner;
use common\models\AdOwnerCreditFundDetailRecord;
use common\models\AdOwnerFundChangeRecord;
use common\models\AdWeixinPlan;
use frontend\helpers\SiteHelper;
use Yii;
use admin\controllers\BaseAppController;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Exception;
use yii\db\Query;
use yii\web\Response;

/**
 * Class CreditController
 * @package admin\modules\ad\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class CreditController extends BaseAppController
{
    /**
     * 授信申请 列表
     * @return string
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $adOwner = $request->post("ad-owner");
            $status = $request->post("status", -1);
            $page = $request->post('page', 0);

            $query = (new Query())
                ->select(['fund_change_record.uuid AS record_uuid', 'fund_change_record.owner_uuid', 'fund_change_record.plan_uuid', 'fund_change_record.type AS record_type', 'fund_change_record.amount AS credit_amount', 'fund_change_record.operator_name', 'fund_change_record.create_time AS record_create_time', 'fund_change_record.status AS record_status', 'fund_change_record.complete_time AS record_complete_time', 'fund_change_record.comment',
                    'ad_weixin_plan.name AS plan_name',
                    'ad_owner.contact_name AS ad_owner_contact_name', 'ad_owner.contact_1 AS ad_owner_contact', 'ad_owner.comp_name'])
                ->from(['fund_change_record' => AdOwnerFundChangeRecord::tableName()])
                ->leftJoin(['ad_owner' => AdOwner::tableName()], 'fund_change_record.owner_uuid = ad_owner.uuid')
                ->leftJoin(['ad_weixin_plan' => AdWeixinPlan::tableName()], 'fund_change_record.plan_uuid = ad_weixin_plan.uuid')
                ->where(['type' => AdOwnerFundChangeRecord::TYPE_CREDIT])
                ->orderBy(['fund_change_record.create_time' => SORT_DESC]);

            // 广告主 或 联系人
            if (!empty($adOwner)) {
                $query->andWhere(['or', ['like', 'ad_owner.contact_name', $adOwner], ['like', 'ad_owner.comp_name', $adOwner]]);
            }

            // 授信状态
            if (isset($status) && $status > -1) {
                $query->andWhere(['fund_change_record.status' => $status]);
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
        } else {
            $query = (new Query())
                ->select(['fund_change_record.uuid AS record_uuid', 'fund_change_record.owner_uuid', 'fund_change_record.plan_uuid', 'fund_change_record.type AS record_type', 'fund_change_record.amount AS credit_amount', 'fund_change_record.operator_name', 'fund_change_record.create_time AS record_create_time', 'fund_change_record.status AS record_status', 'fund_change_record.complete_time AS record_complete_time', 'fund_change_record.comment',
                    'ad_weixin_plan.name AS plan_name',
                    'ad_owner.contact_name AS ad_owner_contact_name', 'ad_owner.contact_1 AS ad_owner_contact', 'ad_owner.comp_name'])
                ->from(['fund_change_record' => AdOwnerFundChangeRecord::tableName()])
                ->leftJoin(['ad_owner' => AdOwner::tableName()], 'fund_change_record.owner_uuid = ad_owner.uuid')
                ->leftJoin(['ad_weixin_plan' => AdWeixinPlan::tableName()], 'fund_change_record.plan_uuid = ad_weixin_plan.uuid')
                ->where(['fund_change_record.type' => AdOwnerFundChangeRecord::TYPE_CREDIT])
                ->orderBy(['fund_change_record.create_time' => SORT_DESC]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);

            return $this->render('list', [
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * 允许授信
     */
    public function actionAllowCredit()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        if ($request->isPost) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                // 改变充值记录状态
                $uuid = $request->post("uuid");
                $operatorUuid = Yii::$app->user->identity['uuid'];
                $operatorName = Yii::$app->user->identity['user_name'];
                $fundChangeRecord = AdOwnerFundChangeRecord::findOne(['uuid' => $uuid]);
                $fundChangeRecord->status = AdOwnerFundChangeRecord::STATUS_SUCCESS;
                $fundChangeRecord->operator_uuid = $operatorUuid;
                $fundChangeRecord->operator_name = $operatorName;
                $fundChangeRecord->complete_time = time();
                $fundChangeRecord->save();

                // 增加广告主授信可用金额
                $adOwner = AdOwner::findOne(['uuid' => $fundChangeRecord['owner_uuid']]);
                $adOwner->total_available_credit =
                    $adOwner['total_available_credit'] + $fundChangeRecord['amount'];
                if ($adOwner->cust_credit_fund == AdOwner::CUST_CREDIT_FUND_NOT) {
                    $adOwner->cust_credit_fund = AdOwner::CUST_CREDIT_FUND_ALREADY;
                }
                $adOwner->save();

                // 添加授信记录
                $plan = AdWeixinPlan::findOne(['uuid' => $fundChangeRecord['plan_uuid']]);
                $creditFundDetailRecord = new AdOwnerCreditFundDetailRecord();
                $creditFundDetailRecord->uuid = time();
                $creditFundDetailRecord->owner_uuid = $adOwner['uuid'];
                $creditFundDetailRecord->owner_name = $adOwner['comp_name'];
                $creditFundDetailRecord->ad_plan_uuid = $plan['uuid'];
                $creditFundDetailRecord->ad_plan_name = $plan['name'];
                $creditFundDetailRecord->amount = $fundChangeRecord['amount'];
                $creditFundDetailRecord->apply_time = $fundChangeRecord['create_time'];
                $creditFundDetailRecord->apply_person = $adOwner['contact_name'];
                $creditFundDetailRecord->complete_time = $fundChangeRecord['complete_time'];
                $creditFundDetailRecord->oper_person = $operatorName;
                $creditFundDetailRecord->status = AdOwnerCreditFundDetailRecord::STATUS_SUCCESS;

                $creditFundDetailRecord->save();

                $transaction->commit();

                return ['err_code' => 0, 'err_msg' => '授信成功'];
            } catch (Exception $e) {
                $transaction->rollBack();
                return ['err_code' => 1, 'err_msg' => '授信失败'];
            }
        }

    }

    /**
     * 拒绝授信
     */
    public function actionRefuseCredit()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        if ($request->isPost) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                // 改变充值记录状态
                $uuid = $request->post("uuid");
                $operatorUuid = Yii::$app->user->identity['uuid'];
                $operatorName = Yii::$app->user->identity['user_name'];
                $fundChangeRecord = AdOwnerFundChangeRecord::findOne(['uuid' => $uuid]);
                $fundChangeRecord->status = AdOwnerFundChangeRecord::STATUS_CANCEL;
                $fundChangeRecord->operator_uuid = $operatorUuid;
                $fundChangeRecord->operator_name = $operatorName;
                $fundChangeRecord->complete_time = time();

                $fundChangeRecord->save();

                $adOwner = AdOwner::findOne(['uuid' => $fundChangeRecord['owner_uuid']]);

                // 添加授信记录
                $plan = AdWeixinPlan::findOne(['uuid' => $fundChangeRecord['plan_uuid']]);
                $creditFundDetailRecord = new AdOwnerCreditFundDetailRecord();
                $creditFundDetailRecord->uuid = time();
                $creditFundDetailRecord->owner_uuid = $adOwner['uuid'];
                $creditFundDetailRecord->owner_name = $adOwner['comp_name'];
                $creditFundDetailRecord->ad_plan_uuid = $plan['uuid'];
                $creditFundDetailRecord->ad_plan_name = $plan['name'];
                $creditFundDetailRecord->amount = $fundChangeRecord['amount'];
                $creditFundDetailRecord->apply_time = $fundChangeRecord['create_time'];
                $creditFundDetailRecord->apply_person = $adOwner['contact_name'];
                $creditFundDetailRecord->complete_time = $fundChangeRecord['complete_time'];
                $creditFundDetailRecord->oper_person = $operatorName;
                $creditFundDetailRecord->status = AdOwnerCreditFundDetailRecord::STATUS_CANCEL;

                $creditFundDetailRecord->save();

                $transaction->commit();

                return ['err_code' => 0, 'err_msg' => '拒绝授信成功'];
            } catch (Exception $e) {
                $transaction->rollBack();
                return ['err_code' => 1, 'err_msg' => '授信拒绝失败'];
            }
        }

    }

}