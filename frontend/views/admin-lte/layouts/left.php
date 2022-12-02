<?php
use common\models\finance\Request;
use common\models\system\User;
use common\models\procurementplan\Ppmp;

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
//count ppmp status for badges
$ppmp_pending = Ppmp::find()
                    ->where(['year' => date('Y'), 'status_id' => 1])
                    ->count();
$ppmp_submitted = Ppmp::find()
                    ->where(['year' => date('Y'), 'status_id' => 2])
                    ->count();
$ppmp_approved = Ppmp::find()
                    ->where(['year' => date('Y'), 'status_id' => 3])
                    ->count();   

//count Financial Request badges
$request_for_approval = Request::find()
                    ->where(['status_id' => Request::STATUS_CHARGED])
                    ->count();

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
                            ['label' => 'CSE PPMP', 'icon' => 'clipboard', 'url' => ['/procurementplan/ppmp/index'],
                                /**
                                 * @var int|string $ppmp_approved
                                 * @var int|string $ppmp_submitted
                                 * @var int|string $ppmp_pending
                                 */
                            'template' => '<a href="{url}">
                                                    {icon}
                                                    {label}
                                                    <span class="pull-right-container">
                                                    <span class="label label-success pull-right">'.$ppmp_approved.'</span>
                                                    <span class="label label-info pull-right">'.$ppmp_submitted.'</span>
                                                    <span class="label label-warning pull-right">'.$ppmp_pending.'</span>
                                                    </span>
                                                </a>',
                        ],
                            ['label' => 'Non CSE PPMP', 'icon' => 'clipboard', 'url' => '#'],
                            ['label' => 'CSE APP', 'icon' => 'file-text', 'url' => ['/procurementplan/app/index']],
                            ['label' => 'Non CSE APP', 'icon' => 'file-text', 'url' => '#'],
                        ]
                    ],
                    [
                        'label' => 'Cashier', 
                        'icon' => 'archive', 
                        'visible'=> Yii::$app->user->can('access-cashiering'),
                        'items' => [
                            ['label' => 'LDDAP-ADA', 'icon' => 'money', 'url' => ['/cashier/lddapada/index']],
                            ['label' => 'LDDAP-ADA (Beta)', 'icon' => 'money', 'url' => ['/cashier/lddapadaitem/index']],
                            ['label' => 'Creditors', 'icon' => 'clipboard', 'url' => ['/cashier/creditor/index']],
                            [
                                'label' => 'Report of Disbursement', 
                                'icon' => 'ruble text-aqua', 
                                'url' => ['/finance/osdv/report'], 
                                'visible'=> Yii::$app->user->can('access-cashiering')
                            ],
                            ['label' => 'Update Counters', 'icon' => 'gear', 'url' => ['/cashier/creditor/index']],
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
                            ['label' => 'PPMP', 'icon' => 'clipboard', 'url' => ['/budget/ppmp/index'], 'visible'=> Yii::$app->user->can('access-budget-management')],
                            ['label' => 'Expenditure Objects (UACS)', 'icon' => 'gear', 'url' => ['/procurement/expenditureobject/index'], 'visible'=> Yii::$app->user->can('access-budget')],
                            //['label' => 'Obligation', 'icon' => 'clipboard', 'url' => ['/budget/obligation/index']],
                        ]
                    ],
                    [
                        'label' => 'Purchasing', 
                        'icon' => 'tasks', 
                        //'url' => ['/settings'],
                        'visible'=> Yii::$app->user->can('access-procurement'),
                        'items' => [
                            ['label' => 'Purchase Request', 'icon' => 'cart-plus', 'url' => ['/procurement/purchaserequest/index']],
                            ['label' => 'Purchase Request V2', 'icon' => 'cart-plus', 'url' => ['/procurement/purchaserequest2/index']],
                            /*['label' => 'Obligation Request', 'icon' => 'object-ungroup', 'url' => ['/procurement/obligationrequest/index']],*/
                            ['label' => 'Quotations, Bids and Awards', 'icon' => 'object-ungroup', 'url' => ['/procurement/bids/index'],'visible'=> Yii::$app->user->can('access-bidsquotation')],
                            // ['label' => 'Purchase Order', 'icon' => 'tags', 'url' => ['/procurement/purchaseorder/index'],'visible'=> Yii::$app->user->can('access-purchaseorder')],
                            ['label' => 'Purchase Order', 'icon' => 'tags', 'url' => ['/procurement/purchaseorder2/index'],'visible'=> Yii::$app->user->can('access-purchaseorder')],
                            ['label' => 'Inspection and Acceptance', 'icon' => 'search', 'url' => ['/procurement/inspection'],'visible'=> Yii::$app->user->can('access-inspection')],
                            /*['label' => 'Disbursement and Payment', 'icon' => 'ruble ', 'url' => ['/procurement/disbursement']],*/
                        ]
                    ],
                    /*[
                        'label' => 'Finance', 
                        'icon' => 'line-chart', 
                        'visible'=> Yii::$app->user->can('access-procurement'),
                        'items' => [
                            ['label' => 'Obligation Request', 'icon' => 'object-ungroup', 'url' => ['/procurement/obligationrequest/index']],
                            ['label' => 'Disbursement and Payment', 'icon' => 'ruble ', 'url' => ['/procurement/disbursement']],
                        ]
                    ],
                    [
                        'label' => 'Financial Request', 
                        'icon' => 'folder-open text-aqua', 
                        'visible' => (Yii::$app->user->can('access-osdv') || (Yii::$app->user->identity->username == 'Admin') ),
                        'items' => [
                            [
                                'label' => 'Dashboard' , 
                                'icon' => 'dashboard text-aqua', 
                                'url' => ['/finance/default/index'], 
                            ],
                            [
                                'label' => 'Obligation and Disbursement', 
                                'icon' => 'ruble text-aqua', 
                                'url' => ['/finance/osdv/coaindex'], 
                            ],
                        ]
                    ],*/
                    [
                        'label' => 'Financial Request', 
                        'icon' => 'folder-open text-aqua', 
                        
                        'visible'=> Yii::$app->user->can('access-finance'),
                        //'visible'=> false,
                        'items' => [
                            
                            [
                                'label' => 'Dashboard' , 
                                'icon' => 'dashboard text-aqua', 
                                'url' => ['/finance/default/index'], 
                            ],
                            [
                                'label' => 'Requests', 
                                'icon' => 'paperclip text-aqua', 
                                'url' => ['/finance/request/index']
                            ],
                            [
                                'label' => 'Verification', 
                                'icon' => 'check text-aqua', 
                                'url' => ['/finance/request/verifyindex'], 
                                'visible'=> Yii::$app->user->can('access-finance-verification') //|| (Yii::$app->user->identity->username == 'Admin')
                            ],
                            [
                                'label' => 'Validation', 
                                'icon' => 'search text-aqua', 
                                'url' => ['/finance/request/validateindex'], 
                                'visible'=> Yii::$app->user->can('access-finance-validation') //|| (Yii::$app->user->identity->username == 'Admin')
                            ],
                            [
                                'label' => 'Obligation', 
                                'icon' => 'ruble text-aqua', 
                                'visible'=> Yii::$app->user->can('access-finance-obligation') || (Yii::$app->user->identity->username == 'Admin'),
                                'items' => [
                                    [
                                        'label' => 'Pending', 
                                        'icon' => 'ruble text-aqua', 
                                        'url' => ['/finance/osdv/obligationindex'], 
                                    ],
                                    [
                                        'label' => 'Obligated', 
                                        'icon' => 'ruble text-aqua', 
                                        'url' => ['/finance/osdv/obligatedindex'], 
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Disbursement', 
                                'icon' => 'ruble text-aqua', 
                                'url' => ['/finance/osdv/disbursementindex'], 
                                'visible'=> Yii::$app->user->can('access-finance-disbursement') || (Yii::$app->user->identity->username == 'Admin'),
                                'items' => [
                                    [
                                        'label' => 'Pending', 
                                        'icon' => 'ruble text-aqua', 
                                        'url' => ['/finance/osdv/disbursementindex'], 
                                    ],
                                    [
                                        'label' => 'Disbursed', 
                                        'icon' => 'ruble text-aqua', 
                                        'url' => ['/finance/osdv/disbursedindex'], 
                                    ],
                                ]
                            ],
                            [
                                'label' => 'For Approval' , 
                                'icon' => 'thumbs-up text-aqua', 
                                'url' => ['/finance/osdv/approvalindex'], 
                                'badge' => '<span class="fa fa-angle-left pull-right">dry-run</span>',
                                
                                'template' => '<a href="{url}">
                                                    {icon}
                                                    {label}
                                                    <span class="pull-right-container">
                                                        <span class="label label-info pull-right">'.$request_for_approval.'</span>
                                                    </span>
                                                </a>',
                                
                                'visible'=> Yii::$app->user->can('access-finance-approval') //|| (Yii::$app->user->identity->username == 'Admin')
                            ],
                            [
                                'label' => 'Reports', 
                                'icon' => 'ruble text-aqua', 
                                'url' => ['/finance/osdv/reportpayroll'], 
                                'visible'=> Yii::$app->user->can('access-finance-processing') || Yii::$app->user->can('access-finance-approval'),
                                'items' => [
                                    [
                                        'label' => 'Report of Disbursement', 
                                        'icon' => 'ruble text-aqua', 
                                        'url' => ['/finance/osdv/report'], 
                                    ],
                                    [
                                        'label' => 'Report of Disbursement (P)', 
                                        'icon' => 'ruble text-aqua', 
                                        'url' => ['/finance/osdv/reportpayroll'], 
                                    ],
                                    [
                                        'label' => 'Check Disbursement Journal', 
                                        'icon' => 'ruble text-aqua', 
                                        'url' => ['/finance/osdv/checkdisbursementjournal'],
                                    ],
                                    [
                                        'label' => 'Skip OS/DV', 
                                        'icon' => 'ruble text-aqua', 
                                        'url' => ['/finance/osdv/skip'], 
                                    ],
                                ],
                            ],
                            /*[
                                'label' => 'Report of Disbursement', 
                                'icon' => 'ruble text-aqua', 
                                'url' => ['/finance/osdv/reportpayroll'], 
                                'visible'=> Yii::$app->user->can('access-finance-processing') || Yii::$app->user->can('access-finance-approval')//|| (Yii::$app->user->identity->username == 'Admin')
                            ],
                            [
                                'label' => 'Check Disbursement Journal', 
                                'icon' => 'ruble text-aqua', 
                                'url' => ['/finance/osdv/checkdisbursementjournal'], 
                                'visible'=> Yii::$app->user->can('access-finance-processing') || Yii::$app->user->can('access-finance-approval')//|| (Yii::$app->user->identity->username == 'Admin')
                            ],*/
                            [
                                'label' => 'Obligation and Disbursement', 
                                'icon' => 'ruble text-aqua', 
                                'url' => ['/finance/osdv/index'], 
                                'visible'=> Yii::$app->user->can('access-finance-processing') || Yii::$app->user->can('access-dv')
                            ],
                            
                            
                            
                            [
                                'label' => 'Approved Requests', 
                                'icon' => 'check text-aqua', 
                                'url' => ['/finance/request/approvedindex'], 
                                'visible'=> Yii::$app->user->can('access-finance-documentcollation') //|| (Yii::$app->user->identity->username == 'Admin')
                            ],
                            [
                                'label' => 'Signed Documents Uploader', 
                                'icon' => 'check text-aqua', 
                                'url' => ['/finance/request/signeduploadindex'], 
                                'visible'=> Yii::$app->user->can('access-finance-documentcollation') //|| (Yii::$app->user->identity->username == 'Admin')
                            ],
                            
                            [
                                'label' => 'Request Types', 
                                'icon' => 'object-ungroup text-aqua', 
                                'url' => ['/finance/requesttype/index'], 
                                //'visible'=> (Yii::$app->user->identity->username == 'Admin')
                                'visible'=> Yii::$app->user->can('access-finance-verification')//  || (Yii::$app->user->identity->username == 'Admin')
                            ],
                            [
                                'label' => 'Fund Sources', 
                                'icon' => 'object-ungroup text-aqua', 
                                'url' => ['/finance/obligationtype/index'], 
                                //'visible'=> (Yii::$app->user->identity->username == 'Admin')
                                'visible'=> Yii::$app->user->can('access-finance-verification')  || (Yii::$app->user->identity->username == 'Admin')
                            ],
                            [
                                'label' => 'Expenditure Objects', 
                                'icon' => 'object-ungroup text-aqua', 
                                'url' => ['/procurement/expenditureobject/index'], 
                                //'visible'=> (Yii::$app->user->identity->username == 'Admin')
                                'visible'=> Yii::$app->user->can('access-finance-verification')//  || (Yii::$app->user->identity->username == 'Admin')
                            ],
                            [
                                'label' => 'Creditors', 
                                'icon' => 'clipboard text-aqua', 
                                'url' => ['/cashier/creditor/index'], 
                                'visible'=> Yii::$app->user->can('access-finance-processing')
                            ],
                            [
                                'label' => 'Payee and Creditor Requests', 
                                'icon' => 'clipboard text-aqua', 
                                'url' => ['/cashier/creditortmp/validateindex'], 
                                'visible'=> Yii::$app->user->can('access-finance-validatecreditor')
                            ],
                            [
                                'label' => 'Attachment Uploader' , 
                                'icon' => 'upload text-aqua', 
                                'url' => ['/finance/osdv/approvalindex'], 
                                'badge' => '<span class="fa fa-angle-left pull-right">dry-run</span>',
                                'visible'=> Yii::$app->user->can('access-finance-fileupload') //|| (Yii::$app->user->identity->username == 'Admin')
                            ],
                            //['label' => 'Request', 'icon' => 'object-ungroup', 'url' => ['/finance/request/index']],
                        ]
                    ],
                    [
                        'label' => 'Employee Compensation', 
                        'icon' => 'ruble green', 
                        'url' => ['/employeecompensation/payroll/index'],
                        'visible'=> Yii::$app->user->can('access-employee-compensation'),
                        'items' => [
                            ['label' => 'Payroll Regular', 'icon' => 'commenting', 'url' => ['/employeecompensation/payroll/index']],
                            ['label' => 'Payroll Contractual', 'icon' => 'commenting', 'url' => ['/employeecompensation/payroll']],
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
                            ['label' => 'Items', 'icon' => 'tag', 'url' => ['/procurementplan/item']],
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
                            ['label' => 'Logs', 'icon' => 'list-alt text-orange', 'url' => ['/logs/blockchain/index'],'visible'=> Yii::$app->user->can('access-gii')],
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
                        'icon' => 'user',
                        //'url' => ['/settings'],
                        //'visible'=> Yii::$app->user->can('access-settings'),
                        'items' => [
                            ['label' => 'Profile', 'icon' => 'user', 'url' => ['/profile'],'visible'=> Yii::$app->user->can('access-settings')],
                            ['label' => 'Change Password', 'icon' => 'key', 'url' => ['/admin/user/change-password'],'visible'=> Yii::$app->user->can('access-settings')],
                            ['label' => 'Login', 'icon' => 'user', 'url' => ['/site/login'],'visible'=>  Yii::$app->user->isGuest],
                            ['label' => 'Sign Out', 'icon' => 'user-times'  , 'url' => Yii::$app->urlManager->createUrl(['/site/logout']), 'visible' => !Yii::$app->user->isGuest, 'template' => '<a href="{url}" data-method="post">{icon}{label}</a>'],
                        ]
                    ],
                ],
            ]
        ) ?>
    </section>
    
</aside>
