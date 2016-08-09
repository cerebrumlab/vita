<?php
/* @var $this yii\web\View */
use yii\bootstrap\Html;

/* @var \app\models\Article $model */
//TODO alt для превью'
?>
<hr/>
<div class="container">
    <?php if (!empty($model->preview_picture)) : ?>
    <div class="col-sm-3">
        <?= Html::img("@web/" . $model->preview_picture, ['class' => 'preview-img']); ?>
    </div>
    <?php endif; ?>
    <div class="col-sm-5">
        <p>
            <a href="<?= Yii::$app->urlManager->createUrl(['article/view', 'id' => $model->id]); ?>"><?= $model->title; ?></a>
        </p>
        <?= $model->preview; ?>
        <br/>
        <p><a href="<?= Yii::$app->urlManager->createUrl(['category/view', 'id' => $model->category_id]); ?>"><?= $model->category->title; ?></a></p>
    </div>
</div>