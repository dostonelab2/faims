<?php

use yii\helpers\Html;
use yii\helpers\url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\procurement\Division;
use common\models\procurement\Section;
use common\models\procurement\Project;
use common\models\procurement\Tmpitem;
use yii\helpers\Json;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Purchaserequest */
/* @var $form yii\widgets\ActiveForm */

$BaseURL = $GLOBALS['frontend_base_uri'];

$con =  Yii::$app->db;
$command = $con->createCommand("SELECT `tbl_profile`.`user_id`,CONCAT(`tbl_profile`.`firstname`,', ', `tbl_profile`.`middleinitial` ,' ', `tbl_profile`.`lastname`, ' - ' , `tbl_profile`.`designation`) AS employeename
        FROM `tbl_profile`");
$employees = $command->queryAll();
$listEmployees = ArrayHelper::map($employees, 'user_id', 'employeename');

//var_dump(Json::encode($itemDataProvider));
//echo Yii::$app->session->getId();
//$this->registerCss($this->render('additem-modal.css'));
?>

<div class="purchaserequest-form">
    <?php $form = ActiveForm::begin(['id' => 'create_pr']); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'section_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Section::find()->all(), 'section_id', 'name'),
                'options' => ['placeholder' => 'Select a section...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
            <?= $form->field($model, 'project_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Project::find()->all(), 'project_id', 'code'),
                'options' => ['placeholder' => 'Select a project ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'purchase_request_number')->textInput(['placeholder' => '<AutoGenerated>', 'readonly' => 'true', 'tabindex' => '-1']) ?>
            <?= $form->field($model, 'purchase_request_sai_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'purchase_request_date')->input("date", ['value' => $model->isNewRecord ? date("Y-m-d") : $model->purchase_request_date]) ?>
            <?= $form->field($model, 'purchase_request_saidate')->textInput()->input("date", ['value' => $model->isNewRecord ? date("Y-m-d") : $model->purchase_request_saidate]) ?>
        </div>

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Items</h3>

                    <div class="box-tools pull-right">
                        <!--
                        <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#toggle-pane-pr-item"><i class="fa fa-minus"></i>
                        </button>
                        -->
                        <button type="button" class="btn btn-box-tool"><i class="fa fa-plus" id="plus-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body collapse" id="toggle-pane-pr-item" style="">
                    <div class="row">
                        <div class="col-md-2">
                            <?=
                            Html::button('Add Items  <i class="fa fa-plus-circle"></i>', [
                                //'disabled' => $model->status_id != 1 or !$isMember,
                                //'value' => Url::to(['create']),
                                'title' => 'Add Items',
                                'class' => 'btn btn-success btn-block',
                                'style' => 'margin-bottom: 6px; display: "";',
                                'id' => 'btnAdditems',
                            ])
                            ?>
                        </div>
                        <div class=col-md-12>
                            <div class="selected-item-table">
                                <?php
                                echo GridView::widget([
                                    'dataProvider' => $itemDataProvider,
                                    //'summary' => '',
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'options' => [
                                            'enablePushState' => false,
                                            'id' => 'selected-item-grid',
                                            'timeout' => 1000,
                                            'clientOptions' => ['backdrop' => false]
                                        ],
                                    ],
                                    'options' => ['style' => 'table-layout:fixed;'],
                                    //'tableOptions' => ['class' => 'my-item-table'],
                                    //'showPageSummary' => true,
                                    'columns' => [
                                        [
                                            'class' => '\kartik\grid\SerialColumn'
                                        ],
                                        [
                                            'attribute' => 'unit_description'
                                        ],
                                        [
                                            'attribute' => 'description',
                                        ],
                                        [
                                            'attribute' => 'qty',
                                            'header' => 'Quantity',
                                            'options' => ['style' => 'width:12%'],
                                            'value' => function ($model) {
                                                return Html::textInput('qty[' . $model->tmppritems_id . ']', $model->qty, [
                                                    'type' => 'number',
                                                    'style' => 'width:100%;',
                                                    'onkeyup' => 'onQty(' . $model->tmppritems_id . ',this.value,' . $model->cost . ')'
                                                ]);
                                            },
                                            'format' => 'raw'
                                        ],
                                        [
                                            'attribute' => 'cost'
                                        ],
                                        [
                                            'attribute' => 'cost',
                                            'header' => 'Total',
                                            'format' => 'html',
                                            'value' => function ($model) {
                                                return '<div class="total-' . $model->tmppritems_id . '">' . $model->cost * $model->qty . '</div>';
                                            }
                                        ],
                                        [
                                            'class' => 'kartik\grid\ActionColumn',
                                            'header' => '',
                                            'options' => ['style' => 'width:20px;'],
                                            'template' => '{remove}',
                                            'buttons' => [
                                                'remove' => function ($url, $model) {
                                                    return Html::button('<i class="fa fa-minus"></i>', [
                                                        //'disabled' => $model->status_id != 1 or !$isMember,
                                                        'value' =>  $model->tmppritems_id,
                                                        'title' => 'Remove Item',
                                                        'class' => 'btn btn-danger btn-circle buttonRemoveItem',
                                                        'style' => 'margin-right: 6px; display: "";',
                                                    ]);
                                                },
                                            ],
                                        ],
                                    ],
                                    'responsive' => true,
                                    'hover' => true
                                ]);
                                ?>
                            </div>
                            <!--
                                <div class="table-responsive collapse" id="myAdd">
                                    <table class="table no-margin">
                                        <thead>
                                            <tr>
                                                <th>Unit</th>
                                                <th>Item Description</th>
                                                <th>Unit Cost</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">Box</a></td>
                                                <td>Call of Duty IV</td>
                                                <td>50,000</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-circle pull-right"><i class="fa fa-plus"></i></button>
                                                </td>
                                            <tr>
                                        </tbody>
                                    </table>
                                </div>
                                -->
                            <!-- /.table-responsive -->
                        </div>
                        <div class=col-md-12 style="margin-bottom: 10px;">
                            <?php /*
                            Html::button('Add Items  <i class="glyphicon glyphicon-list"></i>', [
                                //'disabled' => $model->status_id != 1 or !$isMember,
                                //'value' => Url::to(['create']),
                                'title' => 'Add Items',
                                'class' => 'btn btn-success btn-block',
                                'style' => 'margin-right: 6px; display: "";',
                                'id' => 'buttonmyAdd'
                            ])*/
                            ?>
                        </div>
                        <!--
                            <div class=col-md-12>
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Unit</th>
                                            <th>Item Description</th>
                                            <th>Quantity</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="pages/examples/invoice.html">Box</a></td>
                                            <td>Call of Duty IV</td>
                                            <td class="editable-col-qty" contenteditable="true">1</span></td>
                                            <td>50,000</td>
                                            <td>50,000</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-circle pull-right"><i class="fa fa-minus"></i></button>
                                            </td>
                                        <tr>
                                            <td><a href="pages/examples/invoice.html">Box</a></td>
                                            <td>Call of Duty IV</td>
                                            <td class="editable-col-qty" contenteditable="true">1</span></td>
                                            <td>50,000</td>
                                            <td>50,000</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-circle pull-right"><i class="fa fa-minus"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><a href="pages/examples/invoice.html">Box</a></td>
                                            <td>Call of Duty IV</td>
                                            <td class="editable-col-qty" contenteditable="true">1</span></td>
                                            <td>50,000</td>
                                            <td>50,000</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-circle pull-right"><i class="fa fa-minus"></i></button>
                                            </td>
                                        </tr>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        -->

                    </div>

                    <div class="box-footer clearfix" style="">

                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>


        <div class="col-md-12">
            <?= $form->field($model, 'purchase_request_purpose')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'purchase_request_referrence_no')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'purchase_request_project_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'purchase_request_location_project')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-12">
            <h5 data-step='11' data-intro='Select Assignatory.'>
                <h4 style="border-bottom: #1c1c1c 2px solid;text-transform: uppercase;margin-top: 0px">Assignatory</h4>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'purchase_request_requestedby_id')->widget(Select2::classname(), [
                'data' => $listEmployees,
                'id' => 'cboEmployee',
                'name' => 'cboEmployee',
                'language' => 'en',
                'options' => ['placeholder' => 'Select Employee'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'purchase_request_approvedby_id')->widget(Select2::classname(), [
                'data' => $listEmployees,
                'id' => 'cboApproved',
                'name' => 'cboApproved',
                'language' => 'en',
                'options' => ['placeholder' => 'Select Employee', 'value' => '2'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-lg-12">
            <h4 style="border-bottom: #1c1c1c 2px solid;text-transform: uppercase;"></h4>
        </div>

        <div class="form-group col-md-12">
            <?= Html::submitButton($model->isNewRecord ? 'Create Purchase Request' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<!-- The Modal -->
<div id="modal-additems" class="modal-additems-class modal-additems-hide">
    <!-- Modal content -->
    <div class="modal-additems-content">
        <div class="modal-additems-head"><span class="close">&times;</span></div>
        <div class="modal-additems-body">
            <div class="item-table">
                <?php
                echo GridView::widget([
                    'dataProvider' => $itemDataProvider,
                    //'summary' => '',
                    'pjax' => true,
                    'pjaxSettings' => [
                        'options' => [
                            'enablePushState' => false,
                            'id' => 'pr-item-grid',
                            'timeout' => 1000,
                            'clientOptions' => ['backdrop' => false]
                        ],
                    ],
                    'options' => ['style' => 'table-layout:fixed;'],
                    //'tableOptions' => ['class' => 'my-item-table'],
                    //'showPageSummary' => true,
                    'columns' => [
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            //'headerOptions' => ['class' => 'kartik-sheet-style'],
                            'name' => 'ppmp-item', //additional
                            'checkboxOptions' => function ($model, $key, $index, $column) {
                                //$bool = Tmpitem::find()->where(['item_id' => $model->item_id, 'checked' => 1])->count();
                                return [
                                    'checked' => $model->checked == 1 ? true : false,
                                    'value' => $model->tmppritems_id,
                                    'onclick' => 'onCheck(this.value,this.checked)' //additional
                                ];
                            }
                        ],
                        [
                            'attribute' => 'unit_description'
                        ],
                        [
                            'attribute' => 'description',
                        ],
                        [
                            'attribute' => 'cost'
                        ],
                    ],
                    'responsive' => true,
                    'hover' => true
                ]);
                ?>
            </div>
        </div>

    </div>
</div>



<script>
    $(document).ready(function() {
        /*
        $("#buttonmyAdd").click(function() {
            $("#myAdd").slideToggle('fast', function() {
                $("#myAdd").remove();
            });
            //$("p").remove();
        });*/
        //$("#buttonmyAdd").click(function() {
        //$("#myAdd").slideToggle();
        //$("p").remove();
        //});
        $("input.select-on-check-all").hide();
        $("#plus-minus").click(function() {

            $("#toggle-pane-pr-item").slideToggle('slow', function() {
                if ($('#toggle-pane-pr-item').is(':hidden')) {
                    console.log('hidden');
                    $('#plus-minus').removeClass('fa-minus');
                    $('#plus-minus').addClass('fa-plus');
                } else {
                    console.log('show');
                    $('#plus-minus').removeClass('fa-plus');
                    $('#plus-minus').addClass('fa-minus');
                }

            });
            //$("p").remove();
        });
        /*
        $(".editable-col-qty").on("keypress keyup blur", function(event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });*/

        $('body').on('change', '#purchaserequest-section_id', function() {
            var selectSection = true;
            var year = new Date($('#purchaserequest-purchase_request_date').val());
            //$("#purchaserequest-project_id").prop("readonly", true);
            if ($(this).val() == '') {
                $('#purchaserequest-project_id').attr("disabled", false)
            } else {
                $('#purchaserequest-project_id').attr("disabled", true)
            }
            $.pjax.reload({
                async: true,
                type: "POST",
                container: "#pr-item-grid",
                url: "<?php echo Url::to(['purchaserequest2/create']); ?>",
                data: {
                    section: $(this).val(),
                    year: year.getFullYear(),
                    selectSection: selectSection
                },
            }).done(function() {
                $("input.select-on-check-all").hide();
                $.pjax.reload({
                    async: true,
                    type: "POST",
                    container: "#selected-item-grid",
                    url: "<?php echo Url::to(['purchaserequest2/create']); ?>",
                    data: {
                        section: $("#purchaserequest-section_id").val(),
                        reloadsectionitems: true,
                        //selectSection: false
                    },
                });
            });
        });
        $('body').on('change', '#purchaserequest-project_id', function() {
            var selectProject = true;
            var year = new Date($('#purchaserequest-purchase_request_date').val());
            //$("#purchaserequest-project_id").prop("readonly", true);
            if ($(this).val() == '') {
                $('#purchaserequest-section_id').attr("disabled", false)
            } else {
                $('#purchaserequest-section_id').attr("disabled", true)
            }
            $.pjax.reload({
                async: true,
                type: "POST",
                container: "#pr-item-grid",
                url: "<?php echo Url::to(['purchaserequest2/create']); ?>",
                data: {
                    selectProject: selectProject,
                    project: $(this).val(),
                    year: year.getFullYear()
                }
            }).done(function() {
                $("input.select-on-check-all").hide();
                var selectProject = true;
                $.pjax.reload({
                    async: true,
                    type: "POST",
                    container: "#selected-item-grid",
                    url: "<?php echo Url::to(['purchaserequest2/create']); ?>",
                    data: {
                        project: $("#purchaserequest-project_id").val(),
                        reloadprojectitems: true,
                    },
                });
            });
        });
        $('body').on('click', '.buttonRemoveItem', function() {
            var removeitem = true;
            $.pjax.reload({
                async: true,
                type: "POST",
                container: "#selected-item-grid",
                url: "<?php echo Url::to(['create']); ?>",
                data: {
                    //section: $("#purchaserequest-section_id").val(),
                    tmppritems_id: $(this).val(),
                    removeitem: true,
                    section: $("#purchaserequest-section_id").val(),
                },
            }).done(function() {
                //console.log('success');
                $.pjax.reload({
                    async: true,
                    type: "POST",
                    container: "#pr-item-grid",
                    url: "<?php echo Url::to(['purchaserequest2/create']); ?>",
                    data: {
                        section: $("#purchaserequest-section_id").val(),
                        reloadremoveditems: true,
                    },
                });
            });
        });
        //----show Additems modal------
        $('body').on('click', '#btnAdditems', function() {
            //alert('hello claris!!!');
            $('div.modal-additems-class').removeClass('modal-additems-hide');
            $('div.modal-additems-class').addClass('modal-additems-show');
        });
        //----close Additems modal------
        $('body').on('click', 'span.close', function() {
            //alert('hello claris!!!');
            $('div.modal-additems-class').removeClass('modal-additems-show');
            $('div.modal-additems-class').addClass('modal-additems-hide');
        });
    });
</script>

<script type="text/javascript">
    function onCheck(item_id, checked) {
        var checkItem = true;
        $.pjax.reload({
            async: true,
            type: "POST",
            container: "#selected-item-grid",
            url: "<?php echo Url::to(['purchaserequest2/create']); ?>",
            data: {
                itemId: item_id,
                checked: checked,
                checkItem: checkItem,
                section: $("#purchaserequest-section_id").val()
            }
        })
    }

    function onQty(item_id, value, cost) {
        $.ajax({
            async: true,
            type: "POST",
            url: "<?php echo Url::to(['updateqty']); ?>",
            data: {
                item_id: item_id,
                qty: value
            },
            success: function(data) {
                //console.log('success');
                $("div.total-" + item_id).html((value * cost).toFixed(2))
            }
        });
    }
</script>