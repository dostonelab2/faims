<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_rfq".
 *
 * @property integer $rfq_id
 * @property integer $purchase_request_id
 * @property integer $supplier_id
 * @property string $rfq_number
 */
class Rfq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rfq';
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
            [['purchase_request_id', 'supplier_id', 'rfq_number'], 'required'],
            [['purchase_request_id', 'supplier_id'], 'integer'],
            [['rfq_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rfq_id' => 'Rfq ID',
            'purchase_request_id' => 'Purchase Request ID',
            'supplier_id' => 'Supplier ID',
            'rfq_number' => 'Rfq Number',
        ];
    }
}
