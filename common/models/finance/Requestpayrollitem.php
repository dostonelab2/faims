<?php

namespace common\models\finance;

use common\models\cashier\Creditor;
use Yii;

/**
 * This is the model class for table "tbl_request_payroll_item".
 *
 * @property integer $request_payroll_item_id
 * @property integer $request_payroll_id
 * @property integer $request_id
 * @property integer $osdv_id
 * @property integer $dv_id
 * @property integer $creditor_id
 * @property string $name
 * @property string $particulars
 * @property double $amount
 * @property double $tax
 * @property integer $status_id
 * @property string $osdv_attributes
 * @property integer $active
 */
class Requestpayrollitem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_request_payroll_item';
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
            [['request_payroll_id', 'request_id', 'osdv_id', 'dv_id', 'creditor_id', 'particulars', 'amount', 'tax', 'status_id', 'active'], 'required'],
            [['request_payroll_id', 'request_id', 'osdv_id', 'dv_id', 'creditor_id', 'status_id', 'active'], 'integer'],
            [['particulars'], 'string'],
            [['amount', 'tax'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['osdv_attributes'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_payroll_item_id' => 'Request Payroll Item ID',
            'request_payroll_id' => 'Request Payroll ID',
            'request_id' => 'Request ID',
            'osdv_id' => 'Osdv ID',
            'dv_id' => 'Dv ID',
            'creditor_id' => 'Creditor ID',
            'name' => 'Name',
            'particulars' => 'Particulars',
            'amount' => 'Amount',
            'tax' => 'Tax',
            'status_id' => 'Status ID',
            'osdv_attributes' => 'Osdv Attributes',
            'active' => 'Active',
        ];
    }
    
        /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditor()
    {
        return $this->hasOne(Creditor::className(), ['creditor_id' => 'creditor_id']);
    }
    
        /**
     * @return \yii\db\ActiveQuery
     */
    public function getDv()
    {
        return $this->hasOne(Dv::className(), ['dv_id' => 'dv_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestpayroll()
    {
        return $this->hasOne(Requestpayroll::className(), ['request_payroll_id' => 'request_payroll_id']);
    }
}
