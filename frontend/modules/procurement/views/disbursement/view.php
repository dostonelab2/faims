<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Disbursement */

$this->params['breadcrumbs'][] = $this->title; ?>
<div class="disbursement-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dv_id',
            'dv_no',
            'dv_date',
            'particulars:ntext',
            'payee',
            'address',
            'dv_amount',
            'dv_total',
            'certified_a',
            'certified_b',
            'approved',
            'os_no',
            'taxable',
            'dv_type',
            'po_no',
            'funding_id',
            'fundings',
            'supporting_docs',
            'cash_available',
            'subject_ada',
            'user_id',
        ],
    ]) ?>

</div>
