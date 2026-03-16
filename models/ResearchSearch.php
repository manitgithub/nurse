<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Research;

/**
 * ResearchSearch represents the model behind the search form of `app\models\Research`.
 */
class ResearchSearch extends Research
{
    /**
     * @var string
     */
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'year', 'work_status', 'authors', 'funding_status', 'funding_source', 'duration', 'publish_level', 'tier', 'result_publication', 'globalSearch'], 'safe'],
            [['budget', 'latitude', 'longitude'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Research::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'budget' => $this->budget,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'work_status', $this->work_status])
            ->andFilterWhere(['like', 'authors', $this->authors])
            ->andFilterWhere(['like', 'funding_status', $this->funding_status])
            ->andFilterWhere(['like', 'funding_source', $this->funding_source])
            ->andFilterWhere(['like', 'duration', $this->duration])
            ->andFilterWhere(['like', 'publish_level', $this->publish_level])
            ->andFilterWhere(['like', 'tier', $this->tier])
            ->andFilterWhere(['like', 'result_publication', $this->result_publication]);

        if (!empty($this->globalSearch)) {
            $query->andFilterWhere([
                'or',
                ['like', 'title', $this->globalSearch],
                ['like', 'authors', $this->globalSearch],
                ['like', 'funding_source', $this->globalSearch],
                ['like', 'year', $this->globalSearch],
            ]);
        }

        return $dataProvider;
    }
}
