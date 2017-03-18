<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/23/16 10:39 AM
 */

namespace admin\modules\common\controllers;

use common\helpers\UploadHelper;
use yii\web\Response;
use yii\web\UploadedFile;
use yii;
use yii\web\Controller;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use common\helpers\PlatformHelper;
/**
 * Class FileStorageController
 * @package frontend\modules\common\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class FileStorageController extends Controller
{
    /**
     * 获取七牛的up token
     * @return array
     */
    public function actionGetUpTokenOfQiniu()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $module = $request->get('module');

        $bucket = Yii::$app->params['qiniu']['bucket'][$module];
        $accessKey = Yii::$app->params['qiniu']['accessKey'];
        $secretKey = Yii::$app->params['qiniu']['secretKey'];
        $auth = new Auth($accessKey, $secretKey);
        $upToken = $auth->uploadToken($bucket);

        return ['uptoken' => $upToken];
    }

    /**
     * 使用七牛API上传文件
     */
    public function actionUploadFileToQiniu()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $module = $request->get('module');

        $bucket = Yii::$app->params['qiniu']['bucket'][$module];
        $accessKey = Yii::$app->params['qiniu']['accessKey'];
        $secretKey = Yii::$app->params['qiniu']['secretKey'];
        $domain = Yii::$app->params['qiniu']['domain'][$module];

        // 获取上传Token
        $auth = new Auth($accessKey, $secretKey);
        $upToken = $auth->uploadToken($bucket);

        // 要上传文件的本地路径
        $uploadedFile = UploadedFile::getInstanceByName('fileToUpload');
        $uploadedFileName = $uploadedFile->tempName;
        // 上传到七牛后保存的文件名
        $targetFileName = time() . rand(0, 10000) . '.' . $uploadedFile->extension;
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($upToken, $targetFileName, $uploadedFileName);

        if ($err !== null) {
            return ['errcode' => 500, 'errmsg' => $err];
        } else {
            return ['errcode' => 200, 'errmsg' => '', 'file' => $domain . '/' . $ret['key'], 'fileName' => $ret['key']];
        }
    }

    /**
     * 从七牛移除文件
     */
    public function actionRemoveFileFromQiniu()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $module = $request->post('module');
        $fileName = $request->post('fileName'); // 待删资源的文件名

        $bucket = Yii::$app->params['qiniu']['bucket'][$module]; // 待删资源所在的空间
        $accessKey = Yii::$app->params['qiniu']['accessKey'];
        $secretKey = Yii::$app->params['qiniu']['secretKey'];
        // 获取上传Token
        $auth = new Auth($accessKey, $secretKey);

        // 获得BucketManager
        $bucketMgr = new BucketManager($auth);
        $rtn = $bucketMgr->delete($bucket, $fileName);

        //var_dump($rtn);
        if ($rtn === null) {
            return ['errcode' => 200, 'errmsg' => '删除成功'];
        } else {
            return ['errcode' => 500, 'errmsg' => '删除失败'];
        }
    }

    /**
     *上传图片
     */
    public function actionUploadImage(){
        $request = Yii::$app->request;
        $avatar = $request->get('avatar');
        $uploadParams =  UploadHelper::getUploadStorageConfig();
        if($avatar == 1) {
            $path = $uploadParams['avatar']['store_path'];
        }else{
            $path = $uploadParams['image']['store_path'];
        }
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            //文件夹不存在，则创建
            if(!is_dir($path)){
                mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            }
            $newFileName = PlatformHelper::getUUID() . substr($_FILES["file"]["name"], -4) ;
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $path . $newFileName  )){
                return ['err_code' => 1, 'msg' => $newFileName];
            }
            return ['err_code' => 0, 'msg' => '上传失败'];

        }

    }

    /**
     * TODO: 上传文件
     */
    public function  actionUploadFile(){
        $uploadParams =  UploadHelper::getUploadStorageConfig();
        $cate = $uploadParams['file']['cate'];
        if($cate == 'local'){
            if (Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $path = $uploadParams['file']['local_path'];
                //文件夹不存在，则创建
                if(!is_dir($path)){
                    mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
                }
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $path . time(). iconv("UTF-8", "GBK", $_FILES["file"]["name"]) )){

                    return ['err_code' => 1, 'msg' => time().$_FILES["file"]["name"]];

                }

                return ['err_code' => 0, 'msg' => '上传失败'];

            }
        }else if($cate == 'qiniu'){

        }
    }

    /**
     * TODO:物理删除需要删除的文件
     */
    public function actionDelFile()
    {
        $request = Yii::$app->request;
        $uploadParams =  UploadHelper::getUploadStorageConfig();
        $cate = $uploadParams['image']['cate'];
        if($cate == 'local'){
            if($request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $img = $request->post('img');
                $path = $uploadParams['image']['local_path'];
                if(!is_dir($path)){
                    return ['err_code' => 1, 'err_msg' => '文件目录不存在'];
                }
                $img_path = $path.$img;//img路径
                if(file_exists($img_path)){
                    if(unlink($img_path)){
                        return ['err_code' => 0, 'err_msg' => '删除成功'];
                    }else{
                        return ['err_code' => 1, 'err_msg' => '删除失败'];
                    }
                }else{
                    return ['err_code' => 1, 'err_msg' => '文件不存在'];
                }
            }
        }else if($cate == 'qiniu'){

        }

    }



}