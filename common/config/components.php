<?php
use kartik\mpdf\Pdf;

return [
    'mailer' => [
           'class' => 'yii\swiftmailer\Mailer',
           'viewPath' => '@common/mail',
            'useFileTransport' => false,//set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'mailer.dost@gmail.com',
                'password' => 'DostRegion9',
                'port' => '587',
                'encryption' => 'tls',
                'streamOptions'=>[
                   'ssl'=>[
                        'verify_peer'=>false,
                        'verify_peer_name'=>false,
                        'allow_self_signed'=>true
                  ]
                ]
            ],
    ],
    /*
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'viewPath' => '@common/mail',
        // send all mails to a file by default. You have to set
        // 'useFileTransport' to false and configure a transport
        // for the mailer to send real emails.
        'useFileTransport' => false,
    ],*/
    'cache' => [
            'class' => 'yii\caching\FileCache',
    ],
    'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'defaultRoles' => ['Guest'],
    ],
    'user' => [
            //'identityClass' => 'mdm\admin\models\User',
            //'class'=>'mdm\admin\models\User',
            //'loginUrl' => ['admin/user/login'],
            'identityClass' => 'common\models\User',
            //'class'=>'common\models\User',
    ],
    'pdf' => [
        'class' => Pdf::classname(),
        'format' => Pdf::FORMAT_A4,
        'orientation' => Pdf::ORIENT_PORTRAIT,
        'destination' => Pdf::DEST_BROWSER,
        // refer settings section for all configuration options
    ],
    'urlManager' => [
       'class' => 'yii\web\UrlManager',
       // Disable index.php
       'showScriptName' => false,
       // Disable r= routes
       'enablePrettyUrl' => true,
       'rules' => array(
          '<controller:\w+>/<id:\d+>' => '<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
          '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
       ),
    ],
    'Notification' => [
        'class' => 'common\components\Notification'
    ],
    'NumbersToWords' => [
        'class' => 'common\components\NumbersToWords'
    ],
];
 