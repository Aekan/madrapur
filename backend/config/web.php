<?php

$config = [
    'homeUrl' => Yii::getAlias('@backendUrl'),
    'controllerNamespace' => 'backend\controllers',

    'defaultRoute' => 'timeline-event/index',
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'cookieValidationKey' => env('BACKEND_COOKIE_VALIDATION_KEY'),
            'baseUrl' => env('BACKEND_BASE_URL'),
        ],
        'user' => [
            'class' => yii\web\User::class,
            'identityClass' => common\models\User::class,
            'loginUrl' => [
                'sign-in/login'
            ],
            'enableAutoLogin' => true,
            'as afterLogin' => common\behaviors\LoginTimestampBehavior::class,
        ],
    ],
    'modules' => [
        'ModulusCart' => [
            'class' => backend\modules\ModulusCart\Module::class,
        ],
        'gridview' => [
            'class' => kartik\grid\Module::class,
        ],
        'Payment' => [
                'class' => backend\modules\Payment\Module::class,
        ],
        'redactor' => [
            'class' => yii\redactor\RedactorModule::class,
        ],
        'content' => [
            'class' => backend\modules\content\Module::class,
        ],
        'widget' => [
            'class' => backend\modules\widget\Module::class,
        ],
        'file' => [
            'class' => backend\modules\file\Module::class,
        ],
        'madActiveRecord' => [
            'class' => backend\modules\MadActiveRecord\Module::class,
        ],
        'Order' => [
            'class' => backend\modules\Order\Module::class,
        ],
        'Products' => [
            'class' => backend\modules\Products\Module::class,
        ],
        'Product' => [
            'class' => backend\modules\Product\Module::class,
        ],
        'Reservations' => [
            'class' => backend\modules\Reservations\Module::class,
        ],
        'system' => [
            'class' => backend\modules\system\Module::class,
        ],
        'translation' => [
            'class' => backend\modules\translation\Module::class,
        ],
        'rbac' => [
            'class' => backend\modules\rbac\Module::class,
            'defaultRoute' => 'rbac-auth-item/index',
        ],
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
            //'root' => '@app',             // The root directory of the project scan.
            'root' => '@webroot',               // The root directory of the project scan.
            'layout' => null,               // Name of the used layout. If using own layout use 'null'.
            'allowedIPs' => [$_SERVER['REMOTE_ADDR']],  // IP addresses from which the translation interface is accessible.
            'roles' => ['@'],               // For setting access levels to the translating interface.
            'tmpDir' => '@runtime',         // Writable directory for the client-side temporary language files.
            // IMPORTANT: must be identical for all applications (the AssetsManager serves the JavaScript files containing language elements from this directory).
            'phpTranslators' => ['::t'],    // list of the php function for translating messages.
            'jsTranslators' => ['lajax.t'], // list of the js function for translating messages.
            'patterns' => ['*.js', '*.php'],// list of file extensions that contain language elements.
            'ignoredCategories' => ['yii'], // these categories won't be included in the language database.
            'ignoredItems' => ['config'],   // these files will not be processed.
            'scanTimeLimit' => null,        // increase to prevent "Maximum execution time" errors, if null the default max_execution_time will be used
            'searchEmptyCommand' => '!',    // the search string to enter in the 'Translation' search field to find not yet translated items, set to null to disable this feature
            'defaultExportStatus' => 1,     // the default selection of languages to export, set to 0 to select all languages by default
            'defaultExportFormat' => 'json',// the default format for export, can be 'json' or 'xml'
            'tables' => [                   // Properties of individual tables
                [
                    'connection' => 'db',   // connection identifier
                    'table' => '{{%language}}',         // table name
                    'columns' => ['name', 'name_ascii'],// names of multilingual fields
                    'category' => 'database-table-name',// the category is the database table name
                ]
            ]
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',
        ],
    ],
    'as globalAccess' => [
        'class' => common\behaviors\GlobalAccessBehavior::class,
        'rules' => [
            [
                'controllers' => [
                    'sign-in'
                ],
                'allow' => true,
                'roles' => [
                    '?'
                ],
                'actions' => [
                    'login'
                ],
            ],
            [
                'controllers' => [
                    'sign-in'
                ],
                'allow' => true,
                'roles' => [
                    '@'
                ],
                'actions' => [
                    'logout'
                ],
            ],
            [
                'controllers' => [
                    'site'
                ],
                'allow' => true,
                'roles' => [
                    '?',
                    '@'
                ],
                'actions' => [
                    'error'
                ],
            ],
            [
                'controllers' => [
                    'debug/default'
                ],
                'allow' => true,
                'roles' => [
                    '?'
                ],
            ],
            [
                'controllers' => [
                    'user'
                ],
                'allow' => true,
                'roles' => [
                    'administrator'
                ],
            ],
            [
                'controllers' => [
                    'user'
                ],
                'allow' => false,
            ],
            [
                'allow' => true,
                'roles' => [
                    'manager',
                    'administrator'
                ],
            ],
        ],
    ],
];

define("WEB_ROOT", ".");
define("OTP", "../../../../simplepay/");

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => yii\gii\Module::class,
        'generators' => [
            'crud' => [
                'class' => yii\gii\generators\crud\Generator::class,
                'templates' => [
                    'yii2-starter-kit' => Yii::getAlias('@backend/views/_gii/templates'),
                ],
                'template' => 'yii2-starter-kit',
                'messageCategory' => 'backend',
            ],
        ],
    ];
}

return $config;
