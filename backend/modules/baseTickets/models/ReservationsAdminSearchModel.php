<?php
namespace backend\modules\Reservations\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\Products\models\Products;
use backend\modules\Products\models\Productscities;
use backend\modules\Products\models\Productscountires;
use yii\helpers\ArrayHelper;
use backend\modules\Products\models\Graylinepartners;

class ReservationsAdminSearchModel extends Products {
    public $id;
    public $uuid;
    public $source;
    public $data;
    public $invoice_date;
    public $reservation_date;

    public function rules() {
        return [
            [['id'], 'integer'],
            [['uuid'], 'string'],
            [['source'], 'string', 'max' => 255],
            [['data'], 'string', 'max' => 1000],
            [['invoice_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['reservation_date'], 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    public function search($params) {
        $query = Reservations::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        $this->load($params);

        return $dataProvider;
    }
}

