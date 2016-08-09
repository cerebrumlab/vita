<?php

use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\SearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name;
?>
<div class="search-view">
    <h2>Результаты поиска по запросу '<?= $model->search_text; ?>'</h2>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '//category/_preview'
    ]) ?>

    <hr/>

</div>
