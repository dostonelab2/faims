<?php

namespace common\models\procurementplan;

use common\models\procurementplan\Itemcategory;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\Unitofmeasure;

use Yii;

/**
 * This is the model class for table "tbl_ppmp_item".
 *
 * @property integer $ppmp_item_id
 * @property integer $ppmp_id
 * @property string $code
 * @property string $description
 * @property integer $quantity
 * @property integer $unit
 * @property double $estimated_budget
 * @property integer $mode_of_procurement
 * @property integer $q1
 * @property integer $q2
 * @property integer $q3
 * @property integer $q4
 * @property integer $q5
 * @property integer $q6
 * @property integer $q7
 * @property integer $q8
 * @property integer $q9
 * @property integer $q10
 * @property integer $q11
 * @property integer $q12
 *
 * @property Ppmp $ppmp
 * @property PpmpItemSched[] $ppmpItemScheds
 */
class Ppmpitem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ppmp_item';
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
            [['ppmp_id', 'item_id', 'item_category_id', 'ppmp_item_category_id', 'code', 'description', 'quantity', 'unit', 'estimated_budget', 'mode_of_procurement', 'active'], 'required'],
            [['ppmp_id', 'ppmp_item_category_id','quantity', 'unit', 'mode_of_procurement', 'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'active'], 'integer'],
            [['description', 'item_specification'], 'string'],
            [['estimated_budget', 'cost'], 'number'],
            [['code'], 'string', 'max' => 20],
            [['ppmp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ppmp::className(), 'targetAttribute' => ['ppmp_id' => 'ppmp_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ppmp_item_id' => 'Ppmp Item ID',
            'ppmp_id' => 'Ppmp ID',
            'item_id' => 'Item',
            'item_category_id' => 'Item Category',
            'ppmp_item_category_id' => 'Category',
            'code' => 'Code',
            'description' => 'Description',
            'quantity' => 'Quantity',
            'unit' => 'Unit of Measure',
            'cost' => 'Unit Cost',
            'estimated_budget' => 'Estimated Budget',
            'mode_of_procurement' => 'Mode Of Procurement',
            'q1' => 'Q1',
            'q2' => 'Q2',
            'q3' => 'Q3',
            'q4' => 'Q4',
            'q5' => 'Q5',
            'q6' => 'Q6',
            'q7' => 'Q7',
            'q8' => 'Q8',
            'q9' => 'Q9',
            'q10' => 'Q10',
            'q11' => 'Q11',
            'q12' => 'Q12',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPpmp()
    {
        return $this->hasOne(Ppmp::className(), ['ppmp_id' => 'ppmp_id']);
    }
    
    public function getUnitofmeasure()
    {
        return $this->hasOne(Unitofmeasure::className(), ['unit_of_measure_id' => 'unit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPpmpItemScheds()
    {
        return $this->hasMany(PpmpItemSched::className(), ['ppmp_item_id' => 'ppmp_item_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemcategory()
    {
        return $this->hasOne(Itemcategory::className(), ['item_category_id' => 'item_category_id']);
    }
    
    public function getTotalqty()
    {
        $total = $this->q1 + $this->q2 + $this->q3 + $this->q4 + $this->q5 + $this->q6 + $this->q7 + $this->q8 + $this->q9 + $this->q10 + $this->q11 + $this->q12;
        return $total;
    }
    
    public function getTotalamount()
    {
        $totalamount = $this->getTotalqty() * $this->cost;
        return $totalamount;
    }
    
    public function getMonthItemQuantity($month)
    {
        $queryPpmpItems = $this->find()
                            ->where(['item_id' => $this->item_id,'active' => 1])
                            ->sum($month);
        return $queryPpmpItems;
    }
    
    public function getItemQuantity()
    {
        $queryPpmpItems = $this->find()
                            ->where(['item_id' => $this->item_id,'active' => 1])
                            ->sum('q1+q2+q3+q4+q5+q6+q7+q8+q9+q10+q11+q12');
        return $queryPpmpItems;
    }
}
