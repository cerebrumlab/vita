<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid) {
            return ['class' => $model->is_active ? 'success' : 'danger'];
        },
        'columns' => [
            'comment_text',
            'username',
            'email',
            'article.title',
            [
                'attribute' => 'is_active',
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [0 => 'Нет', 1 => 'Да'], ['class' => 'form-control', 'prompt' => '-']),
                'value' => function ($model) {
                    return $model->is_active ? 'Да' : 'Нет';
                }
            ],
            'timestamp',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{activation} {deactivation} {delete}',
                'buttons' => [
                    'activation' => function($url, $model, $key) {
                        $options = [
                            'title' => 'Отобразить',
                            'aria-label' => 'Отобразить',
                            'data-confirm' => 'Отобразить комментарий?',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return $model->is_active ? '' : Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    },
                    'deactivation' => function($url, $model, $key) {
                        $options = [
                            'title' => 'Скрыть',
                            'aria-label' => 'Скрыть',
                            'data-confirm' => 'Скрыть комментарий?',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return $model->is_active ? Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, $options) : '';
                    }
                ]
            ],
        ],
    ]); ?>

</div>
