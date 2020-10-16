<?php

namespace common\models\finance;

use Yii;

use common\models\procurement\Type;
use common\models\procurement\Expenditureclass;
/**
 * This is the model class for table "tbl_osdv".
 *
 * @property integer $osdv_id
 * @property integer $request_id
 * @property integer $type_id
 * @property integer $expenditure_class_id
 * @property integer $status_id
 * @property integer $created_by
 */
class Osdv extends \yii\db\ActiveRecord
{
    public $cashAvailable;
    public $subjectToAda;
    public $supportingDocumentsComplete;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_osdv';
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
            [['request_id', 'type_id', 'status_id', 'created_by'], 'required'],
            [['create_date'], 'safe'],
            [['request_id', 'type_id', 'expenditure_class_id', 'status_id', 'created_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'osdv_id' => 'Osdv ID',
            'request_id' => 'Request ID',
            'type_id' => 'Type ID',
            'expenditure_class_id' => 'Expenditure Class ID',
            'status_id' => 'Status ID',
            'create_date' => 'Date Created',
            'created_by' => 'Created By',
        ];
    }
    
    public function getOs()  
    {  
      return $this->hasOne(Os::className(), ['osdv_id' => 'osdv_id']);  
    }
    
    public function getAllotments()  
    {  
      return $this->hasMany(Osallotment::className(), ['osdv_id' => 'osdv_id'])->andOnCondition(['active' => 1]);  
    }
    
    public function getAccounttransactions()  
    {  
      return $this->hasMany(Accounttransaction::className(), ['request_id' => 'osdv_id']);  
    }
    
    public function getDv()  
    {  
      return $this->hasOne(Dv::className(), ['osdv_id' => 'osdv_id']);  
    }
    
    public function getRequest()  
    {  
      return $this->hasOne(Request::className(), ['request_id' => 'request_id']);  
    }
    
    public function getType()  
    {  
      return $this->hasOne(Type::className(), ['type_id' => 'type_id']);  
    }
    
    public function getExpenditureClass()  
    {  
      return $this->hasOne(Expenditureclass::className(), ['expenditure_class_id' => 'expenditure_class_id']);  
    }
    
    public function getNetamount()
    {
        return $this->request->amount;
    }
    
    public function getTax()
    {
        return $this->request->amount;
    }
    
    public function getGrossamount()
    {
        return $this->request->amount;
    }
}
