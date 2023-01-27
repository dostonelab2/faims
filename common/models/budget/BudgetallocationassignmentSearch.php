<?php

namespace common\models\budget;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\budget\Budgetallocationassignment;

/**
 * BudgetallocationassignmentSearch represents the model behind the search form about `common\models\budget\Budgetallocationassignment`.
 */
class BudgetallocationassignmentSearch extends Budgetallocationassignment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['budget_allocation_assignment_id', 'budget_allocation_id', 'request_id', 'budget_allocation_item_id', 'budget_allocation_item_detail_id'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Budgetallocationassignment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'budget_allocation_assignment_id' => $this->budget_allocation_assignment_id,
            'budget_allocation_id' => $this->budget_allocation_id,
            'request_id' => $this->request_id,
            'budget_allocation_item_id' => $this->budget_allocation_item_id,
            'budget_allocation_item_detail_id' => $this->budget_allocation_item_detail_id,
            'amount' => $this->amount,
        ]);

        return $dataProvider;
    }
}
