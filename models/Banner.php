<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * This is the model class for table "Banners".
 *
 * @property string $id
 * @property integer $position_id
 * @property resource $file_name
 * @property integer $is_active
 * @property string $timestamp
 */
class Banner extends ActiveRecord
{
    public static $BANNER_POSITION = [
        0 => 'Шапка сайта',
        1 => 'Боковая панель'
    ];

    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Banners';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position_id', 'file_name', 'is_active'], 'required'],
            [['position_id', 'is_active'], 'integer'],
            [['timestamp'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position_id' => 'Позиция на странице',
            'file_name' => 'Имя файла',
            'is_active' => 'Активно',
            'file' => 'Файл',
            'timestamp' => 'Последнее изменение',
        ];
    }

    public function transactions()
    {
        return [
            'default' => self::OP_ALL,
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->file = UploadedFile::getInstance($this, 'file');
        $this->file->saveAs($this->file_name);
        parent::afterSave($insert, $changedAttributes);
    }

    public static function getHeaderBanner()
    {
        $src = self::getRandomBannerByPosition(0);
        return $src ? Html::img("@web/" . $src, ['class' => 'header-banner-img']) : "";
    }

    public static function getSidebarBanner()
    {
        $src = self::getRandomBannerByPosition(1);
        return $src ? Html::img("@web/" . $src, ['class' => 'sidebar-banner-img']) : "";
    }

    /**
     * @return mixed
     */
    private static function getRandomBannerByPosition($position_id)
    {
        $banners = Banner::find()->where(['position_id' => $position_id, 'is_active' => 1])->all();
        $count = count($banners);
        $random_banner = rand(0, $count - 1);
        return isset($banners[$random_banner]) ? $banners[$random_banner]->file_name : null;
    }

}
