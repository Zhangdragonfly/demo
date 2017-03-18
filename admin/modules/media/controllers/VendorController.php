<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */
namespace admin\modules\media\controllers;

use admin\helpers\AdminHelper;
use admin\helpers\Post;
use common\helpers\DateTimeHelper;
use common\helpers\PlatformHelper;
use common\models\MediaWeixin;
use Yii;
use admin\controllers\BaseAppController;
use common\models\MediaVendor;
use common\models\WomAccount;
use common\models\MediaVendorBind;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Response;
use SoapClient;

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
     * 媒体主列表
     * @return string
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $contact = $request->post('contact', ''); // 媒体主or联系方式
            $orderTimeRange = $request->post('order-time-range', '');
            $status = $request->post('status', -1);
            $page = $request->post('page', 0);

            $query = (new Query())
                ->select(['media_vendor.uuid AS vendor_uuid', 'media_vendor.name AS vendor_name', 'media_vendor.contact_info', 'media_vendor.contact_person', 'media_vendor.contact1', 'media_vendor.contact2', 'media_vendor.weixin', 'media_vendor.qq', 'account.login_account', 'media_vendor.weixin_media_cnt', 'media_vendor.video_media_cnt', 'media_vendor.weibo_media_cnt', 'media_vendor.balance', 'media_vendor.withdraw_amount', 'media_vendor.register_type'])
                ->from(['media_vendor' => MediaVendor::tableName()])
                ->leftJoin(['account' => WomAccount::tableName()], 'account.uuid = media_vendor.account_uuid')
                ->andWhere(['!=', 'media_vendor.etl_status', 2])//未删除
                ->orderBy(['media_vendor.last_update_time' => SORT_DESC]);

            if (!empty($mediaType) && $mediaType != -1 && !empty($mediaPublicName)) {
                $query->leftJoin(['bind' => MediaVendorBind::tableName()], 'bind.vendor_uuid = media_vendor.uuid');
                $query->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = bind.media_uuid');
                $query->andWhere(['weixin.public_id' => $mediaPublicName]);
            }

            if (!empty($contact)) {
                $query->andWhere(['or', ['like', 'media_vendor.name', $contact], ['like', 'media_vendor.contact_person', $contact], ['like', 'account.login_account', $contact], ['like', 'media_vendor.contact1', $contact], ['like', 'media_vendor.contact2', $contact], ['like', 'media_vendor.weixin', $contact], ['like', 'media_vendor.qq', $contact], ['like', 'media_vendor.contact_info', $contact]]);
            }

            if ($status != -1) {
                $query->andWhere(['=', 'account.status', $status]);
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => AdminHelper::getPageSize(),
                    'page' => $page
                ]
            ]);
            return $this->render('list', ['dataProvider' => $dataProvider]);
        } else {
            $request = Yii::$app->request;
            $mediaType = $request->get('media-type', ''); // 媒体类型
            $mediaUUID = $request->get('media-uuid', ''); // 媒体uuid
            $mediaPublicId = $request->get('media-public-id', ''); // 媒体public id

            $query = (new Query())
                ->select(['media_vendor.uuid AS vendor_uuid', 'media_vendor.name AS vendor_name', 'media_vendor.contact_info', 'media_vendor.contact_person', 'media_vendor.contact1', 'media_vendor.contact2', 'media_vendor.weixin', 'media_vendor.qq', 'account.login_account', 'media_vendor.weixin_media_cnt', 'media_vendor.video_media_cnt', 'media_vendor.weibo_media_cnt', 'media_vendor.balance', 'media_vendor.withdraw_amount', 'media_vendor.register_type'])
                ->from(['media_vendor' => MediaVendor::tableName()])
                ->leftJoin(['account' => WomAccount::tableName()], 'account.uuid = media_vendor.account_uuid')
                ->andWhere(['!=', 'media_vendor.etl_status', 2])//未删除
                ->orderBy(['media_vendor.last_update_time' => SORT_DESC]);

            if (!empty($mediaType) && !empty($mediaUUID)) {
                //$mediaType == 1(微信),$mediaType == 2(微博),$mediaType == 3(视频),
                if ($mediaType == 1) {
                    $query->leftJoin(['bind' => MediaVendorBind::tableName()], 'bind.vendor_uuid = media_vendor.uuid');
                    $query->leftJoin(['weixin' => MediaWeixin::tableName()], 'weixin.uuid = bind.media_uuid');
                    $query->andWhere(['weixin.uuid' => $mediaUUID]);
                } else if ($mediaType == 2) {

                } elseif ($mediaType == 3) {

                } else {

                }
            }

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
     * 新建媒体主
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $contact_json = $request->post('contact_array');
            $contact_array = json_decode($contact_json,true);
            $vendorName = $request->post('vendor_name');
            $activeEndTime = $request->post('active_end_time');
            $comment = $request->post('comment');
            $pay_type = $request->post('pay_type');
            $pay_user = $request->post('pay_user');
            $bank_name = $request->post('bank_name');
            $bank_account = $request->post('bank_account');

            if ($activeEndTime == '') {
                $activeEndTime = -1; // 不限时间
            } else {
                $activeEndTime = strtotime($activeEndTime);
            }
            $vendor = new MediaVendor();
            $vendor->uuid = PlatformHelper::getUUID();
            $vendor->account_uuid = -1;
            $vendor->name = $vendorName;
            $vendor->contact_person = $contact_array[0]['contact_person'];
            $vendor->contact1 = $contact_array[0]['contact_phone'];
            $vendor->weixin =$contact_array[0]['weixin'];
            $vendor->qq = $contact_array[0]['qq'];
            $vendor->contact_info = $contact_json;
            $vendor->comment = $comment;
            $vendor->register_type = MediaVendor::REGISTER_TYPE_ADMIN;
            $vendor->active_end_time = $activeEndTime;
            $vendor->last_update_time = time();
            $vendor->pay_type = $pay_type;
            $vendor->pay_user = $pay_user;
            $vendor->bank_name = $bank_name;
            $vendor->bank_account = $bank_account;
            $vendor->is_exist_erp = 1;
            $vendor->save();
//            $transaction = Yii::$app->db->beginTransaction();
//            try {
//                $vendor->save();
//                //存储erp数据库的信息
//                $arr = $vendor->find()->asArray()->where(['uuid'=>$vendor->uuid])->one();
//                $res = Post::post('http://www.qmgerp.com/index.php?r=wom_data/supplier/add-supplier',$arr);
//            } catch (Exception $e) {
//                $transaction->rollBack();
//                return ['err_code' => 1, 'err_msg' => '更新失败'];
//
//            }
//            $transaction->commit();
            return ['err_code' => 0, 'err_msg' => '更新成功'];
        } else {
            return $this->render('create');
        }
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
            ->select('vendor.uuid AS vendor_uuid, media_vendor_bind.uuid AS media_vendor_bind_uuid, vendor.name AS vendor_name, vendor.contact_info, vendor.register_type, media_vendor_bind.status AS bind_status, media_vendor_bind.is_activated, media_vendor_bind.is_pref_vendor')
            ->from(['media_vendor_bind' => MediaVendorBind::tableName()])
            ->leftJoin(['vendor' => MediaVendor::tableName()], 'vendor.uuid  =  media_vendor_bind.vendor_uuid')
            ->where(['media_vendor_bind.media_uuid' => $mediaUuid, 'media_vendor_bind.status' => [0, 1, 2, 5]])
            ->orderBy(['media_vendor_bind.create_time' => SORT_ASC])
            ->all();

        return ['err_code' => 0, 'err_msg' => '获取成功', 'vendor_list' => $vendorList];
    }

    /**
     * 获取供应商信息
     */
    public function actionGet()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $vendorUUID = $request->get('vendor_uuid');

        $vendor = (new Query())
            ->select('media_vendor.*')
            ->from(['media_vendor' => MediaVendor::tableName()])
            ->where(['media_vendor.uuid' => $vendorUUID])
            ->one();

        if($vendor !== null){
            if($vendor['active_end_time'] != -1){
                $vendor['active_end_time'] = date('Y-m-d', $vendor['active_end_time']);
            } else {
                $vendor['active_end_time'] = '';
            }
        }

        return ['err_code' => 0, 'err_msg' => '获取成功', 'vendor' => $vendor];
    }

    /**
     * 更新信息
     */
    public function actionUpdate()
    {

        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $vendorUUID = $request->post('vendor_uuid');
            $vendorName = $request->post('vendor_name');
            $contactListJson = $request->post('contact_list');
            $activeEndTime = $request->post('active_end_time');
            $comment = $request->post('comment');
            $pay_type = $request->post('pay_type');
            $pay_user = $request->post('pay_user');
            $bank_name = $request->post('bank_name');
            $bank_account = $request->post('bank_account');

            if ($activeEndTime == '') {
                $activeEndTime = -1; // 不限时间
            } else {
                $activeEndTime = strtotime($activeEndTime);
            }

            $contactListArray = json_decode($contactListJson, true);
            $contact = $contactListArray[0];

            $vendor = MediaVendor::findOne(['uuid' => $vendorUUID]);
            if (isset($vendor)) {
                $vendor->name = $vendorName;
                $vendor->comment = $comment;
                $vendor->contact_person = $contact['contact_person'];
                $vendor->contact1 = $contact['contact_phone'];
                $vendor->weixin = $contact['weixin'];
                $vendor->qq = $contact['qq'];
                $vendor->contact_info = $contactListJson;
                $vendor->active_end_time = $activeEndTime;
                $vendor->pay_type = $pay_type;
                $vendor->pay_user = $pay_user;
                $vendor->bank_name = $bank_name;
                $vendor->bank_account = $bank_account;
                $vendor->save();
//                $transaction = Yii::$app->db->beginTransaction();
//                try {
//                    $vendor->save();
//                    //更新erp数据库的信息
//                    $arr = $vendor->find()->asArray()->where(['uuid'=>$vendorUUID])->one();
//                    $client = new SoapClient('http://www.qmgerp.com/index.php?r=wom_data/supplier/soap');
//                    $client->updateSupplier($arr);
//                } catch (Exception $e) {
//                    $transaction->rollBack();
//                    return ['err_code' => 1, 'err_msg' => '更新失败'];
//                }
//                $transaction->commit();
                return ['err_code' => 0, 'err_msg' => '更新成功'];
            } else {
                return ['err_code' => 1, 'err_msg' => '更新失败'];
            }
        }
    }

    /**
     * 媒体主搜索
     */
    public function actionSearch()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $vendor_search = $request->post('vendor_search');
        if(!empty($vendor_search)){
            $vendorList = (new Query())
                ->select('vendor.uuid AS vendor_uuid, vendor.name AS vendor_name, vendor.contact_info, vendor.comment, vendor.register_type')
                ->from(['vendor' => MediaVendor::tableName()])
                ->where(['or', ['like', 'vendor.name', $vendor_search], ['like', 'vendor.contact_person', $vendor_search], ['like', 'vendor.contact1', $vendor_search], ['like', 'vendor.contact2', $vendor_search], ['like', 'vendor.weixin', $vendor_search], ['like', 'vendor.qq', $vendor_search], ['like', 'vendor.contact_info', $vendor_search]])
                ->andWhere(['vendor.etl_status' => 1])
                ->orderBy(['vendor.last_update_time' => SORT_DESC])
                ->limit(5)
                ->all();
        }else{
            $vendorList = (new Query())
                ->select('vendor.uuid AS vendor_uuid, vendor.name AS vendor_name, vendor.contact_info, vendor.comment, vendor.register_type')
                ->from(['vendor' => MediaVendor::tableName()])
                ->andWhere(['vendor.etl_status' => 1])
                ->orderBy(['vendor.default_vendor' => SORT_DESC, 'vendor.last_update_time' => SORT_DESC])
                ->limit(10)
                ->all();
        }
        return ['err_code' => 0, 'err_msg' => '获取成功', 'vendor_list' => $vendorList];
    }


    //逻辑删除媒体主
    public function actionDelete(){
        $request = Yii::$app->request;
        $vendor_uuid = $request->post('vendor_uuid');
        if(!empty($vendor_uuid)){
            $vendor = MediaVendor::findOne(['uuid'=>$vendor_uuid]);
            $vendor->etl_status = 2;
            $vendor->save();
            return json_encode(['err_code' => 0, 'err_msg' => '删除成功']);
        }else{
            return json_encode(['err_code' => 1, 'err_msg' => '删除失败']);
        }
    }



    //检查媒体主是否存在
    public function actionCheckVendorName(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $vendor_name = $request->post('vendor_name');
            $Vendor = MediaVendor::find()->where(['like','name',$vendor_name])->all();
            if (!empty($Vendor)) {
                return json_encode(['err_code' => 1, 'err_msg' => '该媒体主已经存在！']);
            } else {
                return json_encode(['err_code' => 0]);
            }
        }
    }

}
