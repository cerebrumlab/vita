<?php

namespace app\controllers;

use app\models\Article;
use app\models\ArticleSearch;
use app\models\Category;
use app\models\SearchForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\Response;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(['ArticleSearch' => ['is_active' => 1]]);
        return $this->render('index', ['$searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionMap()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        $dom = new \DOMDocument("1.0", "utf-8");
        $root = $dom->createElement("urlset");
        $root->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
        $dom->appendChild($root);

        $categories = Category::findAll(['is_active' => 1]);
        $articles = Article::findAll(['is_active' => 1]);
        $items = array_merge($categories, $articles);

        foreach ($items as $item) {
            $url = $dom->createElement("url");

            $loc = $dom->createElement("loc");
            if ($item instanceof Article) {
                $loc->appendChild($dom->createTextNode(Url::to(['article/view', 'id' => $item->id], true)));
            } else {
                $loc->appendChild($dom->createTextNode(Url::to(['category/view', 'id' => $item->id], true)));
            }

            $lastmod = $dom->createElement("lastmod");
            $lastmod->appendChild($dom->createTextNode($item->timestamp));

            $changefreq = $dom->createElement("changefreq");
            $changefreq->appendChild($dom->createTextNode("monthly"));

            $priority = $dom->createElement("priority");
            $priority->appendChild($dom->createTextNode("0.5"));

            $url->appendChild($loc);
            $url->appendChild($lastmod);
            $url->appendChild($changefreq);
            $url->appendChild($priority);
            $root->appendChild($url);
        }

        return $dom->saveXML();
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSearch()
    {
        $model = new SearchForm();
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        return $this->render('search', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSitemap()
    {
        return $this->render('sitemap');
    }

}
