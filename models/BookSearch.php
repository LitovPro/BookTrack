<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BookSearch represents the model behind the search form of `app\models\Book`.
 */
class BookSearch extends Book
{
    /**
     * @var string Search term for title, ISBN, or author
     */
    public $search;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'year'], 'integer'],
            [['title', 'isbn', 'description', 'search'], 'safe'],
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
        $query = Book::find()->with('authors');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
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
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'isbn', $this->isbn])
            ->andFilterWhere(['like', 'description', $this->description]);

        // Search in title, ISBN, or author names
        if (!empty($this->search)) {
            $query->andWhere([
                'or',
                ['like', 'title', $this->search],
                ['like', 'isbn', $this->search],
                ['like', 'description', $this->search],
                ['exists', Author::find()
                    ->from(['a' => Author::tableName()])
                    ->innerJoin(['ba' => '{{%book_author}}'], 'ba.author_id = a.id')
                    ->where('ba.book_id = ' . Book::tableName() . '.id')
                    ->andWhere(['like', 'a.full_name', $this->search])
                ]
            ]);
        }

        return $dataProvider;
    }
}

