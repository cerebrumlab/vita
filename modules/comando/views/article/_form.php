<?php

use app\models\Category;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'html_title')->textInput(['maxlength' => true]) ?>
    <?php if (!$model->isNewRecord) : ?>
        <?= Html::img("@web/" . $model->preview_picture, ['class' => 'article-preview-img']); ?>
    <?php endif; ?>
    <?= $form->field($model, 'preview_input')->fileInput() ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->where(['is_active' => 1])->all(), 'id', 'title')) ?>
    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'standart',
        'clientOptions' => [
            'filebrowserUploadUrl' => '/comando/article/upload'
        ]
    ]) ?>
    <?= $form->field($model, 'key_words')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'is_active')->checkbox() ?>
    <?= $form->field($model, 'is_comment_enabled')->checkbox() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
