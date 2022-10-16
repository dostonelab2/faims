<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */

$this->title = $model->obligation_request_id;

?>
<div class="obligationrequest-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'obligation_request_id',
            'os_no',
            'os_date',
            'particulars:ntext',
            'ppa',
            'account_code',
            'amount',
            'payee',
            'office',
            'address',
           // 'requested_by',
            //'requested_bypos',
           // 'funds_available',
            //'funds_available_pos',
            'purchase_no',
            'os_type',
            'dv_no',
           // 'user_id',
        ],
    ]) ?>

</div>
