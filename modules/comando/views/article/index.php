<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить статью', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'title',
            'html_title',
            [
                'attribute' => 'category_title',
                'value' => function($model) {
                    return $model->category->title;
                }
            ],
            'description',
            'key_words',
            [
                'attribute' => 'is_active',
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [0 => 'Нет', 1 => 'Да'], ['class' => 'form-control', 'prompt' => '-']),
                'value' => function ($model) {
                    return $model->is_active ? 'Да' : 'Нет';
                }
            ],
            [
                'attribute' => 'is_comment_enabled',
                'filter' => Html::activeDropDownList($searchModel, 'is_comment_enabled', [0 => 'Нет', 1 => 'Да'], ['class' => 'form-control', 'prompt' => '-']),
                'value' => function ($model) {
                    return $model->is_comment_enabled ? 'Да' : 'Нет';
                }
            ],
            'timestamp',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>

</div>
