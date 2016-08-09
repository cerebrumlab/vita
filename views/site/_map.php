<?php

/** @var $model \app\models\Category */
/** @var $article \app\models\Article */

use yii\bootstrap\Html;

?>
<hr/>
<div class='col-sm-offset-1'><p><?= Html::a($model->title, ['category/view', 'id' => $model->id]); ?></p></div>
<?php foreach ($model->getArticles()->models as $article) : ?>
    <div class='col-sm-offset-2'><p><?= Html::a($article->title, ['article/view', 'id' => $article->id]); ?></p></div>
<?php endforeach; ?>

<br/><br/>
