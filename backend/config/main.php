<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'backend\modules\v1\Module',
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/user'],
                    'except' => ['delete'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/post'],
                    'extraPatterns' => [
                        'POST {id}/like' => 'createLike',
                        'DELETE {id}/like' => 'deleteLike',
                        'GET,HEAD {id}/like' => 'indexLike',
                        'OPTIONS {id}/like' => 'optionsLike',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/access-token'],
                    'patterns' => [
                        'POST'=>'create', 
                        'PUT,PATCH' => 'update', 
                        'DELETE' => 'delete'
                    ],
                    'pluralize' => false,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
    ],
    'params' => $params,
];
