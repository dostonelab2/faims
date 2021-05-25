<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\Requestpayrollitem;

/**
 * RequestpayrollitemSearch represents the model behind the search form about `common\models\finance\Requestpayrollitem`.
 */
class RequestpayrollitemSearch extends Requestpayrollitem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_payroll_item_id', 'request_payroll_id', 'request_id', 'osdv_id', 'dv_id', 'creditor_id', 'status_id', 'active'], 'integer'],
            [['name', 'particulars', 'osdv_attributes'], 'safe'],
            [['amount', 'tax'], 'number'],
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
        $query = Requestpayrollitem::find();

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
            'request_payroll_item_id' => $this->request_payroll_item_id,
            'request_payroll_id' => $this->request_payroll_id,
            'request_id' => $this->request_id,
            'osdv_id' => $this->osdv_id,
            'dv_id' => $this->dv_id,
            'creditor_id' => $this->creditor_id,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'status_id' => $this->status_id,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'particulars', $this->particulars])
            ->andFilterWhere(['like', 'osdv_attributes', $this->osdv_attributes]);

        return $dataProvider;
    }
}
