<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Personnel;

/**
 * PersonnelSearch represents the model behind the search form of `app\models\Personnel`.
 */
class PersonnelSearch extends Personnel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personnel_code', 'fullname', 'track', 'department_id', 'subject_group_id', 'status', 'job_position'], 'safe'],
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
        $query = Personnel::find()->with(['qualification', 'contractType', 'department']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'personnel_code', $this->personnel_code])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['track' => $this->track])
            ->andFilterWhere(['job_position' => $this->job_position])
            ->andFilterWhere(['department_id' => $this->department_id])
            ->andFilterWhere(['subject_group_id' => $this->subject_group_id])
            ->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }
}
