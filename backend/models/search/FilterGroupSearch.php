<?php

namespace backend\models\search;

use common\models\Filter;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class FilterGroupSearch
 * @package backend\models\search
 */
class FilterGroupSearch extends Filter
{

    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'status', 'order', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string'],
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
        $query = Filter::find()->alias('f')->joinWith(['filterTranslate']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['name'] = [
            'asc' => ['ft.name' => SORT_ASC],
            'desc' => ['ft.name' => SORT_DESC],
            'default' => SORT_ASC,
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query
            ->andFilterWhere([
                'f.id' => $this->id,
                'f.parent_id' => 0,
                'f.status' => $this->status,
                'f.order' => $this->order,
                'f.created_by' => $this->created_by,
                'f.updated_by' => $this->updated_by,
                'f.created_at' => $this->created_at,
                'f.updated_at' => $this->updated_at,
                'f.updated_at' => $this->updated_at,
            ]);

        $query->andFilterWhere(['like', 'ft.name', $this->name]);

        return $dataProvider;
    }

}
