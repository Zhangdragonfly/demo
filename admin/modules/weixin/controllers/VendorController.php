<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
namespace admin\modules\weixin\controllers;

use admin\controllers\BaseAppController;
use common\helpers\PlatformHelper;
use common\models\MediaVendor;
use common\models\MediaVendorWeixinPriceList;
use common\models\MediaWeixin;
use common\models\WomAccount;
use common\models\MediaVendorBind;
use common\models\MediaWeixinPriceRecord;
use Composer\Package\Loader\ValidatingArrayLoader;
use Yii;
use common\models\AccountMediaVendor;
use admin\models\Account;


use yii\db\Query;
use yii\web\Response;
use yii\db\ActiveRecord;

/**
 * 媒体供应商管理
 * Class VendorController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class VendorController extends BaseAppController
{
    /**
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 获取供应商列表
     */
    public function actionGetListOfMedia()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mediaUuid = $request->get('media_uuid');
        $mediaType = $request->get('media_type');
        $vendorList = (new Query())
            ->select('account.login_account, vendor.name, vendor.contact_person, vendor.contact1, media_vendor_bind.status bind_status, media_vendor_bind.is_activated, media_vendor_bind.uuid media_vendor_bind_uuid')
            ->from(['media_vendor_bind' => MediaVendorBind::tableName()])
            ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  media_vendor_bind.vendor_uuid')
            ->leftJoin(['account' => WomAccount::tableName()], 'account.uuid  =  vendor.account_uuid')
            ->where(['media_vendor_bind.media_uuid' => $mediaUuid, 'media_vendor_bind.media_type' => $mediaType])
            ->orderBy(['media_vendor_bind.status' => SORT_ASC])
            ->all();

        return ['err_code' => 0, 'err_msg' => '获取成功', 'vendor_list' => $vendorList];
    }

    /**
     * 获取媒体主信息
     */
    public function actionGetInfo()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;

        $bindUuid = $request->get('bind_uuid'); // media vendor bind uuid
        $vendorUuid = $request->get('vendor_uuid'); // vendor_uuid


        if(!empty($bindUuid)){
            $query = (new Query())
                ->select([
                    'vendor.name AS vendor_name',
                    'media_vendor_bind.uuid',
                    'vendor.contact_person',
                    'vendor.contact1',
                    'vendor.contact2',
                    'media_vendor_bind.status bind_status',
                    'price_list.orig_price_s_min', 'price_list.orig_price_s_max', 'price_list.orig_price_m_1_min', 'price_list.orig_price_m_1_max', 'price_list.orig_price_m_2_min', 'price_list.orig_price_m_2_max', 'price_list.orig_price_m_3_min', 'price_list.orig_price_m_3_max', 'price_list.pub_config', 'price_list.s_pub_type', 'price_list.m_1_pub_type', 'price_list.m_2_pub_type', 'price_list.m_3_pub_type', 'price_list.deposit_percent_config', 'price_list.serve_percent_config', 'media_vendor_bind.coop_level', 'media_vendor_bind.pay_period', 'media_vendor_bind.is_pref_vendor', 'media_vendor_bind.media_ownership', 'price_list.active_end_time'])
                ->from(['media_vendor_bind' => MediaVendorBind::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  media_vendor_bind.vendor_uuid')
                ->leftJoin(['price_list' => MediaVendorWeixinPriceList::tableName()], 'price_list.bind_uuid  =  media_vendor_bind.uuid')
                ->where(['media_vendor_bind.uuid' => $bindUuid]);

        }
        if(!empty($vendorUuid)){
            $query = (new Query())
                ->select(['vendor.active_end_time'])
                ->from(['vendor' => MediaVendor::tableName()])
                ->where(['vendor.uuid' => $vendorUuid]);
        }
        $vendor = $query->one();
        if ($vendor['active_end_time'] == -1 || empty($vendor['active_end_time'])) {
            $vendor['active_end_time'] = '';
        } else {
            $vendor['active_end_time'] = date("Y-m-d", $vendor['active_end_time']);
        }
        return ['err_code' => 0, 'err_msg' => '获取成功', 'vendor' => $vendor, 'global_serve_percent' => PlatformHelper::getGlobalServePercent(), 'global_deposit_percent' => PlatformHelper::getGlobalDepositPercent()];
    }

    /**
     * 审核供应商
     */
    public function actionVerify()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isPost) {
            //$userinfo = $this->getCurrentLoginUser();//账号信息
            $bindUUID = $request->post('bind_uuid');
            $pubConfig = $request->post('pub_config');
            $depositPercentConfig = $request->post('deposit_percent_config');
            $servePercentConfig = $request->post('serve_percent_config');
            $vendorBindStatus = $request->post('vendor_bind_status');
            $payPeriod = $request->post('pay_period');
            $coopLevel = $request->post('coop_level');
            $isPrefVendor = $request->post('is_pref_vendor');
            $mediaOwnership = $request->post('media_ownership');
            $activeEndTime = $request->post('active_end_time', '');

            if ($activeEndTime == '') {
                $activeEndTime = -1; // 不限时间
            } else {
                $activeEndTime = strtotime($activeEndTime);
            }

            $pubConfigArray = json_decode($pubConfig, true);
            $pos_s_pub_config = $pubConfigArray['pos_s'];
            $pos_m_1_pub_config = $pubConfigArray['pos_m_1'];
            $pos_m_2_pub_config = $pubConfigArray['pos_m_2'];
            $pos_m_3_pub_config = $pubConfigArray['pos_m_3'];

            $priceList = MediaVendorWeixinPriceList::find()->where(['bind_uuid' => $bindUUID])->one();
            if (isset($priceList)) {
                $priceList->active_end_time = $activeEndTime;
                $priceList->deposit_percent_config = $depositPercentConfig;
                $priceList->serve_percent_config = $servePercentConfig;

                $priceList->pub_config = $pubConfig;
                $priceList->s_pub_type = $pos_s_pub_config['pub_type'];
                $priceList->orig_price_s_min = $pos_s_pub_config['orig_price_min'];
                $priceList->orig_price_s_max = $pos_s_pub_config['orig_price_max'];
                $priceList->retail_price_s_min = $pos_s_pub_config['retail_price_min'];
                $priceList->retail_price_s_max = $pos_s_pub_config['retail_price_max'];
                $priceList->execute_price_s = $pos_s_pub_config['execute_price'];

                $priceList->m_1_pub_type = $pos_m_1_pub_config['pub_type'];
                $priceList->orig_price_m_1_min = $pos_m_1_pub_config['orig_price_min'];
                $priceList->orig_price_m_1_max = $pos_m_1_pub_config['orig_price_max'];
                $priceList->retail_price_m_1_min = $pos_m_1_pub_config['retail_price_min'];
                $priceList->retail_price_m_1_max = $pos_m_1_pub_config['retail_price_max'];
                $priceList->execute_price_m_1 = $pos_m_1_pub_config['execute_price'];

                $priceList->m_2_pub_type = $pos_m_2_pub_config['pub_type'];
                $priceList->orig_price_m_2_min = $pos_m_2_pub_config['orig_price_min'];
                $priceList->orig_price_m_2_max = $pos_m_2_pub_config['orig_price_max'];
                $priceList->retail_price_m_2_min = $pos_m_2_pub_config['retail_price_min'];
                $priceList->retail_price_m_2_max = $pos_m_2_pub_config['retail_price_max'];
                $priceList->execute_price_m_2 = $pos_m_2_pub_config['execute_price'];

                $priceList->m_3_pub_type = $pos_m_3_pub_config['pub_type'];
                $priceList->orig_price_m_3_min = $pos_m_3_pub_config['orig_price_min'];
                $priceList->orig_price_m_3_max = $pos_m_3_pub_config['orig_price_max'];
                $priceList->retail_price_m_3_min = $pos_m_3_pub_config['retail_price_min'];
                $priceList->retail_price_m_3_max = $pos_m_3_pub_config['retail_price_max'];
                $priceList->execute_price_m_3 = $pos_m_3_pub_config['execute_price'];

                $priceList->save();
            } else {
                //Yii::trace('price list is null', 'dev\#' . __METHOD__);
                $priceList = new MediaVendorWeixinPriceList();
                $priceList->uuid = PlatformHelper::getUUID();
                $priceList->bind_uuid = $bindUUID;
                $priceList->active_end_time = $activeEndTime;
                $priceList->deposit_percent_config = $depositPercentConfig;
                $priceList->serve_percent_config = $servePercentConfig;

                $priceList->pub_config = $pubConfig;
                $priceList->s_pub_type = $pos_s_pub_config['pub_type'];
                $priceList->orig_price_s_min = $pos_s_pub_config['orig_price_min'];
                $priceList->orig_price_s_max = $pos_s_pub_config['orig_price_max'];
                $priceList->retail_price_s_min = $pos_s_pub_config['retail_price_min'];
                $priceList->retail_price_s_max = $pos_s_pub_config['retail_price_max'];
                $priceList->execute_price_s = $pos_s_pub_config['execute_price'];

                $priceList->m_1_pub_type = $pos_m_1_pub_config['pub_type'];
                $priceList->orig_price_m_1_min = $pos_m_1_pub_config['orig_price_min'];
                $priceList->orig_price_m_1_max = $pos_m_1_pub_config['orig_price_max'];
                $priceList->retail_price_m_1_min = $pos_m_1_pub_config['retail_price_min'];
                $priceList->retail_price_m_1_max = $pos_m_1_pub_config['retail_price_max'];
                $priceList->execute_price_m_1 = $pos_m_1_pub_config['execute_price'];

                $priceList->m_2_pub_type = $pos_m_2_pub_config['pub_type'];
                $priceList->orig_price_m_2_min = $pos_m_2_pub_config['orig_price_min'];
                $priceList->orig_price_m_2_max = $pos_m_2_pub_config['orig_price_max'];
                $priceList->retail_price_m_2_min = $pos_m_2_pub_config['retail_price_min'];
                $priceList->retail_price_m_2_max = $pos_m_2_pub_config['retail_price_max'];
                $priceList->execute_price_m_2 = $pos_m_2_pub_config['execute_price'];

                $priceList->m_3_pub_type = $pos_m_3_pub_config['pub_type'];
                $priceList->orig_price_m_3_min = $pos_m_3_pub_config['orig_price_min'];
                $priceList->orig_price_m_3_max = $pos_m_3_pub_config['orig_price_max'];
                $priceList->retail_price_m_3_min = $pos_m_3_pub_config['retail_price_min'];
                $priceList->retail_price_m_3_max = $pos_m_3_pub_config['retail_price_max'];
                $priceList->execute_price_m_3 = $pos_m_3_pub_config['execute_price'];

                $priceList->save();
            }

            $bind = MediaVendorBind::find()
                ->where(['uuid' => $priceList->bind_uuid])
                ->one();

            $weixin = MediaWeixin::find()
                ->where(['uuid' => $bind->media_uuid])
                ->one();
            if ($isPrefVendor == 1) {

                // 将所有vendor设置为"非首选"
                MediaVendorBind::updateAll(['is_pref_vendor' => 0], ['media_uuid' => $bind->media_uuid]);

                $weixin->has_pref_vendor = 1;
                $weixin->pref_vendor_uuid = $bind->vendor_uuid;

                $weixin->has_origin_pub =$request->post('has_origin_pub');
                $weixin->has_direct_pub = $request->post('has_direct_pub');

                // 更新价格
                $weixin->pub_config = $pubConfig;
                $weixin->active_end_time = $activeEndTime;

                // 单图文
                $weixin->s_pub_type = $pos_s_pub_config['pub_type'];
                $weixin->orig_price_s_min = $pos_s_pub_config['orig_price_min'];
                $weixin->orig_price_s_max = $pos_s_pub_config['orig_price_max'];
                $weixin->retail_price_s_min = $pos_s_pub_config['retail_price_min'];
                $weixin->retail_price_s_max = $pos_s_pub_config['retail_price_max'];
                $weixin->execute_price_s = $pos_s_pub_config['execute_price'];

                // 多图文第1条
                $weixin->m_1_pub_type = $pos_m_1_pub_config['pub_type'];
                $weixin->orig_price_m_1_min = $pos_m_1_pub_config['orig_price_min'];
                $weixin->orig_price_m_1_max = $pos_m_1_pub_config['orig_price_max'];
                $weixin->retail_price_m_1_min = $pos_m_1_pub_config['retail_price_min'];
                $weixin->retail_price_m_1_max = $pos_m_1_pub_config['retail_price_max'];
                $weixin->execute_price_m_1 = $pos_m_1_pub_config['execute_price'];

                // 多图文第2条
                $weixin->m_2_pub_type = $pos_m_2_pub_config['pub_type'];
                $weixin->orig_price_m_2_min = $pos_m_2_pub_config['orig_price_min'];
                $weixin->orig_price_m_2_max = $pos_m_2_pub_config['orig_price_max'];
                $weixin->retail_price_m_2_min = $pos_m_2_pub_config['retail_price_min'];
                $weixin->retail_price_m_2_max = $pos_m_2_pub_config['retail_price_max'];
                $weixin->execute_price_m_2 = $pos_m_2_pub_config['execute_price'];

                // 多图文第3-N条
                $weixin->m_3_pub_type = $pos_m_3_pub_config['pub_type'];
                $weixin->orig_price_m_3_min = $pos_m_3_pub_config['orig_price_min'];
                $weixin->orig_price_m_3_max = $pos_m_3_pub_config['orig_price_max'];
                $weixin->retail_price_m_3_min = $pos_m_3_pub_config['retail_price_min'];
                $weixin->retail_price_m_3_max = $pos_m_3_pub_config['retail_price_max'];
                $weixin->execute_price_m_3 = $pos_m_3_pub_config['execute_price'];

                if ($weixin->status == MediaWeixin::STATUS_INFO_VERIFY_OK) {
                    $weixin->put_up = 1; // 账号信息审核通过 && 设置媒体主为首选 ==> 上架
                }
                //首选 资源审核时间同步
                $weixin->last_verify_time = time();

            } else {
                // 设置该vendor为"非首选"
                $prefVendorBind = MediaVendorBind::findOne(['media_uuid' => $bind->media_uuid, 'is_pref_vendor' => 1]);
                if ($prefVendorBind == null) {
                    $weixin->has_pref_vendor = 0;
                    $weixin->pref_vendor_uuid = '';
                }
            }
            if ($bind->status == 0 && ($vendorBindStatus == 1 || $vendorBindStatus == 2)) {
                // 原先:待审核  现在:审核通过or未通过
                $weixin->to_verify_vendor_cnt = $weixin->to_verify_vendor_cnt - 1;
            }
            if (($bind->status == 1 || $bind->status == 2) && $vendorBindStatus == 0) {
                // 原先: 审核通过or未通过 现在: 待审核
                $weixin->to_verify_vendor_cnt = $weixin->to_verify_vendor_cnt + 1;
            }
            $weixin->has_updated = 1; // 已经更新供应商信息
            $weixin->save();

            $bind->status = $vendorBindStatus;
            $bind->last_verify_time = time();
            $bind->pay_period = $payPeriod;
            $bind->coop_level = $coopLevel;
            $bind->media_ownership = $mediaOwnership;
            $bind->is_pref_vendor = $isPrefVendor;
            $bind->save();

            //价格改变记录
            $Record = new MediaWeixinPriceRecord();
            $Record->uuid = PlatformHelper::getUUID();
            $Record->media_uuid = $weixin->uuid;
            $Record->vendor_uuid = $bind->vendor_uuid;
            //$Record->account_uuid = $userinfo['account_uuid']; //登录账户uuid
            $Record->is_prefer_vendor = $isPrefVendor;
            $Record->s_pub_type = $pos_s_pub_config['pub_type'];
            $Record->m_1_pub_type = $pos_m_1_pub_config['pub_type'];
            $Record->m_2_pub_type = $pos_m_2_pub_config['pub_type'];
            $Record->m_3_pub_type = $pos_m_3_pub_config['pub_type'];
            $Record->orig_price_s = $pos_s_pub_config['orig_price_min'];
            $Record->orig_price_m_1 = $pos_m_1_pub_config['orig_price_min'];
            $Record->orig_price_m_2 = $pos_m_2_pub_config['orig_price_min'];
            $Record->orig_price_m_3 = $pos_m_3_pub_config['orig_price_min'];
            $Record->retail_price_s = $pos_s_pub_config['retail_price_min'];
            $Record->retail_price_m_1 = $pos_m_1_pub_config['retail_price_min'];
            $Record->retail_price_m_2 = $pos_m_2_pub_config['retail_price_min'];
            $Record->retail_price_m_3 = $pos_m_3_pub_config['retail_price_min'];
            $Record->create_time = time();
            $Record->save();

            return ['err_code' => 0, 'err_msg' => '保存成功'];
        }
    }

    /**
     * 审核供应商
     */
    public function actionSetInfo()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isPost) {
            $bindUUID = $request->post('bind_uuid');
            $pubConfig = $request->post('pub_config');
            $depositPercentConfig = $request->post('deposit_percent_config');
            $servePercentConfig = $request->post('serve_percent_config');
            $vendorBindStatus = $request->post('vendor_bind_status');
            $payPeriod = $request->post('pay_period');
            $coopLevel = $request->post('coop_level');
            $isPrefVendor = $request->post('is_pref_vendor');
            $mediaOwnership = $request->post('media_ownership');
            $activeEndTime = $request->post('active_end_time');

            if ($activeEndTime == '') {
                $activeEndTime = -1;
            } else {
                $activeEndTime = strtotime($activeEndTime);
            }

            $pubConfigArray = json_decode($pubConfig, true);
            $pos_s_pub_config = $pubConfigArray['pos_s'];
            $pos_m_1_pub_config = $pubConfigArray['pos_m_1'];
            $pos_m_2_pub_config = $pubConfigArray['pos_m_2'];
            $pos_m_3_pub_config = $pubConfigArray['pos_m_3'];

            $priceList = MediaVendorWeixinPriceList::find()
                ->where(['bind_uuid' => $bindUUID])
                ->one();

            $priceList->pub_config = $pubConfig;
            $priceList->active_end_time = $activeEndTime;
            $priceList->deposit_percent_config = $depositPercentConfig;
            $priceList->serve_percent_config = $servePercentConfig;

            $priceList->s_pub_type = $pos_s_pub_config['pub_type'];
            $priceList->orig_price_s_min = $pos_s_pub_config['orig_price_min'];
            $priceList->orig_price_s_max = $pos_s_pub_config['orig_price_max'];
            $priceList->retail_price_s_min = $pos_s_pub_config['retail_price_min'];
            $priceList->retail_price_s_max = $pos_s_pub_config['retail_price_max'];
            $priceList->execute_price_s = $pos_s_pub_config['execute_price'];

            $priceList->save();

            $bind = MediaVendorBind::find()
                ->where(['uuid' => $priceList->bind_uuid])
                ->one();

            $weixin = MediaWeixin::find()
                ->where(['uuid' => $bind->media_uuid])
                ->one();
            if ($isPrefVendor == 1) {
                $weixin->has_pref_vendor = 1;
                $weixin->pref_vendor_uuid = $bind->vendor_uuid;

                $weixin->has_origin_pub = $bind->has_origin_pub;
                $weixin->has_direct_pub = $bind->has_direct_pub;

                // 更新价格
                $weixin->pub_config = $pubConfig;
                $weixin->active_end_time = $activeEndTime;

                // 单图文
                $weixin->s_pub_type = $pos_s_pub_config['pub_type'];
                $weixin->orig_price_s_min = $pos_s_pub_config['orig_price_min'];
                $weixin->orig_price_s_max = $pos_s_pub_config['orig_price_max'];
                $weixin->retail_price_s_min = $pos_s_pub_config['retail_price_min'];
                $weixin->retail_price_s_max = $pos_s_pub_config['retail_price_max'];
                $weixin->execute_price_s = $pos_s_pub_config['execute_price'];

                // 多图文第1条
                $weixin->m_1_pub_type = $pos_m_1_pub_config['pub_type'];
                $weixin->orig_price_m_1_min = $pos_m_1_pub_config['orig_price_min'];
                $weixin->orig_price_m_1_max = $pos_m_1_pub_config['orig_price_max'];
                $weixin->retail_price_m_1_min = $pos_m_1_pub_config['retail_price_min'];
                $weixin->retail_price_m_1_max = $pos_m_1_pub_config['retail_price_max'];
                $weixin->execute_price_m_1 = $pos_m_1_pub_config['execute_price'];

                // 多图文第2条
                $weixin->m_2_pub_type = $pos_m_2_pub_config['pub_type'];
                $weixin->orig_price_m_2_min = $pos_m_2_pub_config['orig_price_min'];
                $weixin->orig_price_m_2_max = $pos_m_2_pub_config['orig_price_max'];
                $weixin->retail_price_m_2_min = $pos_m_2_pub_config['retail_price_min'];
                $weixin->retail_price_m_2_max = $pos_m_2_pub_config['retail_price_max'];
                $weixin->execute_price_m_2 = $pos_m_2_pub_config['execute_price'];

                // 多图文第3-N条
                $weixin->m_3_pub_type = $pos_m_3_pub_config['pub_type'];
                $weixin->orig_price_m_3_min = $pos_m_3_pub_config['orig_price_min'];
                $weixin->orig_price_m_3_max = $pos_m_3_pub_config['orig_price_max'];
                $weixin->retail_price_m_3_min = $pos_m_3_pub_config['retail_price_min'];
                $weixin->retail_price_m_3_max = $pos_m_3_pub_config['retail_price_max'];
                $weixin->execute_price_m_3 = $pos_m_3_pub_config['execute_price'];

            } else {
                $weixin->has_pref_vendor = 0;
                $weixin->pref_vendor_uuid = '';
            }
            if ($bind->status == 0 && ($vendorBindStatus == 1 || $vendorBindStatus == 2)) {
                // 原先:待审核  现在:审核通过or未通过
                $weixin->to_verify_vendor_cnt = $weixin->to_verify_vendor_cnt - 1;
            }
            if (($bind->status == 1 || $bind->status == 2) && $vendorBindStatus == 0) {
                // 原先: 审核通过or未通过 现在: 待审核
                $weixin->to_verify_vendor_cnt = $weixin->to_verify_vendor_cnt + 1;
            }
            $weixin->save();

            $bind->status = $vendorBindStatus;
            $bind->pay_period = $payPeriod;
            $bind->coop_level = $coopLevel;
            $bind->media_ownership = $mediaOwnership;
            $bind->is_pref_vendor = $isPrefVendor;

            $bind->save();

            return ['err_code' => 0, 'err_msg' => '审核成功'];
        }
    }

}