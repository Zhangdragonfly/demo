<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:24 PM
 */
namespace admin\modules\weixin\controllers;

use Yii;
use admin\controllers\BaseAppController;
use admin\helpers\AdminHelper;
use common\helpers\DateTimeHelper;
use common\helpers\PlatformHelper;
use common\models\MediaExecutor;
use common\models\MediaExecutorAssign;
use common\models\MediaVendorBind;
use common\models\MediaVendorWeixinPriceList;
use common\models\MediaVendor;
use common\models\MediaWeixin;
use common\models\MediaBatchUpload;
use common\models\MediaWeixinPriceRecord;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Response;
use PHPExcel;
use PHPExcel_Writer_Excel5;
use PHPExcel_Writer_Excel2007;
use PHPExcel_IOFactory;

/**
 * 微信媒体资源控制类
 * Class MediaController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaController extends BaseAppController
{
    /**
     * 新建资源
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            //$userinfo = $this->getCurrentLoginUser();//账号信息
            $prefer_vendor_uuid = $request->post('prefer_vendor_uuid'); //首选媒体主uuid
            $vendor_price_info_array = $request->post('vendor_price_data_obj');//报价信息数组
            $prefer_price_array = array();
            foreach ($vendor_price_info_array as $k => $v) {
                if ($v['vendor_uuid'] == $prefer_vendor_uuid) {
                    $vendor_price_info_array[$k]['is_prefer_vendor'] = 1;
                    $prefer_price_array['psr'] = $v['pos_s_radio'];
                    $prefer_price_array['pm1r'] = $v['pos_m_1_radio'];
                    $prefer_price_array['pm2r'] = $v['pos_m_2_radio'];
                    $prefer_price_array['pm3r'] = $v['pos_m_3_radio'];
                    $prefer_price_array['psop'] = $v['pos_s_orig_price'];
                    $prefer_price_array['pm1op'] = $v['pos_m_1_orig_price'];
                    $prefer_price_array['pm2op'] = $v['pos_m_2_orig_price'];
                    $prefer_price_array['pm3op'] = $v['pos_m_3_orig_price'];
                    $prefer_price_array['psrp'] = $v['pos_s_retail_price'];
                    $prefer_price_array['pm1rp'] = $v['pos_m_1_retail_price'];
                    $prefer_price_array['pm2rp'] = $v['pos_m_2_retail_price'];;
                    $prefer_price_array['pm3rp'] = $v['pos_m_3_retail_price'];
                    $prefer_price_array['pscp'] = $v['pos_s_execute_price'];
                    $prefer_price_array['pm1cp'] = $v['pos_m_1_execute_price'];
                    $prefer_price_array['pm2cp'] = $v['pos_m_2_execute_price'];
                    $prefer_price_array['pm3cp'] = $v['pos_m_3_execute_price'];
                    $prefer_price_array['has_origin_pub'] = $v['has_origin_pub'];
                    $prefer_price_array['has_direct_pub'] = $v['has_direct_pub'];
                    $prefer_price_array['active_end_time'] = strtotime($v['active_end_time']);
                } else {
                    $vendor_price_info_array[$k]['is_prefer_vendor'] = 0;
                }
            }
            //media_weixin的保存
            $mediaWeixin = new MediaWeixin();
            $mediaWeixin->uuid = PlatformHelper::getUUID();
            $mediaWeixin->public_id = $request->post('weixin_id');
            $mediaWeixin->public_name = $request->post('weixin_name');
            $mediaWeixin->follower_num = $request->post('follower_num');
            $mediaWeixin->media_cate = $request->post('media_cate');
            $mediaWeixin->follower_area = $request->post('follower_area');
            $mediaWeixin->media_belong_type = $request->post('belong_type');
            $mediaWeixin->pref_vendor_uuid = $prefer_vendor_uuid;
            $mediaWeixin->s_pub_type = $prefer_price_array['psr'];
            $mediaWeixin->m_1_pub_type = $prefer_price_array['pm1r'];
            $mediaWeixin->m_2_pub_type = $prefer_price_array['pm2r'];
            $mediaWeixin->m_3_pub_type = $prefer_price_array['pm3r'];
            $mediaWeixin->orig_price_s_min = $prefer_price_array['psop'];
            $mediaWeixin->orig_price_s_max = $prefer_price_array['psop'];
            $mediaWeixin->orig_price_m_1_min = $prefer_price_array['pm1op'];
            $mediaWeixin->orig_price_m_1_max = $prefer_price_array['pm1op'];
            $mediaWeixin->orig_price_m_2_min = $prefer_price_array['pm2op'];
            $mediaWeixin->orig_price_m_2_max = $prefer_price_array['pm2op'];
            $mediaWeixin->orig_price_m_3_min = $prefer_price_array['pm3op'];
            $mediaWeixin->orig_price_m_3_max = $prefer_price_array['pm3op'];
            $mediaWeixin->retail_price_s_min = $prefer_price_array['psrp'];
            $mediaWeixin->retail_price_s_max = $prefer_price_array['psrp'];
            $mediaWeixin->retail_price_m_1_min = $prefer_price_array['pm1rp'];
            $mediaWeixin->retail_price_m_1_max = $prefer_price_array['pm1rp'];
            $mediaWeixin->retail_price_m_2_min = $prefer_price_array['pm2rp'];
            $mediaWeixin->retail_price_m_2_max = $prefer_price_array['pm2rp'];
            $mediaWeixin->retail_price_m_3_min = $prefer_price_array['pm3rp'];
            $mediaWeixin->retail_price_m_3_max = $prefer_price_array['pm3rp'];
            $mediaWeixin->execute_price_s = $prefer_price_array['pscp'];
            $mediaWeixin->execute_price_m_1 = $prefer_price_array['pm1cp'];
            $mediaWeixin->execute_price_m_2 = $prefer_price_array['pm2cp'];
            $mediaWeixin->execute_price_m_3 = $prefer_price_array['pm3cp'];
            $mediaWeixin->pub_config = $request->post('pub_config');              //发布配置
            $mediaWeixin->has_origin_pub = $prefer_price_array['has_origin_pub'];
            $mediaWeixin->has_direct_pub = $prefer_price_array['has_direct_pub'];
            $mediaWeixin->status = MediaWeixin::STATUS_INFO_VERIFY_OK;
            $mediaWeixin->active_end_time = $prefer_price_array['active_end_time'];
            $mediaWeixin->last_verify_time = time();
            $mediaWeixin->last_update_time = time();
            $mediaWeixin->create_time = time();
            $mediaWeixin->has_pref_vendor = 1;                                    //是否设置首选供应商
            $mediaWeixin->vendor_cnt = count($vendor_price_info_array);           //供应商数量
            $mediaWeixin->to_verify_vendor_cnt = 0; //待审核供应商数量
            $mediaWeixin->comment = $request->post('comment');
            $mediaWeixin->save();
            $weixin_uuid = $mediaWeixin->uuid;//微信uuid

            foreach ($vendor_price_info_array as $k => $v) {
                //media_vendor_bind的保存
                $mediaVendorBind = new MediaVendorBind();
                $mediaVendorBind->uuid = PlatformHelper::getUUID();
                $mediaVendorBind->media_uuid = $weixin_uuid;
                $mediaVendorBind->media_type = 1; // TODO media_type 待去除
                $mediaVendorBind->vendor_uuid = $v['vendor_uuid'];
                $mediaVendorBind->status = MediaVendorBind::STATUS_VERIFY_OK;
                $mediaVendorBind->is_pref_vendor = $vendor_price_info_array[$k]['is_prefer_vendor'];
                $mediaVendorBind->media_cate = $request->post('media_cate');
                $mediaVendorBind->follower_area = $request->post('follower_area');
                $mediaVendorBind->comment = $request->post('comment');
                $mediaVendorBind->has_origin_pub = $v['has_origin_pub'];
                $mediaVendorBind->has_direct_pub = $v['has_direct_pub'];
                $mediaVendorBind->coop_level = $v['coop_level'];
                $mediaVendorBind->pay_period = $v['pay_period'];
                $mediaVendorBind->media_ownership = $v['media_ownership'];
                $mediaVendorBind->create_time = time();
                $mediaVendorBind->save();

                //media_vendor_weixin_price_list的保存
                $VendorPriceList = new MediaVendorWeixinPriceList();
                $VendorPriceList->uuid = PlatformHelper::getUUID();
                $VendorPriceList->bind_uuid = $mediaVendorBind->uuid;
                $VendorPriceList->pub_config = $request->post('pub_config');
                $VendorPriceList->s_pub_type = $v['pos_s_radio'];
                $VendorPriceList->m_1_pub_type = $v['pos_m_1_radio'];
                $VendorPriceList->m_2_pub_type = $v['pos_m_2_radio'];
                $VendorPriceList->m_3_pub_type = $v['pos_m_3_radio'];
                $VendorPriceList->orig_price_s_min = $v['pos_s_orig_price'];
                $VendorPriceList->orig_price_s_max = $v['pos_s_orig_price'];
                $VendorPriceList->orig_price_m_1_min = $v['pos_m_1_orig_price'];
                $VendorPriceList->orig_price_m_1_max = $v['pos_m_1_orig_price'];
                $VendorPriceList->orig_price_m_2_min = $v['pos_m_2_orig_price'];
                $VendorPriceList->orig_price_m_2_max = $v['pos_m_2_orig_price'];
                $VendorPriceList->orig_price_m_3_min = $v['pos_m_3_orig_price'];
                $VendorPriceList->orig_price_m_3_max = $v['pos_m_3_orig_price'];
                $VendorPriceList->retail_price_s_min = $v['pos_s_retail_price'];
                $VendorPriceList->retail_price_s_max = $v['pos_s_retail_price'];
                $VendorPriceList->retail_price_m_1_min = $v['pos_m_1_retail_price'];
                $VendorPriceList->retail_price_m_1_max = $v['pos_m_1_retail_price'];
                $VendorPriceList->retail_price_m_2_min = $v['pos_m_2_retail_price'];;
                $VendorPriceList->retail_price_m_2_max = $v['pos_m_2_retail_price'];
                $VendorPriceList->retail_price_m_3_min = $v['pos_m_3_retail_price'];
                $VendorPriceList->retail_price_m_3_max = $v['pos_m_3_retail_price'];
                $VendorPriceList->execute_price_s = $v['pos_s_execute_price'];
                $VendorPriceList->execute_price_m_1 = $v['pos_m_1_execute_price'];
                $VendorPriceList->execute_price_m_2 = $v['pos_m_2_execute_price'];
                $VendorPriceList->execute_price_m_3 = $v['pos_m_3_execute_price'];
                $VendorPriceList->active_end_time = strtotime($v['active_end_time']);
                $VendorPriceList->save();

                //vendor表
                $vendor = MediaVendor::findOne(['uuid'=>$v['vendor_uuid']]);
                $vendor->weixin_media_cnt +=1;
                $vendor->save();

                //价格改变记录
                $Record = new MediaWeixinPriceRecord();
                $Record->uuid = PlatformHelper::getUUID();
                $Record->media_uuid = $weixin_uuid;
                $Record->vendor_uuid = $v['vendor_uuid'];
                //$Record->account_uuid =$userinfo['account_uuid']; //登录账户uuid
                $Record->is_prefer_vendor =$vendor_price_info_array[$k]['is_prefer_vendor'];
                $Record->s_pub_type = $v['pos_s_radio'];
                $Record->m_1_pub_type = $v['pos_m_1_radio'];
                $Record->m_2_pub_type = $v['pos_m_2_radio'];
                $Record->m_3_pub_type = $v['pos_m_3_radio'];
                $Record->orig_price_s = $v['pos_s_orig_price'];
                $Record->orig_price_m_1 = $v['pos_m_1_orig_price'];
                $Record->orig_price_m_2 = $v['pos_m_2_orig_price'];
                $Record->orig_price_m_3 = $v['pos_m_3_orig_price'];
                $Record->retail_price_s = $v['pos_s_retail_price'];
                $Record->retail_price_m_1 = $v['pos_m_1_retail_price'];
                $Record->retail_price_m_2 = $v['pos_m_2_retail_price'];
                $Record->retail_price_m_3 = $v['pos_m_3_retail_price'];
                $Record->create_time = time();
                $Record->save();
            }
            return ['err_code' => 0, 'err_msg' => '保存成功!'];
        } else {
            $query = (new Query())
                ->select(['media_batch_upload.uuid AS upload_record_uuid', 'media_batch_upload.total_cnt', 'media_batch_upload.succ_cnt', 'media_batch_upload.fail_cnt', 'media_batch_upload.create_time', 'media_batch_upload.operator_name'])
                ->from(['media_batch_upload' => MediaBatchUpload::tableName()])
                ->orderBy(['media_batch_upload.create_time' => SORT_DESC]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0,
                ]
            ]);

            return $this->render('create', [
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * 检查账号是否存在
     */
    public function actionCheckExist()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $weixinId = $request->post('weixin_id');
            $weixin = MediaWeixin::findOne(['public_id' => $weixinId]);
            if ($weixin !== null) {
                if ($weixin->status == 3 || $weixin->status == 2) {
                    return ['err_code' => 2, 'err_msg' => '该账号已经存在，为无效账号！'];
                } else {
                    return ['err_code' => 2, 'err_msg' => '该账号已经在系统中存在！'];
                }
            } else {
                return ['err_code' => 3, 'err_msg' => '该账号不存在，可以添加！'];
            }
        }
    }

    /**
     * 查看导入记录
     * @return string
     */
    public function actionQueryBatchUploadHistory()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $page = $request->post('page');
            $createTimeRange = $request->post('create-time-range');

            $query = (new Query())
                ->select(['media_upload_record.uuid AS upload_record_uuid', 'media_upload_record.succ_cnt', 'media_upload_record.fail_cnt', 'media_upload_record.create_time', 'media_upload_record.operator_name'])
                ->from(['media_upload_record' => MediaBatchUpload::tableName()])
                ->orderBy(['media_upload_record.create_time' => SORT_DESC]);

            if (!empty($createTimeRange)) {
                $dateTimeRange = DateTimeHelper::getStartEndDateFromRange();
                $startDate = $dateTimeRange['startDate'];
                $endDate = $dateTimeRange['endDate'];
                $query->andWhere(['between', 'media_upload_record.create_time', $startDate, $endDate]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page,
                ]
            ]);

            return $this->render('create', [
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * 批量导入微信资源
     */
    public function actionUploadInBatch()
    {
        // var_dump($_FILES);
        // if ($_FILES["file"]["error"] > 0){
        //     echo "Error: " . $_FILES["file"]["error"] . "<br />";
        // }else{
        //     $filetype =  $_FILES['file'] ['type'];
        //     $filename = $_FILES['file'] ['name'];   //获取上传的文件名
        //     $tmp_name = $_FILES ['file']['tmp_name'];    //上传到服务器上的临时文件名
        //     $objReader = PHPExcel_IOFactory::createReader($filetype);
        //     $objPHPExcel = $objReader->load($filename);
        //     $sheet = $objPHPExcel->getSheet(0);
        //     $highestRow = $sheet->getHighestRow(); //取得总行数
        //     var_dump($sheet);
        // }
    }

    /**
     * 全部微信资源
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        $vendor_uuid = $request->get('vendor_uuid');
        $query = (new Query())
            ->select(['weixin.id as media_id', 'weixin.uuid as media_uuid','weixin.pref_vendor_uuid', 'weixin.public_id', 'weixin.public_name', 'weixin.media_cate', 'weixin.follower_num', 'weixin.put_up', 'weixin.status as weixin_status', 'weixin.account_cert', 'weixin.is_activated', 'weixin.create_time', 'weixin.last_update_time', 'weixin.last_verify_time', 'weixin.vendor_cnt', 'weixin.to_verify_vendor_cnt', 'weixin.has_pref_vendor', 'weixin.order_finished_cnt', 'weixin.order_refuse_cnt', 'weixin.order_abort_cnt', 'weixin.active_end_time', 'weixin.pub_config', 'weixin.cust_sort', 'weixin.comment', 'weixin.t_media_intro', 'weixin.has_updated', 'weixin.t_comment','weixin.has_pref_vendor',
                'media_executor.name AS executor_name',
                'vendor.name AS vendor_name', 'vendor.contact_person AS vendor_contact_person','vendor.contact1','vendor.comment as vendor_comment','weixin.is_push'])
            ->from(['weixin' => MediaWeixin::tableName()])
//                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
//                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
//                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
            ->orderBy(['weixin.cust_sort' => SORT_DESC, 'weixin.last_update_time' => SORT_DESC]);

        $vendor_name = "";
        if(!empty($vendor_uuid)){//供应商对应的资源列表
            $vendor = MediaVendor::findOne(['uuid'=>$vendor_uuid]);
            $vendor_name = $vendor->name;
            $query->leftJoin(['vendorBind' => MediaVendorBind::tableName()], 'weixin.uuid  =  vendorBind.media_uuid')
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  vendorBind.vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->andWhere(['vendor.uuid' => $vendor_uuid]);
        }else{
            $query->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid');
        }

        $page = 0;
        if ($request->isPost) {
            $account = $request->post('account'); // 公众号 或 ID
            $status = $request->post('status',-1); //状态 0待审核 1 已审核 2无效账号
            $mediaCate = $request->post('media-cate', -1);
            $isPutUp = $request->post('is-put-up', -1);
            $price_min = $request->post('price-cnt-min', 0);
            $price_max = $request->post('price-cnt-max', 0);
            $activeEndTimeRange = $request->post('active-end-time-range', '');
            $hasUpdated = $request->post('has-updated', -1);
            $remark = $request->post('remark', '');//1.0备注
            $page = $request->post('page', 0);

            if (isset($account)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $account], ['like', 'weixin.public_id', $account]]);
            }
            //价格区间
            if (($price_min == 0 && $price_max > 0) || ($price_min > 0 && $price_max > 0)) {
                $query->andWhere(['between', 'weixin.orig_price_m_1_min', $price_min, $price_max]);
            }

            if ($mediaCate != -1) {
                $query->andWhere(['like', 'weixin.media_cate', '#' . $mediaCate . '#']);
            }

            if ($isPutUp != -1) {
                $query->andWhere(['weixin.put_up' => $isPutUp]);
            }

            if (!empty($remark)) {
                $query->andWhere(['like', 'weixin.t_comment', $remark]);
            }
            if (!empty($activeEndTimeRange)) {
                $activeEndTimeRangeArr = DateTimeHelper::getStartEndDateFromRange($activeEndTimeRange);
                $startDate = $activeEndTimeRangeArr['startDate'];
                $endDate = $activeEndTimeRangeArr['endDate'];
                $query->andWhere(['<=', 'weixin.active_end_time', $endDate]);
                $query->andWhere(['>=', 'weixin.active_end_time', $startDate]);
            }

            if ($hasUpdated != -1) {
                $query->andWhere(['weixin.has_updated' => $hasUpdated]);
            }
            if ($status != -1) {
                if($status==0){
                    $query->andWhere(['weixin.status' => MediaWeixin::STATUS_INFO_TO_VERIFY])
                          ->orWhere(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK, 'weixin.has_pref_vendor' => 0]);
                }
                if($status==1){
                    $query->andWhere(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK, 'weixin.has_pref_vendor' => 1]);
                }
                if($status==2){
                    $query->andWhere(['weixin.status' => [MediaWeixin::STATUS_INFO_INVALID, MediaWeixin::STATUS_INFO_INVALID_MANUAL]]);
                }
            }
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
            'vendor_uuid' => $vendor_uuid,
            'vendor_name' => $vendor_name
        ]);
    }

    /**
     * 待审核资源列表
     * 资源待审核 = 资源的待审核供应商数>0 or 资源的状态为信息待审核
     */
    public function actionToVerifyList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $account = $request->post('account', '');
            $followerCntMin = intval($request->post('follower-cnt-min', 0));
            $followerCntMax = intval($request->post('follower-cnt-max', 0));
            $mediaCate = $request->post('media-cate', -1);
            $mediaExecutor = $request->post('media-executor', -1);
            $isActivated = $request->post('is-activated', -1);
            $page = $request->post('page', 0);

            $query = (new Query())
                ->select(['weixin.id as media_id', 'weixin.uuid as media_uuid', 'weixin.pref_vendor_uuid','weixin.public_id', 'weixin.public_name', 'weixin.media_cate', 'weixin.follower_num', 'weixin.put_up', 'weixin.status as weixin_status', 'weixin.account_cert', 'weixin.is_activated', 'weixin.create_time', 'weixin.last_update_time', 'weixin.last_verify_time', 'weixin.vendor_cnt', 'weixin.to_verify_vendor_cnt', 'weixin.has_pref_vendor', 'weixin.comment', 'weixin.t_media_intro', 'weixin.active_end_time', 'weixin.pub_config', 'weixin.t_comment',
                    'media_executor.name AS executor_name',
                    'vendor.name AS vendor_name', 'vendor.contact_person AS vendor_contact_person','vendor.contact1','vendor.comment as vendor_comment','weixin.is_push'])
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->where(['weixin.status' => MediaWeixin::STATUS_INFO_TO_VERIFY])
                ->orWhere(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK, 'weixin.has_pref_vendor' => 0])
                ->orderBy(['weixin.last_update_time' => SORT_DESC]);

            if (!empty($account)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $account], ['like', 'weixin.public_id', $account]]);
            }

            if (($followerCntMin == 0 && $followerCntMax > 0) || ($followerCntMin > 0 && $followerCntMax > 0)) {
                $query->andWhere(['between', 'weixin.follower_num', $followerCntMin, $followerCntMax]);
            }

            if ($mediaCate != -1) {
                $query->andWhere(['like', 'weixin.media_cate', '#' . $mediaCate . '#']);
            }

            if ($mediaExecutor != -1) {
                // TODO
            }

            if ($isActivated != -1) {
                $query->andWhere(['weixin.is_activated' => $isActivated]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);

            return $this->render('to-verify-list', ['dataProvider' => $dataProvider]);
        } else {
            $query = (new Query())
                ->select(['weixin.id as media_id', 'weixin.uuid as media_uuid', 'weixin.pref_vendor_uuid','weixin.public_id', 'weixin.public_name', 'weixin.media_cate', 'weixin.follower_num', 'weixin.put_up', 'weixin.status as weixin_status', 'weixin.account_cert', 'weixin.is_activated', 'weixin.create_time', 'weixin.last_update_time', 'weixin.last_verify_time', 'weixin.vendor_cnt', 'weixin.to_verify_vendor_cnt', 'weixin.has_pref_vendor', 'weixin.comment', 'weixin.t_media_intro', 'weixin.active_end_time', 'weixin.pub_config', 'weixin.t_comment',
                    'media_executor.name AS executor_name',
                    'vendor.name AS vendor_name', 'vendor.contact_person AS vendor_contact_person','vendor.contact1','vendor.comment as vendor_comment','weixin.is_push'])
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->where(['weixin.status' => MediaWeixin::STATUS_INFO_TO_VERIFY])
                ->orWhere(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK, 'weixin.has_pref_vendor' => 0])
                ->orderBy(['weixin.last_update_time' => SORT_DESC]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);
            return $this->render('to-verify-list', ['dataProvider' => $dataProvider]);
        }
    }

    /**
     * 审核未通过列表
     */
    public function actionVerifyInvalidList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $account = $request->post('account', '');
            $followerCntMin = floatval($request->post('follower-cnt-min', 0)) * 10000;
            $followerCntMax = floatval($request->post('follower-cnt-max', 0)) * 10000;
            $mediaCate = $request->post('media-cate', -1);
            $mediaExecutor = $request->post('media-executor', -1);
            $isActivated = $request->post('is-activated', -1);
            $page = $request->post('page', 0);
            $query = (new Query())
                ->select(['weixin.uuid as media_uuid',
                    'weixin.public_id',
                    'weixin.public_name',
                    'weixin.media_cate',
                    'weixin.follower_num',
                    'weixin.put_up',
                    'weixin.is_push',
                    'weixin.status as weixin_status',
                    'weixin.account_cert',
                    'weixin.is_activated',
                    'weixin.create_time',
                    'weixin.last_update_time',
                    'weixin.last_verify_time',
                    'weixin.vendor_cnt',
                    'weixin.to_verify_vendor_cnt',
                    'weixin.has_pref_vendor',
                    'weixin.comment',
                    'weixin.t_media_intro',
                    'vendor.name AS vendor_name',
                    'vendor.contact_person AS vendor_contact_person'])
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->where(['weixin.status' => [MediaWeixin::STATUS_INFO_INVALID, MediaWeixin::STATUS_INFO_INVALID_MANUAL]])
                ->orderBy(['weixin.last_update_time' => SORT_DESC]);

            if (!empty($account)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $account], ['like', 'weixin.public_id', $account]]);
            }

            if (($followerCntMin == 0 && $followerCntMax > 0) || ($followerCntMin > 0 && $followerCntMax > 0)) {
                $query->andWhere(['between', 'weixin.follower_num', $followerCntMin, $followerCntMax]);
            }

            if ($isActivated != -1) {
                $query->andWhere(['weixin.is_activated' => $isActivated]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);

            return $this->render('verify-invalid-list', ['dataProvider' => $dataProvider]);
        } else {
            $query = (new Query())
                ->select(['weixin.uuid as media_uuid',
                    'weixin.public_id',
                    'weixin.public_name',
                    'weixin.media_cate',
                    'weixin.follower_num',
                    'weixin.put_up',
                    'weixin.is_push',
                    'weixin.status as weixin_status',
                    'weixin.account_cert',
                    'weixin.is_activated',
                    'weixin.create_time',
                    'weixin.last_update_time',
                    'weixin.last_verify_time',
                    'weixin.vendor_cnt',
                    'weixin.to_verify_vendor_cnt',
                    'weixin.has_pref_vendor',
                    'weixin.comment',
                    'weixin.t_media_intro',
                    'vendor.name AS vendor_name',
                    'vendor.contact_person AS vendor_contact_person'])
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->where(['weixin.status' => [MediaWeixin::STATUS_INFO_INVALID, MediaWeixin::STATUS_INFO_INVALID_MANUAL]])
                ->orderBy(['weixin.last_update_time' => SORT_DESC]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);

            return $this->render('verify-invalid-list', ['dataProvider' => $dataProvider]);
        }
    }

    /**
     * 审核已通过
     */
    public function actionVerifySuccList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $account = $request->post('account'); // 公众号 或 ID
            //$vendor = $request->post('media-vendor'); // 媒体主或其联系方式
            $remark = $request->post('remark', '');//1.0备注
            $price_min = $request->post('price-cnt-min', 0);
            $price_max = $request->post('price-cnt-max', 0);
            $mediaCate = $request->post('media-cate', -1);
            $isPutUp = $request->post('is-put-up', -1);
            $activeEndTimeRange = $request->post('active-end-time-range', '');
            $hasUpdated = $request->post('has-updated', -1);
            $page = $request->post('page', 0);

            $query = (new Query())
                ->select(['weixin.id as media_id', 'weixin.uuid as media_uuid','weixin.pref_vendor_uuid', 'weixin.public_id', 'weixin.public_name', 'weixin.media_cate', 'weixin.follower_num', 'weixin.put_up', 'weixin.status as weixin_status', 'weixin.account_cert', 'weixin.is_activated', 'weixin.create_time', 'weixin.last_update_time', 'weixin.last_verify_time', 'weixin.vendor_cnt', 'weixin.to_verify_vendor_cnt', 'weixin.has_pref_vendor', 'weixin.order_finished_cnt', 'weixin.order_refuse_cnt', 'weixin.order_abort_cnt', 'weixin.active_end_time', 'weixin.pub_config', 'weixin.cust_sort', 'weixin.comment', 'weixin.t_media_intro', 'weixin.has_updated', 'weixin.t_comment',
                    'media_executor.name AS executor_name',
                    'vendor.name AS vendor_name', 'vendor.contact_person AS vendor_contact_person','vendor.contact1','vendor.comment as vendor_comment','weixin.is_push'])
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->where(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK, 'weixin.has_pref_vendor' => 1])
                ->orderBy(['weixin.cust_sort' => SORT_DESC, 'weixin.last_update_time' => SORT_DESC]);

            if (isset($account)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $account], ['like', 'weixin.public_id', $account]]);
            }
            //价格区间
            if (($price_min == 0 && $price_max > 0) || ($price_min > 0 && $price_max > 0)) {
                $query->andWhere(['between', 'weixin.orig_price_m_1_min', $price_min, $price_max]);
            }

            if ($mediaCate != -1) {
                $query->andWhere(['like', 'weixin.media_cate', '#' . $mediaCate . '#']);
            }

            if ($isPutUp != -1) {
                $query->andWhere(['weixin.put_up' => $isPutUp]);
            }

            if (!empty($activeEndTimeRange)) {
                $activeEndTimeRangeArr = DateTimeHelper::getStartEndDateFromRange($activeEndTimeRange);
                $startDate = $activeEndTimeRangeArr['startDate'];
                $endDate = $activeEndTimeRangeArr['endDate'];
                $query->andWhere(['<=', 'weixin.active_end_time', $endDate]);
                $query->andWhere(['>=', 'weixin.active_end_time', $startDate]);
            }

            if ($hasUpdated != -1) {
                $query->andWhere(['weixin.has_updated' => $hasUpdated]);
            }

            if (!empty($remark)) {//1.0备注
                $query->andWhere(['like', 'weixin.t_comment', $remark]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);
            return $this->render('verify-succ-list', [
                'dataProvider' => $dataProvider
            ]);
        } else {
            $query = (new Query())
                ->select(['weixin.id as media_id', 'weixin.uuid as media_uuid','weixin.pref_vendor_uuid', 'weixin.public_id', 'weixin.public_name', 'weixin.media_cate', 'weixin.follower_num', 'weixin.put_up', 'weixin.status as weixin_status', 'weixin.account_cert', 'weixin.is_activated', 'weixin.create_time', 'weixin.last_update_time', 'weixin.last_verify_time', 'weixin.vendor_cnt', 'weixin.to_verify_vendor_cnt', 'weixin.has_pref_vendor', 'weixin.order_finished_cnt', 'weixin.order_refuse_cnt', 'weixin.order_abort_cnt', 'weixin.active_end_time', 'weixin.pub_config', 'weixin.cust_sort', 'weixin.comment', 'weixin.t_media_intro', 'weixin.has_updated', 'weixin.t_comment',
                    'media_executor.name AS executor_name',
                    'vendor.name AS vendor_name', 'vendor.contact_person AS vendor_contact_person','vendor.contact1','vendor.comment as vendor_comment','weixin.is_push'])
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->where(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK, 'weixin.has_pref_vendor' => 1])
                ->orderBy(['weixin.last_update_time' => SORT_DESC]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);

            return $this->render('verify-succ-list', [
                'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * 待更新
     */
    public function actionToUpdateList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $account = $request->post('account'); // 公众号 或 ID
            $vendor = $request->post('media-vendor'); // 媒体主或其联系方式
            $followerCntMin = floatval($request->post('follower-cnt-min', 0)) * 10000;
            $followerCntMax = floatval($request->post('follower-cnt-max', 0)) * 10000;
            $mediaCate = $request->post('media-cate', -1);
            $isActivated = $request->post('is-activated', -1);
            $isPutUp = $request->post('is-put-up', -1);
            $activeEndTimeRange = $request->post('active-end-time-range', '');
            $hasUpdated = $request->post('has-updated', -1);
            $page = $request->post('page', 0);

            $need_update_time = strtotime("+14 day");//十四天后的时间
            $query = (new Query())
                ->select(['weixin.id as media_id', 'weixin.uuid as media_uuid','weixin.pref_vendor_uuid', 'weixin.public_id', 'weixin.public_name', 'weixin.media_cate', 'weixin.follower_num', 'weixin.put_up', 'weixin.status as weixin_status', 'weixin.account_cert', 'weixin.is_activated', 'weixin.create_time', 'weixin.last_update_time', 'weixin.last_verify_time', 'weixin.vendor_cnt', 'weixin.to_verify_vendor_cnt', 'weixin.has_pref_vendor', 'weixin.order_finished_cnt', 'weixin.order_refuse_cnt', 'weixin.order_abort_cnt', 'weixin.pub_config', 'weixin.cust_sort', 'weixin.comment', 'weixin.t_media_intro', 'weixin.has_updated', 'weixin.t_comment','weixin.active_end_time',
                    'media_executor.name AS executor_name',
                    'vendor.name AS vendor_name', 'vendor.contact_person AS vendor_contact_person','vendor.contact1','vendor.comment as vendor_comment','weixin.is_push'])
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->where(['weixin.has_pref_vendor' => 1])
                ->where(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK])
                ->andWhere(['weixin.put_up' => 1])
                ->andWhere(['<=','weixin.active_end_time',$need_update_time])
                ->orderBy(['weixin.active_end_time' => SORT_DESC]);

            if (isset($account)) {
                $query->andWhere(['or', ['like', 'weixin.public_name', $account], ['like', 'weixin.public_id', $account]]);
            }
            if (($followerCntMin == 0 && $followerCntMax > 0) || ($followerCntMin > 0 && $followerCntMax > 0)) {
                $query->andWhere(['between', 'weixin.follower_num', $followerCntMin, $followerCntMax]);
            }

            if ($mediaCate != -1) {
                $query->andWhere(['like', 'weixin.media_cate', '#' . $mediaCate . '#']);
            }

            if ($isActivated != -1) {
                $query->andWhere(['weixin.is_activated' => $isActivated]);
            }

            if ($isPutUp != -1) {
                $query->andWhere(['weixin.put_up' => $isPutUp]);
            }

            if (!empty($activeEndTimeRange)) {
                $activeEndTimeRangeArr = DateTimeHelper::getStartEndDateFromRange($activeEndTimeRange);
                $startDate = $activeEndTimeRangeArr['startDate'];
                $endDate = $activeEndTimeRangeArr['endDate'];
                $query->andWhere(['<=', 'weixin.active_end_time', $endDate]);
                $query->andWhere(['>=', 'weixin.active_end_time', $startDate]);
            }

            if ($hasUpdated != -1) {
                $query->andWhere(['weixin.has_updated' => $hasUpdated]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);

            //\Yii::trace($query->createCommand()->getRawSql(), 'dev\#' . __METHOD__);

            return $this->render('to-update-list', [
                'dataProvider' => $dataProvider
            ]);
        } else {

            $need_update_time = strtotime("+14 day");//十四天后的时间
            $query = (new Query())
                ->select(['weixin.id as media_id', 'weixin.uuid as media_uuid','weixin.pref_vendor_uuid', 'weixin.public_id', 'weixin.public_name', 'weixin.media_cate', 'weixin.follower_num', 'weixin.put_up', 'weixin.status as weixin_status', 'weixin.account_cert', 'weixin.is_activated', 'weixin.create_time', 'weixin.last_update_time', 'weixin.last_verify_time', 'weixin.vendor_cnt', 'weixin.to_verify_vendor_cnt', 'weixin.has_pref_vendor', 'weixin.order_finished_cnt', 'weixin.order_refuse_cnt', 'weixin.order_abort_cnt', 'weixin.pub_config', 'weixin.cust_sort', 'weixin.comment', 'weixin.t_media_intro', 'weixin.has_updated', 'weixin.t_comment','weixin.active_end_time',
                    'media_executor.name AS executor_name',
                    'vendor.name AS vendor_name', 'vendor.contact_person AS vendor_contact_person','vendor.contact1','vendor.comment as vendor_comment','weixin.is_push'])
                ->from(['weixin' => MediaWeixin::tableName()])
                ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  weixin.pref_vendor_uuid')
                ->leftJoin(['media_executor_assign' => MediaExecutorAssign::tableName()], 'media_executor_assign.media_uuid  =  weixin.uuid')
                ->leftJoin(['media_executor' => MediaExecutor::tableName()], 'media_executor.uuid  =  media_executor_assign.executor_uuid')
                ->where(['weixin.has_pref_vendor' => 1])
                ->where(['weixin.status' => MediaWeixin::STATUS_INFO_VERIFY_OK])
                ->andWhere(['weixin.put_up' => 1])
               ->andWhere(['<=','weixin.active_end_time',$need_update_time])
               ->orderBy(['weixin.active_end_time' => SORT_DESC]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => 0
                ]
            ]);

            // Yii::trace($query->createCommand()->getRawSql(), 'dev\#' . __METHOD__);

            return $this->render('to-update-list', [
                'dataProvider' => $dataProvider
            ]);
        }
    }



    /**
     * 置顶
     */
    public function actionSetTop()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaUUID = $request->post('media_uuid');
            MediaWeixin::updateAll(['cust_sort' => 5], ['uuid' => $mediaUUID]);
            return ['err_code' => 0, 'err_msg' => '设置成功!'];
        }
    }

    /**
     * 取消置顶
     */
    public function actionCancelTop()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaUUID = $request->post('media_uuid');
            MediaWeixin::updateAll(['cust_sort' => 0], ['uuid' => $mediaUUID]);
            return ['err_code' => 0, 'err_msg' => '设置成功!'];
        }
    }


    /**
     * 主推
     */
    public function actionSetPush()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaUUID = $request->post('media_uuid');
            MediaWeixin::updateAll(['is_push' => 1], ['uuid' => $mediaUUID]);
            return ['err_code' => 0, 'err_msg' => '设置成功!'];
        }
    }

    /**
     * 取消主推
     */
    public function actionCancelPush()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaUUID = $request->post('media_uuid');
            MediaWeixin::updateAll(['is_push' => 0], ['uuid' => $mediaUUID]);
            return ['err_code' => 0, 'err_msg' => '设置成功!'];
        }
    }


    /**
     * 审核资源
     * @return array
     * @throws ErrorException
     */
    public function actionVerify()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $uuid = $request->post('uuid');
            $publicName = $request->post('public_name');
            $followerNum = $request->post('follower_num');
            $mediaCate = $request->post('media_cate');
            $followerArea = $request->post('follower_area');
            $status = $request->post('status');
            $comment = $request->post('comment');
            $belong_type = $request->post('belong_type');

            $weixin = MediaWeixin::find()
                ->where(['uuid' => $uuid])
                ->one();

            if (!isset($weixin)) {
                throw new ErrorException('Cannot find weixin account');
            }
            if($weixin->status != $status){//状态发生改变时改变最新审核时间
                $weixin->last_verify_time = time();
            }
            $weixin->public_name = $publicName;
            $weixin->follower_num = $followerNum;
            $weixin->media_cate = $mediaCate;
            $weixin->follower_area = $followerArea;
            $weixin->status = $status;
            $weixin->comment = $comment;
            $weixin->media_belong_type = $belong_type;
            $weixin->last_update_time = time();
            $weixin->has_updated = 1; // 已经更新账号信息

            if ($status == MediaWeixin::STATUS_INFO_TO_VERIFY) {
                // 待审核 or 审核失败
                $weixin->put_up = 0;
                $weixin->in_wom_rank = 0;
            }
            $weixin->save();

            $bindList = MediaVendorBind::findAll(['media_uuid' => $uuid]);
            foreach ($bindList as $bind) {
                $bind->media_cate = $mediaCate;
                $bind->save();
            }

            return ['err_code' => 0, 'err_msg' => '更新成功!'];
        }
    }

    /**
     * 获得资源
     * @return array
     * @throws ErrorException
     */
    public function actionGetInfo()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;

        $uuid = $request->get('media_uuid');
        $weixin = MediaWeixin::find()
            ->where(['uuid' => $uuid])
            ->one();

        if (isset($weixin)) {
            $weixin->create_time = date("Y-m-d H:i", $weixin->create_time);
            return ['err_code' => 0, 'weixin' => $weixin];
        } else {
            throw new ErrorException('Cannot find such weixin account');
        }
    }

    /**
     * 资源上下架
     * @return array
     */
    public function actionPutUp()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            Yii::$app->response->format = Response::FORMAT_JSON;

            $uuid = $request->post('uuid');
            $put_up = $request->post('put_up');
            $in_wom_rank = $request->post('in_wom_rank');
            $weixin = MediaWeixin::find()
                ->where(['uuid' => $uuid])
                ->one();
            $weixin->put_up = $put_up; // 上架
            $weixin->in_wom_rank = $in_wom_rank; // 上沃米排行榜
            $weixin->last_put_up_time = time();
            $weixin->save();
            return ['err_code' => 0, 'err_msg' => '更新成功!'];
        }
    }

    /**
     * 资源详情
     */
    public function actionDetail()
    {
        $request = Yii::$app->request;
        $mediaUUID = $request->get('media_uuid');

        $media = MediaWeixin::find()
            ->where(['uuid' => $mediaUUID])
            ->one();

        return $this->render('detail', ['media' => $media]);
    }

    /**
     * 删除资源
     */
    public function actionDelete()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mediaUUID = $request->post('media_uuid');

        MediaWeixin::updateAll(['status' => MediaWeixin::STATUS_INFO_INVALID, 'put_up' => MediaWeixin::STATUS_PUT_DOWN, 'in_wom_rank' => 0], ['uuid' => $mediaUUID]);

        return ['err_code' => 0, 'err_msg' => '删除成功!'];
    }

    /**
     * 下载已选择的微信资源到excel
     */
    public function actionDownload()
    {

    }

    /**
     * 获取供应商以及报价信息
     */
    public function actionSearchPriceInfo()
    {
        $connection = Yii::$app->db;
        $request = Yii::$app->request;
        $media_weixin_uuid = $request->post('uuid');
        $sql = " SELECT
                      v.uuid,
                      v.name,
                      v.contact1,
                      vb.is_pref_vendor,
                      vpl.s_pub_type,
                      vpl.m_1_pub_type,
                      vpl.m_2_pub_type,
                      vpl.m_3_pub_type,
                      vpl.orig_price_s_min,
                      vpl.orig_price_m_1_min,
                      vpl.orig_price_m_2_min,
                      vpl.orig_price_m_3_min,
                      vpl.active_end_time
                 FROM media_vendor v
                 JOIN media_vendor_bind vb ON v.uuid = vb.vendor_uuid
                 JOIN media_vendor_weixin_price_list vpl ON vb.uuid = vpl.bind_uuid
                 JOIN media_weixin w ON vb.media_uuid = w.uuid
                 WHERE w.uuid  = '{$media_weixin_uuid}'";
        $res = $connection->createCommand($sql)->queryAll();
        foreach ($res as $key => $value) {
            $res[$key]['active_end_time'] = date("Y-m-d", $res[$key]['active_end_time']);
        }
        return json_encode(['err_code' => 0, 'err_msg' => '更新成功!', 'data' => $res]);
    }

    /**
     * 为某资源添加媒体主
     */
    public function actionAddVendor()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaUUID = $request->post('media_uuid');
            $vendorUUID = $request->post('vendor_uuid');

            $mediaVendorBind = MediaVendorBind::findOne(['media_uuid' => $mediaUUID, 'vendor_uuid' => $vendorUUID]);
            if ($mediaVendorBind !== null && $mediaVendorBind->status != MediaVendorBind::STATUS_DELETED) {
                return ['err_code' => 1, 'err_msg' => '已经存在'];
            }

            if ($mediaVendorBind === null) {
                $mediaVendorBind = new MediaVendorBind();
                $mediaVendorBind->uuid = PlatformHelper::getUUID();
                $mediaVendorBind->media_uuid = $mediaUUID;
                $mediaVendorBind->vendor_uuid = $vendorUUID;
                $mediaVendorBind->create_time = time();
                $mediaVendorBind->save();
                //vendor表
                $vendor = MediaVendor::findOne(['uuid'=>$vendorUUID]);
                $vendor->weixin_media_cnt = ($vendor->weixin_media_cnt) + 1;
                $vendor->save();

            } else {
                $mediaVendorBind->status = MediaVendorBind::STATUS_TO_VERIFY;
                $mediaVendorBind->save();
            }

            $priceList = new MediaVendorWeixinPriceList();
            $priceList->uuid = PlatformHelper::getUUID();
            $priceList->bind_uuid = $mediaVendorBind->uuid;
            $priceList->pub_config = '';
            $priceList->save();

            $weixin = MediaWeixin::findOne(['uuid' => $mediaUUID]);
            $weixin->vendor_cnt = $weixin->vendor_cnt + 1;
            $weixin->to_verify_vendor_cnt = $weixin->to_verify_vendor_cnt + 1;
            $weixin->save();

            return ['err_code' => 0, 'err_msg' => '添加成功!'];
        }
    }

    /**
     * 从某资源移除媒体主
     */
    public function actionRemoveVendor()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $bindUUID = $request->post('bind_uuid');
            //vendor表移除媒体主资源
            $vendorBind = MediaVendorBind::findOne(['uuid'=>$bindUUID]);
            $vendor = MediaVendor::findOne(['uuid'=>$vendorBind->vendor_uuid]);
            $vendor->weixin_media_cnt = ($vendor->weixin_media_cnt) - 1;
            $vendor->save();
            MediaVendorBind::updateAll(['status' => MediaVendorBind::STATUS_DELETED], ['uuid' => $bindUUID]);

            return ['err_code' => 0, 'err_msg' => '移除成功!'];
        }
    }

    /**
     * 重新审核微信"无效账号"
     */
    public function actionReVerify()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mediaUUID = $request->post('media_uuid');

            MediaWeixin::updateAll(['status' => MediaWeixin::STATUS_INFO_TO_VERIFY, 'last_update_time' => time()], ['uuid' => $mediaUUID]);

            return ['err_code' => 0, 'err_msg' => '设置成功!'];
        }
    }

}
