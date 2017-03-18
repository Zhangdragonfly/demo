<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'account' => [
            'class' => 'admin\modules\account\Module'
        ],
        'ad' => [
            'class' => 'admin\modules\ad\Module'
        ],
        'media' => [
            'class' => 'admin\modules\media\Module'
        ],
        'weixin' => [
            'class' => 'admin\modules\weixin\Module'
        ],
        'weibo' => [
            'class' => 'admin\modules\weibo\Module'
        ],
        'video' => [
            'class' => 'admin\modules\video\Module'
        ],
        'home' => [
            'class' => 'admin\modules\home\Module'
        ],
        'common' => [
            'class' => 'admin\modules\common\Module'
        ],
        'demo' => [
            'class' => 'admin\modules\demo\Module'
        ],
        'system' => [
            'class' => 'admin\modules\system\Module'
        ],
        'website' => [
            'class' => 'admin\modules\website\Module',
            'modules' =>[
                'home' => [
                    'class' => 'admin\modules\website\modules\home\Module'
                ]
            ]
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'admin\models\Account',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'loginUrl' => ['site\index'],
        ],
        'errorHandler' => [
            'errorAction' => 'site\error',
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
            ],
        ]
    ],
    'params' => $params,
];
