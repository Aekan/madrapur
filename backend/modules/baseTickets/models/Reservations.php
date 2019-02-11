<?php
namespace backend\modules\Reservations\models;

use Yii;
use yii\db\ActiveRecord;

class Reservations extends ActiveRecord {

    public static function tableName() {
        return 'mres_1802'; //TODO: dynamic table name
    }

    public function rules() {
        return [
            [['id'], 'integer'],
            [['uuid'], 'string', 'max' => 32],
            [['source'], 'string', 'max' => 255],
            [['data'], 'string', 'max' => 1000],
            [['invoice_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['reservation_date'], 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'uuid' => Yii::t('app', 'UUID'),
            'source' => Yii::t('app', 'Forrás'),
            'data' => Yii::t('app', 'JSON adat'),
            'invoice_date' => Yii::t('app', 'Számla kelte'),
            'reservation_date' => Yii::t('app', 'Foglalás dátuma'),
        ];
    }

    public function afterFind() {
        parent::afterFind();

        if(Yii::$app->language != Yii::$app->sourceLanguage && !empty($this->translation))
            $this->attributes = $this->translation->attributes;
    }

    public static function getList() {
        return ArrayHelper::map(self::find()->all(), 'id', 'invoice_date');
    }

}