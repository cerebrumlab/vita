<?php

use app\models\Banner;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Баннеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить баннер', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'position_id',
                'value' => function ($model) {
                    return Banner::$BANNER_POSITION[$model->position_id];
                }
            ],
            'file_name',
            [
                'attribute' => 'is_active',
                'value' => function ($model) {
                    return ($model->is_active) ? 'Да' : 'Нет';
                }
            ],
            [
                'format' => 'html',
                'label' => 'Изображение',
                'value' => function ($model) {
                    return Html::img('@web/' . $model->file_name, ['style' => 'height: 50px']);
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
