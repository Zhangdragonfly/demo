<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'wom',
    'bootstrap' => ['log'],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'wom\controllers',
    'modules' => [
        /* site模块 */
        'site' => [
            'class' => 'wom\modules\site\Module'
        ],
        /* 微信模块 */
        'weixin' => [
            'class' => 'wom\modules\weixin\Module'
        ],
        /* 微博模块 */
        'weibo' => [
            'class' => 'wom\modules\weibo\Module'
        ],
        /* 视频模块 */
        'video' => [
            'class' => 'wom\modules\video\Module'
        ],
        /* 广告主模块 */
        'ad-owner' => [
            'class' => 'wom\modules\adOwner\Module'
        ],
        /* 媒体主用户中心模块 */
        'media-vendor' => [
            'class' => 'wom\modules\mediaVendor\Module'
        ]
    ],
    'defaultRoute' => 'wom',
    'homeUrl' => '/', // home page url
    'components' => [
        'user' => [
            'identityClass' => 'common\models\UserAccount',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'loginUrl' => ['site\account\login']
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'wom/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace'],
                    'logVars' => [],
                    'categories' => ['dev\*'],//表示以 dev 开头的分类
                    'logFile' => '@runtime/logs/dev-trace.log',
                    'exportInterval' => 1,
                    'enabled' => true
                ],//Yii::trace('message', 'dev\#' . __METHOD__);
            ]
        ]
    ],
    'params' => $params,
];
