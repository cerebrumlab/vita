<?php
/* @var $this yii\web\View */

use app\models\Category;
use app\models\CategorySearch;
use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = Yii::$app->name . " - Карта сайта";
?>

<div class="category-view">

    <h1>Карта сайта</h1>

    <?= ListView::widget([
        'dataProvider' => (new CategorySearch())->search([]),
        'itemView' => '_map'
    ]) ?>

    <hr/>

</div>
