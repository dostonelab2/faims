<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Disbursement */

$this->title = 'Create Disbursement and Payment';
$this->params['breadcrumbs'][] = ['label' => 'Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disbursement-create">

    <?= $this->render('_form', [
        'model' => $model,
        'dvtype_data' => $dvtype_data,
        'listEmployee'=>$listEmployee,
        'listSono'=>$listSono,
        'listPono'=>$listPono,
        'assig' => $assig,
    ]) ?>

</div>
