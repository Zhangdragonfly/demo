<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rdsvwq3t80d6dyy39hk3o.mysql.rds.aliyuncs.com;dbname=wom_prod',//51wom生产环境
            'username' => 'wom',
            'password' => 'Qmg2016mw#semys$',
            'charset' => 'utf8'
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0
        ],
    ]
];
