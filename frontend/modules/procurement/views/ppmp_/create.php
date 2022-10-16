<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Ppmp */

$this->title = 'Create PPMP';
$this->params['breadcrumbs'][] = ['label' => 'Ppmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppmp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'listDivisions' => $listDivisions,
        'chargeTo' => $chargeTo,
        'projects' => $projects,
        'years' => $years,
    ]) ?>

</div>
