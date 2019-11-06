<?php

namespace backend\modules\Product\models;

use backend\modules\MadActiveRecord\models\MadActiveRecord;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Default model for the `Product` module
 *
 * @property int $id [int(11)]
 * @property string $name [varchar(255)]
 * @property string $icon [varchar(255)]
 * @property string $type [varchar(255)]
 * @property int $price [bigint(15)]
 */
class AddOn extends MadActiveRecord {

    public static function tableName() {
        return 'modulusProductAddOns';
    }

    public function search($params) {
        $what = ['*'];
        $from = self::tableName();
        $where = "1";

        $rows = self::aSelect(AddOn::class, $what, $from, $where);

        $dataProvider = new ActiveDataProvider([
            'query' => $rows,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        return $dataProvider;
    }

    public function rules() {
        return [
            [['id'], 'integer'],
            [
                [
                    'name',
                    'icon',
                    'type',
                ],
                'string',
                'max' => 255
            ],
            [['price','hufPrice'], 'integer'],
            [
                [
                    'name',
                    'icon',
                    'type',
                    'price',
                ],
                'required',
            ],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', ' '),
        ];
    }
}
