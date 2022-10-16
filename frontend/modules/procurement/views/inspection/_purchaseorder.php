<?php
/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/16/2018
 * Time: 9:16 AM
 */

use kartik\helpers\Panel;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput  ;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\procurement\BidsDetailSearch;
use common\components\Functions;
use common\models\procurement\Supplier;
use common\modules\pdfprint;
use common\components\MyPrint;
$func = new Functions();
$con =  Yii::$app->db;

/*$command2 = $con->createCommand("CALL `fais-procurement`.`spGenerateSupplier`(".$model->purchase_request_id.")");
$supplier = $command2->queryAll();
$listSupplier = ArrayHelper::map($supplier,'supplier_id','supplier_name');                                                                                                  iveForm */

?>

<div class="purchaserorder-form">

    <h1>Test Module</h1>

</div>


