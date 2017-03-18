<?php
return [
    // 网站域名配置
    'domain' => [
        'home' => 'http://www.51wom.com', // 首页
        'weixin' => 'http://weixin.51wom.com', // 微信二级域名
        'weibo' => 'http://weibo.51wom.com', // 微博二级域名
        'video' => 'http://video.51wom.com', // 视频二级域名
        'admin' => 'http://admin.51wom.com' // admin二级域名
    ],

    // 2.0 文件上传
    // target (local 表示本地服务器, qiniu 表示七牛云存储)
    // store_path 存储的路径
    'upload' => [
        'image' => ['target' => 'local', 'store_path' => '/alidata1/51wom-upload/img/'], // 图片
        'avatar' => ['target' => 'local', 'store_path' => '/alidata1/51wom-upload/img/avatar/'], // 头像
        'file' => ['target' => 'local', 'store_path' => '/alidata1/51wom-upload/file/'] // 文件
    ],

    // 2.1文件上传
    'common.upload.target-dir' => '/alidata1/51wom.com/external-file',

    // 2.1 最新的文件目录
    'common.external_file.root_dir.absolute' => '/alidata1/51wom.com/external_file/',  // 外部文件目录的绝对根目录
    'common.external_file.root_dir.relative' => 'external_file/', // 外部文件目录的相对根目录

    'common.external_file.home_page.absolute' => '/alidata1/51wom.com/external_file/home_page/',  // 首页
    'common.external_file.home_page.relative' => 'external_file/home_page/', // 首页

    'common.external_file.media_video.absolute' => '/alidata1/51wom.com/external_file/media/video/',  // 视频资源
    'common.external_file.media_video.relative' => 'external_file/media/video/', // 视频资源

    'common.external_file.plan_order.absolute' => '/alidata1/51wom.com/external_file/plan_order/',  // 资源投放
    'common.external_file.plan_order.relative' => 'external_file/plan_order/', // 资源投放

    'common.external_file.other.absolute' => '/alidata1/51wom.com/external_file/other/',  // 其他图片
    'common.external_file.other.relative' => 'external_file/other/', // 其他图片

    'media.video.default_avatar' => 'external_file/media/video/avatar/default_avatar.png' // 视频默认头像
];
