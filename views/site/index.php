<?php

use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name;
?>
<div class="category-view">

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '//category/_preview'
    ]) ?>

    <hr/>

</div>
