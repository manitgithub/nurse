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
    public $expertise_id;
    public $active_year_be;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personnel_code', 'fullname', 'track', 'department_id', 'subject_group_id', 'status', 'job_position', 'gender', 'academic_position', 'qualification_id', 'contract_type_id', 'resignation_year', 'expertise_id', 'active_year_be'], 'safe'],
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
            // Default to active personnel if no status filter applied
            if (!isset($params[$this->formName()]['status'])) {
                $query->andWhere(['status' => 1]);
            }
            return $dataProvider;
        }

        // grid filtering conditions
        if ($this->status === null || $this->status === '') {
            $query->andWhere(['{{%personnels}}.status' => 1]);
        } else {
            $query->andFilterWhere(['{{%personnels}}.status' => $this->status]);
        }
        $query->andFilterWhere(['like', 'personnel_code', $this->personnel_code])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['track' => $this->track])
            ->andFilterWhere(['job_position' => $this->job_position])
            ->andFilterWhere(['department_id' => $this->department_id])
            ->andFilterWhere(['subject_group_id' => $this->subject_group_id])
            ->andFilterWhere(['gender' => $this->gender])
            ->andFilterWhere(['academic_position' => $this->academic_position])
            ->andFilterWhere(['qualification_id' => $this->qualification_id])
            ->andFilterWhere(['contract_type_id' => $this->contract_type_id])
            ->andFilterWhere(['resignation_year' => $this->resignation_year]);

        if ($this->expertise_id) {
            $query->joinWith('expertises')
                ->andWhere(['{{%expertises}}.id' => $this->expertise_id]);
        }

        if ($this->active_year_be) {
            $adYear = (int)$this->active_year_be - 543;
            $query->andWhere(['<=', 'start_date', $adYear . '-12-31']);
            $query->andWhere([
                'or',
                ['resignation_year' => null],
                ['>=', 'resignation_year', $this->active_year_be]
            ]);
        }

        return $dataProvider;
    }
}
