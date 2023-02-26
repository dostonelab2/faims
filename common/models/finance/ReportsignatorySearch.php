<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\Reportsignatory;

/**
 * ReportsignatorySearch represents the model behind the search form about `common\models\finance\Reportsignatory`.
 */
class ReportsignatorySearch extends Reportsignatory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_signatory_id', 'division_id', 'user1', 'user2', 'user3', 'active_user'], 'integer'],
            [['scope', 'box'], 'safe'],
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
        $query = Reportsignatory::find();

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
            'report_signatory_id' => $this->report_signatory_id,
            'division_id' => $this->division_id,
            'user1' => $this->user1,
            'user2' => $this->user2,
            'user3' => $this->user3,
            'active_user' => $this->active_user,
        ]);

        $query->andFilterWhere(['like', 'scope', $this->scope])
            ->andFilterWhere(['like', 'box', $this->box]);

        return $dataProvider;
    }
}
