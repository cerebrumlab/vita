<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */

$this->title = 'Редактировать баннер: ' . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = "#" . $model->id;
?>
<div class="banner-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::img("@web/" . $model->file_name, ['class' => 'banner-preview']); ?></p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>