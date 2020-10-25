<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\widgets\SwitchInput;

use yii\bootstrap\Modal;
use common\models\cashier\Creditor;
use common\models\finance\Request;
use common\models\finance\Requestattachment;
use common\models\finance\Requesttype;
use common\models\finance\Obligationtype;
use common\models\procurement\Division;
use common\models\system\Comment;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Request */

$this->title = 'Signed Documents Uploader';
$this->params['breadcrumbs'][] = ['label' => 'Request', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainer',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>

    
    <?php

    echo Html::button('<i class="glyphicon glyphicon-file"></i> View', ['value' => Url::to(['request/uploadsigned']), 'title' => Yii::t('app', "Attachment"), 'class'=>'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonUploadAttachments']);
    ?>
    