<?php

use app\models\CommentSearch;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $comment_model app\models\Comment */

$this->title = $model->html_title;
$this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['category/view', 'id' => $model->category->id]];
$this->params['breadcrumbs'][] = $model->title;

$this->registerMetaTag(['name' => 'description', 'content' => $model->description]);
$this->registerMetaTag(['name' => 'Keywords', 'content' => $model->key_words])
?>

    <div class="article-view">
        <h1><?= Html::encode($model->title) ?></h1>
        <div class="article-content">
            <?= $model->content; ?>
        </div>
    </div>
    <hr/>

    <h3>Статьи из категории "<?= $model->category->title; ?>"</h3>
    <div class="article-random col-sm-12">
        <?php foreach ($model->getRandomArticles(3) as $value) : ?>
            <div class="col-sm-4">
                <div class="preview_img">
                    <?php if ($value->preview_picture) : ?>
                        <?= Html::img("@web/" . $value->preview_picture, ['class' => 'preview-img']); ?>
                    <?php else : ?>
                        <div class="preview-img"></div>
                    <?php endif; ?>
                </div>
                <div class="col-sm-12">
                    <a href="<?= Url::to(['article/view', 'id' => $value->id]); ?>"><?= $value->title; ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <br/>
    <hr/>

    <div class="comment-view">
        <h3>Комментарии</h3>
        <?= ListView::widget([
            'dataProvider' => (new CommentSearch())->search(['CommentSearch' => ['fid_article' => $model->id, 'is_active' => 1]]),
            'summary' => false,
            'itemView' => '_comments'
        ]);
        ?>
    </div>
    <hr/>

<?php if ($model->is_comment_enabled) : ?>
    <h3>Оставьте свой комментарий</h3>
    <div class="comment-create">
        <?php $form = ActiveForm::begin(['action' => Url::to(['comment/create'])]); ?>

        <?= $form->field($comment_model, 'comment_text')->textarea(['rows' => 8]) ?>
        <?= $form->field($comment_model, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($comment_model, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($comment_model, 'article_id')->hiddenInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php endif; ?>