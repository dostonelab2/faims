<?php
 
use kartik\widgets\FileInput;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\finance\Requestattachment;

$form = ActiveForm::begin([
    'options'=>['enctype'=>'multipart/form-data'] // important
]);


echo $form->field($model, 'pdfFile')->widget(FileInput::classname(), [
    //'disabled' => $model->request->owner() ? false : true, //this also disables buttons on fileActionSettings below
    'pluginOptions' => [
        'allowedFileExtensions'=>['pdf'],
        'previewFileType' => 'any',
        'overwriteInitial' => true,
        //'initialPreview' =>[Requestattachment::checkFile($model->attachment_id)],
        'initialPreview' => [
            //Yii::getAlias('@uploads') . "finance/request/" . $model->request->request_number. "/" . $model->filename,
            "/uploads/finance/request/" . $model->requestattachment->request->request_number. "/" . $model->filename,
        ],
        'initialPreviewAsData'=>true,
        'initialPreviewConfig'=>[
            ['type' => "pdf", 
            'size' => 20000, 
            //'caption' => $model->attachment->name, 
            //'url' => Url::to(['request/deleteattachment']), 
            //'key' => $model->request_attachment_signed_id
            ]
        ],
        
        'fileActionSettings' => [
            'showDrag' => false,
            //'showZoom' => false,
            //'showUpload' => $model->request->owner() ? true : false,
            //'showDelete' => $model->request->owner() ? true : false,
        ],
        'uploadUrl' => Url::to(['uploads/']), 
    ]
]);

//if($model->request->owner()){
    echo Html::submitButton('Upload', [
        'class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'float: right;']
    );
//}

//if(Yii::$app->user->can('access-finance-verification') || (Yii::$app->user->identity->username == 'Admin')){
    /*echo Html::submitButton('Mark as VERIFIED', [
        'class'=>'btn btn-primary', 'style' => 'float: right;']
    );*/
    
    //echo Html::button('Mark as VERIFIED', ['value' => Url::to(['request/markverified', 'id'=>$model->request_attachment_id]), 'title' => 'Mark as Verified', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px; float: right;', 'id'=>'buttonMarkVerify']);
//}

ActiveForm::end(); ?>

<br><br>
<script>
$(document).ready(function(){
    $(".fileinput-upload-button").hide();
    //$(".file-caption").change(function(){
        //alert($(".file-caption-name").prop('title'));
    //});
});
</script>