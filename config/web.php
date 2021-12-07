<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'Nucleo',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ab09BGzu8kIA3LdqR_ZVwJfHSC5gzUH1',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
//                'multipart/form-data' => 'yii\web\MultipartFormDataParser'
            ]
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'es_ES',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
            
        ],
        
        /************** Componente interoperable *************/
        'registral'=> [
            'class' => $params['servicioRegistral'],//'app\components\ServicioLugar'
        ],
        'lugar'=> [
            'class' => $params['servicioLugar'],
        ],
        'inventario'=> [
            'class' => $params['servicioInventario'],
        ],
        /************* Fin Componente interoperable *************/
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        
        #URL #Ruteo
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [   #Producto
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/producto',
                ],  
                [   #Marca
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/marca',
                ],  
                [   #Usuario
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/usuario',   
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'OPTIONS login' => 'options'
                    ],
                    'tokens' => ['{id}'=>'<id:\\w+>', '{cuil}'=>'<cuil:\\w+>'],                       
                ],  
            ],
        ],
        
    ],
    'params' => $params,

    #Modules
    'modules'=>[
        'api' => [
            'class' => 'app\modules\api\Api',
        ],
        "audit"=>[
            "class"=>"bedezign\yii2\audit\Audit",
            "ignoreActions" =>['audit/*', 'debug/*'],
            'userIdentifierCallback' => ['app\components\ServicioUsuarios', 'userIdentifierCallback'],
            'userFilterCallback' => ['app\components\ServicioUsuarios', 'userFilterCallback'],
            'accessIps'=>null,
            'accessUsers'=>null,
            'accessRoles'=>['admin']
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableConfirmation'=> false,
            'enableRegistration'=> true,
            'enablePasswordRecovery'=> false,
            'admins'=>['admin']
        ],
        'registral' => [
            'class' => 'app\modules\registral\Registral',
        ],
        'rbac' => 'dektrium\rbac\RbacWebModule',
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
