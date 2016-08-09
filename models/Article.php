<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "Articles".
 *
 * @property string $id
 * @property string $title
 * @property string $slug
 * @property string $html_title
 * @property string $description
 * @property string $content
 * @property string $key_words
 * @property boolean $is_active
 * @property boolean $is_comment_enabled
 * @property string $category_id
 * @property string $preview_picture
 * @property string $timestamp
 *
 * @property Category $category
 * @property string $preview_input
 * @property string $preview
 * @property string $shortPreview
 */
class Article extends ActiveRecord
{

    /**
     * @var UploadedFile
     */
    public $preview_input;

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

    public function transactions()
    {
        return [
            'default' => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Articles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'content', 'key_words', 'category_id', 'slug', 'html_title'], 'required'],
            [['content'], 'string'],
            [['is_active', 'category_id', 'is_comment_enabled'], 'integer'],
            [['timestamp'], 'safe'],
            [['title', 'html_title'], 'string', 'max' => 100],
            [['preview_picture'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 500],
            [['preview_input'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['key_words'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Статья',
            'html_title' => 'Заголовок (HTML)',
            'description' => 'Описание',
            'content' => 'Текст статьи',
            'key_words' => 'Ключевые слова',
            'is_active' => 'Доступно',
            'is_comment_enabled' => 'Разрешить комментарии',
            'category_id' => 'Категория',
            'slug' => 'Короткое название (ЧПУ)',
            'preview_input' => 'Превью',
            'timestamp' => 'Последнее обновление',
        ];
    }


    public function afterSave($insert, $changedAttributes)
    {
        $this->preview_input = UploadedFile::getInstance($this, 'preview_input');
        if (!empty($this->preview_input)) {
            $this->preview_input->saveAs($this->preview_picture);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getRandomArticles($n)
    {
        return $this->find()->where(['not in', 'id', [$this->id]])->limit($n)->orderBy("RAND()")->all();
    }

    public static function getLastArticles($n)
    {
        return new ActiveDataProvider(['query' => Article::find()->orderBy(['id' => SORT_DESC])->limit($n), 'pagination' => false]);
    }

    public function getPreview()
    {
        return $this->getFirstNSentence(2);
    }

    public function getShortPreview()
    {
        return $this->getFirstNSentence(1);
    }

    /**
     * @return string
     */
    private function getFirstNSentence($n)
    {
        $raw_text = strip_tags($this->content);
        $statements = explode('.', $raw_text);
        $preview = [];
        for ($i = 0; $i < $n; $i++) {
            if (isset($statements[$i])) {
                $preview[] = trim($statements[$i]);
            }
        }
        return implode('. ', $preview) . "...";
    }
}
