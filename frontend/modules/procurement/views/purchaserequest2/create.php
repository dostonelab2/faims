<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Purchaserequest */

$this->title = 'Create Purchaserequest';
$this->params['breadcrumbs'][] = ['label' => 'Purchaserequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="purchaserequest-create">

    <?= $this->render('_form', [
        'model' => $model,
        'itemDataProvider' => $itemDataProvider,
    ]) ?>

</div>
