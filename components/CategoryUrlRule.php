<?php
namespace app\components;

use app\models\Category;
use yii\web\NotFoundHttpException;
use yii\web\UrlRule;

class CategoryUrlRule extends UrlRule
{

    public function createUrl($manager, $route, $params)
    {

        if ($route === 'category/view' && isset($params['id'])) {
            $model = $this->findModel($params['id']);
            return $model->slug . (isset($params['page']) ? "?page=" . $params['page'] : "");
        }
        
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->pathInfo;

        if ($model = $this->findCategory($pathInfo)) {
            return [
                'category/view', [
                    'id' => $model->id,
                    'model' => $model
                ]
            ];
        }
        return false;
    }

    /**
     * @param string $id
     * @return Category | bool
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * @param $slug
     * @return array|bool|null|Category
     */
    protected function findCategory($slug)
    {
        if (($model = Category::find()->where(['slug' => $slug])->one()) !== null) {
            return $model;
        } else {
            return false;
        }
    }


}