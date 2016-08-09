<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Banner;
use app\models\Category;
use app\models\SearchForm;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header class="header">
    <div class="container">
        <div class="col-sm-2">
            <a href="/" class="header-link"><?= Yii::$app->name; ?></a>
        </div>
        <div class="col-sm-1">
            <?= Html::a('О нас', ['site/about']); ?>
        </div>
        <div class="col-sm-1">
        </div>
        <div class="col-sm-8">
            <?= Banner::getHeaderBanner(); ?>
        </div>
    </div>
</header>

<div class="wrap">
    <div class="container">
        <?php
        NavBar::begin(['options' => ['class' => 'navbar-default navbar']]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => Category::getActiveCategories(),
        ]);
        NavBar::end();
        ?>
        <?php echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="col-sm-9">
            <?= $content ?>
        </div>
        <div class="col-sm-3">
            <form class="form-horizontal" action="<?= Yii::$app->urlManager->createUrl(['/site/search']); ?>">
                <?= Html::activeTextInput(new SearchForm(), 'search_text', ['class' => 'form-control', 'placeholder' => 'Поиск...']); ?>
            </form>
            <div class="sidebar-banner">
                <?= Banner::getSidebarBanner(); ?>
            </div>
            <p><b>Последние публикации</b></p>
            <?= \yii\widgets\ListView::widget([
                'dataProvider' => \app\models\Article::getLastArticles(5),
                'itemView' => '//article/_last',
                'emptyText' => false,
                'summary' => false
            ]) ?>

        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="col-sm-3">
            Copyright
        </div>
        <div class="col-sm-6">
            <p><b>Категории</b></p>
            <?php foreach (Category::getActiveCategories() as $value) : ?>
                <div class="col-sm-4">
                    <p><a href="<?= Yii::$app->urlManager->createUrl($value['url']); ?>"><?= $value['label']; ?></a></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-sm-3">
            <?= Html::a("Карта сайта", ['site/sitemap']); ?>
        </div>
    </div>
</footer>

<span id="top-link-block" class="hidden">
    <a href="#" class="well well-sm" onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i>
    </a>
</span>

<?php $this->registerJs("if (($(window).height() + 100) < $(document).height()) {
        $('#top-link-block').removeClass('hidden').affix({
            offset: {top: 100}
        });
    }
"); ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
