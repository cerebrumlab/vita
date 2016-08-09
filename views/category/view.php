<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = $model->html_title;
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="category-view">

    <h1><?= Html::encode($model->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $model->getArticles(),
        'itemView' => '_preview'
    ]) ?>

    <hr/>

</div>
