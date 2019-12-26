<?php
use common\models\system\User;

$Request_URI=$_SERVER['REQUEST_URI'];
if($Request_URI=='/'){
    $Backend_URI=Yii::$app->urlManagerBackend->createUrl('/');
    $Backend_URI=$Backend_URI."/uploads/user/photo/";
}else{
    $Backend_URI='//localhost/faims/backend/web/uploads/user/photo/';
}
Yii::$app->params['uploadUrl']=$Backend_URI;
if(Yii::$app->user->isGuest){
    $CurrentUserName="Visitor";
    $CurrentUserAvatar=Yii::$app->params['uploadUrl'] . 'no-image.png';
    $CurrentUserDesignation='Guest';
    $UsernameDesignation=$CurrentUserName;
}else{
    $CurrentUser= User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
    $CurrentUserName=$CurrentUser->profile ? $CurrentUser->profile->fullname : $CurrentUser->username;
    $CurrentUserAvatar=$CurrentUser->profile ? Yii::$app->params['uploadUrl'].$CurrentUser->profile->getImageUrl() : Yii::$app->params['uploadUrl'] . 'no-image.png';
    $CurrentUserDesignation=$CurrentUser->profile ? $CurrentUser->profile->designation : '';
    if($CurrentUserDesignation==''){
       $UsernameDesignation=$CurrentUserName;
    }else{
       $UsernameDesignation=$CurrentUserName.'<br>'.$CurrentUserDesignation;
    }
}
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $CurrentUserAvatar ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $UsernameDesignation ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => 'Procurement Plan', 
                        'icon' => 'archive', 
                        //'url' => ['/settings'],
                        'visible'=> Yii::$app->user->can('access-procurementplan'),
                        'items' => [
                            //['label' => 'Line-Item Budget', 'icon' => 'money', 'url' => ['/procurementplan/lineitembudget/index']],
                            ['label' => 'PPMP', 'icon' => 'clipboard', 'url' => ['/procurementplan/ppmp/index']],
                            ['label' => 'APP', 'icon' => 'file-text', 'url' => ['/procurementplan/app/index']],
                        ]
                    ],
                    [
                        'label' => 'Budget', 
                        'icon' => 'archive', 
                        //'url' => ['/settings'],
                        'visible'=> Yii::$app->user->can('access-budget'),
                        'items' => [
                            ['label' => 'Budget Estimate per NEP', 'icon' => 'money', 'url' => ['/budget/expenditure/index']],
                            ['label' => 'Budget Allocation', 'icon' => 'money', 'url' => ['/budget/budgetallocation/index']],
                            ['label' => 'PPMP', 'icon' => 'clipboard', 'url' => ['/budget/ppmp/index']],
                            ['label' => 'Obligation', 'icon' => 'clipboard', 'url' => ['/budget/saob/index']],
                        ]
                    ],
                    [
                        'label' => 'Purchasing', 
                        'icon' => 'tasks', 
                        //'url' => ['/settings'],
                        'visible'=> Yii::$app->user->can('access-procurement'),
                        'items' => [
                            ['label' => 'Purchase Request', 'icon' => 'cart-plus', 'url' => ['/procurement/purchaserequest/index']],
                            /*['label' => 'Obligation Request', 'icon' => 'object-ungroup', 'url' => ['/procurement/obligationrequest/index']],*/
                            ['label' => 'Quotations, Bids and Awards', 'icon' => 'object-ungroup', 'url' => ['/procurement/bids/index'],'visible'=> Yii::$app->user->can('access-bidsquotation')],
                            ['label' => 'Purchase Order', 'icon' => 'tags', 'url' => ['/procurement/purchaseorder/index'],'visible'=> Yii::$app->user->can('access-purchaseorder')],
                            ['label' => 'Inspection and Acceptance', 'icon' => 'search', 'url' => ['/procurement/inspection'],'visible'=> Yii::$app->user->can('access-inspection')],
                            /*['label' => 'Disbursement and Payment', 'icon' => 'ruble ', 'url' => ['/procurement/disbursement']],*/
                        ]
                    ],
                    [
                        'label' => 'Finance', 
                        'icon' => 'line-chart', 
                        'visible'=> Yii::$app->user->can('access-procurement'),
                        'items' => [
                            ['label' => 'Obligation Request', 'icon' => 'object-ungroup', 'url' => ['/procurement/obligationrequest/index']],
                            ['label' => 'Disbursement and Payment', 'icon' => 'ruble ', 'url' => ['/procurement/disbursement']],
                        ]
                    ],
                    /*[
                        'label' => 'Evaluation', 
                        'icon' => 'line-chart', 
                        'url' => ['/settings'],
                        'visible'=> Yii::$app->user->can('access-evaluation'),
                        'items' => [
                            ['label' => 'Performance Evaluation', 'icon' => 'commenting', 'url' => ['/procurement/performance']],
                            ['label' => 'PAR', 'icon' => 'briefcase', 'url' => ['/procurement/par']],
                            ['label' => 'ICS', 'icon' => 'book', 'url' => ['/procurement/ics']],
                        ]
                    ],*/
                    [
                        'label' => 'Libraries', 
                        'icon' => 'book', 
                        'visible'=> Yii::$app->user->can('access-book'),
                        'items' => [
                            ['label' => 'Suppliers', 'icon' => 'truck', 'url' => ['/procurement/supplier']],
                            ['label' => 'Unit Type', 'icon' => 'cog', 'url' => ['/procurement/unittype']],
                            ['label' => 'Position', 'icon' => 'fa fa-user-o', 'url' => ['/procurement/position']],
                            ['label' => 'Division', 'icon' => 'cog', 'url' => ['/procurement/division']],
                            ['label' => 'Section', 'icon' => 'cog', 'url' => ['/procurement/section']],
                            ['label' => 'Report Configuration', 'icon' => 'th-list', 'url' => ['/procurement/assignatory'],'visible'=> Yii::$app->user->can('access-system-tools')],
                        ]
                    ],
                    [
                        'label' => 'System tools',
                        'icon' => 'cogs',
                        'url' => '/#',
                        'visible'=> Yii::$app->user->can('access-system-tools'),
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],'visible'=> Yii::$app->user->can('access-gii')],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],'visible'=> Yii::$app->user->can('access-debug')],
                            ['label' => 'Package List', 'icon' => 'cog', 'url' => ['/package'],'visible'=> Yii::$app->user->can('access-package-list')],
                            ['label' => 'Package Manager', 'icon' => 'cog', 'url' => ['/package/manager'],'visible'=> Yii::$app->user->can('access-package')],
                            [
                                'label' => 'RBAC',
                                'icon' => 'fa fa-user-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Users', 'icon' => 'fa fa-user-o', 'url' => ['/admin/user'],'visible'=> Yii::$app->user->can('access-user')],
                                    ['label' => 'Groups', 'icon' => 'dashboard', 'url' => ['/admin/group'],'visible'=> Yii::$app->user->can('access-user')],
                                    ['label' => 'Assignment', 'icon' => 'dashboard', 'url' => ['/admin'],'visible'=> Yii::$app->user->can('access-assignment')],
                                    ['label' => 'Route', 'icon' => 'line-chart', 'url' => ['/admin/route'],'visible'=> Yii::$app->user->can('access-route')],
                                    ['label' => 'Roles', 'icon' => 'glide-g', 'url' => ['/admin/role'],'visible'=> Yii::$app->user->can('access-role')],
                                    ['label' => 'Permissions', 'icon' => 'resistance', 'url' => ['/admin/permission'],'visible'=> Yii::$app->user->can('access-permission')],
                                    ['label' => 'Menus', 'icon' => 'scribd', 'url' => ['/admin/menu'],'visible'=> Yii::$app->user->can('access-menu')],
                                    ['label' => 'Rules', 'icon' => 'reorder', 'url' => ['/admin/rule'],'visible'=> Yii::$app->user->can('access-rule')],
                                ],
                                'visible'=> Yii::$app->user->can('access-rbac')
                            ],
                        ],
                    ],

                    [
                        'label' => 'Account Setting',
                        'icon' => 'user-secret',
                        //'url' => ['/settings'],
                        //'visible'=> Yii::$app->user->can('access-settings'),
                        'items' => [
                            ['label' => 'Profile', 'icon' => 'user', 'url' => ['/profile'],'visible'=> Yii::$app->user->can('access-settings')],
                            ['label' => 'Login', 'icon' => 'user', 'url' => ['/site/login'],'visible'=>  Yii::$app->user->isGuest],
                            ['label' => 'Sign Out', 'icon' => 'user-times'  , 'url' => Yii::$app->urlManager->createUrl(['/site/logout']), 'visible' => !Yii::$app->user->isGuest, 'template' => '<a href="{url}" data-method="post">{icon}{label}</a>'],
                        ]
                    ],
                ],
            ]
        ) ?>
    </section>

</aside>
