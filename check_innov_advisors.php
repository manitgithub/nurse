<?php
// Simple script to test if we can extract top advisors
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/config/web.php',
    require __DIR__ . '/config/web-local.php'
);

(new yii\web\Application($config));

$topAdvisors = \app\models\Innovation::find()
    ->select(['advisor as label', 'COUNT(*) as total'])
    ->where(['not', ['advisor' => null]])
    ->andWhere(['!=', 'advisor', ''])
    ->groupBy('advisor')
    ->orderBy(['total' => SORT_DESC])
    ->limit(10)
    ->asArray()->all();

print_r($topAdvisors);
