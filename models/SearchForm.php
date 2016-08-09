<?php
namespace app\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;

class SearchForm extends Model
{
    public $search_text;

    public function rules()
    {
        return [
            [['search_text'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'search_text' => 'Поиск...'
        ];
    }

    public function search()
    {
        $query = Article::find()->andFilterWhere(['like', 'title', $this->search_text]);
        return new ActiveDataProvider(['query' => $query]);
    }
}