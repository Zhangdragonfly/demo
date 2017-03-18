<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/15/16 2:16 PM
 */

namespace common\helpers;
use Yii;
/**
 * Class UploadHelper
 * @package common\helpers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class UploadHelper
{
    /**
     * 获取上传文件的配置
     * ["image": ["target": "xxx", "store_path": "store absolute path"], "": ""]
     * @return mixed
     */
    public static function getUploadStorageConfig()
    {
        $config = Yii::$app->params['upload'];
        return $config;
    }

    /**
     * 获取七牛的配置
     * @return mixed
     */
    public static function getQiniuConfig()
    {
        return Yii::$app->params['qiniu'];
    }
}