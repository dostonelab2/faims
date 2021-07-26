<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\web\View;
use yii2mod\alert\Alert;
use common\components\Functions;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PurchaserequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Purchase Request';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/vue.min.js', ['position' => View::POS_BEGIN]);
$this->registerJsFile('/js/axios.min.js', ['position' => View::POS_BEGIN]);
$this->registerJsFile('/js/vue-tables-2.min.js', ['position' => View::POS_BEGIN]);
//$this->registerJsFile('/js/vue-pagination-2.min.js', ['position' => View::POS_BEGIN]);
$this->registerJsFile('/js/purchaserequest/ajax-modal-popup.js');
$this->registerCss($this->render('pr-modal-additems.css'));

$func = new Functions();
?>
<div class="purchaserequest-index" id="pr-app">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2><i class="fa fa-cart-plus"></i><?= Html::encode(' PURCHASE REQUEST') ?></h2>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <?php // Html::a('Create Purchase Request', ['create'], ['class' => 'btn btn-success btn-block']) 
                    ?>
                    <?=
                    Html::button('Create Purchase Request  <i class="glyphicon glyphicon-list"></i>', [
                        //'disabled' => $model->status_id != 1 or !$isMember,
                        'value' => Url::to(['create']),
                        'title' => 'Create Purchase Request',
                        'class' => 'btn btn-success btn-block',
                        'style' => 'margin-right: 6px; display: "";',
                        'id' => 'buttonCreatePR'
                    ])
                    ?>
                </div>
            </div>
            <v-client-table :data="datalist" :columns="columns" :options="options">
            <div slot="po_number" slot-scope="props" v-html="props.row.po_number"></div>
            </v-client-table>
            <!--
            <table id="pr-table" class="table table-bordered table-striped">
                <thead>

                    <tr>
                        <th style="width: 10.98%;" data-col-seq="0">
                            <a class="desc" href="/procurement/purchaserequest2/index?sort=purchase_request_number" data-sort="purchase_request_number">PR Number</a>
                        </th>
                        <th style="width: 39.95%;" data-col-seq="1">
                            Purpose
                        </th>
                        <th data-col-seq="2" style="width: 18.58%;">
                            Division
                        </th>
                        <th data-col-seq="3" style="width: 9.4%;">
                            Requested by
                        </th>
                        <th data-col-seq="4" style="width: 9.04%;">
                            P.O. Numbers</th>
                        <th class="kv-align-center kv-align-middle skip-export" style="width: 12%;" data-col-seq="5">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="data in datalist">
                        <td>{{data.purchase_request_number}}</td>
                        <td>{{data.purchase_request_purpose}}</td>
                        <td>{{data.division_name}}</td>
                        <td>{{data.requested_by}}</td>
                        <td v-html="data.po_number">
                        </td>
                        <td class="skip-export kv-align-center kv-align-middle" style="width:80px;" data-col-seq="5"><button type="button" id="buttonViewPR" class="btn btn-success btn-sm" value="/procurement/purchaserequest2/view?id={{data.purchase_request_id}}" title="" data-toggle="tooltip" data-original-title="view"><span class="glyphicon glyphicon-eye-open"></span></button> <button type="button" id="buttonUpdatePR" class="btn btn-info btn-sm" value="/procurement/purchaserequest2/update?id=820" title="" data-toggle="tooltip" data-original-title="update"><span class="glyphicon glyphicon-pencil"></span></button> <a class="btn btn-warning btn-sm" href="/procurement/purchaserequest/reportprfull?id=820" title="" data-toggle="tooltip" target="_blank" data-original-title="print"><span class="glyphicon glyphicon-print"></span></a></td>
                    </tr>
                </tbody>
            </table>
            <pagination :records="pagesize" v-model="page" :per-page="perpage" @paginate="readdata">
            -->
        </div>
    </div>
</div>

<?php
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'createPRModal',
    //'size' => 'modal-lg',
    'options' => [
        'tabindex' => false,
    ],
]);
echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader2" style="color: #ffffff"></h4>',
    'id' => 'viewPRModal',
    //'size' => 'modal-lg',
    'options' => [
        'tabindex' => false,
    ],
]);
echo "<div id='modalContent2'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

// This section will allow to popup a notification
$session = Yii::$app->session;
if ($session->isActive) {
    $session->open();
    if (isset($session['deletepopup'])) {
        $func->CrudAlert2("Deleted Successfully", Alert::TYPE_WARNING);
        unset($session['deletepopup']);
        $session->close();
    }
    if (isset($session['updatepopup'])) {
        $func->CrudAlert2("Updated Successfully");
        unset($session['updatepopup']);
        $session->close();
    }
    if (isset($session['savepopup'])) {
        $func->CrudAlert2("Saved Successfully", Alert::TYPE_SUCCESS, true);
        unset($session['savepopup']);
        $session->close();
    }
    if (isset($session['errorpopup'])) {
        $func->CrudAlert2("Error Transaction", Alert::TYPE_WARNING, true);
        unset($session['errorpopup']);
        $session->close();
    }
}


?>


<script>
    $(document).ready(function() {
        $('div.sa-confirm-button-container button.confirm').click(function() {
            location.reload();
        });
    });

    //Vue.use(VueTables.ClientTable);
    Vue.use(VueTables.ClientTable,{
        compileTemplates: true,
        filterByRows: true,
        texts:{
            filter: 'Search:'
        }
    });
    var application = new Vue({
        el: '#pr-app',
        data() {
            return {
                columns: [
                    'purchase_request_number',
                    'purchase_request_purpose',
                    'division_name',
                    'requested_by',
                    'po_number',
                    'actions'
                ],
                datalist: [],
                options: {
                    headings: {
                        purchase_request_number: 'PR Number',
                        purchase_request_purpose: 'Purpose',
                        division_name: 'Division',
                        requested_by: 'Requested by',
                        po_number: 'PO Number',
                        actions: 'Actions'
                    },
                    perPage:20
                },
            }
        },
        
        mounted() {
            this.readdata();
            this.$refs.table.setLimit(1);

        },
        methods: {
            readdata: function() {
                axios.get(`/ajax/purchaserequest2`).then(function(response) {
                    application.datalist = response.data;
                })
            }
        }
    });
</script>