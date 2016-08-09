<?php
namespace app\components;

use app\models\Article;
use app\models\Category;
use yii\web\NotFoundHttpException;
use yii\web\UrlRule;

class ArticleUrlRule extends UrlRule
{
    public function createUrl($manager, $route, $params)
    {
        if ($route == 'article/view' && isset($params['id'])) {
            $model = $this->findModel($params['id']);
            return $model->category->slug . "/" . $model->slug;
        }
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->pathInfo;

        $pathInfoArray = explode('/', $pathInfo);
        if (sizeof($pathInfoArray) != 2) {
            return false;
        }
        list($category_title, $article_title) = $pathInfoArray;

        if (!empty($category_title) && !empty($article_title) && ($category = $this->findCategory($category_title))) {
            $model = $this->findArticle($article_title);
            if (!$model) {
                throw new NotFoundHttpException("Статья не найдена");
            }
            return [
                'article/view', [
                    'id' => $model->id,
                    'model' => $model
                ]
            ];
        }
        return false;
    }

    /**
     * @param $slug
     * @return Category
     */
    protected function findCategory($slug)
    {
        if (($model = Category::find()->where(['slug' => $slug])->one()) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * @param $slug
     * @return Article
     */
    protected function findArticle($slug)
    {
        if (($model = Article::find()->where(['slug' => $slug])->one()) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Article the loaded model
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }
}