<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_budget_allocation_assignment".
 *
 * @property integer $budget_allocation_assignment_id
 * @property integer $request_id
 * @property integer $budget_allocation_item_id
 * @property integer $budget_allocation_item_detail_id
 * @property double $amount
 */
class Budgetallocationassignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_budget_allocation_assignment';
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
            [['request_id', 'budget_allocation_item_id', 'budget_allocation_item_detail_id', 'amount'], 'required'],
            [['request_id', 'budget_allocation_item_id', 'budget_allocation_item_detail_id'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'budget_allocation_assignment_id' => 'Budget Allocation Assignment ID',
            'request_id' => 'Request ID',
            'budget_allocation_item_id' => 'Budget Allocation Item ID',
            'budget_allocation_item_detail_id' => 'Budget Allocation Item Detail ID',
            'amount' => 'Amount',
        ];
    }

    public function getRequest()  
    {  
      return $this->hasOne(Request::className(), ['request_id' => 'request_id']);  
    }
}
