<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/8/16 4:31 PM
 */

namespace wom\modules\adOwner\controllers;

use common\helpers\PlatformHelper;
use common\models\AdWeixinMaterial;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use yii;

/**
 * 广告主个人中心/微信素材库管理
 * Class AdminWeixinMaterialLibController
 * @package wom\modules\adOwner\controllers\AdminController
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class AdminWeixinMaterialLibController extends AdOwnerBaseAppController
{
    public $layout = '//admin-ad-owner';

    /**
     * 列表
     */
    public function actionList(){
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        $query = (new Query())
            ->select([
                'material.uuid',
                'material.title',
                'material.create_time',
                'material.last_update_time',
            ])
            ->distinct()
            ->from(['material' => AdWeixinMaterial::tableName()])
            ->andWhere(['material.ad_owner_uuid' => $ad_owner_uuid])
            ->orderBy(['material.last_update_time' => SORT_DESC]);
        $page = 0;
        if ($request->isPost) {//存在post搜索条件
            $page = $request->post('page');
            $search_name = $request->post('search_name');
            if (!empty($search_name)){
                $query->andWhere(['or', ['like', 'material.author', $search_name], ['like', 'material.title', $search_name]]);
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
        return $this->render('list', [
            'dataProvider' =>  $dataProvider->getModels(),
            'pager' => $pager
        ]);
    }


    /**
     * 新建
     */
    public function actionCreate(){
        $this->layout = "//site-stage";
        $request = Yii::$app->request;
        $ad_owner_uuid = $this->getLoginAccountInfo()['ad-owner-uuid'];
        if($request->isPost){
            $materialUuid = $request->post('material_uuid');
            if(!empty($materialUuid)){//更新
                $material = AdWeixinMaterial::findOne(['uuid'=>$materialUuid]);
                $material->original_mp_url = trim($request->post('orig_url'));
                $material->title = trim($request->post('title'));
                $material->author = trim($request->post('author'));
                $material->article_desc = trim($request->post('desc'));
                $material->article_content = $request->post('content');
                $material->last_update_time = time();
                $material->save();
            }else{//新建
                $material = new AdWeixinMaterial();
                $material->uuid = PlatformHelper::getUUID();
                $material->ad_owner_uuid = $ad_owner_uuid;
                $material->original_mp_url = trim($request->post('orig_url'));
                $material->title = trim($request->post('title'));
                $material->author = trim($request->post('author'));
                $material->article_desc = trim($request->post('desc'));
                $material->article_content = $request->post('content');
                $material->create_time = time();
                $material->last_update_time = time();
                $material->save();
            }
           return json_encode(['err_code'=>0,'err_msg'=>'保存成功']);
        }
        if($request->isGet){
            $materialUuid = $request->get('material_uuid');
            $material = AdWeixinMaterial::findOne(['uuid'=>$materialUuid]);
            if(!empty($material)){
                return $this->render('create',[
                    'material'=>$material
                ]);
            }else{
                return $this->render('create');
            }
        }

    }

    /**
     * 新建
     */
    public function actionDeleteMaterial(){
        $request = Yii::$app->request;
        if($request->isPost) {
            $materialUuid = $request->post('material_uuid');
            AdWeixinMaterial::deleteAll(['uuid'=>$materialUuid]);
            return json_encode(['err_code'=>0,'err_msg'=>'删除成功']);
        }else{
            return json_encode(['err_code'=>1,'err_msg'=>'删除失败']);
        }

    }

}
