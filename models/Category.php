<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Categories".
 *
 * @property string $id
 * @property string $title
 * @property string $html_title
 * @property string $slug
 * @property integer $is_active
 * @property string $timestamp
 */
class Category extends ActiveRecord
{

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'app\components\SlugBehaviour',
                'in_attribute' => 'title',
                'out_attribute' => 'slug',
                'translit' => true
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'html_title'], 'required'],
            [['parent_id'], 'integer'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['is_active'], 'integer'],
            [['timestamp'], 'safe'],
            [['title', 'slug', 'html_title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id'=> "Родительская категория",
            'title' => 'Категория',
            'html_title' => 'Заголовок (HTML)',
            'slug' => 'Короткое название',
            'is_active' => 'Доступно',
            'timestamp' => 'Последнее изменение',
        ];
    }

    public function transactions()
    {
        return [
            'default' => self::OP_ALL,
        ];
    }

    public static function getActiveCategories()
    {
        $categories = Category::find()->where(['is_active' => 1])->all();
        $result = [];
        foreach ($categories as $value) {
            $result[] = ['url' => ['category/view', 'id' => $value->id], 'label' => $value->title];

        }
        return $result;
    }

    /**
     * @return ActiveDataProvider
     */
    public function getArticles()
    {
        return new ActiveDataProvider([
            'query' => Article::find()->where(['category_id' => $this->id,  'is_active' => 1,]),
            'sort'=>array(
                'defaultOrder'=>['id' => SORT_DESC],)
        ]);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

}
