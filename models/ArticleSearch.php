<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * ArticleSearch represents the model behind the search form about `app\models\Article`.
 */
class ArticleSearch extends Article
{
    public $category_title;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'category_id', 'is_comment_enabled'], 'integer'],
            [['title', 'description', 'content', 'key_words', 'timestamp', 'category_title', 'html_title'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return ['category_title' => 'Категория'] + parent::attributeLabels();
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
        $query = ArticleSearch::find();
        $query->joinWith(['category']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>array(
                'defaultOrder'=>['id' => SORT_DESC],)
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $dataProvider->sort->attributes['category_title'] = [
            'asc' => [Category::tableName() . '.title' => SORT_ASC],
            'desc' => [Category::tableName() . '.title' => SORT_DESC],
        ];
        $query->andFilterWhere([
            'id' => $this->id,
            Article::tableName() . '.is_active' => $this->is_active,
            'is_comment_enabled' => $this->is_comment_enabled,
            'category_id' => $this->category_id,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', Category::tableName() . '.title', $this->category_title])
            ->andFilterWhere(['like', 'html_title', $this->html_title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'key_words', $this->key_words]);

        return $dataProvider;
    }
}
