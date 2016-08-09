<?php
/* @var \app\models\Article $model */
use yii\bootstrap\Html;

?>
<div class="col-sm-12 last-added">
    <div class="preview_img">
        <?php if ($model->preview_picture) : ?>
            <?= Html::img("@web/" . $model->preview_picture, ['class' => 'preview-img']); ?>
        <?php endif; ?>
    </div>
    <div class="col-sm-12">
        <a href="<?= Yii::$app->urlManager->createUrl(['article/view', 'id' => $model->id]); ?>"><?= $model->title; ?> </a>
        <br/>

    </div>
</div> 