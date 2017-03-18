<?php
return [
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log'],
    'aliases' => [
        '@nineinchnick/nfy' => '@vendor/nineinchnick/yii2-nfy',
    ],
    'modules' => [
        'nfy' => [
            'class' => 'nineinchnick\nfy\Module',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => 'redis'
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'cache' => 'cache',
            'timeout'=> 24*3600,
        ],
        'weixin' => [
            'class' => 'weixin\api\Weixin',
            'appID' => 'wxe9a6a36a4a421577',
            'appSecret' => 'ffacd38a554d5eb6b3414ccbcf8f0655',
            'token' => 'yeexiao',
            'encodingAESKey' => '0HfkrxXo0H00pOdr02IaGkRZhpS31LGqAlt99nQVinp'
        ],
        'dbmq' => [
            'class' => 'nineinchnick\nfy\components\DbQueue',
            'id' => 'queue',
            'label' => 'Notifications',
            'timeout' => 30,
        ],
        'sysvmq' => [
            'class' => 'nineinchnick\nfy\components\SysVQueue',
            'id' => 'a',
            'label' => 'IPC queue',
        ],
        'redismq' => [
            'class' => 'nineinchnick\nfy\components\RedisQueue',
            'id' => 'mq',
            'label' => 'Redis queue',
            'redis' => 'redis',
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://wom-dts-datawarehouse-reader:6amsu20xsl1llpq@115.28.26.225:27011/wom-dts-datawarehouse',
        ],
    ]
];
