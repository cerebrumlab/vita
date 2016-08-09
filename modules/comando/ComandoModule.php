<?php

namespace app\modules\comando;

use yii\base\Module;

class ComandoModule extends Module
{
    public $defaultRoute = 'article';

    public $controllerNamespace = 'app\modules\comando\controllers';

    public function init()
    {
        \Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = [
            'js' => ['jquery.min.js']
        ];
        parent::init();
    }
}
