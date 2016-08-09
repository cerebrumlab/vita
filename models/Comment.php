<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "Comments".
 *
 * @property string $id
 * @property string $comment_text
 * @property string $article_id
 * @property boolean $is_active
 * @property string $username
 * @property string $email
 * @property string $timestamp
 *
 * @property Article $article
 */
class Comment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_text', 'article_id', 'username', 'email'], 'required'],
            [['is_active'], 'boolean'],
            [['article_id'], 'integer'],
            [['timestamp', 'is_active'], 'safe'],
            [['username'], 'string', 'max' => 80],
            [['email'], 'string', 'max' => 100],
            [['comment_text'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_text' => 'Комментарий',
            'article_id' => 'Статья',
            'is_active' => 'Модерация пройдена',
            'timestamp' => 'Дата добавления',
            'username' => 'Ваше имя',
            'email' => 'Ваш email',
        ];
    }

    public function transactions()
    {
        return [
            'default' => self::OP_ALL,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }
}
