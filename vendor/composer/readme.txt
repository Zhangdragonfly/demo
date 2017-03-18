
autoload_namespaces.php

return array(
    'HTMLPurifier' => array($vendorDir . '/ezyang/htmlpurifier/library'),
    'Diff' => array($vendorDir . '/phpspec/php-diff/lib'),
    'weixin' => array($vendorDir . '/weixin')
);

autoload_psr4.php

return array(
    'yii\\swiftmailer\\' => array($vendorDir . '/yiisoft/yii2-swiftmailer'),
    'yii\\redis\\' => array($vendorDir . '/yiisoft/yii2-redis'),
    'yii\\gii\\' => array($vendorDir . '/yiisoft/yii2-gii'),
    'yii\\faker\\' => array($vendorDir . '/yiisoft/yii2-faker'),
    'yii\\debug\\' => array($vendorDir . '/yiisoft/yii2-debug'),
    'yii\\composer\\' => array($vendorDir . '/yiisoft/yii2-composer'),
    'yii\\codeception\\' => array($vendorDir . '/yiisoft/yii2-codeception'),
    'yii\\bootstrap\\' => array($vendorDir . '/yiisoft/yii2-bootstrap'),
    'yii\\' => array($vendorDir . '/yiisoft/yii2'),
    'cebe\\markdown\\' => array($vendorDir . '/cebe/markdown'),
    'Qiniu\\' => array($vendorDir . '/qiniu/php-sdk/src/Qiniu'),
    'Faker\\' => array($vendorDir . '/fzaninotto/faker/src/Faker'),
    'Pingpp\\' => array($vendorDir . '/pingplusplus/pingpp-php/lib'),
);