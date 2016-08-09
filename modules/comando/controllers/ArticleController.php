<?php
namespace app\modules\comando\controllers;
use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class ArticleController extends Controller
{
    const IMAGE_DIR = '/uploads/ckeditor/';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Administrator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post())) {
            $model->preview_input = UploadedFile::getInstance($model, 'preview_input');
            if (!empty($model->preview_input)) {
                $model->preview_picture = 'preview/' . $model->preview_input->baseName . '.' . $model->preview_input->extension;
            }
            if ($model->save()) {
                Yii::warning("Article changed. Attributes = " . print_r($model->attributes));
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->preview_input = UploadedFile::getInstance($model, 'preview_input');
            if (!empty($model->preview_input)) {
                $model->preview_picture = 'preview/' . $model->preview_input->baseName . '.' . $model->preview_input->extension;
            }
            if ($model->save()) {
                Yii::warning("Article changed. Attributes = " . print_r($model->attributes));
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Загрузка изображений через CKEditor
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpload()
    {
        $uploadedFile = UploadedFile::getInstanceByName('upload');
        $mime = FileHelper::getMimeType($uploadedFile->tempName);
        $file = $uploadedFile->name;

        $url = Yii::$app->urlManager->createAbsoluteUrl(static::IMAGE_DIR. $file);
        $uploadPath = Yii::getAlias('@webroot') . static::IMAGE_DIR . $file;
        if ($uploadedFile == null) {
            $message = "Файл не загружен";
        } else if ($uploadedFile->size == 0) {
            $message = "Файл пуст";
        } else if ($mime != "image/jpeg" && $mime != "image/png") {
            $message = "Тип файла должен быть JPG или PNG";
        } else if ($uploadedFile->tempName == null) {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        } else {
            $message = "";
            $move = $uploadedFile->saveAs($uploadPath);
            if (!$move) {
                $message = "Ошибка перемещения файла. Проверьте скрипт и разрешения на чтение/запись";
            }
        }
        $funcNum = $_GET['CKEditorFuncNum'];
        return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}