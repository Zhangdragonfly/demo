<?php
namespace common\helpers;

use yii;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Processing\PersistentFop;
use Qiniu\Storage\BucketManager;

class QiniuHelper{
    /**
     * @param string $filePath (本地服务器的相对路径)
     * @return mixed
     * @throws \Exception
     */
    public static function getUploadToken($filePath='')
    {
        // 用于签名的公钥和私钥
        $accessKey = 'ejzGYs5bUiP9v-LeBlXYeNTrqXemJPXKS3xe-fBT';
        $secretKey = '8I4YlwQNM5-qf2FWlpLS5ltqwT23dhUlSOD-DuLR';
        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);
        // 空间名
        // http://developer.qiniu.com/docs/v6/api/overview/concepts.html#bucket
        $bucket = 'upload-51wom';
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径

        // 上传到七牛后保存的文件名
        $key = 'WX-article-crawl-'.time().'-'.rand(1000,9999).substr($filePath,12,30);

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        return $ret;
    }
}