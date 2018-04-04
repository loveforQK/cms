<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'=>'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'MTgzOTQxMWYyZDI5NzAyNGZkOGUwYzFhODFkZjgwMzI=',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\SysUser',
            'enableAutoLogin' => true,
            'loginUrl' => '/admin/login'
        ],
        'session'=>array(
            'timeout'=>604800,
            'name' => 'PHPSID',
            'cookieParams' => [
                'httpOnly' => true,
                'path' => '/',
                'lifetime' => 604800
            ],
        ),
        'errorHandler' => [
            'errorAction' => 'admin/site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logVars' => ['_GET'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
		'assetManager' => [
            'baseUrl'=>'@web/admin/assets',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>['/admin/static/js/jquery.min.js']
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>['/admin/static/js/bootstrap.min.js']
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => ['/admin/static/css/bootstrap.min.css'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' =>false,//这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qq.com',  //每种邮箱的host配置不一样
                'username' => '1773552326@qq.com',
                'password' => 'ddztuzqomkfzbeif',
                'port' => '25',
                'encryption' => 'tls',

            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['1773552326@qq.com'=>'系统管理员（请勿回复）']
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
];
return $config;
