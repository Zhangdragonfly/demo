<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM
 */

namespace wom\modules\site\controllers;

use common\helpers\ExternalFileHelper;
use common\helpers\PlatformHelper;
use wom\controllers\BaseAppController;
use Yii;
use yii\web\Response;

/**
 * 文件上传uploader
 * Class FileUploaderController
 * @package wom\modules\site\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class FileUploaderController extends BaseAppController
{
    /**
     * 上传文件
     */
    public function actionUpload()
    {
        $request = Yii::$app->request;
        $fileType = $request->get('file_type', ''); // 文件类型
        $cateCode = $request->get('cate_code', ''); // 文件分类

        if ($cateCode == ExternalFileHelper::FILE_CATE_PLAN_ORDER) {
            $targetUploadDirectory = ExternalFileHelper::getPlanOrderRelativeDirectory();
        } else if ($cateCode == ExternalFileHelper::FILE_CATE_MEDIA_WEIXIN) {
            // TODO
        } else if ($cateCode == ExternalFileHelper::FILE_CATE_MEDIA_WEIBO) {
            // TODO
        } else if ($cateCode == ExternalFileHelper::FILE_CATE_MEDIA_VIDEO) {
            // TODO
        } else {
            $targetUploadDirectory = ExternalFileHelper::getOtherRelativeDirectory();
        }

        // 文件夹不存在，则创建
        if (!is_dir($targetUploadDirectory)) {
            mkdir(iconv('UTF-8', 'GBK', $targetUploadDirectory), 0777, true);
        }
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $fileExt = substr(strrchr($_FILES['file']['name'], '.'), 1);
            $newFileName = PlatformHelper::getUUID() . '.' . $fileExt;
            $newFileFullPath = $targetUploadDirectory . $newFileName;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $newFileFullPath)) {
                return ['err_code' => 0, 'msg' => '上传成功', 'file_name' => $newFileName];
            }
            return ['err_code' => 1, 'msg' => '上传失败'];
        }
    }

    /**
     * 物理删除图片
     * @return array
     */
    public function actionDeleteFile()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $imgName = $request->get('img_name');
            $fileType = $request->get('file_type', ''); // 文件类型
            $cateCode = $request->get('cate_code', ''); // 文件分类

            if ($cateCode == ExternalFileHelper::FILE_CATE_PLAN_ORDER) {
                $targetUploadDirectory = ExternalFileHelper::getPlanOrderRelativeDirectory();
            } else if ($cateCode == ExternalFileHelper::FILE_CATE_MEDIA_WEIXIN) {
                // TODO
            } else if ($cateCode == ExternalFileHelper::FILE_CATE_MEDIA_WEIBO) {
                // TODO
            } else if ($cateCode == ExternalFileHelper::FILE_CATE_MEDIA_VIDEO) {
                // TODO
            } else {
                $targetUploadDirectory = ExternalFileHelper::getOtherRelativeDirectory();
            }

            $fileFullPath = $targetUploadDirectory . $imgName;
            if (file_exists($fileFullPath)) {
                if (unlink($fileFullPath)) {
                    return ['err_code' => 0, 'err_msg' => '删除成功'];
                } else {
                    return ['err_code' => 1, 'err_msg' => '删除失败'];
                }
            } else {
                return ['err_code' => 2, 'err_msg' => '文件不存在'];
            }
        }
    }
}