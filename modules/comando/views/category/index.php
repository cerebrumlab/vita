<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'title',
            'html_title',
            'slug',
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
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>

</div>
