<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_request_attachment".
 *
 * @property integer $request_attachment_id
 * @property integer $request_id
 * @property string $name
 * @property integer $attachment_id
 */
class Requestattachment extends \yii\db\ActiveRecord
{
    public $pdfFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_request_attachment';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('procurementdb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'attachment_id'], 'required'],
            [['request_id', 'status_id', 'attachment_id'], 'integer'],
            [['pdfFile'], 'file'],
            [['filename'], 'safe'],
            [['filename'], 'string', 'max' => 100],
            //[['filename'], 'file', 'extensions'=>'pdf', 'skipOnEmpty' => true] //
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_attachment_id' => 'Request Attachment ID',
            'request_id' => 'Request ID',
            'filename' => 'Filename',
            'status_id' => 'Status',
            'attachment_id' => 'Attachment ID',
        ];
    }
    
    public function getAttachment()  
    {  
      return $this->hasOne(Attachment::className(), ['attachment_id' => 'attachment_id']);  
    } 
    
    public function getRequest()  
    {  
      return $this->hasOne(Request::className(), ['request_id' => 'request_id']);  
    }
    
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['request_attachment_id' => 'record_id']);
    }
    
    public static function hasAttachment($id)
    {
        $model  = Requestattachment::findOne($id);
        
        //$file = 'uploads/finance/request/' . $model->request->request_number.'/'. $model->filename;
        //clearstatcache();
        if($model->filename != NULL){
            $file = 'uploads/finance/request/' . $model->request->request_number.'/'. $model->filename;
        }else{
            $file = false;
        }

        if(file_exists($file)) {
            return 1;
        } else {
            return 0;
        }
    }
}
