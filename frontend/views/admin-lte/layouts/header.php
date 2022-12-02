<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\system\User;
use common\models\system\Package;
use common\models\system\Message;
use yii\helpers\ArrayHelper;
/* @var $this \yii\web\View */
/* @var $content string */
//header("Access-Control-Allow-Origin: *");
//setcookie('cross-site-cookie', 'name', ['samesite' => 'None', 'secure' => true]);
$Request_URI=$_SERVER['REQUEST_URI'];
//$_SERVER['SERVER_NAME']
if($Request_URI=='/'){//alias ex: http://admin.eulims.local
    $Backend_URI=Yii::$app->urlManagerBackend->createUrl('/');
    $Backend_URI=$Backend_URI."/uploads/user/photo/";
}else{//http://localhost/eulims/backend/web
    $Backend_URI='//localhost/faims/backend/web/uploads/user/photo/';
}
Yii::$app->params['uploadUrl']=$Backend_URI;
//echo Yii::$app->params['uploadUrl'];
//exit;
//echo $Backend_URI."/uploads/user/photo/";
//exit;
if(Yii::$app->user->isGuest){
    $CurrentUserName="Visitor";
    $CurrentUserAvatar=Yii::$app->params['uploadUrl'] . 'no-image.png';
    $CurrentUserDesignation='Guest';
    $UsernameDesignation=$CurrentUserName;
}else{
    $CurrentUser= User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
    $CurrentUserName=$CurrentUser->profile ? $CurrentUser->profile->fullname : $CurrentUser->username;
    //$CurrentUserAvatar=$CurrentUser->profile ? Yii::$app->params['uploadUrl'].$CurrentUser->profile->getImageUrl() : Yii::$app->params['uploadUrl'] . 'no-image.png';
    $CurrentUserAvatar=$CurrentUser->profile ? "/images/user/photo/" . $CurrentUser->profile->avatar : "/images/user/photo/" . 'no-image.png';
    $CurrentUserDesignation=$CurrentUser->profile ? $CurrentUser->profile->designation : '';
    if($CurrentUserDesignation==''){
       $UsernameDesignation=$CurrentUserName;
    }else{
       $UsernameDesignation=$CurrentUserName.'-'.$CurrentUserDesignation;
    }
}

$moduleDir= Yii::$app->basePath.'/modules';
$Directories = scandir($moduleDir);
$Packages= Package::find()->all();
$conditions = ['to' => Yii::$app->user->id, 'status' => 0];
$messages=Message::find()->where($conditions)->all();
$TotalMsg=count($messages);
if($TotalMsg<=0){
    $TotalUnreadMessage="You have no message.";
}elseif($TotalMsg==1){
    $TotalUnreadMessage="You have $TotalMsg unread message.";
}else{
    $TotalUnreadMessage="You have $TotalMsg unread messages.";
}
if($TotalMsg==0){
    $TotalMsg='';
}
?>

<header class="main-header">
    <?= Html::a(Html::img(Yii::$app->request->baseUrl."/images/logo.png",['title'=>'Finance and Administrative Information System','alt'=>'Finance and Administrative Information System','height'=>'45px','width'=>'100%']), Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        
        <div class="navbar-custom-menu">
            
            <ul class="nav navbar-nav">
                <li class="dropdown tasks-menu">
                    <ul class="dropdown-menu">
        
                    </ul>
                </li>
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><?= $TotalMsg ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?= $TotalUnreadMessage ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <?php foreach($messages as $message){ ?>
                                    <a href="<?= Url::to(['/message/message/view','hash'=>$message->hash]) ?>">
                                            <div class="pull-left">
                                                <span><?= $message->sender->username ?></span>
                                            </div>
                                            <h4>
                                                <?= $message->title ?>
                                                <small><i class="fa fa-clock-o"></i> <?= $message->created_at ?></small>
                                            </h4>
                                            <p><?= $message->message ?></p>
                                        </a> 
                                    <?php } ?>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="<?= Url::to($GLOBALS['frontend_base_uri'].'message/message/inbox') ?>">View all Messages</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-warning text-yellow"></i> Very long description here that may
                                        not fit into the page and may cause design problems
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-red"></i> 5 new members joined
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-red"></i> You changed your username
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $CurrentUserAvatar ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= $CurrentUserName ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $CurrentUserAvatar ?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
                            <?= $CurrentUserName ?>
                                <small><?= $UsernameDesignation ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>

            </ul>
        </div>
    </nav>
</header>
