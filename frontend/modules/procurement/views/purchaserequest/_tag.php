<?php

/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/16/2018
 * Time: 9:16 AM
 */

use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\procurement\Purchaserequest;
use common\models\procurement\Section;
use common\models\procurement\Division;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use common\modules\pdfprint;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\PurchaserequestSearch */
/* @var $model common\models\procurement\Lineitembudget */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="purchaserequest-modal">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'purchase_request_number',
            //'purchase_request_date',
            [
                'label'  => 'subject_type',
                'value'  => date("m-d-Y", strtotime($model->purchase_request_date)),
            ],
            'purchase_request_purpose',
            /* 'purchase_request_referrence_no',
            'purchase_request_project_name',
            'purchase_request_location_project',
           */
        ],
    ]) ?>

    <?php
    $fin = "";
    $x = 0;
    foreach ($prdetails as $pr) {
        $x++;
        if ($x == 1) {
            $unit = $pr["name_short"];
        } else {
            $unit = $pr["name_long"];
        }
        $itemdescription = $pr["purchase_request_details_item_description"];
        $quantity = $pr["purchase_request_details_quantity"];
        $price = $pr["purchase_request_details_price"];
        $totalcost =  $quantity * $price;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='10%' style='padding-left: 60px;'>" . $unit . "</td>";
        $append = $append . "<td width='54%' style='text-align: justify;padding-left: 40px;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 35px;'>" . $quantity . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 30px;'>" . number_format($price, 2) . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 35px;'>" . number_format($totalcost, 2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>
    <div class="table-scroll">
        <table border="1" width="100%">
            <tr class=\"nospace-border\">
                <td width='10%' style='padding-left: 60px;'> Unit</td>
                <td width='54%' style='text-align: justify;padding-left: 40px;'> Item Description</td>
                <td width='12%' style='padding-left: 35px;'>Quantity</td>
                <td width='12%' style='padding-left: 30px;'>Price</td>
                <td width='12%' style='padding-left: 35px;'>Total</td>
            </tr>
            <?php
            echo $fin;
            ?>
        </table>
    </div>
</div>

<?php
if(Yii::$app->user->can('approve-pr')){
    if ($model->status == 3) {
        echo Html::button('Approve  <i class="fa fa-thumbs-up"></i>', [
            'value' => $model->purchase_request_id,
            'title' => 'Approve',
            'class' => 'btn-approve btn btn-success btn-block',
            'style' => 'margin-right: 6px; display: "";',
            'id' => 'buttonApprove'
        ]);
    }
}

if(Yii::$app->user->can('review-pr')){
    if ($model->status == 0) {
        echo Html::button('Review  <i class="fa fa-eye"></i>', [
            'value' => $model->purchase_request_id,
            'title' => 'Review',
            'class' => 'btn-review btn btn-info btn-block',
            'style' => 'margin-right: 6px; display: "";',
            'id' => 'buttonReview'
        ]);
    }
}

if(Yii::$app->user->can('disapprove-pr')){
    echo Html::button('Disapprove  <i class="fa fa-thumbs-down"></i>', [
        'value' => $model->purchase_request_id,
        'title' => 'Disapprove',
        'class' => 'btn-disapprove btn btn-danger btn-block',
        'style' => 'margin-right: 6px; display: "";',
        'id' => 'buttonDisapprove'
    ]);
}
?>